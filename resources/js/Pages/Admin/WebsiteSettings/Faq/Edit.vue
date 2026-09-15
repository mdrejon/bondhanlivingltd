<template>
    <AdminLayout>
        <div class="max-w-3xl space-y-6">
            <h1 class="text-lg font-semibold text-gray-800">FAQ Page Settings</h1>

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
                            <input v-model="form.faq_hero_title" type="text" class="input" placeholder="Frequently Asked Questions" />
                        </div>
                    </div>
                </section>

                <!-- Section Content -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">FAQ Section Content</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Badge Text</label>
                            <input v-model="form.faq_page_badge" type="text" class="input" placeholder="FAQ'S" />
                        </div>
                        <div>
                            <label class="label">Section Title</label>
                            <input v-model="form.faq_page_title" type="text" class="input" placeholder="Most Questions That Asked By People." />
                        </div>
                        <div class="col-span-2">
                            <label class="label">Section Description</label>
                            <textarea v-model="form.faq_page_desc" rows="3" class="input resize-none"
                                placeholder="Find answers to the most common questions..."></textarea>
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="label">Side Image</label>
                            <DropZone @change="file => onImg('page', file)" hint="JPEG / PNG / WebP — max 5 MB" preview-class="w-full h-44 object-cover"
                                :existing-preview="currentPageImg ? '/storage/' + currentPageImg : null" />
                        </div>
                    </div>
                </section>

                <!-- SEO Configuration -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">SEO Configuration</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="label">Meta Title <span class="text-gray-400 font-normal">({{ form.faq_seo_title.length }}/160)</span></label>
                            <input v-model="form.faq_seo_title" @input="onMetaTitleInput" type="text" maxlength="160" class="input"
                                placeholder="FAQs – Hotel Beach Way" />
                        </div>
                        <div class="col-span-2">
                            <label class="label">Meta Description <span class="text-gray-400 font-normal">({{ form.faq_seo_description.length }}/320)</span></label>
                            <textarea v-model="form.faq_seo_description" @input="onMetaDescInput" rows="3" maxlength="320" class="input resize-none"
                                placeholder="Find answers to frequently asked questions about Hotel Beach Way..."></textarea>
                        </div>
                        <div class="col-span-2">
                            <label class="label">Keywords</label>
                            <input v-model="form.faq_seo_keywords" @input="onMetaKeywordsInput" type="text" class="input"
                                placeholder="hotel faq, cox's bazar hotel questions, hotel beach way help" />
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

const currentHeroImg = ref(props.settings.faq_hero_image  ?? null);
const currentOgImg   = ref(props.settings.faq_seo_og_image ?? null);
const currentPageImg = ref(props.settings.faq_page_image  ?? null);

const form = useForm({
    faq_hero_title:      props.settings.faq_hero_title      ?? '',
    faq_seo_title:       props.settings.faq_seo_title       ?? '',
    faq_seo_description: props.settings.faq_seo_description ?? '',
    faq_seo_keywords:    props.settings.faq_seo_keywords    ?? '',
    faq_page_badge:      props.settings.faq_page_badge      ?? '',
    faq_page_title:      props.settings.faq_page_title      ?? '',
    faq_page_desc:       props.settings.faq_page_desc       ?? '',
    faq_hero_image:      null,
    faq_seo_og_image:    null,
    faq_page_image:      null,
});

const { onMetaTitleInput, onMetaDescInput, onMetaKeywordsInput } = useSeoAutoFill(form, {
    titleSource: () => form.faq_page_title,
    descSource:  () => form.faq_page_desc,
    titleKey:    'faq_seo_title',
    descKey:     'faq_seo_description',
    keywordsKey: 'faq_seo_keywords',
    titleSuffix: ' – Hotel Beach Way',
});

function onImg(type, file) {
    if (!file) return;
    if (type === 'hero') { form.faq_hero_image   = file; }
    if (type === 'og')   { form.faq_seo_og_image = file; }
    if (type === 'page') { form.faq_page_image   = file; }
}

function submit() {
    form.transform(data => ({ ...data, _method: 'PUT' }))
        .post(route('admin.website-settings.faq-page.update'), { forceFormData: true });
}
</script>

<style scoped>
.label { @apply block text-sm text-gray-600 mb-1; }
.input { @apply w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none; }
</style>
