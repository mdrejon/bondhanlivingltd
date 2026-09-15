<template>
    <div class="relative inline-block" v-click-outside="() => open = false">
        <button
            class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded transition-colors"
            @click="open = !open"
        >
            Select
            <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="6 9 12 15 18 9"/>
            </svg>
        </button>

        <div
            v-if="open"
            class="absolute right-0 mt-1 w-36 bg-white border border-gray-200 rounded shadow-lg z-10"
        >
            <Link
                :href="route('admin.room-bookings.show', bookingId)"
                class="flex items-center gap-2 w-full px-3 py-2 text-xs text-gray-700 hover:bg-gray-50 transition-colors"
                @click="open = false"
            >
                <span class="w-3.5 h-3.5 text-gray-400" v-html="icons.view" />
                View
            </Link>
            <Link
                :href="route('admin.room-bookings.edit', bookingId)"
                class="flex items-center gap-2 w-full px-3 py-2 text-xs text-gray-700 hover:bg-gray-50 transition-colors"
                @click="open = false"
            >
                <span class="w-3.5 h-3.5 text-gray-400" v-html="icons.edit" />
                Edit
            </Link>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    bookingId: { type: [String, Number], required: true },
});

const open = ref(false);

const icons = {
    view: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`,
    edit: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>`,
};

// Close dropdown when clicking outside
const vClickOutside = {
    mounted(el, binding) {
        el._clickOutside = (e) => { if (!el.contains(e.target)) binding.value(e); };
        document.addEventListener('click', el._clickOutside);
    },
    unmounted(el) {
        document.removeEventListener('click', el._clickOutside);
    },
};
</script>
