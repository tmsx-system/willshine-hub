<script setup>
import { Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

defineOptions({ layout: GuestLayout });

const rewards = [
    { id: 1, name: 'Diskon Belanja 5%', category: 'Discount', points: '500 Points', desc: 'Potongan harga langsung untuk pesanan berikutnya.', icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z' },
    { id: 2, name: 'Gratis Ongkir', category: 'Shipping', points: '300 Points', desc: 'Bebas biaya kirim untuk wilayah Jabodetabek.', icon: 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4' },
    { id: 3, name: 'Exclusive Fruit Hamper', category: 'Gift', points: '2000 Points', desc: 'Parsel buah eksklusif untuk mitra VIP.', icon: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10' },
];
</script>

<template>
    <div class="min-h-screen bg-[#fcf6f9] font-sans pb-20">
        <!-- ── Navbar ─────────────────────────────────── -->
        <nav class="max-w-[1400px] mx-auto px-6 py-5 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-2xl font-bold text-[#b91c63]">Willshine</span>
                <span class="text-2xl font-bold text-gray-700">Hub</span>
            </div>

            <div class="hidden md:flex items-center gap-8 text-sm font-semibold">
                <Link href="/" class="text-gray-500 hover:text-[#b91c63]">Home</Link>
                <Link href="/products" class="text-gray-500 hover:text-[#b91c63]">Products</Link>
                <Link href="/public-rewards" class="text-[#b91c63] border-b-2 border-[#b91c63] pb-1">Rewards</Link>
            </div>

            <div class="flex items-center gap-4">
                <Link href="/login" class="text-[#b91c63] font-semibold text-sm hover:underline">Login</Link>
                <Link href="/login" class="bg-[#b91c63] text-white px-5 py-2.5 rounded-full text-sm font-semibold shadow-md hover:bg-[#9a1752] transition-colors">
                    Get Started
                </Link>
            </div>
        </nav>

        <main class="max-w-[1400px] mx-auto px-6 mt-10">
            <div class="text-center mb-16">
                <h1 class="text-5xl font-extrabold text-gray-900 mb-6 tracking-tight">Kumpulkan Poin, <br/><span class="text-[#b91c63]">Nikmati Keuntungan</span></h1>
                <p class="text-gray-500 max-w-2xl mx-auto text-lg">Program loyalitas khusus untuk mitra B2B Willshine. Setiap transaksi Anda bernilai poin yang bisa ditukarkan dengan beragam hadiah menarik.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div v-for="reward in rewards" :key="reward.id" class="bg-white rounded-[2rem] p-8 border border-pink-100 shadow-sm hover:shadow-xl transition-all relative overflow-hidden group">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-pink-50 rounded-full blur-2xl group-hover:bg-pink-100 transition-colors"></div>
                    
                    <div class="w-16 h-16 bg-[#b91c63] text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-pink-900/20 relative z-10">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" :d="reward.icon"></path></svg>
                    </div>
                    
                    <div class="relative z-10">
                        <span class="inline-block px-3 py-1 bg-pink-100 text-[#b91c63] text-xs font-bold rounded-full mb-4">{{ reward.points }}</span>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ reward.name }}</h3>
                        <p class="text-gray-500 leading-relaxed">{{ reward.desc }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-20 bg-[#2a2d37] rounded-[3rem] p-12 text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-[#b91c63]/20 to-transparent"></div>
                <div class="relative z-10 max-w-2xl mx-auto">
                    <h2 class="text-3xl font-bold text-white mb-6">Mulai Kumpulkan Poin Hari Ini!</h2>
                    <p class="text-gray-300 mb-10">Daftarkan bisnis Anda dan langsung dapatkan akses ke portal B2B eksklusif kami beserta program loyalitasnya.</p>
                    <Link href="/login" class="bg-[#b91c63] text-white px-10 py-4 rounded-full font-bold text-lg hover:bg-[#9a1752] transition-colors shadow-xl shadow-pink-900/30">
                        Gabung Sekarang
                    </Link>
                </div>
            </div>
        </main>
    </div>
</template>
