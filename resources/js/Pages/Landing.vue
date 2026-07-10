<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    products: { type: Array, default: () => [] },
});

const sampleProducts = [
    { id: 'sample-1', name: 'Cavendish Banana Premium', category: 'Banana', grade: 'Premium Grade - 12 kg per box', uom: 'Box', price: 'Rp75.000', stock_status: 'In Stock', image: '/images/banana_organic.png', badge: 'Best Seller' },
    { id: 'sample-2', name: 'Papaya Red Lady', category: 'Papaya', grade: 'Fresh Selection - minimum order 1 box', uom: 'Box', price: 'Rp68.000', stock_status: 'In Stock', image: '/images/honey_mango.png', badge: 'New Arrival' },
    { id: 'sample-3', name: 'Premium Mangosteen', category: 'Mangosteen', grade: 'Grade A - 5 kg pack', uom: 'Pack', price: 'Rp125.000', stock_status: 'Low Stock', image: '/images/dragon_fruit.png', badge: 'Popular' },
    { id: 'sample-4', name: 'Sweet Pineapple', category: 'Pineapple', grade: 'Fresh Selection - crate packaging', uom: 'Crate', price: 'Rp95.000', stock_status: 'In Stock', image: '/images/apple_fuji.png', badge: 'Special Offer' },
];

const products = computed(() => {
    if (!props.products.length) return sampleProducts;

    return props.products.slice(0, 4).map((product, index) => ({
        ...sampleProducts[index],
        ...product,
        price: product.price || sampleProducts[index]?.price,
        image: product.image || sampleProducts[index]?.image,
        badge: sampleProducts[index]?.badge || 'Featured',
        stock_status: product.stock_status || 'In Stock',
    }));
});

const categories = [
    { name: 'Banana', count: '12 products', image: '/images/banana_organic.png' },
    { name: 'Papaya', count: '8 products', image: '/images/honey_mango.png' },
    { name: 'Pineapple', count: '6 products', image: '/images/apple_fuji.png' },
    { name: 'Mangosteen', count: '5 products', image: '/images/dragon_fruit.png' },
    { name: 'Watermelon', count: '7 products', image: '/images/hero_fruits.png' },
    { name: 'Special Offers', count: 'Weekly deals', image: '/images/banana_organic.png' },
];

const benefits = [
    ['Fresh Product Selection', 'Produk dipilih berdasarkan standar kualitas dan kesegaran.'],
    ['Clear Product Information', 'Informasi grade, kemasan, satuan, dan harga ditampilkan dengan jelas.'],
    ['Easy Ordering', 'Proses pemesanan sederhana dari berbagai perangkat.'],
    ['Reliable Service', 'Dukungan untuk kebutuhan pelanggan pribadi maupun bisnis.'],
];

const steps = ['Explore Products', 'Add Products to Cart', 'Complete Your Order', 'Track Your Delivery'];
</script>

<template>
    <PublicLayout active="home">
        <section class="public-container grid items-center gap-12 py-12 lg:grid-cols-[1fr_.92fr] lg:py-16">
            <div>
                <p class="text-sm font-bold uppercase tracking-[.2em] text-[#BE185D]">Premium fresh produce</p>
                <h1 class="mt-4 max-w-4xl text-4xl font-black leading-[1.05] tracking-tight text-[#111827] sm:text-5xl lg:text-6xl">
                    Fresh Products, Selected with Care
                </h1>
                <p class="mt-5 max-w-2xl text-base leading-7 text-[#374151] md:text-lg">
                    Temukan pilihan buah segar dan produk berkualitas untuk kebutuhan rumah, usaha, dan pembelian rutin Anda.
                </p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <Link href="/products" class="inline-flex min-h-12 items-center justify-center rounded-xl bg-[#EC4899] px-6 py-3 text-sm font-bold text-white shadow-lg shadow-pink-900/15 transition hover:-translate-y-0.5 hover:bg-[#BE185D]">
                        Shop Products
                    </Link>
                    <a href="#categories" class="inline-flex min-h-12 items-center justify-center rounded-xl border border-[#FBCFE8] bg-white px-6 py-3 text-sm font-bold text-[#BE185D] transition hover:bg-[#FDF2F8]">
                        Explore Categories
                    </a>
                </div>
                <div class="mt-8 grid grid-cols-2 gap-3 sm:grid-cols-4">
                    <div v-for="item in ['Fresh Selection', 'Quality Checked', 'Reliable Supply', 'Easy Ordering']" :key="item" class="rounded-2xl border border-[#E5E7EB] bg-white px-4 py-3 text-sm font-bold text-[#374151] shadow-sm">
                        {{ item }}
                    </div>
                </div>
            </div>

            <div class="relative">
                <div class="overflow-hidden rounded-[2rem] border border-white bg-white p-3 shadow-[0_28px_80px_rgba(157,23,77,.16)]">
                    <img :src="'/images/hero_fruits.png'" alt="Fresh fruit selection from Willshine Hub" class="h-[26rem] w-full rounded-[1.4rem] object-cover" />
                </div>
                <div class="absolute -bottom-6 left-6 rounded-2xl border border-[#FBCFE8] bg-white px-5 py-4 shadow-xl shadow-pink-900/10">
                    <p class="text-xs font-bold uppercase tracking-[.16em] text-[#BE185D]">Today selection</p>
                    <p class="mt-1 text-xl font-black text-[#111827]">Quality checked</p>
                </div>
            </div>
        </section>

        <section id="categories" class="public-container py-10">
            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-3xl font-black tracking-tight text-[#111827]">Shop by Category</h2>
                    <p class="mt-2 text-sm text-[#6B7280]">Find fresh products by type, packaging, and availability.</p>
                </div>
                <Link href="/products" class="text-sm font-bold text-[#BE185D] hover:underline">View all categories</Link>
            </div>
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <Link v-for="category in categories" :key="category.name" href="/products" class="group overflow-hidden rounded-[1.4rem] border border-[#E5E7EB] bg-white p-3 shadow-sm transition hover:-translate-y-1 hover:border-[#FBCFE8] hover:shadow-xl hover:shadow-pink-900/10">
                    <div class="h-36 overflow-hidden rounded-[1rem] bg-[#FDF2F8]">
                        <img :src="category.image" :alt="category.name" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" />
                    </div>
                    <div class="flex items-center justify-between p-3">
                        <div>
                            <h3 class="font-black text-[#111827]">{{ category.name }}</h3>
                            <p class="mt-1 text-sm text-[#6B7280]">{{ category.count }}</p>
                        </div>
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#FCE7F3] text-[#BE185D]">-></span>
                    </div>
                </Link>
            </div>
        </section>

        <section class="public-container py-10">
            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-3xl font-black tracking-tight text-[#111827]">Featured Products</h2>
                    <p class="mt-2 text-sm text-[#6B7280]">Pilihan produk unggulan yang tersedia untuk Anda.</p>
                </div>
                <Link href="/products" class="text-sm font-bold text-[#BE185D] hover:underline">Shop all products</Link>
            </div>
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <article v-for="product in products" :key="product.id" class="group flex flex-col overflow-hidden rounded-[1.4rem] border border-[#E5E7EB] bg-white p-3 shadow-sm transition hover:-translate-y-1 hover:border-[#FBCFE8] hover:shadow-xl hover:shadow-pink-900/10">
                    <div class="relative h-52 overflow-hidden rounded-[1rem] bg-[#FDF2F8]">
                        <img :src="product.image" :alt="product.name" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" />
                        <span class="absolute left-3 top-3 rounded-full bg-[#EC4899] px-3 py-1 text-xs font-bold text-white">{{ product.badge }}</span>
                        <button class="absolute right-3 top-3 flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-[#BE185D]" aria-label="Save product">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.8 7.2a5 5 0 00-8.8-3.2A5 5 0 003.2 7.2C3.2 13 12 19 12 19s8.8-6 8.8-11.8z" /></svg>
                        </button>
                    </div>
                    <div class="flex flex-1 flex-col p-3">
                        <p class="text-xs font-bold uppercase tracking-[.14em] text-[#BE185D]">{{ product.category }}</p>
                        <h3 class="mt-2 text-lg font-black leading-tight text-[#111827]">{{ product.name }}</h3>
                        <p class="mt-2 line-clamp-2 text-sm text-[#6B7280]">{{ product.grade }}</p>
                        <div class="mt-4 flex items-center justify-between">
                            <p class="font-black text-[#BE185D]">{{ product.price }} <span class="text-xs font-semibold text-[#9CA3AF]">/ {{ product.uom }}</span></p>
                            <span class="rounded-full bg-[#DCFCE7] px-3 py-1 text-xs font-bold text-[#166534]">{{ product.stock_status }}</span>
                        </div>
                        <Link href="/login" class="mt-5 inline-flex min-h-11 items-center justify-center rounded-xl bg-[#FCE7F3] text-sm font-bold text-[#BE185D] transition hover:bg-[#FBCFE8]">Add to Cart</Link>
                    </div>
                </article>
            </div>
        </section>

        <section class="public-container py-10">
            <div class="grid gap-6 lg:grid-cols-4">
                <article v-for="benefit in benefits" :key="benefit[0]" class="rounded-[1.4rem] border border-[#E5E7EB] bg-white p-6 shadow-sm">
                    <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#E8F5EC] text-[#3F7D5A]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <h3 class="text-lg font-black text-[#111827]">{{ benefit[0] }}</h3>
                    <p class="mt-3 text-sm leading-6 text-[#6B7280]">{{ benefit[1] }}</p>
                </article>
            </div>
        </section>

        <section id="promotions" class="public-container py-10">
            <div class="grid overflow-hidden rounded-[2rem] border border-[#FBCFE8] bg-[#FFF7FB] lg:grid-cols-[.9fr_1.1fr]">
                <div class="p-8 md:p-10">
                    <p class="text-sm font-bold uppercase tracking-[.18em] text-[#BE185D]">New to Willshine Hub?</p>
                    <h2 class="mt-3 text-3xl font-black tracking-tight text-[#111827]">Create your account for faster ordering.</h2>
                    <p class="mt-4 leading-7 text-[#374151]">Buat akun untuk menikmati pengalaman belanja yang lebih cepat, melihat penawaran khusus, menyimpan alamat, dan memantau pesanan Anda.</p>
                    <div class="mt-6 grid gap-3 text-sm font-semibold text-[#374151] sm:grid-cols-2">
                        <span>Faster checkout</span><span>Saved addresses</span><span>Order history</span><span>Reward points</span>
                    </div>
                    <a href="mailto:sales@tmsx.co.id" class="mt-8 inline-flex rounded-xl bg-[#EC4899] px-6 py-3 text-sm font-bold text-white">Create Your Account</a>
                </div>
                <div id="about" class="bg-white p-8 md:p-10">
                    <p class="text-sm font-bold uppercase tracking-[.18em] text-[#3F7D5A]">Solutions for Business Customers</p>
                    <h2 class="mt-3 text-3xl font-black tracking-tight text-[#111827]">Fresh products for recurring business needs.</h2>
                    <p class="mt-4 leading-7 text-[#374151]">Produk segar untuk restoran, hotel, cafe, reseller, toko buah, distributor, dan kebutuhan usaha lainnya.</p>
                    <div class="mt-6 grid gap-3 text-sm font-semibold text-[#374151] sm:grid-cols-2">
                        <span>Bulk purchase</span><span>Grade and packaging options</span><span>Recurring orders</span><span>Clear availability</span>
                    </div>
                    <a href="mailto:sales@tmsx.co.id" class="mt-8 inline-flex rounded-xl border border-[#FBCFE8] bg-white px-6 py-3 text-sm font-bold text-[#BE185D]">Register as Business Customer</a>
                </div>
            </div>
        </section>

        <section id="how-to-order" class="public-container py-10">
            <h2 class="text-3xl font-black tracking-tight text-[#111827]">How to Order</h2>
            <div class="mt-6 grid gap-4 md:grid-cols-4">
                <div v-for="(step, index) in steps" :key="step" class="rounded-[1.4rem] border border-[#E5E7EB] bg-white p-6 shadow-sm">
                    <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#EC4899] text-sm font-black text-white">{{ index + 1 }}</span>
                    <h3 class="mt-5 font-black text-[#111827]">{{ step }}</h3>
                </div>
            </div>
        </section>

        <section class="public-container grid gap-6 py-10 lg:grid-cols-3">
            <article v-for="testimonial in [
                ['Ari Pratama', 'Restaurant owner', 'Produk rapi, kualitas konsisten, dan informasi kemasan jelas untuk tim dapur kami.'],
                ['Nadia Putri', 'Fruit store owner', 'Mudah memilih produk dan repeat order untuk kebutuhan toko setiap minggu.'],
                ['Kevin Hartono', 'Hotel purchasing', 'Pilihan grade membantu kami menyesuaikan kebutuhan buffet dan event.']
            ]" :key="testimonial[0]" class="rounded-[1.4rem] border border-[#E5E7EB] bg-white p-6 shadow-sm">
                <div class="text-[#D97706]">★★★★★</div>
                <p class="mt-4 leading-7 text-[#374151]">"{{ testimonial[2] }}"</p>
                <div class="mt-6 flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#FCE7F3] font-black text-[#BE185D]">{{ testimonial[0].slice(0, 2) }}</span>
                    <div>
                        <p class="font-bold text-[#111827]">{{ testimonial[0] }}</p>
                        <p class="text-sm text-[#6B7280]">{{ testimonial[1] }}</p>
                    </div>
                </div>
            </article>
        </section>

        <section class="public-container py-10">
            <div class="rounded-[2rem] bg-[#111827] p-8 text-white md:p-10">
                <h2 class="text-3xl font-black tracking-tight">Stay Updated</h2>
                <p class="mt-3 max-w-2xl text-pink-100">Dapatkan informasi produk terbaru, promo, dan penawaran khusus dari Willshine Hub.</p>
                <form class="mt-6 flex max-w-xl flex-col gap-3 sm:flex-row">
                    <input type="email" placeholder="Email address" class="min-h-12 flex-1 rounded-xl border border-white/10 bg-white px-4 text-sm text-[#111827] outline-none" />
                    <button type="button" class="rounded-xl bg-[#EC4899] px-6 py-3 text-sm font-bold text-white">Subscribe</button>
                </form>
            </div>
        </section>
    </PublicLayout>
</template>
