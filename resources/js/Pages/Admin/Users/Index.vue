<template>
    <AdminLayout title="Users">
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Users</h1>
                    <p class="text-sm text-gray-500 mt-1">Manage admin users and their roles.</p>
                </div>
                <Link :href="route('admin.users.create')" class="btn-primary">
                    + Add User
                </Link>
            </div>

            <div v-if="$page.props.flash?.success" class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
                {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash?.error" class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
                {{ $page.props.flash.error }}
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hotel</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="(user, i) in users" :key="user.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-500">{{ i + 1 }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-semibold text-sm">
                                        {{ user.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ user.name }}</p>
                                        <p v-if="user.id === $page.props.auth.user.id" class="text-xs text-blue-600">(You)</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ user.email }}</td>
                            <td class="px-6 py-4">
                                <span v-if="!user.role_id || user.role?.is_super_admin" class="badge-purple">
                                    Super Admin
                                </span>
                                <span v-else class="badge-blue">
                                    {{ user.role?.name ?? '—' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ user.hotel?.name ?? '—' }}</td>
                            <td class="px-6 py-4">
                                <button @click="toggleStatus(user)"
                                    :class="user.is_active ? 'badge-green' : 'badge-red'"
                                    :disabled="user.id === $page.props.auth.user.id"
                                    class="cursor-pointer hover:opacity-80 transition-opacity disabled:cursor-not-allowed disabled:opacity-60">
                                    {{ user.is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <Link :href="route('admin.users.edit', user.id)" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Edit
                                    </Link>
                                    <button v-if="user.id !== $page.props.auth.user.id"
                                        @click="deleteUser(user)"
                                        class="text-red-600 hover:text-red-800 text-sm font-medium">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="users.length === 0">
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400 text-sm">No users found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';

defineProps({
    users: Array,
});

function toggleStatus(user) {
    if (confirm(`${user.is_active ? 'Deactivate' : 'Activate'} user "${user.name}"?`)) {
        router.patch(route('admin.users.toggle', user.id));
    }
}

function deleteUser(user) {
    if (confirm(`Delete user "${user.name}"? This cannot be undone.`)) {
        router.delete(route('admin.users.destroy', user.id));
    }
}
</script>

<style scoped>
.btn-primary { @apply inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors; }
.badge-purple { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800; }
.badge-blue   { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800; }
.badge-green  { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800; }
.badge-red    { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800; }
</style>
