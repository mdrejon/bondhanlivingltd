<template>
    <AdminLayout>
        <div class="max-w-3xl space-y-6">
            <h1 class="text-lg font-semibold text-gray-800">Rooms Page Settings</h1>

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
                            <input v-model="form.rooms_hero_title" type="text" class="input" placeholder="Rooms & Suites" />
                        </div>
                    </div>
                </section>

                <!-- Section Content -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Rooms Section Content</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Badge Text</label>
                            <input v-model="form.rooms_badge" type="text" class="input" placeholder="OUR ROOMS & SUITES" />
                        </div>
                        <div>
                            <label class="label">Section Title</label>
                            <input v-model="form.rooms_title" type="text" class="input" placeholder="Book Your Stay And Relax In Luxury Resort" />
                            <p class="text-xs text-gray-400 mt-1">Use &lt;br&gt; for line breaks.</p>
                        </div>
                    </div>
                </section>

                <!-- SEO Configuration -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">SEO Configuration</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="label">Meta Title <span class="text-gray-400 font-normal">({{ form.rooms_seo_title.length }}/160)</span></label>
                            <input v-model="form.rooms_seo_title" @input="onMetaTitleInput" type="text" maxlength="160" class="input"
                                placeholder="Rooms & Suites – Hotel Beach Way" />
                        </div>
                        <div class="col-span-2">
                            <label class="label">Meta Description <span class="text-gray-400 font-normal">({{ form.rooms_seo_description.length }}/320)</span></label>
                            <textarea v-model="form.rooms_seo_description" @input="onMetaDescInput" rows="3" maxlength="320" class="input resize-none"
                                placeholder="Explore our elegantly furnished rooms and suites at Hotel Beach Way, Cox's Bazar."></textarea>
                        </div>
                        <div class="col-span-2">
                            <label class="label">Keywords</label>
                            <input v-model="form.rooms_seo_keywords" @input="onMetaKeywordsInput" type="text" class="input"
                                placeholder="hotel rooms cox's bazar, beach hotel suites, hotel beach way rooms" />
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

const currentHeroImg = ref(props.settings.rooms_hero_image  ?? null);
const currentOgImg   = ref(props.settings.rooms_seo_og_image ?? null);

const form = useForm({
    rooms_hero_title:      props.settings.rooms_hero_title      ?? '',
    rooms_seo_title:       props.settings.rooms_seo_title       ?? '',
    rooms_seo_description: props.settings.rooms_seo_description ?? '',
    rooms_seo_keywords:    props.settings.rooms_seo_keywords    ?? '',
    rooms_badge:           props.settings.rooms_badge           ?? '',
    rooms_title:           props.settings.rooms_title           ?? '',
    rooms_hero_image:      null,
    rooms_seo_og_image:    null,
});

const { onMetaTitleInput, onMetaDescInput, onMetaKeywordsInput } = useSeoAutoFill(form, {
    titleSource: () => form.rooms_hero_title,
    descSource:  () => form.rooms_seo_description,
    titleKey:    'rooms_seo_title',
    descKey:     'rooms_seo_description',
    keywordsKey: 'rooms_seo_keywords',
    titleSuffix: ' – Hotel Beach Way',
});

function onImg(type, file) {
    if (!file) return;
    if (type === 'hero') form.rooms_hero_image   = file;
    if (type === 'og')   form.rooms_seo_og_image = file;
}

function submit() {
    form.transform(data => ({ ...data, _method: 'PUT' }))
        .post(route('admin.website-settings.rooms-page.update'), { forceFormData: true });
}
</script>

<style scoped>
.label { @apply block text-sm text-gray-600 mb-1; }
.input { @apply w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none; }
</style>
