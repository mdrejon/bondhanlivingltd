<template>
    <AdminLayout>
        <div class="space-y-4">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-gray-800">FAQ Management</h1>
                    <p class="text-xs text-gray-400 mt-0.5">Manage FAQ groups and configure the FAQ page appearance</p>
                </div>
                <a :href="route('admin.faqs.create')"
                    class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors">
                    + Add FAQ Group
                </a>
            </div>

            <!-- Flash -->
            <div v-if="$page.props.flash?.success"
                class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded">
                {{ $page.props.flash.success }}
            </div>

            <!-- ── FAQ Page Settings (collapsible) ── -->
            <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
                <button type="button" @click="pageSettingsOpen = !pageSettingsOpen"
                    class="w-full flex items-center justify-between px-6 py-4 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                        FAQ Page Settings (Hero &amp; SEO)
                    </span>
                    <svg class="w-4 h-4 text-gray-400 transition-transform" :class="pageSettingsOpen ? 'rotate-180' : ''"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>

                <div v-show="pageSettingsOpen" class="border-t border-gray-100 p-6 space-y-6">
                    <form @submit.prevent="savePageSettings" class="space-y-6">

                        <!-- Page Hero -->
                        <div>
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Page Hero &amp; Breadcrumb</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="col-span-2 md:col-span-1">
                                    <label class="label">Hero Background Image</label>
                                    <DropZone @change="file => onImg('hero', file)" hint="JPEG / PNG / WebP — max 5 MB" preview-class="w-full h-44 object-cover"
                                        :existing-preview="faqSettings.faq_hero_image ? '/storage/' + faqSettings.faq_hero_image : null" />
                                </div>
                                <div class="col-span-2 md:col-span-1">
                                    <label class="label">Page Title (Breadcrumb)</label>
                                    <input v-model="pageForm.faq_hero_title" type="text" class="input"
                                        placeholder="Frequently Asked Questions" />
                                </div>
                            </div>
                        </div>

                        <!-- SEO -->
                        <div class="border-t pt-4">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">SEO Configuration</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="col-span-2">
                                    <label class="label">Meta Title <span class="text-gray-400 font-normal">({{ pageForm.faq_seo_title.length }}/160)</span></label>
                                    <input v-model="pageForm.faq_seo_title" type="text" maxlength="160" class="input"
                                        placeholder="FAQs – Hotel Beach Way" />
                                </div>
                                <div class="col-span-2">
                                    <label class="label">Meta Description <span class="text-gray-400 font-normal">({{ pageForm.faq_seo_description.length }}/320)</span></label>
                                    <textarea v-model="pageForm.faq_seo_description" rows="2" maxlength="320"
                                        class="input resize-none"
                                        placeholder="Find answers to frequently asked questions about Hotel Beach Way..."></textarea>
                                </div>
                                <div class="col-span-2 md:col-span-1">
                                    <label class="label">Keywords</label>
                                    <input v-model="pageForm.faq_seo_keywords" type="text" class="input"
                                        placeholder="hotel faq, cox's bazar hotel questions" />
                                </div>
                                <div class="col-span-2 md:col-span-1">
                                    <label class="label">OG / Social Share Image</label>
                                    <DropZone @change="file => onImg('og', file)" hint="JPEG / PNG / WebP — max 5 MB" preview-class="w-full h-36 object-cover"
                                        :existing-preview="faqSettings.faq_seo_og_image ? '/storage/' + faqSettings.faq_seo_og_image : null" />
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end border-t pt-4">
                            <button type="submit" :disabled="pageForm.processing"
                                class="px-5 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-60">
                                {{ pageForm.processing ? 'Saving...' : 'Save Page Settings' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabs -->
            <div class="border-b border-gray-200">
                <nav class="flex gap-0">
                    <button v-for="tab in tabs" :key="tab.key"
                        @click="activeTab = tab.key"
                        :class="[
                            'px-6 py-2.5 text-sm font-medium border-b-2 transition-colors -mb-px',
                            activeTab === tab.key
                                ? 'border-blue-600 text-blue-600'
                                : 'border-transparent text-gray-500 hover:text-gray-700'
                        ]">
                        {{ tab.label }}
                        <span class="ml-1.5 px-1.5 py-0.5 rounded-full text-xs"
                            :class="activeTab === tab.key ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500'">
                            {{ tab.key === 'home' ? homeFaqs.length : tab.key === 'about' ? aboutFaqs.length : faqFaqs.length }}
                        </span>
                    </button>
                </nav>
            </div>

            <!-- FAQ Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-4 py-3 text-gray-500 font-medium w-8">#</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Title</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">FAQ Items</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium w-16">Order</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium w-24">Status</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium w-32">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(faq, i) in currentFaqs" :key="faq.id">
                            <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-gray-400">{{ i + 1 }}</td>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-gray-800 truncate max-w-[220px]">
                                        {{ faq.title || '(no title)' }}
                                    </p>
                                    <span class="inline-block mt-0.5 px-1.5 py-0.5 bg-gray-100 text-gray-500 text-xs rounded">
                                        {{ faq.badge }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <!-- Accordion preview -->
                                    <div class="space-y-1">
                                        <div v-for="(item, j) in faq.items.slice(0, 3)" :key="j"
                                            class="text-xs text-gray-600 bg-gray-50 rounded px-2 py-1 truncate max-w-[320px]">
                                            <span class="font-medium text-gray-700">Q{{ j + 1 }}:</span>
                                            {{ item.question }}
                                        </div>
                                        <div v-if="faq.items.length > 3" class="text-xs text-gray-400 px-2">
                                            + {{ faq.items.length - 3 }} more
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-gray-500 text-center">{{ faq.sort_order }}</td>
                                <td class="px-4 py-3">
                                    <button @click="toggleStatus(faq)"
                                        :class="faq.is_active
                                            ? 'bg-green-100 text-green-700 hover:bg-green-200'
                                            : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                                        class="px-2.5 py-0.5 rounded-full text-xs font-semibold transition-colors">
                                        {{ faq.is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <a :href="route('admin.faqs.edit', faq.id)"
                                            class="px-3 py-1.5 bg-indigo-600 text-white text-xs rounded hover:bg-indigo-700">
                                            Edit
                                        </a>
                                        <button @click="confirmDelete(faq)"
                                            class="px-3 py-1.5 bg-red-600 text-white text-xs rounded hover:bg-red-700">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <tr v-if="!currentFaqs.length">
                            <td colspan="6" class="px-4 py-10 text-center text-gray-400">
                                No FAQ groups for this page yet.
                                <a :href="route('admin.faqs.create')" class="text-blue-600 hover:underline ml-1">Add one</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Delete modal -->
        <div v-if="deleteTarget" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-80 shadow-xl">
                <h3 class="font-semibold text-gray-800 mb-2">Delete FAQ Group</h3>
                <p class="text-sm text-gray-600 mb-1">
                    Delete "<strong>{{ deleteTarget.title || 'this FAQ group' }}</strong>"?
                </p>
                <p class="text-xs text-gray-400 mb-4">
                    This will remove all {{ deleteTarget.items?.length }} FAQ items inside it.
                </p>
                <div class="flex justify-end gap-2">
                    <button @click="deleteTarget = null"
                        class="px-4 py-2 text-sm text-gray-600 border rounded hover:bg-gray-50">Cancel</button>
                    <button @click="doDelete"
                        class="px-4 py-2 text-sm bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import DropZone from '@/Components/Admin/Shared/DropZone.vue';

const props = defineProps({
    homeFaqs:    { type: Array,  default: () => [] },
    aboutFaqs:   { type: Array,  default: () => [] },
    faqFaqs:     { type: Array,  default: () => [] },
    faqSettings: { type: Object, default: () => ({}) },
});

// ── Page settings panel ──
const pageSettingsOpen = ref(false);

const pageForm = useForm({
    faq_hero_title:      props.faqSettings.faq_hero_title      ?? '',
    faq_seo_title:       props.faqSettings.faq_seo_title       ?? '',
    faq_seo_description: props.faqSettings.faq_seo_description ?? '',
    faq_seo_keywords:    props.faqSettings.faq_seo_keywords    ?? '',
    faq_hero_image:      null,
    faq_seo_og_image:    null,
});

function onImg(type, file) {
    if (!file) return;
    if (type === 'hero') { pageForm.faq_hero_image   = file; }
    if (type === 'og')   { pageForm.faq_seo_og_image = file; }
}

function savePageSettings() {
    pageForm.transform(data => ({ ...data, _method: 'PUT' }))
        .post(route('admin.website-settings.faq-page.update'), { forceFormData: true });
}

// ── FAQ tabs ──
const tabs = [
    { key: 'home',  label: 'Home Page' },
    { key: 'about', label: 'About Page' },
    { key: 'faq',   label: 'FAQ Page' },
];

const activeTab = ref('home');

const currentFaqs = computed(() => {
    if (activeTab.value === 'home')  return props.homeFaqs;
    if (activeTab.value === 'about') return props.aboutFaqs;
    return props.faqFaqs;
});

// ── Delete ──
const deleteTarget = ref(null);

function confirmDelete(faq) { deleteTarget.value = faq; }

function doDelete() {
    router.delete(route('admin.faqs.destroy', deleteTarget.value.id), {
        onFinish: () => { deleteTarget.value = null; },
    });
}

function toggleStatus(faq) {
    router.patch(route('admin.faqs.toggle', faq.id));
}
</script>

<style scoped>
.label { @apply block text-sm text-gray-600 mb-1; }
.input { @apply w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none; }
</style>
