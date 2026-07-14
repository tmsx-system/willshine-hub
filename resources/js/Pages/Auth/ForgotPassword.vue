<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { LoaderCircle, Mail } from 'lucide-vue-next';

const page = usePage();
const status = computed(() => page.props.flash?.status);

const form = useForm({
    email: '',
});

function submit() {
    form.post('/forgot-password');
}
</script>

<template>
    <main class="flex min-h-screen items-center justify-center bg-[#FFFBFD] px-4 py-10">
        <section class="w-full max-w-md rounded-[2rem] border border-[#FBCFE8] bg-white p-8 shadow-[0_30px_90px_rgba(157,23,77,.12)]">
            <Link href="/" class="inline-flex items-center gap-2 text-sm font-semibold text-[#6B7280] transition hover:text-[#BE185D]">
                <span aria-hidden="true">&larr;</span>
                Home
            </Link>

            <div class="mt-8">
                <p class="text-sm font-bold uppercase tracking-[.18em] text-[#BE185D]">Reset password</p>
                <h1 class="mt-3 text-3xl font-black tracking-tight text-[#111827]">Forgot your password?</h1>
                <p class="mt-3 leading-7 text-[#6B7280]">Enter your customer account email. We will send a reset link if the account exists.</p>
            </div>

            <div v-if="status" class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                {{ status }}
            </div>
            <div v-if="form.errors.email" class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                {{ form.errors.email }}
            </div>

            <form class="mt-6 space-y-5" @submit.prevent="submit">
                <div>
                    <label for="email" class="mb-2 block text-sm font-bold text-[#374151]">Email</label>
                    <div class="relative">
                        <Mail class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-[#EC4899]" />
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            required
                            autocomplete="email"
                            class="h-14 w-full rounded-2xl border border-[#E5E7EB] bg-white pl-12 pr-4 text-[#111827] outline-none transition placeholder:text-[#9CA3AF] focus:border-[#EC4899] focus:ring-4 focus:ring-[#FCE7F3]"
                            style="padding-left: 3.5rem;"
                            placeholder="name@example.com"
                        />
                    </div>
                </div>

                <button type="submit" :disabled="form.processing" class="flex h-14 w-full items-center justify-center rounded-2xl bg-[#EC4899] px-6 font-bold text-white shadow-lg shadow-pink-900/15 transition hover:-translate-y-0.5 hover:bg-[#BE185D] disabled:cursor-not-allowed disabled:opacity-60">
                    <LoaderCircle v-if="form.processing" class="mr-2 h-5 w-5 animate-spin" />
                    {{ form.processing ? 'Sending...' : 'Send reset link' }}
                </button>
            </form>

            <Link href="/login" class="mt-6 inline-flex text-sm font-bold text-[#BE185D] hover:underline">Back to login</Link>
        </section>
    </main>
</template>
