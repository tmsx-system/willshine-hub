<script setup>
import { computed } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import {
    BadgeCheck,
    Building2,
    KeyRound,
    Mail,
    Save,
    ShieldCheck,
    UserRound,
} from 'lucide-vue-next';
import BuyerLayout from '@/Layouts/BuyerLayout.vue';

defineOptions({ layout: BuyerLayout });

const props = defineProps({
    buyer: { type: Object, required: true },
});

const page = usePage();
const status = computed(() => page.props.flash?.status);

const form = useForm({
    name: props.buyer.contact || '',
    email: props.buyer.email || '',
    password: '',
    password_confirmation: '',
});

function submit() {
    form.post('/buyer/settings', {
        preserveScroll: true,
        onSuccess: () => form.reset('password', 'password_confirmation'),
    });
}
</script>

<template>
    <div class="px-4 sm:px-6 lg:px-8 py-6 max-w-5xl mx-auto space-y-6">
        <div class="animate-fade-in-up">
            <h2 class="text-xl font-extrabold text-gray-900">Pengaturan Akun</h2>
            <p class="mt-1 text-sm text-gray-500">Kelola data login customer dan informasi akun yang terhubung ke ERP.</p>
        </div>

        <div v-if="status" class="rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
            {{ status }}
        </div>

        <div class="grid gap-6 lg:grid-cols-[1fr_.8fr]">
            <form class="card p-6 space-y-5" @submit.prevent="submit">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Data Login</h3>
                    <p class="mt-1 text-sm text-gray-500">Data ini digunakan untuk login ke portal customer.</p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">Nama Kontak</label>
                    <div class="relative">
                        <UserRound class="input-icon-left h-5 w-5" />
                        <input
                            v-model="form.name"
                            type="text"
                            class="input-field input-field--leading-icon"
                            style="padding-left: 3.5rem;"
                            :class="{ 'border-red-300': form.errors.name }"
                        />
                    </div>
                    <p v-if="form.errors.name" class="mt-1 text-xs font-semibold text-red-500">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">Email Login</label>
                    <div class="relative">
                        <Mail class="input-icon-left h-5 w-5" />
                        <input
                            v-model="form.email"
                            type="email"
                            class="input-field input-field--leading-icon"
                            style="padding-left: 3.5rem;"
                            :class="{ 'border-red-300': form.errors.email }"
                        />
                    </div>
                    <p v-if="form.errors.email" class="mt-1 text-xs font-semibold text-red-500">{{ form.errors.email }}</p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-gray-700">Password Baru</label>
                        <div class="relative">
                            <KeyRound class="input-icon-left h-5 w-5" />
                            <input
                                v-model="form.password"
                                type="password"
                                class="input-field input-field--leading-icon"
                                style="padding-left: 3.5rem;"
                                placeholder="Kosongkan jika tidak diganti"
                                :class="{ 'border-red-300': form.errors.password }"
                            />
                        </div>
                        <p v-if="form.errors.password" class="mt-1 text-xs font-semibold text-red-500">{{ form.errors.password }}</p>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-gray-700">Konfirmasi Password</label>
                        <div class="relative">
                            <ShieldCheck class="input-icon-left h-5 w-5" />
                            <input
                                v-model="form.password_confirmation"
                                type="password"
                                class="input-field input-field--leading-icon"
                                style="padding-left: 3.5rem;"
                                placeholder="Ulangi password baru"
                            />
                        </div>
                    </div>
                </div>

                <button type="submit" :disabled="form.processing" class="btn-primary justify-center min-h-12 px-5">
                    <Save class="h-4 w-4" />
                    {{ form.processing ? 'Menyimpan...' : 'Simpan Pengaturan' }}
                </button>
            </form>

            <aside class="space-y-4">
                <div class="card p-6">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-pink-50 text-pink-600">
                            <Building2 class="h-6 w-6" />
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Customer ERP</p>
                            <h3 class="text-base font-extrabold text-gray-900">{{ buyer.name }}</h3>
                        </div>
                    </div>
                    <div class="mt-5 space-y-3 text-sm">
                        <div class="flex justify-between gap-4">
                            <span class="text-gray-500">Kontak</span>
                            <span class="font-semibold text-gray-800 text-right">{{ buyer.contact }}</span>
                        </div>
                        <div class="flex justify-between gap-4">
                            <span class="text-gray-500">Email</span>
                            <span class="font-semibold text-gray-800 text-right">{{ buyer.email }}</span>
                        </div>
                        <div class="flex justify-between gap-4">
                            <span class="text-gray-500">Tier</span>
                            <span class="font-semibold text-gray-800 text-right">{{ buyer.tier ?? 'Customer' }}</span>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-pink-100 bg-pink-50 p-5">
                    <div class="flex items-start gap-3">
                        <BadgeCheck class="mt-0.5 h-5 w-5 shrink-0 text-pink-600" />
                        <p class="text-sm leading-6 text-gray-600">
                            Nama customer, price list, alokasi produk, dan batas order mengikuti data ERP/admin. Hubungi admin jika data customer tidak sesuai.
                        </p>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</template>
