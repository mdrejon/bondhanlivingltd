<template>
    <AdminLayout title="Hotel-wise Report">
        <div class="max-w-4xl space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-gray-800">Hotel-wise Report</h1>
                    <p class="text-sm text-gray-500">Every hotel in your jurisdiction, with current occupancy.</p>
                </div>
                <ExportLinks type="hotels" />
            </div>

            <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Hotel Name</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">District / Upazila</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600">Current Guests</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600">Today Check-in</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600">Today Check-out</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-if="rows.length === 0">
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400 text-sm">No hotels in your jurisdiction.</td>
                        </tr>
                        <tr v-for="r in rows" :key="r.hotel_id" class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-800">{{ r.hotel_name }}</td>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ r.district }}<span v-if="r.upazila">, {{ r.upazila }}</span></td>
                            <td class="px-4 py-3 text-right font-medium">{{ r.current_guests }}</td>
                            <td class="px-4 py-3 text-right text-green-600">{{ r.today_checkin }}</td>
                            <td class="px-4 py-3 text-right text-gray-500">{{ r.today_checkout }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import ExportLinks from '@/Components/Admin/Government/ExportLinks.vue';

defineProps({ rows: Array });
</script>
