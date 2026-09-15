<template>
    <div>
        <!-- Parent item -->
        <component
            :is="item.children ? 'button' : (item.route ? Link : 'button')"
            :href="!item.children && item.route ? route(item.route) : undefined"
            :class="[
                'flex items-center gap-3 w-full px-4 py-2.5 text-sm transition-colors cursor-pointer',
                isActive
                    ? 'bg-blue-600 text-white font-semibold'
                    : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                collapsed ? 'justify-center' : '',
            ]"
            @click="item.children ? toggleOpen() : null"
        >
            <!-- Icon -->
            <span class="flex-shrink-0" v-html="item.icon" />

            <!-- Label + chevron -->
            <template v-if="!collapsed">
                <span class="flex-1 text-left truncate">{{ item.name }}</span>
                <svg
                    v-if="item.children"
                    :class="['w-3.5 h-3.5 transition-transform', open ? 'rotate-180' : '']"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <polyline points="6 9 12 15 18 9" />
                </svg>
            </template>
        </component>

        <!-- Children dropdown -->
        <transition name="slide">
            <div v-if="!collapsed && item.children && open" class="bg-gray-50 border-l-2 border-blue-500 ml-4">
                <Link
                    v-for="child in item.children"
                    :key="child.name"
                    :href="child.route ? route(child.route, child.query || {}) : '#'"
                    :class="[
                        'flex items-center px-4 py-2 text-sm transition-colors',
                        isCurrentRoute(child.route)
                            ? 'text-blue-600 font-semibold bg-blue-50'
                            : 'text-gray-500 hover:text-gray-800 hover:bg-gray-100',
                    ]"
                >
                    {{ child.name }}
                </Link>
            </div>
        </transition>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    item:      { type: Object, required: true },
    collapsed: { type: Boolean, default: false },
});

function isCurrentRoute(routeName) {
    if (!routeName) return false;
    try {
        return route().current(routeName);
    } catch {
        return false;
    }
}

const isActive = computed(() => {
    if (isCurrentRoute(props.item.route)) return true;
    if (props.item.children) {
        return props.item.children.some(c => isCurrentRoute(c.route));
    }
    return false;
});

const open = ref(isActive.value);

// Re-open parent when active child detected (e.g. after navigation)
watch(isActive, (val) => {
    if (val) open.value = true;
}, { immediate: true });

function toggleOpen() {
    open.value = !open.value;
}
</script>

<style scoped>
.slide-enter-active,
.slide-leave-active {
    transition: max-height 0.2s ease, opacity 0.2s ease;
    max-height: 200px;
    overflow: hidden;
}
.slide-enter-from,
.slide-leave-to {
    max-height: 0;
    opacity: 0;
}
</style>
