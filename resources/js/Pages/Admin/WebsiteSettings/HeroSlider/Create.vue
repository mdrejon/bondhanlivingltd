<template>
    <AdminLayout>
        <div class="max-w-3xl space-y-4">
            <div class="flex items-center justify-between">
                <h1 class="text-lg font-semibold text-gray-800">Add New Slide</h1>
                <a :href="route('admin.website-settings.sliders.index')" class="text-sm text-gray-500 hover:text-gray-700">← Back to Slides</a>
            </div>

            <form @submit.prevent="submit" class="bg-white rounded-lg shadow-sm p-6 space-y-5" enctype="multipart/form-data">

                <!-- Background Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Background Image</label>
                    <DropZone @change="onImageChange" hint="JPEG / PNG / WebP — max 5 MB" preview-class="w-full h-44 object-cover" />
                    <InputError :message="form.errors.background_image" />
                </div>

                <!-- Label -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slide Label <span class="text-gray-400 font-normal">(e.g. "Welcome To Hotel Beach Way")</span></label>
                    <input v-model="form.label" type="text" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none" />
                    <InputError :message="form.errors.label" />
                </div>

                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                    <input v-model="form.title" type="text" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none" placeholder="Modern & Luxurious Stay Near..." />
                    <InputError :message="form.errors.title" />
                </div>

                <!-- Subtitle / accent words -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle / Accent Words <span class="text-gray-400 font-normal">(comma-separated highlighted phrases, e.g. "Kolatoli, Beach")</span></label>
                    <input v-model="form.subtitle" type="text" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none" placeholder="Kolatoli, Beach" />
                    <InputError :message="form.errors.subtitle" />
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea v-model="form.description" rows="3" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none" placeholder="A boutique hotel with world-class amenities..."></textarea>
                    <InputError :message="form.errors.description" />
                </div>

                <!-- Button -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Button Text <span class="text-red-500">*</span></label>
                        <input v-model="form.button_text" type="text" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none" placeholder="Explore Rooms" />
                        <InputError :message="form.errors.button_text" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Button URL <span class="text-red-500">*</span></label>
                        <input v-model="form.button_url" type="text" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none" placeholder="#rooms" />
                        <InputError :message="form.errors.button_url" />
                    </div>
                </div>

                <!-- Star label + rating + order + active -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Star Label</label>
                        <input v-model="form.star_label" type="text" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none" placeholder="3-Star Standard Hotel" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Star Rating <span class="text-gray-400 font-normal">(0–5)</span></label>
                        <input v-model.number="form.star_rating" type="number" min="0" max="5" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none" />
                        <InputError :message="form.errors.star_rating" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <input v-model.number="form.sort_order" type="number" min="0" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none" />
                    </div>
                    <div class="flex items-end pb-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input v-model="form.is_active" type="checkbox" class="w-4 h-4 text-blue-600 rounded" />
                            <span class="text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                    <a :href="route('admin.website-settings.sliders.index')" class="px-5 py-2 text-sm text-gray-600 border rounded hover:bg-gray-50">Cancel</a>
                    <button type="submit" :disabled="form.processing" class="px-5 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-60">
                        {{ form.processing ? 'Saving...' : 'Create Slide' }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import InputError  from '@/Components/InputError.vue';
import DropZone from '@/Components/Admin/Shared/DropZone.vue';

const form = useForm({
    label:            '',
    title:            '',
    subtitle:         '',
    description:      '',
    button_text:      'Explore Rooms',
    button_url:       '#',
    star_label:       '',
    star_rating:      5,
    sort_order:       0,
    is_active:        true,
    background_image: null,
});

function onImageChange(file) {
    if (!file) return;
    form.background_image = file;
}

function submit() {
    form.post(route('admin.website-settings.sliders.store'), {
        forceFormData: true,
    });
}
</script>
