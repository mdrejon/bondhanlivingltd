<template>
    <AdminLayout>
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h1 class="text-lg font-semibold text-gray-800 mb-4">Package Bookings</h1>
            <DataTable :columns="columns" :rows="[]" />
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import DataTable   from '@/Components/Admin/Shared/DataTable.vue';

const columns = [
    { key: 'package', label: 'Package' },
    { key: 'guest',   label: 'Guest' },
    { key: 'cost',    label: 'Cost' },
    { key: 'status',  label: 'Payment Status' },
    { key: 'action',  label: 'Actions' },
];
</script>
