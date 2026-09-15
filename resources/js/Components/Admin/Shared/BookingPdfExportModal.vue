<template>
    <button type="button" @click="open"
        class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm rounded hover:bg-gray-50 inline-flex items-center gap-1.5">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>
        </svg>
        Print / PDF
    </button>

    <Teleport to="body">
        <div v-if="showModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" @click.self="close">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
                <div class="flex items-start justify-between px-6 py-4 border-b border-gray-100">
                    <div>
                        <h2 class="text-base font-semibold text-gray-800">Export {{ title }}</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Choose a date range, then print or download as PDF.</p>
                    </div>
                    <button @click="close" class="text-gray-400 hover:text-gray-600 mt-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="px-6 py-5 space-y-4">
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" v-for="opt in presets" :key="opt.value"
                            @click="range = opt.value"
                            :class="range === opt.value ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50'"
                            class="px-3 py-2 text-sm rounded-lg border font-medium transition-colors">
                            {{ opt.label }}
                        </button>
                    </div>

                    <div v-if="range === 'custom'" class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Start Date</label>
                            <input v-model="start" type="date" class="input w-full" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">End Date</label>
                            <input v-model="end" type="date" class="input w-full" />
                        </div>
                    </div>

                    <p v-if="error" class="text-xs text-red-600">{{ error }}</p>
                </div>

                <div class="flex gap-3 px-6 py-4 border-t border-gray-100">
                    <button @click="handlePrint"
                        class="flex-1 px-4 py-2 text-sm border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium inline-flex items-center justify-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                        Print
                    </button>
                    <button @click="handleDownload"
                        class="flex-1 px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">
                        Download PDF
                    </button>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
    type:  { type: String, required: true }, // 'all' | 'online' | 'manual'
    title: { type: String, required: true },
});

const showModal = ref(false);
const range      = ref('today');
const start      = ref('');
const end        = ref('');
const error      = ref('');

const presets = [
    { value: 'today',     label: 'Today' },
    { value: '7days',     label: 'Last 7 Days' },
    { value: 'month',     label: 'This Month' },
    { value: 'lastmonth', label: 'Last Month' },
    { value: 'custom',    label: 'Custom' },
];

function open() {
    range.value = 'today';
    start.value = '';
    end.value   = '';
    error.value = '';
    showModal.value = true;
}

function close() {
    showModal.value = false;
}

function buildUrl(routeName) {
    if (range.value === 'custom' && (!start.value || !end.value)) {
        error.value = 'Please select both a start and end date.';
        return null;
    }
    error.value = '';

    const params = new URLSearchParams({
        type:  props.type,
        range: range.value,
    });
    if (range.value === 'custom') {
        params.set('start', start.value);
        params.set('end', end.value);
    }

    return route(routeName) + '?' + params.toString();
}

function handlePrint() {
    const url = buildUrl('admin.bookings.export.print');
    if (!url) return;
    window.open(url, '_blank');
    close();
}

function handleDownload() {
    const url = buildUrl('admin.bookings.export.pdf');
    if (!url) return;
    window.location.href = url;
    close();
}
</script>

<style scoped>
.input { @apply border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors bg-white; }
</style>
