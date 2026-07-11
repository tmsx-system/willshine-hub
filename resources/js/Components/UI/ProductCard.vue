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
    if (props.product.stock_status === 'empty') return;

    emit('add-to-cart', { product: props.product, qty: qty.value });
    added.value = true;
    setTimeout(() => { added.value = false; }, 1800);
}

function decreaseQty() {
    qty.value = Math.max(minQty.value, qty.value - 1);
}

function increaseQty() {
    if (!canIncrease.value) return;

    qty.value += 1;
}

function formatPrice(value) {
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
                <p class="text-[11px] font-bold uppercase tracking-wide text-pink-700">Alokasi harian</p>
                <p class="mt-0.5 text-sm font-extrabold text-gray-900">{{ product.daily_quantity }} {{ product.uom }}</p>
                <p v-if="hasMaxQty" class="mt-0.5 text-[11px] text-gray-500">Maks. order: {{ maxQty }} {{ product.uom }}</p>
            </div>
            <p v-if="product.allocation_note" class="mt-2 text-xs text-gray-500 line-clamp-2">{{ product.allocation_note }}</p>

            <div class="mt-auto pt-3">
                <p class="text-base font-bold text-pink-700">{{ formatPrice(product.price) }}</p>
                <p class="text-xs text-gray-400">per {{ product.uom }}</p>

                <div v-if="product.stock_status !== 'empty'" class="mt-3 flex items-center gap-2">
                    <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden">
                        <button type="button" @click="decreaseQty" class="px-2.5 py-1.5 text-gray-500 hover:bg-pink-50 hover:text-pink-700 transition-colors text-sm font-bold">-</button>
                        <span class="px-3 text-sm font-semibold text-gray-800">{{ qty }}</span>
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
                        :class="['flex-1 text-xs font-semibold py-2 rounded-xl transition-all duration-300', added ? 'bg-emerald-500 text-white' : 'btn-primary']"
                        style="padding-top:8px;padding-bottom:8px;"
                    >
                        <span v-if="added">Ditambahkan</span>
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
