<template>
    <div class="relative">
        <input
            ref="inputRef"
            type="text"
            :placeholder="placeholder"
            :class="inputClass"
            readonly
        />
        <button
            v-if="modelValue"
            type="button"
            @click="clear"
            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
            title="Clear date"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</template>

<script setup>
import { ref, watch, onMounted, onBeforeUnmount } from 'vue';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';

const props = defineProps({
    modelValue:  { type: String, default: '' },
    placeholder: { type: String, default: 'Select date & time…' },
    options:     { type: Object, default: () => ({}) },
    inputClass:  { type: String, default: 'w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors bg-white cursor-pointer pr-8' },
});

const emit = defineEmits(['update:modelValue']);

const inputRef = ref(null);
let fp = null;

onMounted(() => {
    fp = flatpickr(inputRef.value, {
        enableTime: true,
        dateFormat: 'Y-m-d H:i',
        altInput: true,
        altFormat: 'D, d M Y — H:i',
        minuteIncrement: 1,
        time_24hr: true,
        ...props.options,
        defaultDate: props.modelValue || null,
        onChange: (selectedDates, dateStr) => {
            emit('update:modelValue', dateStr);
        },
        onReady: (_, __, fp) => {
            if (props.modelValue) fp.setDate(props.modelValue, false);
        },
    });
});

watch(() => props.modelValue, (val) => {
    if (!fp) return;
    if (val) {
        fp.setDate(val, false);
    } else {
        fp.clear();
    }
});

function clear() {
    fp?.clear();
    emit('update:modelValue', '');
}

onBeforeUnmount(() => {
    fp?.destroy();
    fp = null;
});
</script>
