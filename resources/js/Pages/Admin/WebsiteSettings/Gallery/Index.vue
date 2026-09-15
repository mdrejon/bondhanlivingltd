<template>
    <AdminLayout>
        <div class="max-w-5xl space-y-6">
            <h1 class="text-lg font-semibold text-gray-800">Gallery Management</h1>

            <!-- Flash -->
            <div v-if="$page.props.flash?.success"
                class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded">
                {{ $page.props.flash.success }}
            </div>

            <!-- ── Section Header Settings ── -->
            <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Section Header</h2>
                <form @submit.prevent="saveSettings" class="space-y-3">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Badge Text</label>
                            <input v-model="settingsForm.gallery_badge" type="text" class="input" placeholder="OUR GALLERY" />
                            <InputError :message="settingsForm.errors.gallery_badge" />
                        </div>
                        <div>
                            <label class="label">Section Title</label>
                            <input v-model="settingsForm.gallery_title" type="text" class="input" placeholder="Hotel Gallery" />
                            <InputError :message="settingsForm.errors.gallery_title" />
                        </div>
                        <div class="col-span-2">
                            <label class="label">Subtitle <span class="text-xs text-gray-400">(shown on gallery page)</span></label>
                            <textarea v-model="settingsForm.gallery_subtitle" rows="2" class="input resize-none"
                                placeholder="Explore the beauty and comfort of Hotel Beach Way..."></textarea>
                            <InputError :message="settingsForm.errors.gallery_subtitle" />
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" :disabled="settingsForm.processing"
                            class="px-5 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-60">
                            {{ settingsForm.processing ? 'Saving...' : 'Save Header' }}
                        </button>
                    </div>
                </form>
            </section>

            <!-- ── Page Hero & SEO ── -->
            <section class="bg-white rounded-lg shadow-sm p-6 space-y-5">
                <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Page Hero &amp; SEO Configuration</h2>
                <form @submit.prevent="saveSeo" class="space-y-5">
                    <!-- Hero -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2 md:col-span-1">
                            <label class="label">Hero Background Image</label>
                            <DropZone @change="onHeroImg" hint="JPEG / PNG / WebP — max 5 MB" preview-class="w-full h-44 object-cover"
                                :existing-preview="currentHeroImg ? '/storage/' + currentHeroImg : null" />
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="label">Page Title (Breadcrumb)</label>
                            <input v-model="seoForm.gallery_hero_title" type="text" class="input" placeholder="Gallery" />
                        </div>
                    </div>

                    <!-- SEO -->
                    <div class="border-t pt-4 space-y-4">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide">SEO Configuration</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="label">Meta Title <span class="text-gray-400 font-normal">({{ seoForm.gallery_seo_title.length }}/160)</span></label>
                                <input v-model="seoForm.gallery_seo_title" type="text" maxlength="160" class="input"
                                    placeholder="Gallery – Hotel Beach Way" />
                            </div>
                            <div class="col-span-2">
                                <label class="label">Meta Description <span class="text-gray-400 font-normal">({{ seoForm.gallery_seo_description.length }}/320)</span></label>
                                <textarea v-model="seoForm.gallery_seo_description" rows="3" maxlength="320"
                                    class="input resize-none"
                                    placeholder="Browse our photo gallery of Hotel Beach Way, Cox's Bazar..."></textarea>
                            </div>
                            <div class="col-span-2">
                                <label class="label">Keywords</label>
                                <input v-model="seoForm.gallery_seo_keywords" type="text" class="input"
                                    placeholder="hotel beach way gallery, cox's bazar hotel photos" />
                            </div>
                            <div class="col-span-2 md:col-span-1">
                                <label class="label">OG / Social Share Image</label>
                                <DropZone @change="onOgImg" hint="JPEG / PNG / WebP — max 5 MB" preview-class="w-full h-36 object-cover"
                                    :existing-preview="currentOgImg ? '/storage/' + currentOgImg : null" />
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" :disabled="seoForm.processing"
                            class="px-5 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-60">
                            {{ seoForm.processing ? 'Saving...' : 'Save Page Settings' }}
                        </button>
                    </div>
                </form>
            </section>

            <!-- ── Upload New Images ── -->
            <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Upload Images</h2>
                <form @submit.prevent="uploadImages" class="space-y-3">
                    <DropZone multiple @change="onFileSelect" hint="JPG, PNG, WEBP — max 4MB each" />

                    <!-- Preview selected files -->
                    <div v-if="selectedFiles.length" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                        <div v-for="(f, i) in selectedFiles" :key="i" class="relative group">
                            <img :src="f.preview" class="w-full h-24 object-cover rounded border" />
                            <button type="button" @click="removeSelected(i)"
                                class="absolute top-1 right-1 w-5 h-5 bg-red-600 text-white rounded-full text-xs hidden group-hover:flex items-center justify-center">
                                ✕
                            </button>
                            <p class="text-xs text-gray-500 mt-1 truncate">{{ f.name }}</p>
                        </div>
                    </div>

                    <div v-if="selectedFiles.length" class="flex justify-end">
                        <button type="submit" :disabled="uploadForm.processing"
                            class="px-5 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700 disabled:opacity-60">
                            {{ uploadForm.processing ? 'Uploading...' : `Upload ${selectedFiles.length} Image(s)` }}
                        </button>
                    </div>
                    <InputError :message="uploadForm.errors.images" />
                </form>
            </section>

            <!-- ── Gallery Grid Management ── -->
            <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between border-b pb-2">
                    <h2 class="text-sm font-semibold text-gray-700">Gallery Images ({{ images.length }})</h2>
                    <p class="text-xs text-gray-400">First image = featured (tall slot on homepage). Drag to reorder.</p>
                </div>

                <div v-if="!images.length" class="py-10 text-center text-gray-400 text-sm">
                    No gallery images yet — upload some above.
                </div>

                <!-- Grid -->
                <div v-else class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    <div v-for="(img, i) in localImages" :key="img.id"
                        class="relative group rounded-lg overflow-hidden border"
                        :class="i === 0 ? 'ring-2 ring-yellow-400' : ''">

                        <!-- Image -->
                        <div class="relative">
                            <img :src="`/storage/${img.image}`"
                                class="w-full h-32 object-cover transition-opacity"
                                :class="img.is_active ? 'opacity-100' : 'opacity-40'"
                                :alt="img.alt || ''" />

                            <!-- Featured badge -->
                            <span v-if="i === 0"
                                class="absolute top-1 left-1 bg-yellow-400 text-yellow-900 text-xs px-1.5 py-0.5 rounded font-semibold">
                                Featured
                            </span>

                            <!-- Hover overlay with actions -->
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-2">
                                <button type="button" @click="openEdit(img)"
                                    class="px-3 py-1 bg-white text-gray-800 text-xs rounded hover:bg-gray-100">
                                    Edit Alt
                                </button>
                                <button type="button" @click="toggleStatus(img)"
                                    :class="img.is_active ? 'bg-yellow-400 text-yellow-900' : 'bg-green-500 text-white'"
                                    class="px-3 py-1 text-xs rounded hover:opacity-90">
                                    {{ img.is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                                <button type="button" @click="confirmDelete(img)"
                                    class="px-3 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700">
                                    Delete
                                </button>
                            </div>
                        </div>

                        <!-- Sort controls + info -->
                        <div class="bg-gray-50 px-2 py-1.5 flex items-center justify-between gap-1">
                            <span class="text-xs text-gray-500 truncate flex-1">{{ img.alt || '(no alt)' }}</span>
                            <div class="flex gap-0.5 flex-shrink-0">
                                <button type="button" @click="moveUp(i)" :disabled="i === 0"
                                    class="w-6 h-6 flex items-center justify-center text-gray-400 hover:text-gray-700 disabled:opacity-30 text-xs">
                                    ↑
                                </button>
                                <button type="button" @click="moveDown(i)" :disabled="i === localImages.length - 1"
                                    class="w-6 h-6 flex items-center justify-center text-gray-400 hover:text-gray-700 disabled:opacity-30 text-xs">
                                    ↓
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save order button -->
                <div v-if="orderChanged" class="flex justify-end pt-2 border-t border-gray-100">
                    <button type="button" @click="saveOrder"
                        class="px-5 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">
                        Save New Order
                    </button>
                </div>
            </section>
        </div>

        <!-- Edit Alt Modal -->
        <div v-if="editTarget" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-96 shadow-xl space-y-4">
                <h3 class="font-semibold text-gray-800">Edit Image Details</h3>
                <img :src="`/storage/${editTarget.image}`" class="w-full h-40 object-cover rounded" />
                <div>
                    <label class="label">Alt Text</label>
                    <input v-model="editForm.alt" type="text" class="input" placeholder="Descriptive alt text..." />
                </div>
                <div>
                    <label class="label">Caption <span class="text-xs text-gray-400">(optional)</span></label>
                    <input v-model="editForm.caption" type="text" class="input" placeholder="Optional caption..." />
                </div>
                <div>
                    <label class="label">Sort Order</label>
                    <input v-model.number="editForm.sort_order" type="number" min="0" class="input" />
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button @click="editTarget = null"
                        class="px-4 py-2 text-sm text-gray-600 border rounded hover:bg-gray-50">Cancel</button>
                    <button @click="saveEdit"
                        class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
                </div>
            </div>
        </div>

        <!-- Delete Confirm Modal -->
        <div v-if="deleteTarget" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-80 shadow-xl">
                <h3 class="font-semibold text-gray-800 mb-2">Delete Image</h3>
                <p class="text-sm text-gray-600 mb-4">This will permanently delete the image file. Cannot be undone.</p>
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
import { ref, reactive, computed } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import InputError  from '@/Components/InputError.vue';
import DropZone from '@/Components/Admin/Shared/DropZone.vue';

const props = defineProps({
    images:   { type: Array,  default: () => [] },
    settings: { type: Object, default: () => ({}) },
});

// ── Section header form ──
const settingsForm = useForm({
    gallery_badge:    props.settings.gallery_badge    ?? 'OUR GALLERY',
    gallery_title:    props.settings.gallery_title    ?? 'Hotel Gallery',
    gallery_subtitle: props.settings.gallery_subtitle ?? '',
});

function saveSettings() {
    settingsForm.post(route('admin.website-settings.gallery.settings'));
}

// ── Page Hero & SEO form ──
const currentHeroImg = ref(props.settings.gallery_hero_image  ?? null);
const currentOgImg   = ref(props.settings.gallery_seo_og_image ?? null);

const seoForm = useForm({
    gallery_hero_title:      props.settings.gallery_hero_title      ?? '',
    gallery_seo_title:       props.settings.gallery_seo_title       ?? '',
    gallery_seo_description: props.settings.gallery_seo_description ?? '',
    gallery_seo_keywords:    props.settings.gallery_seo_keywords    ?? '',
    gallery_hero_image:      null,
    gallery_seo_og_image:    null,
});

function onHeroImg(file) {
    if (!file) return;
    seoForm.gallery_hero_image = file;
}

function onOgImg(file) {
    if (!file) return;
    seoForm.gallery_seo_og_image = file;
}

function saveSeo() {
    seoForm.post(route('admin.website-settings.gallery.settings'), { forceFormData: true });
}

// ── Upload form ──
const selectedFiles = ref([]);

const uploadForm = useForm({ images: [] });

function onFileSelect(files) {
    addFiles(files);
}

function addFiles(files) {
    const list = Array.isArray(files) ? files : Array.from(files);
    for (const file of list) {
        selectedFiles.value.push({ file, name: file.name, preview: URL.createObjectURL(file) });
    }
}

function removeSelected(i) {
    selectedFiles.value.splice(i, 1);
}

function uploadImages() {
    const fd = new FormData();
    selectedFiles.value.forEach(({ file }) => fd.append('images[]', file));

    router.post(route('admin.website-settings.gallery.store'), fd, {
        forceFormData: true,
        onSuccess: () => { selectedFiles.value = []; },
    });
}

// ── Local reorder ──
const localImages  = ref([...props.images]);
const originalOrder = props.images.map(i => i.id).join(',');

const orderChanged = computed(() =>
    localImages.value.map(i => i.id).join(',') !== originalOrder
);

function moveUp(i) {
    if (i === 0) return;
    const arr = localImages.value;
    [arr[i - 1], arr[i]] = [arr[i], arr[i - 1]];
}

function moveDown(i) {
    const arr = localImages.value;
    if (i === arr.length - 1) return;
    [arr[i], arr[i + 1]] = [arr[i + 1], arr[i]];
}

function saveOrder() {
    const order = localImages.value.map((img, idx) => ({ id: img.id, sort_order: idx }));
    router.post(route('admin.website-settings.gallery.reorder'), { order });
}

// ── Toggle status ──
function toggleStatus(img) {
    router.patch(route('admin.website-settings.gallery.toggle', img.id));
}

// ── Edit modal ──
const editTarget = ref(null);
const editForm   = reactive({ alt: '', caption: '', sort_order: 0 });

function openEdit(img) {
    editTarget.value = img;
    editForm.alt        = img.alt        ?? '';
    editForm.caption    = img.caption    ?? '';
    editForm.sort_order = img.sort_order ?? 0;
}

function saveEdit() {
    router.patch(route('admin.website-settings.gallery.update', editTarget.value.id), editForm, {
        onSuccess: () => { editTarget.value = null; },
    });
}

// ── Delete modal ──
const deleteTarget = ref(null);

function confirmDelete(img) {
    deleteTarget.value = img;
}

function doDelete() {
    router.delete(route('admin.website-settings.gallery.destroy', deleteTarget.value.id), {
        onFinish: () => { deleteTarget.value = null; },
    });
}
</script>

<style scoped>
.label { @apply block text-sm text-gray-600 mb-1; }
.input { @apply w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none; }
</style>
