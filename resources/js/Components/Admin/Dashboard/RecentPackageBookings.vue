<template>
    <div class="bg-white rounded-lg shadow-sm p-5">
        <h2 class="text-base font-semibold text-gray-800 mb-0.5">Recent Package Bookings</h2>
        <p class="text-xs text-gray-400 mb-4">Top 10 latest package bookings</p>

        <div class="overflow-x-auto"><table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left py-2 px-3 text-gray-500 font-medium">Package</th>
                    <th class="text-left py-2 px-3 text-gray-500 font-medium">Cost</th>
                    <th class="text-left py-2 px-3 text-gray-500 font-medium">Payment Status</th>
                    <th class="text-left py-2 px-3 text-gray-500 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="(booking, i) in bookings"
                    :key="i"
                    class="border-b border-gray-50 hover:bg-gray-50 transition-colors"
                >
                    <td class="py-3 px-3 text-gray-700">{{ booking.package }}</td>
                    <td class="py-3 px-3 text-gray-600">{{ booking.cost }}</td>
                    <td class="py-3 px-3">
                        <StatusBadge :status="booking.status" />
                    </td>
                    <td class="py-3 px-3">
                        <ActionDropdown :booking-id="i" />
                    </td>
                </tr>
            </tbody>
        </table></div>
    </div>
</template>

<script setup>
import StatusBadge    from '@/Components/Admin/Shared/StatusBadge.vue';
import ActionDropdown from '@/Components/Admin/Shared/ActionDropdown.vue';

defineProps({
    bookings: { type: Array, default: () => [] },
});
</script>
