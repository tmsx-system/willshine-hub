<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use App\Models\RewardRedemption;
use App\Models\RewardTransaction;
use App\Services\RewardService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class BuyerRewardController extends Controller
{
    public function index(Request $request): Response
    {
        $account = $request->user('customer');

        if (
            !$account->can_view_reward
            || !Schema::hasTable('rewards')
            || !Schema::hasTable('reward_transactions')
            || !Schema::hasTable('reward_redemptions')
        ) {
            return Inertia::render('Buyer/Rewards', [
                'points' => 0,
                'tier' => 'Bronze',
                'next_tier' => 'Silver',
                'next_points' => 500,
                'rewards' => [],
                'history' => [],
                'pending_redemptions' => [],
            ]);
        }

        $points = app(RewardService::class)->balance($account);
        [$tier, $nextTier, $nextPoints] = $this->tierData($points);

        return Inertia::render('Buyer/Rewards', [
            'points' => $points,
            'tier' => $tier,
            'next_tier' => $nextTier,
            'next_points' => $nextPoints,
            'rewards' => Reward::query()
                ->where('is_active', true)
                ->where(function ($query) {
                    $query->whereNull('valid_until')->orWhere('valid_until', '>=', now()->toDateString());
                })
                ->orderBy('display_order')
                ->orderBy('points_required')
                ->get()
                ->map(fn (Reward $reward): array => [
                    'id' => $reward->id,
                    'name' => $reward->name,
                    'category' => $reward->category,
                    'points' => $reward->points_required,
                    'desc' => $reward->description,
                    'valid' => $reward->valid_until?->toDateString(),
                ]),
            'history' => RewardTransaction::query()
                ->where('customer_account_id', $account->id)
                ->where('status', 'approved')
                ->latest()
                ->limit(25)
                ->get()
                ->map(fn (RewardTransaction $transaction): array => [
                    'type' => $transaction->points >= 0 ? 'earn' : 'redeem',
                    'description' => $transaction->description ?: ucfirst($transaction->type),
                    'points' => $transaction->points,
                    'date' => $transaction->created_at?->toDateString(),
                ]),
            'pending_redemptions' => RewardRedemption::query()
                ->where('customer_account_id', $account->id)
                ->where('status', 'pending')
                ->pluck('reward_id')
                ->all(),
        ]);
    }

    public function redeem(Request $request): RedirectResponse
    {
        $account = $request->user('customer');

        abort_unless($account->can_view_reward, 403);

        if (!Schema::hasTable('rewards') || !Schema::hasTable('reward_redemptions')) {
            return back()->withErrors(['reward' => 'Tabel reward belum tersedia. Jalankan migration terlebih dahulu.']);
        }

        $data = $request->validate([
            'reward_id' => ['required', 'integer'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $reward = Reward::query()
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('valid_until')->orWhere('valid_until', '>=', now()->toDateString());
            })
            ->findOrFail($data['reward_id']);

        $balance = app(RewardService::class)->balance($account);

        if ($balance < $reward->points_required) {
            return back()->withErrors(['reward' => 'Poin reward belum cukup.']);
        }

        $hasPending = RewardRedemption::query()
            ->where('customer_account_id', $account->id)
            ->where('reward_id', $reward->id)
            ->where('status', 'pending')
            ->exists();

        if ($hasPending) {
            return back()->withErrors(['reward' => 'Reward ini sudah menunggu approval.']);
        }

        RewardRedemption::create([
            'redemption_number' => 'RR-' . now()->format('YmdHis') . '-' . $account->id . '-' . strtoupper(Str::random(4)),
            'customer_account_id' => $account->id,
            'reward_id' => $reward->id,
            'points_spent' => $reward->points_required,
            'status' => 'pending',
            'notes' => $data['notes'] ?? null,
        ]);

        return back()->with('status', 'Reward berhasil diajukan dan menunggu approval sales.');
    }

    private function tierData(int $points): array
    {
        return match (true) {
            $points >= 2500 => ['Platinum', 'Platinum', 2500],
            $points >= 1000 => ['Gold', 'Platinum', 2500],
            $points >= 500 => ['Silver', 'Gold', 1000],
            default => ['Bronze', 'Silver', 500],
        };
    }
}
