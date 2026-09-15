<template>
    <AdminLayout>
        <div class="max-w-4xl space-y-4">
            <div class="flex items-center gap-3">
                <a :href="route('admin.services.index')" class="text-gray-400 hover:text-gray-600 text-sm">← Back</a>
                <h1 class="text-lg font-semibold text-gray-800">Edit Service — {{ service.title }}</h1>
            </div>

            <div v-if="$page.props.flash?.success"
                class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded">
                {{ $page.props.flash.success }}
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <ServiceForm :form="form" :existing="service" @image-change="onImageChange" />

                <div class="flex justify-end">
                    <button type="submit" :disabled="form.processing"
                        class="px-6 py-2.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-60">
                        {{ form.processing ? 'Saving...' : 'Save Changes' }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import ServiceForm from './ServiceForm.vue';

const props = defineProps({
    service: { type: Object, required: true },
});

const s = props.service;

const form = useForm({
    title:           s.title          ?? '',
    icon_svg:        s.icon_svg       ?? '',
    image:           null,
    short_desc:      s.short_desc     ?? '',
    description:     s.description    ?? '',
    benefits_title:  s.benefits_title ?? 'We Give The Best Services',
    benefits_text:   s.benefits_text  ?? '',
    gallery_image_1: null,
    gallery_image_2: null,
    features:        Array.isArray(s.features) ? [...s.features] : [],
    faqs:            Array.isArray(s.faqs)     ? s.faqs.map(f => ({ ...f })) : [],
    btn_text:        s.btn_text       ?? 'View Services',
    btn_url:         s.btn_url        ?? '#',
    is_featured:     s.is_featured    ?? false,
    sort_order:      s.sort_order     ?? 0,
    is_active:       s.is_active      ?? true,
});

function onImageChange({ field, file }) {
    form[field] = file;
}

function submit() {
    form.transform(data => ({ ...data, _method: 'PUT' }))
        .post(route('admin.services.update', s.id), {
            forceFormData: true,
        });
}
</script>
