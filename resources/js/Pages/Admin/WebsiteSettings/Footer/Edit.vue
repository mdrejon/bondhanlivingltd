<template>
    <AdminLayout>
        <div class="max-w-4xl space-y-4">
            <h1 class="text-lg font-semibold text-gray-800">Footer Settings</h1>

            <div v-if="$page.props.flash?.success" class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded">
                {{ $page.props.flash.success }}
            </div>

            <form @submit.prevent="submit" class="space-y-6">

                <!-- Brand -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Brand Section</h2>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Footer Logo</label>
                        <DropZone @change="onLogoChange" hint="JPEG / PNG / WebP — max 5 MB" preview-class="w-full h-28 object-contain p-2 bg-gray-50"
                            :existing-preview="currentLogo ? '/storage/' + currentLogo : null" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Brand Description</label>
                        <textarea v-model="form.footer_brand_description" rows="3" class="input" placeholder="A 3-star boutique hotel near Kolatoli Beach..."></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Facebook URL</label>
                            <input v-model="form.footer_facebook_url" type="text" class="input" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Twitter / X URL</label>
                            <input v-model="form.footer_twitter_url" type="text" class="input" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Instagram URL</label>
                            <input v-model="form.footer_instagram_url" type="text" class="input" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">YouTube URL</label>
                            <input v-model="form.footer_youtube_url" type="text" class="input" />
                        </div>
                    </div>
                </section>

                <!-- Quick Links -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-3">
                    <div class="flex items-center justify-between border-b pb-2">
                        <h2 class="text-sm font-semibold text-gray-700">Quick Links</h2>
                        <button type="button" @click="addQuickLink" class="text-xs text-blue-600 hover:underline">+ Add Link</button>
                    </div>
                    <div
                        v-for="(link, i) in form.footer_quick_links"
                        :key="i"
                        class="flex items-center gap-3"
                    >
                        <input v-model="link.label" type="text" placeholder="Label" class="input flex-1" />
                        <input v-model="link.url"   type="text" placeholder="URL"   class="input flex-1" />
                        <button type="button" @click="removeQuickLink(i)" class="text-red-400 hover:text-red-600 text-lg leading-none">&times;</button>
                    </div>
                    <p v-if="!form.footer_quick_links.length" class="text-xs text-gray-400">No quick links yet.</p>
                </section>

                <!-- Service Links -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-3">
                    <div class="flex items-center justify-between border-b pb-2">
                        <h2 class="text-sm font-semibold text-gray-700">Our Services Links</h2>
                        <button type="button" @click="addServiceLink" class="text-xs text-blue-600 hover:underline">+ Add Link</button>
                    </div>
                    <div
                        v-for="(link, i) in form.footer_service_links"
                        :key="i"
                        class="flex items-center gap-3"
                    >
                        <input v-model="link.label" type="text" placeholder="Label" class="input flex-1" />
                        <input v-model="link.url"   type="text" placeholder="URL"   class="input flex-1" />
                        <button type="button" @click="removeServiceLink(i)" class="text-red-400 hover:text-red-600 text-lg leading-none">&times;</button>
                    </div>
                    <p v-if="!form.footer_service_links.length" class="text-xs text-gray-400">No service links yet.</p>
                </section>

                <!-- Official Contact Info -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Official Contact Info</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Phone Line 1</label>
                            <input v-model="form.footer_phone_1" type="text" class="input" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Phone Line 2</label>
                            <input v-model="form.footer_phone_2" type="text" class="input" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Phone Line 3</label>
                            <input v-model="form.footer_phone_3" type="text" class="input" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Email 1</label>
                            <input v-model="form.footer_email_1" type="email" class="input" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Email 2</label>
                            <input v-model="form.footer_email_2" type="email" class="input" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Address Line 1</label>
                            <input v-model="form.footer_address_line1" type="text" class="input" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Address Line 2</label>
                            <input v-model="form.footer_address_line2" type="text" class="input" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Website URL</label>
                            <input v-model="form.footer_website_url" type="text" class="input" />
                        </div>
                    </div>
                </section>

                <!-- Newsletter & Copyright -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Newsletter & Copyright</h2>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Newsletter Title</label>
                        <input v-model="form.footer_newsletter_title" type="text" class="input" placeholder="Stay Updated With Hotel Beach Way..." />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Privacy Policy URL</label>
                            <input v-model="form.footer_privacy_url" type="text" class="input" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Terms URL</label>
                            <input v-model="form.footer_terms_url" type="text" class="input" />
                        </div>
                    </div>
                </section>

                <div class="flex justify-end">
                    <button type="submit" :disabled="form.processing" class="px-6 py-2.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-60">
                        {{ form.processing ? 'Saving...' : 'Save Footer Settings' }}
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
import DropZone from '@/Components/Admin/Shared/DropZone.vue';

const props = defineProps({
    settings: { type: Object, required: true },
});

const currentLogo = ref(props.settings.footer_logo);

const form = useForm({
    footer_logo:              null,
    footer_brand_description: props.settings.footer_brand_description ?? '',
    footer_facebook_url:      props.settings.footer_facebook_url ?? '',
    footer_twitter_url:       props.settings.footer_twitter_url ?? '',
    footer_instagram_url:     props.settings.footer_instagram_url ?? '',
    footer_youtube_url:       props.settings.footer_youtube_url ?? '',
    footer_quick_links:       props.settings.footer_quick_links ?? [],
    footer_service_links:     props.settings.footer_service_links ?? [],
    footer_phone_1:           props.settings.footer_phone_1 ?? '',
    footer_phone_2:           props.settings.footer_phone_2 ?? '',
    footer_phone_3:           props.settings.footer_phone_3 ?? '',
    footer_email_1:           props.settings.footer_email_1 ?? '',
    footer_email_2:           props.settings.footer_email_2 ?? '',
    footer_address_line1:     props.settings.footer_address_line1 ?? '',
    footer_address_line2:     props.settings.footer_address_line2 ?? '',
    footer_website_url:       props.settings.footer_website_url ?? '',
    footer_newsletter_title:  props.settings.footer_newsletter_title ?? '',
    footer_privacy_url:       props.settings.footer_privacy_url ?? '',
    footer_terms_url:         props.settings.footer_terms_url ?? '',
});

function onLogoChange(file) {
    if (!file) return;
    form.footer_logo = file;
}

function addQuickLink()      { form.footer_quick_links.push({ label: '', url: '' }); }
function removeQuickLink(i)  { form.footer_quick_links.splice(i, 1); }
function addServiceLink()    { form.footer_service_links.push({ label: '', url: '' }); }
function removeServiceLink(i){ form.footer_service_links.splice(i, 1); }

function submit() {
    form.post(route('admin.website-settings.footer.update'), {
        forceFormData: true,
    });
}
</script>

<style scoped>
.input {
    @apply w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none;
}
</style>
