<template>
    <AdminLayout>
        <div class="space-y-4">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-gray-800">Teams</h1>
                    <p class="text-xs text-gray-400 mt-0.5">Manage team members</p>
                </div>
                <a
                    :href="route('admin.teams.create')"
                    class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors"
                >
                    + Add Team Member
                </a>
            </div>

            <!-- Flash message -->
            <div v-if="$page.props.flash?.success" class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded">
                {{ $page.props.flash.success }}
            </div>

            <!-- Teams table -->
            <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">#</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Image</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Name</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Designation</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Status</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(team, i) in teams"
                            :key="team.id"
                            class="border-b border-gray-50 hover:bg-gray-50 transition-colors"
                        >
                            <td class="px-4 py-3 text-gray-500">{{ i + 1 }}</td>
                            <td class="px-4 py-3">
                                <img
                                    v-if="team.image"
                                    :src="team.image.startsWith('http') || team.image.startsWith('assets') ? (team.image.startsWith('assets') ? `/${team.image}` : team.image) : `/storage/${team.image}`"
                                    class="w-12 h-12 object-cover rounded-full"
                                    alt=""
                                />
                                <div v-else class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-xs text-gray-400">
                                    No image
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-700 max-w-xs">
                                <p class="font-medium truncate">{{ team.name }}</p>
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ team.designation || '—' }}</td>
                            <td class="px-4 py-3">
                                <button
                                    @click="toggleStatus(team)"
                                    class="px-2.5 py-0.5 rounded-full text-xs font-semibold capitalize transition-colors"
                                    :class="team.status ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-red-100 text-red-700 hover:bg-red-200'"
                                >
                                    {{ team.status ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a
                                        :href="route('admin.teams.edit', team.id)"
                                        class="px-3 py-1.5 bg-indigo-600 text-white text-xs rounded hover:bg-indigo-700"
                                    >Edit</a>
                                    <button
                                        @click="confirmDelete(team)"
                                        class="px-3 py-1.5 bg-red-600 text-white text-xs rounded hover:bg-red-700"
                                    >Delete</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!teams.length">
                            <td colspan="6" class="px-4 py-10 text-center text-gray-400">No team members yet. Add your first team member.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Delete confirm modal -->
            <div v-if="deleteTarget" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-6 w-80 shadow-xl">
                    <h3 class="font-semibold text-gray-800 mb-2">Delete Team Member</h3>
                    <p class="text-sm text-gray-600 mb-4">Are you sure you want to delete "<strong>{{ deleteTarget.name }}</strong>"? This cannot be undone.</p>
                    <div class="flex justify-end gap-2">
                        <button @click="deleteTarget = null" class="px-4 py-2 text-sm text-gray-600 border rounded hover:bg-gray-50">Cancel</button>
                        <button @click="doDelete" class="px-4 py-2 text-sm bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
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
    teams: { type: Array, default: () => [] },
});

const deleteTarget = ref(null);

function confirmDelete(team) {
    deleteTarget.value = team;
}

function doDelete() {
    router.delete(route('admin.teams.destroy', deleteTarget.value.id), {
        onFinish: () => { deleteTarget.value = null; },
    });
}

function toggleStatus(team) {
    router.patch(route('admin.teams.toggle', team.id), {}, { preserveScroll: true });
}
</script>
