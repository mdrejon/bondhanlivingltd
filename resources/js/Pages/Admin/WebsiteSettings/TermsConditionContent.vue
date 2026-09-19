<template>
    <AdminLayout>
        <Head title="Terms & Conditions Page Content" />

        <div class="max-w-6xl mx-auto space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-800">Terms & Conditions Sections</h2>
                <!-- Flash Message -->
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
                    <div v-if="activeTab === 'terms_page_hero'">
                        <form @submit.prevent="submit('terms_page_hero')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">Hero / Breadcrumb</h3>
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <InputLabel for="hero_title" value="Page Title" />
                                        <TextInput id="hero_title" v-model="forms.terms_page_hero.terms_page_hero.title" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="hero_bg" value="Background Image" />
                                        <DropZone
                                            @change="file => forms.terms_page_hero.terms_page_hero.bg_image_file = file"
                                            :existingPreview="getImageUrl(content.terms_page_hero?.bg_image)"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.terms_page_hero.processing">Save Section</PrimaryButton>
                            </div>
                        </form>
                    </div>

                    <!-- 2. Terms Items -->
                    <div v-if="activeTab === 'terms_page_items'">
                        <form @submit.prevent="submit('terms_page_items')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <div class="flex justify-between items-center mb-4 border-b pb-2">
                                    <h3 class="text-lg font-medium text-gray-800">Terms and Conditions Rules</h3>
                                    <button type="button" @click="addTermItem" class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded text-sm font-medium hover:bg-indigo-100 transition">
                                        + Add Term Item
                                    </button>
                                </div>
                                
                                <div v-if="forms.terms_page_items.terms_page_items.length === 0" class="text-center py-8 text-gray-500">
                                    No terms added yet. Click "+ Add Term Item" to start.
                                </div>

                                <div v-for="(item, index) in forms.terms_page_items.terms_page_items" :key="index" class="p-4 mb-4 border border-gray-200 rounded-lg bg-gray-50 relative">
                                    <button type="button" @click="removeTermItem(index)" class="absolute top-4 right-4 text-red-500 hover:text-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                          <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    
                                    <div class="grid grid-cols-1 gap-4 pr-8">
                                        <div>
                                            <InputLabel :for="'term_title_' + index" value="Title" />
                                            <TextInput :id="'term_title_' + index" v-model="item.title" class="block w-full mt-1" placeholder="e.g. Application" />
                                        </div>
                                        <div>
                                            <InputLabel :for="'term_desc_' + index" value="Description" />
                                            <textarea :id="'term_desc_' + index" v-model="item.description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full mt-1" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.terms_page_items.processing">Save Section</PrimaryButton>
                            </div>
                        </form>
                    </div>

                    <!-- 3. SEO -->
                    <div v-if="activeTab === 'terms_page_seo'">
                        <form @submit.prevent="submit('terms_page_seo')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">SEO Configuration</h3>
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <InputLabel for="seo_title" value="Meta Title" />
                                        <TextInput id="seo_title" v-model="forms.terms_page_seo.terms_page_seo.meta_title" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="seo_desc" value="Meta Description" />
                                        <textarea id="seo_desc" v-model="forms.terms_page_seo.terms_page_seo.meta_description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full mt-1" rows="3"></textarea>
                                    </div>
                                    <div>
                                        <InputLabel for="seo_keywords" value="Meta Keywords" />
                                        <TextInput id="seo_keywords" v-model="forms.terms_page_seo.terms_page_seo.meta_keywords" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="seo_author" value="Meta Author" />
                                        <TextInput id="seo_author" v-model="forms.terms_page_seo.terms_page_seo.meta_author" class="block w-full mt-1" />
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.terms_page_seo.processing">Save Section</PrimaryButton>
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
    { key: 'terms_page_hero', label: 'Hero Section' },
    { key: 'terms_page_items', label: 'Terms and Conditions' },
    { key: 'terms_page_seo', label: 'SEO Configuration' }
];

const activeTab = ref('terms_page_hero');

const forms = {
    terms_page_hero: useForm({
        terms_page_hero: {
            title: props.content.terms_page_hero?.title || '',
            bg_image: props.content.terms_page_hero?.bg_image || '',
            bg_image_file: null
        }
    }),
    terms_page_items: useForm({
        terms_page_items: Array.isArray(props.content.terms_page_items) ? [...props.content.terms_page_items] : []
    }),
    terms_page_seo: useForm({
        terms_page_seo: {
            meta_title: props.content.terms_page_seo?.meta_title || '',
            meta_description: props.content.terms_page_seo?.meta_description || '',
            meta_keywords: props.content.terms_page_seo?.meta_keywords || '',
            meta_author: props.content.terms_page_seo?.meta_author || ''
        }
    })
};

const addTermItem = () => {
    forms.terms_page_items.terms_page_items.push({
        title: '',
        description: ''
    });
};

const removeTermItem = (index) => {
    forms.terms_page_items.terms_page_items.splice(index, 1);
};

const submit = (formKey) => {
    forms[formKey].post(route('admin.website-settings.terms-content.update'), {
        preserveScroll: true,
        forceFormData: true
    });
};

const getImageUrl = (path) => {
    if (!path) return '';
    if (path.startsWith('assets/')) return '/' + path;
    return '/storage/' + path;
};
</script>
