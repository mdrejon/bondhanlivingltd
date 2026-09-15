<template>
    <AdminLayout>
        <div class="max-w-3xl space-y-4">

            <!-- Page header -->
            <div class="flex items-center justify-between">
                <h1 class="text-lg font-semibold text-gray-800">Services Page Settings</h1>
                <span class="text-xs text-gray-400">Breadcrumb, SEO &amp; homepage section settings</span>
            </div>

            <!-- Flash success -->
            <div v-if="$page.props.flash?.success"
                class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ $page.props.flash.success }}
            </div>

            <form @submit.prevent="submit" class="space-y-4">

                <!-- ─── Page Hero / Breadcrumb ─── -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b border-gray-100 pb-2">Page Hero &amp; Breadcrumb</h2>

                    <div>
                        <label class="label">Page Title <span class="text-xs text-gray-400">(shown in breadcrumb hero area)</span></label>
                        <input v-model="form.svc_page_hero_title" type="text" class="input" placeholder="Our Services" />
                        <InputError :message="form.errors.svc_page_hero_title" />
                    </div>

                    <div>
                        <label class="label">Hero Background Image</label>
                        <DropZone @change="file => onImage(file, 'hero')" hint="Recommended: 1920×600px. JPEG / PNG / WebP" preview-class="w-full h-44 object-cover"
                            :existing-preview="currentHeroImage ? '/storage/' + currentHeroImage : null" />
                        <InputError :message="form.errors.svc_page_hero_image" />
                    </div>
                </section>

                <!-- ─── SEO Configuration ─── -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b border-gray-100 pb-2">SEO Configuration</h2>

                    <div>
                        <label class="label">Meta Title <span class="text-xs text-gray-400">(max 160 chars — shown in browser tab &amp; search results)</span></label>
                        <input v-model="form.svc_seo_title" @input="onMetaTitleInput" type="text" class="input"
                            placeholder="Services – Hotel Beach Way, Cox's Bazar" maxlength="160" />
                        <p class="text-xs text-gray-400 mt-1">{{ (form.svc_seo_title || '').length }}/160</p>
                        <InputError :message="form.errors.svc_seo_title" />
                    </div>

                    <div>
                        <label class="label">Meta Description <span class="text-xs text-gray-400">(max 320 chars)</span></label>
                        <textarea v-model="form.svc_seo_description" @input="onMetaDescInput" rows="3" class="input resize-none"
                            placeholder="Discover world-class services at Hotel Beach Way — gym, spa, pool, restaurant and more." maxlength="320"></textarea>
                        <p class="text-xs text-gray-400 mt-1">{{ (form.svc_seo_description || '').length }}/320</p>
                        <InputError :message="form.errors.svc_seo_description" />
                    </div>

                    <div>
                        <label class="label">Meta Keywords <span class="text-xs text-gray-400">(comma-separated)</span></label>
                        <input v-model="form.svc_seo_keywords" @input="onMetaKeywordsInput" type="text" class="input"
                            placeholder="hotel services, spa, gym, swimming pool, cox's bazar hotel" />
                        <InputError :message="form.errors.svc_seo_keywords" />
                    </div>

                    <div>
                        <label class="label">OG / Social Share Image <span class="text-xs text-gray-400">(recommended 1200×630px)</span></label>
                        <DropZone @change="file => onImage(file, 'og')" hint="JPEG / PNG / WebP — max 5 MB" preview-class="w-full h-36 object-cover"
                            :existing-preview="currentOgImage ? '/storage/' + currentOgImage : null" />
                        <InputError :message="form.errors.svc_seo_og_image" />
                    </div>
                </section>

                <!-- ─── Homepage Section Header ─── -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b border-gray-100 pb-2">Homepage Section Header</h2>

                    <div>
                        <label class="label">Section Badge</label>
                        <input v-model="form.svc_badge" type="text" class="input" placeholder="OUR SERVICES" />
                        <p class="mt-1 text-xs text-gray-400">Small label shown above the title (e.g. "OUR SERVICES")</p>
                        <InputError :message="form.errors.svc_badge" />
                    </div>

                    <div>
                        <label class="label">
                            Title
                            <span class="text-xs font-normal text-gray-400 ml-1">— HTML allowed, use &lt;br&gt; for line breaks</span>
                        </label>
                        <textarea
                            v-model="form.svc_title"
                            rows="3"
                            class="input font-mono text-sm"
                            placeholder="We Provide Services&lt;br&gt;For You"
                        ></textarea>
                        <InputError :message="form.errors.svc_title" />
                    </div>
                </section>

                <!-- ─── Description ─── -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b border-gray-100 pb-2">Description</h2>

                    <div>
                        <label class="label">Body Text</label>
                        <textarea
                            v-model="form.svc_desc"
                            rows="5"
                            class="input"
                            placeholder="Located in the heart of Cox's Bazar, minutes from the beach — offering 208 guestrooms with modern amenities..."
                        ></textarea>
                        <InputError :message="form.errors.svc_desc" />
                    </div>
                </section>

                <!-- ─── CTA Button ─── -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b border-gray-100 pb-2">CTA Button</h2>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Button Text</label>
                            <input v-model="form.svc_btn_text" type="text" class="input" placeholder="View All Services" />
                            <InputError :message="form.errors.svc_btn_text" />
                        </div>
                        <div>
                            <label class="label">Button URL</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 text-xs text-gray-500 bg-gray-50 border border-r-0 border-gray-300 rounded-l-md">
                                    URL
                                </span>
                                <input
                                    v-model="form.svc_btn_url"
                                    type="text"
                                    class="input rounded-l-none"
                                    placeholder="/services"
                                />
                            </div>
                            <InputError :message="form.errors.svc_btn_url" />
                        </div>
                    </div>
                </section>

                <!-- ─── Detail Page Sidebar ─── -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b border-gray-100 pb-2">Detail Page Sidebar — "Needs Any Help?" Box</h2>

                    <div>
                        <label class="label">Box Title</label>
                        <input v-model="form.svc_help_title" type="text" class="input" placeholder="Needs Any Help?" />
                        <InputError :message="form.errors.svc_help_title" />
                    </div>

                    <div>
                        <label class="label">Description Text</label>
                        <textarea
                            v-model="form.svc_help_desc"
                            rows="3"
                            class="input resize-none"
                            placeholder="Our client care team is available 24/7 to answer your questions and assist with any service inquiry or reservation."
                        ></textarea>
                        <InputError :message="form.errors.svc_help_desc" />
                    </div>
                </section>

                <!-- ─── Actions ─── -->
                <div class="flex justify-end">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-60 transition-colors"
                    >
                        <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                        </svg>
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
import InputError  from '@/Components/InputError.vue';
import DropZone from '@/Components/Admin/Shared/DropZone.vue';
import { useSeoAutoFill } from '@/Composables/useSeoAutoFill';

const props = defineProps({
    settings: { type: Object, required: true },
});

const currentHeroImage = ref(props.settings.svc_page_hero_image ?? null);
const currentOgImage   = ref(props.settings.svc_seo_og_image ?? null);

const form = useForm({
    svc_page_hero_image:  null,
    svc_page_hero_title:  props.settings.svc_page_hero_title  ?? '',
    svc_seo_title:        props.settings.svc_seo_title        ?? '',
    svc_seo_description:  props.settings.svc_seo_description  ?? '',
    svc_seo_keywords:     props.settings.svc_seo_keywords     ?? '',
    svc_seo_og_image:     null,
    svc_badge:            props.settings.svc_badge            ?? '',
    svc_title:            props.settings.svc_title            ?? '',
    svc_desc:             props.settings.svc_desc             ?? '',
    svc_btn_text:         props.settings.svc_btn_text         ?? '',
    svc_btn_url:          props.settings.svc_btn_url          ?? '',
    svc_help_title:       props.settings.svc_help_title       ?? '',
    svc_help_desc:        props.settings.svc_help_desc        ?? '',
});

const { onMetaTitleInput, onMetaDescInput, onMetaKeywordsInput } = useSeoAutoFill(form, {
    titleSource: () => form.svc_title,
    descSource:  () => form.svc_desc,
    titleKey:    'svc_seo_title',
    descKey:     'svc_seo_description',
    keywordsKey: 'svc_seo_keywords',
});

function onImage(file, type) {
    if (!file) return;
    if (type === 'hero') {
        form.svc_page_hero_image = file;
    } else {
        form.svc_seo_og_image = file;
    }
}

function submit() {
    form.post(route('admin.website-settings.services.update'), {
        forceFormData: true,
    });
}
</script>

<style scoped>
.label { @apply block text-sm text-gray-600 mb-1; }
.input { @apply w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none; }
</style>
