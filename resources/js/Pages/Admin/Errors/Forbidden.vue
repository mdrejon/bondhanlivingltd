<template>
    <AdminLayout title="Access Denied">
        <div class="flex flex-col items-center justify-center min-h-[60vh] text-center">
            <div class="w-20 h-20 rounded-full bg-red-100 flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-2">Access Denied</h1>
            <p class="text-gray-500 text-base mb-8 max-w-sm">
                You don't have permission to access this page. Contact your administrator to request access.
            </p>

            <div class="flex items-center gap-3">
                <Link :href="route('admin.dashboard')" class="btn-primary">
                    Go to Dashboard
                </Link>
                <button @click="$router.back()" onclick="history.back()" class="btn-secondary">
                    Go Back
                </button>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';
</script>

<style scoped>
.btn-primary   { @apply inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors; }
.btn-secondary { @apply inline-flex items-center px-5 py-2.5 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg border border-gray-300 transition-colors; }
</style>
