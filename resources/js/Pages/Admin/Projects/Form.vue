<template>
    <AdminLayout>
        <div class="max-w-4xl space-y-4">
            <div class="flex items-center justify-between">
                <h1 class="text-lg font-semibold text-gray-800">{{ isEdit ? 'Edit Project' : 'Add New Project' }}</h1>
                <a :href="route('admin.projects.index')" class="text-sm text-gray-500 hover:text-gray-700">← Back to Projects</a>
            </div>

            <form @submit.prevent="submit" class="bg-white rounded-lg shadow-sm p-6 space-y-5" enctype="multipart/form-data">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Project Title <span class="text-red-500">*</span></label>
                        <input v-model="form.title" type="text" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none" placeholder="e.g. Contemporary Villa" />
                        <InputError :message="form.errors.title" />
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <input v-model="form.category" type="text" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none" placeholder="e.g. Constructions" />
                        <InputError :message="form.errors.category" />
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                        <select v-model="form.status" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none">
                            <option value="running">Running</option>
                            <option value="completed">Completed</option>
                            <option value="upcoming">Upcoming</option>
                        </select>
                        <InputError :message="form.errors.status" />
                    </div>

                    <!-- Client -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Client Name</label>
                        <input v-model="form.client" type="text" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none" placeholder="e.g. Bondhan Living" />
                        <InputError :message="form.errors.client" />
                    </div>

                    <!-- Location -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <input v-model="form.location" type="text" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none" placeholder="e.g. Dhaka, Bangladesh" />
                        <InputError :message="form.errors.location" />
                    </div>

                    <!-- Thumbnail Image -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Thumbnail Image</label>
                        <div v-if="isEdit && project.thumbnail" class="mb-3">
                            <p class="text-xs text-gray-500 mb-1">Current Image:</p>
                            <img :src="`/storage/${project.thumbnail}`" class="h-32 rounded border" alt="thumbnail" />
                        </div>
                        <DropZone @change="onImageChange" hint="JPEG / PNG / WebP — max 5 MB" preview-class="w-full h-44 object-cover" />
                        <InputError :message="form.errors.thumbnail" />
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea v-model="form.description" rows="5" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none" placeholder="Project details..."></textarea>
                        <InputError :message="form.errors.description" />
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <a :href="route('admin.projects.index')" class="px-5 py-2 text-sm text-gray-600 border rounded hover:bg-gray-50">Cancel</a>
                    <button type="submit" :disabled="form.processing" class="px-5 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-60">
                        {{ form.processing ? 'Saving...' : (isEdit ? 'Update Project' : 'Create Project') }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import InputError  from '@/Components/InputError.vue';
import DropZone from '@/Components/Admin/Shared/DropZone.vue';

const props = defineProps({
    project: { type: Object, default: null },
});

const isEdit = computed(() => !!props.project);

const form = useForm({
    title:       props.project?.title || '',
    category:    props.project?.category || '',
    status:      props.project?.status || 'running',
    client:      props.project?.client || '',
    location:    props.project?.location || '',
    description: props.project?.description || '',
    thumbnail:   null,
});

function onImageChange(file) {
    form.thumbnail = file;
}

function submit() {
    if (isEdit.value) {
        // Inertia doesn't support PUT/PATCH with FormData containing files directly via put()
        // We need to use POST and spoof the method
        form.transform((data) => ({
            ...data,
            _method: 'put',
        })).post(route('admin.projects.update', props.project.id), {
            forceFormData: true,
        });
    } else {
        form.post(route('admin.projects.store'), {
            forceFormData: true,
        });
    }
}
</script>
