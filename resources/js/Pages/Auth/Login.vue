<script setup>
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

defineOptions({ layout: GuestLayout });

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

// Quick fill demo
function fillDemo() {
    form.email = 'hendra@berkah.co.id';
    form.password = 'password';
}
</script>

<template>
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md animate-scale-in">
            <!-- Logo -->
            <div class="text-center mb-8">
                <Link href="/" class="inline-flex flex-col items-center gap-3">
                    <div class="w-16 h-16 rounded-3xl bg-gradient-to-br from-pink-400 to-pink-700 flex items-center justify-center shadow-xl" style="box-shadow: 0 8px 32px rgba(190,24,93,0.35);">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold text-gray-900">Willshine Hub</h1>
                        <p class="text-sm text-pink-500 font-medium">Buyer Portal — PT TMSX</p>
                    </div>
                </Link>
            </div>

            <!-- Login Card -->
            <div class="card p-8" style="box-shadow: 0 20px 60px rgba(236, 72, 153, 0.1);">
                <!-- Header -->
                <div class="mb-7">
                    <h2 class="text-xl font-bold text-gray-900">Selamat Datang Kembali</h2>
                    <p class="text-sm text-gray-500 mt-1">Masuk untuk mengakses portal buyer Anda</p>
                </div>

                <!-- Demo banner -->
                <div class="mb-6 p-3.5 bg-pink-50 border border-pink-100 rounded-2xl flex items-start gap-3">
                    <span class="text-lg flex-shrink-0">💡</span>
                    <div>
                        <p class="text-xs font-semibold text-pink-800">Mode Demo</p>
                        <p class="text-xs text-pink-600 mt-0.5">
                            Gunakan akun demo:
                            <button @click="fillDemo" class="underline font-semibold hover:text-pink-800 transition-colors">Isi otomatis</button>
                        </p>
                    </div>
                </div>

                <!-- Error message -->
                <div v-if="form.errors.email || form.errors.password" class="mb-4 p-3.5 bg-red-50 border border-red-100 rounded-2xl">
                    <p class="text-xs text-red-600 font-medium">{{ form.errors.email || form.errors.password }}</p>
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </div>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                autocomplete="email"
                                placeholder="email@perusahaan.com"
                                required
                                class="input-field pl-10"
                                :class="{ 'border-red-300': form.errors.email }"
                            />
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-sm font-semibold text-gray-700">Password</label>
                            <a href="#" class="text-xs text-pink-600 hover:text-pink-800 font-medium transition-colors">Lupa password?</a>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input
                                id="password"
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                autocomplete="current-password"
                                placeholder="••••••••"
                                required
                                class="input-field pl-10 pr-11"
                                :class="{ 'border-red-300': form.errors.password }"
                            />
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-gray-600 transition-colors"
                            >
                                <svg v-if="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember me -->
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input
                            v-model="form.remember"
                            type="checkbox"
                            class="w-4 h-4 rounded border-gray-300 text-pink-600 focus:ring-pink-400"
                        />
                        <span class="text-sm text-gray-600">Ingat saya di perangkat ini</span>
                    </label>

                    <!-- Submit -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="btn-primary w-full justify-center py-3.5 text-base mt-2 relative overflow-hidden"
                    >
                        <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                        {{ form.processing ? 'Memverifikasi...' : 'Masuk ke Portal' }}
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <p class="text-center text-xs text-gray-400 mt-6">
                Belum terdaftar sebagai mitra?
                <a href="mailto:sales@tmsx.co.id" class="text-pink-600 font-semibold hover:text-pink-800 transition-colors">Hubungi tim sales kami</a>
            </p>
        </div>
    </div>
</template>
