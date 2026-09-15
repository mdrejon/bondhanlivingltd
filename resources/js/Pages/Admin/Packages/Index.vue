<template>
    <AdminLayout>
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-lg font-semibold text-gray-800">Packages Management</h1>
                <a
                    :href="route('admin.packages.create')"
                    class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors"
                >
                    + Add Package
                </a>
            </div>
            <DataTable :columns="columns" :rows="[]" />
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import DataTable   from '@/Components/Admin/Shared/DataTable.vue';

const columns = [
    { key: 'name',     label: 'Package Name' },
    { key: 'duration', label: 'Duration' },
    { key: 'cost',     label: 'Cost' },
    { key: 'status',   label: 'Status' },
    { key: 'action',   label: 'Actions' },
];
</script>
