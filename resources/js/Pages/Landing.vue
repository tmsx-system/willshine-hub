<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

defineOptions({ layout: GuestLayout });

const products = [
    {
        name: 'Premium Fuji Apple',
        grade: 'Grade AAA / Import',
        price: 'Rp 45.000/kg',
        stockStatus: 'In Stock',
        image: '/images/apple_fuji.png',
        action: 'Tambah Pesanan',
    },
    {
        name: 'Organic Banana',
        grade: 'Cavendish Local Organik',
        price: 'Rp 18.500/kg',
        stockStatus: 'Limited',
        image: '/images/banana_organic.png',
        action: 'Tambah Pesanan',
    },
    {
        name: 'Dragon Fruit',
        grade: 'Super Red / Superfood',
        price: 'Rp 25.000/kg',
        stockStatus: 'Hot Sale',
        image: '/images/dragon_fruit.png',
        action: 'Tambah Pesanan',
    },
    {
        name: 'Honey Mango',
        grade: 'Probolinggo Export Quality',
        price: 'Rp 35.000/kg',
        stockStatus: 'Out of Stock',
        image: '/images/honey_mango.png',
        action: 'Notify Me',
    },
];
</script>

<template>
    <div class="min-h-screen bg-[#fcf6f9] font-sans">
        <!-- ── Navbar ─────────────────────────────────── -->
        <nav class="max-w-[1400px] mx-auto px-6 py-5 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-2xl font-bold text-[#b91c63]">Willshine</span>
                <span class="text-2xl font-bold text-gray-700">Hub</span>
            </div>

            <div class="hidden md:flex items-center gap-8 text-sm font-semibold">
                <Link href="/" class="text-[#b91c63] border-b-2 border-[#b91c63] pb-1">Home</Link>
                <Link href="/products" class="text-gray-500 hover:text-[#b91c63]">Products</Link>
                <Link href="/public-rewards" class="text-gray-500 hover:text-[#b91c63]">Rewards</Link>
            </div>

            <div class="flex items-center gap-4">
                <Link href="/login" class="text-[#b91c63] font-semibold text-sm hover:underline">Login</Link>
                <Link href="/login" class="bg-[#b91c63] text-white px-5 py-2.5 rounded-full text-sm font-semibold shadow-md hover:bg-[#9a1752] transition-colors">
                    Get Started
                </Link>
            </div>
        </nav>

        <!-- ── Hero Section ────────────────────────────── -->
        <section class="max-w-[1400px] mx-auto px-6 py-12 flex flex-col-reverse lg:flex-row items-center gap-12 lg:gap-24">
            <!-- Left: Text content -->
            <div class="flex-1">
                <div class="inline-flex items-center gap-2 bg-[#fce7f0] text-[#b91c63] px-3 py-1.5 rounded-full text-xs font-bold mb-6">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    B2B Fresh Produce Exclusive
                </div>

                <h1 class="text-5xl lg:text-7xl font-extrabold text-gray-900 leading-tight tracking-tight mb-6">
                    Order <span class="text-[#b91c63]">Fresh<br>Products</span> Easily
                </h1>

                <p class="text-gray-600 text-base max-w-lg mb-10 leading-relaxed">
                    Portal pelanggan untuk melihat stok, membuat pesanan, dan memantau order secara real-time. Solusi terintegrasi untuk rantai pasok buah berkualitas tinggi.
                </p>

                <div class="flex flex-wrap items-center gap-4">
                    <Link href="/login" class="bg-[#b91c63] text-white px-8 py-4 rounded-2xl font-bold flex items-center gap-2 hover:bg-[#9a1752] transition-shadow shadow-xl shadow-pink-900/20">
                        Login Buyer
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </Link>
                    <Link href="/products" class="bg-[#f0e4ea] text-gray-800 px-8 py-4 rounded-2xl font-bold hover:bg-[#e6d8df] transition-colors">
                        Lihat Produk
                    </Link>
                </div>
            </div>

            <!-- Right: Hero Image -->
            <div class="flex-1 relative w-full max-w-2xl">
                <!-- Decorative background elements -->
                <div class="absolute -top-10 -right-10 w-full h-full bg-[#fce7f0] rounded-[3rem] -z-10"></div>
                
                <div class="relative rounded-[3rem] overflow-hidden border-8 border-white shadow-2xl">
                    <img :src="'/images/hero_fruits.png'" alt="Fresh fruits bowl" class="w-full h-auto object-cover aspect-[4/3]" />
                </div>

                <!-- Floating Badge -->
                <div class="absolute -bottom-6 left-10 bg-white p-4 rounded-2xl shadow-xl flex items-center gap-4">
                    <div class="w-12 h-12 bg-[#b91c63] rounded-full flex items-center justify-center text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Real-time Stock</p>
                        <p class="text-[#b91c63] font-bold text-lg">99.9% Reliable</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ── Features Section ────────────────────────── -->
        <section class="max-w-[1400px] mx-auto px-6 py-20">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-extrabold text-gray-900 mb-3">Efficient Supply Management</h2>
                <p class="text-gray-500 max-w-2xl mx-auto">
                    Nikmati kemudahan mengelola operasional harian Anda dengan fitur-fitur yang dirancang khusus untuk efisiensi bisnis buah.
                </p>
            </div>

            <!-- Bento Grid Layout -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
                <!-- Stock Allocation (Span 2) -->
                <div class="md:col-span-2 bg-[#f4f7fc] border border-blue-100 rounded-[2rem] p-8 flex flex-col md:flex-row gap-8 items-center">
                    <div class="flex-1">
                        <div class="w-12 h-12 bg-[#b91c63] text-white rounded-full flex items-center justify-center mb-6 shadow-md shadow-pink-900/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Stock Allocation</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">
                            Alokasi stok otomatis berdasarkan preferensi pelanggan dan histori pesanan. Pastikan ketersediaan produk unggulan Anda selalu terjaga.
                        </p>
                    </div>
                    <div class="flex-1 w-full bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                        <div class="flex justify-between mb-2">
                            <div class="w-16 h-3 bg-pink-100 rounded-full"></div>
                            <div class="w-8 h-3 bg-blue-100 rounded-full"></div>
                        </div>
                        <div class="space-y-4 mt-6">
                            <div class="relative h-2 bg-gray-100 rounded-full">
                                <div class="absolute inset-y-0 left-0 w-[85%] bg-[#b91c63] rounded-full"></div>
                                <span class="absolute -right-2 -top-5 text-[10px] font-bold text-[#b91c63]">85% Allocated</span>
                            </div>
                            <div class="relative h-2 bg-gray-100 rounded-full">
                                <div class="absolute inset-y-0 left-0 w-[60%] bg-gray-600 rounded-full"></div>
                                <span class="absolute -right-2 -top-5 text-[10px] font-bold text-gray-600">60% Allocated</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Easy Ordering -->
                <div class="bg-white border border-pink-100 rounded-[2rem] p-8 shadow-sm">
                    <div class="w-12 h-12 bg-[#b91c63] text-white rounded-full flex items-center justify-center mb-6 shadow-md shadow-pink-900/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Easy Ordering</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mb-6">
                        Proses checkout satu klik yang cepat dan efisien.
                    </p>
                    <div class="flex gap-2">
                        <div class="h-1.5 w-1/2 bg-[#b91c63] rounded-full"></div>
                        <div class="h-1.5 w-1/2 bg-pink-200 rounded-full"></div>
                    </div>
                </div>

                <!-- Order Approval -->
                <div class="bg-[#e8ebf0] border border-gray-200 rounded-[2rem] p-8">
                    <div class="w-12 h-12 bg-gray-700 text-white rounded-full flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Order Approval</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Jalur persetujuan transparan dengan notifikasi instan.
                    </p>
                </div>

                <!-- Reward Points (Span 2) -->
                <div class="md:col-span-2 bg-[#fbe7f0] border border-pink-200 rounded-[2rem] p-8 flex flex-col md:flex-row gap-8 items-center justify-between">
                    <div class="flex-1">
                        <div class="w-12 h-12 bg-[#b91c63] text-white rounded-full flex items-center justify-center mb-6 shadow-md shadow-pink-900/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Reward Points</h3>
                        <p class="text-[#883e60] text-sm leading-relaxed max-w-sm">
                            Setiap transaksi memberikan poin loyalitas yang dapat ditukarkan dengan diskon eksklusif dan produk gratis.
                        </p>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-24 h-24 rounded-full border-4 border-[#b91c63] bg-white flex items-center justify-center relative shadow-lg">
                            <span class="text-2xl font-bold text-[#b91c63]">2.5k</span>
                            <div class="absolute -top-2 -right-2 w-6 h-6 bg-gray-700 rounded-full flex items-center justify-center text-white text-xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        </div>
                        <p class="text-[#b91c63] text-xs font-bold mt-4">Loyalty Level: Platinum</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ── Product Showcase ────────────────────────── -->
        <section class="max-w-[1400px] mx-auto px-6 py-20">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
                <div>
                    <h2 class="text-2xl font-extrabold text-gray-900">Fresh This Week</h2>
                    <p class="text-gray-500 mt-1">Stok terbaru buah segar berkualitas tinggi yang siap untuk dipesan.</p>
                </div>
                <Link href="/products" class="text-[#b91c63] font-bold text-sm hover:underline flex items-center gap-1">
                    See All Catalog
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                </Link>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div v-for="(product, idx) in products" :key="idx" class="bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-shadow flex flex-col group p-2">
                    <div class="relative h-56 bg-gray-50 rounded-2xl overflow-hidden">
                        <img :src="product.image" :alt="product.name" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                        
                        <!-- Status Badge -->
                        <div class="absolute top-3 right-3 bg-white/90 backdrop-blur px-2.5 py-1 rounded-md text-[10px] font-bold border border-white/50"
                             :class="{
                                 'text-[#b91c63]': product.stockStatus === 'In Stock',
                                 'text-amber-600': product.stockStatus === 'Limited',
                                 'text-red-600': product.stockStatus === 'Hot Sale',
                                 'text-gray-500': product.stockStatus === 'Out of Stock'
                             }">
                            {{ product.stockStatus }}
                        </div>
                    </div>
                    
                    <div class="p-4 flex flex-col flex-1">
                        <h3 class="font-bold text-gray-900 text-lg leading-tight mb-1">{{ product.name }}</h3>
                        <p class="text-xs text-gray-500 mb-3">{{ product.grade }}</p>
                        
                        <p class="text-[#b91c63] font-bold text-base mb-4 mt-auto">{{ product.price }}</p>
                        
                        <button :class="[
                            'w-full py-3 rounded-xl font-bold text-sm flex items-center justify-center gap-2 transition-colors',
                            product.action === 'Tambah Pesanan' 
                                ? 'bg-[#b91c63] text-white hover:bg-[#9a1752]' 
                                : 'bg-[#e5dce0] text-gray-600 hover:bg-[#d6cbd1]'
                        ]">
                            <svg v-if="product.action === 'Tambah Pesanan'" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            {{ product.action }}
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- ── CTA Section ─────────────────────────────── -->
        <section class="max-w-[1400px] mx-auto px-6 py-10">
            <div class="bg-[#2a2d37] rounded-[2rem] p-10 md:p-16 flex flex-col md:flex-row items-center justify-between gap-10">
                <div class="max-w-xl">
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Ready to transform your procurement?</h2>
                    <p class="text-gray-400 text-base leading-relaxed">
                        Join hundreds of businesses that have simplified their fruit sourcing with Willshine Hub. Experience efficiency like never before.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                    <Link href="/login" class="bg-[#b91c63] text-white px-8 py-4 rounded-xl font-bold text-center hover:bg-[#9a1752] transition-colors whitespace-nowrap">
                        Register Your Business
                    </Link>
                    <a href="mailto:support@tmsx.co.id" class="bg-[#3a3f4c] text-white px-8 py-4 rounded-xl font-bold text-center hover:bg-[#4a5061] transition-colors whitespace-nowrap border border-gray-600">
                        Contact Support
                    </a>
                </div>
            </div>
        </section>

        <!-- ── Footer ──────────────────────────────────── -->
        <footer class="max-w-[1400px] mx-auto px-6 py-10 border-t border-gray-200 mt-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-bold text-gray-800">Willshine Hub Buyer Portal</p>
                    <p class="text-xs text-gray-500 mt-1">© {{ new Date().getFullYear() }} PT TMSX. All rights reserved.</p>
                </div>
                
                <div class="flex items-center gap-6 text-xs text-gray-500 font-medium">
                    <a href="#" class="hover:text-[#b91c63]">Privacy Policy</a>
                    <a href="#" class="hover:text-[#b91c63]">Terms of Service</a>
                    <a href="mailto:support@tmsx.co.id" class="hover:text-[#b91c63]">Contact Support</a>
                </div>
            </div>
        </footer>
    </div>
</template>
