<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const showPassword = ref(false);

function submit() {
    form.post('/login', {
        onError: () => form.reset('password'),
    });
}
</script>

<template>
    <main class="min-h-screen bg-[#FFFBFD] p-3 sm:p-5">
        <div class="mx-auto grid min-h-[calc(100vh-1.5rem)] max-w-[1440px] overflow-hidden rounded-[2rem] border border-[#FBCFE8] bg-white shadow-[0_30px_90px_rgba(157,23,77,.12)] sm:min-h-[calc(100vh-2.5rem)] lg:grid-cols-[1.05fr_.95fr]">
            <section class="relative hidden overflow-hidden bg-[#FFF7FB] p-10 lg:flex lg:flex-col lg:justify-between xl:p-14">
                <div class="absolute -right-24 -top-20 h-96 w-96 rounded-full bg-[#FCE7F3] blur-3xl"></div>
                <div class="absolute -left-24 bottom-10 h-80 w-80 rounded-full bg-[#E8F5EC] blur-3xl"></div>

                <Link href="/" class="relative z-10 flex w-fit items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#FCE7F3] text-lg font-black text-[#BE185D]">W</span>
                    <span class="text-xl font-black tracking-tight text-[#111827]">Willshine <span class="text-[#EC4899]">Hub</span></span>
                </Link>

                <div class="relative z-10 max-w-xl">
                    <p class="text-sm font-bold uppercase tracking-[.2em] text-[#BE185D]">Customer account</p>
                    <h1 class="mt-5 text-5xl font-black leading-[1.08] tracking-tight text-[#111827] xl:text-6xl">
                        Welcome back to fresh ordering.
                    </h1>
                    <p class="mt-6 max-w-lg text-base leading-7 text-[#374151]">
                        Shop fresh products, save your delivery details, review orders, and enjoy Willshine Rewards from one account.
                    </p>

                    <div class="mt-10 overflow-hidden rounded-[2rem] border-4 border-white shadow-2xl shadow-pink-900/15">
                        <img :src="'/images/hero_fruits.png'" alt="Fresh product selection from Willshine Hub" class="h-72 w-full object-cover" />
                    </div>

                    <div class="mt-8 grid grid-cols-3 gap-3">
                        <div v-for="item in ['Saved addresses', 'Order history', 'Reward points']" :key="item" class="rounded-2xl border border-[#E5E7EB] bg-white px-4 py-3 text-sm font-bold text-[#374151] shadow-sm">
                            {{ item }}
                        </div>
                    </div>
                </div>
            </section>

            <section class="relative flex items-center justify-center px-5 py-10 sm:px-10 lg:px-14 xl:px-20">
                <div class="absolute right-0 top-0 h-56 w-56 rounded-full bg-[#FCE7F3]/70 blur-3xl"></div>
                <div class="relative w-full max-w-md">
                    <div class="mb-10 flex items-center justify-between">
                        <Link href="/" class="inline-flex items-center gap-2 text-sm font-semibold text-[#6B7280] transition hover:text-[#BE185D]">
                            <span aria-hidden="true">&larr;</span>
                            Home
                        </Link>
                        <div class="flex items-center gap-2 lg:hidden">
                            <span class="font-black text-[#111827]">Willshine Hub</span>
                        </div>
                    </div>

                    <div class="mb-8">
                        <p class="text-sm font-bold uppercase tracking-[.18em] text-[#BE185D]">Customer account</p>
                        <h2 class="mt-3 text-3xl font-black tracking-tight text-[#111827] sm:text-4xl">Welcome Back</h2>
                        <p class="mt-3 leading-7 text-[#6B7280]">Enter your registered email and password to continue shopping.</p>
                    </div>

                    <div v-if="form.errors.email || form.errors.password" class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                        {{ form.errors.email || form.errors.password }}
                    </div>

                    <form class="space-y-5" @submit.prevent="submit">
                        <div>
                            <label for="email" class="mb-2 block text-sm font-bold text-[#374151]">Email</label>
                            <div class="relative">
                                <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-[#9CA3AF]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    autocomplete="email"
                                    required
                                    placeholder="name@example.com"
                                    class="h-14 w-full rounded-2xl border border-[#E5E7EB] bg-white pl-12 pr-4 text-[#111827] outline-none transition placeholder:text-[#9CA3AF] focus:border-[#EC4899] focus:ring-4 focus:ring-[#FCE7F3]"
                                    :class="{ 'border-red-300': form.errors.email }"
                                />
                            </div>
                        </div>

                        <div>
                            <div class="mb-2 flex items-center justify-between">
                                <label for="password" class="text-sm font-bold text-[#374151]">Password</label>
                                <a href="mailto:support@tmsx.co.id" class="text-xs font-bold text-[#BE185D] hover:underline">Forgot password?</a>
                            </div>
                            <div class="relative">
                                <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-[#9CA3AF]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 10V8a5 5 0 0110 0v2m-11 0h12a2 2 0 012 2v7H4v-7a2 2 0 012-2z" />
                                </svg>
                                <input
                                    id="password"
                                    v-model="form.password"
                                    :type="showPassword ? 'text' : 'password'"
                                    autocomplete="current-password"
                                    required
                                    placeholder="Enter password"
                                    class="h-14 w-full rounded-2xl border border-[#E5E7EB] bg-white pl-12 pr-12 text-[#111827] outline-none transition placeholder:text-[#9CA3AF] focus:border-[#EC4899] focus:ring-4 focus:ring-[#FCE7F3]"
                                    :class="{ 'border-red-300': form.errors.password }"
                                />
                                <button
                                    type="button"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-[#9CA3AF] transition hover:text-[#BE185D]"
                                    :aria-label="showPassword ? 'Hide password' : 'Show password'"
                                    @click="showPassword = !showPassword"
                                >
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6z" />
                                        <circle cx="12" cy="12" r="2.5" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <label class="flex w-fit cursor-pointer items-center gap-3 text-sm text-[#6B7280]">
                            <input v-model="form.remember" type="checkbox" class="h-4 w-4 rounded border-[#E5E7EB] text-[#EC4899] focus:ring-[#FCE7F3]" />
                            Remember me on this device
                        </label>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="flex h-14 w-full items-center justify-center rounded-2xl bg-[#EC4899] px-6 font-bold text-white shadow-lg shadow-pink-900/15 transition hover:-translate-y-0.5 hover:bg-[#BE185D] disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            <svg v-if="form.processing" class="mr-2 h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0A12 12 0 000 12h4z" />
                            </svg>
                            {{ form.processing ? 'Verifying...' : 'Login' }}
                        </button>
                    </form>

                    <p class="mt-8 text-center text-sm text-[#6B7280]">
                        New to Willshine Hub?
                        <a href="mailto:sales@tmsx.co.id" class="font-bold text-[#BE185D] hover:underline">Create an account</a>
                    </p>
                </div>
            </section>
        </div>
    </main>
</template>
