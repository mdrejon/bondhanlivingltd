<template>
  <Head title="Contact Page Content" />

  <AdminLayout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
      <div class="sm:flex sm:justify-between sm:items-center mb-8">
        <div class="mb-4 sm:mb-0">
          <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">Contact Page Content ✨</h1>
        </div>
        <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
          <button @click="submitForm" class="btn bg-indigo-500 hover:bg-indigo-600 text-white">
            <svg class="w-4 h-4 fill-current opacity-50 shrink-0" viewBox="0 0 16 16">
              <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
            </svg>
            <span class="hidden xs:block ml-2">Save Changes</span>
          </button>
        </div>
      </div>

      <div v-if="$page.props.flash.success" class="mb-4 p-4 bg-emerald-100 text-emerald-600 border border-emerald-200 rounded">
        {{ $page.props.flash.success }}
      </div>

      <div class="bg-white shadow-lg rounded-sm border border-slate-200">
        <div class="flex flex-wrap border-b border-slate-200">
          <button @click="activeTab = 'hero'" :class="{'text-indigo-500 border-indigo-500': activeTab === 'hero', 'text-slate-600 hover:text-slate-800 border-transparent': activeTab !== 'hero'}" class="px-4 py-3 border-b-2 font-medium text-sm">Hero Section</button>
          <button @click="activeTab = 'info'" :class="{'text-indigo-500 border-indigo-500': activeTab === 'info', 'text-slate-600 hover:text-slate-800 border-transparent': activeTab !== 'info'}" class="px-4 py-3 border-b-2 font-medium text-sm">Contact Information</button>
          <button @click="activeTab = 'map'" :class="{'text-indigo-500 border-indigo-500': activeTab === 'map', 'text-slate-600 hover:text-slate-800 border-transparent': activeTab !== 'map'}" class="px-4 py-3 border-b-2 font-medium text-sm">Map Embed</button>
          <button @click="activeTab = 'seo'" :class="{'text-indigo-500 border-indigo-500': activeTab === 'seo', 'text-slate-600 hover:text-slate-800 border-transparent': activeTab !== 'seo'}" class="px-4 py-3 border-b-2 font-medium text-sm">SEO Config</button>
        </div>

        <div class="p-6">
          <form @submit.prevent="submitForm">
            <!-- Hero Tab -->
            <div v-show="activeTab === 'hero'">
              <h2 class="text-xl font-bold text-slate-800 mb-4">Hero/Breadcrumb Section</h2>
              <div class="grid gap-5">
                <div>
                  <label class="block text-sm font-medium mb-1">Title</label>
                  <input v-model="form.contact_page_hero.title" type="text" class="form-input w-full" />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1">Background Image</label>
                  <input type="file" @change="e => form.contact_page_hero.bg_image_file = e.target.files[0]" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                  <div class="mt-2" v-if="form.contact_page_hero.bg_image">
                    <img :src="getImgUrl(form.contact_page_hero.bg_image)" class="w-32 h-auto rounded border" />
                  </div>
                </div>
              </div>
            </div>

            <!-- Contact Info Tab -->
            <div v-show="activeTab === 'info'">
              <h2 class="text-xl font-bold text-slate-800 mb-4">Contact Information</h2>
              <div class="grid gap-5">
                <div>
                  <label class="block text-sm font-medium mb-1">Section Title</label>
                  <input v-model="form.contact_page_info.title" type="text" class="form-input w-full" />
                </div>
                
                <hr>

                <h3 class="text-lg font-semibold text-slate-800">Corporate Office</h3>
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium mb-1">Label</label>
                    <input v-model="form.contact_page_info.office_label" type="text" class="form-input w-full" />
                  </div>
                  <div>
                    <label class="block text-sm font-medium mb-1">Google Maps Link URL</label>
                    <input v-model="form.contact_page_info.office_map_url" type="text" class="form-input w-full" />
                  </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Address text</label>
                    <textarea v-model="form.contact_page_info.office_address" rows="2" class="form-textarea w-full"></textarea>
                </div>

                <hr>

                <h3 class="text-lg font-semibold text-slate-800">Phone & Email</h3>
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium mb-1">Label</label>
                    <input v-model="form.contact_page_info.phone_label" type="text" class="form-input w-full" />
                  </div>
                  <div>
                    <label class="block text-sm font-medium mb-1">Email Address</label>
                    <input v-model="form.contact_page_info.email" type="email" class="form-input w-full" />
                  </div>
                  <div>
                    <label class="block text-sm font-medium mb-1">Phone Number 1</label>
                    <input v-model="form.contact_page_info.phone_1" type="text" class="form-input w-full" />
                  </div>
                  <div>
                    <label class="block text-sm font-medium mb-1">Phone Number 2 (Optional)</label>
                    <input v-model="form.contact_page_info.phone_2" type="text" class="form-input w-full" />
                  </div>
                </div>

                <hr>
                
                <h3 class="text-lg font-semibold text-slate-800">Hours of Operation</h3>
                <div>
                    <label class="block text-sm font-medium mb-1">Label</label>
                    <input v-model="form.contact_page_info.hours_label" type="text" class="form-input w-full" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium mb-1">Hours Line 1</label>
                    <input v-model="form.contact_page_info.hours_1" type="text" class="form-input w-full" />
                  </div>
                  <div>
                    <label class="block text-sm font-medium mb-1">Hours Line 2 (Optional)</label>
                    <input v-model="form.contact_page_info.hours_2" type="text" class="form-input w-full" />
                  </div>
                </div>

              </div>
            </div>

            <!-- Map Embed Tab -->
            <div v-show="activeTab === 'map'">
              <h2 class="text-xl font-bold text-slate-800 mb-4">Google Maps Embed</h2>
              <div class="grid gap-5">
                <div>
                  <label class="block text-sm font-medium mb-1">Map Embed URL (src attribute from Google Maps iframe)</label>
                  <textarea v-model="form.contact_page_map.embed_url" rows="4" class="form-textarea w-full"></textarea>
                  <p class="text-xs text-slate-500 mt-2">Go to Google Maps -> Share -> Embed a map, and copy ONLY the URL inside the src="..." attribute.</p>
                </div>
              </div>
            </div>

            <!-- SEO Tab -->
            <div v-show="activeTab === 'seo'">
              <h2 class="text-xl font-bold text-slate-800 mb-4">SEO Configuration</h2>
              <div class="grid gap-5">
                <div>
                  <label class="block text-sm font-medium mb-1">Meta Title</label>
                  <input v-model="form.contact_page_seo.meta_title" type="text" class="form-input w-full" />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1">Meta Description</label>
                  <textarea v-model="form.contact_page_seo.meta_description" rows="3" class="form-textarea w-full"></textarea>
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1">Meta Keywords</label>
                  <input v-model="form.contact_page_seo.meta_keywords" type="text" class="form-input w-full" />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1">Meta Author</label>
                  <input v-model="form.contact_page_seo.meta_author" type="text" class="form-input w-full" />
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';

const props = defineProps({
  content: Object
});

const activeTab = ref('hero');

const form = useForm({
  contact_page_hero: props.content.contact_page_hero || { title: '', bg_image: '', bg_image_file: null },
  contact_page_info: props.content.contact_page_info || { 
      title: '', office_label: '', office_address: '', office_map_url: '', 
      phone_label: '', phone_1: '', phone_2: '', email: '', 
      hours_label: '', hours_1: '', hours_2: '' 
  },
  contact_page_map: props.content.contact_page_map || { embed_url: '' },
  contact_page_seo: props.content.contact_page_seo || { meta_title: '', meta_description: '', meta_keywords: '', meta_author: '' }
});

const submitForm = () => {
  form.post(route('admin.website-settings.contact-content.update'), {
    preserveScroll: true,
    forceFormData: true,
  });
};

const getImgUrl = (path) => {
  if (!path) return '';
  if (path.startsWith('assets/')) return '/' + path;
  return '/storage/' + path;
};
</script>
