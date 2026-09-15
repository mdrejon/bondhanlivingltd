<template>
    <AdminLayout>
        <div class="max-w-3xl space-y-6">
            <h1 class="text-lg font-semibold text-gray-800">Booking Page Settings</h1>

            <!-- Flash -->
            <div v-if="$page.props.flash?.success"
                class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded">
                {{ $page.props.flash.success }}
            </div>

            <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-6">

                <!-- Page Hero & Breadcrumb -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Page Hero &amp; Breadcrumb</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2 md:col-span-1">
                            <label class="label">Hero Background Image</label>
                            <DropZone @change="file => onImg('hero', file)" hint="JPEG / PNG / WebP — max 5 MB" preview-class="w-full h-44 object-cover"
                                :existing-preview="currentHeroImg ? '/storage/' + currentHeroImg : null" />
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="label">Page Title (Breadcrumb)</label>
                            <input v-model="form.booking_hero_title" type="text" class="input" placeholder="Book Now" />
                            <p class="text-xs text-gray-400 mt-1">Shown as the page heading and the breadcrumb's current item.</p>
                        </div>
                    </div>
                </section>

                <!-- Child Policy -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Child Policy Note</h2>
                    <div>
                        <label class="label">Note Content</label>
                        <textarea v-model="form.booking_child_policy_note" rows="6" class="input resize-y"
                            placeholder="Couple Room: 02 Adults + 01 Child (Below 5 Years)&#10;Triple Room: 03 Adults + 01 Child (Below 5 Years)"></textarea>
                        <p class="text-xs text-gray-400 mt-1">One policy line per row. Shown as a note on the Book Now page under Room Selection.</p>
                    </div>
                </section>

                <!-- Cancellation Policy -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Cancellation Policy</h2>
                    <div>
                        <label class="label">Policy Text</label>
                        <textarea v-model="form.booking_cancellation_policy" rows="4" class="input resize-y"
                            placeholder="Free cancellation up to 72 hours prior to check-in. Cancellations made within 72 hours of check-in are non-refundable."></textarea>
                    </div>
                </section>

                <!-- Need Assistance -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Need Assistance Contact</h2>
                    <p class="text-xs text-gray-400 -mt-2">Leave blank to fall back to the Footer Settings contact info.</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Mobile Number</label>
                            <input v-model="form.booking_assist_phone" type="text" class="input" placeholder="+88 01777-909595" />
                        </div>
                        <div>
                            <label class="label">Email Address</label>
                            <input v-model="form.booking_assist_email" type="email" class="input" placeholder="info@hotelbeachway.com" />
                        </div>
                    </div>
                </section>

                <!-- SEO Configuration -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">SEO Configuration</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="label">Meta Title <span class="text-gray-400 font-normal">({{ form.booking_seo_title.length }}/160)</span></label>
                            <input v-model="form.booking_seo_title" @input="onMetaTitleInput" type="text" maxlength="160" class="input"
                                placeholder="Book Your Stay – Hotel Beach Way" />
                        </div>
                        <div class="col-span-2">
                            <label class="label">Meta Description <span class="text-gray-400 font-normal">({{ form.booking_seo_description.length }}/320)</span></label>
                            <textarea v-model="form.booking_seo_description" @input="onMetaDescInput" rows="3" maxlength="320" class="input resize-none"
                                placeholder="Reserve your room at Hotel Beach Way, Cox's Bazar. Easy online booking with instant confirmation."></textarea>
                        </div>
                        <div class="col-span-2">
                            <label class="label">Keywords</label>
                            <input v-model="form.booking_seo_keywords" @input="onMetaKeywordsInput" type="text" class="input"
                                placeholder="book hotel cox's bazar, hotel beach way booking, reserve room cox's bazar" />
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="label">OG / Social Share Image</label>
                            <DropZone @change="file => onImg('og', file)" hint="JPEG / PNG / WebP — max 5 MB" preview-class="w-full h-36 object-cover"
                                :existing-preview="currentOgImg ? '/storage/' + currentOgImg : null" />
                        </div>
                    </div>
                </section>

                <div class="flex justify-end">
                    <button type="submit" :disabled="form.processing"
                        class="px-6 py-2.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-60">
                        {{ form.processing ? 'Saving...' : 'Save Settings' }}
                    </button>
                </div>

            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import DropZone from '@/Components/Admin/Shared/DropZone.vue';
import { useSeoAutoFill } from '@/Composables/useSeoAutoFill';

const props = defineProps({
    settings: { type: Object, default: () => ({}) },
});

const currentHeroImg = ref(props.settings.booking_hero_image  ?? null);
const currentOgImg   = ref(props.settings.booking_seo_og_image ?? null);

const form = useForm({
    booking_hero_title:          props.settings.booking_hero_title          ?? '',
    booking_seo_title:           props.settings.booking_seo_title           ?? '',
    booking_seo_description:     props.settings.booking_seo_description     ?? '',
    booking_seo_keywords:        props.settings.booking_seo_keywords        ?? '',
    booking_child_policy_note:   props.settings.booking_child_policy_note   ?? '',
    booking_cancellation_policy: props.settings.booking_cancellation_policy ?? '',
    booking_assist_phone:        props.settings.booking_assist_phone        ?? '',
    booking_assist_email:        props.settings.booking_assist_email        ?? '',
    booking_hero_image:          null,
    booking_seo_og_image:        null,
});

const { onMetaTitleInput, onMetaDescInput, onMetaKeywordsInput } = useSeoAutoFill(form, {
    titleSource: () => form.booking_hero_title,
    descSource:  () => form.booking_seo_description,
    titleKey:    'booking_seo_title',
    descKey:     'booking_seo_description',
    keywordsKey: 'booking_seo_keywords',
    titleSuffix: ' – Hotel Beach Way',
});

function onImg(type, file) {
    if (!file) return;
    if (type === 'hero') form.booking_hero_image   = file;
    if (type === 'og')   form.booking_seo_og_image = file;
}

function submit() {
    form.transform(data => ({ ...data, _method: 'PUT' }))
        .post(route('admin.website-settings.booking-page.update'), { forceFormData: true });
}
</script>

<style scoped>
.label { @apply block text-sm text-gray-600 mb-1; }
.input { @apply w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none; }
</style>
