<template>
    <span :class="['inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold', badge.class]">
        {{ badge.label }}
    </span>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    status: { type: String, required: true },
});

const STATUS_MAP = {
    // booking statuses (DB snake_case)
    pending:      { label: 'Pending',      class: 'bg-yellow-100 text-yellow-700' },
    confirmed:    { label: 'Confirmed',    class: 'bg-blue-100 text-blue-700' },
    checked_in:   { label: 'Checked In',   class: 'bg-cyan-100 text-cyan-700' },
    checked_out:  { label: 'Checked Out',  class: 'bg-green-100 text-green-700' },
    cancelled:    { label: 'Cancelled',    class: 'bg-gray-100 text-gray-600' },
    // payment statuses
    paid:         { label: 'Paid',         class: 'bg-green-100 text-green-700' },
    partial_paid: { label: 'Partial Paid', class: 'bg-blue-100 text-blue-700' },
    unpaid:       { label: 'Unpaid',       class: 'bg-orange-100 text-orange-700' },
    rejected:     { label: 'Rejected',     class: 'bg-red-100 text-red-700' },
    // legacy title-case keys (kept for compatibility)
    'Paid':         { label: 'Paid',         class: 'bg-green-100 text-green-700' },
    'Partial Paid': { label: 'Partial Paid', class: 'bg-blue-100 text-blue-700' },
    'Pending':      { label: 'Pending',      class: 'bg-yellow-100 text-yellow-700' },
    'Rejected':     { label: 'Rejected',     class: 'bg-red-100 text-red-700' },
    'Cancelled':    { label: 'Cancelled',    class: 'bg-gray-100 text-gray-600' },
};

const badge = computed(() => {
    const key = props.status?.toLowerCase().replace(/\s+/g, '_');
    return STATUS_MAP[props.status] ?? STATUS_MAP[key] ?? {
        label: props.status,
        class: 'bg-gray-100 text-gray-600',
    };
});
</script>
