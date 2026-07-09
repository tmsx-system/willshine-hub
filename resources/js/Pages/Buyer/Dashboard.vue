<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import BuyerLayout from '@/Layouts/BuyerLayout.vue';
import StatCard from '@/Components/UI/StatCard.vue';
import Badge from '@/Components/UI/Badge.vue';
import ProgressBar from '@/Components/UI/ProgressBar.vue';

defineOptions({ layout: BuyerLayout });

const props = defineProps({
    buyer:         { type: Object, required: true },
    stats:         { type: Object, required: true },
    recent_orders: { type: Array,  required: true },
});

const greeting = computed(() => {
    const h = new Date().getHours();
    if (h < 12) return 'Selamat Pagi';
    if (h < 15) return 'Selamat Siang';
    if (h < 18) return 'Selamat Sore';
    return 'Selamat Malam';
});

const nextTierPoints = 2500;
const progressPct = computed(() => Math.min(100, (props.stats.reward_points / nextTierPoints) * 100));

function formatRupiah(n) {
    return 'Rp ' + n.toLocaleString('id-ID');
}

const quickActions = [
    { label: 'Buat Pesanan', href: '/buyer/catalog', icon: '🛒', color: 'bg-pink-50 text-pink-700 hover:bg-pink-100 border-pink-100' },
    { label: 'Lihat Katalog', href: '/buyer/catalog', icon: '📦', color: 'bg-blue-50 text-blue-700 hover:bg-blue-100 border-blue-100' },
    { label: 'Cek Reward', href: '/buyer/rewards', icon: '🎁', color: 'bg-amber-50 text-amber-700 hover:bg-amber-100 border-amber-100' },
    { label: 'Riwayat Order', href: '/buyer/orders', icon: '📋', color: 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border-emerald-100' },
];
</script>

<template>
    <div class="px-4 sm:px-6 lg:px-8 py-6 max-w-7xl mx-auto space-y-6">

        <!-- ── Welcome Banner ─────────────────────────── -->
        <div class="relative overflow-hidden rounded-3xl p-6 lg:p-8 animate-fade-in-up" style="background: linear-gradient(135deg, #be185d 0%, #ec4899 60%, #f472b6 100%); box-shadow: 0 12px 40px rgba(190,24,93,0.3);">
            <!-- Pattern overlay -->
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 28px 28px;" />
            <!-- Blob -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/4" />

            <div class="relative flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <p class="text-pink-200 text-sm font-medium">{{ greeting }}, 👋</p>
                    <h2 class="text-white text-xl sm:text-2xl font-extrabold mt-0.5">{{ buyer.contact }}</h2>
                    <p class="text-pink-200 text-sm mt-0.5">{{ buyer.name }}</p>

                    <!-- Tier badge -->
                    <div class="mt-3 inline-flex items-center gap-2 bg-white/20 backdrop-blur border border-white/30 rounded-full px-3 py-1">
                        <span class="text-sm">⭐</span>
                        <span class="text-white text-xs font-bold">Tier {{ buyer.tier }}</span>
                    </div>
                </div>

                <div class="flex gap-3">
                    <Link href="/buyer/catalog" class="inline-flex items-center gap-2 bg-white text-pink-700 font-bold px-5 py-2.5 rounded-2xl text-sm hover:shadow-lg hover:shadow-pink-900/20 transition-all duration-200 hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        Pesan Sekarang
                    </Link>
                </div>
            </div>
        </div>

        <!-- ── Stat Cards ──────────────────────────────── -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <StatCard
                label="Alokasi Stok"
                :value="stats.stock_allocation"
                suffix=" box"
                icon="stock"
                color="pink"
                :trend="12"
                :delay="0"
            />
            <StatCard
                label="Pesanan Pending"
                :value="stats.pending_orders"
                icon="orders"
                color="amber"
                :delay="100"
            />
            <StatCard
                label="Total Belanja"
                :value="formatRupiah(stats.total_spend)"
                icon="spend"
                color="blue"
                :trend="8"
                :delay="200"
            />
            <StatCard
                label="Reward Points"
                :value="stats.reward_points"
                suffix=" pts"
                icon="reward"
                color="green"
                :trend="5"
                :delay="300"
            />
        </div>

        <!-- ── Quick Actions ───────────────────────────── -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 animate-fade-in-up delay-200">
            <Link
                v-for="action in quickActions"
                :key="action.href"
                :href="action.href"
                :class="['flex flex-col items-center gap-2 p-4 rounded-2xl border font-semibold text-sm transition-all duration-200 hover:-translate-y-0.5', action.color]"
            >
                <span class="text-2xl">{{ action.icon }}</span>
                <span class="text-xs font-semibold">{{ action.label }}</span>
            </Link>
        </div>

        <!-- ── Two-column Layout (desktop) ────────────── -->
        <div class="grid lg:grid-cols-5 gap-6">

            <!-- Recent Orders (wider) -->
            <div class="lg:col-span-3 card p-6 animate-fade-in-up delay-300">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-base font-bold text-gray-900">Pesanan Terbaru</h3>
                    <Link href="/buyer/orders" class="text-xs font-semibold text-pink-600 hover:text-pink-800 transition-colors">
                        Lihat Semua →
                    </Link>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="pb-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wide">Order ID</th>
                                <th class="pb-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wide hidden sm:table-cell">Tanggal</th>
                                <th class="pb-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wide">Total</th>
                                <th class="pb-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wide">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr
                                v-for="order in recent_orders"
                                :key="order.id"
                                class="hover:bg-pink-50/50 transition-colors"
                            >
                                <td class="py-3.5 pr-4">
                                    <p class="font-semibold text-gray-800 text-sm">{{ order.id }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ order.items }} item</p>
                                </td>
                                <td class="py-3.5 pr-4 hidden sm:table-cell">
                                    <p class="text-sm text-gray-600">{{ new Date(order.date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) }}</p>
                                </td>
                                <td class="py-3.5 pr-4 text-right">
                                    <p class="font-semibold text-gray-800 text-sm">{{ formatRupiah(order.total) }}</p>
                                </td>
                                <td class="py-3.5 text-right">
                                    <Badge :status="order.status" size="sm" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Reward Progress (narrower) -->
            <div class="lg:col-span-2 space-y-4">
                <!-- Tier & Points card -->
                <div class="card p-6 animate-fade-in-up delay-400">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-bold text-gray-900">Program Reward</h3>
                        <Link href="/buyer/rewards" class="text-xs font-semibold text-pink-600 hover:text-pink-800 transition-colors">Detail →</Link>
                    </div>

                    <!-- Tier display -->
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-300 to-amber-500 flex items-center justify-center text-2xl" style="box-shadow: 0 4px 12px rgba(245,158,11,0.3);">
                            ⭐
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium">Tier Saat Ini</p>
                            <p class="text-lg font-extrabold" style="background: linear-gradient(135deg, #f59e0b, #d97706); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                                GOLD
                            </p>
                        </div>
                        <div class="ml-auto text-right">
                            <p class="text-xs text-gray-400 font-medium">Poin Aktif</p>
                            <p class="text-2xl font-extrabold text-pink-600">{{ stats.reward_points.toLocaleString('id-ID') }}</p>
                        </div>
                    </div>

                    <!-- Progress to Platinum -->
                    <ProgressBar
                        :current="stats.reward_points"
                        :target="nextTierPoints"
                        label="Menuju Tier Platinum"
                    />

                    <p class="mt-3 text-xs text-gray-400 text-center">
                        Butuh <span class="font-bold text-pink-600">{{ (nextTierPoints - stats.reward_points).toLocaleString('id-ID') }} poin</span> lagi untuk Platinum ✨
                    </p>
                </div>

                <!-- Quick redeem hint -->
                <div class="card-pink p-5 rounded-2xl animate-fade-in-up delay-500">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">🎁</span>
                        <div>
                            <p class="text-sm font-bold text-gray-800">Reward Tersedia!</p>
                            <p class="text-xs text-gray-600 mt-0.5">Anda memiliki poin cukup untuk menukarkan <span class="font-semibold text-pink-700">Diskon 5%</span> atau <span class="font-semibold text-pink-700">Free Ongkir</span>.</p>
                            <Link href="/buyer/rewards" class="mt-3 inline-flex btn-secondary text-xs px-3 py-1.5">
                                Tukar Reward
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>