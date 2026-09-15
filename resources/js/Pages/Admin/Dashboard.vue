<template>
    <AdminLayout>
        <div class="space-y-6">
            <!-- Stats row -->
            <StatCardsGrid :stats="stats" />

            <!-- Tables row -->
            <div class="grid grid-cols-1 gap-6">
                <RecentRoomBookings :bookings="recentRoomBookings" />
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout        from '@/Layouts/Admin/AdminLayout.vue';
import StatCardsGrid      from '@/Components/Admin/Dashboard/StatCardsGrid.vue';
import RecentRoomBookings from '@/Components/Admin/Dashboard/RecentRoomBookings.vue';

defineProps({
    stats:              { type: Object, required: true },
    recentRoomBookings: { type: Array,  default: () => [] },
});
</script>
