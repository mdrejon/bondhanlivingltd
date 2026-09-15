<template>
    <FrontLayout title="About Us">
        <!-- Hero -->
        <div class="pt-24 pb-14 bg-gradient-to-br from-gray-900 to-slate-800 text-white text-center">
            <p class="text-amber-400 text-sm font-semibold uppercase tracking-widest mb-2">Our Story</p>
            <h1 class="text-4xl lg:text-5xl font-bold mb-3">About {{ $page.props.site?.name }}</h1>
            <p class="text-white/60 text-base max-w-xl mx-auto">Where luxury meets the serenity of the ocean.</p>
        </div>

        <!-- About Section -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-14 items-center mb-20">
                    <div class="relative">
                        <div class="aspect-[4/3] rounded-2xl overflow-hidden shadow-2xl">
                            <img v-if="settings.about_image" :src="`/storage/${settings.about_image}`" alt="About" class="w-full h-full object-cover" />
                            <div v-else class="w-full h-full bg-gradient-to-br from-amber-100 to-amber-200 flex items-center justify-center">
                                <svg class="w-24 h-24 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H3"/></svg>
                            </div>
                        </div>
                        <div class="absolute -bottom-5 -right-5 bg-amber-500 text-white rounded-2xl p-5 shadow-xl hidden md:block">
                            <div class="text-3xl font-bold">{{ settings.about_years ?? '10' }}+</div>
                            <div class="text-amber-100 text-xs font-medium">Years of Excellence</div>
                        </div>
                    </div>
                    <div>
                        <p class="text-amber-600 text-sm font-semibold uppercase tracking-widest mb-3">Who We Are</p>
                        <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight mb-5">
                            {{ settings.about_title ?? 'A Legacy of Luxury & Comfort' }}
                        </h2>
                        <div v-if="settings.about_description" v-html="settings.about_description" class="prose prose-sm max-w-none text-gray-600 mb-8"></div>
                        <p v-else class="text-gray-600 leading-relaxed mb-8">
                            Founded with a passion for hospitality, Hotel Beach Way has been welcoming guests from around the world for over a decade. Our beachside location, combined with world-class amenities and dedicated staff, ensures every stay is truly memorable.
                        </p>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-20">
                    <div v-for="stat in aboutStats" :key="stat.label" class="text-center p-7 rounded-2xl bg-amber-50 border border-amber-100">
                        <div class="text-4xl font-bold text-amber-600 mb-1">{{ stat.value }}</div>
                        <div class="text-sm text-gray-500 font-medium">{{ stat.label }}</div>
                    </div>
                </div>

                <!-- Why Choose Us -->
                <div class="text-center mb-12">
                    <p class="text-amber-600 text-sm font-semibold uppercase tracking-widest mb-2">Why Choose Us</p>
                    <h2 class="text-3xl font-bold text-gray-900">The Beach Way Difference</h2>
                </div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-7">
                    <div v-for="item in whyUs" :key="item.title" class="p-6 rounded-2xl border border-gray-100 hover:border-amber-200 hover:shadow-md transition-all">
                        <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center mb-4">
                            <div v-html="item.icon" class="w-6 h-6 text-amber-600"></div>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-2">{{ item.title }}</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">{{ item.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="py-16 bg-gray-900 text-center">
            <div class="max-w-2xl mx-auto px-4">
                <h2 class="text-3xl font-bold text-white mb-4">Experience It Yourself</h2>
                <p class="text-gray-400 mb-8">Come and see why thousands of guests call Hotel Beach Way their home away from home.</p>
                <Link :href="route('rooms')" class="inline-flex items-center px-8 py-4 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-full transition-colors">
                    Explore Our Rooms
                </Link>
            </div>
        </section>
    </FrontLayout>
</template>

<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    settings: { type: Object, default: () => ({}) },
});

const page = usePage();

const aboutStats = computed(() => [
    { value: (props.settings.about_rooms  ?? '50') + '+',   label: 'Luxury Rooms' },
    { value: (props.settings.about_years  ?? '10') + '+',   label: 'Years Experience' },
    { value: (props.settings.about_guests ?? '5000') + '+', label: 'Happy Guests' },
    { value: (props.settings.about_staff  ?? '100') + '+',  label: 'Expert Staff' },
]);

const whyUs = [
    {
        title: 'Prime Beachside Location',
        desc: 'Nestled directly on the beach, every room offers stunning ocean views and direct access to pristine sandy shores.',
        icon: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>`,
    },
    {
        title: 'World-Class Amenities',
        desc: 'From our infinity pool and spa to our gourmet restaurant, every facility is designed to exceed your expectations.',
        icon: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>`,
    },
    {
        title: '24/7 Dedicated Service',
        desc: 'Our passionate team is available around the clock to ensure your every need is met with warmth and professionalism.',
        icon: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
    },
    {
        title: 'Personalized Experience',
        desc: 'We believe every guest is unique. Our team tailors every aspect of your stay to your personal preferences.',
        icon: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>`,
    },
    {
        title: 'Sustainably Minded',
        desc: 'We are committed to environmental responsibility, using eco-friendly practices to protect the beauty of our coastal surroundings.',
        icon: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>`,
    },
    {
        title: 'Trusted & Highly Rated',
        desc: 'Consistently rated 4.9/5 by our guests across all major travel platforms — quality you can trust.',
        icon: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>`,
    },
];
</script>
