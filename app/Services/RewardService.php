<?php

namespace App\Services;

use App\Models\CustomerAccount;
use App\Models\PurchaseRequest;
use App\Models\RewardRedemption;
use App\Models\RewardTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class RewardService
{
    public function balance(CustomerAccount|int $account): int
    {
        if (!Schema::hasTable('reward_transactions')) {
            return 0;
        }

        $accountId = $account instanceof CustomerAccount ? $account->id : $account;

        return (int) RewardTransaction::query()
            ->where('customer_account_id', $accountId)
            ->where('status', 'approved')
            ->sum('points');
    }

    public function earnFromPurchaseRequest(PurchaseRequest $request): ?RewardTransaction
    {
        if (!Schema::hasTable('reward_transactions')) {
            return null;
        }

        if (!$request->customer_account_id || (float) $request->grand_total <= 0) {
            return null;
        }

        $points = (int) floor((float) $request->grand_total / 1000);

        if ($points <= 0) {
            return null;
        }

        return DB::transaction(function () use ($request, $points): ?RewardTransaction {
            $exists = RewardTransaction::query()
                ->where('purchase_request_id', $request->id)
                ->where('type', 'earn')
                ->exists();

            if ($exists) {
                return null;
            }

            $balanceAfter = $this->balance($request->customer_account_id) + $points;

            return RewardTransaction::create([
                'customer_account_id' => $request->customer_account_id,
                'purchase_request_id' => $request->id,
                'type' => 'earn',
                'points' => $points,
                'balance_after' => $balanceAfter,
                'status' => 'approved',
                'reference' => $request->request_number,
                'description' => "Earn points from approved order {$request->request_number}",
            ]);
        });
    }

    public function approveRedemption(RewardRedemption $redemption, int $processedBy): RewardTransaction
    {
        return DB::transaction(function () use ($redemption, $processedBy): RewardTransaction {
            $redemption->loadMissing('reward');

            if ($redemption->status !== 'pending') {
                throw new RuntimeException('Reward redemption is not pending.');
            }

            $balance = $this->balance($redemption->customer_account_id);

            if ($balance < $redemption->points_spent) {
                throw new RuntimeException('Customer reward points are not enough.');
            }

            $transaction = RewardTransaction::create([
                'customer_account_id' => $redemption->customer_account_id,
                'reward_id' => $redemption->reward_id,
                'type' => 'redeem',
                'points' => -1 * $redemption->points_spent,
                'balance_after' => $balance - $redemption->points_spent,
                'status' => 'approved',
                'reference' => $redemption->redemption_number,
                'description' => 'Redeemed reward: ' . ($redemption->reward?->name ?: $redemption->redemption_number),
            ]);

            $redemption->update([
                'status' => 'approved',
                'processed_by' => $processedBy,
                'processed_at' => now(),
            ]);

            return $transaction;
        });
    }
}
