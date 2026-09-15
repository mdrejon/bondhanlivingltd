<template>
    <AdminLayout>
        <div class="max-w-4xl space-y-4">
            <div class="flex items-center gap-3">
                <a :href="route('admin.services.index')" class="text-gray-400 hover:text-gray-600 text-sm">← Back</a>
                <h1 class="text-lg font-semibold text-gray-800">Add New Service</h1>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <ServiceForm :form="form" @image-change="onImageChange" @gallery-change="onGalleryChange" />

                <div class="flex justify-end">
                    <button type="submit" :disabled="form.processing"
                        class="px-6 py-2.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-60">
                        {{ form.processing ? 'Creating...' : 'Create Service' }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AdminLayout   from '@/Layouts/Admin/AdminLayout.vue';
import ServiceForm   from './ServiceForm.vue';

const form = useForm({
    title:          '',
    icon_svg:       '',
    image:          null,
    short_desc:     '',
    description:    '',
    benefits_title: 'We Give The Best Services',
    benefits_text:  '',
    gallery_image_1: null,
    gallery_image_2: null,
    features:       [],
    faqs:           [],
    btn_text:       'View Services',
    btn_url:        '#',
    is_featured:    false,
    sort_order:     0,
    is_active:      true,
});

function onImageChange({ field, file }) {
    form[field] = file;
}

function onGalleryChange({ field, file }) {
    form[field] = file;
}

function submit() {
    form.post(route('admin.services.store'), { forceFormData: true });
}
</script>
