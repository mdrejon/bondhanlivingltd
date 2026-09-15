<template>
    <FrontLayout :title="room.name">
        <!-- Hero with first gallery image -->
        <div class="relative pt-16 h-96 overflow-hidden bg-gray-900">
            <img v-if="room.gallery_images?.[0]" :src="`/storage/${room.gallery_images[0]}`" :alt="room.name"
                class="w-full h-full object-cover opacity-70" />
            <div v-else class="w-full h-full bg-gradient-to-br from-slate-800 to-slate-700"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
            <div class="absolute bottom-8 left-0 right-0 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <nav class="flex items-center gap-2 text-white/60 text-xs mb-3">
                    <Link :href="route('home')" class="hover:text-white">Home</Link>
                    <span>/</span>
                    <Link :href="route('rooms')" class="hover:text-white">Rooms</Link>
                    <span>/</span>
                    <span class="text-white">{{ room.name }}</span>
                </nav>
                <h1 class="text-3xl lg:text-4xl font-bold text-white">{{ room.name }}</h1>
            </div>
        </div>

        <section class="py-14 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-3 gap-10">

                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Gallery -->
                        <div v-if="room.gallery_images?.length > 1" class="bg-white rounded-2xl p-5 shadow-sm">
                            <h2 class="font-bold text-gray-800 mb-4 text-lg">Gallery</h2>
                            <div class="grid grid-cols-3 gap-2">
                                <div v-for="(img, i) in room.gallery_images" :key="i"
                                    :class="i === 0 ? 'col-span-2 row-span-2' : ''"
                                    class="overflow-hidden rounded-xl">
                                    <div :class="i === 0 ? 'aspect-[4/3]' : 'aspect-square'">
                                        <img :src="`/storage/${img}`" :alt="room.name + ' ' + (i+1)"
                                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-300 cursor-pointer" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="bg-white rounded-2xl p-7 shadow-sm">
                            <h2 class="font-bold text-gray-800 mb-4 text-lg">About This Room</h2>
                            <div v-if="room.description" v-html="room.description" class="prose prose-sm max-w-none text-gray-600"></div>
                            <p v-else class="text-gray-500 text-sm">{{ room.short_desc }}</p>
                        </div>

                        <!-- Amenities -->
                        <div v-if="room.amenities?.length" class="bg-white rounded-2xl p-7 shadow-sm">
                            <h2 class="font-bold text-gray-800 mb-4 text-lg">Amenities</h2>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                <div v-for="amenity in room.amenities" :key="amenity"
                                    class="flex items-center gap-2.5 text-sm text-gray-700">
                                    <div class="w-5 h-5 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-3 h-3 text-amber-600" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                    </div>
                                    {{ amenity }}
                                </div>
                            </div>
                        </div>

                        <!-- Features -->
                        <div v-if="room.features?.length" class="bg-white rounded-2xl p-7 shadow-sm">
                            <h2 class="font-bold text-gray-800 mb-4 text-lg">Room Features</h2>
                            <ul class="space-y-2.5">
                                <li v-for="feature in room.features" :key="feature" class="flex items-start gap-2.5 text-sm text-gray-600">
                                    <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ feature }}
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Pricing card -->
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 sticky top-24">
                            <div class="text-center pb-5 border-b border-gray-100 mb-5">
                                <!-- Special offer badge -->
                                <div v-if="room.discounted_price_bdt || room.discounted_price_usd"
                                    class="inline-block mb-3 text-xs bg-red-500 text-white px-3 py-1 rounded-full font-semibold tracking-wide uppercase">
                                    Special Offer
                                </div>

                                <!-- BDT pricing -->
                                <div class="flex items-baseline justify-center gap-2 mb-1">
                                    <span v-if="room.discounted_price_bdt" class="text-lg text-gray-400 line-through font-normal">
                                        ৳{{ Number(room.price).toLocaleString() }}
                                    </span>
                                    <span class="text-3xl font-bold text-amber-600">
                                        ৳{{ Number(room.discounted_price_bdt ?? room.price).toLocaleString() }}
                                    </span>
                                </div>

                                <!-- USD pricing -->
                                <div v-if="room.price_usd" class="flex items-baseline justify-center gap-2 mb-1">
                                    <span v-if="room.discounted_price_usd" class="text-sm text-gray-400 line-through">
                                        ${{ Number(room.price_usd).toFixed(2) }}
                                    </span>
                                    <span class="text-lg font-semibold text-gray-600">
                                        ${{ Number(room.discounted_price_usd ?? room.price_usd).toFixed(2) }}
                                    </span>
                                </div>

                                <div class="text-gray-400 text-sm">{{ room.price_unit }}</div>
                            </div>

                            <!-- Quick specs -->
                            <div class="space-y-3 mb-6">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">Room Type</span>
                                    <span class="font-medium text-gray-800">{{ room.name }}</span>
                                </div>
                                <div v-if="room.bed_type" class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">Bed Type</span>
                                    <span class="font-medium text-gray-800">{{ room.bed_type }}</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">Capacity</span>
                                    <span class="font-medium text-gray-800">{{ room.max_adults }} Adults{{ room.max_children > 0 ? ` + ${room.max_children} Children` : '' }}</span>
                                </div>
                                <div v-if="room.check_in_time" class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">Check-in</span>
                                    <span class="font-medium text-gray-800">{{ room.check_in_time }}</span>
                                </div>
                                <div v-if="room.check_out_time" class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">Check-out</span>
                                    <span class="font-medium text-gray-800">{{ room.check_out_time }}</span>
                                </div>
                                <div v-if="room.rating" class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">Rating</span>
                                    <span class="font-medium text-amber-600">★ {{ room.rating }}</span>
                                </div>
                            </div>

                            <Link :href="route('contact')"
                                class="block w-full text-center py-3.5 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl transition-colors mb-3">
                                Book This Room
                            </Link>
                            <Link :href="route('contact')"
                                class="block w-full text-center py-3 border border-gray-200 hover:border-gray-300 text-gray-600 hover:text-gray-800 font-medium rounded-xl transition-colors text-sm">
                                Ask a Question
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Related Rooms -->
                <div v-if="related.length > 0" class="mt-14">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Other Rooms You May Like</h2>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="r in related" :key="r.id"
                            class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                            <div class="aspect-[16/9] overflow-hidden">
                                <img v-if="r.gallery_images?.[0]" :src="`/storage/${r.gallery_images[0]}`" :alt="r.name"
                                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" />
                                <div v-else class="w-full h-full bg-slate-200"></div>
                            </div>
                            <div class="p-5">
                                <h3 class="font-bold text-gray-900 mb-1">{{ r.name }}</h3>
                                <p class="text-sm text-gray-400 mb-3 line-clamp-1">{{ r.short_desc }}</p>
                                <div class="flex items-center justify-between gap-2">
                                    <div>
                                        <div class="flex items-baseline gap-1.5">
                                            <span v-if="r.discounted_price_bdt" class="text-xs text-gray-400 line-through">৳{{ Number(r.price).toLocaleString() }}</span>
                                            <span class="text-amber-600 font-bold">৳{{ Number(r.discounted_price_bdt ?? r.price).toLocaleString() }}</span>
                                        </div>
                                        <div v-if="r.price_usd" class="flex items-baseline gap-1 text-xs">
                                            <span v-if="r.discounted_price_usd" class="text-gray-400 line-through">${{ Number(r.price_usd).toFixed(2) }}</span>
                                            <span class="text-gray-500 font-medium">${{ Number(r.discounted_price_usd ?? r.price_usd).toFixed(2) }}</span>
                                        </div>
                                        <span class="text-xs font-normal text-gray-400">{{ r.price_unit }}</span>
                                    </div>
                                    <Link :href="route('rooms.detail', r.slug)" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex-shrink-0">View →</Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </FrontLayout>
</template>

<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    room:    { type: Object, required: true },
    related: { type: Array,  default: () => [] },
});
</script>
