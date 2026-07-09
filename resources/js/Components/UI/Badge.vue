<script setup>
defineProps({
    status: {
        type: String,
        required: true,
        // Pending | Approved | Invoiced | Rejected | Processing
    },
    size: {
        type: String,
        default: 'md', // sm | md
    },
});

const classMap = {
    Pending:    'badge-pending',
    Approved:   'badge-approved',
    Invoiced:   'badge-invoiced',
    Rejected:   'badge-rejected',
    Processing: 'badge-processing',
};

const dotMap = {
    Pending:    '#d97706',
    Approved:   '#2563eb',
    Invoiced:   '#059669',
    Rejected:   '#dc2626',
    Processing: '#7c3aed',
};

const labelMap = {
    Pending:    'Menunggu',
    Approved:   'Disetujui',
    Invoiced:   'Diinvoice',
    Rejected:   'Ditolak',
    Processing: 'Diproses',
};
</script>

<template>
    <span :class="['badge', classMap[status] ?? 'badge-pending', size === 'sm' ? 'text-[0.65rem] px-2 py-0.5' : '']">
        <span
            class="inline-block w-1.5 h-1.5 rounded-full"
            :style="{ backgroundColor: dotMap[status] }"
        />
        {{ labelMap[status] ?? status }}
    </span>
</template>
