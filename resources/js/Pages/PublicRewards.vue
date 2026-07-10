<script setup>
import { Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

defineProps({
    rewards: { type: Array, default: () => [] },
});

const benefits = [
    ['Exclusive offers', 'Customer-only promotions and limited product bundles.'],
    ['Special discounts', 'Redeem points for discounts on selected orders.'],
    ['Early product access', 'Get updates when seasonal products become available.'],
    ['Better repeat orders', 'Rewards designed for personal and business customers.'],
];
</script>

<template>
    <PublicLayout active="rewards">
        <section class="bg-[#FFF7FB]">
            <div class="public-container grid items-center gap-10 py-12 lg:grid-cols-[1fr_.85fr] lg:py-16">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[.22em] text-[#BE185D]">Willshine Rewards</p>
                    <h1 class="mt-4 max-w-3xl text-4xl font-black leading-tight tracking-tight text-[#111827] md:text-6xl">
                        Earn points for every fresh order.
                    </h1>
                    <p class="mt-5 max-w-2xl text-lg leading-8 text-[#374151]">
                        Nikmati benefit pelanggan seperti gratis ongkir, diskon belanja, bonus produk, dan penawaran khusus.
                    </p>
                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <Link href="/login" class="inline-flex min-h-12 items-center justify-center rounded-xl bg-[#EC4899] px-6 py-3 text-sm font-bold text-white shadow-lg shadow-pink-900/15 transition hover:bg-[#BE185D]">
                            View My Rewards
                        </Link>
                        <Link href="/products" class="inline-flex min-h-12 items-center justify-center rounded-xl border border-[#FBCFE8] bg-white px-6 py-3 text-sm font-bold text-[#BE185D] transition hover:bg-[#FDF2F8]">
                            Shop Products
                        </Link>
                    </div>
                </div>
                <div class="rounded-[2rem] border border-[#FBCFE8] bg-white p-6 shadow-[0_24px_70px_rgba(157,23,77,.12)]">
                    <p class="text-sm font-bold text-[#6B7280]">Reward progress preview</p>
                    <div class="mt-5 flex items-end justify-between">
                        <div>
                            <p class="text-5xl font-black text-[#BE185D]">900</p>
                            <p class="mt-1 text-sm font-semibold text-[#6B7280]">available points</p>
                        </div>
                        <span class="rounded-full bg-[#FEF3C7] px-3 py-1 text-xs font-bold text-[#92400E]">Silver</span>
                    </div>
                    <div class="mt-6 h-3 overflow-hidden rounded-full bg-[#FCE7F3]">
                        <div class="h-full w-[72%] rounded-full bg-[#EC4899]"></div>
                    </div>
                    <p class="mt-3 text-sm text-[#6B7280]">100 points to unlock the next reward.</p>
                </div>
            </div>
        </section>

        <section class="public-container py-12">
            <div class="grid gap-5 md:grid-cols-4">
                <article v-for="benefit in benefits" :key="benefit[0]" class="rounded-[1.4rem] border border-[#E5E7EB] bg-white p-6 shadow-sm">
                    <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#FCE7F3] text-[#BE185D]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3l2.4 4.9 5.4.8-3.9 3.8.9 5.4L12 15.4 7.2 18l.9-5.4-3.9-3.8 5.4-.8L12 3z" />
                        </svg>
                    </div>
                    <h3 class="font-black text-[#111827]">{{ benefit[0] }}</h3>
                    <p class="mt-3 text-sm leading-6 text-[#6B7280]">{{ benefit[1] }}</p>
                </article>
            </div>
        </section>

        <section class="public-container pb-16">
            <div class="mb-6">
                <h2 class="text-3xl font-black tracking-tight text-[#111827]">Reward Catalog</h2>
                <p class="mt-2 text-sm text-[#6B7280]">Redeem points for practical benefits on future orders.</p>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <article
                    v-for="reward in rewards"
                    :key="reward.id"
                    class="overflow-hidden rounded-[1.4rem] border border-[#E5E7EB] bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-[#FBCFE8] hover:shadow-xl hover:shadow-pink-900/10"
                >
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-[#FCE7F3] text-[#BE185D]">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.9" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" :d="reward.icon"></path>
                        </svg>
                    </div>
                    <span class="mt-6 inline-flex rounded-full bg-[#FDF2F8] px-3 py-1 text-xs font-bold text-[#BE185D]">
                        {{ reward.points }}
                    </span>
                    <h3 class="mt-4 text-2xl font-black text-[#111827]">{{ reward.name }}</h3>
                    <p class="mt-3 leading-7 text-[#6B7280]">{{ reward.desc }}</p>
                    <Link href="/login" class="mt-6 inline-flex w-full min-h-12 items-center justify-center rounded-xl bg-[#EC4899] text-sm font-bold text-white transition hover:bg-[#BE185D]">
                        Redeem Reward
                    </Link>
                </article>
            </div>

            <div v-if="rewards.length === 0" class="rounded-[1.6rem] border border-[#E5E7EB] bg-white px-6 py-14 text-center shadow-sm">
                <p class="text-xl font-black text-[#111827]">No rewards available yet</p>
                <p class="mt-2 text-sm text-[#6B7280]">New rewards and exclusive benefits will appear here.</p>
            </div>
        </section>
    </PublicLayout>
</template>
