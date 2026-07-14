<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { Eye, LoaderCircle, LockKeyhole, Mail } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps({
    token: { type: String, required: true },
    email: { type: String, default: '' },
});

const showPassword = ref(false);
const showConfirmation = ref(false);

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

function submit() {
    form.post('/reset-password', {
        onError: () => form.reset('password', 'password_confirmation'),
    });
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
                <h1 class="mt-3 text-3xl font-black tracking-tight text-[#111827]">Create a new password</h1>
                <p class="mt-3 leading-7 text-[#6B7280]">Use at least 8 characters for your customer account password.</p>
            </div>

            <div v-if="form.errors.email || form.errors.password || form.errors.token" class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                {{ form.errors.email || form.errors.password || form.errors.token }}
            </div>

            <form class="mt-6 space-y-5" @submit.prevent="submit">
                <div>
                    <label for="email" class="mb-2 block text-sm font-bold text-[#374151]">Email</label>
                    <div class="relative">
                        <Mail class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-[#EC4899]" />
                        <input id="email" v-model="form.email" type="email" required autocomplete="email" class="h-14 w-full rounded-2xl border border-[#E5E7EB] bg-white pl-12 pr-4 text-[#111827] outline-none transition focus:border-[#EC4899] focus:ring-4 focus:ring-[#FCE7F3]" style="padding-left: 3.5rem;" />
                    </div>
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-bold text-[#374151]">New password</label>
                    <div class="relative">
                        <LockKeyhole class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-[#EC4899]" />
                        <input id="password" v-model="form.password" :type="showPassword ? 'text' : 'password'" required autocomplete="new-password" class="h-14 w-full rounded-2xl border border-[#E5E7EB] bg-white pl-12 pr-12 text-[#111827] outline-none transition focus:border-[#EC4899] focus:ring-4 focus:ring-[#FCE7F3]" style="padding-left: 3.5rem; padding-right: 3.5rem;" />
                        <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-[#9CA3AF] transition hover:text-[#BE185D]" @click="showPassword = !showPassword">
                            <Eye class="h-5 w-5" />
                        </button>
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-bold text-[#374151]">Confirm password</label>
                    <div class="relative">
                        <LockKeyhole class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-[#EC4899]" />
                        <input id="password_confirmation" v-model="form.password_confirmation" :type="showConfirmation ? 'text' : 'password'" required autocomplete="new-password" class="h-14 w-full rounded-2xl border border-[#E5E7EB] bg-white pl-12 pr-12 text-[#111827] outline-none transition focus:border-[#EC4899] focus:ring-4 focus:ring-[#FCE7F3]" style="padding-left: 3.5rem; padding-right: 3.5rem;" />
                        <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-[#9CA3AF] transition hover:text-[#BE185D]" @click="showConfirmation = !showConfirmation">
                            <Eye class="h-5 w-5" />
                        </button>
                    </div>
                </div>

                <button type="submit" :disabled="form.processing" class="flex h-14 w-full items-center justify-center rounded-2xl bg-[#EC4899] px-6 font-bold text-white shadow-lg shadow-pink-900/15 transition hover:-translate-y-0.5 hover:bg-[#BE185D] disabled:cursor-not-allowed disabled:opacity-60">
                    <LoaderCircle v-if="form.processing" class="mr-2 h-5 w-5 animate-spin" />
                    {{ form.processing ? 'Saving...' : 'Reset password' }}
                </button>
            </form>
        </section>
    </main>
</template>
