<template>
    <FrontLayout title="FAQs">
        <div class="pt-24 pb-14 bg-gradient-to-br from-gray-900 to-slate-800 text-white text-center">
            <p class="text-amber-400 text-sm font-semibold uppercase tracking-widest mb-2">Have Questions?</p>
            <h1 class="text-4xl lg:text-5xl font-bold mb-3">Frequently Asked Questions</h1>
            <p class="text-white/60 text-base max-w-xl mx-auto">Find answers to the most common questions about your stay.</p>
        </div>

        <section class="py-16 bg-gray-50 min-h-[50vh]">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div v-if="faqs.length > 0" class="space-y-3">
                    <div v-for="faq in faqs" :key="faq.id"
                        class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                        <button @click="toggle(faq.id)"
                            class="w-full flex items-center justify-between px-7 py-5 text-left gap-4">
                            <span class="font-semibold text-gray-800">{{ faq.question }}</span>
                            <div :class="openId === faq.id ? 'bg-amber-500' : 'bg-gray-100 hover:bg-gray-200'"
                                class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-colors">
                                <svg :class="openId === faq.id ? 'text-white rotate-45' : 'text-gray-500'"
                                    class="w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                        </button>
                        <div v-show="openId === faq.id" class="px-7 pb-6">
                            <p class="text-gray-500 leading-relaxed text-sm">{{ faq.answer }}</p>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-20 text-gray-400">
                    <p class="text-lg font-medium">No FAQs added yet.</p>
                </div>

                <!-- Still have questions? -->
                <div class="mt-12 text-center p-8 bg-amber-50 rounded-2xl border border-amber-100">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Still have questions?</h3>
                    <p class="text-gray-500 text-sm mb-5">Our friendly team is always happy to help you out.</p>
                    <Link :href="route('contact')" class="inline-flex items-center gap-2 px-7 py-3.5 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-full transition-colors">
                        Contact Us
                    </Link>
                </div>
            </div>
        </section>
    </FrontLayout>
</template>

<script setup>
import { ref } from 'vue';
import FrontLayout from '@/Layouts/FrontLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    faqs: { type: Array, default: () => [] },
});

const openId = ref(null);
function toggle(id) {
    openId.value = openId.value === id ? null : id;
}
</script>
