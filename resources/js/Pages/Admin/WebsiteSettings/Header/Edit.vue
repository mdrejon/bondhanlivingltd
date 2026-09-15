<template>
    <AdminLayout>
        <div class="max-w-3xl space-y-4">
            <h1 class="text-lg font-semibold text-gray-800">Header Settings</h1>

            <div v-if="$page.props.flash?.success" class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded">
                {{ $page.props.flash.success }}
            </div>

            <form @submit.prevent="submit" class="space-y-6">

                <!-- Logo -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Logo</h2>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Logo Image</label>
                        <DropZone @change="onLogoChange" hint="JPEG / PNG / WebP — max 5 MB" preview-class="w-full h-28 object-contain p-2 bg-gray-50"
                            :existing-preview="currentLogo ? '/storage/' + currentLogo : null" />
                        <InputError :message="form.errors.header_logo" />
                    </div>
                </section>

                <!-- Contact info -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Contact Info (Top Bar)</h2>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Phone</label>
                        <input v-model="form.header_phone" type="text" class="input" placeholder="+88 01777-909595" />
                        <InputError :message="form.errors.header_phone" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Email</label>
                        <input v-model="form.header_email" type="email" class="input" placeholder="info@hotelbeachway.com" />
                        <InputError :message="form.errors.header_email" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Address</label>
                        <input v-model="form.header_address" type="text" class="input" placeholder="Kolatoli Road, Cox's Bazar, Bangladesh" />
                        <InputError :message="form.errors.header_address" />
                    </div>
                </section>

                <!-- Social links -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Social Links (Top Bar)</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Facebook URL</label>
                            <input v-model="form.header_facebook_url" type="text" class="input" placeholder="https://facebook.com/..." />
                            <InputError :message="form.errors.header_facebook_url" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Twitter / X URL</label>
                            <input v-model="form.header_twitter_url" type="text" class="input" placeholder="https://twitter.com/..." />
                            <InputError :message="form.errors.header_twitter_url" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Instagram URL</label>
                            <input v-model="form.header_instagram_url" type="text" class="input" placeholder="https://instagram.com/..." />
                            <InputError :message="form.errors.header_instagram_url" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Pinterest URL</label>
                            <input v-model="form.header_pinterest_url" type="text" class="input" placeholder="https://pinterest.com/..." />
                            <InputError :message="form.errors.header_pinterest_url" />
                        </div>
                    </div>
                </section>

                <!-- Book button -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Book Online Button (Navbar)</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Button Text</label>
                            <input v-model="form.header_book_btn_text" type="text" class="input" placeholder="Book Online" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Button URL</label>
                            <input v-model="form.header_book_btn_url" type="text" class="input" placeholder="#contact" />
                        </div>
                    </div>
                </section>

                <div class="flex justify-end">
                    <button type="submit" :disabled="form.processing" class="px-6 py-2.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-60">
                        {{ form.processing ? 'Saving...' : 'Save Header Settings' }}
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
import InputError  from '@/Components/InputError.vue';
import DropZone from '@/Components/Admin/Shared/DropZone.vue';

const props = defineProps({
    settings: { type: Object, required: true },
});

const currentLogo = ref(props.settings.header_logo);

const form = useForm({
    header_phone:         props.settings.header_phone ?? '',
    header_email:         props.settings.header_email ?? '',
    header_address:       props.settings.header_address ?? '',
    header_facebook_url:  props.settings.header_facebook_url ?? '',
    header_twitter_url:   props.settings.header_twitter_url ?? '',
    header_instagram_url: props.settings.header_instagram_url ?? '',
    header_pinterest_url: props.settings.header_pinterest_url ?? '',
    header_book_btn_text: props.settings.header_book_btn_text ?? 'Book Online',
    header_book_btn_url:  props.settings.header_book_btn_url ?? '#contact',
    header_logo:          null,
});

function onLogoChange(file) {
    if (!file) return;
    form.header_logo = file;
}

function submit() {
    form.post(route('admin.website-settings.header.update'), {
        forceFormData: true,
    });
}
</script>

<style scoped>
.input {
    @apply w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none;
}
</style>
