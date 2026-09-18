<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';

const props = defineProps({
    settings: Object,
});

const form = useForm({
    header_phone: props.settings.header_phone || '',
    header_email: props.settings.header_email || '',
    social_facebook: props.settings.social_facebook || '',
    social_twitter: props.settings.social_twitter || '',
    social_linkedin: props.settings.social_linkedin || '',
    social_instagram: props.settings.social_instagram || '',
});

const submit = () => {
    form.post(route('admin.website-settings.header.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Header Settings" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Header Settings
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit" class="space-y-6">
                            
                            <!-- Contact Info -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Contact Information</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="header_phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                        <input type="text" id="header_phone" v-model="form.header_phone" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                                        <div v-if="form.errors.header_phone" class="text-red-500 text-sm mt-1">{{ form.errors.header_phone }}</div>
                                    </div>
                                    <div>
                                        <label for="header_email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                        <input type="email" id="header_email" v-model="form.header_email" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                                        <div v-if="form.errors.header_email" class="text-red-500 text-sm mt-1">{{ form.errors.header_email }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Social Links -->
                            <div class="mt-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Social Media Links</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="social_facebook" class="block text-sm font-medium text-gray-700">Facebook URL</label>
                                        <input type="url" id="social_facebook" v-model="form.social_facebook" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                                        <div v-if="form.errors.social_facebook" class="text-red-500 text-sm mt-1">{{ form.errors.social_facebook }}</div>
                                    </div>
                                    <div>
                                        <label for="social_twitter" class="block text-sm font-medium text-gray-700">Twitter URL</label>
                                        <input type="url" id="social_twitter" v-model="form.social_twitter" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                                        <div v-if="form.errors.social_twitter" class="text-red-500 text-sm mt-1">{{ form.errors.social_twitter }}</div>
                                    </div>
                                    <div>
                                        <label for="social_linkedin" class="block text-sm font-medium text-gray-700">LinkedIn URL</label>
                                        <input type="url" id="social_linkedin" v-model="form.social_linkedin" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                                        <div v-if="form.errors.social_linkedin" class="text-red-500 text-sm mt-1">{{ form.errors.social_linkedin }}</div>
                                    </div>
                                    <div>
                                        <label for="social_instagram" class="block text-sm font-medium text-gray-700">Instagram URL</label>
                                        <input type="url" id="social_instagram" v-model="form.social_instagram" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                                        <div v-if="form.errors.social_instagram" class="text-red-500 text-sm mt-1">{{ form.errors.social_instagram }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" :disabled="form.processing">
                                    Save Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
