<template>
    <AdminLayout>
        <div class="space-y-4">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-gray-800">Services Management</h1>
                    <p class="text-xs text-gray-400 mt-0.5">Manage hotel services shown on service listing, detail and home page</p>
                </div>
                <a :href="route('admin.services.create')"
                    class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors">
                    + Add Service
                </a>
            </div>

            <!-- Flash -->
            <div v-if="$page.props.flash?.success"
                class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded">
                {{ $page.props.flash.success }}
            </div>

            <!-- Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">#</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Image</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Title</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Short Desc</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Order</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Featured</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Status</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(svc, i) in services" :key="svc.id"
                            class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-gray-500">{{ i + 1 }}</td>
                            <td class="px-4 py-3">
                                <img v-if="svc.image" :src="`/storage/${svc.image}`"
                                    class="w-20 h-12 object-cover rounded" alt="" />
                                <div v-else class="w-20 h-12 bg-gray-100 rounded flex items-center justify-center text-xs text-gray-400">
                                    No image
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-700 max-w-[180px]">
                                <p class="font-medium truncate">{{ svc.title }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ svc.slug }}</p>
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs max-w-[200px]">
                                <span class="line-clamp-2">{{ svc.short_desc || '—' }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ svc.sort_order }}</td>
                            <td class="px-4 py-3">
                                <span :class="svc.is_featured
                                    ? 'bg-yellow-100 text-yellow-700'
                                    : 'bg-gray-100 text-gray-400'"
                                    class="px-2 py-0.5 rounded-full text-xs font-semibold">
                                    {{ svc.is_featured ? 'Featured' : 'No' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <button @click="toggleStatus(svc)"
                                    :class="svc.is_active
                                        ? 'bg-green-100 text-green-700 hover:bg-green-200'
                                        : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                                    class="px-2.5 py-0.5 rounded-full text-xs font-semibold transition-colors">
                                    {{ svc.is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a :href="route('admin.services.edit', svc.id)"
                                        class="px-3 py-1.5 bg-indigo-600 text-white text-xs rounded hover:bg-indigo-700">
                                        Edit
                                    </a>
                                    <button @click="confirmDelete(svc)"
                                        class="px-3 py-1.5 bg-red-600 text-white text-xs rounded hover:bg-red-700">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!services.length">
                            <td colspan="8" class="px-4 py-10 text-center text-gray-400">
                                No services yet. Add your first service.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Delete modal -->
            <div v-if="deleteTarget" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-6 w-80 shadow-xl">
                    <h3 class="font-semibold text-gray-800 mb-2">Delete Service</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Are you sure you want to delete
                        "<strong>{{ deleteTarget.title }}</strong>"?
                        This cannot be undone.
                    </p>
                    <div class="flex justify-end gap-2">
                        <button @click="deleteTarget = null"
                            class="px-4 py-2 text-sm text-gray-600 border rounded hover:bg-gray-50">
                            Cancel
                        </button>
                        <button @click="doDelete"
                            class="px-4 py-2 text-sm bg-red-600 text-white rounded hover:bg-red-700">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';

defineProps({
    services: { type: Array, default: () => [] },
});

const deleteTarget = ref(null);

function confirmDelete(svc) {
    deleteTarget.value = svc;
}

function doDelete() {
    router.delete(route('admin.services.destroy', deleteTarget.value.id), {
        onFinish: () => { deleteTarget.value = null; },
    });
}

function toggleStatus(svc) {
    router.patch(route('admin.services.toggle', svc.id));
}
</script>
