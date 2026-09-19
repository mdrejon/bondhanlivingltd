<template>
    <AdminLayout>
        <Head title="Add Blog" />

        <div class="max-w-4xl mx-auto space-y-6">
            <div class="flex items-center gap-4">
                <Link
                    :href="route('admin.blogs.index')"
                    class="p-2 text-gray-500 hover:text-gray-700 bg-white rounded-full border shadow-sm transition-colors"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                </Link>
                <h2 class="text-2xl font-bold text-gray-800">Add New Blog</h2>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <form @submit.prevent="submit" class="p-6 space-y-6">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <InputLabel for="title" value="Title *" />
                            <TextInput
                                id="title"
                                v-model="form.title"
                                type="text"
                                class="mt-1 block w-full"
                                required
                            />
                            <InputError :message="form.errors.title" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="category" value="Category" />
                            <TextInput
                                id="category"
                                v-model="form.category"
                                type="text"
                                class="mt-1 block w-full"
                                placeholder="e.g. INDUSTRY"
                            />
                            <InputError :message="form.errors.category" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="author" value="Author" />
                            <TextInput
                                id="author"
                                v-model="form.author"
                                type="text"
                                class="mt-1 block w-full"
                                placeholder="e.g. Bondhon"
                            />
                            <InputError :message="form.errors.author" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="published_at" value="Publish Date" />
                            <TextInput
                                id="published_at"
                                v-model="form.published_at"
                                type="date"
                                class="mt-1 block w-full"
                            />
                            <InputError :message="form.errors.published_at" class="mt-2" />
                        </div>
                        
                        <div class="flex items-center mt-6">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" v-model="form.status" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                                <span class="ml-2 text-sm text-gray-600">Active Status</span>
                            </label>
                        </div>

                        <div class="md:col-span-2">
                            <InputLabel value="Blog Image" />
                            <DropZone
                                @change="file => form.image = file"
                                class="mt-1"
                            />
                            <InputError :message="form.errors.image" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <InputLabel for="content" value="Content" />
                            <textarea
                                id="content"
                                v-model="form.content"
                                rows="6"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            ></textarea>
                            <InputError :message="form.errors.content" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-gray-100">
                        <Link
                            :href="route('admin.blogs.index')"
                            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 mr-3"
                        >
                            Cancel
                        </Link>
                        <PrimaryButton :disabled="form.processing">
                            Create Blog
                        </PrimaryButton>
                    </div>
                </form>
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

const form = useForm({
    title: '',
    category: 'INDUSTRY',
    author: 'Bondhon',
    published_at: new Date().toISOString().split('T')[0],
    content: '',
    image: null,
    status: true,
});

const submit = () => {
    form.post(route('admin.blogs.store'));
};
</script>
