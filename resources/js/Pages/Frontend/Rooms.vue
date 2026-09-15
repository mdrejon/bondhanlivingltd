<template>
    <FrontLayout title="Rooms & Suites">
        <!-- Page Hero -->
        <div class="pt-24 pb-14 bg-gradient-to-br from-gray-900 to-slate-800 text-white text-center">
            <p class="text-amber-400 text-sm font-semibold uppercase tracking-widest mb-2">Accommodations</p>
            <h1 class="text-4xl lg:text-5xl font-bold mb-3">Rooms & Suites</h1>
            <p class="text-white/60 text-base max-w-xl mx-auto">Each room is thoughtfully designed to deliver comfort, elegance, and an unforgettable beachside experience.</p>
        </div>

        <section class="py-16 bg-gray-50 min-h-[50vh]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div v-if="rooms.length > 0" class="space-y-8">
                    <div v-for="room in rooms" :key="room.id"
                        class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-shadow flex flex-col md:flex-row">
                        <!-- Image -->
                        <div class="md:w-80 lg:w-96 flex-shrink-0">
                            <div class="h-56 md:h-full overflow-hidden">
                                <img v-if="room.gallery_images?.[0]" :src="`/storage/${room.gallery_images[0]}`" :alt="room.name"
                                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" />
                                <div v-else class="w-full h-full bg-gradient-to-br from-slate-200 to-slate-300 flex items-center justify-center">
                                    <svg class="w-14 h-14 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 p-7 flex flex-col justify-between">
                            <div>
                                <div class="flex items-start justify-between gap-4 mb-3">
                                    <h2 class="text-xl font-bold text-gray-900">{{ room.name }}</h2>
                                    <div class="text-right flex-shrink-0 space-y-0.5">
                                        <!-- BDT price -->
                                        <div class="flex items-baseline gap-2 justify-end">
                                            <span v-if="room.discounted_price_bdt" class="text-sm text-gray-400 line-through">৳{{ Number(room.price).toLocaleString() }}</span>
                                            <span class="text-2xl font-bold text-amber-600">
                                                ৳{{ Number(room.discounted_price_bdt ?? room.price).toLocaleString() }}
                                            </span>
                                        </div>
                                        <!-- USD price -->
                                        <div v-if="room.price_usd" class="flex items-baseline gap-1.5 justify-end">
                                            <span v-if="room.discounted_price_usd" class="text-xs text-gray-400 line-through">${{ Number(room.price_usd).toFixed(2) }}</span>
                                            <span class="text-sm font-semibold text-gray-500">
                                                ${{ Number(room.discounted_price_usd ?? room.price_usd).toFixed(2) }}
                                            </span>
                                        </div>
                                        <div class="text-xs text-gray-400">{{ room.price_unit }}</div>
                                        <!-- Offer badge -->
                                        <div v-if="room.discounted_price_bdt || room.discounted_price_usd"
                                            class="inline-block text-xs bg-red-500 text-white px-2 py-0.5 rounded-full font-medium">
                                            Special Offer
                                        </div>
                                    </div>
                                </div>
                                <p class="text-gray-500 text-sm leading-relaxed mb-4">{{ room.short_desc }}</p>

                                <!-- Specs -->
                                <div class="flex flex-wrap gap-4 text-xs text-gray-500 mb-4">
                                    <span class="flex items-center gap-1.5 bg-gray-50 px-3 py-1.5 rounded-full">
                                        <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        {{ room.max_adults }} Adults{{ room.max_children > 0 ? `, ${room.max_children} Children` : '' }}
                                    </span>
                                    <span v-if="room.bed_type" class="flex items-center gap-1.5 bg-gray-50 px-3 py-1.5 rounded-full">
                                        <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                        {{ room.bed_type }}
                                    </span>
                                    <span v-if="room.check_in_time" class="flex items-center gap-1.5 bg-gray-50 px-3 py-1.5 rounded-full">
                                        Check-in: {{ room.check_in_time }}
                                    </span>
                                    <span v-if="room.rating" class="flex items-center gap-1.5 bg-amber-50 px-3 py-1.5 rounded-full text-amber-700 font-medium">
                                        ★ {{ room.rating }}
                                    </span>
                                </div>

                                <!-- Amenities preview -->
                                <div v-if="room.amenities?.length" class="flex flex-wrap gap-1.5">
                                    <span v-for="amenity in room.amenities.slice(0, 5)" :key="amenity"
                                        class="text-xs bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full">
                                        {{ amenity }}
                                    </span>
                                    <span v-if="room.amenities.length > 5" class="text-xs text-gray-400 px-2 py-1">
                                        +{{ room.amenities.length - 5 }} more
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 mt-5 pt-5 border-t border-gray-100">
                                <Link :href="route('rooms.detail', room.slug)"
                                    class="flex-1 text-center py-3 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl transition-colors text-sm">
                                    View Details & Book
                                </Link>
                                <Link :href="route('contact')"
                                    class="px-5 py-3 border border-gray-200 hover:border-gray-300 text-gray-600 hover:text-gray-800 font-medium rounded-xl transition-colors text-sm">
                                    Enquire
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="text-center py-24 text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H3"/></svg>
                    <p class="text-lg font-medium">No rooms available at the moment.</p>
                    <p class="text-sm mt-1">Please check back later or contact us directly.</p>
                </div>
            </div>
        </section>
    </FrontLayout>
</template>

<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    rooms: { type: Array, default: () => [] },
});
</script>
