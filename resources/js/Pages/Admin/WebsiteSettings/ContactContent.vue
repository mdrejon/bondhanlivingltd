<template>
    <AdminLayout>
        <Head title="Contact Page Content" />

        <div class="max-w-6xl mx-auto space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-800">Contact Page Sections</h2>
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
                    <div v-if="activeTab === 'contact_page_hero'">
                        <form @submit.prevent="submit('contact_page_hero')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">Hero / Breadcrumb</h3>
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <InputLabel for="hero_title" value="Page Title" />
                                        <TextInput id="hero_title" v-model="forms.contact_page_hero.contact_page_hero.title" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="hero_bg" value="Background Image" />
                                        <DropZone
                                            @change="file => forms.contact_page_hero.contact_page_hero.bg_image_file = file"
                                            :existingPreview="getImageUrl(content.contact_page_hero?.bg_image)"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.contact_page_hero.processing">Save Section</PrimaryButton>
                            </div>
                        </form>
                    </div>

                    <!-- 2. Contact Info -->
                    <div v-if="activeTab === 'contact_page_info'">
                        <form @submit.prevent="submit('contact_page_info')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">Contact Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <InputLabel for="info_title" value="Section Title" />
                                        <TextInput id="info_title" v-model="forms.contact_page_info.contact_page_info.title" class="block w-full mt-1" />
                                    </div>
                                    
                                    <div class="md:col-span-2 mt-4">
                                        <h4 class="text-md font-semibold text-gray-700 border-b pb-1 mb-4">Corporate Office</h4>
                                    </div>
                                    <div>
                                        <InputLabel for="office_label" value="Office Label" />
                                        <TextInput id="office_label" v-model="forms.contact_page_info.contact_page_info.office_label" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="office_map_url" value="Google Maps Link URL" />
                                        <TextInput id="office_map_url" v-model="forms.contact_page_info.contact_page_info.office_map_url" class="block w-full mt-1" />
                                    </div>
                                    <div class="md:col-span-2">
                                        <InputLabel for="office_address" value="Address Text" />
                                        <textarea id="office_address" v-model="forms.contact_page_info.contact_page_info.office_address" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full mt-1" rows="2"></textarea>
                                    </div>

                                    <div class="md:col-span-2 mt-4">
                                        <h4 class="text-md font-semibold text-gray-700 border-b pb-1 mb-4">Phone & Email</h4>
                                    </div>
                                    <div>
                                        <InputLabel for="phone_label" value="Label" />
                                        <TextInput id="phone_label" v-model="forms.contact_page_info.contact_page_info.phone_label" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="email_address" value="Email Address" />
                                        <TextInput id="email_address" v-model="forms.contact_page_info.contact_page_info.email" type="email" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="phone_1" value="Phone Number 1" />
                                        <TextInput id="phone_1" v-model="forms.contact_page_info.contact_page_info.phone_1" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="phone_2" value="Phone Number 2 (Optional)" />
                                        <TextInput id="phone_2" v-model="forms.contact_page_info.contact_page_info.phone_2" class="block w-full mt-1" />
                                    </div>

                                    <div class="md:col-span-2 mt-4">
                                        <h4 class="text-md font-semibold text-gray-700 border-b pb-1 mb-4">Hours of Operation</h4>
                                    </div>
                                    <div class="md:col-span-2">
                                        <InputLabel for="hours_label" value="Label" />
                                        <TextInput id="hours_label" v-model="forms.contact_page_info.contact_page_info.hours_label" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="hours_1" value="Hours Line 1" />
                                        <TextInput id="hours_1" v-model="forms.contact_page_info.contact_page_info.hours_1" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="hours_2" value="Hours Line 2 (Optional)" />
                                        <TextInput id="hours_2" v-model="forms.contact_page_info.contact_page_info.hours_2" class="block w-full mt-1" />
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.contact_page_info.processing">Save Section</PrimaryButton>
                            </div>
                        </form>
                    </div>

                    <!-- 3. Map Embed Tab -->
                    <div v-if="activeTab === 'contact_page_map'">
                        <form @submit.prevent="submit('contact_page_map')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">Google Maps Embed</h3>
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <InputLabel for="map_embed_url" value="Map Embed URL (src attribute)" />
                                        <textarea id="map_embed_url" v-model="forms.contact_page_map.contact_page_map.embed_url" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full mt-1" rows="4"></textarea>
                                        <p class="text-xs text-slate-500 mt-2">Go to Google Maps -> Share -> Embed a map, and copy ONLY the URL inside the src="..." attribute.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.contact_page_map.processing">Save Section</PrimaryButton>
                            </div>
                        </form>
                    </div>

                    <!-- 4. SEO -->
                    <div v-if="activeTab === 'contact_page_seo'">
                        <form @submit.prevent="submit('contact_page_seo')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">SEO Configuration</h3>
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <InputLabel for="seo_title" value="Meta Title" />
                                        <TextInput id="seo_title" v-model="forms.contact_page_seo.contact_page_seo.meta_title" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="seo_desc" value="Meta Description" />
                                        <textarea id="seo_desc" v-model="forms.contact_page_seo.contact_page_seo.meta_description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full mt-1" rows="3"></textarea>
                                    </div>
                                    <div>
                                        <InputLabel for="seo_keywords" value="Meta Keywords" />
                                        <TextInput id="seo_keywords" v-model="forms.contact_page_seo.contact_page_seo.meta_keywords" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="seo_author" value="Meta Author" />
                                        <TextInput id="seo_author" v-model="forms.contact_page_seo.contact_page_seo.meta_author" class="block w-full mt-1" />
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.contact_page_seo.processing">Save Section</PrimaryButton>
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
    { key: 'contact_page_hero', label: 'Hero Section' },
    { key: 'contact_page_info', label: 'Contact Information' },
    { key: 'contact_page_map', label: 'Map Embed' },
    { key: 'contact_page_seo', label: 'SEO Configuration' }
];

const activeTab = ref('contact_page_hero');

const forms = {
    contact_page_hero: useForm({
        contact_page_hero: {
            title: props.content.contact_page_hero?.title || '',
            bg_image: props.content.contact_page_hero?.bg_image || '',
            bg_image_file: null
        }
    }),
    contact_page_info: useForm({
        contact_page_info: {
            title: props.content.contact_page_info?.title || '',
            office_label: props.content.contact_page_info?.office_label || '',
            office_address: props.content.contact_page_info?.office_address || '',
            office_map_url: props.content.contact_page_info?.office_map_url || '',
            phone_label: props.content.contact_page_info?.phone_label || '',
            phone_1: props.content.contact_page_info?.phone_1 || '',
            phone_2: props.content.contact_page_info?.phone_2 || '',
            email: props.content.contact_page_info?.email || '',
            hours_label: props.content.contact_page_info?.hours_label || '',
            hours_1: props.content.contact_page_info?.hours_1 || '',
            hours_2: props.content.contact_page_info?.hours_2 || ''
        }
    }),
    contact_page_map: useForm({
        contact_page_map: {
            embed_url: props.content.contact_page_map?.embed_url || ''
        }
    }),
    contact_page_seo: useForm({
        contact_page_seo: {
            meta_title: props.content.contact_page_seo?.meta_title || '',
            meta_description: props.content.contact_page_seo?.meta_description || '',
            meta_keywords: props.content.contact_page_seo?.meta_keywords || '',
            meta_author: props.content.contact_page_seo?.meta_author || ''
        }
    })
};

const submit = (formKey) => {
    forms[formKey].post(route('admin.website-settings.contact-content.update'), {
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
