<template>
    <div ref="containerRef" class="relative px-3 py-2 border-b border-gray-100">
        <div class="relative">
            <svg
                class="absolute left-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input
                v-model="query"
                type="text"
                placeholder="Search Menu Here..."
                class="w-full pl-8 pr-3 py-1.5 text-sm bg-gray-50 border border-gray-200 rounded focus:outline-none focus:ring-1 focus:ring-blue-400"
                role="combobox"
                aria-autocomplete="list"
                :aria-expanded="isOpen"
                @focus="isOpen = true"
                @keydown.down.prevent="move(1)"
                @keydown.up.prevent="move(-1)"
                @keydown.enter.prevent="selectHighlighted"
                @keydown.esc="close"
            />
        </div>

        <!-- Suggestions -->
        <div
            v-if="isOpen && query.trim()"
            class="absolute left-3 right-3 mt-1 max-h-72 overflow-y-auto bg-white border border-gray-200 rounded shadow-lg z-50"
            role="listbox"
        >
            <Link
                v-for="(result, index) in results"
                :key="result.route + result.label"
                :href="route(result.route)"
                role="option"
                :class="[
                    'block px-3 py-2 text-sm cursor-pointer',
                    index === highlightedIndex ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50',
                ]"
                @mousedown.prevent="select(result)"
                @mouseenter="highlightedIndex = index"
            >
                <span class="block truncate">{{ result.label }}</span>
                <span v-if="result.group" class="block text-xs text-gray-400 truncate">{{ result.group }}</span>
            </Link>

            <p v-if="results.length === 0" class="px-3 py-2 text-sm text-gray-400">
                No menu items match "{{ query }}"
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    navItems: { type: Array, default: () => [] },
});

const query = ref('');
const isOpen = ref(false);
const highlightedIndex = ref(0);
const containerRef = ref(null);

// Flatten the nested nav tree (parents + children) into one searchable list.
const flatItems = computed(() => {
    const list = [];

    for (const item of props.navItems) {
        if (item.route) {
            list.push({ label: item.name, route: item.route, group: null });
        }
        for (const child of item.children ?? []) {
            if (child.route) {
                list.push({ label: child.name, route: child.route, group: item.name });
            }
        }
    }

    return list;
});

const results = computed(() => {
    const q = query.value.trim().toLowerCase();
    if (!q) return [];

    return flatItems.value
        .filter(i => i.label.toLowerCase().includes(q) || i.group?.toLowerCase().includes(q))
        .slice(0, 8);
});

watch(results, () => { highlightedIndex.value = 0; });

function move(delta) {
    if (!results.value.length) return;
    isOpen.value = true;
    const max = results.value.length - 1;
    highlightedIndex.value = Math.min(max, Math.max(0, highlightedIndex.value + delta));
}

function select(result) {
    router.visit(route(result.route));
    query.value = '';
    close();
}

function selectHighlighted() {
    const result = results.value[highlightedIndex.value];
    if (result) select(result);
}

function close() {
    isOpen.value = false;
}

function handleClickOutside(e) {
    if (containerRef.value && !containerRef.value.contains(e.target)) {
        close();
    }
}

onMounted(() => document.addEventListener('click', handleClickOutside));
onUnmounted(() => document.removeEventListener('click', handleClickOutside));
</script>
