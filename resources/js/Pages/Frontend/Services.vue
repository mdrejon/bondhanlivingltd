<template>
    <FrontLayout title="Our Services">
        <div class="pt-24 pb-14 bg-gradient-to-br from-gray-900 to-slate-800 text-white text-center">
            <p class="text-amber-400 text-sm font-semibold uppercase tracking-widest mb-2">What We Offer</p>
            <h1 class="text-4xl lg:text-5xl font-bold mb-3">Our Services</h1>
            <p class="text-white/60 text-base max-w-xl mx-auto">We provide a full range of premium services to make your stay extraordinary.</p>
        </div>

        <section class="py-16 bg-gray-50 min-h-[50vh]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div v-if="services.length > 0" class="space-y-12">
                    <div v-for="(service, i) in services" :key="service.id"
                        :class="i % 2 === 1 ? 'flex-row-reverse' : ''"
                        class="flex flex-col md:flex-row gap-10 items-center bg-white rounded-2xl p-8 shadow-sm">
                        <!-- Image -->
                        <div class="md:w-2/5 w-full flex-shrink-0">
                            <div class="aspect-[4/3] rounded-xl overflow-hidden">
                                <img v-if="service.image" :src="`/storage/${service.image}`" :alt="service.title"
                                    class="w-full h-full object-cover" />
                                <div v-else class="w-full h-full bg-gradient-to-br from-amber-100 to-amber-200 flex items-center justify-center">
                                    <div v-if="service.icon_svg" v-html="service.icon_svg" class="w-16 h-16 text-amber-400"></div>
                                    <svg v-else class="w-16 h-16 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <!-- Content -->
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                <div v-if="service.icon_svg" class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                                    <div v-html="service.icon_svg" class="w-5 h-5 text-amber-600"></div>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900">{{ service.title }}</h2>
                            </div>
                            <p class="text-gray-600 leading-relaxed mb-4">{{ service.short_desc }}</p>
                            <div v-if="service.description" v-html="service.description" class="prose prose-sm max-w-none text-gray-500 mb-5"></div>

                            <!-- Features list -->
                            <ul v-if="service.features?.length" class="space-y-2 mb-5">
                                <li v-for="feat in service.features" :key="feat" class="flex items-center gap-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ feat }}
                                </li>
                            </ul>

                            <a v-if="service.btn_url" :href="service.btn_url"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-full text-sm transition-colors">
                                {{ service.btn_text ?? 'Learn More' }}
                            </a>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-24 text-gray-400">
                    <p class="text-lg font-medium">No services listed yet.</p>
                </div>
            </div>
        </section>
    </FrontLayout>
</template>

<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue';

defineProps({
    services: { type: Array, default: () => [] },
});
</script>
