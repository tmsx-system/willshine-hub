<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { Check, ChevronDown } from 'lucide-vue-next';

const props = defineProps({
    modelValue: { type: [String, Number, null], default: null },
    options: { type: Array, default: () => [] },
    label: { type: String, default: '' },
    prefix: { type: String, default: '' },
    placeholder: { type: String, default: 'Select option' },
});

const emit = defineEmits(['update:modelValue']);

const root = ref(null);
const isOpen = ref(false);

const normalizedOptions = computed(() => props.options.map((option) => {
    if (typeof option === 'object' && option !== null) {
        return {
            label: option.label ?? option.value,
            value: option.value ?? option.label,
        };
    }

    return { label: option, value: option };
}));

const selectedOption = computed(() => {
    return normalizedOptions.value.find(option => option.value === props.modelValue);
});

const displayLabel = computed(() => {
    const value = selectedOption.value?.label ?? props.modelValue ?? props.placeholder;

    return props.prefix ? `${props.prefix}: ${value}` : value;
});

function choose(option) {
    emit('update:modelValue', option.value);
    isOpen.value = false;
}

function handleDocumentClick(event) {
    if (!root.value || root.value.contains(event.target)) return;

    isOpen.value = false;
}

function handleKeydown(event) {
    if (event.key === 'Escape') {
        isOpen.value = false;
    }
}

onMounted(() => {
    document.addEventListener('click', handleDocumentClick);
    document.addEventListener('keydown', handleKeydown);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleDocumentClick);
    document.removeEventListener('keydown', handleKeydown);
});
</script>

<template>
    <div ref="root" class="relative">
        <button
            type="button"
            class="group flex min-h-12 w-full items-center gap-3 rounded-2xl border border-[#FBCFE8] bg-white px-4 py-3 text-left text-sm font-bold text-[#374151] shadow-sm transition hover:-translate-y-0.5 hover:border-[#F9A8D4] hover:bg-[#FDF2F8] hover:shadow-[0_12px_28px_rgba(236,72,153,.12)] focus:outline-none focus:ring-4 focus:ring-[#FCE7F3]"
            :class="isOpen ? 'border-[#EC4899] ring-4 ring-[#FCE7F3]' : ''"
            :aria-expanded="isOpen"
            :aria-label="label || placeholder"
            @click="isOpen = !isOpen"
        >
            <slot name="icon" />
            <span class="min-w-0 flex-1 truncate">{{ displayLabel }}</span>
            <ChevronDown
                class="h-4 w-4 shrink-0 text-[#BE185D] transition-transform duration-200"
                :class="isOpen ? 'rotate-180' : ''"
            />
        </button>

        <Transition
            enter-active-class="transition duration-150 ease-out"
            enter-from-class="-translate-y-1 opacity-0"
            enter-to-class="translate-y-0 opacity-100"
            leave-active-class="transition duration-100 ease-in"
            leave-from-class="translate-y-0 opacity-100"
            leave-to-class="-translate-y-1 opacity-0"
        >
            <div
                v-if="isOpen"
                class="absolute left-0 right-0 top-[calc(100%+8px)] z-50 overflow-hidden rounded-2xl border border-[#FBCFE8] bg-white p-1.5 shadow-[0_24px_60px_rgba(157,23,77,.18)]"
            >
                <button
                    v-for="option in normalizedOptions"
                    :key="option.value"
                    type="button"
                    class="flex min-h-10 w-full items-center gap-2 rounded-xl px-3 text-left text-sm font-bold transition"
                    :class="option.value === modelValue
                        ? 'bg-[#EC4899] text-white shadow-sm shadow-pink-900/10'
                        : 'text-[#374151] hover:bg-[#FDF2F8] hover:text-[#BE185D]'"
                    @click="choose(option)"
                >
                    <span class="min-w-0 flex-1 truncate">{{ option.label }}</span>
                    <Check v-if="option.value === modelValue" class="h-4 w-4 shrink-0" />
                </button>
            </div>
        </Transition>
    </div>
</template>
