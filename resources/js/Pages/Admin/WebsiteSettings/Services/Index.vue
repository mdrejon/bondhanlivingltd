<template>
    <Head title="Services" />

    <AdminLayout>
        <div class="flex justify-between items-center bg-white p-6 shadow-sm sm:rounded-lg mb-6 max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Services</h2>
            <Link
                :href="route('admin.website-settings.services.create')"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded"
            >
                Add New Service
            </Link>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 overflow-x-auto">
                        <table class="w-full whitespace-nowrap">
                            <thead>
                                <tr class="text-left font-bold">
                                    <th class="pb-4 pt-6 px-6">Image</th>
                                    <th class="pb-4 pt-6 px-6">Title</th>
                                    <th class="pb-4 pt-6 px-6">Category</th>
                                    <th class="pb-4 pt-6 px-6">Status</th>
                                    <th class="pb-4 pt-6 px-6">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="service in services.data" :key="service.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                                    <td class="border-t">
                                        <div class="px-6 py-4 flex items-center">
                                            <img v-if="service.image" :src="`/storage/${service.image}`" class="w-16 h-16 object-cover rounded-md" />
                                            <div v-else class="w-16 h-16 bg-gray-200 rounded-md flex items-center justify-center text-gray-500 text-xs">No Image</div>
                                        </div>
                                    </td>
                                    <td class="border-t">
                                        <span class="px-6 py-4 flex items-center">
                                            {{ service.title }}
                                        </span>
                                    </td>
                                    <td class="border-t">
                                        <span class="px-6 py-4 flex items-center">
                                            {{ service.category }}
                                        </span>
                                    </td>
                                    <td class="border-t">
                                        <div class="px-6 py-4 flex items-center">
                                            <button
                                                @click="toggleStatus(service)"
                                                :class="[
                                                    'px-2 py-1 rounded-full text-xs font-semibold',
                                                    service.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                                ]"
                                            >
                                                {{ service.status ? 'Active' : 'Inactive' }}
                                            </button>
                                        </div>
                                    </td>
                                    <td class="border-t w-px">
                                        <div class="px-6 py-4 flex items-center gap-4">
                                            <Link
                                                :href="route('admin.website-settings.services.edit', service.id)"
                                                class="text-indigo-600 hover:text-indigo-900"
                                            >
                                                Edit
                                            </Link>
                                            <button
                                                @click="destroy(service)"
                                                class="text-red-600 hover:text-red-900"
                                            >
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="services.data.length === 0">
                                    <td class="px-6 py-4 border-t text-center text-gray-500" colspan="5">No services found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';

const props = defineProps({
    services: Object,
});

const toggleStatus = (service) => {
    router.patch(route('admin.website-settings.services.toggle', service.id), {}, { preserveScroll: true });
};

const destroy = (service) => {
    if (confirm('Are you sure you want to delete this service?')) {
        router.delete(route('admin.website-settings.services.destroy', service.id), { preserveScroll: true });
    }
};
</script>
