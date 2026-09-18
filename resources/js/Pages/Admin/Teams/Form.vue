<template>
    <AdminLayout>
        <div class="max-w-4xl space-y-4">
            <div class="flex items-center justify-between">
                <h1 class="text-lg font-semibold text-gray-800">{{ isEdit ? 'Edit Team Member' : 'Add New Team Member' }}</h1>
                <a :href="route('admin.teams.index')" class="text-sm text-gray-500 hover:text-gray-700">← Back to Teams</a>
            </div>

            <form @submit.prevent="submit" class="bg-white rounded-lg shadow-sm p-6 space-y-5" enctype="multipart/form-data">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                        <input v-model="form.name" type="text" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none" placeholder="e.g. John Doe" />
                        <InputError :message="form.errors.name" />
                    </div>

                    <!-- Designation -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Designation <span class="text-red-500">*</span></label>
                        <input v-model="form.designation" type="text" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none" placeholder="e.g. Founder" />
                        <InputError :message="form.errors.designation" />
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select v-model="form.status" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none">
                            <option :value="true">Active</option>
                            <option :value="false">Inactive</option>
                        </select>
                        <InputError :message="form.errors.status" />
                    </div>

                    <!-- Order -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <input v-model="form.order" type="number" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none" placeholder="0" />
                        <InputError :message="form.errors.order" />
                    </div>

                    <!-- Facebook -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Facebook URL</label>
                        <input v-model="form.facebook" type="url" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none" placeholder="https://facebook.com/..." />
                        <InputError :message="form.errors.facebook" />
                    </div>

                    <!-- Twitter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Twitter URL</label>
                        <input v-model="form.twitter" type="url" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none" placeholder="https://twitter.com/..." />
                        <InputError :message="form.errors.twitter" />
                    </div>

                    <!-- LinkedIn -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">LinkedIn URL</label>
                        <input v-model="form.linkedin" type="url" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none" placeholder="https://linkedin.com/in/..." />
                        <InputError :message="form.errors.linkedin" />
                    </div>
                    
                    <!-- Instagram -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Instagram URL</label>
                        <input v-model="form.instagram" type="url" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none" placeholder="https://instagram.com/..." />
                        <InputError :message="form.errors.instagram" />
                    </div>

                    <!-- Image -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Team Member Image</label>
                        <div v-if="isEdit && team.image" class="mb-3">
                            <p class="text-xs text-gray-500 mb-1">Current Image:</p>
                            <img :src="team.image.startsWith('http') || team.image.startsWith('assets') ? (team.image.startsWith('assets') ? `/${team.image}` : team.image) : `/storage/${team.image}`" class="h-32 rounded border" alt="image" />
                        </div>
                        <DropZone @change="onImageChange" hint="JPEG / PNG / WebP — max 2 MB" preview-class="w-full h-44 object-cover" />
                        <InputError :message="form.errors.image" />
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <a :href="route('admin.teams.index')" class="px-5 py-2 text-sm text-gray-600 border rounded hover:bg-gray-50">Cancel</a>
                    <button type="submit" :disabled="form.processing" class="px-5 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-60">
                        {{ form.processing ? 'Saving...' : (isEdit ? 'Update Member' : 'Add Member') }}
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
    team: { type: Object, default: null },
});

const isEdit = computed(() => !!props.team);

const form = useForm({
    name:        props.team?.name || '',
    designation: props.team?.designation || '',
    facebook:    props.team?.facebook || '',
    twitter:     props.team?.twitter || '',
    linkedin:    props.team?.linkedin || '',
    instagram:   props.team?.instagram || '',
    status:      props.team !== null ? !!props.team.status : true,
    order:       props.team?.order || 0,
    image:       null,
});

function onImageChange(file) {
    form.image = file;
}

function submit() {
    if (isEdit.value) {
        form.transform((data) => ({
            ...data,
            _method: 'put',
        })).post(route('admin.teams.update', props.team.id), {
            forceFormData: true,
        });
    } else {
        form.post(route('admin.teams.store'), {
            forceFormData: true,
        });
    }
}
</script>
