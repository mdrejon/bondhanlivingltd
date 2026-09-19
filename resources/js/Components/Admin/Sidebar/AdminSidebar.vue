<template>
    <aside
        :class="[
            'flex flex-col bg-white shadow-lg transition-all duration-300',
            // Mobile (< lg): fixed off-canvas drawer, slides in over the content
            'fixed inset-y-0 left-0 z-40 w-64',
            mobileOpen ? 'translate-x-0' : '-translate-x-full',
            // Desktop (lg+): back in the normal flow, collapsible to icon rail
            'lg:static lg:translate-x-0 lg:z-20',
            collapsed ? 'lg:w-16' : 'lg:w-64',
        ]"
    >
        <!-- User profile section -->
        <div class="flex items-center gap-3 p-4 border-b border-gray-100">
            <img
                :src="avatarUrl"
                alt="Admin Avatar"
                class="w-10 h-10 rounded-full flex-shrink-0"
            />
            <div v-if="!iconMode" class="overflow-hidden">
                <p class="text-sm font-semibold text-gray-800 truncate">
                    {{ userName }}
                </p>
                <p class="text-xs text-gray-500 truncate">{{ userRole }}</p>
            </div>
            <button
                v-if="!iconMode"
                class="ml-auto text-gray-400 hover:text-gray-600"
                @click="$emit('toggle')"
            >
                <ChevronLeftIcon class="w-4 h-4" />
            </button>
        </div>

        <!-- Toggle button when collapsed -->
        <button
            v-if="iconMode"
            class="flex justify-center p-3 text-gray-400 hover:text-gray-600 border-b border-gray-100"
            @click="$emit('toggle')"
        >
            <ChevronRightIcon class="w-4 h-4" />
        </button>

        <!-- Search -->
        <SidebarSearch v-if="!iconMode" :nav-items="visibleNavItems" />

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-2">
            <SidebarNavItem
                v-for="item in visibleNavItems"
                :key="item.name"
                :item="item"
                :collapsed="iconMode"
            />
        </nav>
    </aside>
</template>

<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import SidebarSearch from "./SidebarSearch.vue";
import SidebarNavItem from "./SidebarNavItem.vue";

const props = defineProps({
    collapsed: Boolean, // desktop icon-rail mode
    mobileOpen: Boolean, // mobile off-canvas drawer visibility
});
defineEmits(["toggle"]);

// The icon rail only exists on desktop; the mobile drawer always shows full content.
const iconMode = computed(() => props.collapsed && !props.mobileOpen);

const page = usePage();

const userName = computed(() => page.props.auth?.user?.name ?? "Admin");
const userRole = computed(() => page.props.auth?.role_name ?? "Admin");
const avatarUrl = computed(
    () =>
        `https://ui-avatars.com/api/?name=${encodeURIComponent(userName.value)}&background=4f46e5&color=fff`,
);

function canView(module) {
    if (!module) return true; // no module restriction
    const perms = page.props.auth?.permissions;
    if (perms === null || perms === undefined) return true; // super admin
    return perms?.[module]?.view ?? false;
}

const visibleNavItems = computed(() =>
    navItems.filter((item) => canView(item.module ?? null)),
);

// Inline minimal SVG icons to avoid external dependency
const ChevronLeftIcon = {
    template: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>`,
};
const ChevronRightIcon = {
    template: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>`,
};

const navItems = [
    // ── Dashboard ──────────────────────────────────────────────────────────
    {
        name: "Dashboard",
        route: "admin.dashboard",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>`,
    },

    // ── Projects Management ────────────────────────────────────────────────
    {
        name: "Projects",
        route: "admin.projects.index",
        module: "projects",
        icon: '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" /></svg>',
        children: [
            { name: "All Projects", route: "admin.projects.index" },
            { name: "Add Project", route: "admin.projects.create" },
            { name: "Project Page Content", route: "admin.website-settings.project-content.edit" },
        ],
    },

    // ── Teams Management ──────────────────────────────────────────────────
    {
        name: "Teams",
        route: "admin.teams.index",
        module: "teams",
        icon: '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>',
        children: [
            { name: "All Teams", route: "admin.teams.index" },
            { name: "Add Team Member", route: "admin.teams.create" },
        ],
    },

    // ── Services Management ────────────────────────────────────────────────
    {
        name: "Services",
        route: "admin.website-settings.services.index",
        module: "services",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>`,
        children: [
            {
                name: "All Services",
                route: "admin.website-settings.services.index",
            },
            {
                name: "Service Page Content",
                route: "admin.website-settings.service-content.edit",
            },
        ],
    },

    // Testimonials
    {
        name: "Testimonials",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 01.778-.332 48.294 48.294 0 005.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" /></svg>`,
        children: [
            { name: "All Testimonials", route: "admin.testimonials.index" },
            { name: "Add Testimonial", route: "admin.testimonials.create" },
        ],
    },

    // Blogs
    {
        name: "Blogs",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15M9 11l3 3L22 4" /></svg>`,
        module: "blogs",
        children: [
            { name: "All Blogs", route: "admin.blogs.index" },
            { name: "Add Blog", route: "admin.blogs.create" },
        ],
    },

    // ── Global Settings ─────────────────────────────────────────────────────
    {
        name: "Global Settings",
        route: "admin.website-settings.sliders.index",
        module: "global-settings",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`,
        children: [
            {
                name: "Hero Slider",
                route: "admin.website-settings.sliders.index",
            },
            {
                name: "Home Page Content",
                route: "admin.website-settings.home-content.edit",
            },
            {
                name: "About Page Content",
                route: "admin.website-settings.about-content.edit",
            },
            {
                name: "History Page Content",
                route: "admin.website-settings.history-content.edit",
            },
            {
                name: "Chairman Message",
                route: "admin.website-settings.chairman-content.edit",
            },
            {
                name: "Corporate Background",
                route: "admin.website-settings.corporate-content.edit",
            },
            {
                name: "Contact Page Content",
                route: "admin.website-settings.contact-content.edit",
            },
            {
                name: "Terms & Conditions",
                route: "admin.website-settings.terms-content.edit",
            },
            {
                name: "Gallery Page Content",
                route: "admin.website-settings.gallery-content.edit",
            },
            {
                name: "Header Settings",
                route: "admin.website-settings.header.edit",
            },
            {
                name: "Footer Settings",
                route: "admin.website-settings.footer.edit",
            },
            {
                name: "Features & Amenities",
                route: "admin.website-settings.features-amenities.edit",
            },
        ],
    },

    // ── Email SMTP Setting ──────────────────────────────────────────────────
    {
        name: "Email SMTP Setting",
        route: "admin.website-settings.mail.edit",
        module: "email-smtp-setting",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>`,
    },

    // ── User Management ─────────────────────────────────────────────────────
    {
        name: "User Management",
        route: "admin.users.index",
        module: "user-management",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M23 21v-2a4 4 0 00-3-3.87"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 3.13a4 4 0 010 7.75"/></svg>`,
        children: [
            { name: "Users", route: "admin.users.index" },
            { name: "Add User", route: "admin.users.create" },
            { name: "Roles", route: "admin.roles.index" },
            { name: "Add Role", route: "admin.roles.create" },
        ],
    },

    // ── Backup ──────────────────────────────────────────────────────────────
    {
        name: "Backup",
        route: "admin.backups.index",
        module: "backups",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17l4 4 4-4m-4-5v9"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.88 18.09A5 5 0 0018 9h-1.26A8 8 0 103 16.29"/></svg>`,
    },

];
</script>
