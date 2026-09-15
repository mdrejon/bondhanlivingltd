<template>
    <AdminLayout title="Government Dashboard">
        <div class="space-y-6">
            <div>
                <h1 class="text-lg font-semibold text-gray-800">Government Monitoring Dashboard</h1>
                <p class="text-sm text-gray-500">Live occupancy across the {{ stats.hotel_count }} hotel(s) in your jurisdiction.</p>
            </div>

            <!-- Stat cards -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                <StatCard label="Today Check-ins" :value="stats.today_checkins" bg-color="bg-blue-500" :icon="icons.checkin" />
                <StatCard label="Today Check-outs" :value="stats.today_checkouts" bg-color="bg-cyan-500" :icon="icons.checkout" />
                <StatCard label="Currently Staying" :value="stats.current_guests" bg-color="bg-green-500" :icon="icons.guests" />
                <StatCard label="Foreign Guests" :value="stats.foreign_guests" bg-color="bg-purple-600" :icon="icons.globe" />
                <StatCard label="Hotels in Jurisdiction" :value="stats.hotel_count" bg-color="bg-gray-600" :icon="icons.hotel" />
            </div>

            <!-- Gender split -->
            <section class="bg-white rounded-lg shadow-sm p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-1">Guests by Gender</h2>
                <p class="text-xs text-gray-400 mb-3">Based on the primary registrant per room — companions' individual genders aren't separately captured.</p>
                <div class="flex gap-6 text-sm">
                    <div><span class="font-bold text-blue-700">{{ genderSplit.male }}</span> Male</div>
                    <div><span class="font-bold text-pink-600">{{ genderSplit.female }}</span> Female</div>
                    <div><span class="font-bold text-gray-500">{{ genderSplit.other }}</span> Other / Not specified</div>
                </div>
            </section>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Hotel-wise -->
                <section class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="text-sm font-semibold text-gray-700">Hotel-wise Occupancy</h2>
                        <Link :href="route('admin.government.hotels')" class="text-xs text-blue-600 hover:underline">Full Report</Link>
                    </div>
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500">Hotel</th>
                                <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">Guests</th>
                                <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">In</th>
                                <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">Out</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="h in perHotel" :key="h.hotel_id">
                                <td class="px-4 py-2 text-gray-700">{{ h.hotel_name }}</td>
                                <td class="px-4 py-2 text-right font-medium">{{ h.current_guests }}</td>
                                <td class="px-4 py-2 text-right text-green-600">{{ h.today_checkin }}</td>
                                <td class="px-4 py-2 text-right text-gray-500">{{ h.today_checkout }}</td>
                            </tr>
                            <tr v-if="perHotel.length === 0"><td colspan="4" class="px-4 py-6 text-center text-gray-400">No hotels in your jurisdiction yet.</td></tr>
                        </tbody>
                    </table>
                </section>

                <!-- District / Upazila -->
                <section class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100">
                        <h2 class="text-sm font-semibold text-gray-700">District-wise Occupancy</h2>
                    </div>
                    <table class="w-full text-sm mb-4">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500">District</th>
                                <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">Hotels</th>
                                <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">Guests</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="d in districtWise" :key="d.label">
                                <td class="px-4 py-2 text-gray-700">{{ d.label }}</td>
                                <td class="px-4 py-2 text-right text-gray-400 text-xs">{{ d.hotel_count }} hotel(s)</td>
                                <td class="px-4 py-2 text-right font-medium">{{ d.current_guests }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="px-5 py-3 border-t border-b border-gray-100">
                        <h2 class="text-sm font-semibold text-gray-700">Upazila-wise Occupancy</h2>
                    </div>
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500">Upazila</th>
                                <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">Hotels</th>
                                <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">Guests</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="u in upazilaWise" :key="u.label">
                                <td class="px-4 py-2 text-gray-700">{{ u.label }}</td>
                                <td class="px-4 py-2 text-right text-gray-400 text-xs">{{ u.hotel_count }} hotel(s)</td>
                                <td class="px-4 py-2 text-right font-medium">{{ u.current_guests }}</td>
                            </tr>
                        </tbody>
                    </table>
                </section>
            </div>

            <!-- 30 day trend -->
            <section class="bg-white rounded-lg shadow-sm p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-3">Last 30 Days</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="text-gray-500">
                                <th class="px-2 py-1 text-left">Date</th>
                                <th class="px-2 py-1 text-right">Check-ins</th>
                                <th class="px-2 py-1 text-right">Check-outs</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr v-for="d in trend" :key="d.date">
                                <td class="px-2 py-1 text-gray-600">{{ d.date }}</td>
                                <td class="px-2 py-1 text-right text-green-600">{{ d.check_ins }}</td>
                                <td class="px-2 py-1 text-right text-gray-500">{{ d.check_outs }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import StatCard from '@/Components/Admin/Dashboard/StatCard.vue';

defineProps({
    stats: Object,
    genderSplit: Object,
    perHotel: Array,
    districtWise: Array,
    upazilaWise: Array,
    trend: Array,
});

const icons = {
    checkin:  `<svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h11m0-9h1a2 2 0 012 2v10a2 2 0 01-2 2h-1"/></svg>`,
    checkout: `<svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H10m4 9H5a2 2 0 01-2-2V5a2 2 0 012-2h9"/></svg>`,
    guests:   `<svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`,
    globe:    `<svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>`,
    hotel:    `<svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/></svg>`,
};
</script>
