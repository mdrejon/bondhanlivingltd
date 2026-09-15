<template>
    <AdminLayout>
        <Head title="Home Page Content" />

        <div class="max-w-6xl mx-auto space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-800">Home Page Sections</h2>
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
                    
                    <!-- 1. Why Choose Us -->
                    <div v-if="activeTab === 'home_page_why_choose_us'">
                        <form @submit.prevent="submit('home_page_why_choose_us')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">Main Details</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <InputLabel for="wcu_subtitle" value="Subtitle" />
                                        <TextInput id="wcu_subtitle" v-model="forms.home_page_why_choose_us.subtitle" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="wcu_since_year" value="Since Year (e.g., 1998)" />
                                        <TextInput id="wcu_since_year" v-model="forms.home_page_why_choose_us.since_year" class="block w-full mt-1" />
                                    </div>
                                    <div class="md:col-span-2">
                                        <InputLabel for="wcu_title" value="Title" />
                                        <TextInput id="wcu_title" v-model="forms.home_page_why_choose_us.title" class="block w-full mt-1" />
                                    </div>
                                    <div class="md:col-span-2">
                                        <InputLabel for="wcu_description" value="Description" />
                                        <textarea
                                            id="wcu_description"
                                            v-model="forms.home_page_why_choose_us.description"
                                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full mt-1"
                                            rows="3"
                                        ></textarea>
                                    </div>
                                    <div class="md:col-span-2">
                                        <InputLabel for="wcu_image" value="Main Image (Side Illustration)" />
                                        <input
                                            id="wcu_image"
                                            type="file"
                                            accept="image/*"
                                            class="block w-full mt-1 file-input"
                                            @change="e => handleFileUpload(e, 'home_page_why_choose_us', 'image')"
                                        />
                                        <p v-if="content.home_page_why_choose_us?.image" class="mt-2 text-sm text-gray-500">
                                            Current image: <a :href="getImageUrl(content.home_page_why_choose_us.image)" target="_blank" class="text-indigo-600 hover:underline">View</a>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <div class="flex items-center justify-between mb-4 border-b pb-2">
                                    <h3 class="text-lg font-medium text-gray-800">Features</h3>
                                    <button type="button" @click="addFeature" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">+ Add Feature</button>
                                </div>
                                <div class="space-y-4">
                                    <div v-for="(feature, index) in forms.home_page_why_choose_us.features" :key="index" class="p-4 border border-gray-100 rounded-lg bg-gray-50 relative">
                                        <button type="button" @click="removeFeature(index)" class="absolute top-2 right-2 text-red-500 hover:text-red-700">Remove</button>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                                            <div>
                                                <InputLabel :for="'f_title_'+index" value="Title" />
                                                <TextInput :id="'f_title_'+index" v-model="feature.title" class="block w-full mt-1" />
                                            </div>
                                            <div>
                                                <InputLabel :for="'f_url_'+index" value="URL" />
                                                <TextInput :id="'f_url_'+index" v-model="feature.url" class="block w-full mt-1" />
                                            </div>
                                            <div class="md:col-span-2">
                                                <InputLabel :for="'f_text_'+index" value="Text" />
                                                <TextInput :id="'f_text_'+index" v-model="feature.text" class="block w-full mt-1" />
                                            </div>
                                            <div class="md:col-span-2">
                                                <InputLabel :for="'f_icon_'+index" value="Icon" />
                                                <input type="file" accept="image/*" class="block w-full mt-1 file-input" @change="e => handleArrayFileUpload(e, 'home_page_why_choose_us', 'features', index, 'icon_file')" />
                                                <p v-if="feature.icon && !feature.icon_file" class="mt-1 text-xs text-gray-500">Current icon uploaded.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.home_page_why_choose_us.processing">Save Section</PrimaryButton>
                            </div>
                        </form>
                    </div>

                    <!-- 2. Services -->
                    <div v-if="activeTab === 'home_page_service'">
                        <form @submit.prevent="submit('home_page_service')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">Services Section</h3>
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <InputLabel for="srv_subtitle" value="Subtitle" />
                                        <TextInput id="srv_subtitle" v-model="forms.home_page_service.subtitle" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="srv_title" value="Title" />
                                        <TextInput id="srv_title" v-model="forms.home_page_service.title" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="srv_bg" value="Background Image" />
                                        <input id="srv_bg" type="file" accept="image/*" class="block w-full mt-1 file-input" @change="e => handleFileUpload(e, 'home_page_service', 'bg_image')" />
                                        <p v-if="content.home_page_service?.bg_image" class="mt-2 text-sm text-gray-500">Current: <a :href="getImageUrl(content.home_page_service.bg_image)" target="_blank" class="text-indigo-600 hover:underline">View</a></p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.home_page_service.processing">Save Section</PrimaryButton>
                            </div>
                        </form>
                    </div>

                    <!-- 3. CTA -->
                    <div v-if="activeTab === 'home_page_cta'">
                        <form @submit.prevent="submit('home_page_cta')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">Call to Action</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <InputLabel for="cta_title" value="Title" />
                                        <TextInput id="cta_title" v-model="forms.home_page_cta.title" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="cta_btn_text" value="Button Text" />
                                        <TextInput id="cta_btn_text" v-model="forms.home_page_cta.button_text" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="cta_btn_url" value="Button URL" />
                                        <TextInput id="cta_btn_url" v-model="forms.home_page_cta.button_url" class="block w-full mt-1" />
                                    </div>
                                    <div class="md:col-span-2">
                                        <InputLabel for="cta_bg" value="Background Image" />
                                        <input id="cta_bg" type="file" accept="image/*" class="block w-full mt-1 file-input" @change="e => handleFileUpload(e, 'home_page_cta', 'bg_image')" />
                                        <p v-if="content.home_page_cta?.bg_image" class="mt-2 text-sm text-gray-500">Current: <a :href="getImageUrl(content.home_page_cta.bg_image)" target="_blank" class="text-indigo-600 hover:underline">View</a></p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.home_page_cta.processing">Save Section</PrimaryButton>
                            </div>
                        </form>
                    </div>

                    <!-- 4. Team -->
                    <div v-if="activeTab === 'home_page_team'">
                        <form @submit.prevent="submit('home_page_team')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">Team Section</h3>
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <InputLabel for="team_subtitle" value="Subtitle" />
                                        <TextInput id="team_subtitle" v-model="forms.home_page_team.subtitle" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="team_title" value="Title" />
                                        <TextInput id="team_title" v-model="forms.home_page_team.title" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="team_bg" value="Background Image" />
                                        <input id="team_bg" type="file" accept="image/*" class="block w-full mt-1 file-input" @change="e => handleFileUpload(e, 'home_page_team', 'bg_image')" />
                                        <p v-if="content.home_page_team?.bg_image" class="mt-2 text-sm text-gray-500">Current: <a :href="getImageUrl(content.home_page_team.bg_image)" target="_blank" class="text-indigo-600 hover:underline">View</a></p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.home_page_team.processing">Save Section</PrimaryButton>
                            </div>
                        </form>
                    </div>

                    <!-- 5. Projects -->
                    <div v-if="activeTab === 'home_page_project'">
                        <form @submit.prevent="submit('home_page_project')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">Projects Section</h3>
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <InputLabel for="proj_subtitle" value="Subtitle" />
                                        <TextInput id="proj_subtitle" v-model="forms.home_page_project.subtitle" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="proj_title" value="Title" />
                                        <TextInput id="proj_title" v-model="forms.home_page_project.title" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="proj_bg" value="Background Image" />
                                        <input id="proj_bg" type="file" accept="image/*" class="block w-full mt-1 file-input" @change="e => handleFileUpload(e, 'home_page_project', 'bg_image')" />
                                        <p v-if="content.home_page_project?.bg_image" class="mt-2 text-sm text-gray-500">Current: <a :href="getImageUrl(content.home_page_project.bg_image)" target="_blank" class="text-indigo-600 hover:underline">View</a></p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.home_page_project.processing">Save Section</PrimaryButton>
                            </div>
                        </form>
                    </div>

                    <!-- 6. Achievements -->
                    <div v-if="activeTab === 'home_page_achievements'">
                        <form @submit.prevent="submit('home_page_achievements')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">Achievements</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <InputLabel for="ach_subtitle" value="Subtitle" />
                                        <TextInput id="ach_subtitle" v-model="forms.home_page_achievements.subtitle" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="ach_title" value="Title" />
                                        <TextInput id="ach_title" v-model="forms.home_page_achievements.title" class="block w-full mt-1" />
                                    </div>
                                    <div class="md:col-span-2">
                                        <InputLabel for="ach_desc" value="Description" />
                                        <textarea id="ach_desc" v-model="forms.home_page_achievements.description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full mt-1" rows="2"></textarea>
                                    </div>
                                    <div>
                                        <InputLabel for="ach_bg" value="Background Image" />
                                        <input id="ach_bg" type="file" accept="image/*" class="block w-full mt-1 file-input" @change="e => handleFileUpload(e, 'home_page_achievements', 'bg_image')" />
                                        <p v-if="content.home_page_achievements?.bg_image" class="mt-2 text-sm text-gray-500">Current: <a :href="getImageUrl(content.home_page_achievements.bg_image)" target="_blank" class="text-indigo-600 hover:underline">View</a></p>
                                    </div>
                                    <div>
                                        <InputLabel for="ach_side" value="Side Image" />
                                        <input id="ach_side" type="file" accept="image/*" class="block w-full mt-1 file-input" @change="e => handleFileUpload(e, 'home_page_achievements', 'side_image')" />
                                        <p v-if="content.home_page_achievements?.side_image" class="mt-2 text-sm text-gray-500">Current: <a :href="getImageUrl(content.home_page_achievements.side_image)" target="_blank" class="text-indigo-600 hover:underline">View</a></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <div class="flex items-center justify-between mb-4 border-b pb-2">
                                    <h3 class="text-lg font-medium text-gray-800">Counters</h3>
                                    <button type="button" @click="addCounter" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">+ Add Counter</button>
                                </div>
                                <div class="space-y-4">
                                    <div v-for="(counter, index) in forms.home_page_achievements.counters" :key="index" class="p-4 border border-gray-100 rounded-lg bg-gray-50 relative">
                                        <button type="button" @click="removeCounter(index)" class="absolute top-2 right-2 text-red-500 hover:text-red-700">Remove</button>
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-2">
                                            <div>
                                                <InputLabel :for="'c_num_'+index" value="Number" />
                                                <TextInput :id="'c_num_'+index" v-model="counter.number" class="block w-full mt-1" />
                                            </div>
                                            <div>
                                                <InputLabel :for="'c_suf_'+index" value="Suffix" />
                                                <TextInput :id="'c_suf_'+index" v-model="counter.suffix" class="block w-full mt-1" placeholder="e.g. k+" />
                                            </div>
                                            <div class="md:col-span-2">
                                                <InputLabel :for="'c_text_'+index" value="Text" />
                                                <TextInput :id="'c_text_'+index" v-model="counter.text" class="block w-full mt-1" />
                                            </div>
                                            <div class="md:col-span-4">
                                                <InputLabel :for="'c_icon_'+index" value="Icon" />
                                                <input type="file" accept="image/*" class="block w-full mt-1 file-input" @change="e => handleArrayFileUpload(e, 'home_page_achievements', 'counters', index, 'icon_file')" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.home_page_achievements.processing">Save Section</PrimaryButton>
                            </div>
                        </form>
                    </div>

                    <!-- 7. Testimonials -->
                    <div v-if="activeTab === 'home_page_testimonial'">
                        <form @submit.prevent="submit('home_page_testimonial')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">Testimonials Section</h3>
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <InputLabel for="testi_subtitle" value="Subtitle" />
                                        <TextInput id="testi_subtitle" v-model="forms.home_page_testimonial.subtitle" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="testi_title" value="Title" />
                                        <TextInput id="testi_title" v-model="forms.home_page_testimonial.title" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="testi_bg" value="Background Image" />
                                        <input id="testi_bg" type="file" accept="image/*" class="block w-full mt-1 file-input" @change="e => handleFileUpload(e, 'home_page_testimonial', 'bg_image')" />
                                        <p v-if="content.home_page_testimonial?.bg_image" class="mt-2 text-sm text-gray-500">Current: <a :href="getImageUrl(content.home_page_testimonial.bg_image)" target="_blank" class="text-indigo-600 hover:underline">View</a></p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.home_page_testimonial.processing">Save Section</PrimaryButton>
                            </div>
                        </form>
                    </div>

                    <!-- 8. Blog -->
                    <div v-if="activeTab === 'home_page_blog'">
                        <form @submit.prevent="submit('home_page_blog')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">Blog Section</h3>
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <InputLabel for="blog_subtitle" value="Subtitle" />
                                        <TextInput id="blog_subtitle" v-model="forms.home_page_blog.subtitle" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="blog_title" value="Title" />
                                        <TextInput id="blog_title" v-model="forms.home_page_blog.title" class="block w-full mt-1" />
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.home_page_blog.processing">Save Section</PrimaryButton>
                            </div>
                        </form>
                    </div>

                    <!-- 9. SEO -->
                    <div v-if="activeTab === 'home_page_seo'">
                        <form @submit.prevent="submit('home_page_seo')" class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">SEO Configuration</h3>
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <InputLabel for="seo_title" value="Meta Title" />
                                        <TextInput id="seo_title" v-model="forms.home_page_seo.meta_title" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="seo_description" value="Meta Description" />
                                        <textarea id="seo_description" v-model="forms.home_page_seo.meta_description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full mt-1" rows="3"></textarea>
                                    </div>
                                    <div>
                                        <InputLabel for="seo_keywords" value="Meta Keywords" />
                                        <TextInput id="seo_keywords" v-model="forms.home_page_seo.meta_keywords" class="block w-full mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="seo_author" value="Meta Author" />
                                        <TextInput id="seo_author" v-model="forms.home_page_seo.meta_author" class="block w-full mt-1" />
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="forms.home_page_seo.processing">Save Section</PrimaryButton>
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

const props = defineProps({
    content: {
        type: Object,
        required: true
    }
});

const activeTab = ref('home_page_why_choose_us');
const tabs = [
    { key: 'home_page_why_choose_us', label: 'Why Choose Us' },
    { key: 'home_page_service', label: 'Services' },
    { key: 'home_page_cta', label: 'Call to Action' },
    { key: 'home_page_team', label: 'Team' },
    { key: 'home_page_project', label: 'Projects' },
    { key: 'home_page_achievements', label: 'Achievements' },
    { key: 'home_page_testimonial', label: 'Testimonials' },
    { key: 'home_page_blog', label: 'Blog' },
    { key: 'home_page_seo', label: 'SEO Config' },
];

const forms = {
    home_page_why_choose_us: useForm({
        section_key: 'home_page_why_choose_us',
        image: null,
        since_year: props.content.home_page_why_choose_us?.since_year || '',
        subtitle: props.content.home_page_why_choose_us?.subtitle || '',
        title: props.content.home_page_why_choose_us?.title || '',
        description: props.content.home_page_why_choose_us?.description || '',
        features: Array.isArray(props.content.home_page_why_choose_us?.features) ? props.content.home_page_why_choose_us.features.map(f => ({ ...f, icon_file: null })) : []
    }),
    home_page_service: useForm({
        section_key: 'home_page_service',
        subtitle: props.content.home_page_service?.subtitle || '',
        title: props.content.home_page_service?.title || '',
        bg_image: null,
    }),
    home_page_cta: useForm({
        section_key: 'home_page_cta',
        title: props.content.home_page_cta?.title || '',
        button_text: props.content.home_page_cta?.button_text || '',
        button_url: props.content.home_page_cta?.button_url || '',
        bg_image: null,
    }),
    home_page_team: useForm({
        section_key: 'home_page_team',
        subtitle: props.content.home_page_team?.subtitle || '',
        title: props.content.home_page_team?.title || '',
        bg_image: null,
    }),
    home_page_project: useForm({
        section_key: 'home_page_project',
        subtitle: props.content.home_page_project?.subtitle || '',
        title: props.content.home_page_project?.title || '',
        bg_image: null,
    }),
    home_page_achievements: useForm({
        section_key: 'home_page_achievements',
        subtitle: props.content.home_page_achievements?.subtitle || '',
        title: props.content.home_page_achievements?.title || '',
        description: props.content.home_page_achievements?.description || '',
        bg_image: null,
        side_image: null,
        counters: Array.isArray(props.content.home_page_achievements?.counters) ? props.content.home_page_achievements.counters.map(c => ({ ...c, icon_file: null })) : []
    }),
    home_page_testimonial: useForm({
        section_key: 'home_page_testimonial',
        subtitle: props.content.home_page_testimonial?.subtitle || '',
        title: props.content.home_page_testimonial?.title || '',
        bg_image: null,
    }),
    home_page_blog: useForm({
        section_key: 'home_page_blog',
        subtitle: props.content.home_page_blog?.subtitle || '',
        title: props.content.home_page_blog?.title || '',
    }),
    home_page_seo: useForm({
        section_key: 'home_page_seo',
        meta_title: props.content.home_page_seo?.meta_title || '',
        meta_description: props.content.home_page_seo?.meta_description || '',
        meta_keywords: props.content.home_page_seo?.meta_keywords || '',
        meta_author: props.content.home_page_seo?.meta_author || '',
    })
};

function handleFileUpload(e, formKey, fieldName) {
    if (e.target.files && e.target.files.length > 0) {
        forms[formKey][fieldName] = e.target.files[0];
    }
}

function handleArrayFileUpload(e, formKey, arrayName, index, fieldName) {
    if (e.target.files && e.target.files.length > 0) {
        forms[formKey][arrayName][index][fieldName] = e.target.files[0];
    }
}

function addFeature() {
    forms.home_page_why_choose_us.features.push({ title: '', text: '', url: '', icon: null, icon_file: null });
}
function removeFeature(index) {
    forms.home_page_why_choose_us.features.splice(index, 1);
}

function addCounter() {
    forms.home_page_achievements.counters.push({ number: '', suffix: '', text: '', icon: null, icon_file: null });
}
function removeCounter(index) {
    forms.home_page_achievements.counters.splice(index, 1);
}

function submit(formKey) {
    forms[formKey].post(route('admin.website-settings.home-content.update'), {
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
