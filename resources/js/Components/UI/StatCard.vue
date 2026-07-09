<script setup>
import { ref, onMounted } from 'vue';

const props = defineProps({
    label:   { type: String, required: true },
    value:   { type: [String, Number], required: true },
    prefix:  { type: String, default: '' },
    suffix:  { type: String, default: '' },
    trend:   { type: Number, default: null },   // positive = up, negative = down, null = hidden
    color:   { type: String, default: 'pink' }, // pink | blue | green | amber
    icon:    { type: String, default: 'default' },
    delay:   { type: Number, default: 0 },
});

const colorMap = {
    pink:  { bg: 'bg-pink-50',   icon: 'bg-pink-100',   iconText: 'text-pink-600',   value: 'text-pink-700'  },
    blue:  { bg: 'bg-blue-50',   icon: 'bg-blue-100',   iconText: 'text-blue-600',   value: 'text-blue-700'  },
    green: { bg: 'bg-emerald-50',icon: 'bg-emerald-100',iconText: 'text-emerald-600',value: 'text-emerald-700'},
    amber: { bg: 'bg-amber-50',  icon: 'bg-amber-100',  iconText: 'text-amber-600',  value: 'text-amber-700' },
};

const c = colorMap[props.color] ?? colorMap.pink;

const visible = ref(false);
onMounted(() => {
    setTimeout(() => { visible.value = true; }, props.delay);
});
</script>

<template>
    <div
        class="card card-hover p-5 cursor-default select-none"
        :class="visible ? 'animate-fade-in-up' : 'opacity-0'"
    >
        <div class="flex items-start justify-between gap-3">
            <!-- Icon -->
            <div :class="['w-11 h-11 rounded-2xl flex items-center justify-center flex-shrink-0', c.icon]">
                <!-- Stock Allocation -->
                <svg v-if="icon === 'stock'" :class="['w-5 h-5', c.iconText]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 10V11"/>
                </svg>
                <!-- Pending Orders -->
                <svg v-else-if="icon === 'orders'" :class="['w-5 h-5', c.iconText]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <!-- Total Spend -->
                <svg v-else-if="icon === 'spend'" :class="['w-5 h-5', c.iconText]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <!-- Reward Points -->
                <svg v-else-if="icon === 'reward'" :class="['w-5 h-5', c.iconText]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
                <!-- Default -->
                <svg v-else :class="['w-5 h-5', c.iconText]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>

            <!-- Trend badge -->
            <div v-if="trend !== null" class="flex items-center gap-1 text-xs font-semibold" :class="trend >= 0 ? 'text-emerald-600' : 'text-red-500'">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" :d="trend >= 0 ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" />
                </svg>
                {{ Math.abs(trend) }}%
            </div>
        </div>

        <div class="mt-4">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">{{ label }}</p>
            <p class="mt-1 text-2xl font-bold" :class="c.value">
                {{ prefix }}{{ typeof value === 'number' ? value.toLocaleString('id-ID') : value }}{{ suffix }}
            </p>
        </div>
    </div>
</template>
