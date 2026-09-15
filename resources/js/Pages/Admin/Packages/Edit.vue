<template>
    <AdminLayout>
        <div class="bg-white rounded-lg shadow-sm p-6 max-w-2xl">
            <h1 class="text-lg font-semibold text-gray-800 mb-6">Edit Package</h1>
            <p class="text-gray-400 text-sm">Package edit form goes here.</p>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
defineProps({ packageId: [String, Number] });
</script>
