<template>
    <form @submit.prevent="$emit('submit')" class="space-y-6">

        <!-- Basic Info -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Basic Information</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="label">Post Title <span class="text-red-500">*</span></label>
                    <input v-model="form.title" type="text" class="input" placeholder="Enter post title..." />
                    <p v-if="form.errors.title" class="text-xs text-red-500 mt-1">{{ form.errors.title }}</p>
                </div>
                <div class="col-span-2">
                    <label class="label">URL Slug <span class="text-gray-400 font-normal text-xs">(auto-generated)</span></label>
                    <div class="flex items-center gap-2">
                        <input :value="slugPreview" type="text" class="input bg-gray-50 text-gray-500 cursor-not-allowed" readonly />
                        <span class="text-xs text-gray-400 whitespace-nowrap">/blog/{{ slugPreview || '…' }}</span>
                    </div>
                </div>
                <div>
                    <label class="label">Category</label>
                    <select v-model="form.category_id" class="input">
                        <option :value="null">— No Category —</option>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                    </select>
                </div>
                <div>
                    <label class="label">Tags <span class="text-gray-400 font-normal text-xs">(comma-separated)</span></label>
                    <input v-model="form.tags" type="text" class="input" placeholder="travel, beach, hotel, cox's bazar" />
                </div>
            </div>
            <div class="flex gap-6 pt-1">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input v-model="form.is_featured" type="checkbox" class="w-4 h-4 rounded text-yellow-500" />
                    <span class="text-sm text-gray-600">Featured Post</span>
                </label>
            </div>
        </section>

        <!-- Excerpt & Content -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Content</h2>
            <div>
                <label class="label">Excerpt <span class="text-gray-400 font-normal text-xs">(short summary shown on listing pages)</span></label>
                <textarea v-model="form.excerpt" rows="3" class="input" maxlength="500"
                    placeholder="Brief summary of the post..."></textarea>
                <p class="text-xs text-gray-400 mt-1">{{ (form.excerpt || '').length }}/500 characters</p>
            </div>
            <div>
                <label class="label">Full Content</label>
                <RichEditor v-model="form.content" />
            </div>
        </section>

        <!-- Feature Image -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Feature Image</h2>
            <div v-if="existingFeatureImage && !removeFeatureImageFlag" class="flex items-start gap-4">
                <img :src="'/storage/' + existingFeatureImage" class="w-40 h-28 object-cover rounded border border-gray-200" alt="Feature image" />
                <div class="flex flex-col gap-2">
                    <span class="text-xs text-gray-500">Current feature image</span>
                    <button type="button" @click="onRemoveFeatureImage"
                        class="text-xs text-red-500 hover:text-red-700 underline w-fit">Remove image</button>
                </div>
            </div>
            <div v-if="removeFeatureImageFlag && existingFeatureImage" class="text-xs text-amber-600 italic">
                Feature image will be removed on save.
                <button type="button" @click="removeFeatureImageFlag = false" class="underline ml-1">Undo</button>
            </div>
            <div>
                <label class="label">{{ existingFeatureImage && !removeFeatureImageFlag ? 'Replace Feature Image' : 'Upload Feature Image' }}</label>
                <DropZone @change="onFeatureImageChange" hint="JPEG / PNG / WebP — max 3 MB" preview-class="w-full h-44 object-cover" />
            </div>
        </section>

        <!-- Author Info -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Author Information</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Author Name</label>
                    <input v-model="form.author_name" type="text" class="input" placeholder="John Doe" />
                </div>
                <div>
                    <label class="label">Author Avatar</label>
                    <div v-if="existingAuthorAvatar && !removeAuthorAvatarFlag" class="flex items-center gap-3 mb-2">
                        <img :src="'/storage/' + existingAuthorAvatar" class="w-10 h-10 rounded-full object-cover border" />
                        <button type="button" @click="onRemoveAuthorAvatar"
                            class="text-xs text-red-500 hover:text-red-700 underline">Remove</button>
                    </div>
                    <div v-if="removeAuthorAvatarFlag && existingAuthorAvatar" class="text-xs text-amber-600 italic mb-1">
                        Avatar will be removed.
                        <button type="button" @click="removeAuthorAvatarFlag = false" class="underline ml-1">Undo</button>
                    </div>
                    <DropZone @change="onAuthorAvatarChange" hint="JPG, PNG — square crop" preview-class="w-full h-32 object-cover" />
                </div>
                <div class="col-span-2">
                    <label class="label">Author Bio</label>
                    <textarea v-model="form.author_bio" rows="2" class="input" placeholder="Short bio about the author..." maxlength="1000"></textarea>
                </div>
            </div>
        </section>

        <!-- Publishing -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Publishing</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Status <span class="text-red-500">*</span></label>
                    <select v-model="form.status" class="input">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                </div>
                <div>
                    <label class="label">Publish Date <span class="text-gray-400 font-normal text-xs">(leave blank for now)</span></label>
                    <input v-model="form.published_at" type="datetime-local" class="input" />
                </div>
                <div>
                    <label class="label">Sort Order</label>
                    <input v-model.number="form.sort_order" type="number" min="0" class="input" />
                </div>
            </div>
        </section>

        <!-- SEO -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">SEO Settings</h2>
            <div>
                <label class="label">Meta Title <span class="text-gray-400 font-normal text-xs">(max 160 chars)</span></label>
                <input v-model="form.meta_title" @input="onMetaTitleInput" type="text" class="input" maxlength="160"
                    :placeholder="form.title ? form.title + ' – Hotel Beach Way Blog' : 'Post title – Hotel Beach Way Blog'" />
                <p class="text-xs text-gray-400 mt-1">{{ (form.meta_title || '').length }}/160 characters</p>
            </div>
            <div>
                <label class="label">Meta Description <span class="text-gray-400 font-normal text-xs">(max 320 chars)</span></label>
                <textarea v-model="form.meta_description" @input="onMetaDescInput" rows="3" class="input" maxlength="320"
                    placeholder="A concise description for search engine results..."></textarea>
                <p class="text-xs text-gray-400 mt-1">{{ (form.meta_description || '').length }}/320 characters</p>
            </div>
            <div>
                <label class="label">Meta Keywords <span class="text-gray-400 font-normal text-xs">(comma-separated)</span></label>
                <input v-model="form.meta_keywords" @input="onMetaKeywordsInput" type="text" class="input" maxlength="500"
                    placeholder="hotel blog, cox's bazar, travel tips" />
            </div>

            <!-- OG Image -->
            <div class="border-t border-gray-100 pt-4 space-y-3">
                <label class="label">Open Graph / Social Share Image <span class="text-gray-400 font-normal text-xs">(recommended 1200×630px)</span></label>
                <div v-if="existingOgImage && !removeOgImageFlag" class="flex items-start gap-4">
                    <img :src="'/storage/' + existingOgImage" class="w-40 h-24 object-cover rounded border border-gray-200" alt="OG image" />
                    <div class="flex flex-col gap-2">
                        <span class="text-xs text-gray-500">Current OG image</span>
                        <button type="button" @click="onRemoveOgImage" class="text-xs text-red-500 hover:text-red-700 underline w-fit">Remove</button>
                    </div>
                </div>
                <div v-if="removeOgImageFlag && existingOgImage" class="text-xs text-amber-600 italic">
                    OG image will be removed on save.
                    <button type="button" @click="removeOgImageFlag = false" class="underline ml-1">Undo</button>
                </div>
                <DropZone @change="onOgImageChange" hint="JPEG / PNG / WebP — max 5 MB" preview-class="w-full h-36 object-cover" />
            </div>
        </section>

        <!-- Submit -->
        <div class="flex justify-end gap-3">
            <Link :href="route('admin.blog.index')"
                class="px-5 py-2 text-sm border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                Cancel
            </Link>
            <button type="submit" :disabled="form.processing"
                class="px-6 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-60">
                {{ form.processing ? 'Saving...' : submitLabel }}
            </button>
        </div>
    </form>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import RichEditor from '@/Components/Admin/Shared/RichEditor.vue';
import DropZone from '@/Components/Admin/Shared/DropZone.vue';
import { useSeoAutoFill } from '@/Composables/useSeoAutoFill';

const props = defineProps({
    form:                  Object,
    categories:            { type: Array, default: () => [] },
    submitLabel:           { type: String, default: 'Save Post' },
    existingFeatureImage:  { type: String, default: null },
    existingOgImage:       { type: String, default: null },
    existingAuthorAvatar:  { type: String, default: null },
});

defineEmits(['submit']);

const { onMetaTitleInput, onMetaDescInput, onMetaKeywordsInput } = useSeoAutoFill(props.form, {
    titleSource: () => props.form.title,
    descSource:  () => props.form.excerpt,
    titleSuffix: ' – Hotel Beach Way Blog',
});

const removeFeatureImageFlag  = ref(false);
const removeOgImageFlag       = ref(false);
const removeAuthorAvatarFlag  = ref(false);

const slugPreview = computed(() => {
    return (props.form.title || '')
        .toLowerCase().trim()
        .replace(/[^\w\s-]/g, '')
        .replace(/[\s_-]+/g, '-')
        .replace(/^-+|-+$/g, '');
});

function onFeatureImageChange(file) {
    if (!file) return;
    props.form.feature_image = file;
    removeFeatureImageFlag.value = false;
}

function onRemoveFeatureImage() {
    removeFeatureImageFlag.value = true;
    props.form.remove_feature_image = true;
    props.form.feature_image = null;
}

function onOgImageChange(file) {
    if (!file) return;
    props.form.og_image = file;
    removeOgImageFlag.value = false;
}

function onRemoveOgImage() {
    removeOgImageFlag.value = true;
    props.form.remove_og_image = true;
    props.form.og_image = null;
}

function onAuthorAvatarChange(file) {
    if (!file) return;
    props.form.author_avatar = file;
    removeAuthorAvatarFlag.value = false;
}

function onRemoveAuthorAvatar() {
    removeAuthorAvatarFlag.value = true;
    props.form.remove_author_avatar = true;
    props.form.author_avatar = null;
}
</script>

<style scoped>
.label { @apply block text-sm font-medium text-gray-700 mb-1.5; }
.input { @apply w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors bg-white; }
select.input { @apply cursor-pointer; }
textarea.input { @apply resize-none; }
</style>
