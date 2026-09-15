<template>
  <Head title="History Page Content" />

  <AdminLayout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
      <!-- Page header -->
      <div class="sm:flex sm:justify-between sm:items-center mb-8">
        <div class="mb-4 sm:mb-0">
          <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">History Page Content ✨</h1>
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

      <!-- Flash Message -->
      <div v-if="$page.props.flash.success" class="mb-4 p-4 bg-emerald-100 text-emerald-600 border border-emerald-200 rounded">
        {{ $page.props.flash.success }}
      </div>

      <div class="bg-white shadow-lg rounded-sm border border-slate-200">
        <!-- Tabs -->
        <div class="flex flex-wrap border-b border-slate-200">
          <button @click="activeTab = 'hero'" :class="{'text-indigo-500 border-indigo-500': activeTab === 'hero', 'text-slate-600 hover:text-slate-800 border-transparent': activeTab !== 'hero'}" class="px-4 py-3 border-b-2 font-medium text-sm">Hero Section</button>
          <button @click="activeTab = 'main'" :class="{'text-indigo-500 border-indigo-500': activeTab === 'main', 'text-slate-600 hover:text-slate-800 border-transparent': activeTab !== 'main'}" class="px-4 py-3 border-b-2 font-medium text-sm">Main Area</button>
          <button @click="activeTab = 'timeline'" :class="{'text-indigo-500 border-indigo-500': activeTab === 'timeline', 'text-slate-600 hover:text-slate-800 border-transparent': activeTab !== 'timeline'}" class="px-4 py-3 border-b-2 font-medium text-sm">Timeline Milestones</button>
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
                  <input v-model="form.history_page_hero.title" type="text" class="form-input w-full" />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1">Background Image</label>
                  <input type="file" @change="e => form.history_page_hero.bg_image_file = e.target.files[0]" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                  <div class="mt-2" v-if="form.history_page_hero.bg_image">
                    <img :src="getImgUrl(form.history_page_hero.bg_image)" class="w-32 h-auto rounded border" />
                  </div>
                </div>
              </div>
            </div>

            <!-- Main Tab -->
            <div v-show="activeTab === 'main'">
              <h2 class="text-xl font-bold text-slate-800 mb-4">Main Content Area</h2>
              <div class="grid gap-5">
                <div>
                  <label class="block text-sm font-medium mb-1">Subtitle</label>
                  <input v-model="form.history_page_main.subtitle" type="text" class="form-input w-full" />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1">Title</label>
                  <input v-model="form.history_page_main.title" type="text" class="form-input w-full" />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1">Description</label>
                  <textarea v-model="form.history_page_main.description" rows="4" class="form-textarea w-full"></textarea>
                </div>
              </div>
            </div>

            <!-- Timeline Tab -->
            <div v-show="activeTab === 'timeline'">
              <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-slate-800">Timeline Milestones</h2>
                <button type="button" @click="addTimelineItem" class="btn-sm bg-indigo-500 hover:bg-indigo-600 text-white">Add Milestone</button>
              </div>
              <div v-for="(item, index) in form.history_page_timeline" :key="index" class="p-4 border border-slate-200 rounded-sm mb-4 bg-slate-50 relative">
                <button type="button" @click="removeTimelineItem(index)" class="absolute top-2 right-2 text-rose-500 hover:text-rose-600">
                  <svg class="w-5 h-5 fill-current" viewBox="0 0 16 16"><path d="M5 7h6v2H5z" /></svg>
                </button>
                <div class="grid gap-5">
                  <div class="grid grid-cols-2 gap-4">
                    <div>
                      <label class="block text-sm font-medium mb-1">Year</label>
                      <input v-model="item.year" type="text" class="form-input w-full" placeholder="e.g. 2010" />
                    </div>
                    <div>
                      <label class="block text-sm font-medium mb-1">Title</label>
                      <input v-model="item.title" type="text" class="form-input w-full" />
                    </div>
                  </div>
                  <div>
                    <label class="block text-sm font-medium mb-1">Description</label>
                    <textarea v-model="item.description" rows="3" class="form-textarea w-full"></textarea>
                  </div>
                  <div>
                    <label class="block text-sm font-medium mb-1">Image</label>
                    <input type="file" @change="e => item.image_file = e.target.files[0]" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700" />
                    <div class="mt-2" v-if="item.image">
                      <img :src="getImgUrl(item.image)" class="w-32 h-auto rounded border" />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- SEO Tab -->
            <div v-show="activeTab === 'seo'">
              <h2 class="text-xl font-bold text-slate-800 mb-4">SEO Configuration</h2>
              <div class="grid gap-5">
                <div>
                  <label class="block text-sm font-medium mb-1">Meta Title</label>
                  <input v-model="form.history_page_seo.meta_title" type="text" class="form-input w-full" />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1">Meta Description</label>
                  <textarea v-model="form.history_page_seo.meta_description" rows="3" class="form-textarea w-full"></textarea>
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1">Meta Keywords</label>
                  <input v-model="form.history_page_seo.meta_keywords" type="text" class="form-input w-full" />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1">Meta Author</label>
                  <input v-model="form.history_page_seo.meta_author" type="text" class="form-input w-full" />
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

// Initialize form with defaults if needed
const form = useForm({
  history_page_hero: props.content.history_page_hero || { title: '', bg_image: '', bg_image_file: null },
  history_page_main: props.content.history_page_main || { subtitle: '', title: '', description: '' },
  history_page_timeline: props.content.history_page_timeline || [],
  history_page_seo: props.content.history_page_seo || { meta_title: '', meta_description: '', meta_keywords: '', meta_author: '' }
});

const addTimelineItem = () => {
  form.history_page_timeline.push({
    year: '',
    title: '',
    description: '',
    image: '',
    image_file: null
  });
};

const removeTimelineItem = (index) => {
  form.history_page_timeline.splice(index, 1);
};

const submitForm = () => {
  form.post(route('admin.website-settings.history-content.update'), {
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
