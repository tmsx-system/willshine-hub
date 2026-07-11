<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { Link } from '@inertiajs/vue3';
import { ChevronDown, Heart, Minus, PackageSearch, Plus, Search, ShoppingCart, SlidersHorizontal } from 'lucide-vue-next';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    products: { type: Array, default: () => [] },
});

const normalizedProducts = computed(() => {
    return props.products.map((product) => ({
        ...product,
        price: product.price || 'Hubungi kami',
        packaging: product.packaging || product.grade || product.uom || 'Fresh product',
        stock_status: product.stock_status || 'Tersedia',
        badge: product.badge || null,
    }));
});

const categories = computed(() => ['All', ...new Set(normalizedProducts.value.map(product => product.category).filter(Boolean))]);
const activeCategory = ref('All');
const search = ref('');
const sort = ref('Recommended');
const selectedGrade = ref('All');
const selectedPackaging = ref('All');
const selectedAvailability = ref('All');
const selectedPromotion = ref('All');
const likedProductIds = ref([]);

const uniqueOptions = (field) => computed(() => [
    'All',
    ...new Set(normalizedProducts.value.map(product => product[field]).filter(Boolean)),
]);

const gradeOptions = uniqueOptions('grade');
const packagingOptions = uniqueOptions('uom');
const availabilityOptions = uniqueOptions('stock_status');
const promotionOptions = computed(() => [
    'All',
    'With offer',
    ...new Set(normalizedProducts.value.map(product => product.badge).filter(Boolean)),
]);
const selectedGradeLabel = computed(() => `Grade: ${selectedGrade.value}`);
const selectedPackagingLabel = computed(() => `Packaging: ${selectedPackaging.value}`);
const selectedAvailabilityLabel = computed(() => `Availability: ${selectedAvailability.value}`);
const selectedPromotionLabel = computed(() => `Promotion: ${selectedPromotion.value}`);

const hasActiveFilters = computed(() => {
    return Boolean(search.value)
        || activeCategory.value !== 'All'
        || selectedGrade.value !== 'All'
        || selectedPackaging.value !== 'All'
        || selectedAvailability.value !== 'All'
        || selectedPromotion.value !== 'All';
});

const resetFilters = () => {
    search.value = '';
    activeCategory.value = 'All';
    selectedGrade.value = 'All';
    selectedPackaging.value = 'All';
    selectedAvailability.value = 'All';
    selectedPromotion.value = 'All';
};

const isLiked = (productId) => likedProductIds.value.includes(productId);

const toggleLike = (productId) => {
    likedProductIds.value = isLiked(productId)
        ? likedProductIds.value.filter(id => id !== productId)
        : [...likedProductIds.value, productId];
};

onMounted(() => {
    try {
        likedProductIds.value = JSON.parse(localStorage.getItem('willshine-liked-products') || '[]');
    } catch {
        likedProductIds.value = [];
    }
});

watch(likedProductIds, (ids) => {
    localStorage.setItem('willshine-liked-products', JSON.stringify(ids));
}, { deep: true });

const filteredProducts = computed(() => {
    const keyword = search.value.trim().toLowerCase();
    let list = normalizedProducts.value.filter((product) => {
        const matchesCategory = activeCategory.value === 'All' || product.category === activeCategory.value;
        const matchesGrade = selectedGrade.value === 'All' || product.grade === selectedGrade.value;
        const matchesPackaging = selectedPackaging.value === 'All' || product.uom === selectedPackaging.value;
        const matchesAvailability = selectedAvailability.value === 'All' || product.stock_status === selectedAvailability.value;
        const matchesPromotion = selectedPromotion.value === 'All'
            || (selectedPromotion.value === 'With offer' && product.badge)
            || product.badge === selectedPromotion.value;
        const matchesSearch = !keyword || [product.name, product.category, product.grade, product.packaging, product.uom]
            .filter(Boolean)
            .some(value => String(value).toLowerCase().includes(keyword));

        return matchesCategory && matchesGrade && matchesPackaging && matchesAvailability && matchesPromotion && matchesSearch;
    });

    if (sort.value === 'Name A-Z') {
        list = [...list].sort((a, b) => a.name.localeCompare(b.name));
    }

    if (sort.value === 'Name Z-A') {
        list = [...list].sort((a, b) => b.name.localeCompare(a.name));
    }

    return list;
});
</script>

<template>
    <PublicLayout active="products">
        <section class="bg-[#FFF7FB]">
            <div class="public-container py-12 lg:py-16">
                <p class="text-sm font-bold uppercase tracking-[.22em] text-[#BE185D]">Shop Fresh Products</p>
                <h1 class="mt-4 max-w-3xl text-4xl font-black leading-tight tracking-tight text-[#111827] md:text-6xl">
                    Jelajahi pilihan produk berkualitas.
                </h1>
                <p class="mt-5 max-w-2xl text-lg leading-8 text-[#374151]">
                    Jelajahi pilihan produk berkualitas untuk kebutuhan pribadi maupun bisnis.
                </p>
            </div>
        </section>

        <section class="public-container -mt-6 pb-16">
            <div class="rounded-[1.6rem] border border-[#FBCFE8] bg-white p-4 shadow-[0_18px_50px_rgba(236,72,153,.12)]">
                <div class="grid gap-4 lg:grid-cols-[1fr_220px]">
                    <label class="relative block">
                        <span class="sr-only">Search products</span>
                        <Search class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-[#EC4899]" />
                        <input
                            v-model="search"
                            type="search"
                            placeholder="Search products, categories, or item names"
                            class="h-12 w-full rounded-2xl border border-[#E5E7EB] bg-white pl-12 pr-4 text-sm font-medium text-[#111827] outline-none transition placeholder:text-[#9CA3AF] focus:border-[#EC4899] focus:ring-4 focus:ring-[#FCE7F3]"
                        />
                    </label>
                    <label class="relative flex h-12 cursor-pointer items-center rounded-2xl border border-[#E5E7EB] bg-white px-4 pr-11 text-sm font-bold text-[#374151] transition hover:border-[#FBCFE8] hover:bg-[#FDF2F8] focus-within:border-[#EC4899] focus-within:ring-4 focus-within:ring-[#FCE7F3]">
                        <span class="sr-only">Sort products</span>
                        <span class="truncate">{{ sort }}</span>
                        <select v-model="sort" class="absolute inset-0 z-10 h-full w-full cursor-pointer appearance-none opacity-0">
                            <option>Recommended</option>
                            <option>Newest</option>
                            <option>Name A-Z</option>
                            <option>Name Z-A</option>
                            <option>Price Low to High</option>
                            <option>Price High to Low</option>
                            <option>Most Popular</option>
                        </select>
                        <ChevronDown class="pointer-events-none absolute right-4 top-1/2 z-20 h-4 w-4 -translate-y-1/2 text-[#374151]" />
                    </label>
                </div>

                <div class="mt-4 flex gap-2 overflow-x-auto pb-1">
                    <button
                        v-for="cat in categories"
                        :key="cat"
                        type="button"
                        @click="activeCategory = cat"
                        :class="[
                            'min-h-11 shrink-0 rounded-full px-4 text-sm font-bold transition',
                            activeCategory === cat
                                ? 'bg-[#EC4899] text-white shadow-sm'
                                : 'border border-[#E5E7EB] bg-white text-[#374151] hover:border-[#FBCFE8] hover:bg-[#FDF2F8] hover:text-[#BE185D]'
                        ]"
                    >
                        {{ cat }}
                    </button>
                </div>

                <div class="mt-4 grid gap-3 text-sm font-semibold text-[#6B7280] md:grid-cols-4">
                    <label class="group relative flex min-h-14 cursor-pointer items-center gap-2 rounded-2xl border border-[#FBCFE8] px-4 py-3 pr-11 text-left transition hover:-translate-y-0.5 hover:bg-[#FDF2F8] hover:shadow-[0_12px_28px_rgba(236,72,153,.12)] focus-within:ring-4 focus-within:ring-[#FCE7F3]">
                        <SlidersHorizontal class="pointer-events-none h-4 w-4 shrink-0 text-[#EC4899]" />
                        <span class="pointer-events-none min-w-0 flex-1 truncate text-sm font-bold text-[#374151]">{{ selectedGradeLabel }}</span>
                        <span class="sr-only">Filter grade</span>
                        <select v-model="selectedGrade" class="absolute inset-0 z-10 h-full w-full cursor-pointer appearance-none opacity-0">
                            <option v-for="option in gradeOptions" :key="option" :value="option">Grade: {{ option }}</option>
                        </select>
                        <ChevronDown class="pointer-events-none absolute right-4 top-1/2 z-20 h-4 w-4 -translate-y-1/2 text-[#BE185D]" />
                    </label>
                    <label class="group relative flex min-h-14 cursor-pointer items-center gap-2 rounded-2xl border border-[#FBCFE8] px-4 py-3 pr-11 text-left transition hover:-translate-y-0.5 hover:bg-[#FDF2F8] hover:shadow-[0_12px_28px_rgba(236,72,153,.12)] focus-within:ring-4 focus-within:ring-[#FCE7F3]">
                        <SlidersHorizontal class="pointer-events-none h-4 w-4 shrink-0 text-[#EC4899]" />
                        <span class="pointer-events-none min-w-0 flex-1 truncate text-sm font-bold text-[#374151]">{{ selectedPackagingLabel }}</span>
                        <span class="sr-only">Filter packaging</span>
                        <select v-model="selectedPackaging" class="absolute inset-0 z-10 h-full w-full cursor-pointer appearance-none opacity-0">
                            <option v-for="option in packagingOptions" :key="option" :value="option">Packaging: {{ option }}</option>
                        </select>
                        <ChevronDown class="pointer-events-none absolute right-4 top-1/2 z-20 h-4 w-4 -translate-y-1/2 text-[#BE185D]" />
                    </label>
                    <label class="group relative flex min-h-14 cursor-pointer items-center gap-2 rounded-2xl border border-[#FBCFE8] px-4 py-3 pr-11 text-left transition hover:-translate-y-0.5 hover:bg-[#FDF2F8] hover:shadow-[0_12px_28px_rgba(236,72,153,.12)] focus-within:ring-4 focus-within:ring-[#FCE7F3]">
                        <SlidersHorizontal class="pointer-events-none h-4 w-4 shrink-0 text-[#EC4899]" />
                        <span class="pointer-events-none min-w-0 flex-1 truncate text-sm font-bold text-[#374151]">{{ selectedAvailabilityLabel }}</span>
                        <span class="sr-only">Filter availability</span>
                        <select v-model="selectedAvailability" class="absolute inset-0 z-10 h-full w-full cursor-pointer appearance-none opacity-0">
                            <option v-for="option in availabilityOptions" :key="option" :value="option">Availability: {{ option }}</option>
                        </select>
                        <ChevronDown class="pointer-events-none absolute right-4 top-1/2 z-20 h-4 w-4 -translate-y-1/2 text-[#BE185D]" />
                    </label>
                    <label class="group relative flex min-h-14 cursor-pointer items-center gap-2 rounded-2xl border border-[#FBCFE8] px-4 py-3 pr-11 text-left transition hover:-translate-y-0.5 hover:bg-[#FDF2F8] hover:shadow-[0_12px_28px_rgba(236,72,153,.12)] focus-within:ring-4 focus-within:ring-[#FCE7F3]">
                        <SlidersHorizontal class="pointer-events-none h-4 w-4 shrink-0 text-[#EC4899]" />
                        <span class="pointer-events-none min-w-0 flex-1 truncate text-sm font-bold text-[#374151]">{{ selectedPromotionLabel }}</span>
                        <span class="sr-only">Filter promotion</span>
                        <select v-model="selectedPromotion" class="absolute inset-0 z-10 h-full w-full cursor-pointer appearance-none opacity-0">
                            <option v-for="option in promotionOptions" :key="option" :value="option">Promotion: {{ option }}</option>
                        </select>
                        <ChevronDown class="pointer-events-none absolute right-4 top-1/2 z-20 h-4 w-4 -translate-y-1/2 text-[#BE185D]" />
                    </label>
                </div>
            </div>

            <div class="mt-8 flex items-center justify-between">
                <p class="text-sm font-semibold text-[#6B7280]">{{ filteredProducts.length }} products found</p>
                <button v-if="hasActiveFilters" type="button" class="text-sm font-bold text-[#BE185D]" @click="resetFilters">
                    Reset Filters
                </button>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <article
                    v-for="product in filteredProducts"
                    :key="product.id"
                    class="group flex flex-col overflow-hidden rounded-[1.4rem] border border-[#FBCFE8] bg-white p-3 shadow-sm transition-all hover:-translate-y-1 hover:border-[#F9A8D4] hover:shadow-[0_24px_50px_rgba(236,72,153,.16)]"
                    :class="{ 'opacity-70': product.stock_status === 'Out of Stock' }"
                >
                    <div class="relative h-56 overflow-hidden rounded-[1rem] bg-[#FDF2F8]">
                        <img v-if="product.image" :src="product.image" :alt="product.name" class="h-full w-full object-cover saturate-[1.05] transition-transform duration-500 group-hover:scale-105" />
                        <div v-else class="flex h-full w-full items-center justify-center text-[#BE185D]">
                            <PackageSearch class="h-14 w-14" />
                        </div>
                        <div class="pointer-events-none absolute inset-0 bg-[#EC4899]/10 mix-blend-soft-light"></div>
                        <span v-if="product.badge" class="pointer-events-none absolute left-3 top-3 rounded-full bg-[#EC4899] px-3 py-1 text-xs font-bold text-white">{{ product.badge }}</span>
                        <span
                            class="pointer-events-none absolute bottom-3 left-3 rounded-full px-3 py-1 text-xs font-bold"
                            :class="product.stock_status === 'Out of Stock' ? 'bg-[#FEE2E2] text-[#991B1B]' : product.stock_status === 'Low Stock' ? 'bg-[#FEF3C7] text-[#92400E]' : 'bg-[#DCFCE7] text-[#166534]'"
                        >
                            {{ product.stock_status }}
                        </span>
                        <button
                            type="button"
                            class="absolute right-3 top-3 z-30 flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-[#BE185D] shadow-sm transition hover:-translate-y-0.5 hover:bg-[#FCE7F3] hover:shadow-[0_12px_24px_rgba(236,72,153,.16)]"
                            :class="isLiked(product.id) ? 'bg-[#EC4899] text-white' : ''"
                            :aria-label="isLiked(product.id) ? 'Remove from wishlist' : 'Add to wishlist'"
                            @click.stop.prevent="toggleLike(product.id)"
                        >
                            <Heart class="h-5 w-5" :fill="isLiked(product.id) ? 'currentColor' : 'none'" />
                        </button>
                    </div>

                    <div class="flex flex-1 flex-col p-3">
                        <p class="text-xs font-bold uppercase tracking-[.14em] text-[#BE185D]">{{ product.category }}</p>
                        <h3 class="mt-2 text-lg font-black leading-tight text-[#111827]">{{ product.name }}</h3>
                        <p class="mt-2 text-sm text-[#6B7280]">{{ product.grade }} - {{ product.packaging }}</p>
                        <p class="mt-4 text-lg font-black text-[#BE185D]">
                            {{ product.price }}
                            <span class="text-xs font-semibold text-[#9CA3AF]">/ {{ product.uom }}</span>
                        </p>
                        <p v-if="product.original_price" class="text-xs font-semibold text-[#9CA3AF] line-through">{{ product.original_price }}</p>

                        <div class="mt-5 grid grid-cols-[112px_1fr] gap-2">
                            <div class="flex min-h-11 items-center justify-between rounded-xl border border-[#E5E7EB] px-3 text-sm font-bold text-[#374151]">
                                <button type="button" aria-label="Decrease quantity"><Minus class="h-4 w-4" /></button>
                                <span>1</span>
                                <button type="button" aria-label="Increase quantity"><Plus class="h-4 w-4" /></button>
                            </div>
                            <Link
                                href="/login"
                                class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl text-sm font-bold transition"
                                :class="product.stock_status === 'Out of Stock' ? 'bg-[#F3F4F6] text-[#6B7280]' : 'bg-[#EC4899] text-white hover:bg-[#BE185D]'"
                            >
                                <ShoppingCart v-if="product.stock_status !== 'Out of Stock'" class="h-4 w-4" />
                                {{ product.stock_status === 'Out of Stock' ? 'Notify Me' : 'Add to Cart' }}
                            </Link>
                        </div>
                        <Link href="/login" class="mt-3 text-center text-sm font-bold text-[#BE185D] hover:underline">Quick View</Link>
                    </div>
                </article>
            </div>

            <div v-if="filteredProducts.length === 0" class="mt-10 rounded-[1.6rem] border border-[#E5E7EB] bg-white px-6 py-16 text-center shadow-sm">
                <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-[#FCE7F3] text-[#BE185D]">
                    <Search class="h-8 w-8" />
                </div>
                <h3 class="text-xl font-black text-[#111827]">No products found</h3>
                <p class="mt-2 text-[#6B7280]">Try adjusting your search or filter selection.</p>
                <button type="button" class="mt-6 rounded-xl bg-[#EC4899] px-6 py-3 text-sm font-bold text-white" @click="resetFilters">
                    Reset Filters
                </button>
            </div>
        </section>
    </PublicLayout>
</template>
