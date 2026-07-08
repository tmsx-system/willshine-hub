<script setup>
import BuyerLayout from '@/Layouts/BuyerLayout.vue'
</script>

<template>
    <BuyerLayout>
        <div class="grid gap-4 md:grid-cols-4">
            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Alokasi Stock</p>
                <p class="mt-2 text-2xl font-bold text-gray-900">0</p>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Pesanan Pending</p>
                <p class="mt-2 text-2xl font-bold text-gray-900">0</p>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Total Order</p>
                <p class="mt-2 text-2xl font-bold text-gray-900">0</p>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Reward Point</p>
                <p class="mt-2 text-2xl font-bold text-gray-900">0</p>
            </div>
        </div>
    </BuyerLayout>
</template>