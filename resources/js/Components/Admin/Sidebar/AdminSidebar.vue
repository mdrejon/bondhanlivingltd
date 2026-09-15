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

    // ── Dashboard ──────────────────────────────────────────────────────────
    {
        name: "Room Availability",
        route: "admin.room-availability.index",
        module: "room-availability",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>`,
    },

    // ── Bookings ────────────────────────────────────────────────────────────
    {
        name: "Bookings",
        route: "admin.room-bookings.index",
        module: "bookings",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>`,
        children: [
            { name: "All Bookings", route: "admin.bookings.all" },
            { name: "Online Bookings", route: "admin.bookings.online" },
            { name: "Manual Bookings", route: "admin.bookings.manual" },
            { name: "Add Booking", route: "admin.room-bookings.create" },
            // { name: 'Room Availability',  route: 'admin.room-availability.index' },
            { name: "Booking Follow-up", route: "admin.bookings.follow-up" },
            { name: "Booking History", route: "admin.bookings.history" },
            { name: "Cancelled Bookings", route: "admin.bookings.cancelled" },
        ],
    },

    // ── Room Management ─────────────────────────────────────────────────────
    {
        name: "Room Management",
        route: "admin.rooms.index",
        module: "room-management",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H3m14 0h2M3 21h2M9 7h6M9 11h6M9 15h4"/></svg>`,
        children: [
            { name: "Room Types", route: "admin.room-types.index" },
            { name: "Add Room Type", route: "admin.room-types.create" },
            { name: "All Rooms", route: "admin.rooms.index" },
            { name: "Add Room", route: "admin.rooms.create" },
        ],
    },
    // ── Customers ───────────────────────────────────────────────────────────
    {
        name: "Customers",
        route: "admin.customers.index",
        module: "customers",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>`,
        children: [
            { name: "Customer List", route: "admin.customers.index" },
            { name: "Customer History", route: "admin.customers.history" },
        ],
    },

    // ── Inquiries ───────────────────────────────────────────────────────────
    {
        name: "Inquiries",
        route: "admin.inquiries.index",
        module: "inquiries",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>`,
        children: [
            { name: "List", route: "admin.inquiries.index" },
            // { name: 'View & Make Reply', route: null },
        ],
    },

    // ── Website Management ──────────────────────────────────────────────────
    {
        name: "Website Management",
        route: null,
        module: "website-management",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>`,
        children: [
            {
                name: "About Settings",
                route: "admin.website-settings.about.edit",
            },
            { name: "Services", route: "admin.services.index" },
            { name: "Gallery", route: "admin.website-settings.gallery.index" },
            { name: "FAQ's", route: "admin.faqs.index" },
            { name: "Testimonials", route: "admin.testimonials.index" },
            { name: "Blog Category", route: "admin.blog-categories.index" },
            { name: "Blog Posts", route: "admin.blog.index" },
        ],
    },

    // ── Reports ─────────────────────────────────────────────────────────────
    {
        name: "Reports",
        route: null,
        module: "reports",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>`,
        children: [
            { name: "Income Report", route: "admin.reports.income" },
            { name: "Discount Report", route: "admin.reports.discount" },
            { name: "Booking Report", route: "admin.reports.booking" },
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
                name: "Header Settings",
                route: "admin.website-settings.header.edit",
            },
            {
                name: "Footer Settings",
                route: "admin.website-settings.footer.edit",
            },

            {
                name: "History Settings",
                route: "admin.website-settings.history.edit",
            },
            {
                name: "Contact Settings",
                route: "admin.website-settings.contact.edit",
            },
            {
                name: "Services Settings",
                route: "admin.website-settings.services.edit",
            },
            {
                name: "Rooms Settings",
                route: "admin.website-settings.rooms-page.edit",
            },
            {
                name: "Booking Settings",
                route: "admin.website-settings.booking-page.edit",
            },
            {
                name: "Blog Settings",
                route: "admin.website-settings.blog-page.edit",
            },
            {
                name: "Email Notifications",
                route: "admin.website-settings.email-notifications.edit",
            },
            { name: "Facilities", route: "admin.facilities.index" },
            { name: "Room Amenities", route: "admin.room-amenities.index" },
            // { name: 'Room Features',     route: null },
            // { name: 'Social Media',      route: null },
            // { name: 'SEO Settings',      route: null },
            // { name: 'General Settings',  route: null },
        ],
    },

    // ── Email SMTP Setting ──────────────────────────────────────────────────
    {
        name: "Email SMTP Setting",
        route: "admin.website-settings.mail.edit",
        module: "email-smtp-setting",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>`,
    },

    // ── Monitoring Reports ──────────────────────────────────────────────────
    {
        name: "Monitoring Reports",
        route: "admin.government.hotels",
        module: "gov-reports",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>`,
        children: [
            { name: "Hotel-wise Report", route: "admin.government.hotels" },
            { name: "Guest Report", route: "admin.government.guests" },
            {
                name: "Date-wise Report",
                route: "admin.government.guests",
                query: {
                    date_from: new Date().toISOString().slice(0, 10),
                    date_to: new Date().toISOString().slice(0, 10),
                },
            },
            {
                name: "Foreign Guest Report",
                route: "admin.government.guests",
                query: { foreign_only: 1 },
            },
            {
                name: "Nationality Report",
                route: "admin.government.nationality",
            },
            {
                name: "NID / Passport Search",
                route: "admin.government.nid-search",
            },
        ],
    },

    // ── Hotel Registration ──────────────────────────────────────────────────
    {
        name: "Hotel Registration",
        route: "admin.hotels.index",
        module: "hotel-registration",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4M9 9h.01M9 12h.01M9 15h.01"/></svg>`,
        children: [
            { name: "All Hotels", route: "admin.hotels.index" },
            { name: "Add Hotel", route: "admin.hotels.create" },
        ],
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

    // ── Hotel Backup (Phase 8B — hotel-scoped export/import) ─────────────────
    {
        name: "Hotel Backup",
        route: "admin.hotel-backups.index",
        module: "hotel-backups",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17l4 4 4-4m-4-5v9"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.88 18.09A5 5 0 0018 9h-1.26A8 8 0 103 16.29"/></svg>`,
    },
];
</script>
