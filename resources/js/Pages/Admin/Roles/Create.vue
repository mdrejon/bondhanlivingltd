<template>
    <AdminLayout title="Add Role">
        <div class="max-w-4xl mx-auto space-y-6">
            <div class="flex items-center gap-3">
                <Link :href="route('admin.roles.index')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </Link>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Add Role</h1>
                    <p class="text-sm text-gray-500">Create a new role and define its permissions.</p>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Basic Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
                    <h2 class="text-base font-semibold text-gray-800">Role Information</h2>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Role Name <span class="text-red-500">*</span></label>
                            <input v-model="form.name" type="text" class="input" placeholder="e.g. Content Manager" required />
                            <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
                        </div>
                        <div>
                            <label class="label">Description</label>
                            <input v-model="form.description" type="text" class="input" placeholder="Brief description of this role" />
                        </div>
                    </div>

                    <div class="flex items-center gap-6">
                        <label v-if="canGrantSuperAdmin" class="flex items-center gap-2 cursor-pointer">
                            <input v-model="form.is_super_admin" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-purple-600" />
                            <span class="text-sm text-gray-700 font-medium">Super Admin</span>
                            <span class="text-xs text-gray-400">(full access to all modules)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input v-model="form.is_active" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-blue-600" />
                            <span class="text-sm text-gray-700 font-medium">Active</span>
                        </label>
                    </div>
                    <p v-if="!canGrantSuperAdmin" class="text-xs text-gray-400">
                        You can only grant permissions you yourself currently have — modules you don't have access to aren't listed below.
                    </p>
                </div>

                <!-- Permissions Matrix -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <div>
                            <h2 class="text-base font-semibold text-gray-800">Module Permissions</h2>
                            <p class="text-xs text-gray-500 mt-0.5">Grant access to specific modules and actions.</p>
                        </div>
                        <div v-if="form.is_super_admin" class="text-sm text-purple-600 font-medium">
                            Super Admin has full access — permissions below are ignored.
                        </div>
                        <div v-else class="flex items-center gap-4 text-xs text-gray-500">
                            <button type="button" @click="selectAll(true)" class="text-blue-600 hover:underline">Select All</button>
                            <button type="button" @click="selectAll(false)" class="text-red-600 hover:underline">Clear All</button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-1/3">Module</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">View</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Create</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Edit</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Delete</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr v-for="(perm, idx) in form.permissions" :key="perm.key"
                                    :class="idx % 2 === 0 ? 'bg-white' : 'bg-gray-50/50'"
                                    class="hover:bg-blue-50/30 transition-colors">
                                    <td class="px-6 py-3">
                                        <span class="text-sm font-medium text-gray-800">{{ modules[idx]?.name }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <input v-if="modules[idx]?.actions?.includes('view')" type="checkbox" v-model="perm.can_view" @change="onViewChange(perm)"
                                            class="w-4 h-4 rounded border-gray-300 text-blue-600" :disabled="form.is_super_admin" />
                                        <span v-else class="text-gray-300">—</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <input v-if="modules[idx]?.actions?.includes('create')" type="checkbox" v-model="perm.can_create" @change="ensureView(perm)"
                                            class="w-4 h-4 rounded border-gray-300 text-blue-600" :disabled="form.is_super_admin" />
                                        <span v-else class="text-gray-300">—</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <input v-if="modules[idx]?.actions?.includes('edit')" type="checkbox" v-model="perm.can_edit" @change="ensureView(perm)"
                                            class="w-4 h-4 rounded border-gray-300 text-blue-600" :disabled="form.is_super_admin" />
                                        <span v-else class="text-gray-300">—</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <input v-if="modules[idx]?.actions?.includes('delete')" type="checkbox" v-model="perm.can_delete" @change="ensureView(perm)"
                                            class="w-4 h-4 rounded border-gray-300 text-blue-600" :disabled="form.is_super_admin" />
                                        <span v-else class="text-gray-300">—</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" :disabled="form.processing" class="btn-primary">
                        {{ form.processing ? 'Creating...' : 'Create Role' }}
                    </button>
                    <Link :href="route('admin.roles.index')" class="btn-secondary">Cancel</Link>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    modules: Array,
    canGrantSuperAdmin: { type: Boolean, default: false },
});

const form = useForm({
    name:           '',
    description:    '',
    is_super_admin: false,
    is_active:      true,
    permissions: props.modules.map(m => ({
        key:        m.key,
        can_view:   false,
        can_create: false,
        can_edit:   false,
        can_delete: false,
    })),
});

function onViewChange(perm) {
    if (!perm.can_view) {
        perm.can_create = false;
        perm.can_edit   = false;
        perm.can_delete = false;
    }
}

function ensureView(perm) {
    if (perm.can_create || perm.can_edit || perm.can_delete) {
        perm.can_view = true;
    }
}

function selectAll(value) {
    form.permissions.forEach(p => {
        p.can_view   = value;
        p.can_create = value;
        p.can_edit   = value;
        p.can_delete = value;
    });
}

function submit() {
    form.post(route('admin.roles.store'));
}
</script>

<style scoped>
.label { @apply block text-sm font-medium text-gray-700 mb-1.5; }
.input { @apply w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors bg-white; }
.btn-primary   { @apply inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors disabled:opacity-60; }
.btn-secondary { @apply inline-flex items-center px-5 py-2.5 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg border border-gray-300 transition-colors; }
</style>
