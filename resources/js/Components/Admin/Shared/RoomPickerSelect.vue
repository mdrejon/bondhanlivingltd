<template>
    <div class="relative" v-click-outside="() => open = false">
        <button type="button" @click="toggle"
            :disabled="disabled"
            class="input w-full flex items-center justify-between gap-2 text-left disabled:opacity-60 disabled:cursor-not-allowed">
            <span :class="selectedLabel ? 'text-gray-800' : 'text-gray-400'" class="truncate">
                {{ selectedLabel || placeholder }}
            </span>
            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 12 15 18 9"/>
            </svg>
        </button>

        <div v-if="open" class="absolute z-20 mt-1 w-full max-h-64 overflow-y-auto bg-white border border-gray-200 rounded-lg shadow-lg py-1">
            <button type="button" @click="select(null)"
                class="block w-full px-3 py-2 text-sm text-left text-gray-500 hover:bg-gray-50">
                {{ placeholder }}
            </button>

            <button v-for="r in rooms" :key="r.id" type="button"
                @click="r.available === false ? null : select(r.id)"
                :disabled="r.available === false"
                class="flex items-center justify-between gap-3 w-full px-3 py-2 text-sm text-left transition-colors"
                :class="r.available === false
                    ? 'opacity-50 cursor-not-allowed text-gray-400'
                    : 'text-gray-800 hover:bg-blue-50 cursor-pointer'"
            >
                <span class="truncate">
                    {{ r.room_number }}<span v-if="r.room_name" class="text-gray-400"> – {{ r.room_name }}</span>
                </span>
                <span class="flex-shrink-0 px-2 py-0.5 rounded-full text-[11px] font-semibold"
                    :class="r.available === false ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-700'">
                    {{ r.available === false ? 'Booked' : 'Available' }}
                </span>
            </button>

            <p v-if="!rooms.length" class="px-3 py-2 text-sm text-gray-400 italic">No rooms of this type.</p>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    modelValue:  { type: [String, Number], default: '' },
    rooms:       { type: Array,   default: () => [] },
    placeholder: { type: String,  default: '— Unassigned —' },
    disabled:    { type: Boolean, default: false },
});

const emit = defineEmits(['update:modelValue']);

const open = ref(false);

const selectedLabel = computed(() => {
    const r = props.rooms.find(x => String(x.id) === String(props.modelValue));
    if (!r) return '';
    return r.room_number + (r.room_name ? ' – ' + r.room_name : '');
});

function toggle() {
    if (!props.disabled) open.value = !open.value;
}

function select(id) {
    emit('update:modelValue', id ?? '');
    open.value = false;
}

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

<style scoped>
.input { @apply border border-gray-300 rounded-lg px-3 py-2.5 text-sm bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors; }
</style>
