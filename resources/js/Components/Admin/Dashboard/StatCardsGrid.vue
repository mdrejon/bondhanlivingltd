<template>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <StatCard
            label="Total Rooms"
            :value="stats.total_rooms"
            :icon="icons.building"
            bg-color="bg-blue-500"
        />
        <StatCard
            label="Room Types"
            :value="stats.total_room_types"
            :icon="icons.roomType"
            bg-color="bg-green-500"
        />
        <StatCard
            label="All Room Bookings"
            :value="stats.all_room_bookings"
            :icon="icons.calendar"
            bg-color="bg-cyan-500"
        />
        <StatCard
            label="Paid Room Bookings"
            :value="stats.paid_room_bookings"
            :icon="icons.calendarCheck"
            bg-color="bg-purple-600"
        />
    </div>
</template>

<script setup>
import StatCard from './StatCard.vue';

defineProps({
    stats: { type: Object, required: true },
});

const icons = {
    building:      `<svg class="w-12 h-12" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2 20h20M4 20V6a2 2 0 012-2h12a2 2 0 012 2v14M8 20V10h8v10M8 6h8"/></svg>`,
    roomType:      `<svg class="w-12 h-12" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>`,
    calendar:      `<svg class="w-12 h-12" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>`,
    calendarCheck: `<svg class="w-12 h-12" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><polyline points="9 16 11 18 15 14"/></svg>`,
};
</script>
