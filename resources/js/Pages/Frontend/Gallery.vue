<template>
    <FrontLayout title="Gallery">
        <div class="pt-24 pb-14 bg-gradient-to-br from-gray-900 to-slate-800 text-white text-center">
            <p class="text-amber-400 text-sm font-semibold uppercase tracking-widest mb-2">Visual Tour</p>
            <h1 class="text-4xl lg:text-5xl font-bold mb-3">Photo Gallery</h1>
            <p class="text-white/60 text-base max-w-xl mx-auto">Experience the beauty of Hotel Beach Way through our gallery.</p>
        </div>

        <section class="py-14 bg-gray-50 min-h-[50vh]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div v-if="images.length > 0" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    <div v-for="(img, i) in images" :key="img.id"
                        :class="[(i % 7 === 0) ? 'col-span-2 row-span-2' : '']"
                        class="overflow-hidden rounded-xl group cursor-pointer"
                        @click="open(i)">
                        <div :class="(i % 7 === 0) ? 'aspect-square' : 'aspect-square'">
                            <img :src="`/storage/${img.image}`" :alt="img.title ?? 'Hotel'"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-24 text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="text-lg font-medium">No gallery images yet.</p>
                </div>
            </div>
        </section>

        <!-- Lightbox -->
        <div v-if="lightbox !== null" class="fixed inset-0 z-[100] bg-black/90 flex items-center justify-center"
            @click.self="lightbox = null">
            <button @click="lightbox = null" class="absolute top-4 right-4 text-white/70 hover:text-white">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <button v-if="lightbox > 0" @click="lightbox--" class="absolute left-4 text-white/70 hover:text-white">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <img :src="`/storage/${images[lightbox]?.image}`" :alt="images[lightbox]?.title ?? 'Gallery'"
                class="max-h-[85vh] max-w-[90vw] object-contain rounded-lg shadow-2xl" />
            <button v-if="lightbox < images.length - 1" @click="lightbox++" class="absolute right-4 text-white/70 hover:text-white">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div class="absolute bottom-4 text-white/50 text-sm">{{ lightbox + 1 }} / {{ images.length }}</div>
        </div>
    </FrontLayout>
</template>

<script setup>
import { ref } from 'vue';
import FrontLayout from '@/Layouts/FrontLayout.vue';

const props = defineProps({
    images: { type: Array, default: () => [] },
});

const lightbox = ref(null);
function open(i) { lightbox.value = i; }
</script>
