<script setup>
import { ref, computed, watch } from 'vue';
import BuyerLayout from '@/Layouts/BuyerLayout.vue';
import ProductCard from '@/Components/UI/ProductCard.vue';
import SearchInput from '@/Components/UI/SearchInput.vue';
import { CheckCircle2, SearchX } from 'lucide-vue-next';

defineOptions({ layout: BuyerLayout });

const props = defineProps({
    categories: { type: Array, required: true },
    products:   { type: Array, required: true },
    uses_customer_rules: { type: Boolean, default: false },
});

const activeCategory = ref('Semua');
const searchQuery = ref('');
const CART_STORAGE_KEY = 'willshine_buyer_cart';

function loadCartItems() {
    if (typeof window === 'undefined') return [];

    try {
        const stored = window.localStorage.getItem(CART_STORAGE_KEY);

        return stored ? JSON.parse(stored) : [];
    } catch {
        return [];
    }
}

const cartItems = ref(loadCartItems());

watch(cartItems, items => {
    if (typeof window === 'undefined') return;
    window.localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(items));
}, { deep: true });

const filteredProducts = computed(() => {
    let list = props.products;

    if (activeCategory.value !== 'Semua') {
        list = list.filter(p => p.category === activeCategory.value);
    }

    if (searchQuery.value.trim()) {
        const q = searchQuery.value.toLowerCase();
        list = list.filter(p =>
            p.name.toLowerCase().includes(q) ||
            p.category.toLowerCase().includes(q) ||
            p.grade.toLowerCase().includes(q)
        );
    }

    return list;
});

const cartCount = computed(() => cartItems.value.reduce((sum, i) => sum + i.qty, 0));

function handleAddToCart({ product, qty }) {
    const maxQty = Number(product.maximum_qty || product.daily_quantity || 0);
    const existing = cartItems.value.find(i => i.id === product.id);

    if (existing) {
        const nextQty = existing.qty + qty;
        existing.qty = maxQty > 0 ? Math.min(nextQty, maxQty) : nextQty;
    } else {
        cartItems.value.push({ ...product, qty: maxQty > 0 ? Math.min(qty, maxQty) : qty });
    }

    showCartToast.value = true;
    setTimeout(() => { showCartToast.value = false; }, 2000);
}

const showCartToast = ref(false);

const stockCounts = computed(() => ({
    full:  props.products.filter(p => p.stock_status === 'full').length,
    low:   props.products.filter(p => p.stock_status === 'low').length,
    empty: props.products.filter(p => p.stock_status === 'empty').length,
}));
</script>

<template>
    <div class="px-4 sm:px-6 lg:px-8 py-6 max-w-7xl mx-auto">

        <!-- ── Page Header ─────────────────────────────── -->
        <div class="mb-6 animate-fade-in-up">
            <h2 class="text-xl font-extrabold text-gray-900">Katalog Produk</h2>
            <p class="text-sm text-gray-500 mt-0.5">
                Tersedia <span class="font-semibold text-pink-600">{{ products.length }}</span> produk —
                <span class="text-emerald-600 font-medium">{{ stockCounts.full }} tersedia</span>,
                <span class="text-amber-600 font-medium">{{ stockCounts.low }} terbatas</span>,
                <span class="text-red-500 font-medium">{{ stockCounts.empty }} habis</span>
            </p>
            <p v-if="uses_customer_rules" class="mt-2 inline-flex rounded-full bg-pink-50 px-3 py-1 text-xs font-bold text-pink-700 ring-1 ring-pink-100">
                Katalog dan alokasi qty mengikuti setting customer.
            </p>
        </div>

        <!-- ── Search + Filters ────────────────────────── -->
        <div class="card p-4 mb-6 animate-fade-in-up delay-100">
            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Search -->
                <div class="flex-1">
                    <SearchInput
                        v-model="searchQuery"
                        placeholder="Cari nama produk, kategori, atau grade..."
                    />
                </div>

                <!-- Filter button (decorative) -->
                <button class="btn-ghost flex items-center gap-2 flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filter
                </button>
            </div>

            <!-- Category pills -->
            <div class="flex gap-2 mt-3 overflow-x-auto pb-1 scrollbar-hide">
                <button
                    v-for="cat in categories"
                    :key="cat"
                    @click="activeCategory = cat"
                    :class="[
                        'flex-shrink-0 px-4 py-1.5 rounded-full text-sm font-semibold transition-all duration-200',
                        activeCategory === cat
                            ? 'bg-pink-600 text-white shadow-sm shadow-pink-200'
                            : 'bg-gray-100 text-gray-600 hover:bg-pink-50 hover:text-pink-700'
                    ]"
                >
                    {{ cat }}
                </button>
            </div>
        </div>

        <!-- ── Product Grid ─────────────────────────────── -->
        <div v-if="filteredProducts.length > 0" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 animate-fade-in">
            <ProductCard
                v-for="(product, i) in filteredProducts"
                :key="product.id"
                :product="product"
                :class="['animate-fade-in-up', `delay-${Math.min(i * 50, 400)}`]"
                @add-to-cart="handleAddToCart"
            />
        </div>

        <!-- Empty state -->
        <div v-else class="flex flex-col items-center justify-center py-24 text-center animate-scale-in">
            <SearchX class="mb-4 h-14 w-14 text-pink-500" />
            <h3 class="text-lg font-bold text-gray-800">Produk tidak ditemukan</h3>
            <p class="text-sm text-gray-500 mt-1 max-w-xs">Coba kata kunci lain atau ganti filter kategori.</p>
            <button @click="searchQuery = ''; activeCategory = 'Semua'" class="mt-5 btn-secondary">
                Reset Pencarian
            </button>
        </div>

        <!-- ── Cart Summary Sticky Bar (when items added) ── -->
        <Transition name="slide-up">
            <div
                v-if="cartCount > 0"
                class="fixed bottom-20 lg:bottom-6 right-4 lg:right-6 z-50 animate-scale-in"
            >
                <a href="/buyer/cart" class="flex items-center gap-3 bg-pink-700 text-white px-5 py-3 rounded-2xl shadow-2xl shadow-pink-200 hover:bg-pink-800 transition-all duration-200 hover:-translate-y-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="font-bold text-sm">{{ cartCount }} item di keranjang</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>
        </Transition>

        <!-- ── Add to Cart Toast ─────────────────────────── -->
        <Transition name="fade">
            <div
                v-if="showCartToast"
                class="fixed top-20 right-4 z-50 glass rounded-2xl px-5 py-3 flex items-center gap-3 shadow-lg shadow-pink-100 animate-slide-right"
            >
                <CheckCircle2 class="h-5 w-5 text-emerald-600" />
                <p class="text-sm font-semibold text-gray-800">Produk ditambahkan ke keranjang</p>
            </div>
        </Transition>
    </div>
</template>
