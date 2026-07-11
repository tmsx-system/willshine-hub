<script setup>
import { computed, ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { CheckCircle2, Clock3, LoaderCircle, Package, ShoppingCart } from 'lucide-vue-next';
import BuyerLayout from '@/Layouts/BuyerLayout.vue';

defineOptions({ layout: BuyerLayout });

const props = defineProps({
    items: { type: Array, default: () => [] },
});

const CART_STORAGE_KEY = 'willshine_buyer_cart';

function loadCartItems() {
    if (props.items.length) return props.items.map(item => ({ ...item }));
    if (typeof window === 'undefined') return [];

    try {
        const stored = window.localStorage.getItem(CART_STORAGE_KEY);

        return stored ? JSON.parse(stored) : [];
    } catch {
        return [];
    }
}

const cartItems = ref(loadCartItems());
const notes = ref('');
const paymentMethod = ref('transfer');
const isSubmitting = ref(false);
const submitted = ref(false);

watch(cartItems, items => {
    if (typeof window === 'undefined') return;
    window.localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(items));
}, { deep: true });

const subtotal = computed(() => cartItems.value.reduce((sum, item) => sum + Number(item.price || 0) * Number(item.qty || 0), 0));
const tax = computed(() => Math.round(subtotal.value * 0.11));
const total = computed(() => subtotal.value + tax.value);

function updateQty(item, delta) {
    item.qty = Math.max(1, Number(item.qty || 1) + delta);
}

function removeItem(id) {
    cartItems.value = cartItems.value.filter(item => item.id !== id);
}

function formatRupiah(value) {
    return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
}

function submitOrder() {
    if (cartItems.value.length === 0) return;

    router.post('/buyer/cart/submit', {
        items: cartItems.value,
        notes: notes.value,
        payment_method: paymentMethod.value,
    }, {
        preserveScroll: true,
        onStart: () => {
            isSubmitting.value = true;
        },
        onSuccess: () => {
            cartItems.value = [];
            if (typeof window !== 'undefined') {
                window.localStorage.removeItem(CART_STORAGE_KEY);
            }
            submitted.value = true;
        },
        onFinish: () => {
            isSubmitting.value = false;
        },
    });
}
</script>

<template>
    <div class="px-4 sm:px-6 lg:px-8 py-6 max-w-7xl mx-auto">
        <div class="mb-6 animate-fade-in-up">
            <h2 class="text-xl font-extrabold text-gray-900">Keranjang Pesanan</h2>
            <p class="text-sm text-gray-500 mt-0.5">
                <span class="font-semibold text-pink-600">{{ cartItems.length }}</span> produk dalam keranjang Anda
            </p>
        </div>

        <div v-if="submitted" class="flex flex-col items-center justify-center py-20 text-center animate-scale-in">
            <div class="mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-emerald-100">
                <CheckCircle2 class="h-12 w-12 text-emerald-600" />
            </div>
            <h3 class="text-2xl font-extrabold text-gray-900">Pesanan Berhasil Dibuat</h3>
            <p class="text-gray-500 mt-2 max-w-md">Pesanan Anda telah dikirim dan sedang menunggu persetujuan dari tim admin.</p>
            <div class="mt-4 inline-flex items-center gap-2 bg-amber-50 border border-amber-100 rounded-2xl px-4 py-2">
                <Clock3 class="h-4 w-4 text-amber-500" />
                <span class="text-sm font-semibold text-amber-700">Status: Menunggu Persetujuan</span>
            </div>
            <div class="flex gap-3 mt-8">
                <a href="/buyer/orders" class="btn-primary text-sm">Lihat Pesanan</a>
                <a href="/buyer/catalog" class="btn-ghost text-sm">Lanjut Belanja</a>
            </div>
        </div>

        <div v-else-if="cartItems.length === 0" class="flex flex-col items-center justify-center py-24 text-center animate-scale-in">
            <ShoppingCart class="mb-5 h-16 w-16 text-pink-500" />
            <h3 class="text-lg font-bold text-gray-800">Keranjang Anda kosong</h3>
            <p class="text-sm text-gray-500 mt-1">Tambahkan produk dari katalog untuk mulai memesan.</p>
            <a href="/buyer/catalog" class="mt-6 btn-primary text-sm">Jelajahi Katalog</a>
        </div>

        <div v-else class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-3 animate-fade-in-up">
                <div
                    v-for="(item, index) in cartItems"
                    :key="item.id"
                    class="card p-5 flex items-center gap-4 animate-fade-in-up"
                    :style="{ animationDelay: index * 60 + 'ms' }"
                >
                    <div class="w-14 h-14 bg-gradient-to-br from-pink-50 to-pink-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <Package class="h-7 w-7 text-pink-500" />
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-900 text-sm leading-tight">{{ item.name }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ item.grade }} / per {{ item.uom }}</p>
                        <p class="text-sm font-bold text-pink-700 mt-1">
                            {{ formatRupiah(item.price) }}
                            <span class="text-xs font-normal text-gray-400">/{{ item.uom }}</span>
                        </p>
                    </div>

                    <div class="flex flex-col items-end gap-2">
                        <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden">
                            <button @click="updateQty(item, -1)" class="px-3 py-2 text-gray-500 hover:bg-pink-50 hover:text-pink-700 transition-colors font-bold text-sm">-</button>
                            <span class="px-4 py-2 text-sm font-bold text-gray-800 min-w-[2.5rem] text-center">{{ item.qty }}</span>
                            <button @click="updateQty(item, 1)" class="px-3 py-2 text-gray-500 hover:bg-pink-50 hover:text-pink-700 transition-colors font-bold text-sm">+</button>
                        </div>
                        <p class="text-sm font-bold text-gray-800">{{ formatRupiah(item.price * item.qty) }}</p>
                        <button @click="removeItem(item.id)" class="text-xs text-red-400 hover:text-red-600 transition-colors font-medium">Hapus</button>
                    </div>
                </div>

                <div class="card p-5 animate-fade-in-up delay-300">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Catatan Pesanan</label>
                    <textarea v-model="notes" rows="3" placeholder="Tambahkan catatan untuk pesanan ini (opsional)..." class="input-field resize-none" />
                </div>
            </div>

            <div class="animate-fade-in-up delay-200">
                <div class="card p-6 sticky top-24">
                    <h3 class="font-bold text-gray-900 mb-5 text-base">Ringkasan Pesanan</h3>

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

                    <div class="mt-4 p-3.5 bg-amber-50 border border-amber-100 rounded-2xl flex gap-2.5">
                        <Clock3 class="h-4 w-4 flex-shrink-0 text-amber-500" />
                        <p class="text-xs text-amber-700">Pesanan akan masuk status <strong>Menunggu Persetujuan</strong> dan diproses oleh tim admin dalam 1x24 jam.</p>
                    </div>

                    <button @click="submitOrder" :disabled="isSubmitting" class="btn-primary w-full justify-center mt-5 py-3.5 text-base">
                        <LoaderCircle v-if="isSubmitting" class="-ml-1 mr-2 h-4 w-4 animate-spin text-white" />
                        {{ isSubmitting ? 'Memproses...' : 'Kirim Pesanan' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
