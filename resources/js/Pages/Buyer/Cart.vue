<script setup>
import { ref, computed } from 'vue';
import BuyerLayout from '@/Layouts/BuyerLayout.vue';
import { router } from '@inertiajs/vue3';

defineOptions({ layout: BuyerLayout });

// Mock cart data (in production, this would come from localStorage or server)
const cartItems = ref([
    { id: 1, name: 'Apel Fuji Premium',  category: 'Fresh',  uom: 'Kg',  price: 45000,  qty: 5,  grade: 'Grade A'  },
    { id: 4, name: 'Keju Mozzarella',    category: 'Dairy',  uom: 'Kg',  price: 95000,  qty: 2,  grade: 'Grade A'  },
    { id: 5, name: 'Susu UHT Full Cream',category: 'Dairy',  uom: 'Ltr', price: 18000,  qty: 12, grade: 'Grade B'  },
]);

const notes = ref('');
const paymentMethod = ref('transfer');
const isSubmitting = ref(false);
const submitted = ref(false);

const subtotal = computed(() => cartItems.value.reduce((s, i) => s + i.price * i.qty, 0));
const tax = computed(() => Math.round(subtotal.value * 0.11)); // 11% PPN
const total = computed(() => subtotal.value + tax.value);

const categoryIcon = {
    Fresh:  '🥬', Frozen: '❄️', Dairy: '🧀',
    Beverages: '🧃', Snacks: '🍪', Organic: '🌿',
};

function updateQty(item, delta) {
    item.qty = Math.max(1, item.qty + delta);
}

function removeItem(id) {
    cartItems.value = cartItems.value.filter(i => i.id !== id);
}

function formatRupiah(n) {
    return 'Rp ' + n.toLocaleString('id-ID');
}

async function submitOrder() {
    isSubmitting.value = true;
    await new Promise(r => setTimeout(r, 1500)); // simulate API call
    isSubmitting.value = false;
    submitted.value = true;
}
</script>

<template>
    <div class="px-4 sm:px-6 lg:px-8 py-6 max-w-7xl mx-auto">

        <!-- ── Header ──────────────────────────────────── -->
        <div class="mb-6 animate-fade-in-up">
            <h2 class="text-xl font-extrabold text-gray-900">Keranjang Pesanan</h2>
            <p class="text-sm text-gray-500 mt-0.5">
                <span class="font-semibold text-pink-600">{{ cartItems.length }}</span> produk dalam keranjang Anda
            </p>
        </div>

        <!-- ── Success State ───────────────────────────── -->
        <div v-if="submitted" class="flex flex-col items-center justify-center py-20 text-center animate-scale-in">
            <div class="w-24 h-24 bg-emerald-100 rounded-full flex items-center justify-center text-5xl mb-6">✅</div>
            <h3 class="text-2xl font-extrabold text-gray-900">Pesanan Berhasil Dibuat!</h3>
            <p class="text-gray-500 mt-2 max-w-md">Pesanan Anda telah dikirim dan sedang menunggu persetujuan dari tim admin. Anda akan mendapat notifikasi segera.</p>
            <div class="mt-4 inline-flex items-center gap-2 bg-amber-50 border border-amber-100 rounded-2xl px-4 py-2">
                <span class="text-amber-500">⏳</span>
                <span class="text-sm font-semibold text-amber-700">Status: Menunggu Persetujuan</span>
            </div>
            <div class="flex gap-3 mt-8">
                <a href="/buyer/orders" class="btn-primary text-sm">Lihat Pesanan</a>
                <a href="/buyer/catalog" class="btn-ghost text-sm">Lanjut Belanja</a>
            </div>
        </div>

        <!-- ── Empty Cart ──────────────────────────────── -->
        <div v-else-if="cartItems.length === 0" class="flex flex-col items-center justify-center py-24 text-center animate-scale-in">
            <div class="text-7xl mb-5">🛒</div>
            <h3 class="text-lg font-bold text-gray-800">Keranjang Anda kosong</h3>
            <p class="text-sm text-gray-500 mt-1">Tambahkan produk dari katalog untuk mulai memesan.</p>
            <a href="/buyer/catalog" class="mt-6 btn-primary text-sm">Jelajahi Katalog</a>
        </div>

        <!-- ── Cart Content ─────────────────────────────── -->
        <div v-else class="grid lg:grid-cols-3 gap-6">

            <!-- Cart Items (left) -->
            <div class="lg:col-span-2 space-y-3 animate-fade-in-up">
                <div
                    v-for="(item, i) in cartItems"
                    :key="item.id"
                    class="card p-5 flex items-center gap-4 animate-fade-in-up"
                    :style="{ animationDelay: i * 60 + 'ms' }"
                >
                    <!-- Category icon -->
                    <div class="w-14 h-14 bg-gradient-to-br from-pink-50 to-pink-100 rounded-2xl flex items-center justify-center text-2xl flex-shrink-0">
                        {{ categoryIcon[item.category] ?? '📦' }}
                    </div>

                    <!-- Details -->
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-900 text-sm leading-tight">{{ item.name }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ item.grade }} · per {{ item.uom }}</p>
                        <p class="text-sm font-bold text-pink-700 mt-1">{{ formatRupiah(item.price) }}<span class="text-xs font-normal text-gray-400">/{{ item.uom }}</span></p>
                    </div>

                    <!-- Qty stepper -->
                    <div class="flex flex-col items-end gap-2">
                        <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden">
                            <button @click="updateQty(item, -1)" class="px-3 py-2 text-gray-500 hover:bg-pink-50 hover:text-pink-700 transition-colors font-bold text-sm">−</button>
                            <span class="px-4 py-2 text-sm font-bold text-gray-800 min-w-[2.5rem] text-center">{{ item.qty }}</span>
                            <button @click="updateQty(item, 1)" class="px-3 py-2 text-gray-500 hover:bg-pink-50 hover:text-pink-700 transition-colors font-bold text-sm">+</button>
                        </div>
                        <p class="text-sm font-bold text-gray-800">{{ formatRupiah(item.price * item.qty) }}</p>
                        <button @click="removeItem(item.id)" class="text-xs text-red-400 hover:text-red-600 transition-colors font-medium">Hapus</button>
                    </div>
                </div>

                <!-- Notes field -->
                <div class="card p-5 animate-fade-in-up delay-300">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Catatan Pesanan</label>
                    <textarea
                        v-model="notes"
                        rows="3"
                        placeholder="Tambahkan catatan untuk pesanan ini (opsional)..."
                        class="input-field resize-none"
                    />
                </div>
            </div>

            <!-- Order Summary (right) -->
            <div class="animate-fade-in-up delay-200">
                <div class="card p-6 sticky top-24">
                    <h3 class="font-bold text-gray-900 mb-5 text-base">Ringkasan Pesanan</h3>

                    <!-- Price breakdown -->
                    <div class="space-y-3 pb-4 border-b border-gray-100">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal ({{ cartItems.length }} produk)</span>
                            <span class="font-semibold text-gray-800">{{ formatRupiah(subtotal) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">PPN 11%</span>
                            <span class="font-semibold text-gray-800">{{ formatRupiah(tax) }}</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center py-4 border-b border-gray-100">
                        <span class="font-bold text-gray-900">Total</span>
                        <span class="text-xl font-extrabold text-pink-700">{{ formatRupiah(total) }}</span>
                    </div>

                    <!-- Payment method -->
                    <div class="mt-4 space-y-2">
                        <p class="text-sm font-bold text-gray-700">Metode Pembayaran</p>
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 cursor-pointer hover:border-pink-300 transition-colors" :class="paymentMethod === 'transfer' ? 'border-pink-400 bg-pink-50' : ''">
                            <input v-model="paymentMethod" type="radio" value="transfer" class="text-pink-600 focus:ring-pink-400" />
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Transfer Bank</p>
                                <p class="text-xs text-gray-400">BCA / Mandiri / BNI</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 cursor-pointer hover:border-pink-300 transition-colors" :class="paymentMethod === 'tempo' ? 'border-pink-400 bg-pink-50' : ''">
                            <input v-model="paymentMethod" type="radio" value="tempo" class="text-pink-600 focus:ring-pink-400" />
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Tempo 30 Hari</p>
                                <p class="text-xs text-gray-400">Khusus mitra terverifikasi</p>
                            </div>
                        </label>
                    </div>

                    <!-- Status info -->
                    <div class="mt-4 p-3.5 bg-amber-50 border border-amber-100 rounded-2xl flex gap-2.5">
                        <span class="text-sm flex-shrink-0">⏳</span>
                        <p class="text-xs text-amber-700">Pesanan akan masuk status <strong>Menunggu Persetujuan</strong> dan diproses oleh tim admin dalam 1×24 jam.</p>
                    </div>

                    <!-- Submit button -->
                    <button
                        @click="submitOrder"
                        :disabled="isSubmitting"
                        class="btn-primary w-full justify-center mt-5 py-3.5 text-base"
                    >
                        <svg v-if="isSubmitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                        {{ isSubmitting ? 'Memproses...' : 'Kirim Pesanan' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
