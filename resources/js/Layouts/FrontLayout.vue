<template>
    <div class="min-h-screen flex flex-col font-sans">
        <Head :title="title ? `${title} — ${site.name}` : site.name" />

        <!-- Header -->
        <header
            :class="[
                scrolled || !transparent
                    ? 'bg-white shadow-sm border-b border-gray-100'
                    : 'bg-transparent',
                'fixed top-0 inset-x-0 z-50 transition-all duration-300'
            ]">
            <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 sm:h-18 py-2 sm:py-4">

                    <!-- Logo -->
                    <Link :href="route('home')" class="flex items-center gap-2 flex-shrink-0 min-w-0">
                        <img v-if="site.logo" :src="`/storage/${site.logo}`" alt="Logo" class="h-8 sm:h-10 w-auto max-w-[120px] sm:max-w-[180px] object-contain" />
                        <div v-else class="flex items-center gap-2">
                            <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-amber-500 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M2.5 19h19v2h-19zm9.57-14.82L12 4l.43.18C12.98 4.43 17 6.57 17 11c0 1.99-.75 3.81-2 5.12V18H9v-1.88A7.003 7.003 0 017 11c0-4.43 4.02-6.57 5-6.82z"/>
                                </svg>
                            </div>
                            <span :class="[scrolled || !transparent ? 'text-gray-900' : 'text-white', 'font-bold text-base sm:text-lg leading-tight truncate max-w-[120px] sm:max-w-none']">
                                {{ site.name }}
                            </span>
                        </div>
                    </Link>

                    <!-- Desktop Nav -->
                    <nav class="hidden lg:flex items-center gap-1">
                        <Link v-for="item in navItems" :key="item.route"
                            :href="route(item.route)"
                            :class="[
                                scrolled || !transparent ? 'text-gray-700 hover:text-amber-600' : 'text-white/90 hover:text-white',
                                isActive(item.route) ? (scrolled || !transparent ? '!text-amber-600 font-semibold' : '!text-white font-semibold') : '',
                                'px-4 py-2 text-sm font-medium rounded-lg transition-colors'
                            ]">
                            {{ item.label }}
                        </Link>
                    </nav>

                    <!-- Right Actions -->
                    <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                        <!-- Phone (md+) -->
                        <a v-if="site.phone" :href="`tel:${site.phone}`"
                            :class="[scrolled || !transparent ? 'text-gray-600 hover:text-amber-600' : 'text-white/80 hover:text-white', 'hidden md:flex items-center gap-1.5 text-sm transition-colors']">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            {{ site.phone }}
                        </a>

                        <!-- Book Now CTA -->
                        <Link :href="route('rooms')"
                            class="inline-flex items-center px-3 py-1.5 sm:px-5 sm:py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-xs sm:text-sm font-semibold rounded-full transition-colors shadow-sm whitespace-nowrap">
                            Book Online
                        </Link>

                        <!-- Admin link (lg+) -->
                        <Link v-if="$page.props.auth?.user" :href="route('admin.dashboard')"
                            :class="[scrolled || !transparent ? 'text-gray-500 hover:text-gray-700' : 'text-white/70 hover:text-white', 'hidden lg:flex items-center gap-1 text-xs transition-colors']">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Admin
                        </Link>

                        <!-- Mobile Hamburger -->
                        <button @click="mobileOpen = !mobileOpen"
                            :class="[scrolled || !transparent ? 'text-gray-700' : 'text-white', 'lg:hidden p-1.5 sm:p-2 rounded-lg']">
                            <svg v-if="!mobileOpen" class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                            <svg v-else class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div v-show="mobileOpen" class="lg:hidden bg-white border-t border-gray-100 shadow-lg">
                <div class="max-w-7xl mx-auto px-4 py-4 space-y-1">
                    <Link v-for="item in navItems" :key="item.route"
                        :href="route(item.route)"
                        @click="mobileOpen = false"
                        :class="[isActive(item.route) ? 'bg-amber-50 text-amber-700 font-semibold' : 'text-gray-700 hover:bg-gray-50', 'block px-4 py-2.5 rounded-lg text-sm font-medium transition-colors']">
                        {{ item.label }}
                    </Link>
                    <div class="pt-2 border-t border-gray-100">
                        <Link :href="route('rooms')" @click="mobileOpen = false"
                            class="block w-full text-center px-4 py-3 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-lg transition-colors">
                            Book Now
                        </Link>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Slot -->
        <main class="flex-1">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 sm:gap-10">

                    <!-- Brand -->
                    <div class="lg:col-span-1">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-9 h-9 rounded-full bg-amber-500 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M2.5 19h19v2h-19zm9.57-14.82L12 4l.43.18C12.98 4.43 17 6.57 17 11c0 1.99-.75 3.81-2 5.12V18H9v-1.88A7.003 7.003 0 017 11c0-4.43 4.02-6.57 5-6.82z"/>
                                </svg>
                            </div>
                            <span class="text-white font-bold text-lg">{{ site.name }}</span>
                        </div>
                        <p class="text-sm text-gray-400 leading-relaxed mb-5">{{ site.tagline }}</p>
                        <!-- Social Links -->
                        <div class="flex items-center gap-3">
                            <a v-if="site.facebook" :href="site.facebook" target="_blank" rel="noopener" class="social-icon">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                            </a>
                            <a v-if="site.instagram" :href="site.instagram" target="_blank" rel="noopener" class="social-icon">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                            </a>
                            <a v-if="site.twitter" :href="site.twitter" target="_blank" rel="noopener" class="social-icon">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/></svg>
                            </a>
                            <a v-if="site.youtube" :href="site.youtube" target="_blank" rel="noopener" class="social-icon">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 00-1.95 1.96A29 29 0 001 12a29 29 0 00.46 5.58A2.78 2.78 0 003.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 001.95-1.95A29 29 0 0023 12a29 29 0 00-.46-5.58zM9.75 15.02V8.98L15.5 12l-5.75 3.02z"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h4 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">Quick Links</h4>
                        <ul class="space-y-2.5">
                            <li v-for="item in navItems" :key="item.route">
                                <Link :href="route(item.route)" class="text-sm text-gray-400 hover:text-amber-400 transition-colors flex items-center gap-1.5">
                                    <span class="w-1 h-1 rounded-full bg-amber-500"></span>
                                    {{ item.label }}
                                </Link>
                            </li>
                        </ul>
                    </div>

                    <!-- Services -->
                    <div>
                        <h4 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">Our Services</h4>
                        <ul class="space-y-2.5 text-sm text-gray-400">
                            <li class="flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-amber-500"></span>Room Booking</li>
                            <li class="flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-amber-500"></span>Conference Hall</li>
                            <li class="flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-amber-500"></span>Restaurant & Dining</li>
                            <li class="flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-amber-500"></span>Spa & Wellness</li>
                            <li class="flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-amber-500"></span>Beach Activities</li>
                        </ul>
                    </div>

                    <!-- Contact -->
                    <div>
                        <h4 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">Contact Us</h4>
                        <ul class="space-y-3">
                            <li v-if="site.address" class="flex items-start gap-2.5 text-sm text-gray-400">
                                <svg class="w-4 h-4 mt-0.5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>{{ site.address }}</span>
                            </li>
                            <li v-if="site.phone" class="flex items-center gap-2.5 text-sm">
                                <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <a :href="`tel:${site.phone}`" class="text-gray-400 hover:text-amber-400 transition-colors">{{ site.phone }}</a>
                            </li>
                            <li v-if="site.email" class="flex items-center gap-2.5 text-sm">
                                <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <a :href="`mailto:${site.email}`" class="text-gray-400 hover:text-amber-400 transition-colors">{{ site.email }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Copyright bar -->
            <div class="border-t border-gray-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4
                            flex flex-col items-center text-center gap-2
                            sm:flex-row sm:justify-between sm:text-left sm:gap-3">
                    <p class="text-xs text-gray-500 break-words">{{ site.copyright }}</p>
                    <div class="flex flex-wrap items-center justify-center sm:justify-end gap-0 text-xs text-gray-500">
                        <Link :href="route('home')"
                            class="hover:text-gray-300 transition-colors px-3 sm:px-4 border-r border-white/15 last:border-0 last:pr-0 whitespace-nowrap">
                            Home
                        </Link>
                        <Link :href="route('rooms')"
                            class="hover:text-gray-300 transition-colors px-3 sm:px-4 border-r border-white/15 last:border-0 last:pr-0 whitespace-nowrap">
                            Rooms
                        </Link>
                        <Link :href="route('contact')"
                            class="hover:text-gray-300 transition-colors px-3 sm:px-4 border-r border-white/15 last:border-0 last:pr-0 whitespace-nowrap">
                            Contact
                        </Link>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
    title:       { type: String, default: '' },
    transparent: { type: Boolean, default: false },
});

const page    = usePage();
const site    = computed(() => page.props.site ?? {});
const scrolled   = ref(false);
const mobileOpen = ref(false);

const navItems = [
    { label: 'Home',     route: 'home' },
    { label: 'Rooms',    route: 'rooms' },
    { label: 'Services', route: 'services.front' },
    { label: 'Gallery',  route: 'gallery.front' },
    { label: 'About',    route: 'about' },
    { label: 'FAQs',     route: 'faqs.front' },
    { label: 'Contact',  route: 'contact' },
];

function isActive(routeName) {
    return page.url.startsWith('/' + routeName.replace('.front', '').replace('home', '').replace('.', '/'));
}

function onScroll() {
    scrolled.value = window.scrollY > 60;
}

onMounted(() => window.addEventListener('scroll', onScroll, { passive: true }));
onUnmounted(() => window.removeEventListener('scroll', onScroll));
</script>

<style scoped>
.social-icon {
    @apply w-8 h-8 rounded-full bg-gray-800 hover:bg-amber-500 flex items-center justify-center text-gray-400 hover:text-white transition-colors;
}
</style>
