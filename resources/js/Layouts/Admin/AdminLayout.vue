<template>
    <div class="flex h-screen bg-gray-100 overflow-hidden">
        <!-- Mobile backdrop: clicking the content area closes the drawer -->
        <div
            v-if="mobileOpen"
            class="fixed inset-0 bg-black/40 z-30 lg:hidden"
            @click="mobileOpen = false"
        ></div>

        <!-- Sidebar -->
        <AdminSidebar
            :collapsed="sidebarCollapsed"
            :mobile-open="mobileOpen"
            @toggle="toggleSidebar"
        />

        <!-- Main content area -->
        <div class="flex flex-col flex-1 min-w-0 overflow-hidden">
            <!-- Topbar -->
            <AdminTopbar @toggle-sidebar="toggleSidebar" />

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6">
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminSidebar from '@/Components/Admin/Sidebar/AdminSidebar.vue';
import AdminTopbar  from '@/Components/Admin/Topbar/AdminTopbar.vue';

// Desktop collapse state persists across navigations and reloads: the layout
// remounts on every Inertia visit, so it must be re-read from localStorage.
const COLLAPSE_KEY = 'admin.sidebar-collapsed';

const sidebarCollapsed = ref(
    typeof window !== 'undefined' && window.localStorage.getItem(COLLAPSE_KEY) === '1'
); // desktop: icon-only mode
const mobileOpen = ref(false); // mobile: off-canvas drawer

const isDesktop = () => window.matchMedia('(min-width: 1024px)').matches;

function toggleSidebar() {
    if (isDesktop()) {
        sidebarCollapsed.value = !sidebarCollapsed.value;
        window.localStorage.setItem(COLLAPSE_KEY, sidebarCollapsed.value ? '1' : '0');
    } else {
        mobileOpen.value = !mobileOpen.value;
    }
}

// Close the drawer on every route change. Needed because the layout instance
// survives navigations between routes that render the same page component.
let stopNavListener = null;
onMounted(() => {
    stopNavListener = router.on('navigate', () => { mobileOpen.value = false; });
});
onUnmounted(() => stopNavListener && stopNavListener());
</script>
