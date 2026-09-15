<template>
    <AdminLayout title="Add User">
        <div class="max-w-2xl mx-auto space-y-6">
            <div class="flex items-center gap-3">
                <Link :href="route('admin.users.index')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </Link>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Add User</h1>
                    <p class="text-sm text-gray-500">Create a new admin user.</p>
                </div>
            </div>

            <form @submit.prevent="submit" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
                <div>
                    <label class="label">Full Name <span class="text-red-500">*</span></label>
                    <input v-model="form.name" type="text" class="input" placeholder="Enter full name" required />
                    <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label class="label">Email Address <span class="text-red-500">*</span></label>
                    <input v-model="form.email" type="email" class="input" placeholder="Enter email address" required />
                    <p v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">Password <span class="text-red-500">*</span></label>
                        <input v-model="form.password" type="password" class="input" placeholder="Min 8 characters" required />
                        <p v-if="form.errors.password" class="text-red-500 text-xs mt-1">{{ form.errors.password }}</p>
                    </div>
                    <div>
                        <label class="label">Confirm Password <span class="text-red-500">*</span></label>
                        <input v-model="form.password_confirmation" type="password" class="input" placeholder="Repeat password" required />
                    </div>
                </div>

                <div>
                    <label class="label">Role</label>
                    <select v-model="form.role_id" class="input">
                        <option v-if="isSuperAdmin" :value="null">— Super Admin (no role restriction) —</option>
                        <option v-for="role in roles" :key="role.id" :value="role.id">
                            {{ role.name }}{{ role.is_super_admin ? ' (Super Admin)' : '' }}
                        </option>
                    </select>
                    <p v-if="form.errors.role_id" class="text-red-500 text-xs mt-1">{{ form.errors.role_id }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ isSuperAdmin ? 'Users without a role or with a Super Admin role can access all modules.' : 'Only roles you created for your own hotel are shown.' }}
                    </p>
                </div>

                <div v-if="isSuperAdmin">
                    <label class="label">Hotel</label>
                    <select v-model="form.hotel_id" class="input">
                        <option :value="null">— Not tied to a hotel (platform / government account) —</option>
                        <option v-for="hotel in hotels" :key="hotel.id" :value="hotel.id">{{ hotel.name }}</option>
                    </select>
                    <p v-if="form.errors.hotel_id" class="text-red-500 text-xs mt-1">{{ form.errors.hotel_id }}</p>
                    <p class="text-xs text-gray-500 mt-1">Which hotel this user works for. Leave blank for Super Admin or government accounts.</p>
                </div>
                <p v-else class="text-xs text-gray-400">This user will be added to your own hotel.</p>

                <div class="flex items-center gap-3">
                    <input v-model="form.is_active" type="checkbox" id="is_active" class="w-4 h-4 rounded border-gray-300 text-blue-600" />
                    <label for="is_active" class="text-sm text-gray-700 font-medium">Active (can log in)</label>
                </div>

                <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                    <button type="submit" :disabled="form.processing" class="btn-primary">
                        {{ form.processing ? 'Creating...' : 'Create User' }}
                    </button>
                    <Link :href="route('admin.users.index')" class="btn-secondary">Cancel</Link>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

defineProps({
    roles: Array,
    hotels: Array,
    isSuperAdmin: { type: Boolean, default: false },
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role_id: null,
    hotel_id: null,
    is_active: true,
});

function submit() {
    form.post(route('admin.users.store'));
}
</script>

<style scoped>
.label { @apply block text-sm font-medium text-gray-700 mb-1.5; }
.input { @apply w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors bg-white; }
select.input { @apply cursor-pointer; }
.btn-primary   { @apply inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors disabled:opacity-60; }
.btn-secondary { @apply inline-flex items-center px-5 py-2.5 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg border border-gray-300 transition-colors; }
</style>
