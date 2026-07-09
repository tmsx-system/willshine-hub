<script setup>
import { ref, computed } from 'vue';
import BuyerLayout from '@/Layouts/BuyerLayout.vue';
import ProgressBar from '@/Components/UI/ProgressBar.vue';

defineOptions({ layout: BuyerLayout });

const props = defineProps({
    points:      { type: Number, required: true },
    tier:        { type: String, required: true },
    next_tier:   { type: String, required: true },
    next_points: { type: Number, required: true },
    rewards:     { type: Array,  required: true },
    history:     { type: Array,  required: true },
});

const activeTab = ref('rewards'); // rewards | history
const redeemingId = ref(null);
const redeemedIds = ref([]);

const tierMilestones = [
    { name: 'Bronze', points: 0,    icon: '🥉' },
    { name: 'Silver', points: 500,  icon: '🥈' },
    { name: 'Gold',   points: 1000, icon: '🥇' },
    { name: 'Platinum',points: 2500,icon: '💎' },
];

const tierIcon = {
    Bronze: '🥉', Silver: '🥈', Gold: '🥇', Platinum: '💎',
};

const categoryIcon = {
    Discount: '🏷️', Shipping: '🚚', Gift: '🎁', Cashback: '💰', Service: '⚡',
};

const categoryColor = {
    Discount: 'bg-pink-50 border-pink-100',
    Shipping: 'bg-blue-50 border-blue-100',
    Gift:     'bg-amber-50 border-amber-100',
    Cashback: 'bg-emerald-50 border-emerald-100',
    Service:  'bg-purple-50 border-purple-100',
};

function canRedeem(reward) {
    return props.points >= reward.points && !redeemedIds.value.includes(reward.id);
}

async function redeem(reward) {
    if (!canRedeem(reward)) return;
    redeemingId.value = reward.id;
    await new Promise(r => setTimeout(r, 1200));
    redeemedIds.value.push(reward.id);
    redeemingId.value = null;
}

function formatDate(d) {
    return new Date(d).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
}
</script>

<template>
    <div class="px-4 sm:px-6 lg:px-8 py-6 max-w-7xl mx-auto space-y-6">

        <!-- ── Header Hero Card ────────────────────────── -->
        <div
            class="relative overflow-hidden rounded-3xl p-6 lg:p-8 animate-fade-in-up"
            style="background: linear-gradient(135deg, #be185d 0%, #ec4899 60%, #f472b6 100%); box-shadow: 0 16px 48px rgba(190,24,93,0.3);"
        >
            <!-- Dot pattern -->
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;" />
            <!-- Blob -->
            <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/10 rounded-full blur-3xl" />

            <div class="relative grid sm:grid-cols-2 gap-6 items-center">
                <!-- Points display -->
                <div>
                    <p class="text-pink-200 text-sm font-medium mb-1">Total Poin Aktif</p>
                    <div class="flex items-end gap-3">
                        <p class="text-6xl font-black text-white tracking-tight">{{ points.toLocaleString('id-ID') }}</p>
                        <p class="text-pink-200 text-lg font-bold pb-2">pts</p>
                    </div>
                    <div class="mt-3 flex items-center gap-2">
                        <span class="text-2xl">{{ tierIcon[tier] ?? '⭐' }}</span>
                        <div>
                            <p class="text-white font-bold text-base">Tier {{ tier }}</p>
                            <p class="text-pink-200 text-xs">Poin cukup untuk {{ tier === 'Gold' ? 'tukar 3 reward!' : 'upgrade tier' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Progress to next tier -->
                <div class="bg-white/15 backdrop-blur rounded-2xl p-5 border border-white/20">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-white text-sm font-bold">Menuju Tier {{ next_tier }}</p>
                        <span class="text-lg">{{ tierIcon[next_tier] ?? '💎' }}</span>
                    </div>
                    <div class="progress-track bg-white/20">
                        <div
                            class="progress-fill"
                            :style="{ width: Math.min(100, (points / next_points) * 100) + '%' }"
                        />
                    </div>
                    <div class="flex justify-between mt-2">
                        <p class="text-pink-200 text-xs">{{ points.toLocaleString('id-ID') }} pts</p>
                        <p class="text-pink-200 text-xs">{{ next_points.toLocaleString('id-ID') }} pts</p>
                    </div>
                    <p class="mt-2 text-center text-white text-xs font-semibold">
                        Kurang <span class="text-yellow-300">{{ (next_points - points).toLocaleString('id-ID') }} poin</span> lagi ✨
                    </p>
                </div>
            </div>

            <!-- Tier Milestones Strip -->
            <div class="relative mt-6 pt-5 border-t border-white/20">
                <div class="relative">
                    <!-- Track -->
                    <div class="absolute top-4 left-0 right-0 h-0.5 bg-white/20 rounded-full" />
                    <div
                        class="absolute top-4 left-0 h-0.5 bg-white rounded-full transition-all duration-1000"
                        :style="{ width: Math.min(100, (points / 2500) * 100) + '%' }"
                    />
                    <div class="grid grid-cols-4 relative">
                        <div v-for="milestone in tierMilestones" :key="milestone.name" class="flex flex-col items-center gap-1.5">
                            <div
                                :class="['w-8 h-8 rounded-full flex items-center justify-center text-sm z-10 transition-all duration-300', points >= milestone.points ? 'bg-white shadow-lg scale-110' : 'bg-white/20']"
                            >
                                {{ milestone.icon }}
                            </div>
                            <p class="text-xs font-bold" :class="points >= milestone.points ? 'text-white' : 'text-white/50'">{{ milestone.name }}</p>
                            <p class="text-[10px] font-medium" :class="points >= milestone.points ? 'text-pink-200' : 'text-white/30'">{{ milestone.points.toLocaleString() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Tab Navigation ──────────────────────────── -->
        <div class="card p-1.5 flex gap-1 animate-fade-in-up delay-100">
            <button
                @click="activeTab = 'rewards'"
                :class="['flex-1 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200', activeTab === 'rewards' ? 'bg-pink-600 text-white shadow-sm' : 'text-gray-600 hover:bg-pink-50 hover:text-pink-700']"
            >
                🎁 Tukar Reward
            </button>
            <button
                @click="activeTab = 'history'"
                :class="['flex-1 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200', activeTab === 'history' ? 'bg-pink-600 text-white shadow-sm' : 'text-gray-600 hover:bg-pink-50 hover:text-pink-700']"
            >
                📜 Riwayat Poin
            </button>
        </div>

        <!-- ── Rewards Grid ─────────────────────────────── -->
        <div v-if="activeTab === 'rewards'" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 animate-fade-in">
            <div
                v-for="(reward, i) in rewards"
                :key="reward.id"
                :class="['card border p-5 flex flex-col gap-4 card-hover animate-fade-in-up', categoryColor[reward.category]]"
                :style="{ animationDelay: i * 80 + 'ms' }"
            >
                <!-- Header -->
                <div class="flex items-start justify-between gap-3">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-2xl shadow-sm">
                        {{ categoryIcon[reward.category] ?? '🎁' }}
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 bg-white rounded-full text-gray-500">{{ reward.category }}</span>
                </div>

                <!-- Name + Points cost -->
                <div>
                    <h3 class="font-bold text-gray-900 text-base">{{ reward.name }}</h3>
                    <div class="flex items-center gap-1.5 mt-1">
                        <svg class="w-3.5 h-3.5 text-pink-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <span class="text-sm font-bold text-pink-700">{{ reward.points.toLocaleString('id-ID') }} poin</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Berlaku s/d {{ new Date(reward.valid).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) }}</p>
                </div>

                <!-- Redeem button -->
                <div class="mt-auto">
                    <div v-if="redeemedIds.includes(reward.id)" class="w-full py-2.5 rounded-xl bg-emerald-100 text-emerald-700 text-sm font-bold text-center">
                        ✓ Berhasil Ditukar!
                    </div>
                    <button
                        v-else
                        @click="redeem(reward)"
                        :disabled="!canRedeem(reward) || redeemingId === reward.id"
                        :class="[
                            'w-full py-2.5 rounded-xl text-sm font-bold transition-all duration-200',
                            canRedeem(reward)
                                ? 'btn-primary justify-center'
                                : 'bg-gray-100 text-gray-400 cursor-not-allowed'
                        ]"
                    >
                        <svg v-if="redeemingId === reward.id" class="animate-spin h-4 w-4 mr-1 inline" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                        {{ redeemingId === reward.id ? 'Memproses...' : canRedeem(reward) ? 'Tukar Sekarang' : `Kurang ${(reward.points - points).toLocaleString()} poin` }}
                    </button>
                </div>
            </div>
        </div>

        <!-- ── Points History ───────────────────────────── -->
        <div v-else class="card overflow-hidden animate-fade-in">
            <div class="p-5 border-b border-gray-100">
                <h3 class="font-bold text-gray-900">Riwayat Transaksi Poin</h3>
            </div>
            <div class="divide-y divide-gray-50">
                <div
                    v-for="(item, i) in history"
                    :key="i"
                    class="flex items-center gap-4 px-5 py-4 hover:bg-pink-50/40 transition-colors"
                >
                    <!-- Type icon -->
                    <div
                        :class="['w-10 h-10 rounded-2xl flex items-center justify-center text-lg flex-shrink-0',
                            item.type === 'earn'   ? 'bg-emerald-50' :
                            item.type === 'redeem' ? 'bg-pink-50'    : 'bg-amber-50']"
                    >
                        {{ item.type === 'earn' ? '⬆️' : item.type === 'redeem' ? '🎁' : '⭐' }}
                    </div>

                    <!-- Description + date -->
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ item.description }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ formatDate(item.date) }}</p>
                    </div>

                    <!-- Points value -->
                    <div class="text-right flex-shrink-0">
                        <p
                            :class="['text-base font-extrabold', item.points > 0 ? 'text-emerald-600' : 'text-pink-600']"
                        >
                            {{ item.points > 0 ? '+' : '' }}{{ item.points.toLocaleString('id-ID') }}
                        </p>
                        <p class="text-xs text-gray-400">poin</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
