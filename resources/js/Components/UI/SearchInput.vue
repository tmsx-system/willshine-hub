<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    modelValue: { type: String, default: '' },
    placeholder:{ type: String, default: 'Cari produk...' },
    debounce:   { type: Number, default: 300 },
});

const emit = defineEmits(['update:modelValue', 'search']);

const localValue = ref(props.modelValue);
let timer = null;

watch(localValue, (v) => {
    clearTimeout(timer);
    timer = setTimeout(() => {
        emit('update:modelValue', v);
        emit('search', v);
    }, props.debounce);
});

function clearSearch() {
    localValue.value = '';
    emit('update:modelValue', '');
    emit('search', '');
}
</script>

<template>
    <div class="relative">
        <!-- Search icon -->
        <div class="input-icon-left">
            <svg class="h-4 w-4 text-current" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>

        <input
            v-model="localValue"
            type="text"
            :placeholder="placeholder"
            class="input-field input-field--leading-icon input-field--trailing-action"
            style="padding-left: 3.5rem; padding-right: 3rem;"
        />

        <!-- Clear button -->
        <button
            v-if="localValue"
            @click="clearSearch"
            class="input-action-right"
            type="button"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</template>
