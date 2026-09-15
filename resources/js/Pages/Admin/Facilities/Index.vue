<template>
    <AdminLayout>
        <div class="max-w-5xl space-y-6">
            <h1 class="text-lg font-semibold text-gray-800">Facilities Management</h1>

            <!-- Flash -->
            <div v-if="$page.props.flash?.success"
                class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded">
                {{ $page.props.flash.success }}
            </div>

            <!-- ── Section Settings ── -->
            <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Section Settings</h2>
                <form @submit.prevent="saveSettings" class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">Badge Text</label>
                        <input v-model="settingsForm.fac_badge" type="text" class="input" placeholder="FACILITIES" />
                    </div>
                    <div>
                        <label class="label">Section Title</label>
                        <input v-model="settingsForm.fac_title" type="text" class="input" placeholder="Hotel's Facilities" />
                    </div>
                    <div class="col-span-2 flex justify-end">
                        <button type="submit" :disabled="settingsForm.processing"
                            class="px-5 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-60">
                            {{ settingsForm.processing ? 'Saving...' : 'Save Settings' }}
                        </button>
                    </div>
                </form>
            </section>

            <!-- ── Page Hero & Breadcrumb ── -->
            <section class="bg-white rounded-lg shadow-sm p-6 space-y-5">
                <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Page Hero &amp; Breadcrumb</h2>
                <form @submit.prevent="savePageSettings" enctype="multipart/form-data" class="space-y-5">
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Hero BG Image -->
                        <div class="col-span-2 md:col-span-1">
                            <label class="label">Hero Background Image</label>
                            <DropZone @change="onHeroImg" hint="JPEG / PNG / WebP — max 5 MB" preview-class="w-full h-44 object-cover"
                                :existing-preview="currentHeroImg ? '/storage/' + currentHeroImg : null" />
                        </div>
                        <!-- Hero Title -->
                        <div class="col-span-2 md:col-span-1">
                            <label class="label">Page Title (Breadcrumb)</label>
                            <input v-model="pageForm.fac_hero_title" type="text" class="input" placeholder="Our Facilities" />
                        </div>
                    </div>

                    <!-- SEO Configuration -->
                    <div class="border-t pt-4 space-y-4">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide">SEO Configuration</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="label">Meta Title <span class="text-gray-400 font-normal">({{ pageForm.fac_seo_title.length }}/160)</span></label>
                                <input v-model="pageForm.fac_seo_title" type="text" maxlength="160" class="input" placeholder="Facilities – Hotel Beach Way" />
                            </div>
                            <div class="col-span-2">
                                <label class="label">Meta Description <span class="text-gray-400 font-normal">({{ pageForm.fac_seo_description.length }}/320)</span></label>
                                <textarea v-model="pageForm.fac_seo_description" rows="3" maxlength="320" class="input resize-none" placeholder="Explore the facilities at Hotel Beach Way..."></textarea>
                            </div>
                            <div class="col-span-2">
                                <label class="label">Keywords</label>
                                <input v-model="pageForm.fac_seo_keywords" type="text" class="input" placeholder="hotel facilities, beach way, cox's bazar amenities" />
                            </div>
                            <!-- OG Image -->
                            <div class="col-span-2 md:col-span-1">
                                <label class="label">OG / Social Share Image</label>
                                <DropZone @change="onOgImg" hint="JPEG / PNG / WebP — max 5 MB" preview-class="w-full h-36 object-cover"
                                    :existing-preview="currentOgImg ? '/storage/' + currentOgImg : null" />
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" :disabled="pageForm.processing"
                            class="px-5 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-60">
                            {{ pageForm.processing ? 'Saving...' : 'Save Page Settings' }}
                        </button>
                    </div>
                </form>
            </section>

            <!-- ── Add / Edit Facility ── -->
            <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">
                    {{ editingId ? 'Edit Facility Card' : 'Add New Facility Card' }}
                </h2>
                <form @submit.prevent="submitFacility" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Title -->
                        <div>
                            <label class="label">Card Title <span class="text-red-500">*</span></label>
                            <input v-model="form.title" type="text" class="input" placeholder="Basic Facilities" />
                            <InputError :message="form.errors.title" />
                        </div>
                        <!-- Sort + Active -->
                        <div class="flex items-end gap-4">
                            <div class="flex-1">
                                <label class="label">Sort Order</label>
                                <input v-model.number="form.sort_order" type="number" min="0" class="input" />
                            </div>
                            <label class="flex items-center gap-2 cursor-pointer pb-2">
                                <input v-model="form.is_active" type="checkbox" class="w-4 h-4 rounded text-blue-600" />
                                <span class="text-sm text-gray-600">Active</span>
                            </label>
                        </div>

                        <!-- Icon SVG -->
                        <div class="col-span-2">
                            <label class="label">Icon SVG</label>
                            <div class="flex gap-4 items-start">
                                <div class="flex-1">
                                    <textarea v-model="form.icon_svg" rows="4"
                                        class="input resize-none font-mono text-xs"
                                        placeholder='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" ...>...</svg>'></textarea>
                                    <InputError :message="form.errors.icon_svg" />
                                </div>
                                <!-- Live preview -->
                                <div class="w-16 h-16 flex-shrink-0 flex items-center justify-center border-2 border-dashed border-gray-200 rounded-lg bg-gray-50">
                                    <div v-if="form.icon_svg"
                                        class="w-8 h-8 text-gray-600"
                                        v-html="form.icon_svg"></div>
                                    <span v-else class="text-xs text-gray-300 text-center leading-tight">Icon preview</span>
                                </div>
                            </div>
                        </div>

                        <!-- Items list -->
                        <div class="col-span-2">
                            <label class="label">Facility Items <span class="text-red-500">*</span></label>
                            <InputError :message="form.errors.items" />
                            <div class="space-y-2 mt-1">
                                <div v-for="(item, i) in form.items" :key="i" class="flex gap-2 items-center">
                                    <span class="w-6 h-6 bg-blue-50 text-blue-600 text-xs rounded-full flex-shrink-0 flex items-center justify-center font-semibold">
                                        {{ i + 1 }}
                                    </span>
                                    <input v-model="form.items[i]" type="text" class="input"
                                        :placeholder="`e.g. 24 Hours Front Desk`" />
                                    <button type="button" @click="removeItem(i)"
                                        class="flex-shrink-0 w-7 h-7 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 rounded text-sm">✕</button>
                                </div>
                            </div>
                            <button type="button" @click="addItem"
                                class="mt-3 w-full py-2 border-2 border-dashed border-gray-300 rounded-lg text-sm text-blue-600 hover:border-blue-400 hover:bg-blue-50 transition-colors flex items-center justify-center gap-2">
                                <span class="text-base leading-none">+</span> Add Item
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 justify-end">
                        <button v-if="editingId" type="button" @click="resetForm"
                            class="px-4 py-2 text-sm text-gray-600 border rounded hover:bg-gray-50">Cancel</button>
                        <button type="submit" :disabled="form.processing"
                            class="px-6 py-2.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-60">
                            {{ form.processing ? 'Saving...' : (editingId ? 'Update Card' : 'Add Card') }}
                        </button>
                    </div>
                </form>
            </section>

            <!-- ── Facilities Grid ── -->
            <section class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-gray-700">All Facility Cards ({{ facilities.length }})</h2>
                </div>

                <div v-if="!facilities.length" class="px-6 py-10 text-center text-gray-400 text-sm">
                    No facility cards yet — add one above.
                </div>

                <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-px bg-gray-100">
                    <div v-for="fac in facilities" :key="fac.id"
                        class="bg-white p-5 space-y-3">

                        <!-- Header row -->
                        <div class="flex items-start gap-3">
                            <!-- Icon -->
                            <div class="w-10 h-10 flex-shrink-0 flex items-center justify-center bg-emerald-50 rounded-lg">
                                <div v-if="fac.icon_svg" class="w-5 h-5 text-emerald-700" v-html="fac.icon_svg"></div>
                                <svg v-else class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-800 text-sm truncate">{{ fac.title }}</p>
                                <span :class="fac.is_active ? 'text-green-600' : 'text-gray-400'"
                                    class="text-xs font-medium">
                                    {{ fac.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <span class="text-xs text-gray-400 flex-shrink-0">#{{ fac.sort_order }}</span>
                        </div>

                        <!-- Items list -->
                        <ul class="space-y-1 pl-1">
                            <li v-for="(item, i) in fac.items" :key="i"
                                class="flex items-center gap-2 text-xs text-gray-600">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 flex-shrink-0"></span>
                                {{ item }}
                            </li>
                        </ul>

                        <!-- Actions -->
                        <div class="flex items-center gap-2 pt-2 border-t border-gray-50">
                            <button @click="toggleStatus(fac)"
                                :class="fac.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'"
                                class="px-2 py-1 rounded-full text-xs font-semibold">
                                {{ fac.is_active ? 'Active' : 'Inactive' }}
                            </button>
                            <button @click="startEdit(fac)"
                                class="px-3 py-1.5 bg-indigo-600 text-white text-xs rounded hover:bg-indigo-700">Edit</button>
                            <button @click="confirmDelete(fac)"
                                class="px-3 py-1.5 bg-red-600 text-white text-xs rounded hover:bg-red-700">Delete</button>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Delete modal -->
        <div v-if="deleteTarget" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-80 shadow-xl">
                <h3 class="font-semibold text-gray-800 mb-2">Delete Facility Card</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Remove <strong>{{ deleteTarget.title }}</strong>? Cannot be undone.
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
import { ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import InputError  from '@/Components/InputError.vue';
import DropZone from '@/Components/Admin/Shared/DropZone.vue';

const props = defineProps({
    facilities: { type: Array,  default: () => [] },
    settings:   { type: Object, default: () => ({}) },
});

// ── Section settings ──
const settingsForm = useForm({
    fac_badge: props.settings.fac_badge ?? 'FACILITIES',
    fac_title: props.settings.fac_title ?? "Hotel's Facilities",
});

function saveSettings() {
    settingsForm.post(route('admin.facilities.settings'));
}

// ── Page Hero & SEO settings ──
const currentHeroImg = ref(props.settings.fac_hero_image ?? null);
const currentOgImg   = ref(props.settings.fac_seo_og_image ?? null);

const pageForm = useForm({
    fac_hero_title:      props.settings.fac_hero_title      ?? '',
    fac_seo_title:       props.settings.fac_seo_title       ?? '',
    fac_seo_description: props.settings.fac_seo_description ?? '',
    fac_seo_keywords:    props.settings.fac_seo_keywords    ?? '',
    fac_hero_image:      null,
    fac_seo_og_image:    null,
});

function onHeroImg(file) {
    if (!file) return;
    pageForm.fac_hero_image = file;
}

function onOgImg(file) {
    if (!file) return;
    pageForm.fac_seo_og_image = file;
}

function savePageSettings() {
    pageForm.post(route('admin.facilities.settings'), { forceFormData: true });
}

// ── Add / Edit ──
const editingId = ref(null);

const form = useForm({
    title:      '',
    icon_svg:   '',
    items:      [''],
    sort_order: 0,
    is_active:  true,
});

function addItem()     { form.items.push(''); }
function removeItem(i) { form.items.splice(i, 1); }

function startEdit(fac) {
    editingId.value    = fac.id;
    form.title         = fac.title;
    form.icon_svg      = fac.icon_svg ?? '';
    form.items         = fac.items ? [...fac.items] : [''];
    form.sort_order    = fac.sort_order;
    form.is_active     = fac.is_active;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function resetForm() {
    editingId.value = null;
    form.reset();
    form.items     = [''];
    form.is_active = true;
}

function submitFacility() {
    if (editingId.value) {
        form.put(route('admin.facilities.update', editingId.value), {
            onSuccess: () => resetForm(),
        });
    } else {
        form.post(route('admin.facilities.store'), {
            onSuccess: () => resetForm(),
        });
    }
}

// ── Status / Delete ──
function toggleStatus(fac) {
    router.patch(route('admin.facilities.toggle', fac.id));
}

const deleteTarget = ref(null);
function confirmDelete(fac) { deleteTarget.value = fac; }
function doDelete() {
    router.delete(route('admin.facilities.destroy', deleteTarget.value.id), {
        onFinish: () => { deleteTarget.value = null; },
    });
}
</script>

<style scoped>
.label { @apply block text-sm text-gray-600 mb-1; }
.input { @apply w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none; }
</style>
