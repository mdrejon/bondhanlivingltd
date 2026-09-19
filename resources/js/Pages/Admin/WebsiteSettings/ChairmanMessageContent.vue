<template>
    <AdminLayout>
        <Head title="Chairman Message Content" />

        <div class="max-w-6xl mx-auto space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-800">Chairman Message Sections</h2>
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
                    <div v-if="activeTab === 'chairman_page_hero'">
                        <form @submit.prevent="submit('chairman_page_hero')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">Hero / Breadcrumb</h3>
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <InputLabel for="hero_title" value="Page Title" />
                                        <TextInput id="hero_title" v-model="forms.chairman_page_hero.chairman_page_hero.title" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="hero_bg" value="Background Image" />
                                        <DropZone
                                            @change="file => forms.chairman_page_hero.chairman_page_hero.bg_image_file = file"
                                            :existingPreview="getImageUrl(content.chairman_page_hero?.bg_image)"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.chairman_page_hero.processing">Save Section</PrimaryButton>
                            </div>
                        </form>
                    </div>

                    <!-- 2. Main Area -->
                    <div v-if="activeTab === 'chairman_page_main'">
                        <form @submit.prevent="submit('chairman_page_main')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">Main Content Area</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <InputLabel for="main_subtitle" value="Subtitle" />
                                        <TextInput id="main_subtitle" v-model="forms.chairman_page_main.chairman_page_main.subtitle" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="main_title" value="Title" />
                                        <TextInput id="main_title" v-model="forms.chairman_page_main.chairman_page_main.title" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="main_highlight" value="Title Highlight (Red text)" />
                                        <TextInput id="main_highlight" v-model="forms.chairman_page_main.chairman_page_main.highlight" class="block w-full mt-1" />
                                    </div>
                                    <div class="md:col-span-2">
                                        <InputLabel for="main_desc" value="Message Description" />
                                        <textarea id="main_desc" v-model="forms.chairman_page_main.chairman_page_main.description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full mt-1" rows="8"></textarea>
                                    </div>
                                    <div>
                                        <InputLabel value="Chairman Image" />
                                        <DropZone
                                            @change="file => forms.chairman_page_main.chairman_page_main.image_file = file"
                                            :existingPreview="getImageUrl(content.chairman_page_main?.image)"
                                        />
                                    </div>
                                    <div>
                                        <InputLabel value="Background Shape Image" />
                                        <DropZone
                                            @change="file => forms.chairman_page_main.chairman_page_main.bg_image_file = file"
                                            :existingPreview="getImageUrl(content.chairman_page_main?.bg_image)"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.chairman_page_main.processing">Save Section</PrimaryButton>
                            </div>
                        </form>
                    </div>

                    <!-- 3. Chairman Info -->
                    <div v-if="activeTab === 'chairman_page_info'">
                        <form @submit.prevent="submit('chairman_page_info')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">Chairman Info</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <InputLabel for="info_salutation" value="Salutation (e.g. Thanking you.)" />
                                        <TextInput id="info_salutation" v-model="forms.chairman_page_info.chairman_page_info.salutation" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="info_name" value="Name" />
                                        <TextInput id="info_name" v-model="forms.chairman_page_info.chairman_page_info.name" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="info_designation" value="Designation" />
                                        <TextInput id="info_designation" v-model="forms.chairman_page_info.chairman_page_info.designation" class="block w-full mt-1" />
                                    </div>
                                    <div class="md:col-span-2">
                                        <InputLabel for="info_company" value="Company" />
                                        <TextInput id="info_company" v-model="forms.chairman_page_info.chairman_page_info.company" class="block w-full mt-1" />
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.chairman_page_info.processing">Save Section</PrimaryButton>
                            </div>
                        </form>
                    </div>

                    <!-- 4. SEO -->
                    <div v-if="activeTab === 'chairman_page_seo'">
                        <form @submit.prevent="submit('chairman_page_seo')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">SEO Configuration</h3>
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <InputLabel for="seo_title" value="Meta Title" />
                                        <TextInput id="seo_title" v-model="forms.chairman_page_seo.chairman_page_seo.meta_title" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="seo_desc" value="Meta Description" />
                                        <textarea id="seo_desc" v-model="forms.chairman_page_seo.chairman_page_seo.meta_description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full mt-1" rows="3"></textarea>
                                    </div>
                                    <div>
                                        <InputLabel for="seo_keywords" value="Meta Keywords" />
                                        <TextInput id="seo_keywords" v-model="forms.chairman_page_seo.chairman_page_seo.meta_keywords" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="seo_author" value="Meta Author" />
                                        <TextInput id="seo_author" v-model="forms.chairman_page_seo.chairman_page_seo.meta_author" class="block w-full mt-1" />
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.chairman_page_seo.processing">Save Section</PrimaryButton>
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
    { key: 'chairman_page_hero', label: 'Hero Section' },
    { key: 'chairman_page_main', label: 'Main Content Area' },
    { key: 'chairman_page_info', label: 'Chairman Info' },
    { key: 'chairman_page_seo', label: 'SEO Configuration' }
];

const activeTab = ref('chairman_page_hero');

const forms = {
    chairman_page_hero: useForm({
        chairman_page_hero: {
            title: props.content.chairman_page_hero?.title || '',
            bg_image: props.content.chairman_page_hero?.bg_image || '',
            bg_image_file: null
        }
    }),
    chairman_page_main: useForm({
        chairman_page_main: {
            subtitle: props.content.chairman_page_main?.subtitle || '',
            title: props.content.chairman_page_main?.title || '',
            highlight: props.content.chairman_page_main?.highlight || '',
            description: props.content.chairman_page_main?.description || '',
            bg_image: props.content.chairman_page_main?.bg_image || '',
            image: props.content.chairman_page_main?.image || '',
            bg_image_file: null,
            image_file: null
        }
    }),
    chairman_page_info: useForm({
        chairman_page_info: {
            salutation: props.content.chairman_page_info?.salutation || '',
            name: props.content.chairman_page_info?.name || '',
            designation: props.content.chairman_page_info?.designation || '',
            company: props.content.chairman_page_info?.company || ''
        }
    }),
    chairman_page_seo: useForm({
        chairman_page_seo: {
            meta_title: props.content.chairman_page_seo?.meta_title || '',
            meta_description: props.content.chairman_page_seo?.meta_description || '',
            meta_keywords: props.content.chairman_page_seo?.meta_keywords || '',
            meta_author: props.content.chairman_page_seo?.meta_author || ''
        }
    })
};

const submit = (formKey) => {
    forms[formKey].post(route('admin.website-settings.chairman-content.update'), {
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
