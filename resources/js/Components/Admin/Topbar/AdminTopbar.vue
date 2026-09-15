<template>
    <header class="flex items-center justify-between h-14 px-4 bg-white border-b border-gray-200 shadow-sm flex-shrink-0">
        <!-- Hamburger / sidebar toggle -->
        <button
            class="p-1.5 rounded text-gray-500 hover:bg-gray-100"
            @click="$emit('toggle-sidebar')"
        >
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <line x1="3" y1="6"  x2="21" y2="6"  stroke-width="2" stroke-linecap="round"/>
                <line x1="3" y1="12" x2="21" y2="12" stroke-width="2" stroke-linecap="round"/>
                <line x1="3" y1="18" x2="21" y2="18" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </button>

        <!-- Right actions -->
        <div class="flex items-center gap-3">
            <!-- Super Admin: acting-as-hotel switcher -->
            <div v-if="actingHotel" class="relative">
                <button
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 hover:bg-indigo-100"
                    @click="hotelMenuOpen = !hotelMenuOpen"
                    title="Which hotel's website/settings you're managing"
                >
                    Managing: {{ currentHotelName }}
                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div v-if="hotelMenuOpen" class="absolute right-0 mt-2 w-56 max-h-80 overflow-y-auto bg-white border border-gray-200 rounded shadow-lg z-50">
                    <button v-for="h in actingHotel.hotels" :key="h.id"
                        class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50"
                        :class="h.id === actingHotel.current ? 'font-semibold text-indigo-700' : 'text-gray-700'"
                        @click="setActingHotel(h.id)">
                        {{ h.name }}
                    </button>
                </div>
            </div>

            <!-- Settings icon -->
            <button class="p-1.5 rounded-full text-gray-500 hover:bg-gray-100">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <circle cx="12" cy="12" r="3"/>
                    <path stroke-width="2" d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>
                </svg>
            </button>

            <!-- Dark mode toggle -->
            <button
                class="p-1.5 rounded-full text-gray-500 hover:bg-gray-100"
                @click="toggleDark"
                :title="isDark ? 'Light mode' : 'Dark mode'"
            >
                <svg v-if="isDark" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <circle cx="12" cy="12" r="5"/>
                    <line x1="12" y1="1"  x2="12" y2="3"/>
                    <line x1="12" y1="21" x2="12" y2="23"/>
                    <line x1="4.22" y1="4.22"   x2="5.64"  y2="5.64"/>
                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                    <line x1="1"  y1="12" x2="3"  y2="12"/>
                    <line x1="21" y1="12" x2="23" y2="12"/>
                    <line x1="4.22" y1="19.78" x2="5.64"  y2="18.36"/>
                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                </svg>
                <svg v-else class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                </svg>
            </button>

            <!-- User avatar / dropdown -->
            <div class="relative">
                <button class="flex items-center gap-2" @click="userMenuOpen = !userMenuOpen">
                    <img
                        :src="avatarUrl"
                        :alt="userName"
                        class="w-8 h-8 rounded-full"
                    />
                    <span class="hidden sm:block text-sm font-medium text-gray-700 max-w-[120px] truncate">{{ userName }}</span>
                </button>

                <!-- Dropdown -->
                <div
                    v-if="userMenuOpen"
                    class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded shadow-lg z-50"
                >
                    <div class="px-4 py-2 border-b border-gray-100">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ userName }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ userRole }}</p>
                    </div>
                    <hr class="my-1" />
                    <a
                        :href="route('logout')"
                        class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50"
                        @click.prevent="logout"
                    >Logout</a>
                </div>
            </div>
        </div>
    </header>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

defineEmits(['toggle-sidebar']);

const page        = usePage();
const isDark      = ref(false);
const userMenuOpen = ref(false);
const hotelMenuOpen = ref(false);

const userName  = computed(() => page.props.auth?.user?.name ?? 'Admin');
const userRole  = computed(() => page.props.auth?.role_name ?? 'Admin');
const avatarUrl = computed(() =>
    `https://ui-avatars.com/api/?name=${encodeURIComponent(userName.value)}&background=4f46e5&color=fff`
);

const actingHotel = computed(() => page.props.actingHotel ?? null);
const currentHotelName = computed(() =>
    actingHotel.value?.hotels.find(h => h.id === actingHotel.value.current)?.name ?? '—'
);

function setActingHotel(hotelId) {
    hotelMenuOpen.value = false;
    router.post(route('admin.acting-hotel.update'), { hotel_id: hotelId }, { preserveScroll: true });
}

function toggleDark() {
    isDark.value = !isDark.value;
    document.documentElement.classList.toggle('dark', isDark.value);
}

function logout() {
    router.post(route('logout'));
}
</script>
