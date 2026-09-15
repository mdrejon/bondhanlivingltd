<template>
    <FrontLayout title="Contact Us">
        <div class="pt-24 pb-14 bg-gradient-to-br from-gray-900 to-slate-800 text-white text-center">
            <p class="text-amber-400 text-sm font-semibold uppercase tracking-widest mb-2">Get In Touch</p>
            <h1 class="text-4xl lg:text-5xl font-bold mb-3">Contact Us</h1>
            <p class="text-white/60 text-base max-w-xl mx-auto">We'd love to hear from you. Reach out for bookings, inquiries, or just to say hello.</p>
        </div>

        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <!-- Flash message -->
                <div v-if="$page.props.flash?.success" class="max-w-2xl mx-auto mb-8 bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-xl text-sm flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $page.props.flash.success }}
                </div>

                <div class="grid lg:grid-cols-5 gap-10">

                    <!-- Contact Info -->
                    <div class="lg:col-span-2 space-y-6">
                        <div class="bg-white rounded-2xl p-7 shadow-sm border border-gray-100">
                            <h2 class="text-xl font-bold text-gray-900 mb-6">Get In Touch</h2>
                            <div class="space-y-5">
                                <div v-if="settings.contact_address" class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Address</div>
                                        <p class="text-gray-700 text-sm">{{ settings.contact_address }}</p>
                                    </div>
                                </div>
                                <div v-if="settings.contact_phone" class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Phone</div>
                                        <a :href="`tel:${settings.contact_phone}`" class="text-gray-700 text-sm hover:text-amber-600">{{ settings.contact_phone }}</a>
                                    </div>
                                </div>
                                <div v-if="settings.contact_email" class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Email</div>
                                        <a :href="`mailto:${settings.contact_email}`" class="text-gray-700 text-sm hover:text-amber-600">{{ settings.contact_email }}</a>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Office Hours</div>
                                        <p class="text-gray-700 text-sm">{{ settings.contact_hours ?? 'Open 24/7' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Map -->
                        <div v-if="settings.contact_map_embed" class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100">
                            <div v-html="settings.contact_map_embed" class="w-full h-64"></div>
                        </div>
                        <div v-else class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100">
                            <div class="h-56 bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                                <svg class="w-12 h-12 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Form -->
                    <div class="lg:col-span-3">
                        <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                            <h2 class="text-xl font-bold text-gray-900 mb-6">Send Us a Message</h2>
                            <form @submit.prevent="submit" class="space-y-5">
                                <div class="grid sm:grid-cols-2 gap-5">
                                    <div>
                                        <label class="label">Your Name <span class="text-red-500">*</span></label>
                                        <input v-model="form.name" type="text" class="input" placeholder="Full name" required />
                                        <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
                                    </div>
                                    <div>
                                        <label class="label">Email Address <span class="text-red-500">*</span></label>
                                        <input v-model="form.email" type="email" class="input" placeholder="your@email.com" required />
                                        <p v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</p>
                                    </div>
                                </div>
                                <div class="grid sm:grid-cols-2 gap-5">
                                    <div>
                                        <label class="label">Phone Number</label>
                                        <input v-model="form.phone" type="tel" class="input" placeholder="+880..." />
                                    </div>
                                    <div>
                                        <label class="label">Subject</label>
                                        <input v-model="form.subject" type="text" class="input" placeholder="How can we help?" />
                                    </div>
                                </div>
                                <div>
                                    <label class="label">Message <span class="text-red-500">*</span></label>
                                    <textarea v-model="form.message" rows="5" class="input resize-none" placeholder="Write your message here..." required></textarea>
                                    <p v-if="form.errors.message" class="text-red-500 text-xs mt-1">{{ form.errors.message }}</p>
                                </div>
                                <button type="submit" :disabled="form.processing"
                                    class="w-full py-4 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl transition-colors disabled:opacity-60 text-sm">
                                    {{ form.processing ? 'Sending...' : 'Send Message' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </FrontLayout>
</template>

<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue';
import { useForm } from '@inertiajs/vue3';

defineProps({
    settings: { type: Object, default: () => ({}) },
});

const form = useForm({
    name:    '',
    email:   '',
    phone:   '',
    subject: '',
    message: '',
});

function submit() {
    form.post(route('contact.submit'), {
        onSuccess: () => form.reset(),
    });
}
</script>

<style scoped>
.label { @apply block text-sm font-medium text-gray-700 mb-1.5; }
.input { @apply w-full border border-gray-300 rounded-lg px-4 py-3 text-sm text-gray-800 focus:ring-2 focus:ring-amber-400 focus:border-amber-400 focus:outline-none transition-colors bg-white; }
</style>
