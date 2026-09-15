<template>
    <AdminLayout>
        <div class="max-w-4xl space-y-4">
            <h1 class="text-lg font-semibold text-gray-800">About Section Settings</h1>

            <div v-if="$page.props.flash?.success"
                class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded">
                {{ $page.props.flash.success }}
            </div>

            <form @submit.prevent="submit" class="space-y-6">

                <!-- ─── Page Hero / Breadcrumb ─── -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Page Hero &amp; Breadcrumb</h2>

                    <div>
                        <label class="label">Page Title <span class="text-xs text-gray-400">(shown in breadcrumb hero area)</span></label>
                        <input v-model="form.about_hero_title" type="text" class="input" placeholder="About Us" />
                        <InputError :message="form.errors.about_hero_title" />
                    </div>

                    <div>
                        <label class="label">Hero Background Image</label>
                        <DropZone @change="file => onImage(file, 'hero')" hint="Recommended: 1920×600px. JPEG / PNG / WebP" preview-class="w-full h-44 object-cover"
                            :existing-preview="currentHeroImage ? '/storage/' + currentHeroImage : null" />
                        <InputError :message="form.errors.about_hero_image" />
                    </div>
                </section>

                <!-- ─── SEO Configuration ─── -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">SEO Configuration</h2>

                    <div>
                        <label class="label">Meta Title <span class="text-xs text-gray-400">(max 160 chars — shown in browser tab &amp; search results)</span></label>
                        <input v-model="form.about_seo_title" @input="onMetaTitleInput" type="text" class="input"
                            placeholder="About Us – Hotel Beach Way, Cox's Bazar" maxlength="160" />
                        <p class="text-xs text-gray-400 mt-1">{{ (form.about_seo_title || '').length }}/160</p>
                        <InputError :message="form.errors.about_seo_title" />
                    </div>

                    <div>
                        <label class="label">Meta Description <span class="text-xs text-gray-400">(max 320 chars)</span></label>
                        <textarea v-model="form.about_seo_description" @input="onMetaDescInput" rows="3" class="input resize-none"
                            placeholder="Learn about Hotel Beach Way — a boutique hotel near Kolatoli Beach, Cox's Bazar." maxlength="320"></textarea>
                        <p class="text-xs text-gray-400 mt-1">{{ (form.about_seo_description || '').length }}/320</p>
                        <InputError :message="form.errors.about_seo_description" />
                    </div>

                    <div>
                        <label class="label">Meta Keywords <span class="text-xs text-gray-400">(comma-separated)</span></label>
                        <input v-model="form.about_seo_keywords" @input="onMetaKeywordsInput" type="text" class="input"
                            placeholder="hotel beach way, cox's bazar hotel, about us, boutique hotel" />
                        <InputError :message="form.errors.about_seo_keywords" />
                    </div>

                    <div>
                        <label class="label">OG / Social Share Image <span class="text-xs text-gray-400">(recommended 1200×630px)</span></label>
                        <DropZone @change="file => onImage(file, 'og')" hint="JPEG / PNG / WebP — max 5 MB" preview-class="w-full h-36 object-cover"
                            :existing-preview="currentOgImage ? '/storage/' + currentOgImage : null" />
                        <InputError :message="form.errors.about_seo_og_image" />
                    </div>
                </section>

                <!-- ─── Floating Deco Badges ─── -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Floating Deco Badges</h2>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Top-Left Badge (e.g. "24/7 Front Desk")</p>
                            <div>
                                <label class="label">Badge Value</label>
                                <input v-model="form.about_deco_badge_value" type="text" class="input" placeholder="24/7" />
                                <InputError :message="form.errors.about_deco_badge_value" />
                            </div>
                            <div>
                                <label class="label">Badge Label</label>
                                <input v-model="form.about_deco_badge_label" type="text" class="input" placeholder="Front Desk" />
                                <InputError :message="form.errors.about_deco_badge_label" />
                            </div>
                        </div>
                        <div class="space-y-3">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Bottom-Right Years Badge</p>
                            <div>
                                <label class="label">Years Value</label>
                                <input v-model="form.about_deco_years_value" type="text" class="input" placeholder="13+" />
                                <InputError :message="form.errors.about_deco_years_value" />
                            </div>
                            <div>
                                <label class="label">Years Label</label>
                                <input v-model="form.about_deco_years_label" type="text" class="input" placeholder="Years of Trusted Service" />
                                <InputError :message="form.errors.about_deco_years_label" />
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ─── Left Column ─── -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Left Column</h2>

                    <div>
                        <label class="label">Section Badge Text</label>
                        <input v-model="form.about_badge" type="text" class="input" placeholder="OUR LUXURY RESORTS" />
                        <InputError :message="form.errors.about_badge" />
                    </div>

                    <div>
                        <label class="label">
                            Main Title
                            <span class="text-xs text-gray-400 ml-1">(use &lt;br&gt; for line breaks)</span>
                        </label>
                        <input v-model="form.about_title" type="text" class="input"
                            placeholder="3-Star Boutique Hotel&lt;br&gt;Near Kolatoli Beach" />
                        <InputError :message="form.errors.about_title" />
                    </div>

                    <div>
                        <label class="label">Description</label>
                        <RichEditor v-model="form.about_desc" />
                        <InputError :message="form.errors.about_desc" />
                    </div>

                    <div>
                        <label class="label">Main Image (Left)</label>
                        <DropZone @change="file => onImage(file, 'main')" hint="JPEG / PNG / WebP — max 5 MB" preview-class="w-full h-44 object-cover"
                            :existing-preview="currentMainImage ? '/storage/' + currentMainImage : null" />
                        <InputError :message="form.errors.about_main_image" />
                    </div>
                    <div>
                        <label class="label">Main Image Alt Text</label>
                        <input v-model="form.about_main_image_alt" type="text" class="input"
                            placeholder="Hotel guests relaxing outdoors" />
                        <InputError :message="form.errors.about_main_image_alt" />
                    </div>
                </section>

                <!-- ─── Right Column ─── -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Right Column — Top Image</h2>

                    <div>
                        <label class="label">Top Image (Right)</label>
                        <DropZone @change="file => onImage(file, 'top')" hint="JPEG / PNG / WebP — max 5 MB" preview-class="w-full h-44 object-cover"
                            :existing-preview="currentTopImage ? '/storage/' + currentTopImage : null" />
                        <InputError :message="form.errors.about_top_image" />
                    </div>
                    <div>
                        <label class="label">Top Image Alt Text</label>
                        <input v-model="form.about_top_image_alt" type="text" class="input"
                            placeholder="Scenic lake view at sunset" />
                        <InputError :message="form.errors.about_top_image_alt" />
                    </div>
                </section>

                <!-- ─── Tabs ─── -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-6">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Tabs Content</h2>

                    <!-- Mission -->
                    <div class="space-y-3">
                        <h3 class="text-xs font-semibold text-blue-600 uppercase tracking-wide">Tab 1 — Mission</h3>
                        <div>
                            <label class="label">Tab Button Label</label>
                            <input v-model="form.about_mission_label" type="text" class="input" placeholder="Our Mission" />
                        </div>
                        <div>
                            <label class="label">Tab Content Text</label>
                            <RichEditor v-model="form.about_mission_text" />
                            <InputError :message="form.errors.about_mission_text" />
                        </div>
                        <div>
                            <label class="label">Feature List Items</label>
                            <div v-for="(item, i) in form.about_mission_features" :key="i" class="flex gap-2 mb-2">
                                <input v-model="form.about_mission_features[i]" type="text" class="input"
                                    placeholder="e.g. Free Wi-Fi in All Rooms" />
                                <button type="button" @click="removeFeature('mission', i)"
                                    class="px-3 py-2 text-red-500 hover:bg-red-50 rounded text-sm">✕</button>
                            </div>
                            <button type="button" @click="addFeature('mission')"
                                class="mt-1 text-sm text-blue-600 hover:underline">+ Add Feature</button>
                        </div>
                    </div>

                    <hr class="border-gray-100" />

                    <!-- Vision -->
                    <div class="space-y-3">
                        <h3 class="text-xs font-semibold text-blue-600 uppercase tracking-wide">Tab 2 — Vision</h3>
                        <div>
                            <label class="label">Tab Button Label</label>
                            <input v-model="form.about_vision_label" type="text" class="input" placeholder="Our Vision" />
                        </div>
                        <div>
                            <label class="label">Tab Content Text</label>
                            <RichEditor v-model="form.about_vision_text" />
                            <InputError :message="form.errors.about_vision_text" />
                        </div>
                        <div>
                            <label class="label">Feature List Items</label>
                            <div v-for="(item, i) in form.about_vision_features" :key="i" class="flex gap-2 mb-2">
                                <input v-model="form.about_vision_features[i]" type="text" class="input"
                                    placeholder="e.g. Eco-Friendly Practices" />
                                <button type="button" @click="removeFeature('vision', i)"
                                    class="px-3 py-2 text-red-500 hover:bg-red-50 rounded text-sm">✕</button>
                            </div>
                            <button type="button" @click="addFeature('vision')"
                                class="mt-1 text-sm text-blue-600 hover:underline">+ Add Feature</button>
                        </div>
                    </div>

                    <hr class="border-gray-100" />

                    <!-- History -->
                    <div class="space-y-3">
                        <h3 class="text-xs font-semibold text-blue-600 uppercase tracking-wide">Tab 3 — History</h3>
                        <div>
                            <label class="label">Tab Button Label</label>
                            <input v-model="form.about_history_label" type="text" class="input" placeholder="Our History" />
                        </div>
                        <div>
                            <label class="label">Tab Content Text</label>
                            <RichEditor v-model="form.about_history_text" />
                            <InputError :message="form.errors.about_history_text" />
                        </div>
                        <div>
                            <label class="label">Feature List Items</label>
                            <div v-for="(item, i) in form.about_history_features" :key="i" class="flex gap-2 mb-2">
                                <input v-model="form.about_history_features[i]" type="text" class="input"
                                    placeholder="e.g. Established in 2013" />
                                <button type="button" @click="removeFeature('history', i)"
                                    class="px-3 py-2 text-red-500 hover:bg-red-50 rounded text-sm">✕</button>
                            </div>
                            <button type="button" @click="addFeature('history')"
                                class="mt-1 text-sm text-blue-600 hover:underline">+ Add Feature</button>
                        </div>
                    </div>
                </section>

                <!-- ─── CTA Button ─── -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">"More About" Button</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Button Text</label>
                            <input v-model="form.about_more_btn_text" type="text" class="input" placeholder="More About" />
                            <InputError :message="form.errors.about_more_btn_text" />
                        </div>
                        <div>
                            <label class="label">Button URL</label>
                            <input v-model="form.about_more_btn_url" type="text" class="input" placeholder="#contact" />
                            <InputError :message="form.errors.about_more_btn_url" />
                        </div>
                    </div>
                </section>

                <!-- ─── Stats Counter ─── -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <div class="flex items-center justify-between border-b pb-2">
                        <h2 class="text-sm font-semibold text-gray-700">Stats Counter Bar</h2>
                        <span class="text-xs text-gray-400">e.g. 150+ Happy Customer</span>
                    </div>

                    <!-- Preview strip -->
                    <div class="bg-emerald-900 rounded-xl px-6 py-4 flex divide-x divide-white/20">
                        <div v-for="(stat, i) in form.about_stats" :key="i"
                            class="flex-1 text-center px-4">
                            <div class="text-2xl font-bold text-white">
                                {{ stat.value || '0' }}<sup class="text-sm">{{ stat.suffix || '+' }}</sup>
                            </div>
                            <div class="text-xs text-white/70 mt-1">{{ stat.label || 'Label' }}</div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div v-for="(stat, i) in form.about_stats" :key="i"
                            class="grid grid-cols-12 gap-3 items-center border border-gray-100 rounded-lg p-3 bg-gray-50">
                            <div class="col-span-1 text-center">
                                <span class="w-6 h-6 bg-emerald-100 text-emerald-700 text-xs rounded-full inline-flex items-center justify-center font-semibold">
                                    {{ i + 1 }}
                                </span>
                            </div>
                            <div class="col-span-3">
                                <label class="label text-xs">Number</label>
                                <input v-model="stat.value" type="text" class="input" placeholder="150" />
                            </div>
                            <div class="col-span-2">
                                <label class="label text-xs">Suffix</label>
                                <input v-model="stat.suffix" type="text" class="input" placeholder="+" />
                            </div>
                            <div class="col-span-5">
                                <label class="label text-xs">Label</label>
                                <input v-model="stat.label" type="text" class="input" placeholder="Happy Customer" />
                            </div>
                            <div class="col-span-1 flex justify-end pt-5">
                                <button type="button" @click="removeStat(i)"
                                    class="w-7 h-7 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 rounded text-sm">✕</button>
                            </div>
                        </div>
                    </div>

                    <button type="button" @click="addStat"
                        class="w-full py-2.5 border-2 border-dashed border-gray-300 rounded-lg text-sm text-blue-600 hover:border-blue-400 hover:bg-blue-50 transition-colors flex items-center justify-center gap-2">
                        <span class="text-lg leading-none">+</span> Add Stat Item
                    </button>
                </section>

                <!-- ─── Video Section ─── -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Video Section</h2>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Thumbnail Image</label>
                            <DropZone @change="file => onImage(file, 'video')" hint="JPEG / PNG / WebP — max 5 MB" preview-class="w-full h-44 object-cover"
                                :existing-preview="currentVideoThumb ? '/storage/' + currentVideoThumb : null" />
                            <InputError :message="form.errors.about_video_thumb" />
                        </div>
                        <div>
                            <label class="label">Video URL</label>
                            <input v-model="form.about_video_url" type="text" class="input"
                                placeholder="https://youtube.com/watch?v=..." />
                            <p class="text-xs text-gray-400 mt-1">YouTube, Vimeo, or direct MP4 link</p>
                            <InputError :message="form.errors.about_video_url" />
                        </div>
                    </div>
                </section>

                <!-- ─── Why Choose Us ─── -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Why Choose Us Section</h2>

                    <!-- Header -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Badge Text</label>
                            <input v-model="form.why_badge" type="text" class="input" placeholder="WHY CHOOSE US" />
                            <InputError :message="form.errors.why_badge" />
                        </div>
                        <div>
                            <label class="label">Section Title</label>
                            <input v-model="form.why_title" type="text" class="input"
                                placeholder="The Hotel Beach Way Difference" />
                            <InputError :message="form.errors.why_title" />
                        </div>
                        <div class="col-span-2">
                            <label class="label">Section Description</label>
                            <textarea v-model="form.why_desc" rows="2" class="input resize-none"
                                placeholder="We combine world-class hospitality with genuine warmth..."></textarea>
                            <InputError :message="form.errors.why_desc" />
                        </div>
                    </div>

                    <!-- Feature cards -->
                    <div class="border-t pt-4 space-y-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Feature Cards</p>

                        <div v-for="(feat, i) in form.why_features" :key="i"
                            class="border border-gray-200 rounded-lg overflow-hidden">

                            <!-- Card header -->
                            <div class="flex items-center justify-between bg-gray-50 px-4 py-2 border-b border-gray-200">
                                <span class="text-xs font-semibold text-gray-600">Card {{ i + 1 }}</span>
                                <button type="button" @click="removeWhyFeature(i)"
                                    class="w-6 h-6 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 rounded text-xs">✕</button>
                            </div>

                            <!-- Card fields -->
                            <div class="p-4 space-y-3">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="label text-xs">Card Title</label>
                                        <input v-model="feat.title" type="text" class="input"
                                            placeholder="Keeping You Safe" />
                                    </div>
                                    <div class="row-span-2">
                                        <label class="label text-xs">Icon SVG</label>
                                        <div v-if="feat.icon_svg" class="mb-1 flex items-center gap-2">
                                            <div class="w-8 h-8 flex items-center justify-center text-gray-600"
                                                v-html="feat.icon_svg"></div>
                                            <span class="text-xs text-gray-400">Preview</span>
                                        </div>
                                        <textarea v-model="feat.icon_svg" rows="4" class="input resize-none font-mono text-xs"
                                            placeholder="<svg xmlns=...>...</svg>"></textarea>
                                    </div>
                                    <div>
                                        <label class="label text-xs">Description</label>
                                        <textarea v-model="feat.description" rows="3" class="input resize-none"
                                            placeholder="24/7 CCTV security surveillance throughout the hotel..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" @click="addWhyFeature"
                            class="w-full py-2.5 border-2 border-dashed border-gray-300 rounded-lg text-sm text-blue-600 hover:border-blue-400 hover:bg-blue-50 transition-colors flex items-center justify-center gap-2">
                            <span class="text-lg leading-none">+</span> Add Feature Card
                        </button>
                    </div>
                </section>

                <!-- ─── FAQ Section (About Page) ─── -->
                <div class="flex justify-end">
                    <button type="submit" :disabled="form.processing"
                        class="px-6 py-2.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-60">
                        {{ form.processing ? 'Saving...' : 'Save About Settings' }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import InputError   from '@/Components/InputError.vue';
import RichEditor   from '@/Components/Admin/Shared/RichEditor.vue';
import DropZone from '@/Components/Admin/Shared/DropZone.vue';
import { useSeoAutoFill } from '@/Composables/useSeoAutoFill';

const props = defineProps({
    settings: { type: Object, required: true },
});

const s = props.settings;

const currentHeroImage  = ref(s.about_hero_image);
const currentOgImage    = ref(s.about_seo_og_image);
const currentMainImage  = ref(s.about_main_image);
const currentTopImage   = ref(s.about_top_image);
const currentVideoThumb = ref(s.about_video_thumb);

const defaultStats = [
    { value: '150', suffix: '+', label: 'Happy Customer' },
    { value: '42',  suffix: '+', label: 'Special Rooms' },
    { value: '35',  suffix: '+', label: 'Expert Teams' },
    { value: '10',  suffix: '+', label: 'Awards Win' },
];

const form = useForm({
    // Hero / breadcrumb
    about_hero_image:         null,
    about_hero_title:         s.about_hero_title         ?? 'About Us',
    // SEO
    about_seo_title:          s.about_seo_title          ?? '',
    about_seo_description:    s.about_seo_description    ?? '',
    about_seo_keywords:       s.about_seo_keywords       ?? '',
    about_seo_og_image:       null,

    about_deco_badge_value:   s.about_deco_badge_value   ?? '24/7',
    about_deco_badge_label:   s.about_deco_badge_label   ?? 'Front Desk',
    about_deco_years_value:   s.about_deco_years_value   ?? '13+',
    about_deco_years_label:   s.about_deco_years_label   ?? 'Years of Trusted Service',

    about_badge:              s.about_badge              ?? 'OUR LUXURY RESORTS',
    about_title:              s.about_title              ?? '',
    about_desc:               s.about_desc               ?? '',
    about_main_image:         null,
    about_main_image_alt:     s.about_main_image_alt     ?? '',
    about_top_image:          null,
    about_top_image_alt:      s.about_top_image_alt      ?? '',

    about_mission_label:      s.about_mission_label      ?? 'Our Mission',
    about_mission_text:       s.about_mission_text       ?? '',
    about_mission_features:   Array.isArray(s.about_mission_features) ? [...s.about_mission_features] : [],

    about_vision_label:       s.about_vision_label       ?? 'Our Vision',
    about_vision_text:        s.about_vision_text        ?? '',
    about_vision_features:    Array.isArray(s.about_vision_features)  ? [...s.about_vision_features]  : [],

    about_history_label:      s.about_history_label      ?? 'Our History',
    about_history_text:       s.about_history_text       ?? '',
    about_history_features:   Array.isArray(s.about_history_features) ? [...s.about_history_features] : [],

    about_more_btn_text:      s.about_more_btn_text      ?? 'More About',
    about_more_btn_url:       s.about_more_btn_url       ?? '#contact',

    // Stats
    about_stats: Array.isArray(s.about_stats) && s.about_stats.length
        ? s.about_stats.map(st => ({ ...st }))
        : defaultStats.map(st => ({ ...st })),

    // Video
    about_video_thumb: null,
    about_video_url:   s.about_video_url ?? '',

    // Why Choose Us
    why_badge:     s.why_badge ?? 'WHY CHOOSE US',
    why_title:     s.why_title ?? 'The Hotel Beach Way Difference',
    why_desc:      s.why_desc  ?? '',
    why_features:  Array.isArray(s.why_features)
        ? s.why_features.map(f => ({ ...f }))
        : [],

});

const { onMetaTitleInput, onMetaDescInput, onMetaKeywordsInput } = useSeoAutoFill(form, {
    titleSource: () => form.about_title,
    descSource:  () => form.about_desc,
    titleKey:    'about_seo_title',
    descKey:     'about_seo_description',
    keywordsKey: 'about_seo_keywords',
});

function onImage(file, type) {
    if (!file) return;
    if (type === 'hero') {
        form.about_hero_image = file;
    } else if (type === 'og') {
        form.about_seo_og_image = file;
    } else if (type === 'main') {
        form.about_main_image = file;
    } else if (type === 'top') {
        form.about_top_image  = file;
    } else {
        form.about_video_thumb  = file;
    }
}

// Stats
function addStat()       { form.about_stats.push({ value: '', suffix: '+', label: '' }); }
function removeStat(i)   { form.about_stats.splice(i, 1); }

// Tabs features
function addFeature(tab)        { form[`about_${tab}_features`].push(''); }
function removeFeature(tab, i)  { form[`about_${tab}_features`].splice(i, 1); }

// Why Choose Us
function addWhyFeature()      { form.why_features.push({ icon_svg: '', title: '', description: '' }); }
function removeWhyFeature(i)  { form.why_features.splice(i, 1); }

function submit() {
    form.post(route('admin.website-settings.about.update'), {
        forceFormData: true,
    });
}
</script>

<style scoped>
.label { @apply block text-sm text-gray-600 mb-1; }
.input { @apply w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none; }
</style>
