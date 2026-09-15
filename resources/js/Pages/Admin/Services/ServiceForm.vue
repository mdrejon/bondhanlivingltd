<template>
    <div class="space-y-6">

        <!-- ── Basic Info ── -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Basic Information</h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Service Title <span class="text-red-500">*</span></label>
                    <input v-model="form.title" type="text" class="input" placeholder="e.g. Swimming Pool" />
                    <InputError :message="form.errors.title" />
                </div>
                <div>
                    <label class="label">URL Slug <span class="text-gray-400 font-normal text-xs">(auto-generated)</span></label>
                    <div class="flex items-center gap-2">
                        <input :value="slugPreview" type="text" class="input bg-gray-50 text-gray-500 cursor-not-allowed" readonly />
                        <span class="text-xs text-gray-400 whitespace-nowrap">/services/{{ slugPreview || '…' }}</span>
                    </div>
                </div>
                <div>
                    <label class="label">Button Text</label>
                    <input v-model="form.btn_text" type="text" class="input" placeholder="View Services" />
                    <InputError :message="form.errors.btn_text" />
                </div>
                <div>
                    <label class="label">Button URL</label>
                    <input v-model="form.btn_url" type="text" class="input" placeholder="#" />
                    <InputError :message="form.errors.btn_url" />
                </div>
                <div>
                    <label class="label">Sort Order</label>
                    <input v-model.number="form.sort_order" type="number" min="0" class="input" />
                    <InputError :message="form.errors.sort_order" />
                </div>
                <div class="flex items-end gap-6 pb-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input v-model="form.is_active" type="checkbox" class="w-4 h-4 rounded text-blue-600" />
                        <span class="text-sm text-gray-600">Active</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input v-model="form.is_featured" type="checkbox" class="w-4 h-4 rounded text-yellow-500" />
                        <span class="text-sm text-gray-600">Featured on Home Page</span>
                    </label>
                </div>
            </div>
        </section>

        <!-- ── Icon SVG ── -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Service Icon</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="label">Paste SVG Code</label>
                    <textarea v-model="form.icon_svg" rows="6" class="input font-mono text-xs resize-none"
                        placeholder='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" ...></svg>'></textarea>
                    <p class="text-xs text-gray-400 mt-1">Copy the &lt;svg&gt;...&lt;/svg&gt; markup from any icon library</p>
                    <InputError :message="form.errors.icon_svg" />
                </div>
                <div>
                    <label class="label">Preview</label>
                    <div class="w-16 h-16 border rounded-lg flex items-center justify-center bg-gray-50 text-gray-600"
                        v-html="form.icon_svg || defaultIcon">
                    </div>
                    <!-- Common presets -->
                    <p class="text-xs text-gray-500 mt-3 mb-2">Quick presets:</p>
                    <div class="flex flex-wrap gap-2">
                        <button v-for="preset in iconPresets" :key="preset.name" type="button"
                            @click="form.icon_svg = preset.svg"
                            class="px-2 py-1 text-xs border rounded hover:bg-blue-50 hover:border-blue-300 text-gray-600"
                            :title="preset.name">
                            {{ preset.name }}
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- ── Main Image ── -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Card Image (Listing & Home Page)</h2>
            <DropZone @change="file => onFile(file, 'image')" hint="JPEG / PNG / WebP — max 5 MB" preview-class="w-full h-44 object-cover"
                :existing-preview="existing?.image ? '/storage/' + existing.image : null" />
            <InputError :message="form.errors.image" />

            <div>
                <label class="label">Short Description <span class="text-xs text-gray-400">(shown on card)</span></label>
                <textarea v-model="form.short_desc" rows="3" class="input resize-none"
                    placeholder="Brief description shown on the service card listing..."></textarea>
                <InputError :message="form.errors.short_desc" />
            </div>
        </section>

        <!-- ── Detail Page Content ── -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Detail Page — Introduction</h2>
            <div>
                <label class="label">Full Description <span class="text-xs text-gray-400">(rich text, detail page intro)</span></label>
                <RichEditor v-model="form.description" />
                <InputError :message="form.errors.description" />
            </div>
        </section>

        <!-- ── Benefits Section ── -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Detail Page — "We Give The Best Services"</h2>
            <div>
                <label class="label">Section Sub-Title</label>
                <input v-model="form.benefits_title" type="text" class="input" placeholder="We Give The Best Services" />
            </div>
            <div>
                <label class="label">Benefits Text <span class="text-xs text-gray-400">(rich text)</span></label>
                <RichEditor v-model="form.benefits_text" />
                <InputError :message="form.errors.benefits_text" />
            </div>

            <!-- Gallery images -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Gallery Image 1</label>
                    <DropZone @change="file => onFile(file, 'gallery_image_1')" hint="JPEG / PNG / WebP — max 5 MB" preview-class="w-full h-44 object-cover"
                        :existing-preview="existing?.gallery_image_1 ? '/storage/' + existing.gallery_image_1 : null" />
                    <InputError :message="form.errors.gallery_image_1" />
                </div>
                <div>
                    <label class="label">Gallery Image 2</label>
                    <DropZone @change="file => onFile(file, 'gallery_image_2')" hint="JPEG / PNG / WebP — max 5 MB" preview-class="w-full h-44 object-cover"
                        :existing-preview="existing?.gallery_image_2 ? '/storage/' + existing.gallery_image_2 : null" />
                    <InputError :message="form.errors.gallery_image_2" />
                </div>
            </div>
        </section>

        <!-- ── Why Choose This Service ── -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-3">
            <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Why Choose This Service — Feature List</h2>
            <div v-for="(item, i) in form.features" :key="i" class="flex gap-2">
                <input v-model="form.features[i]" type="text" class="input"
                    placeholder="e.g. Rooftop pool with panoramic views" />
                <button type="button" @click="form.features.splice(i, 1)"
                    class="px-3 py-2 text-red-500 hover:bg-red-50 rounded text-sm flex-shrink-0">✕</button>
            </div>
            <button type="button" @click="form.features.push('')"
                class="text-sm text-blue-600 hover:underline">+ Add Feature</button>
        </section>

        <!-- ── FAQ ── -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">FAQ Section</h2>
            <div v-for="(faq, i) in form.faqs" :key="i"
                class="border border-gray-100 rounded-lg p-4 space-y-3 bg-gray-50">
                <div class="flex justify-between items-center">
                    <span class="text-xs font-semibold text-gray-500 uppercase">FAQ {{ i + 1 }}</span>
                    <button type="button" @click="form.faqs.splice(i, 1)"
                        class="text-xs text-red-500 hover:underline">Remove</button>
                </div>
                <div>
                    <label class="label">Question</label>
                    <input v-model="form.faqs[i].question" type="text" class="input"
                        placeholder="e.g. What are the swimming pool timings?" />
                </div>
                <div>
                    <label class="label">Answer</label>
                    <textarea v-model="form.faqs[i].answer" rows="3" class="input resize-none"
                        placeholder="Answer..."></textarea>
                </div>
            </div>
            <button type="button" @click="form.faqs.push({ question: '', answer: '' })"
                class="text-sm text-blue-600 hover:underline">+ Add FAQ</button>
        </section>

    </div>
</template>

<script setup>
import { reactive, computed } from 'vue';
import InputError from '@/Components/InputError.vue';
import RichEditor from '@/Components/Admin/Shared/RichEditor.vue';
import DropZone from '@/Components/Admin/Shared/DropZone.vue';

const props = defineProps({
    form:     { type: Object, required: true },
    existing: { type: Object, default: null },
});

const emit = defineEmits(['image-change']);

const slugPreview = computed(() => {
    return (props.form.title || '')
        .toLowerCase()
        .trim()
        .replace(/[^\w\s-]/g, '')
        .replace(/[\s_-]+/g, '-')
        .replace(/^-+|-+$/g, '');
});

function onFile(file, field) {
    if (!file) return;
    emit('image-change', { field, file });
}

const defaultIcon = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`;

const iconPresets = [
    {
        name: 'Gym',
        svg: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6.5 6.5h11M6.5 6.5a2.5 2.5 0 000 5h11a2.5 2.5 0 000-5"/><path d="M6.5 11.5v5a1 1 0 001 1h9a1 1 0 001-1v-5"/><line x1="9" y1="6.5" x2="9" y2="4"/><line x1="15" y1="6.5" x2="15" y2="4"/></svg>`,
    },
    {
        name: 'Spa',
        svg: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a5 5 0 015 5c0 2.5-1.5 4.5-3.5 5.5S9 14 9 17h6"/><path d="M9 17h6v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/><line x1="12" y1="2" x2="12" y2="4"/></svg>`,
    },
    {
        name: 'Pool',
        svg: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M2 16c2.5 0 2.5 2 5 2s2.5-2 5-2 2.5 2 5 2 2.5-2 5-2"/><path d="M2 20c2.5 0 2.5 2 5 2s2.5-2 5-2 2.5 2 5 2 2.5-2 5-2"/><path d="M8 10V6a4 4 0 018 0v4"/><line x1="8" y1="10" x2="16" y2="10"/></svg>`,
    },
    {
        name: 'Restaurant',
        svg: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 010 8h-1"/><path d="M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>`,
    },
    {
        name: 'Car',
        svg: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>`,
    },
    {
        name: 'Jogging',
        svg: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="13" cy="4" r="2"/><path d="M5 21l5.5-9L13 15l3-4.5 3 4.5"/><path d="M9 8l2 4h5"/></svg>`,
    },
    {
        name: 'Conference',
        svg: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/></svg>`,
    },
    {
        name: 'WiFi',
        svg: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.55a11 11 0 0114.08 0"/><path d="M1.42 9a16 16 0 0121.16 0"/><path d="M8.53 16.11a6 6 0 016.95 0"/><circle cx="12" cy="20" r="1" fill="currentColor"/></svg>`,
    },
];
</script>

<style scoped>
.label  { @apply block text-sm text-gray-600 mb-1; }
.input  { @apply w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none; }
</style>
