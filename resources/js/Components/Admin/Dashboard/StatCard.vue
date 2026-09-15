<template>
    <div :class="['rounded-lg p-5 text-white flex items-center gap-4 shadow-sm', bgColor]">
        <div class="opacity-80" v-html="icon" />
        <div>
            <p class="text-sm font-medium opacity-90">{{ label }}</p>
            <p class="text-3xl font-bold leading-tight">{{ value }}</p>
        </div>
    </div>
</template>

<script setup>
defineProps({
    label:   { type: String, required: true },
    value:   { type: [String, Number], required: true },
    icon:    { type: String, required: true },
    bgColor: { type: String, default: 'bg-blue-500' },
});
</script>
