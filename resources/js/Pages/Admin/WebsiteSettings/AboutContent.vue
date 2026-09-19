<template>
    <AdminLayout>
        <Head title="About Page Content" />

        <div class="max-w-6xl mx-auto space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-800">About Page Sections</h2>
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
                    <div v-if="activeTab === 'about_page_hero'">
                        <form @submit.prevent="submit('about_page_hero')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">Hero / Breadcrumb</h3>
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <InputLabel for="hero_title" value="Page Title" />
                                        <TextInput id="hero_title" v-model="forms.about_page_hero.title" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="hero_bg" value="Background Image" />
                                        <DropZone
    @change="file => handleFileUpload(file, 'about_page_hero', 'bg_image')"
    :existingPreview="getImageUrl(content.about_page_hero.bg_image)"
/>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.about_page_hero.processing">Save Section</PrimaryButton>
                            </div>
                        </form>
                    </div>

                    <!-- 2. About Company -->
                    <div v-if="activeTab === 'about_page_company'">
                        <form @submit.prevent="submit('about_page_company')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">About Us Details</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <InputLabel for="ac_years" value="Years Experience" />
                                        <TextInput id="ac_years" v-model="forms.about_page_company.years_experience" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="ac_years_text" value="Years Text" />
                                        <TextInput id="ac_years_text" v-model="forms.about_page_company.years_text" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="ac_subtitle" value="Subtitle" />
                                        <TextInput id="ac_subtitle" v-model="forms.about_page_company.subtitle" class="block w-full mt-1" />
                                    </div>
                                    <div class="md:col-span-2">
                                        <InputLabel for="ac_title" value="Title" />
                                        <TextInput id="ac_title" v-model="forms.about_page_company.title" class="block w-full mt-1" />
                                    </div>
                                    <div class="md:col-span-2">
                                        <InputLabel for="ac_desc" value="Description" />
                                        <textarea id="ac_desc" v-model="forms.about_page_company.description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full mt-1" rows="3"></textarea>
                                    </div>
                                    <div>
                                        <InputLabel for="ac_img1" value="Image 1" />
                                        <DropZone
    @change="file => handleFileUpload(file, 'about_page_company', 'image1')"
    :existingPreview="getImageUrl(content.about_page_company.image1)"
/>
                                    </div>
                                    <div>
                                        <InputLabel for="ac_img2" value="Image 2" />
                                        <DropZone
    @change="file => handleFileUpload(file, 'about_page_company', 'image2')"
    :existingPreview="getImageUrl(content.about_page_company.image2)"
/>
                                    </div>
                                    <div>
                                        <InputLabel for="ac_shape" value="Shape Image" />
                                        <DropZone
    @change="file => handleFileUpload(file, 'about_page_company', 'shape_image')"
    :existingPreview="getImageUrl(content.about_page_company.shape_image)"
/>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <div class="flex items-center justify-between mb-4 border-b pb-2">
                                    <h3 class="text-lg font-medium text-gray-800">Features</h3>
                                    <button type="button" @click="addFeature('about_page_company')" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">+ Add Feature</button>
                                </div>
                                <div class="space-y-4">
                                    <div v-for="(feature, index) in forms.about_page_company.features" :key="index" class="p-4 border border-gray-100 rounded-lg bg-gray-50 relative">
                                        <button type="button" @click="removeFeature('about_page_company', index)" class="absolute top-2 right-2 text-red-500 hover:text-red-700">Remove</button>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                                            <div class="md:col-span-2">
                                                <InputLabel :for="'ac_f_title_'+index" value="Title" />
                                                <TextInput :id="'ac_f_title_'+index" v-model="feature.title" class="block w-full mt-1" />
                                            </div>
                                            <div class="md:col-span-2">
                                                <InputLabel :for="'ac_f_text_'+index" value="Text" />
                                                <TextInput :id="'ac_f_text_'+index" v-model="feature.text" class="block w-full mt-1" />
                                            </div>
                                            <div class="md:col-span-2">
                                                <InputLabel :for="'ac_f_icon_'+index" value="Icon" />
                                                <input type="file" accept="image/*" class="block w-full mt-1 file-input" @change="e => handleArrayFileUpload(e, 'about_page_company', 'features', index, 'icon_file')" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.about_page_company.processing">Save Section</PrimaryButton>
                            </div>
                        </form>
                    </div>

                    <!-- 3. Achievements -->
                    <div v-if="activeTab === 'about_page_achievements'">
                        <form @submit.prevent="submit('about_page_achievements')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">Achievements Header</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <InputLabel for="ach_subtitle" value="Subtitle" />
                                        <TextInput id="ach_subtitle" v-model="forms.about_page_achievements.subtitle" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="ach_title" value="Title" />
                                        <TextInput id="ach_title" v-model="forms.about_page_achievements.title" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="ach_btn" value="Button Text" />
                                        <TextInput id="ach_btn" v-model="forms.about_page_achievements.button_text" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="ach_url" value="Button URL" />
                                        <TextInput id="ach_url" v-model="forms.about_page_achievements.button_url" class="block w-full mt-1" />
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <div class="flex items-center justify-between mb-4 border-b pb-2">
                                    <h3 class="text-lg font-medium text-gray-800">Counters</h3>
                                    <button type="button" @click="addCounter" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">+ Add Counter</button>
                                </div>
                                <div class="space-y-4">
                                    <div v-for="(counter, index) in forms.about_page_achievements.counters" :key="index" class="p-4 border border-gray-100 rounded-lg bg-gray-50 relative">
                                        <button type="button" @click="removeCounter(index)" class="absolute top-2 right-2 text-red-500 hover:text-red-700">Remove</button>
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-2">
                                            <div>
                                                <InputLabel :for="'ac_c_num_'+index" value="Number" />
                                                <TextInput :id="'ac_c_num_'+index" v-model="counter.number" class="block w-full mt-1" />
                                            </div>
                                            <div>
                                                <InputLabel :for="'ac_c_suf_'+index" value="Suffix" />
                                                <TextInput :id="'ac_c_suf_'+index" v-model="counter.suffix" class="block w-full mt-1" />
                                            </div>
                                            <div class="md:col-span-2">
                                                <InputLabel :for="'ac_c_text_'+index" value="Text" />
                                                <TextInput :id="'ac_c_text_'+index" v-model="counter.text" class="block w-full mt-1" />
                                            </div>
                                            <div class="md:col-span-4">
                                                <InputLabel :for="'ac_c_icon_'+index" value="Icon" />
                                                <input type="file" accept="image/*" class="block w-full mt-1 file-input" @change="e => handleArrayFileUpload(e, 'about_page_achievements', 'counters', index, 'icon_file')" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.about_page_achievements.processing">Save Section</PrimaryButton>
                            </div>
                        </form>
                    </div>

                    <!-- 4. Mission / Vision -->
                    <div v-if="activeTab === 'about_page_mission'">
                        <form @submit.prevent="submit('about_page_mission')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">Why Choose Us Header</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <InputLabel for="mis_sub" value="Subtitle" />
                                        <TextInput id="mis_sub" v-model="forms.about_page_mission.subtitle" class="block w-full mt-1" />
                                    </div>
                                    <div class="md:col-span-2">
                                        <InputLabel for="mis_title" value="Title" />
                                        <TextInput id="mis_title" v-model="forms.about_page_mission.title" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="mis_bg" value="Background Shape Image" />
                                        <DropZone
    @change="file => handleFileUpload(file, 'about_page_mission', 'bg_shape')"
/>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <div class="flex items-center justify-between mb-4 border-b pb-2">
                                    <h3 class="text-lg font-medium text-gray-800">Tabs (Mission, Vision, Goal)</h3>
                                    <button type="button" @click="addTab" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">+ Add Tab</button>
                                </div>
                                <div class="space-y-8">
                                    <div v-for="(tab, index) in forms.about_page_mission.tabs" :key="index" class="p-4 border border-gray-200 rounded-lg bg-gray-50 relative">
                                        <button type="button" @click="removeTab(index)" class="absolute top-2 right-2 text-red-500 hover:text-red-700">Remove Tab</button>
                                        
                                        <h4 class="font-medium mb-3">Tab Details</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <InputLabel value="Tab Name (e.g. Our Mission)" />
                                                <TextInput v-model="tab.tab_name" class="block w-full mt-1" />
                                            </div>
                                            <div class="md:col-span-2">
                                                <InputLabel value="Heading Title" />
                                                <TextInput v-model="tab.title" class="block w-full mt-1" />
                                            </div>
                                            <div class="md:col-span-2">
                                                <InputLabel value="Description Text" />
                                                <textarea v-model="tab.text" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full mt-1" rows="2"></textarea>
                                            </div>
                                            <div>
                                                <InputLabel value="Video URL" />
                                                <TextInput v-model="tab.video_url" class="block w-full mt-1" />
                                            </div>
                                            <div>
                                                <InputLabel value="Image" />
                                                <input type="file" accept="image/*" class="block w-full mt-1 file-input" @change="e => handleTabFileUpload(e, index)" />
                                            </div>
                                        </div>

                                        <h4 class="font-medium mb-2 mt-4">Checklist Items</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-4">
                                            <div v-for="(chk, cIndex) in tab.checklist" :key="cIndex" class="flex gap-2">
                                                <TextInput v-model="chk.text" class="block w-full" placeholder="Checklist item text" />
                                                <button type="button" @click="tab.checklist.splice(cIndex, 1)" class="text-red-500">X</button>
                                            </div>
                                            <div>
                                                <button type="button" @click="tab.checklist.push({text: ''})" class="text-indigo-600 text-sm mt-2">+ Add Item</button>
                                            </div>
                                        </div>

                                        <h4 class="font-medium mb-2 mt-4 border-t pt-4">Sub Features</h4>
                                        <div class="space-y-4">
                                            <div v-for="(feat, fIndex) in tab.features" :key="fIndex" class="p-3 bg-white border border-gray-200 rounded">
                                                <div class="flex justify-between">
                                                    <h5 class="text-sm font-medium">Feature {{ fIndex + 1 }}</h5>
                                                    <button type="button" @click="tab.features.splice(fIndex, 1)" class="text-red-500 text-sm">Remove</button>
                                                </div>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                                                    <div>
                                                        <InputLabel value="Subtitle (e.g. Planning)" />
                                                        <TextInput v-model="feat.subtitle" class="block w-full mt-1 text-sm" />
                                                    </div>
                                                    <div>
                                                        <InputLabel value="Title" />
                                                        <TextInput v-model="feat.title" class="block w-full mt-1 text-sm" />
                                                    </div>
                                                    <div class="md:col-span-2">
                                                        <InputLabel value="Icon" />
                                                        <input type="file" accept="image/*" class="block w-full mt-1 file-input" @change="e => handleTabFeatureFileUpload(e, index, fIndex)" />
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" @click="tab.features.push({subtitle: '', title: '', icon: null, icon_file: null})" class="text-indigo-600 text-sm">+ Add Sub Feature</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.about_page_mission.processing">Save Section</PrimaryButton>
                            </div>
                        </form>
                    </div>

                    <!-- 5. Work Process -->
                    <div v-if="activeTab === 'about_page_process'">
                        <form @submit.prevent="submit('about_page_process')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">Work Process Header</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <InputLabel for="proc_sub" value="Subtitle" />
                                        <TextInput id="proc_sub" v-model="forms.about_page_process.subtitle" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="proc_title" value="Title" />
                                        <TextInput id="proc_title" v-model="forms.about_page_process.title" class="block w-full mt-1" />
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <div class="flex items-center justify-between mb-4 border-b pb-2">
                                    <h3 class="text-lg font-medium text-gray-800">Process Cards</h3>
                                    <button type="button" @click="addProcessCard" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">+ Add Card</button>
                                </div>
                                <div class="space-y-4">
                                    <div v-for="(card, index) in forms.about_page_process.cards" :key="index" class="p-4 border border-gray-100 rounded-lg bg-gray-50 relative">
                                        <button type="button" @click="removeProcessCard(index)" class="absolute top-2 right-2 text-red-500 hover:text-red-700">Remove</button>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                                            <div>
                                                <InputLabel :for="'pc_sub_'+index" value="Subtitle (e.g. Step - 01)" />
                                                <TextInput :id="'pc_sub_'+index" v-model="card.subtitle" class="block w-full mt-1" />
                                            </div>
                                            <div>
                                                <InputLabel :for="'pc_title_'+index" value="Title" />
                                                <TextInput :id="'pc_title_'+index" v-model="card.title" class="block w-full mt-1" />
                                            </div>
                                            <div class="md:col-span-2">
                                                <InputLabel :for="'pc_text_'+index" value="Text" />
                                                <textarea :id="'pc_text_'+index" v-model="card.text" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full mt-1" rows="2"></textarea>
                                            </div>
                                            <div>
                                                <InputLabel :for="'pc_icon_'+index" value="Icon" />
                                                <input type="file" accept="image/*" class="block w-full mt-1 file-input" @change="e => handleArrayFileUpload(e, 'about_page_process', 'cards', index, 'icon_file')" />
                                            </div>
                                            <div>
                                                <InputLabel :for="'pc_bg_'+index" value="Background Shape" />
                                                <input type="file" accept="image/*" class="block w-full mt-1 file-input" @change="e => handleArrayFileUpload(e, 'about_page_process', 'cards', index, 'bg_shape_file')" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.about_page_process.processing">Save Section</PrimaryButton>
                            </div>
                        </form>
                    </div>

                    <!-- 6. SEO -->
                    <div v-if="activeTab === 'about_page_seo'">
                        <form @submit.prevent="submit('about_page_seo')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">SEO Configuration</h3>
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <InputLabel for="seo_title" value="Meta Title" />
                                        <TextInput id="seo_title" v-model="forms.about_page_seo.meta_title" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="seo_description" value="Meta Description" />
                                        <textarea id="seo_description" v-model="forms.about_page_seo.meta_description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full mt-1" rows="3"></textarea>
                                    </div>
                                    <div>
                                        <InputLabel for="seo_keywords" value="Meta Keywords" />
                                        <TextInput id="seo_keywords" v-model="forms.about_page_seo.meta_keywords" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="seo_author" value="Meta Author" />
                                        <TextInput id="seo_author" v-model="forms.about_page_seo.meta_author" class="block w-full mt-1" />
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.about_page_seo.processing">Save Section</PrimaryButton>
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
import DropZone from '@/Components/Admin/Shared/DropZone.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    content: {
        type: Object,
        required: true
    }
});

const activeTab = ref('about_page_hero');
const tabs = [
    { key: 'about_page_hero', label: 'Hero/Breadcrumb' },
    { key: 'about_page_company', label: 'About Company' },
    { key: 'about_page_achievements', label: 'Achievements' },
    { key: 'about_page_mission', label: 'Why Choose Us' },
    { key: 'about_page_process', label: 'Work Process' },
    { key: 'about_page_seo', label: 'SEO Config' },
];

const forms = {
    about_page_hero: useForm({
        section_key: 'about_page_hero',
        title: props.content.about_page_hero?.title || '',
        bg_image: null,
    }),
    about_page_company: useForm({
        section_key: 'about_page_company',
        years_experience: props.content.about_page_company?.years_experience || '',
        years_text: props.content.about_page_company?.years_text || '',
        subtitle: props.content.about_page_company?.subtitle || '',
        title: props.content.about_page_company?.title || '',
        description: props.content.about_page_company?.description || '',
        image1: null,
        image2: null,
        shape_image: null,
        features: Array.isArray(props.content.about_page_company?.features) ? props.content.about_page_company.features.map(f => ({ ...f, icon_file: null })) : []
    }),
    about_page_achievements: useForm({
        section_key: 'about_page_achievements',
        subtitle: props.content.about_page_achievements?.subtitle || '',
        title: props.content.about_page_achievements?.title || '',
        button_text: props.content.about_page_achievements?.button_text || '',
        button_url: props.content.about_page_achievements?.button_url || '',
        counters: Array.isArray(props.content.about_page_achievements?.counters) ? props.content.about_page_achievements.counters.map(c => ({ ...c, icon_file: null })) : []
    }),
    about_page_mission: useForm({
        section_key: 'about_page_mission',
        subtitle: props.content.about_page_mission?.subtitle || '',
        title: props.content.about_page_mission?.title || '',
        bg_shape: null,
        tabs: Array.isArray(props.content.about_page_mission?.tabs) ? props.content.about_page_mission.tabs.map(t => ({ 
            ...t, 
            image_file: null,
            checklist: Array.isArray(t.checklist) ? t.checklist : [],
            features: Array.isArray(t.features) ? t.features.map(f => ({ ...f, icon_file: null })) : []
        })) : []
    }),
    about_page_process: useForm({
        section_key: 'about_page_process',
        subtitle: props.content.about_page_process?.subtitle || '',
        title: props.content.about_page_process?.title || '',
        cards: Array.isArray(props.content.about_page_process?.cards) ? props.content.about_page_process.cards.map(c => ({ ...c, icon_file: null, bg_shape_file: null })) : []
    }),
    about_page_seo: useForm({
        section_key: 'about_page_seo',
        meta_title: props.content.about_page_seo?.meta_title || '',
        meta_description: props.content.about_page_seo?.meta_description || '',
        meta_keywords: props.content.about_page_seo?.meta_keywords || '',
        meta_author: props.content.about_page_seo?.meta_author || '',
    })
};

function handleFileUpload(file, formKey, fieldName) {
    forms[formKey][fieldName] = file;
}

function handleArrayFileUpload(file, formKey, arrayName, index, fieldName) {
    forms[formKey][arrayName][index][fieldName] = file;
}

function handleTabFileUpload(e, tIndex) {
    if (e.target.files && e.target.files.length > 0) {
        forms.about_page_mission.tabs[tIndex].image_file = e.target.files[0];
    }
}

function handleTabFeatureFileUpload(e, tIndex, fIndex) {
    if (e.target.files && e.target.files.length > 0) {
        forms.about_page_mission.tabs[tIndex].features[fIndex].icon_file = e.target.files[0];
    }
}

function addFeature(formKey) {
    forms[formKey].features.push({ title: '', text: '', icon: null, icon_file: null });
}
function removeFeature(formKey, index) {
    forms[formKey].features.splice(index, 1);
}

function addCounter() {
    forms.about_page_achievements.counters.push({ number: '', suffix: '', text: '', icon: null, icon_file: null });
}
function removeCounter(index) {
    forms.about_page_achievements.counters.splice(index, 1);
}

function addProcessCard() {
    forms.about_page_process.cards.push({ subtitle: '', title: '', text: '', icon: null, icon_file: null, bg_shape: null, bg_shape_file: null });
}
function removeProcessCard(index) {
    forms.about_page_process.cards.splice(index, 1);
}

function addTab() {
    forms.about_page_mission.tabs.push({ tab_name: '', title: '', text: '', video_url: '', image: null, image_file: null, checklist: [], features: [] });
}
function removeTab(index) {
    forms.about_page_mission.tabs.splice(index, 1);
}

function submit(formKey) {
    forms[formKey].post(route('admin.website-settings.about-content.update'), {
        preserveScroll: true,
        forceFormData: true
    });
}

function getImageUrl(path) {
    if (!path) return '';
    if (path.startsWith('assets/')) return `/${path}`;
    return `/storage/${path}`;
}
</script>

<style scoped>
.file-input {
    @apply text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100;
}
</style>
