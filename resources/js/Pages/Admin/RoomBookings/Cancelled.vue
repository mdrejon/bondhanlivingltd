<template>
    <AdminLayout>
        <div class="max-w-7xl space-y-6">
            <div class="flex items-center justify-between">
                <h1 class="text-lg font-semibold text-gray-800">Cancelled Bookings</h1>
            </div>

            <!-- Flash -->
            <div v-if="$page.props.flash?.success"
                class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded">
                {{ $page.props.flash.success }}
            </div>

            <!-- Stats Strip -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="bg-red-50 rounded-lg shadow-sm p-4 text-center border border-red-200">
                    <div class="text-2xl font-bold text-red-600">{{ bookings.length }}</div>
                    <div class="text-xs text-gray-500 mt-1">Total Cancelled</div>
                </div>
                <div class="bg-green-50 rounded-lg shadow-sm p-4 text-center border border-green-200">
                    <div class="text-2xl font-bold text-green-600">{{ onlineCancelledCount }}</div>
                    <div class="text-xs text-gray-500 mt-1">Online Cancelled</div>
                </div>
                <div class="bg-blue-50 rounded-lg shadow-sm p-4 text-center border border-blue-200">
                    <div class="text-2xl font-bold text-blue-600">{{ manualCancelledCount }}</div>
                    <div class="text-xs text-gray-500 mt-1">Manual Cancelled</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="flex flex-wrap gap-3">
                <select v-model="filterSource" class="input">
                    <option value="">All Sources</option>
                    <option value="web">Online</option>
                    <option value="admin">Manual</option>
                </select>
                <input v-model="filterDateFrom" type="date" class="input" title="Check-in from" />
                <input v-model="filterDateTo" type="date" class="input" title="Check-in to" />
                <input v-model="search" type="text" placeholder="Search ref / name / phone..." class="input min-w-[220px]" />
            </div>

            <!-- Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
                <table class="w-full text-sm min-w-[900px]">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600">#</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600">Reference</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600">Guest</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600">Room Type</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600">Check-In</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600">Check-Out</th>
                            <th class="px-3 py-3 text-center text-xs font-semibold text-gray-600">Nights</th>
                            <th class="px-3 py-3 text-right text-xs font-semibold text-gray-600">Amount</th>
                            <th class="px-3 py-3 text-center text-xs font-semibold text-gray-600">Source</th>
                            <th class="px-3 py-3 text-center text-xs font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-if="filtered.length === 0">
                            <td colspan="10" class="px-3 py-8 text-center text-gray-400 text-sm">No cancelled bookings found.</td>
                        </tr>
                        <tr v-for="(b, i) in filtered" :key="b.id" class="hover:bg-gray-50">
                            <td class="px-3 py-3 text-xs text-gray-400">{{ i + 1 }}</td>
                            <td class="px-3 py-3 font-mono text-xs text-gray-700">{{ b.booking_reference }}</td>
                            <td class="px-3 py-3">
                                <div class="font-medium text-gray-800 text-xs">{{ b.customer?.name }}</div>
                                <div class="text-xs text-gray-400">{{ b.customer?.phone }}</div>
                            </td>
                            <td class="px-3 py-3 text-xs text-gray-600">{{ roomTypeSummary(b) }}</td>
                            <td class="px-3 py-3 text-xs text-gray-600">{{ formatDate(b.check_in_date) }}</td>
                            <td class="px-3 py-3 text-xs text-gray-600">{{ formatDate(b.check_out_date) }}</td>
                            <td class="px-3 py-3 text-center text-xs text-gray-600">{{ b.total_nights }}</td>
                            <td class="px-3 py-3 text-right text-xs font-medium text-gray-800">
                                ৳{{ Number(b.total_amount).toLocaleString() }}
                            </td>
                            <td class="px-3 py-3 text-center">
                                <span :class="b.source === 'web'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-blue-100 text-blue-700'"
                                    class="px-2 py-0.5 rounded-full text-xs font-medium">
                                    {{ b.source === 'web' ? 'Online' : 'Manual' }}
                                </span>
                            </td>
                            <td class="px-3 py-3 text-center">
                                <Link :href="route('admin.room-bookings.show', b.id)"
                                    class="text-xs px-2 py-1 rounded border border-blue-300 text-blue-600 hover:bg-blue-50">
                                    View
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import { roomTypeSummary } from '@/Composables/bookingRoomsSummary';

const props = defineProps({
    bookings: Array,
});

const filterSource   = ref('');
const filterDateFrom = ref('');
const filterDateTo   = ref('');
const search         = ref('');

const onlineCancelledCount = computed(() => props.bookings.filter(b => b.source === 'web').length);
const manualCancelledCount = computed(() => props.bookings.filter(b => b.source === 'admin').length);

const filtered = computed(() => props.bookings.filter(b => {
    if (filterSource.value && b.source !== filterSource.value) return false;
    if (filterDateFrom.value && b.check_in_date < filterDateFrom.value) return false;
    if (filterDateTo.value && b.check_in_date > filterDateTo.value) return false;
    if (search.value) {
        const q = search.value.toLowerCase();
        const match = b.booking_reference?.toLowerCase().includes(q)
            || b.customer?.name?.toLowerCase().includes(q)
            || b.customer?.phone?.includes(q);
        if (!match) return false;
    }
    return true;
}));

function formatDate(d) {
    if (!d) return '—';
    return new Date(d).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
}
</script>

<style scoped>
.input { @apply border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors bg-white; }
select.input { @apply cursor-pointer; }
</style>
