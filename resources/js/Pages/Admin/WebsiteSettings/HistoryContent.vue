<template>
    <AdminLayout>
        <Head title="History Page Content" />

        <div class="max-w-6xl mx-auto space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-800">History Page Sections</h2>
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
                    <div v-if="activeTab === 'history_page_hero'">
                        <form @submit.prevent="submit('history_page_hero')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">Hero / Breadcrumb</h3>
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <InputLabel for="hero_title" value="Page Title" />
                                        <TextInput id="hero_title" v-model="forms.history_page_hero.history_page_hero.title" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="hero_bg" value="Background Image" />
                                        <DropZone
                                            @change="file => forms.history_page_hero.history_page_hero.bg_image_file = file"
                                            :existingPreview="getImageUrl(content.history_page_hero?.bg_image)"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.history_page_hero.processing">Save Section</PrimaryButton>
                            </div>
                        </form>
                    </div>

                    <!-- 2. Main Area -->
                    <div v-if="activeTab === 'history_page_main'">
                        <form @submit.prevent="submit('history_page_main')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">Main Content Area</h3>
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <InputLabel for="main_subtitle" value="Subtitle" />
                                        <TextInput id="main_subtitle" v-model="forms.history_page_main.history_page_main.subtitle" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="main_title" value="Title" />
                                        <TextInput id="main_title" v-model="forms.history_page_main.history_page_main.title" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="main_desc" value="Description" />
                                        <textarea id="main_desc" v-model="forms.history_page_main.history_page_main.description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full mt-1" rows="8"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.history_page_main.processing">Save Section</PrimaryButton>
                            </div>
                        </form>
                    </div>

                    <!-- 3. Timeline -->
                    <div v-if="activeTab === 'history_page_timeline'">
                        <form @submit.prevent="submit('history_page_timeline')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <div class="flex items-center justify-between mb-4 border-b pb-2">
                                    <h3 class="text-lg font-medium text-gray-800">Timeline Milestones</h3>
                                    <button type="button" @click="addTimelineItem" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">+ Add Milestone</button>
                                </div>
                                <div class="space-y-6">
                                    <div v-for="(item, index) in forms.history_page_timeline.history_page_timeline" :key="index" class="p-5 border border-gray-200 rounded-lg bg-gray-50 relative">
                                        <button type="button" @click="removeTimelineItem(index)" class="absolute top-3 right-3 text-red-500 hover:text-red-700 text-sm font-medium">Remove</button>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                                            <div>
                                                <InputLabel :for="'tl_year_'+index" value="Year" />
                                                <TextInput :id="'tl_year_'+index" v-model="item.year" class="block w-full mt-1" placeholder="e.g. 2010" />
                                            </div>
                                            <div>
                                                <InputLabel :for="'tl_title_'+index" value="Title" />
                                                <TextInput :id="'tl_title_'+index" v-model="item.title" class="block w-full mt-1" />
                                            </div>
                                            <div class="md:col-span-2">
                                                <InputLabel :for="'tl_desc_'+index" value="Description" />
                                                <textarea :id="'tl_desc_'+index" v-model="item.description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full mt-1" rows="3"></textarea>
                                            </div>
                                            <div class="md:col-span-2">
                                                <InputLabel value="Image" />
                                                <DropZone
                                                    @change="file => item.image_file = file"
                                                    :existingPreview="getImageUrl(item.image)"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="forms.history_page_timeline.history_page_timeline.length === 0" class="text-center py-8 text-gray-500">
                                        No timeline milestones added yet.
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.history_page_timeline.processing">Save Section</PrimaryButton>
                            </div>
                        </form>
                    </div>

                    <!-- 4. SEO -->
                    <div v-if="activeTab === 'history_page_seo'">
                        <form @submit.prevent="submit('history_page_seo')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">SEO Configuration</h3>
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <InputLabel for="seo_title" value="Meta Title" />
                                        <TextInput id="seo_title" v-model="forms.history_page_seo.history_page_seo.meta_title" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="seo_desc" value="Meta Description" />
                                        <textarea id="seo_desc" v-model="forms.history_page_seo.history_page_seo.meta_description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full mt-1" rows="3"></textarea>
                                    </div>
                                    <div>
                                        <InputLabel for="seo_keywords" value="Meta Keywords" />
                                        <TextInput id="seo_keywords" v-model="forms.history_page_seo.history_page_seo.meta_keywords" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="seo_author" value="Meta Author" />
                                        <TextInput id="seo_author" v-model="forms.history_page_seo.history_page_seo.meta_author" class="block w-full mt-1" />
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.history_page_seo.processing">Save Section</PrimaryButton>
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
    { key: 'history_page_hero', label: 'Hero Section' },
    { key: 'history_page_main', label: 'Main Content Area' },
    { key: 'history_page_timeline', label: 'Timeline Milestones' },
    { key: 'history_page_seo', label: 'SEO Configuration' }
];

const activeTab = ref('history_page_hero');

const forms = {
    history_page_hero: useForm({
        history_page_hero: {
            title: props.content.history_page_hero?.title || '',
            bg_image: props.content.history_page_hero?.bg_image || '',
            bg_image_file: null
        }
    }),
    history_page_main: useForm({
        history_page_main: {
            subtitle: props.content.history_page_main?.subtitle || '',
            title: props.content.history_page_main?.title || '',
            description: props.content.history_page_main?.description || ''
        }
    }),
    history_page_timeline: useForm({
        history_page_timeline: (props.content.history_page_timeline || []).map(item => ({ ...item, image_file: null }))
    }),
    history_page_seo: useForm({
        history_page_seo: {
            meta_title: props.content.history_page_seo?.meta_title || '',
            meta_description: props.content.history_page_seo?.meta_description || '',
            meta_keywords: props.content.history_page_seo?.meta_keywords || '',
            meta_author: props.content.history_page_seo?.meta_author || ''
        }
    })
};

const addTimelineItem = () => {
    forms.history_page_timeline.history_page_timeline.push({
        year: '',
        title: '',
        description: '',
        image: '',
        image_file: null
    });
};

const removeTimelineItem = (index) => {
    forms.history_page_timeline.history_page_timeline.splice(index, 1);
};

const submit = (formKey) => {
    forms[formKey].post(route('admin.website-settings.history-content.update'), {
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
