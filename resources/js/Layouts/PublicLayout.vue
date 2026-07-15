<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import {
    Bell,
    CheckCircle2,
    ChevronRight,
    Gift,
    Heart,
    Home,
    LogOut,
    MessageCircle,
    PackageSearch,
    Search,
    Settings,
    ShoppingBag,
    ShoppingCart,
    Sparkles,
    UserCircle,
    UserRound,
    X,
} from 'lucide-vue-next';

defineProps({
    active: { type: String, default: '' },
});

const page = usePage();
const activePanel = ref(null);
const likedProductIds = ref([]);

const user = computed(() => page.props.auth?.user ?? null);
const isLoggedIn = computed(() => Boolean(user.value));
const userName = computed(() => user.value?.customer_name || user.value?.name || 'Customer');
const userEmail = computed(() => user.value?.email || '');
const initials = computed(() => userName.value.slice(0, 2).toUpperCase());
const cartHref = computed(() => isLoggedIn.value ? '/buyer/cart' : '/login');
const accountHref = computed(() => isLoggedIn.value ? '/buyer/settings' : '/login');
const dashboardHref = computed(() => isLoggedIn.value ? '/buyer' : '/login');
const likedCount = computed(() => likedProductIds.value.length);

const mainLinks = [
    { label: 'Home', href: '/', key: 'home', route: true },
    { label: 'Shop', href: '/products', key: 'products', route: true },
    { label: 'Categories', href: '/#categories', key: 'categories' },
    { label: 'Promotions', href: '/#promotions', key: 'promotions' },
    { label: 'Rewards', href: '/rewards', key: 'rewards', route: true },
    { label: 'About Us', href: '/#about', key: 'about' },
    { label: 'Contact', href: '/#contact', key: 'contact' },
];

const mobileLinks = computed(() => [
    { label: 'Home', href: '/', key: 'home', icon: Home },
    { label: 'Shop', href: '/products', key: 'products', icon: ShoppingBag },
    { label: 'Cart', href: cartHref.value, key: 'cart', icon: ShoppingCart },
    { label: 'Rewards', href: '/rewards', key: 'rewards', icon: Gift },
    { label: 'Account', href: accountHref.value, key: 'account', icon: UserRound },
]);

onMounted(() => {
    try {
        likedProductIds.value = JSON.parse(localStorage.getItem('willshine-liked-products') || '[]').map(String);
    } catch {
        likedProductIds.value = [];
    }

    window.addEventListener('willshine-liked-products-updated', syncLikedProducts);
});

onUnmounted(() => {
    window.removeEventListener('willshine-liked-products-updated', syncLikedProducts);
});

function syncLikedProducts(event) {
    likedProductIds.value = Array.isArray(event.detail) ? event.detail.map(String) : [];
}

function openPanel(panel) {
    activePanel.value = panel;

    if (panel === 'wishlist') {
        try {
            likedProductIds.value = JSON.parse(localStorage.getItem('willshine-liked-products') || '[]').map(String);
        } catch {
            likedProductIds.value = [];
        }
    }
}

function closePanel() {
    activePanel.value = null;
}

function logout() {
    router.post('/logout');
}
</script>

<template>
    <div class="min-h-screen bg-[#FFF7FB] text-[#111827]">
        <div class="border-b border-[#FBCFE8] bg-[#FDF2F8] text-[#9D174D]">
            <div class="public-container flex flex-col gap-1 py-2 text-center text-xs font-bold sm:flex-row sm:items-center sm:justify-between sm:text-left">
                <p class="inline-flex items-center justify-center gap-2"><Sparkles class="h-3.5 w-3.5" /> Benih, bibit, dan buah pilihan tersedia setiap hari.</p>
                <p>Delivery available for selected areas.</p>
                <p>Special offers for registered customers.</p>
            </div>
        </div>

        <header class="sticky top-0 z-40 border-b border-[#FBCFE8]/80 bg-white/94 shadow-[0_12px_34px_rgba(236,72,153,.08)] backdrop-blur-xl">
            <nav class="public-container flex h-[76px] items-center justify-between gap-4">
                <Link href="/" class="flex min-w-fit items-center gap-3 rounded-2xl focus:outline-none focus:ring-4 focus:ring-[#FCE7F3]" aria-label="Willshine Hub home">
                    <span class="text-lg font-extrabold tracking-tight text-[#111827]">Willshine <span class="text-[#EC4899]">Hub</span></span>
                </Link>

                <div class="hidden items-center gap-1 rounded-full border border-[#FBCFE8] bg-white p-1.5 shadow-[0_8px_28px_rgba(236,72,153,.08)] lg:flex">
                    <template v-for="item in mainLinks" :key="item.key">
                        <Link
                            v-if="item.route"
                            :href="item.href"
                            class="rounded-full px-4 py-2 text-sm font-bold transition duration-200 hover:-translate-y-0.5 hover:bg-[#FDF2F8] hover:text-[#BE185D] hover:shadow-[0_10px_24px_rgba(236,72,153,.13)] focus:outline-none focus:ring-4 focus:ring-[#FCE7F3]"
                            :class="active === item.key ? 'bg-[#FCE7F3] text-[#BE185D] shadow-sm' : 'text-[#374151]'"
                        >
                            {{ item.label }}
                        </Link>
                        <a
                            v-else
                            :href="item.href"
                            class="rounded-full px-4 py-2 text-sm font-bold text-[#374151] transition duration-200 hover:-translate-y-0.5 hover:bg-[#FDF2F8] hover:text-[#BE185D] hover:shadow-[0_10px_24px_rgba(236,72,153,.13)] focus:outline-none focus:ring-4 focus:ring-[#FCE7F3]"
                        >
                            {{ item.label }}
                        </a>
                    </template>
                </div>

                <div class="flex items-center gap-2">
                    <Link href="/products" class="hidden h-12 w-12 items-center justify-center rounded-full border border-[#E5E7EB] bg-white text-[#374151] shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-[#FBCFE8] hover:bg-[#FDF2F8] hover:text-[#BE185D] hover:shadow-[0_14px_30px_rgba(236,72,153,.16)] sm:flex" aria-label="Search products">
                        <Search class="h-5 w-5" />
                    </Link>
                    <button type="button" class="relative hidden h-12 w-12 items-center justify-center rounded-full border border-[#E5E7EB] bg-white text-[#374151] shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-[#FBCFE8] hover:bg-[#FDF2F8] hover:text-[#BE185D] hover:shadow-[0_14px_30px_rgba(236,72,153,.16)] sm:flex" aria-label="Notifications" @click="openPanel('notifications')">
                        <Bell class="h-5 w-5" />
                        <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-[#EC4899] px-1 text-[10px] font-black text-white shadow-md shadow-pink-900/20">0</span>
                    </button>
                    <button type="button" class="relative hidden h-12 w-12 items-center justify-center rounded-full border border-[#E5E7EB] bg-white text-[#374151] shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-[#FBCFE8] hover:bg-[#FDF2F8] hover:text-[#BE185D] hover:shadow-[0_14px_30px_rgba(236,72,153,.16)] sm:flex" aria-label="Wishlist" @click="openPanel('wishlist')">
                        <Heart class="h-5 w-5" />
                        <span v-if="likedCount > 0" class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-[#EC4899] px-1 text-[10px] font-black text-white shadow-md shadow-pink-900/20">{{ likedCount }}</span>
                    </button>
                    <Link :href="cartHref" class="relative hidden h-12 w-12 items-center justify-center rounded-full border border-[#E5E7EB] bg-white text-[#374151] shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-[#FBCFE8] hover:bg-[#FDF2F8] hover:text-[#BE185D] hover:shadow-[0_14px_30px_rgba(236,72,153,.16)] sm:flex" aria-label="Cart">
                        <ShoppingCart class="h-5 w-5" />
                        <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-[#EC4899] px-1 text-[10px] font-black text-white shadow-md shadow-pink-900/20">0</span>
                    </Link>
                    <button
                        v-if="isLoggedIn"
                        type="button"
                        class="inline-flex h-12 w-12 items-center justify-center rounded-full border border-[#FBCFE8] bg-[#FDF2F8] text-sm font-black text-[#BE185D] shadow-sm transition duration-200 hover:-translate-y-0.5 hover:bg-[#FCE7F3] hover:shadow-[0_14px_30px_rgba(236,72,153,.16)]"
                        :aria-label="`Buka profil ${userName}`"
                        @click="openPanel('profile')"
                    >
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#EC4899] text-xs font-black text-white">{{ initials }}</span>
                    </button>
                    <Link v-else href="/login" class="rounded-full bg-[#EC4899] px-6 py-3 text-sm font-black text-white shadow-[0_14px_30px_rgba(236,72,153,.28)] transition duration-200 hover:-translate-y-0.5 hover:bg-[#BE185D] hover:shadow-[0_18px_40px_rgba(190,24,93,.28)]">
                        Login
                    </Link>
                </div>
            </nav>
        </header>

        <main class="pb-20 lg:pb-0">
            <slot />
        </main>

        <Transition name="fade">
            <div v-if="activePanel" class="fixed inset-0 z-[70] bg-[#111827]/35 backdrop-blur-sm" @click="closePanel" />
        </Transition>

        <Transition name="slide-panel">
            <aside
                v-if="activePanel"
                class="fixed right-0 top-0 z-[80] flex h-screen w-full max-w-md flex-col border-l border-[#FBCFE8] bg-white shadow-[0_24px_80px_rgba(157,23,77,.22)]"
            >
                <div class="flex items-center justify-between border-b border-[#FBCFE8] px-5 py-4">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[.18em] text-[#BE185D]">
                            {{ activePanel === 'notifications' ? 'Notifications' : activePanel === 'wishlist' ? 'Wishlist' : 'Profile' }}
                        </p>
                        <h2 class="mt-1 text-xl font-black text-[#111827]">
                            {{ activePanel === 'notifications' ? 'Pemberitahuan' : activePanel === 'wishlist' ? 'Produk Disukai' : 'Akun Customer' }}
                        </h2>
                    </div>
                    <button type="button" class="flex h-10 w-10 items-center justify-center rounded-full bg-[#FDF2F8] text-[#BE185D] transition hover:bg-[#FCE7F3]" aria-label="Close panel" @click="closePanel">
                        <X class="h-5 w-5" />
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-5">
                    <div v-if="activePanel === 'notifications'" class="space-y-4">
                        <div class="rounded-2xl border border-[#FBCFE8] bg-[#FFF7FB] p-5">
                            <div class="flex items-start gap-3">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-[#FCE7F3] text-[#BE185D]">
                                    <Bell class="h-5 w-5" />
                                </span>
                                <div>
                                    <h3 class="font-black text-[#111827]">Belum ada notifikasi baru</h3>
                                    <p class="mt-2 text-sm leading-6 text-[#6B7280]">Update pesanan, promo, dan informasi akun akan tampil di sini setelah tersedia.</p>
                                </div>
                            </div>
                        </div>
                        <Link :href="dashboardHref" class="flex min-h-12 items-center justify-between rounded-2xl bg-[#EC4899] px-4 text-sm font-black text-white transition hover:bg-[#BE185D]" @click="closePanel">
                            Buka Dashboard
                            <ChevronRight class="h-4 w-4" />
                        </Link>
                    </div>

                    <div v-else-if="activePanel === 'wishlist'" class="space-y-4">
                        <div class="rounded-2xl border border-[#FBCFE8] bg-[#FFF7FB] p-5">
                            <div class="flex items-start gap-3">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-[#FCE7F3] text-[#BE185D]">
                                    <Heart class="h-5 w-5" />
                                </span>
                                <div>
                                    <h3 class="font-black text-[#111827]">{{ likedCount }} produk disukai</h3>
                                    <p class="mt-2 text-sm leading-6 text-[#6B7280]">Wishlist tersimpan di browser ini. Produk yang Anda sukai akan tetap ditandai di halaman katalog.</p>
                                </div>
                            </div>
                        </div>
                        <div v-if="likedCount > 0" class="rounded-2xl border border-[#E5E7EB] bg-white p-4">
                            <p class="text-xs font-black uppercase tracking-[.16em] text-[#BE185D]">Liked product IDs</p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                <span v-for="id in likedProductIds" :key="id" class="rounded-full bg-[#FDF2F8] px-3 py-1 text-xs font-bold text-[#BE185D]">#{{ id }}</span>
                            </div>
                        </div>
                        <Link href="/products" class="flex min-h-12 items-center justify-between rounded-2xl bg-[#EC4899] px-4 text-sm font-black text-white transition hover:bg-[#BE185D]" @click="closePanel">
                            Lihat Katalog
                            <ChevronRight class="h-4 w-4" />
                        </Link>
                    </div>

                    <div v-else class="space-y-4">
                        <div v-if="isLoggedIn" class="rounded-2xl border border-[#FBCFE8] bg-[#FFF7FB] p-5">
                            <div class="flex items-center gap-4">
                                <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-[#EC4899] text-base font-black text-white">{{ initials }}</span>
                                <div class="min-w-0">
                                    <h3 class="truncate font-black text-[#111827]">{{ userName }}</h3>
                                    <p class="truncate text-sm text-[#6B7280]">{{ userEmail }}</p>
                                </div>
                            </div>
                        </div>
                        <div v-else class="rounded-2xl border border-[#FBCFE8] bg-[#FFF7FB] p-5 text-center">
                            <UserCircle class="mx-auto h-12 w-12 text-[#BE185D]" />
                            <h3 class="mt-3 font-black text-[#111827]">Belum login</h3>
                            <p class="mt-2 text-sm text-[#6B7280]">Login untuk membuka dashboard customer.</p>
                        </div>

                        <Link :href="dashboardHref" class="flex min-h-12 items-center justify-between rounded-2xl border border-[#FBCFE8] px-4 text-sm font-black text-[#BE185D] transition hover:bg-[#FDF2F8]" @click="closePanel">
                            <span class="inline-flex items-center gap-2"><UserCircle class="h-4 w-4" /> Dashboard</span>
                            <ChevronRight class="h-4 w-4" />
                        </Link>
                        <Link :href="accountHref" class="flex min-h-12 items-center justify-between rounded-2xl border border-[#FBCFE8] px-4 text-sm font-black text-[#BE185D] transition hover:bg-[#FDF2F8]" @click="closePanel">
                            <span class="inline-flex items-center gap-2"><Settings class="h-4 w-4" /> Pengaturan Akun</span>
                            <ChevronRight class="h-4 w-4" />
                        </Link>
                        <button v-if="isLoggedIn" type="button" class="flex min-h-12 w-full items-center justify-between rounded-2xl bg-[#111827] px-4 text-sm font-black text-white transition hover:bg-[#374151]" @click="logout">
                            <span class="inline-flex items-center gap-2"><LogOut class="h-4 w-4" /> Logout</span>
                            <ChevronRight class="h-4 w-4" />
                        </button>
                        <Link v-else href="/login" class="flex min-h-12 items-center justify-between rounded-2xl bg-[#EC4899] px-4 text-sm font-black text-white transition hover:bg-[#BE185D]" @click="closePanel">
                            Login
                            <ChevronRight class="h-4 w-4" />
                        </Link>
                    </div>
                </div>
            </aside>
        </Transition>

        <footer id="contact" class="border-t border-[#FBCFE8] bg-white">
            <div class="public-container grid gap-10 py-12 md:grid-cols-[1.2fr_.8fr_.8fr_1fr]">
                <div>
                    <div class="flex items-center gap-3">
                        <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#FCE7F3] text-[#BE185D] shadow-sm">
                            <PackageSearch class="h-5 w-5" />
                        </span>
                        <span class="text-lg font-extrabold">Willshine <span class="text-[#EC4899]">Hub</span></span>
                    </div>
                    <p class="mt-4 max-w-sm text-sm leading-6 text-[#6B7280]">
                        Benih, bibit tanaman, dan buah-buahan berkualitas untuk rumah, kebun, reseller, toko buah, dan kebutuhan bisnis berulang.
                    </p>
                </div>
                <div>
                    <h3 class="font-bold text-[#111827]">Shop</h3>
                    <div class="mt-4 grid gap-3 text-sm text-[#6B7280]">
                        <Link href="/products" class="hover:text-[#BE185D]">All Products</Link>
                        <a href="/#categories" class="hover:text-[#BE185D]">Categories</a>
                        <a href="/#promotions" class="hover:text-[#BE185D]">Special Offers</a>
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-[#111827]">Customer Service</h3>
                    <div class="mt-4 grid gap-3 text-sm text-[#6B7280]">
                        <a href="mailto:support@tmsx.co.id" class="hover:text-[#BE185D]">Order Assistance</a>
                        <a href="/#how-to-order" class="hover:text-[#BE185D]">How to Order</a>
                        <Link href="/rewards" class="hover:text-[#BE185D]">Willshine Rewards</Link>
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-[#111827]">Stay Updated</h3>
                    <p class="mt-4 text-sm leading-6 text-[#6B7280]">Get seed, seedling, fruit, promo, and customer-only updates.</p>
                    <form class="mt-4 flex gap-2">
                        <input type="email" placeholder="Email address" class="min-w-0 flex-1 rounded-xl border border-[#FBCFE8] bg-[#FFFBFD] px-3 py-3 text-sm outline-none transition focus:border-[#EC4899] focus:ring-4 focus:ring-[#FCE7F3]" />
                        <button type="button" class="rounded-xl bg-[#EC4899] px-4 py-3 text-sm font-bold text-white shadow-lg shadow-pink-900/15 transition hover:bg-[#BE185D]">Join</button>
                    </form>
                </div>
            </div>
            <div class="border-t border-[#F3F4F6] py-5">
                <div class="public-container flex flex-col gap-2 text-xs text-[#6B7280] sm:flex-row sm:items-center sm:justify-between">
                    <p>Copyright 2026 Willshine Hub. All rights reserved.</p>
                    <p>Privacy Policy - Terms and Conditions - Delivery Information</p>
                </div>
            </div>
        </footer>

        <nav class="fixed inset-x-0 bottom-0 z-50 border-t border-[#FBCFE8] bg-white/95 px-2 py-2 shadow-[0_-14px_40px_rgba(236,72,153,.12)] backdrop-blur lg:hidden">
            <div class="mx-auto grid max-w-md grid-cols-5">
                <Link
                    v-for="item in mobileLinks"
                    :key="item.key"
                    :href="item.href"
                    class="flex min-h-14 flex-col items-center justify-center gap-1 rounded-2xl text-[11px] font-bold transition"
                    :class="active === item.key ? 'bg-[#FCE7F3] text-[#BE185D] shadow-sm' : 'text-[#6B7280] hover:bg-[#FDF2F8] hover:text-[#BE185D]'"
                >
                    <component :is="item.icon" class="h-5 w-5" />
                    {{ item.label }}
                </Link>
            </div>
        </nav>

        <a href="https://wa.me/?text=Halo%20Willshine%20Hub%2C%20saya%20ingin%20bertanya%20tentang%20produk%20dan%20harga." class="fixed bottom-24 right-4 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-[#16A34A] text-white shadow-xl shadow-emerald-900/20 transition hover:-translate-y-1 hover:shadow-2xl lg:bottom-6" aria-label="Contact customer service on WhatsApp">
            <MessageCircle class="h-6 w-6" />
        </a>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.slide-panel-enter-active,
.slide-panel-leave-active {
    transition: transform 0.24s ease, opacity 0.2s ease;
}

.slide-panel-enter-from,
.slide-panel-leave-to {
    opacity: 0;
    transform: translateX(100%);
}
</style>
