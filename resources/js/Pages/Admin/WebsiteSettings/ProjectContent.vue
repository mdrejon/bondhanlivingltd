<template>
    <Head title="Project Page Content" />

    <AdminLayout>
        <div class="flex justify-between items-center bg-white p-6 shadow-sm sm:rounded-lg mb-6 max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Project Page Content</h2>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit" class="space-y-8">

                            <!-- Hero Section -->
                            <div class="border p-4 rounded bg-gray-50">
                                <h3 class="text-lg font-bold mb-4">Hero Section (Breadcrumb)</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="col-span-2">
                                        <InputLabel for="hero_title" value="Hero Title" />
                                        <TextInput
                                            id="hero_title"
                                            v-model="form.project_page_hero.title"
                                            type="text"
                                            class="mt-1 block w-full"
                                            placeholder="E.g. Our Projects"
                                        />
                                    </div>

                                    <div class="col-span-2">
                                        <InputLabel for="hero_bg_image" value="Background Image" />
                                        <DropZone
                                            @change="file => form.project_page_hero.bg_image_file = file"
                                            :existingPreview="form.project_page_hero.bg_image ? `/storage/${form.project_page_hero.bg_image}` : null"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- SEO Settings -->
                            <div class="border p-4 rounded bg-gray-50">
                                <h3 class="text-lg font-bold mb-4">SEO Settings</h3>
                                
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <InputLabel for="meta_title" value="Meta Title" />
                                        <TextInput
                                            id="meta_title"
                                            v-model="form.project_page_seo.meta_title"
                                            type="text"
                                            class="mt-1 block w-full"
                                        />
                                    </div>

                                    <div>
                                        <InputLabel for="meta_keywords" value="Meta Keywords" />
                                        <TextInput
                                            id="meta_keywords"
                                            v-model="form.project_page_seo.meta_keywords"
                                            type="text"
                                            class="mt-1 block w-full"
                                            placeholder="Comma separated"
                                        />
                                    </div>
                                    
                                    <div>
                                        <InputLabel for="meta_author" value="Meta Author" />
                                        <TextInput
                                            id="meta_author"
                                            v-model="form.project_page_seo.meta_author"
                                            type="text"
                                            class="mt-1 block w-full"
                                        />
                                    </div>

                                    <div>
                                        <InputLabel for="meta_description" value="Meta Description" />
                                        <textarea
                                            id="meta_description"
                                            v-model="form.project_page_seo.meta_description"
                                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                            rows="3"
                                        ></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <PrimaryButton :disabled="form.processing">Save Changes</PrimaryButton>
                                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                                    <p v-if="form.recentlySuccessful" class="text-sm text-green-600">Saved.</p>
                                </Transition>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DropZone from '@/Components/Admin/Shared/DropZone.vue';

const props = defineProps({
    content: Object,
});

const defaultContent = props.content || {};

const form = useForm({
    project_page_hero: {
        title: defaultContent.project_page_hero?.title || '',
        bg_image: defaultContent.project_page_hero?.bg_image || '',
        bg_image_file: null,
    },
    project_page_seo: {
        meta_title: defaultContent.project_page_seo?.meta_title || '',
        meta_description: defaultContent.project_page_seo?.meta_description || '',
        meta_keywords: defaultContent.project_page_seo?.meta_keywords || '',
        meta_author: defaultContent.project_page_seo?.meta_author || '',
    }
});

const submit = () => {
    form.post(route('admin.website-settings.project-content.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.project_page_hero.bg_image_file = null;
        }
    });
};
</script>
