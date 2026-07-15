<script setup>
import { computed, ref, watch } from 'vue';

const props = defineProps({
    product: { type: Object, required: true },
});

const emit = defineEmits(['add-to-cart']);

const minQty = computed(() => Math.max(1, Number(props.product.minimum_qty || 1)));
const maxQty = computed(() => {
    const configuredMax = Number(props.product.maximum_qty || 0);
    const dailyQty = Number(props.product.daily_quantity || 0);

    if (configuredMax > 0 && dailyQty > 0) {
        return Math.min(configuredMax, dailyQty);
    }

    return configuredMax > 0 ? configuredMax : dailyQty;
});
const hasMaxQty = computed(() => maxQty.value > 0);
const canIncrease = computed(() => !hasMaxQty.value || qty.value < maxQty.value);
const hasPrice = computed(() => props.product.can_view_price !== false && props.product.price !== null && props.product.price !== undefined && props.product.price !== '');
const canAddToCart = computed(() => props.product.stock_status !== 'empty' && hasPrice.value);
const qty = ref(minQty.value);
const added = ref(false);

const stockClass = {
    full: 'stock-full',
    low: 'stock-low',
    empty: 'stock-empty',
};
const stockLabel = {
    full: 'Stok Tersedia',
    low: 'Stok Terbatas',
    empty: 'Habis',
};

function addToCart() {
    if (!canAddToCart.value) return;

    normalizeQty();
    emit('add-to-cart', { product: props.product, qty: qty.value });
    added.value = true;
    setTimeout(() => { added.value = false; }, 1800);
}

function decreaseQty() {
    qty.value = clampQty(qty.value - 1);
}

function increaseQty() {
    if (!canIncrease.value) return;

    qty.value = clampQty(qty.value + 1);
}

function clampQty(value) {
    const parsed = Math.floor(Number(value));
    const fallback = Number.isFinite(parsed) && parsed > 0 ? parsed : minQty.value;
    const minimum = Math.max(minQty.value, fallback);

    return hasMaxQty.value ? Math.min(minimum, maxQty.value) : minimum;
}

function updateQty(event) {
    qty.value = event.target.value;
}

function normalizeQty() {
    qty.value = clampQty(qty.value);
}

function formatPrice(value) {
    if (value === null || value === undefined || value === '') {
        return 'Harga belum tersedia';
    }

    if (typeof value === 'string' && Number.isNaN(Number(value))) {
        return value;
    }

    const number = Number(value || 0);

    return 'Rp ' + number.toLocaleString('id-ID');
}

watch(() => props.product.id, () => {
    qty.value = minQty.value;
});
</script>

<template>
    <div class="card card-hover overflow-hidden flex flex-col group">
        <div class="relative h-44 bg-gradient-to-br from-pink-50 to-pink-100 flex items-center justify-center overflow-hidden">
            <img
                v-if="product.image"
                :src="product.image"
                :alt="product.name"
                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
            />
            <div v-else class="flex h-full w-full items-center justify-center text-pink-600">
                <span class="text-5xl font-black">WS</span>
            </div>

            <span :class="['badge absolute top-3 left-3', stockClass[product.stock_status]]">
                {{ stockLabel[product.stock_status] }}
            </span>

            <span class="badge absolute top-3 right-3 max-w-[8rem] truncate" style="background:#fce7f3;color:#be185d;">
                {{ product.grade || 'Fresh' }}
            </span>
        </div>

        <div class="p-4 flex flex-col flex-1">
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">{{ product.category }}</p>
            <h3 class="mt-0.5 text-sm font-bold text-gray-900 leading-tight line-clamp-2">{{ product.name }}</h3>
            <p class="mt-1 text-xs text-gray-500">UOM: <span class="font-semibold text-gray-700">{{ product.uom }}</span></p>

            <div v-if="product.daily_quantity !== null && product.daily_quantity !== undefined" class="mt-3 rounded-xl border border-pink-100 bg-pink-50 px-3 py-2">
                <p class="text-[11px] font-bold uppercase tracking-wide text-pink-700">Stok tersedia</p>
                <p class="mt-0.5 text-sm font-extrabold text-gray-900">{{ product.daily_quantity }} {{ product.uom }}</p>
            </div>
            <p v-if="product.allocation_note" class="mt-2 text-xs text-gray-500 line-clamp-2">{{ product.allocation_note }}</p>

            <div class="mt-auto pt-3">
                <p class="text-base font-bold text-pink-700">{{ product.can_view_price === false ? 'Hubungi admin' : formatPrice(product.price) }}</p>
                <p class="text-xs text-gray-400">per {{ product.uom }}</p>
                <p v-if="product.price_note" class="mt-1 text-[11px] font-semibold text-pink-700">{{ product.price_note }}</p>

                <div v-if="product.stock_status !== 'empty'" class="mt-3 flex items-center gap-2">
                    <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden">
                        <button type="button" @click="decreaseQty" class="px-2.5 py-1.5 text-gray-500 hover:bg-pink-50 hover:text-pink-700 transition-colors text-sm font-bold">-</button>
                        <input
                            :value="qty"
                            type="number"
                            inputmode="numeric"
                            :min="minQty"
                            :max="hasMaxQty ? maxQty : undefined"
                            class="h-9 w-14 border-x border-gray-200 bg-white px-1 text-center text-sm font-semibold text-gray-800 outline-none focus:bg-pink-50 focus:text-pink-700"
                            aria-label="Jumlah order"
                            @input="updateQty"
                            @blur="normalizeQty"
                            @keydown.enter.prevent="normalizeQty"
                        />
                        <button
                            type="button"
                            @click="increaseQty"
                            class="px-2.5 py-1.5 text-gray-500 transition-colors text-sm font-bold"
                            :class="canIncrease ? 'hover:bg-pink-50 hover:text-pink-700' : 'cursor-not-allowed opacity-40'"
                            :disabled="!canIncrease"
                        >
                            +
                        </button>
                    </div>

                    <button
                        type="button"
                        @click="addToCart"
                        :disabled="!canAddToCart"
                        :class="[
                            'flex-1 text-xs font-semibold py-2 rounded-xl transition-all duration-300',
                            !canAddToCart ? 'cursor-not-allowed bg-gray-100 text-gray-400' : added ? 'bg-emerald-500 text-white' : 'btn-primary'
                        ]"
                        style="padding-top:8px;padding-bottom:8px;"
                    >
                        <span v-if="added">Ditambahkan</span>
                        <span v-else-if="!hasPrice">Harga belum ada</span>
                        <span v-else>+ Keranjang</span>
                    </button>
                </div>

                <div v-else class="mt-3">
                    <button disabled class="w-full text-xs font-semibold py-2 rounded-xl bg-gray-100 text-gray-400 cursor-not-allowed">
                        Stok Habis
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
