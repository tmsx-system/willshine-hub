<script setup>
import { ref, computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';

const page = usePage();

const cartCount = ref(0);
const user = computed(() => page.props.auth?.user ?? {});
const userLabel = computed(() => user.value.customer_name || user.value.name || 'Pelanggan');
const initials = computed(() => userLabel.value.slice(0, 2).toUpperCase());

const navItems = [
    {
        label: 'Dashboard',
        href: '/buyer',
        exact: true,
        icon: `<svg fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>`,
    },
    {
        label: 'Katalog',
        href: '/buyer/catalog',
        exact: false,
        icon: `<svg fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>`,
    },
    {
        label: 'Keranjang',
        href: '/buyer/cart',
        exact: false,
        badge: cartCount,
        icon: `<svg fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>`,
    },
    {
        label: 'Pesanan',
        href: '/buyer/orders',
        exact: false,
        icon: `<svg fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>`,
    },
    {
        label: 'Reward',
        href: '/buyer/rewards',
        exact: false,
        icon: `<svg fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>`,
    },
    {
        label: 'Pengaturan',
        href: '/buyer/settings',
        exact: false,
        mobileOnly: false,
        icon: `<svg fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`,
    },
];

// Mobile bottom nav: Dashboard, Catalog, Cart, Orders, Rewards
const mobileNavItems = navItems.filter(i => i.label !== 'Pengaturan');

function isActive(item) {
    if (item.exact) {
        return page.url === item.href;
    }
    return page.url.startsWith(item.href);
}

function logout() {
    router.post('/logout');
}
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex">

        <!-- ── Desktop Sidebar ─────────────────────────── -->
        <aside class="hidden lg:flex flex-col fixed inset-y-0 left-0 w-64 bg-white border-r border-gray-100 z-50" style="box-shadow: 4px 0 24px rgba(0,0,0,0.04);">

            <!-- Logo area -->
            <div class="flex items-center gap-3 px-5 py-5 border-b border-gray-100">
                <div>
                    <p class="text-[10px] font-bold text-pink-400 uppercase tracking-widest">PT TMSX</p>
                    <h1 class="text-base font-black text-gray-900 leading-tight">Willshine Hub</h1>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
                <p class="px-4 pb-2 pt-1 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Menu Utama</p>
                <Link
                    v-for="item in navItems"
                    :key="item.href"
                    :href="item.href"
                    :class="['sidebar-item', isActive(item) ? 'active' : '']"
                >
                    <span class="sidebar-icon flex-shrink-0" v-html="item.icon" />
                    <span class="flex-1">{{ item.label }}</span>
                    <!-- Badge -->
                    <span
                        v-if="item.badge && item.badge.value > 0"
                        class="w-5 h-5 bg-pink-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center"
                    >
                        {{ item.badge.value }}
                    </span>
                </Link>
            </nav>

            <!-- User section -->
            <div class="px-3 py-4 border-t border-gray-100">
                <div class="flex items-center gap-3 px-3 py-3 rounded-2xl hover:bg-pink-50 transition-colors cursor-pointer group">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-pink-400 to-pink-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                        {{ initials }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ userLabel }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ user.email }}</p>
                    </div>
                    <button
                        @click="logout"
                        class="opacity-0 group-hover:opacity-100 transition-opacity text-gray-400 hover:text-red-500"
                        title="Keluar"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </div>
            </div>
        </aside>

        <!-- ── Mobile Header ───────────────────────────── -->
        <header class="lg:hidden fixed top-0 inset-x-0 z-40 bg-white border-b border-gray-100" style="box-shadow: 0 2px 12px rgba(0,0,0,0.06);">
            <div class="flex items-center justify-between px-4 h-14">
                <!-- Brand -->
                <div class="flex items-center gap-2.5">
                    <span class="text-sm font-bold text-gray-900">Willshine Hub</span>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2">
                    <!-- Notification -->
                    <button class="relative p-2 text-gray-500 hover:text-pink-600 hover:bg-pink-50 rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-pink-500 rounded-full"></span>
                    </button>

                    <!-- Avatar -->
                    <Link href="/buyer/settings" class="w-8 h-8 rounded-full bg-gradient-to-br from-pink-400 to-pink-600 flex items-center justify-center text-white text-xs font-bold">
                        {{ initials }}
                    </Link>
                </div>
            </div>
        </header>

        <!-- ── Main Content Area ───────────────────────── -->
        <div class="flex-1 lg:ml-64">
            <!-- Desktop top bar (optional page header) -->
            <div class="hidden lg:flex items-center justify-between px-6 py-4 bg-white border-b border-gray-100">
                <div></div>
                <div class="flex items-center gap-3">
                    <!-- Notification -->
                    <button class="relative p-2 text-gray-500 hover:text-pink-600 hover:bg-pink-50 rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-pink-500 rounded-full animate-pulse"></span>
                    </button>

                    <!-- Cart -->
                    <Link href="/buyer/cart" class="relative p-2 text-gray-500 hover:text-pink-600 hover:bg-pink-50 rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span v-if="cartCount > 0" class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-pink-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center">
                            {{ cartCount }}
                        </span>
                    </Link>
                </div>
            </div>

            <!-- Page content -->
            <main class="pt-14 lg:pt-0 pb-24 lg:pb-8 min-h-screen">
                <slot />
            </main>
        </div>

        <!-- ── Mobile Bottom Navigation ────────────────── -->
        <nav
            class="lg:hidden fixed bottom-0 inset-x-0 z-40 bg-white border-t border-gray-100"
            style="box-shadow: 0 -4px 20px rgba(0,0,0,0.07); padding-bottom: env(safe-area-inset-bottom);"
        >
            <div class="grid grid-cols-5 h-16">
                <Link
                    v-for="item in mobileNavItems"
                    :key="item.href"
                    :href="item.href"
                    class="flex flex-col items-center justify-center gap-0.5 relative transition-colors"
                    :class="isActive(item) ? 'text-pink-600' : 'text-gray-400'"
                >
                    <!-- Active indicator -->
                    <span
                        v-if="isActive(item)"
                        class="absolute top-0 left-1/2 -translate-x-1/2 w-8 h-0.5 bg-pink-500 rounded-full"
                    />

                    <span class="w-6 h-6 flex items-center justify-center relative" v-html="item.icon" />

                    <!-- Badge -->
                    <span
                        v-if="item.badge && item.badge.value > 0"
                        class="absolute top-2 left-1/2 ml-2 w-4 h-4 bg-pink-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center"
                    >
                        {{ item.badge.value }}
                    </span>

                    <span class="text-[10px] font-semibold">{{ item.label }}</span>
                </Link>
            </div>
        </nav>

    </div>
</template>
