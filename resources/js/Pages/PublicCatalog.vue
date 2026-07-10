<script setup>
import { computed, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    products: { type: Array, default: () => [] },
});

const sampleProducts = [
    { id: 'catalog-1', name: 'Cavendish Banana Premium', category: 'Banana', grade: 'Premium Grade', packaging: '12 kg per box', uom: 'Box', price: 'Rp75.000', original_price: 'Rp88.000', stock_status: 'In Stock', image: '/images/banana_organic.png', badge: 'Best Seller' },
    { id: 'catalog-2', name: 'Cavendish Banana Chibi', category: 'Banana', grade: 'Fresh Selection', packaging: '5 kg pack', uom: 'Pack', price: 'Rp42.000', original_price: '', stock_status: 'In Stock', image: '/images/banana_organic.png', badge: 'Featured' },
    { id: 'catalog-3', name: 'Barangan Banana Grade A', category: 'Banana', grade: 'Grade A', packaging: 'Minimum order 1 box', uom: 'Box', price: 'Rp82.000', original_price: '', stock_status: 'Low Stock', image: '/images/hero_fruits.png', badge: 'Popular' },
    { id: 'catalog-4', name: 'Papaya Red Lady', category: 'Papaya', grade: 'Fresh Selection', packaging: '12 pcs per crate', uom: 'Crate', price: 'Rp96.000', original_price: '', stock_status: 'In Stock', image: '/images/honey_mango.png', badge: 'New' },
    { id: 'catalog-5', name: 'Sweet Pineapple', category: 'Pineapple', grade: 'Premium Grade', packaging: '10 pcs per crate', uom: 'Crate', price: 'Rp125.000', original_price: 'Rp140.000', stock_status: 'In Stock', image: '/images/apple_fuji.png', badge: 'Special Offer' },
    { id: 'catalog-6', name: 'Premium Mangosteen', category: 'Mangosteen', grade: 'Grade A', packaging: '5 kg pack', uom: 'Pack', price: 'Rp118.000', original_price: '', stock_status: 'Low Stock', image: '/images/dragon_fruit.png', badge: 'Limited Stock' },
    { id: 'catalog-7', name: 'Seedless Watermelon', category: 'Watermelon', grade: 'Fresh Selection', packaging: 'Per piece', uom: 'Pcs', price: 'Rp38.500', original_price: '', stock_status: 'In Stock', image: '/images/hero_fruits.png', badge: 'Featured' },
    { id: 'catalog-8', name: 'Passion Fruit', category: 'Passion Fruit', grade: 'Premium Grade', packaging: '1 kg pack', uom: 'Kg', price: 'Rp47.000', original_price: '', stock_status: 'Out of Stock', image: '/images/dragon_fruit.png', badge: 'Notify Me' },
];

const normalizedProducts = computed(() => {
    const source = props.products.length ? props.products : sampleProducts;

    return source.map((product, index) => ({
        ...sampleProducts[index % sampleProducts.length],
        ...product,
        image: product.image || sampleProducts[index % sampleProducts.length].image,
        price: product.price || sampleProducts[index % sampleProducts.length].price,
        packaging: product.packaging || product.grade || sampleProducts[index % sampleProducts.length].packaging,
        stock_status: product.stock_status || sampleProducts[index % sampleProducts.length].stock_status,
        badge: product.badge || sampleProducts[index % sampleProducts.length].badge,
    }));
});

const categories = computed(() => ['All', ...new Set(normalizedProducts.value.map(product => product.category).filter(Boolean))]);
const activeCategory = ref('All');
const search = ref('');
const sort = ref('Recommended');

const filteredProducts = computed(() => {
    const keyword = search.value.trim().toLowerCase();
    let list = normalizedProducts.value.filter((product) => {
        const matchesCategory = activeCategory.value === 'All' || product.category === activeCategory.value;
        const matchesSearch = !keyword || [product.name, product.category, product.grade, product.packaging, product.uom]
            .filter(Boolean)
            .some(value => String(value).toLowerCase().includes(keyword));

        return matchesCategory && matchesSearch;
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
            <div class="rounded-[1.6rem] border border-[#E5E7EB] bg-white p-4 shadow-[0_18px_50px_rgba(17,24,39,.08)]">
                <div class="grid gap-4 lg:grid-cols-[1fr_220px]">
                    <label class="relative block">
                        <span class="sr-only">Search products</span>
                        <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-[#9CA3AF]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.2-5.2m1.7-4.8a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" />
                        </svg>
                        <input
                            v-model="search"
                            type="search"
                            placeholder="Search products, categories, or item names"
                            class="h-12 w-full rounded-2xl border border-[#E5E7EB] bg-white pl-12 pr-4 text-sm font-medium text-[#111827] outline-none transition placeholder:text-[#9CA3AF] focus:border-[#EC4899] focus:ring-4 focus:ring-[#FCE7F3]"
                        />
                    </label>
                    <label>
                        <span class="sr-only">Sort products</span>
                        <select v-model="sort" class="h-12 w-full rounded-2xl border border-[#E5E7EB] bg-white px-4 text-sm font-bold text-[#374151] outline-none focus:border-[#EC4899] focus:ring-4 focus:ring-[#FCE7F3]">
                            <option>Recommended</option>
                            <option>Newest</option>
                            <option>Name A-Z</option>
                            <option>Name Z-A</option>
                            <option>Price Low to High</option>
                            <option>Price High to Low</option>
                            <option>Most Popular</option>
                        </select>
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
                    <button type="button" class="rounded-2xl border border-[#E5E7EB] px-4 py-3 text-left hover:bg-[#F9FAFB]">Grade: All</button>
                    <button type="button" class="rounded-2xl border border-[#E5E7EB] px-4 py-3 text-left hover:bg-[#F9FAFB]">Packaging: Box, Kg, Pack</button>
                    <button type="button" class="rounded-2xl border border-[#E5E7EB] px-4 py-3 text-left hover:bg-[#F9FAFB]">Availability: In stock</button>
                    <button type="button" class="rounded-2xl border border-[#E5E7EB] px-4 py-3 text-left hover:bg-[#F9FAFB]">Promotion: Active offers</button>
                </div>
            </div>

            <div class="mt-8 flex items-center justify-between">
                <p class="text-sm font-semibold text-[#6B7280]">{{ filteredProducts.length }} products found</p>
                <button v-if="search || activeCategory !== 'All'" type="button" class="text-sm font-bold text-[#BE185D]" @click="search = ''; activeCategory = 'All'">
                    Reset Filters
                </button>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <article
                    v-for="product in filteredProducts"
                    :key="product.id"
                    class="group flex flex-col overflow-hidden rounded-[1.4rem] border border-[#E5E7EB] bg-white p-3 shadow-sm transition-all hover:-translate-y-1 hover:border-[#FBCFE8] hover:shadow-xl hover:shadow-pink-900/10"
                    :class="{ 'opacity-70': product.stock_status === 'Out of Stock' }"
                >
                    <div class="relative h-56 overflow-hidden rounded-[1rem] bg-[#FDF2F8]">
                        <img :src="product.image" :alt="product.name" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
                        <span class="absolute left-3 top-3 rounded-full bg-[#EC4899] px-3 py-1 text-xs font-bold text-white">{{ product.badge }}</span>
                        <span
                            class="absolute bottom-3 left-3 rounded-full px-3 py-1 text-xs font-bold"
                            :class="product.stock_status === 'Out of Stock' ? 'bg-[#FEE2E2] text-[#991B1B]' : product.stock_status === 'Low Stock' ? 'bg-[#FEF3C7] text-[#92400E]' : 'bg-[#DCFCE7] text-[#166534]'"
                        >
                            {{ product.stock_status }}
                        </span>
                        <button class="absolute right-3 top-3 flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-[#BE185D]" aria-label="Add to wishlist">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.8 7.2a5 5 0 00-8.8-3.2A5 5 0 003.2 7.2C3.2 13 12 19 12 19s8.8-6 8.8-11.8z" /></svg>
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
                                <button type="button" aria-label="Decrease quantity">-</button>
                                <span>1</span>
                                <button type="button" aria-label="Increase quantity">+</button>
                            </div>
                            <Link
                                href="/login"
                                class="inline-flex min-h-11 items-center justify-center rounded-xl text-sm font-bold transition"
                                :class="product.stock_status === 'Out of Stock' ? 'bg-[#F3F4F6] text-[#6B7280]' : 'bg-[#EC4899] text-white hover:bg-[#BE185D]'"
                            >
                                {{ product.stock_status === 'Out of Stock' ? 'Notify Me' : 'Add to Cart' }}
                            </Link>
                        </div>
                        <Link href="/login" class="mt-3 text-center text-sm font-bold text-[#BE185D] hover:underline">Quick View</Link>
                    </div>
                </article>
            </div>

            <div v-if="filteredProducts.length === 0" class="mt-10 rounded-[1.6rem] border border-[#E5E7EB] bg-white px-6 py-16 text-center shadow-sm">
                <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-[#FCE7F3] text-[#BE185D]">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.2-5.2m1.7-4.8a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" /></svg>
                </div>
                <h3 class="text-xl font-black text-[#111827]">No products found</h3>
                <p class="mt-2 text-[#6B7280]">Try adjusting your search or filter selection.</p>
                <button type="button" class="mt-6 rounded-xl bg-[#EC4899] px-6 py-3 text-sm font-bold text-white" @click="search = ''; activeCategory = 'All'">
                    Reset Filters
                </button>
            </div>
        </section>
    </PublicLayout>
</template>
