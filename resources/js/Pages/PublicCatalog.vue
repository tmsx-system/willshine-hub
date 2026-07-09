<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

defineOptions({ layout: GuestLayout });

const categories = ['Semua', 'Buah Segar', 'Bibit Tanaman', 'Benih Premium', 'Peralatan Berkebun'];
const activeCategory = ref('Semua');

const products = [
    { id: 1, name: 'Premium Fuji Apple', category: 'Buah Segar', grade: 'Grade AAA / Import', uom: 'Kg', price: 'Rp 45.000', stock_status: 'In Stock', image: '/images/apple_fuji.png' },
    { id: 2, name: 'Organic Banana', category: 'Buah Segar', grade: 'Cavendish Local', uom: 'Kg', price: 'Rp 18.500', stock_status: 'Limited', image: '/images/banana_organic.png' },
    { id: 3, name: 'Dragon Fruit', category: 'Buah Segar', grade: 'Super Red / Local', uom: 'Kg', price: 'Rp 25.000', stock_status: 'In Stock', image: '/images/dragon_fruit.png' },
    { id: 4, name: 'Honey Mango', category: 'Buah Segar', grade: 'Probolinggo Export', uom: 'Kg', price: 'Rp 35.000', stock_status: 'Out of Stock', image: '/images/honey_mango.png' },
    { id: 5, name: 'Bibit Alpukat Miki', category: 'Bibit Tanaman', grade: 'Tinggi 50-70cm', uom: 'Pohon', price: 'Rp 45.000', stock_status: 'In Stock', image: '/images/avocado_tree.png' },
    { id: 6, name: 'Benih Tomat Cherry', category: 'Benih Premium', grade: 'F1 Hybrid / Pack', uom: 'Pack', price: 'Rp 15.000', stock_status: 'In Stock', image: '/images/tomato_seeds.png' },
];

const filteredProducts = computed(() => {
    if (activeCategory.value === 'Semua') return products;
    return products.filter(p => p.category === activeCategory.value);
});
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
                <Link href="/products" class="text-[#b91c63] border-b-2 border-[#b91c63] pb-1">Products</Link>
                <Link href="/public-rewards" class="text-gray-500 hover:text-[#b91c63]">Rewards</Link>
            </div>

            <div class="flex items-center gap-4">
                <Link href="/login" class="text-[#b91c63] font-semibold text-sm hover:underline">Login</Link>
                <Link href="/login" class="bg-[#b91c63] text-white px-5 py-2.5 rounded-full text-sm font-semibold shadow-md hover:bg-[#9a1752] transition-colors">
                    Get Started
                </Link>
            </div>
        </nav>

        <main class="max-w-[1400px] mx-auto px-6 mt-10">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-4">Katalog Produk</h1>
            <p class="text-gray-500 max-w-2xl mb-10">Temukan berbagai produk buah segar, bibit, dan benih premium langsung dari perkebunan terbaik.</p>
            
            <!-- Categories -->
            <div class="flex flex-wrap gap-3 mb-8">
                <button 
                    v-for="cat in categories" :key="cat"
                    @click="activeCategory = cat"
                    :class="[
                        'px-5 py-2.5 rounded-full text-sm font-bold transition-all shadow-sm',
                        activeCategory === cat 
                            ? 'bg-[#b91c63] text-white' 
                            : 'bg-white text-gray-600 hover:bg-pink-50 hover:text-[#b91c63] border border-gray-200'
                    ]"
                >
                    {{ cat }}
                </button>
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <div v-for="product in filteredProducts" :key="product.id" class="bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-shadow flex flex-col group p-2">
                    <div class="relative h-56 bg-gray-50 rounded-2xl overflow-hidden">
                        <img :src="product.image" :alt="product.name" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                        <div class="absolute top-3 right-3 bg-white/90 backdrop-blur px-2.5 py-1 rounded-md text-[10px] font-bold border border-white/50"
                             :class="product.stock_status === 'In Stock' ? 'text-[#b91c63]' : (product.stock_status === 'Out of Stock' ? 'text-gray-500' : 'text-amber-600')">
                            {{ product.stock_status }}
                        </div>
                    </div>
                    
                    <div class="p-4 flex flex-col flex-1">
                        <h3 class="font-bold text-gray-900 text-lg leading-tight mb-1">{{ product.name }}</h3>
                        <p class="text-xs text-gray-500 mb-3">{{ product.grade }}</p>
                        
                        <p class="text-[#b91c63] font-bold text-base mb-4 mt-auto">{{ product.price }} <span class="text-xs text-gray-400 font-normal">/ {{ product.uom }}</span></p>
                        
                        <Link href="/login" class="w-full py-3 rounded-xl font-bold text-sm flex items-center justify-center gap-2 bg-[#f0e4ea] text-gray-800 hover:bg-[#e6d8df] transition-colors">
                            Login untuk Order
                        </Link>
                    </div>
                </div>
            </div>
            
            <div v-if="filteredProducts.length === 0" class="text-center py-20">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Tidak ada produk</h3>
                <p class="text-gray-500">Kategori ini belum memiliki produk.</p>
            </div>
        </main>
    </div>
</template>
