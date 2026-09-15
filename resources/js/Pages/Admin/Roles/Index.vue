<template>
    <AdminLayout title="Roles">
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Roles & Permissions</h1>
                    <p class="text-sm text-gray-500 mt-1">Define roles and control module access.</p>
                </div>
                <Link :href="route('admin.roles.create')" class="btn-primary">
                    + Add Role
                </Link>
            </div>

            <div v-if="$page.props.flash?.success" class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
                {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash?.error" class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
                {{ $page.props.flash.error }}
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="role in roles" :key="role.id"
                    class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex flex-col gap-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-semibold text-gray-900">{{ role.name }}</h3>
                                <span v-if="role.is_super_admin" class="badge-purple">Super Admin</span>
                                <span v-if="!role.is_active" class="badge-red">Inactive</span>
                            </div>
                            <p v-if="role.description" class="text-xs text-gray-500 mt-1">{{ role.description }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>{{ role.users_count }} {{ role.users_count === 1 ? 'user' : 'users' }}</span>
                    </div>

                    <div class="flex items-center gap-3 pt-3 border-t border-gray-100">
                        <Link :href="route('admin.roles.edit', role.id)" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Edit Permissions
                        </Link>
                        <button v-if="role.users_count === 0"
                            @click="deleteRole(role)"
                            class="text-red-600 hover:text-red-800 text-sm font-medium">
                            Delete
                        </button>
                        <span v-else class="text-gray-400 text-xs">(has users)</span>
                    </div>
                </div>

                <div v-if="roles.length === 0" class="col-span-3 bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center text-gray-400 text-sm">
                    No roles found. Create your first role.
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';

defineProps({
    roles: Array,
});

function deleteRole(role) {
    if (confirm(`Delete role "${role.name}"? This cannot be undone.`)) {
        router.delete(route('admin.roles.destroy', role.id));
    }
}
</script>

<style scoped>
.btn-primary  { @apply inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors; }
.badge-purple { @apply inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800; }
.badge-red    { @apply inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800; }
</style>
