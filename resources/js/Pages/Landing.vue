<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import {
    ArrowRight,
    BadgeCheck,
    CheckCircle2,
    Heart,
    Leaf,
    PackageCheck,
    PackageSearch,
    ShieldCheck,
    ShoppingCart,
    Sparkles,
    Star,
    Truck,
} from 'lucide-vue-next';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    products: { type: Array, default: () => [] },
    categories: { type: Array, default: () => [] },
});

const products = computed(() => {
    return props.products.slice(0, 4).map((product) => ({
        ...product,
        price: product.price || 'Hubungi kami',
        badge: product.badge || null,
        stock_status: product.stock_status || 'Tersedia',
    }));
});

const categories = computed(() => props.categories);

const heroBenefits = [
    { label: 'Fresh Selection', icon: Leaf },
    { label: 'Quality Checked', icon: BadgeCheck },
    { label: 'Reliable Supply', icon: Truck },
    { label: 'Easy Ordering', icon: ShoppingCart },
];

const benefits = [
    { title: 'Fresh Product Selection', desc: 'Produk dipilih berdasarkan standar kualitas dan kesegaran.', icon: Leaf },
    { title: 'Clear Product Information', desc: 'Informasi grade, kemasan, satuan, dan harga ditampilkan dengan jelas.', icon: PackageSearch },
    { title: 'Easy Ordering', desc: 'Proses pemesanan sederhana dari berbagai perangkat.', icon: ShoppingCart },
    { title: 'Reliable Service', desc: 'Dukungan untuk kebutuhan pelanggan pribadi maupun bisnis.', icon: ShieldCheck },
];

const steps = [
    { label: 'Explore Products', icon: PackageSearch },
    { label: 'Add Products to Cart', icon: ShoppingCart },
    { label: 'Complete Your Order', icon: CheckCircle2 },
    { label: 'Track Your Delivery', icon: Truck },
];
</script>

<template>
    <PublicLayout active="home">
        <section class="bg-[radial-gradient(circle_at_74%_28%,#FCE7F3_0,transparent_32%),linear-gradient(180deg,#FFF7FB_0%,#FFFBFD_64%,#FFFFFF_100%)]">
            <div class="public-container grid items-center gap-12 py-12 lg:grid-cols-[1fr_.92fr] lg:py-20">
                <div>
                    <p class="inline-flex items-center gap-2 text-sm font-black uppercase tracking-[.2em] text-[#DB2777]">
                        <Sparkles class="h-4 w-4" />
                        Premium fresh produce
                    </p>
                    <h1 class="mt-4 max-w-4xl text-4xl font-black leading-[1.05] tracking-tight text-[#111827] sm:text-5xl lg:text-6xl">
                        Fresh Products, Selected with Care
                    </h1>
                    <p class="mt-5 max-w-2xl text-base leading-7 text-[#374151] md:text-lg">
                        Temukan pilihan buah segar dan produk berkualitas untuk kebutuhan rumah, usaha, dan pembelian rutin Anda.
                    </p>
                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <Link href="/products" class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl bg-[#EC4899] px-6 py-3 text-sm font-black text-white shadow-[0_18px_40px_rgba(236,72,153,.28)] transition hover:-translate-y-0.5 hover:bg-[#BE185D] hover:shadow-[0_24px_52px_rgba(190,24,93,.28)]">
                            Shop Products
                            <ArrowRight class="h-4 w-4" />
                        </Link>
                        <a href="/#categories" class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl border border-[#FBCFE8] bg-white px-6 py-3 text-sm font-black text-[#BE185D] shadow-sm transition hover:-translate-y-0.5 hover:bg-[#FDF2F8] hover:shadow-[0_16px_34px_rgba(236,72,153,.14)]">
                            Explore Categories
                            <PackageSearch class="h-4 w-4" />
                        </a>
                    </div>

                    <div class="mt-8 rounded-[1.6rem] border border-[#FBCFE8] bg-white/80 p-2 shadow-[0_18px_48px_rgba(236,72,153,.12)] backdrop-blur">
                        <div class="grid gap-2 sm:grid-cols-2 xl:grid-cols-4">
                            <div
                                v-for="item in heroBenefits"
                                :key="item.label"
                                class="group flex min-h-[82px] items-center gap-3 rounded-[1.2rem] border border-[#FCE7F3] bg-[#FFFBFD] px-4 py-3 shadow-sm transition hover:-translate-y-1 hover:border-[#F9A8D4] hover:bg-[#FDF2F8] hover:shadow-[0_14px_32px_rgba(236,72,153,.16)]"
                            >
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-[#FCE7F3] text-[#BE185D] transition group-hover:bg-[#EC4899] group-hover:text-white">
                                    <component :is="item.icon" class="h-5 w-5" />
                                </span>
                                <span class="text-sm font-black leading-snug text-[#1F2937]">{{ item.label }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute -inset-6 rounded-[2.4rem] bg-[#F9A8D4]/30 blur-3xl"></div>
                    <div class="relative overflow-hidden rounded-[2rem] border border-white bg-white p-3 shadow-[0_32px_90px_rgba(236,72,153,.20)]">
                        <div class="relative overflow-hidden rounded-[1.4rem]">
                            <img :src="'/images/hero_fruits.png'" alt="Fresh fruit selection from Willshine Hub" class="h-[22rem] w-full object-cover saturate-[1.08] sm:h-[26rem]" />
                            <div class="absolute inset-0 bg-[#EC4899]/18 mix-blend-soft-light"></div>
                            <div class="absolute inset-0 bg-gradient-to-tr from-[#FCE7F3]/28 via-transparent to-[#EC4899]/18"></div>
                        </div>
                    </div>
                    <div class="absolute -bottom-6 left-4 right-4 rounded-2xl border border-[#FBCFE8] bg-white/95 px-5 py-4 shadow-[0_24px_54px_rgba(236,72,153,.22)] backdrop-blur sm:left-6 sm:right-auto sm:min-w-64">
                        <p class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-[.16em] text-[#BE185D]">
                            <PackageCheck class="h-4 w-4" />
                            Today selection
                        </p>
                        <p class="mt-1 text-xl font-black text-[#111827]">Quality checked</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="categories" class="public-container scroll-mt-28 py-10">
            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-3xl font-black tracking-tight text-[#111827]">Shop by Category</h2>
                    <p class="mt-2 text-sm text-[#6B7280]">Find fresh products by type, packaging, and availability.</p>
                </div>
                <a href="/#categories" class="inline-flex items-center gap-2 text-sm font-black text-[#BE185D] hover:text-[#9D174D]">
                    View all categories
                    <ArrowRight class="h-4 w-4" />
                </a>
            </div>
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <Link v-for="category in categories" :key="category.name" href="/products" class="group overflow-hidden rounded-[1.4rem] border border-[#FBCFE8] bg-white p-3 shadow-sm transition hover:-translate-y-1 hover:border-[#F9A8D4] hover:shadow-[0_24px_50px_rgba(236,72,153,.16)]">
                    <div class="relative h-36 overflow-hidden rounded-[1rem] bg-[#FDF2F8]">
                        <img v-if="category.image" :src="category.image" :alt="category.name" class="h-full w-full object-cover saturate-[1.05] transition duration-500 group-hover:scale-105" />
                        <div v-else class="flex h-full w-full items-center justify-center text-[#BE185D]">
                            <PackageSearch class="h-12 w-12" />
                        </div>
                        <div class="absolute inset-0 bg-[#EC4899]/12 mix-blend-soft-light"></div>
                    </div>
                    <div class="flex items-center justify-between p-3">
                        <div>
                            <h3 class="font-black text-[#111827]">{{ category.name }}</h3>
                            <p class="mt-1 text-sm text-[#6B7280]">{{ category.count }}</p>
                        </div>
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#FCE7F3] text-[#BE185D] transition group-hover:bg-[#EC4899] group-hover:text-white">
                            <ArrowRight class="h-5 w-5" />
                        </span>
                    </div>
                </Link>
            </div>
            <div v-if="categories.length === 0" class="rounded-[1.4rem] border border-[#FBCFE8] bg-white px-6 py-12 text-center shadow-sm">
                <PackageSearch class="mx-auto h-12 w-12 text-[#BE185D]" />
                <h3 class="mt-4 text-lg font-black text-[#111827]">Belum ada kategori aktif</h3>
                <p class="mt-2 text-sm text-[#6B7280]">Kategori akan tampil otomatis setelah tersedia dari backend.</p>
            </div>
        </section>

        <section id="promotions" class="public-container scroll-mt-28 py-10">
            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-3xl font-black tracking-tight text-[#111827]">Featured Products</h2>
                    <p class="mt-2 text-sm text-[#6B7280]">Pilihan produk unggulan yang tersedia untuk Anda.</p>
                </div>
                <Link href="/products" class="inline-flex items-center gap-2 text-sm font-black text-[#BE185D] hover:text-[#9D174D]">
                    Shop all products
                    <ArrowRight class="h-4 w-4" />
                </Link>
            </div>
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <article v-for="product in products" :key="product.id" class="group flex flex-col overflow-hidden rounded-[1.4rem] border border-[#FBCFE8] bg-white p-3 shadow-sm transition hover:-translate-y-1 hover:border-[#F9A8D4] hover:shadow-[0_24px_50px_rgba(236,72,153,.16)]">
                    <div class="relative h-52 overflow-hidden rounded-[1rem] bg-[#FDF2F8]">
                        <img v-if="product.image" :src="product.image" :alt="product.name" class="h-full w-full object-cover saturate-[1.05] transition duration-500 group-hover:scale-105" />
                        <div v-else class="flex h-full w-full items-center justify-center text-[#BE185D]">
                            <PackageSearch class="h-14 w-14" />
                        </div>
                        <div class="absolute inset-0 bg-[#EC4899]/10 mix-blend-soft-light"></div>
                        <span v-if="product.badge" class="absolute left-3 top-3 rounded-full bg-[#EC4899] px-3 py-1 text-xs font-black text-white shadow-md shadow-pink-900/15">{{ product.badge }}</span>
                        <button class="absolute right-3 top-3 flex h-10 w-10 items-center justify-center rounded-full bg-white/92 text-[#BE185D] shadow-sm transition hover:bg-[#FCE7F3]" aria-label="Save product">
                            <Heart class="h-5 w-5" />
                        </button>
                    </div>
                    <div class="flex flex-1 flex-col p-3">
                        <p class="text-xs font-black uppercase tracking-[.14em] text-[#BE185D]">{{ product.category }}</p>
                        <h3 class="mt-2 text-lg font-black leading-tight text-[#111827]">{{ product.name }}</h3>
                        <p class="mt-2 line-clamp-2 text-sm text-[#6B7280]">{{ product.grade }}</p>
                        <div class="mt-4 flex items-center justify-between gap-3">
                            <p class="font-black text-[#BE185D]">{{ product.price }} <span class="text-xs font-semibold text-[#9CA3AF]">/ {{ product.uom }}</span></p>
                            <span class="rounded-full bg-[#DCFCE7] px-3 py-1 text-xs font-black text-[#166534]">{{ product.stock_status }}</span>
                        </div>
                        <Link href="/login" class="mt-5 inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-[#FCE7F3] text-sm font-black text-[#BE185D] transition hover:-translate-y-0.5 hover:bg-[#FBCFE8] hover:shadow-[0_14px_30px_rgba(236,72,153,.14)]">
                            <ShoppingCart class="h-4 w-4" />
                            Add to Cart
                        </Link>
                    </div>
                </article>
            </div>
            <div v-if="products.length === 0" class="rounded-[1.4rem] border border-[#FBCFE8] bg-white px-6 py-12 text-center shadow-sm">
                <PackageSearch class="mx-auto h-12 w-12 text-[#BE185D]" />
                <h3 class="mt-4 text-lg font-black text-[#111827]">Belum ada produk aktif</h3>
                <p class="mt-2 text-sm text-[#6B7280]">Produk akan tampil otomatis setelah katalog backend diaktifkan.</p>
            </div>
        </section>

        <section id="about" class="public-container scroll-mt-28 py-10">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <article v-for="benefit in benefits" :key="benefit.title" class="group rounded-[1.4rem] border border-[#FBCFE8] bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-[#F9A8D4] hover:shadow-[0_24px_50px_rgba(236,72,153,.15)]">
                    <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#FCE7F3] text-[#BE185D] transition group-hover:bg-[#EC4899] group-hover:text-white">
                        <component :is="benefit.icon" class="h-6 w-6" />
                    </div>
                    <h3 class="text-lg font-black text-[#111827]">{{ benefit.title }}</h3>
                    <p class="mt-3 text-sm leading-6 text-[#6B7280]">{{ benefit.desc }}</p>
                </article>
            </div>
        </section>

        <section class="public-container py-10">
            <div class="grid overflow-hidden rounded-[2rem] border border-[#FBCFE8] bg-[#FFF7FB] shadow-[0_24px_60px_rgba(236,72,153,.10)] lg:grid-cols-[.9fr_1.1fr]">
                <div class="p-8 md:p-10">
                    <p class="text-sm font-black uppercase tracking-[.18em] text-[#BE185D]">New to Willshine Hub?</p>
                    <h2 class="mt-3 text-3xl font-black tracking-tight text-[#111827]">Create your account for faster ordering.</h2>
                    <p class="mt-4 leading-7 text-[#374151]">Buat akun untuk menikmati pengalaman belanja yang lebih cepat, melihat penawaran khusus, menyimpan alamat, dan memantau pesanan Anda.</p>
                    <div class="mt-6 grid gap-3 text-sm font-semibold text-[#374151] sm:grid-cols-2">
                        <span class="inline-flex items-center gap-2"><CheckCircle2 class="h-4 w-4 text-[#EC4899]" /> Faster checkout</span>
                        <span class="inline-flex items-center gap-2"><CheckCircle2 class="h-4 w-4 text-[#EC4899]" /> Saved addresses</span>
                        <span class="inline-flex items-center gap-2"><CheckCircle2 class="h-4 w-4 text-[#EC4899]" /> Order history</span>
                        <span class="inline-flex items-center gap-2"><CheckCircle2 class="h-4 w-4 text-[#EC4899]" /> Reward points</span>
                    </div>
                    <a href="mailto:sales@tmsx.co.id" class="mt-8 inline-flex rounded-xl bg-[#EC4899] px-6 py-3 text-sm font-black text-white shadow-lg shadow-pink-900/15 transition hover:-translate-y-0.5 hover:bg-[#BE185D]">Create Your Account</a>
                </div>
                <div class="bg-white p-8 md:p-10">
                    <p class="text-sm font-black uppercase tracking-[.18em] text-[#3F7D5A]">Solutions for Business Customers</p>
                    <h2 class="mt-3 text-3xl font-black tracking-tight text-[#111827]">Fresh products for recurring business needs.</h2>
                    <p class="mt-4 leading-7 text-[#374151]">Produk segar untuk restoran, hotel, cafe, reseller, toko buah, distributor, dan kebutuhan usaha lainnya.</p>
                    <div class="mt-6 grid gap-3 text-sm font-semibold text-[#374151] sm:grid-cols-2">
                        <span class="inline-flex items-center gap-2"><CheckCircle2 class="h-4 w-4 text-[#3F7D5A]" /> Bulk purchase</span>
                        <span class="inline-flex items-center gap-2"><CheckCircle2 class="h-4 w-4 text-[#3F7D5A]" /> Grade options</span>
                        <span class="inline-flex items-center gap-2"><CheckCircle2 class="h-4 w-4 text-[#3F7D5A]" /> Recurring orders</span>
                        <span class="inline-flex items-center gap-2"><CheckCircle2 class="h-4 w-4 text-[#3F7D5A]" /> Clear availability</span>
                    </div>
                    <a href="mailto:sales@tmsx.co.id" class="mt-8 inline-flex rounded-xl border border-[#FBCFE8] bg-white px-6 py-3 text-sm font-black text-[#BE185D] shadow-sm transition hover:-translate-y-0.5 hover:bg-[#FDF2F8] hover:shadow-[0_14px_30px_rgba(236,72,153,.14)]">Register as Business Customer</a>
                </div>
            </div>
        </section>

        <section id="how-to-order" class="public-container scroll-mt-28 py-10">
            <h2 class="text-3xl font-black tracking-tight text-[#111827]">How to Order</h2>
            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div v-for="(step, index) in steps" :key="step.label" class="group rounded-[1.4rem] border border-[#FBCFE8] bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-[#F9A8D4] hover:shadow-[0_24px_50px_rgba(236,72,153,.14)]">
                    <span class="flex h-12 w-12 items-center justify-center rounded-full bg-[#EC4899] text-white shadow-lg shadow-pink-900/15">
                        <component :is="step.icon" class="h-5 w-5" />
                    </span>
                    <p class="mt-5 text-xs font-black uppercase tracking-[.16em] text-[#BE185D]">Step {{ index + 1 }}</p>
                    <h3 class="mt-2 font-black text-[#111827]">{{ step.label }}</h3>
                </div>
            </div>
        </section>

        <section class="public-container grid gap-6 py-10 lg:grid-cols-3">
            <article v-for="testimonial in [
                ['Ari Pratama', 'Restaurant owner', 'Produk rapi, kualitas konsisten, dan informasi kemasan jelas untuk tim dapur kami.'],
                ['Nadia Putri', 'Fruit store owner', 'Mudah memilih produk dan repeat order untuk kebutuhan toko setiap minggu.'],
                ['Kevin Hartono', 'Hotel purchasing', 'Pilihan grade membantu kami menyesuaikan kebutuhan buffet dan event.']
            ]" :key="testimonial[0]" class="rounded-[1.4rem] border border-[#FBCFE8] bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-[0_24px_50px_rgba(236,72,153,.14)]">
                <div class="flex text-[#D97706]">
                    <Star v-for="i in 5" :key="i" class="h-4 w-4 fill-current" />
                </div>
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
            <div class="rounded-[2rem] bg-[#9D174D] p-8 text-white shadow-[0_24px_60px_rgba(157,23,77,.20)] md:p-10">
                <h2 class="text-3xl font-black tracking-tight">Stay Updated</h2>
                <p class="mt-3 max-w-2xl text-pink-100">Dapatkan informasi produk terbaru, promo, dan penawaran khusus dari Willshine Hub.</p>
                <form class="mt-6 flex max-w-xl flex-col gap-3 sm:flex-row">
                    <input type="email" placeholder="Email address" class="min-h-12 flex-1 rounded-xl border border-white/10 bg-white px-4 text-sm text-[#111827] outline-none focus:ring-4 focus:ring-white/20" />
                    <button type="button" class="rounded-xl bg-white px-6 py-3 text-sm font-black text-[#BE185D] shadow-lg shadow-pink-950/10 transition hover:-translate-y-0.5">Subscribe</button>
                </form>
            </div>
        </section>
    </PublicLayout>
</template>
