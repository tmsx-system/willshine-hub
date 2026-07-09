<script setup>
defineProps({
    current:  { type: Number, required: true },
    target:   { type: Number, required: true },
    label:    { type: String, default: '' },
    showPct:  { type: Boolean, default: true },
});
</script>

<template>
    <div class="space-y-1.5">
        <div class="flex items-center justify-between text-xs text-gray-500">
            <span v-if="label">{{ label }}</span>
            <span v-if="showPct" class="font-semibold text-pink-700 ml-auto">
                {{ Math.min(100, Math.round((current / target) * 100)) }}%
            </span>
        </div>
        <div class="progress-track">
            <div
                class="progress-fill"
                :style="{ width: Math.min(100, (current / target) * 100) + '%' }"
            />
        </div>
        <div class="flex justify-between text-xs text-gray-400">
            <span>{{ current.toLocaleString('id-ID') }} pts</span>
            <span>{{ target.toLocaleString('id-ID') }} pts</span>
        </div>
    </div>
</template>
