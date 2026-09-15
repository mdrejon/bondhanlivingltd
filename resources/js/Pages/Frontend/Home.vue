<template>
    <FrontLayout transparent>

        <!-- ===== HERO SLIDER ===== -->
        <section class="relative h-screen min-h-[600px] overflow-hidden bg-gray-900">
            <!-- Slides -->
            <transition-group name="fade">
                <div v-for="(slide, i) in heroSlides" :key="slide.id"
                    v-show="currentSlide === i"
                    class="absolute inset-0">
                    <img v-if="slide.image"
                        :src="`/storage/${slide.image}`"
                        :alt="slide.title"
                        class="w-full h-full object-cover opacity-75" />
                    <div v-else class="w-full h-full bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800"></div>
                    <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-black/20 to-black/60"></div>
                </div>
            </transition-group>

            <!-- Fallback when no slides -->
            <div v-if="heroSlides.length === 0" class="absolute inset-0 bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800">
                <div class="absolute inset-0 bg-black/20"></div>
            </div>

            <!-- Hero Content -->
            <div class="relative z-10 h-full flex items-center">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                    <div class="max-w-2xl">
                        <transition name="slide-up" mode="out-in">
                            <div :key="currentSlide">
                                <p class="text-amber-400 text-sm font-semibold uppercase tracking-widest mb-3">
                                    {{ currentSlideData?.subtitle ?? 'Welcome to ' + site.name }}
                                </p>
                                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                                    {{ currentSlideData?.title ?? 'Your Perfect Beachside Escape' }}
                                </h1>
                                <p v-if="currentSlideData?.description" class="text-white/80 text-lg mb-8 leading-relaxed">
                                    {{ currentSlideData.description }}
                                </p>
                            </div>
                        </transition>
                        <div class="flex flex-wrap items-center gap-4">
                            <Link :href="route('rooms')" class="inline-flex items-center px-8 py-4 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-full transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 transform">
                                Explore Rooms
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </Link>
                            <Link :href="route('contact')" class="inline-flex items-center px-8 py-4 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-full border border-white/30 backdrop-blur-sm transition-all">
                                Contact Us
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slider Dots -->
            <div v-if="heroSlides.length > 1" class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 flex items-center gap-2">
                <button v-for="(_, i) in heroSlides" :key="i"
                    @click="currentSlide = i"
                    :class="[currentSlide === i ? 'bg-amber-500 w-6' : 'bg-white/50 hover:bg-white/80 w-2', 'h-2 rounded-full transition-all duration-300']">
                </button>
            </div>

            <!-- Scroll hint -->
            <div class="absolute bottom-8 right-8 z-10 hidden md:flex flex-col items-center gap-1 text-white/50">
                <span class="text-xs tracking-widest uppercase">Scroll</span>
                <div class="w-px h-8 bg-white/30 animate-bounce"></div>
            </div>
        </section>

        <!-- ===== STATS BAR ===== -->
        <section class="bg-amber-500 py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center text-white">
                    <div v-for="stat in stats" :key="stat.label">
                        <div class="text-3xl font-bold">{{ stat.value }}</div>
                        <div class="text-amber-100 text-sm font-medium mt-0.5">{{ stat.label }}</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===== WELCOME / ABOUT ===== -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-14 items-center">
                    <div class="relative">
                        <div class="aspect-[4/3] rounded-2xl overflow-hidden shadow-2xl">
                            <img v-if="settings.about_image" :src="`/storage/${settings.about_image}`" alt="About" class="w-full h-full object-cover" />
                            <div v-else class="w-full h-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                                <svg class="w-24 h-24 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H3m14 0h2M3 21h2"/></svg>
                            </div>
                        </div>
                        <!-- Floating badge -->
                        <div class="absolute -bottom-5 -right-5 bg-amber-500 text-white rounded-2xl p-5 shadow-xl hidden md:block">
                            <div class="text-3xl font-bold">{{ settings.about_years ?? '10' }}+</div>
                            <div class="text-amber-100 text-xs font-medium">Years of Excellence</div>
                        </div>
                    </div>
                    <div>
                        <p class="text-amber-600 text-sm font-semibold uppercase tracking-widest mb-3">About Our Hotel</p>
                        <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight mb-5">
                            {{ settings.about_title ?? 'Luxury Redefined at the Beachside' }}
                        </h2>
                        <p class="text-gray-600 leading-relaxed mb-5" v-html="settings.about_short_desc ?? 'Experience unparalleled comfort and hospitality at Hotel Beach Way. Nestled along the pristine coastline, we offer world-class amenities and personalized service to make every stay memorable.'"></p>
                        <p v-if="settings.about_description" class="text-gray-500 leading-relaxed mb-8" v-html="settings.about_description"></p>
                        <div class="flex flex-wrap gap-6 mb-8">
                            <div v-if="settings.about_rooms" class="text-center">
                                <div class="text-2xl font-bold text-amber-600">{{ settings.about_rooms }}</div>
                                <div class="text-xs text-gray-500 font-medium">Luxury Rooms</div>
                            </div>
                            <div v-if="settings.about_staff" class="text-center">
                                <div class="text-2xl font-bold text-amber-600">{{ settings.about_staff }}</div>
                                <div class="text-xs text-gray-500 font-medium">Expert Staff</div>
                            </div>
                            <div v-if="settings.about_guests" class="text-center">
                                <div class="text-2xl font-bold text-amber-600">{{ settings.about_guests }}</div>
                                <div class="text-xs text-gray-500 font-medium">Happy Guests</div>
                            </div>
                        </div>
                        <Link :href="route('about')" class="inline-flex items-center gap-2 px-7 py-3.5 bg-gray-900 hover:bg-gray-800 text-white font-semibold rounded-full transition-colors">
                            Learn More
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===== FEATURED ROOMS ===== -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <p class="text-amber-600 text-sm font-semibold uppercase tracking-widest mb-2">Accommodations</p>
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900">Our Rooms & Suites</h2>
                    <p class="text-gray-500 mt-3 max-w-xl mx-auto">Choose from our thoughtfully designed rooms, each offering the perfect blend of comfort and luxury.</p>
                </div>
                <div v-if="displayRooms.length > 0" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-7">
                    <div v-for="room in displayRooms" :key="room.id" class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                        <div class="aspect-[16/10] overflow-hidden relative">
                            <img v-if="room.gallery_images?.[0]" :src="`/storage/${room.gallery_images[0]}`" :alt="room.name"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            <div v-else class="w-full h-full bg-gradient-to-br from-slate-200 to-slate-300 flex items-center justify-center">
                                <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
                            </div>
                            <!-- Special offer badge -->
                            <div v-if="room.discounted_price_bdt || room.discounted_price_usd"
                                class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">
                                Special Offer
                            </div>
                            <!-- Price badge -->
                            <div class="absolute top-3 right-3 text-right">
                                <div class="flex flex-col items-end gap-0.5">
                                    <span class="bg-amber-500 text-white text-xs font-bold px-3 py-1 rounded-full leading-tight">
                                        ৳{{ Number(room.discounted_price_bdt ?? room.price).toLocaleString() }} {{ room.price_unit }}
                                    </span>
                                    <span v-if="room.discounted_price_bdt"
                                        class="bg-black/40 text-white/80 text-xs px-2.5 py-0.5 rounded-full line-through leading-tight">
                                        ৳{{ Number(room.price).toLocaleString() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="p-5">
                            <h3 class="text-lg font-bold text-gray-900 mb-1.5">{{ room.name }}</h3>
                            <!-- USD price row -->
                            <div v-if="room.price_usd" class="flex items-baseline gap-1.5 mb-2">
                                <span v-if="room.discounted_price_usd" class="text-xs text-gray-400 line-through">
                                    ${{ Number(room.price_usd).toFixed(2) }}
                                </span>
                                <span class="text-sm font-semibold text-gray-500">
                                    ${{ Number(room.discounted_price_usd ?? room.price_usd).toFixed(2) }}
                                </span>
                                <span class="text-xs text-gray-400">{{ room.price_unit }}</span>
                            </div>
                            <p class="text-gray-500 text-sm mb-4 line-clamp-2">{{ room.short_desc }}</p>
                            <div class="flex items-center gap-4 text-xs text-gray-500 mb-4">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ room.max_adults }} Adults
                                </span>
                                <span v-if="room.bed_type" class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10v11h18V10M3 10l9-7 9 7"/></svg>
                                    {{ room.bed_type }}
                                </span>
                                <span v-if="room.rating" class="flex items-center gap-1 text-amber-500 font-semibold">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    {{ room.rating }}
                                </span>
                            </div>
                            <Link :href="route('rooms.detail', room.slug)"
                                class="block w-full text-center py-2.5 border-2 border-amber-500 text-amber-600 hover:bg-amber-500 hover:text-white font-semibold rounded-xl transition-colors text-sm">
                                View Details
                            </Link>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-16 text-gray-400">No rooms available yet.</div>
                <div class="text-center mt-10">
                    <Link :href="route('rooms')" class="inline-flex items-center gap-2 px-8 py-3.5 bg-gray-900 hover:bg-gray-800 text-white font-semibold rounded-full transition-colors">
                        View All Rooms
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </Link>
                </div>
            </div>
        </section>

        <!-- ===== SERVICES ===== -->
        <section v-if="services.length > 0" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <p class="text-amber-600 text-sm font-semibold uppercase tracking-widest mb-2">What We Offer</p>
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900">Our Services</h2>
                </div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-7">
                    <div v-for="service in services" :key="service.id"
                        class="group p-7 rounded-2xl border border-gray-100 hover:border-amber-200 hover:bg-amber-50/50 transition-all">
                        <div class="w-14 h-14 rounded-2xl bg-amber-100 group-hover:bg-amber-500 flex items-center justify-center mb-5 transition-colors">
                            <div v-if="service.icon_svg" v-html="service.icon_svg" class="w-7 h-7 text-amber-600 group-hover:text-white transition-colors"></div>
                            <svg v-else class="w-7 h-7 text-amber-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ service.title }}</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">{{ service.short_desc }}</p>
                    </div>
                </div>
                <div class="text-center mt-10">
                    <Link :href="route('services.front')" class="inline-flex items-center gap-2 text-amber-600 hover:text-amber-700 font-semibold text-sm">
                        View All Services
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </Link>
                </div>
            </div>
        </section>

        <!-- ===== GALLERY STRIP ===== -->
        <section v-if="gallery.length > 0" class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <p class="text-amber-600 text-sm font-semibold uppercase tracking-widest mb-2">Visual Tour</p>
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900">Photo Gallery</h2>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    <div v-for="(img, i) in gallery.slice(0, 6)" :key="img.id"
                        :class="i === 0 ? 'col-span-2 md:col-span-1 row-span-2' : ''"
                        class="overflow-hidden rounded-xl group cursor-pointer"
                        @click="lightboxIndex = i; showLightbox = true">
                        <div :class="i === 0 ? 'aspect-[4/5]' : 'aspect-square'">
                            <img :src="`/storage/${img.image}`" :alt="img.title ?? 'Gallery'"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        </div>
                    </div>
                </div>
                <div class="text-center mt-8">
                    <Link :href="route('gallery.front')" class="inline-flex items-center gap-2 px-7 py-3.5 bg-gray-900 hover:bg-gray-800 text-white font-semibold rounded-full transition-colors">
                        View Full Gallery
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </Link>
                </div>
            </div>
        </section>

        <!-- ===== TESTIMONIALS ===== -->
        <section v-if="testimonials.length > 0" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <p class="text-amber-600 text-sm font-semibold uppercase tracking-widest mb-2">Guest Voices</p>
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900">What Our Guests Say</h2>
                </div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="t in testimonials.slice(0, 6)" :key="t.id"
                        class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                        <!-- Stars -->
                        <div class="flex gap-0.5 mb-4">
                            <svg v-for="s in 5" :key="s" class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                        <p class="text-gray-600 text-sm leading-relaxed mb-5">"{{ t.content ?? t.review }}"</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full overflow-hidden bg-amber-100 flex-shrink-0">
                                <img v-if="t.image" :src="`/storage/${t.image}`" :alt="t.name" class="w-full h-full object-cover" />
                                <div v-else class="w-full h-full flex items-center justify-center text-amber-700 font-bold text-sm">
                                    {{ t.name?.charAt(0) ?? 'G' }}
                                </div>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">{{ t.name }}</div>
                                <div v-if="t.designation ?? t.role" class="text-xs text-gray-400">{{ t.designation ?? t.role }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===== FAQS ===== -->
        <section v-if="faqs.length > 0" class="py-20 bg-gray-50">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <p class="text-amber-600 text-sm font-semibold uppercase tracking-widest mb-2">Have Questions?</p>
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900">Frequently Asked Questions</h2>
                </div>
                <div class="space-y-3">
                    <div v-for="faq in faqs" :key="faq.id"
                        class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <button @click="openFaq === faq.id ? openFaq = null : openFaq = faq.id"
                            class="w-full flex items-center justify-between px-6 py-4 text-left gap-4">
                            <span class="font-semibold text-gray-800 text-sm">{{ faq.question }}</span>
                            <svg :class="openFaq === faq.id ? 'rotate-45' : ''" class="w-5 h-5 text-amber-500 flex-shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>
                        <div v-show="openFaq === faq.id" class="px-6 pb-5">
                            <p class="text-gray-500 text-sm leading-relaxed">{{ faq.answer }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===== CTA BANNER ===== -->
        <section class="py-20 bg-gradient-to-r from-amber-500 to-orange-500">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4">Ready to Book Your Stay?</h2>
                <p class="text-amber-100 text-lg mb-8">Experience luxury, comfort, and the beauty of the beachside. Your dream vacation starts here.</p>
                <div class="flex flex-wrap items-center justify-center gap-4">
                    <Link :href="route('rooms')" class="inline-flex items-center px-9 py-4 bg-white text-amber-600 font-bold rounded-full hover:bg-gray-50 transition-all shadow-lg hover:shadow-xl">
                        View Rooms
                    </Link>
                    <Link :href="route('contact')" class="inline-flex items-center px-9 py-4 bg-transparent text-white font-semibold rounded-full border-2 border-white/60 hover:border-white transition-all">
                        Get In Touch
                    </Link>
                </div>
            </div>
        </section>

    </FrontLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import FrontLayout from '@/Layouts/FrontLayout.vue';

const props = defineProps({
    sliders:       { type: Array, default: () => [] },
    featuredRooms: { type: Array, default: () => [] },
    allRooms:      { type: Array, default: () => [] },
    services:      { type: Array, default: () => [] },
    gallery:       { type: Array, default: () => [] },
    testimonials:  { type: Array, default: () => [] },
    faqs:          { type: Array, default: () => [] },
    settings:      { type: Object, default: () => ({}) },
});

const page = usePage();
const site = computed(() => page.props.site ?? {});

// Rooms: prefer featured, fall back to all
const displayRooms = computed(() =>
    props.featuredRooms.length > 0 ? props.featuredRooms : props.allRooms
);

// Hero slider
const heroSlides = computed(() => props.sliders);
const currentSlide = ref(0);
const currentSlideData = computed(() => heroSlides.value[currentSlide.value] ?? null);

// Auto-advance slider
import { onMounted, onUnmounted } from 'vue';
let sliderInterval = null;
onMounted(() => {
    if (heroSlides.value.length > 1) {
        sliderInterval = setInterval(() => {
            currentSlide.value = (currentSlide.value + 1) % heroSlides.value.length;
        }, 5000);
    }
});
onUnmounted(() => clearInterval(sliderInterval));

// FAQ accordion
const openFaq = ref(null);

// Stats
const stats = [
    { value: '50+',   label: 'Luxury Rooms'  },
    { value: '10+',   label: 'Years Experience' },
    { value: '5000+', label: 'Happy Guests'   },
    { value: '4.9★',  label: 'Guest Rating'   },
];

// Lightbox (simple)
const showLightbox = ref(false);
const lightboxIndex = ref(0);
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 1s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
.slide-up-enter-active { transition: all 0.5s ease; }
.slide-up-enter-from { opacity: 0; transform: translateY(20px); }
</style>
