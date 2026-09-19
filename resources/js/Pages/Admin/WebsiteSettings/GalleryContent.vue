<template>
    <AdminLayout>
        <Head title="Gallery Page Content" />

        <div class="max-w-6xl mx-auto space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-800">Gallery Page Sections</h2>
                <div v-if="$page.props.flash.success" class="px-4 py-2 bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-md text-sm font-medium">
                    {{ $page.props.flash.success }}
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-6">
                <!-- Sidebar Tabs -->
                <div class="w-full md:w-1/4">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <ul class="flex flex-col">
                            <li v-for="tab in tabs" :key="tab.key">
                                <button
                                    @click="activeTab = tab.key"
                                    :class="[
                                        'w-full text-left px-4 py-3 text-sm font-medium transition-colors',
                                        activeTab === tab.key 
                                            ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600' 
                                            : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-l-4 border-transparent'
                                    ]"
                                >
                                    {{ tab.label }}
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="w-full md:w-3/4">
                    
                    <!-- 1. Hero -->
                    <div v-if="activeTab === 'gallery_page_hero'">
                        <form @submit.prevent="submit('gallery_page_hero')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">Hero / Breadcrumb</h3>
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <InputLabel for="hero_title" value="Page Title" />
                                        <TextInput id="hero_title" v-model="forms.gallery_page_hero.gallery_page_hero.title" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="hero_bg" value="Background Image" />
                                        <DropZone
                                            @change="file => forms.gallery_page_hero.gallery_page_hero.bg_image_file = file"
                                            :existingPreview="getImageUrl(content.gallery_page_hero?.bg_image)"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.gallery_page_hero.processing">Save Section</PrimaryButton>
                            </div>
                        </form>
                    </div>

                    <!-- 2. Gallery Images -->
                    <div v-if="activeTab === 'gallery_page_images'">
                        <form @submit.prevent="submit('gallery_page_images')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <div class="flex justify-between items-center mb-4 border-b pb-2">
                                    <h3 class="text-lg font-medium text-gray-800">Gallery Images</h3>
                                    <button type="button" @click="addImageItem" class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded text-sm font-medium hover:bg-indigo-100 transition">
                                        + Add Image
                                    </button>
                                </div>
                                
                                <div v-if="forms.gallery_page_images.gallery_page_images.length === 0" class="text-center py-8 text-gray-500">
                                    No images added yet. Click "+ Add Image" to start.
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div v-for="(item, index) in forms.gallery_page_images.gallery_page_images" :key="index" class="p-4 border border-gray-200 rounded-lg bg-gray-50 relative">
                                        <button type="button" @click="removeImageItem(index)" class="absolute top-2 right-2 text-red-500 hover:text-red-700 bg-white rounded-full shadow p-1 z-10">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        
                                        <div>
                                            <InputLabel :for="'gallery_img_' + index" :value="'Image ' + (index + 1)" class="mb-2" />
                                            <DropZone
                                                @change="file => item.image_file = file"
                                                :existingPreview="getImageUrl(item.image)"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.gallery_page_images.processing">Save Section</PrimaryButton>
                            </div>
                        </form>
                    </div>

                    <!-- 3. SEO -->
                    <div v-if="activeTab === 'gallery_page_seo'">
                        <form @submit.prevent="submit('gallery_page_seo')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">SEO Configuration</h3>
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <InputLabel for="seo_title" value="Meta Title" />
                                        <TextInput id="seo_title" v-model="forms.gallery_page_seo.gallery_page_seo.meta_title" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="seo_desc" value="Meta Description" />
                                        <textarea id="seo_desc" v-model="forms.gallery_page_seo.gallery_page_seo.meta_description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full mt-1" rows="3"></textarea>
                                    </div>
                                    <div>
                                        <InputLabel for="seo_keywords" value="Meta Keywords" />
                                        <TextInput id="seo_keywords" v-model="forms.gallery_page_seo.gallery_page_seo.meta_keywords" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="seo_author" value="Meta Author" />
                                        <TextInput id="seo_author" v-model="forms.gallery_page_seo.gallery_page_seo.meta_author" class="block w-full mt-1" />
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.gallery_page_seo.processing">Save Section</PrimaryButton>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DropZone from '@/Components/Admin/Shared/DropZone.vue';

const props = defineProps({
    content: {
        type: Object,
        default: () => ({})
    }
});

const tabs = [
    { key: 'gallery_page_hero', label: 'Hero Section' },
    { key: 'gallery_page_images', label: 'Gallery Images' },
    { key: 'gallery_page_seo', label: 'SEO Configuration' }
];

const activeTab = ref('gallery_page_hero');

const forms = {
    gallery_page_hero: useForm({
        gallery_page_hero: {
            title: props.content.gallery_page_hero?.title || '',
            bg_image: props.content.gallery_page_hero?.bg_image || '',
            bg_image_file: null
        }
    }),
    gallery_page_images: useForm({
        gallery_page_images: Array.isArray(props.content.gallery_page_images) 
            ? props.content.gallery_page_images.map(img => ({ ...img, image_file: null })) 
            : []
    }),
    gallery_page_seo: useForm({
        gallery_page_seo: {
            meta_title: props.content.gallery_page_seo?.meta_title || '',
            meta_description: props.content.gallery_page_seo?.meta_description || '',
            meta_keywords: props.content.gallery_page_seo?.meta_keywords || '',
            meta_author: props.content.gallery_page_seo?.meta_author || ''
        }
    })
};

const addImageItem = () => {
    forms.gallery_page_images.gallery_page_images.push({
        image: '',
        image_file: null
    });
};

const removeImageItem = (index) => {
    forms.gallery_page_images.gallery_page_images.splice(index, 1);
};

const submit = (formKey) => {
    forms[formKey].post(route('admin.website-settings.gallery-content.update'), {
        preserveScroll: true,
        forceFormData: true
    });
};

const getImageUrl = (path) => {
    if (!path) return '';
    if (path.startsWith('assets/')) return '/' + path;
    if (path.startsWith('http')) return path;
    return '/storage/' + path;
};
</script>
