<template>
    <Head title="Edit Service" />

    <AdminLayout>
        <div class="flex justify-between items-center bg-white p-6 shadow-sm sm:rounded-lg mb-6 max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Service</h2>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit" class="space-y-6">
                            
                            <!-- Basic Info -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <InputLabel for="title" value="Title" />
                                    <TextInput id="title" v-model="form.title" type="text" class="mt-1 block w-full" required />
                                    <InputError class="mt-2" :message="form.errors.title" />
                                </div>
                                
                                <div>
                                    <InputLabel for="category" value="Category" />
                                    <select id="category" v-model="form.category" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">Select a category</option>
                                        <option value="Commercial">Commercial</option>
                                        <option value="Residential">Residential</option>
                                        <option value="Industrial">Industrial</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.category" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="short_description" value="Short Description (for listing cards)" />
                                <textarea id="short_description" v-model="form.short_description" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                                <InputError class="mt-2" :message="form.errors.short_description" />
                            </div>

                            <div>
                                <InputLabel for="description" value="Description (for details page)" />
                                <textarea id="description" v-model="form.description" rows="6" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <InputLabel for="icon" value="Icon (SVG/Image for card)" />
                                    <DropZone
                                        id="icon"
                                        @change="file => form.icon = file"
                                        class="mt-1 block w-full"
                                        :existingPreview="service.icon ? `/storage/${service.icon}` : null"
                                    />
                                    <InputError class="mt-2" :message="form.errors.icon" />
                                </div>

                                <div>
                                    <InputLabel for="image" value="Main Image (for details page)" />
                                    <DropZone
                                        id="image"
                                        @change="file => form.image = file"
                                        class="mt-1 block w-full"
                                        :existingPreview="service.image ? `/storage/${service.image}` : null"
                                    />
                                    <InputError class="mt-2" :message="form.errors.image" />
                                </div>
                            </div>
                            
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" v-model="form.status" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                                    <span class="ml-2 text-sm text-gray-600">Active</span>
                                </label>
                            </div>

                            <!-- SEO -->
                            <div class="border-t pt-4 mt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Settings</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <InputLabel for="seo_title" value="SEO Title" />
                                        <TextInput id="seo_title" v-model="form.seo_title" type="text" class="mt-1 block w-full" />
                                        <InputError class="mt-2" :message="form.errors.seo_title" />
                                    </div>
                                    <div>
                                        <InputLabel for="seo_keywords" value="SEO Keywords" />
                                        <TextInput id="seo_keywords" v-model="form.seo_keywords" type="text" class="mt-1 block w-full" />
                                        <InputError class="mt-2" :message="form.errors.seo_keywords" />
                                    </div>
                                    <div class="col-span-2">
                                        <InputLabel for="seo_description" value="SEO Description" />
                                        <textarea id="seo_description" v-model="form.seo_description" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                                        <InputError class="mt-2" :message="form.errors.seo_description" />
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <PrimaryButton :disabled="form.processing">Update Service</PrimaryButton>
                                <Link :href="route('admin.website-settings.services.index')" class="text-gray-600 hover:text-gray-900">Cancel</Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DropZone from '@/Components/Admin/Shared/DropZone.vue';

const props = defineProps({
    service: Object,
});

const form = useForm({
    title: props.service.title || '',
    category: props.service.category || '',
    short_description: props.service.short_description || '',
    description: props.service.description || '',
    icon: null,
    image: null,
    seo_title: props.service.seo_title || '',
    seo_description: props.service.seo_description || '',
    seo_keywords: props.service.seo_keywords || '',
    status: !!props.service.status,
    _method: 'PUT', // Needed for file uploads via PUT
});

const submit = () => {
    form.post(route('admin.website-settings.services.update', props.service.id), {
        onSuccess: () => {
            form.icon = null;
            form.image = null;
        }
    });
};
</script>
