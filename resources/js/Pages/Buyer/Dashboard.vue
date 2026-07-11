<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import BuyerLayout from '@/Layouts/BuyerLayout.vue';
import StatCard from '@/Components/UI/StatCard.vue';
import Badge from '@/Components/UI/Badge.vue';
import ProgressBar from '@/Components/UI/ProgressBar.vue';
import { ClipboardList, Gift, PackageSearch, ShoppingBag, Sparkles, Star } from 'lucide-vue-next';

defineOptions({ layout: BuyerLayout });

const props = defineProps({
    buyer: { type: Object, required: true },
    stats: { type: Object, required: true },
    recent_orders: { type: Array, required: true },
});

const greeting = computed(() => {
    const hour = new Date().getHours();
    if (hour < 12) return 'Selamat Pagi';
    if (hour < 15) return 'Selamat Siang';
    if (hour < 18) return 'Selamat Sore';
    return 'Selamat Malam';
});

const nextTierPoints = 2500;
const progressPct = computed(() => Math.min(100, (props.stats.reward_points / nextTierPoints) * 100));

const quickActions = [
    { label: 'Buat Pesanan', href: '/buyer/catalog', icon: ShoppingBag, color: 'bg-pink-50 text-pink-700 hover:bg-pink-100 border-pink-100' },
    { label: 'Lihat Katalog', href: '/buyer/catalog', icon: PackageSearch, color: 'bg-blue-50 text-blue-700 hover:bg-blue-100 border-blue-100' },
    { label: 'Cek Reward', href: '/buyer/rewards', icon: Gift, color: 'bg-amber-50 text-amber-700 hover:bg-amber-100 border-amber-100' },
    { label: 'Riwayat Order', href: '/buyer/orders', icon: ClipboardList, color: 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border-emerald-100' },
];

function formatRupiah(value) {
    return 'Rp ' + value.toLocaleString('id-ID');
}
</script>

<template>
    <div class="px-4 sm:px-6 lg:px-8 py-6 max-w-7xl mx-auto space-y-6">
        <div class="relative overflow-hidden rounded-3xl p-6 lg:p-8 animate-fade-in-up" style="background: linear-gradient(135deg, #be185d 0%, #ec4899 60%, #f472b6 100%); box-shadow: 0 12px 40px rgba(190,24,93,0.3);">
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 28px 28px;" />
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/4" />

            <div class="relative flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <p class="inline-flex items-center gap-2 text-pink-200 text-sm font-medium">
                        <Sparkles class="h-4 w-4" />
                        {{ greeting }}
                    </p>
                    <h2 class="text-white text-xl sm:text-2xl font-extrabold mt-0.5">{{ buyer.contact }}</h2>
                    <p class="text-pink-200 text-sm mt-0.5">{{ buyer.name }}</p>

                    <div class="mt-3 inline-flex items-center gap-2 bg-white/20 backdrop-blur border border-white/30 rounded-full px-3 py-1">
                        <Star class="h-4 w-4 text-white" />
                        <span class="text-white text-xs font-bold">Tier {{ buyer.tier ?? 'Customer' }}</span>
                    </div>
                </div>

                <Link href="/buyer/catalog" class="inline-flex items-center gap-2 bg-white text-pink-700 font-bold px-5 py-2.5 rounded-2xl text-sm hover:shadow-lg hover:shadow-pink-900/20 transition-all duration-200 hover:-translate-y-0.5">
                    <ShoppingBag class="w-4 h-4" />
                    Pesan Sekarang
                </Link>
            </div>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <StatCard label="Alokasi Stok" :value="stats.stock_allocation" suffix=" box" icon="stock" color="pink" :trend="12" :delay="0" />
            <StatCard label="Pesanan Pending" :value="stats.pending_orders" icon="orders" color="amber" :delay="100" />
            <StatCard label="Total Belanja" :value="formatRupiah(stats.total_spend)" icon="spend" color="blue" :trend="8" :delay="200" />
            <StatCard label="Reward Points" :value="stats.reward_points" suffix=" pts" icon="reward" color="green" :trend="5" :delay="300" />
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 animate-fade-in-up delay-200">
            <Link
                v-for="action in quickActions"
                :key="action.href"
                :href="action.href"
                :class="['flex flex-col items-center gap-2 p-4 rounded-2xl border font-semibold text-sm transition-all duration-200 hover:-translate-y-0.5', action.color]"
            >
                <component :is="action.icon" class="h-7 w-7" />
                <span class="text-xs font-semibold">{{ action.label }}</span>
            </Link>
        </div>

        <div class="grid lg:grid-cols-5 gap-6">
            <div class="lg:col-span-3 card p-6 animate-fade-in-up delay-300">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-base font-bold text-gray-900">Pesanan Terbaru</h3>
                    <Link href="/buyer/orders" class="text-xs font-semibold text-pink-600 hover:text-pink-800 transition-colors">
                        Lihat Semua
                    </Link>
                </div>

                <div v-if="recent_orders.length" class="overflow-x-auto">
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
                            <tr v-for="order in recent_orders" :key="order.id" class="hover:bg-pink-50/50 transition-colors">
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
                <div v-else class="py-14 text-center">
                    <ClipboardList class="mx-auto h-10 w-10 text-pink-500" />
                    <h3 class="mt-4 text-base font-bold text-gray-800">Belum ada pesanan</h3>
                    <p class="mt-2 text-sm text-gray-500">Pesanan customer akan tampil otomatis di sini.</p>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-4">
                <div class="card p-6 animate-fade-in-up delay-400">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-bold text-gray-900">Program Reward</h3>
                        <Link href="/buyer/rewards" class="text-xs font-semibold text-pink-600 hover:text-pink-800 transition-colors">Detail</Link>
                    </div>

                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-300 to-amber-500 flex items-center justify-center" style="box-shadow: 0 4px 12px rgba(245,158,11,0.3);">
                            <Star class="h-7 w-7 text-white" />
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium">Tier Saat Ini</p>
                            <p class="text-lg font-extrabold text-amber-600">{{ buyer.tier ?? 'Customer' }}</p>
                        </div>
                        <div class="ml-auto text-right">
                            <p class="text-xs text-gray-400 font-medium">Poin Aktif</p>
                            <p class="text-2xl font-extrabold text-pink-600">{{ stats.reward_points.toLocaleString('id-ID') }}</p>
                        </div>
                    </div>

                    <ProgressBar :current="stats.reward_points" :target="nextTierPoints" label="Menuju Tier Platinum" />

                    <p class="mt-3 text-xs text-gray-400 text-center">
                        Progress {{ progressPct.toFixed(0) }}% menuju Platinum
                    </p>
                </div>

                <div class="card-pink p-5 rounded-2xl animate-fade-in-up delay-500">
                    <div class="flex items-start gap-3">
                        <Gift class="h-6 w-6 shrink-0 text-pink-600" />
                        <div>
                            <p class="text-sm font-bold text-gray-800">Reward Customer</p>
                            <p class="text-xs text-gray-600 mt-0.5">Reward aktif akan mengikuti data poin dan benefit customer dari backend.</p>
                            <Link href="/buyer/rewards" class="mt-3 inline-flex btn-secondary text-xs px-3 py-1.5">
                                Lihat Reward
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
