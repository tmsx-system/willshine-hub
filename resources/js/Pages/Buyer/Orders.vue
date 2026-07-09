<script setup>
import { ref, computed } from 'vue';
import BuyerLayout from '@/Layouts/BuyerLayout.vue';
import Badge from '@/Components/UI/Badge.vue';

defineOptions({ layout: BuyerLayout });

const props = defineProps({
    orders: { type: Array, required: true },
});

const activeFilter = ref('Semua');
const expandedOrder = ref(null);

const filters = ['Semua', 'Pending', 'Approved', 'Invoiced', 'Rejected'];

const filteredOrders = computed(() => {
    if (activeFilter.value === 'Semua') return props.orders;
    return props.orders.filter(o => o.status === activeFilter.value);
});

const filterCounts = computed(() => {
    const counts = {};
    filters.forEach(f => {
        counts[f] = f === 'Semua' ? props.orders.length : props.orders.filter(o => o.status === f).length;
    });
    return counts;
});

function toggleExpand(id) {
    expandedOrder.value = expandedOrder.value === id ? null : id;
}

function formatRupiah(n) {
    return 'Rp ' + n.toLocaleString('id-ID');
}

function formatDate(d) {
    return new Date(d).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
}

// Mock order items for expanded view
function getOrderItems(orderId) {
    return [
        { name: 'Apel Fuji Premium', qty: 5, uom: 'Kg', price: 45000 },
        { name: 'Stroberi Lokal', qty: 2, uom: 'Box', price: 120000 },
        { name: 'Susu UHT Full Cream', qty: 12, uom: 'Ltr', price: 18000 },
    ];
}
</script>

<template>
    <div class="px-4 sm:px-6 lg:px-8 py-6 max-w-7xl mx-auto">

        <!-- ── Header ──────────────────────────────────── -->
        <div class="mb-6 animate-fade-in-up">
            <h2 class="text-xl font-extrabold text-gray-900">Riwayat Pesanan</h2>
            <p class="text-sm text-gray-500 mt-0.5">Total <span class="font-semibold text-pink-600">{{ orders.length }}</span> pesanan</p>
        </div>

        <!-- ── Filter Tabs ─────────────────────────────── -->
        <div class="card p-1.5 mb-6 flex overflow-x-auto gap-1 animate-fade-in-up delay-100">
            <button
                v-for="filter in filters"
                :key="filter"
                @click="activeFilter = filter"
                :class="[
                    'flex-shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200',
                    activeFilter === filter
                        ? 'bg-pink-600 text-white shadow-sm'
                        : 'text-gray-600 hover:bg-pink-50 hover:text-pink-700'
                ]"
            >
                {{ filter }}
                <span :class="['text-[10px] w-5 h-5 rounded-full flex items-center justify-center font-bold', activeFilter === filter ? 'bg-white/30 text-white' : 'bg-gray-100 text-gray-600']">
                    {{ filterCounts[filter] }}
                </span>
            </button>
        </div>

        <!-- ── Order List ───────────────────────────────── -->
        <div v-if="filteredOrders.length" class="space-y-3 animate-fade-in">

            <div
                v-for="(order, i) in filteredOrders"
                :key="order.id"
                class="card overflow-hidden animate-fade-in-up"
                :style="{ animationDelay: i * 50 + 'ms' }"
            >
                <!-- Order header row -->
                <button
                    class="w-full flex items-center justify-between p-5 hover:bg-pink-50/50 transition-colors text-left"
                    @click="toggleExpand(order.id)"
                >
                    <div class="flex items-center gap-4 min-w-0">
                        <!-- Icon -->
                        <div class="w-10 h-10 bg-pink-50 rounded-2xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>

                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="font-bold text-gray-900 text-sm">{{ order.id }}</p>
                                <Badge :status="order.status" size="sm" />
                            </div>
                            <p class="text-xs text-gray-400 mt-0.5">{{ formatDate(order.date) }} · {{ order.items }} item · {{ order.payment }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 flex-shrink-0 ml-4">
                        <div class="text-right hidden sm:block">
                            <p class="font-bold text-gray-900 text-sm">{{ formatRupiah(order.total) }}</p>
                            <p class="text-xs text-gray-400">Total</p>
                        </div>
                        <!-- Chevron -->
                        <svg
                            class="w-4 h-4 text-gray-400 transition-transform duration-200"
                            :class="expandedOrder === order.id ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </button>

                <!-- Expanded detail -->
                <Transition name="expand">
                    <div v-if="expandedOrder === order.id" class="border-t border-gray-100 overflow-hidden">
                        <div class="p-5 bg-pink-50/40">
                            <!-- Mobile total -->
                            <div class="flex items-center justify-between mb-4 sm:hidden">
                                <span class="text-sm font-medium text-gray-600">Total Pesanan</span>
                                <span class="font-bold text-gray-900">{{ formatRupiah(order.total) }}</span>
                            </div>

                            <!-- Items table -->
                            <div class="bg-white rounded-2xl overflow-hidden border border-gray-100">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2.5 text-left text-xs font-bold text-gray-400 uppercase">Produk</th>
                                            <th class="px-4 py-2.5 text-center text-xs font-bold text-gray-400 uppercase">Qty</th>
                                            <th class="px-4 py-2.5 text-right text-xs font-bold text-gray-400 uppercase">Harga</th>
                                            <th class="px-4 py-2.5 text-right text-xs font-bold text-gray-400 uppercase">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        <tr v-for="item in getOrderItems(order.id)" :key="item.name">
                                            <td class="px-4 py-3">
                                                <p class="font-semibold text-gray-800">{{ item.name }}</p>
                                                <p class="text-xs text-gray-400">{{ item.uom }}</p>
                                            </td>
                                            <td class="px-4 py-3 text-center text-gray-600">{{ item.qty }}</td>
                                            <td class="px-4 py-3 text-right text-gray-600">{{ formatRupiah(item.price) }}</td>
                                            <td class="px-4 py-3 text-right font-semibold text-gray-800">{{ formatRupiah(item.qty * item.price) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center justify-between mt-4">
                                <div class="flex gap-2">
                                    <button v-if="order.status === 'Invoiced'" class="btn-secondary text-xs px-3 py-2">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                                        </svg>
                                        Unduh Invoice
                                    </button>
                                    <button v-if="order.status === 'Invoiced'" class="btn-ghost text-xs px-3 py-2">
                                        Pesan Ulang
                                    </button>
                                </div>
                                <button v-if="order.status === 'Pending'" class="text-xs text-red-500 hover:text-red-700 font-semibold transition-colors">
                                    Batalkan
                                </button>
                            </div>
                        </div>
                    </div>
                </Transition>
            </div>
        </div>

        <!-- Empty state -->
        <div v-else class="flex flex-col items-center justify-center py-24 text-center animate-scale-in">
            <div class="text-6xl mb-4">📋</div>
            <h3 class="text-lg font-bold text-gray-800">Belum ada pesanan</h3>
            <p class="text-sm text-gray-500 mt-1">Pesanan dengan status "{{ activeFilter }}" tidak ditemukan.</p>
            <a href="/buyer/catalog" class="mt-5 btn-primary text-sm">Mulai Pesan</a>
        </div>
    </div>
</template>

<style scoped>
.expand-enter-active,
.expand-leave-active {
    transition: max-height 0.3s ease, opacity 0.2s ease;
    max-height: 500px;
    overflow: hidden;
}

.expand-enter-from,
.expand-leave-to {
    max-height: 0;
    opacity: 0;
}
</style>
