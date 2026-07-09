<script setup>
import { ref, inject } from 'vue';

const props = defineProps({
    product: { type: Object, required: true },
});

const emit = defineEmits(['add-to-cart']);

const qty = ref(1);
const added = ref(false);

const stockClass = {
    full:  'stock-full',
    low:   'stock-low',
    empty: 'stock-empty',
};
const stockLabel = {
    full:  'Stok Tersedia',
    low:   'Stok Terbatas',
    empty: 'Habis',
};

// Category emoji icons
const categoryIcon = {
    Fresh:     '🥬',
    Frozen:    '❄️',
    Dairy:     '🧀',
    Beverages: '🧃',
    Snacks:    '🍪',
    Organic:   '🌿',
};

function addToCart() {
    if (props.product.stock_status === 'empty') return;
    emit('add-to-cart', { product: props.product, qty: qty.value });
    added.value = true;
    setTimeout(() => { added.value = false; }, 1800);
}

function formatPrice(n) {
    return 'Rp ' + n.toLocaleString('id-ID');
}
</script>

<template>
    <div class="card card-hover overflow-hidden flex flex-col group">
        <!-- Product Image Area -->
        <div class="relative h-44 bg-gradient-to-br from-pink-50 to-pink-100 flex items-center justify-center overflow-hidden">
            <!-- Category icon -->
            <span class="text-7xl select-none transition-transform duration-300 group-hover:scale-110">
                {{ categoryIcon[product.category] ?? '📦' }}
            </span>

            <!-- Stock badge -->
            <span :class="['badge absolute top-3 left-3', stockClass[product.stock_status]]">
                {{ stockLabel[product.stock_status] }}
            </span>

            <!-- Grade badge -->
            <span class="badge absolute top-3 right-3" style="background:#fce7f3;color:#be185d;">
                {{ product.grade }}
            </span>
        </div>

        <!-- Content -->
        <div class="p-4 flex flex-col flex-1">
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">{{ product.category }}</p>
            <h3 class="mt-0.5 text-sm font-bold text-gray-900 leading-tight line-clamp-2">{{ product.name }}</h3>
            <p class="mt-1 text-xs text-gray-500">UOM: <span class="font-semibold text-gray-700">{{ product.uom }}</span></p>

            <div class="mt-auto pt-3">
                <!-- Price -->
                <p class="text-base font-bold text-pink-700">{{ formatPrice(product.price) }}</p>
                <p class="text-xs text-gray-400">per {{ product.uom }}</p>

                <!-- Qty + Add to Cart -->
                <div v-if="product.stock_status !== 'empty'" class="mt-3 flex items-center gap-2">
                    <!-- Qty stepper -->
                    <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden">
                        <button @click="qty = Math.max(1, qty - 1)" class="px-2.5 py-1.5 text-gray-500 hover:bg-pink-50 hover:text-pink-700 transition-colors text-sm font-bold">−</button>
                        <span class="px-3 text-sm font-semibold text-gray-800">{{ qty }}</span>
                        <button @click="qty++" class="px-2.5 py-1.5 text-gray-500 hover:bg-pink-50 hover:text-pink-700 transition-colors text-sm font-bold">+</button>
                    </div>

                    <!-- Add button -->
                    <button
                        @click="addToCart"
                        :class="['flex-1 text-xs font-semibold py-2 rounded-xl transition-all duration-300', added ? 'bg-emerald-500 text-white' : 'btn-primary']"
                        style="padding-top:8px;padding-bottom:8px;"
                    >
                        <span v-if="added">✓ Ditambahkan</span>
                        <span v-else>+ Keranjang</span>
                    </button>
                </div>

                <!-- Out of stock -->
                <div v-else class="mt-3">
                    <button disabled class="w-full text-xs font-semibold py-2 rounded-xl bg-gray-100 text-gray-400 cursor-not-allowed">
                        Stok Habis
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
