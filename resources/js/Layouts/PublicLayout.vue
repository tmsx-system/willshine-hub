<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    active: { type: String, default: '' },
});

const mainLinks = [
    { label: 'Home', href: '/', key: 'home', route: true },
    { label: 'Shop', href: '/products', key: 'products', route: true },
    { label: 'Categories', href: '/#categories', key: 'categories' },
    { label: 'Promotions', href: '/#promotions', key: 'promotions' },
    { label: 'Rewards', href: '/public-rewards', key: 'rewards', route: true },
    { label: 'About Us', href: '/#about', key: 'about' },
    { label: 'Contact', href: '/#contact', key: 'contact' },
];

const mobileLinks = [
    { label: 'Home', href: '/', key: 'home', icon: 'M3 10.5 12 3l9 7.5V21h-6v-6H9v6H3V10.5z' },
    { label: 'Shop', href: '/products', key: 'products', icon: 'M4 6h16l-1.5 13h-13L4 6zm3-3h10l1 3H6l1-3z' },
    { label: 'Cart', href: '/login', key: 'cart', icon: 'M6 6h15l-2 8H8L6 6zm0 0L5 3H2m7 17a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z' },
    { label: 'Rewards', href: '/public-rewards', key: 'rewards', icon: 'M12 3l2.4 4.9 5.4.8-3.9 3.8.9 5.4L12 15.4 7.2 18l.9-5.4-3.9-3.8 5.4-.8L12 3z' },
    { label: 'Account', href: '/login', key: 'account', icon: 'M12 12a4 4 0 100-8 4 4 0 000 8zm-7 9a7 7 0 0114 0H5z' },
];
</script>

<template>
    <div class="min-h-screen bg-[#FFFBFD] text-[#111827]">
        <div class="bg-[#9D174D] text-white">
            <div class="public-container flex flex-col gap-1 py-2 text-center text-xs font-semibold sm:flex-row sm:items-center sm:justify-between sm:text-left">
                <p>Fresh products selected daily.</p>
                <p class="text-pink-100">Delivery available for selected areas.</p>
                <p class="text-pink-100">Special offers for registered customers.</p>
            </div>
        </div>

        <header class="sticky top-0 z-40 border-b border-[#FBCFE8]/80 bg-white/92 backdrop-blur-xl">
            <nav class="public-container flex h-[76px] items-center justify-between gap-4">
                <Link href="/" class="flex min-w-fit items-center gap-3" aria-label="Willshine Hub home">
                    <span class="text-lg font-extrabold tracking-tight text-[#111827]">Willshine <span class="text-[#EC4899]">Hub</span></span>
                </Link>

                <div class="hidden items-center gap-1 rounded-full border border-[#E5E7EB] bg-white p-1 lg:flex">
                    <template v-for="item in mainLinks" :key="item.key">
                        <Link
                            v-if="item.route"
                            :href="item.href"
                            class="rounded-full px-4 py-2 text-sm font-semibold transition"
                            :class="active === item.key ? 'bg-[#FCE7F3] text-[#BE185D]' : 'text-[#374151] hover:bg-[#FDF2F8] hover:text-[#BE185D]'"
                        >
                            {{ item.label }}
                        </Link>
                        <a
                            v-else
                            :href="item.href"
                            class="rounded-full px-4 py-2 text-sm font-semibold text-[#374151] transition hover:bg-[#FDF2F8] hover:text-[#BE185D]"
                        >
                            {{ item.label }}
                        </a>
                    </template>
                </div>

                <div class="flex items-center gap-2">
                    <Link href="/products" class="hidden h-11 w-11 items-center justify-center rounded-full border border-[#E5E7EB] text-[#374151] transition hover:border-[#FBCFE8] hover:bg-[#FDF2F8] hover:text-[#BE185D] sm:flex" aria-label="Search products">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.2-5.2m1.7-4.8a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" />
                        </svg>
                    </Link>
                    <Link href="/login" class="hidden h-11 w-11 items-center justify-center rounded-full border border-[#E5E7EB] text-[#374151] transition hover:border-[#FBCFE8] hover:bg-[#FDF2F8] hover:text-[#BE185D] sm:flex" aria-label="Wishlist">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.8 7.2a5 5 0 00-8.8-3.2A5 5 0 003.2 7.2C3.2 13 12 19 12 19s8.8-6 8.8-11.8z" />
                        </svg>
                    </Link>
                    <Link href="/login" class="relative hidden h-11 w-11 items-center justify-center rounded-full border border-[#E5E7EB] text-[#374151] transition hover:border-[#FBCFE8] hover:bg-[#FDF2F8] hover:text-[#BE185D] sm:flex" aria-label="Cart">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h15l-2 8H8L6 6zm0 0L5 3H2m7 17a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z" />
                        </svg>
                        <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-[#EC4899] px-1 text-[10px] font-bold text-white">0</span>
                    </Link>
                    <Link href="/login" class="rounded-full bg-[#EC4899] px-5 py-3 text-sm font-bold text-white shadow-lg shadow-pink-900/15 transition hover:-translate-y-0.5 hover:bg-[#BE185D]">
                        Login
                    </Link>
                </div>
            </nav>
        </header>

        <main class="pb-20 lg:pb-0">
            <slot />
        </main>

        <footer id="contact" class="border-t border-[#FBCFE8] bg-white">
            <div class="public-container grid gap-10 py-12 md:grid-cols-[1.2fr_.8fr_.8fr_1fr]">
                <div>
                    <div class="flex items-center gap-3">
                        <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#FCE7F3] text-lg font-black text-[#BE185D]">W</span>
                        <span class="text-lg font-extrabold">Willshine <span class="text-[#EC4899]">Hub</span></span>
                    </div>
                    <p class="mt-4 max-w-sm text-sm leading-6 text-[#6B7280]">
                        Fresh produce and quality products for homes, retailers, restaurants, hotels, cafes, and recurring business purchases.
                    </p>
                </div>
                <div>
                    <h3 class="font-bold text-[#111827]">Shop</h3>
                    <div class="mt-4 grid gap-3 text-sm text-[#6B7280]">
                        <Link href="/products">All Products</Link>
                        <a href="/#categories">Categories</a>
                        <a href="/#promotions">Special Offers</a>
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-[#111827]">Customer Service</h3>
                    <div class="mt-4 grid gap-3 text-sm text-[#6B7280]">
                        <a href="mailto:support@tmsx.co.id">Order Assistance</a>
                        <a href="/#how-to-order">How to Order</a>
                        <Link href="/public-rewards">Willshine Rewards</Link>
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-[#111827]">Stay Updated</h3>
                    <p class="mt-4 text-sm leading-6 text-[#6B7280]">Get product updates, promos, and customer-only offers.</p>
                    <form class="mt-4 flex gap-2">
                        <input type="email" placeholder="Email address" class="min-w-0 flex-1 rounded-xl border border-[#E5E7EB] px-3 py-3 text-sm outline-none focus:border-[#EC4899] focus:ring-4 focus:ring-[#FCE7F3]" />
                        <button type="button" class="rounded-xl bg-[#EC4899] px-4 py-3 text-sm font-bold text-white">Join</button>
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

        <nav class="fixed inset-x-0 bottom-0 z-50 border-t border-[#FBCFE8] bg-white/95 px-2 py-2 shadow-[0_-14px_40px_rgba(17,24,39,.08)] backdrop-blur lg:hidden">
            <div class="mx-auto grid max-w-md grid-cols-5">
                <Link
                    v-for="item in mobileLinks"
                    :key="item.key"
                    :href="item.href"
                    class="flex min-h-14 flex-col items-center justify-center gap-1 rounded-2xl text-[11px] font-bold transition"
                    :class="active === item.key ? 'bg-[#FCE7F3] text-[#BE185D]' : 'text-[#6B7280]'"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" :d="item.icon" />
                    </svg>
                    {{ item.label }}
                </Link>
            </div>
        </nav>

        <a href="https://wa.me/" class="fixed bottom-24 right-4 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-[#16A34A] text-white shadow-xl shadow-emerald-900/20 lg:bottom-6" aria-label="Contact customer service on WhatsApp">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.9" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 19l1.4-4.2A7.5 7.5 0 1112 19.5a7.4 7.4 0 01-3.5-.9L5 19z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.5 9.5c.7 2 2.1 3.4 4 4l1-1" />
            </svg>
        </a>
    </div>
</template>
