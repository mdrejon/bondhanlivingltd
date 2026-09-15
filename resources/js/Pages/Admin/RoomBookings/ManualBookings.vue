<template>
    <AdminLayout>
        <div class="max-w-7xl space-y-6">
            <div class="flex items-center justify-between">
                <h1 class="text-lg font-semibold text-gray-800">Manual Bookings</h1>
                <div class="flex items-center gap-3">
                    <BookingPdfExportModal type="manual" title="Manual Bookings List" />
                    <Link :href="route('admin.room-bookings.create')"
                        class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                        + New Booking
                    </Link>
                </div>
            </div>

            <!-- Flash -->
            <div v-if="$page.props.flash?.success"
                class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded">
                {{ $page.props.flash.success }}
            </div>

            <!-- Stats Strip -->
            <div class="grid grid-cols-4 sm:grid-cols-8 gap-3">
                <div class="bg-white rounded-lg shadow-sm p-3 text-center border border-gray-100">
                    <div class="text-xl font-bold text-gray-700">{{ bookings.length }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">Total Manual</div>
                </div>
                <div class="bg-yellow-50 rounded-lg shadow-sm p-3 text-center border border-yellow-200">
                    <div class="text-xl font-bold text-yellow-600">{{ statusCount('pending') }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">Pending</div>
                </div>
                <div class="bg-blue-50 rounded-lg shadow-sm p-3 text-center border border-blue-200">
                    <div class="text-xl font-bold text-blue-600">{{ statusCount('confirmed') }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">Confirmed</div>
                </div>
                <div class="bg-purple-50 rounded-lg shadow-sm p-3 text-center border border-purple-200">
                    <div class="text-xl font-bold text-purple-600">{{ statusCount('payment_pending') }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">Pay Pending</div>
                </div>
                <div class="bg-green-50 rounded-lg shadow-sm p-3 text-center border border-green-200">
                    <div class="text-xl font-bold text-green-600">{{ statusCount('checked_in') }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">Checked In</div>
                </div>
                <div class="bg-gray-50 rounded-lg shadow-sm p-3 text-center border border-gray-200">
                    <div class="text-xl font-bold text-gray-500">{{ statusCount('checked_out') }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">Checked Out</div>
                </div>
                <div class="bg-red-50 rounded-lg shadow-sm p-3 text-center border border-red-200">
                    <div class="text-xl font-bold text-red-500">{{ statusCount('cancelled') }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">Cancelled</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="flex flex-wrap gap-3">
                <select v-model="filterStatus" class="input">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="payment_pending">Payment Pending</option>
                    <option value="checked_in">Checked In</option>
                    <option value="checked_out">Checked Out</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <select v-model="filterRoomType" class="input">
                    <option value="">All Room Types</option>
                    <option v-for="rt in roomTypes" :key="rt.id" :value="rt.id">{{ rt.name }}</option>
                </select>
                <input v-model="filterDateFrom" type="date" class="input" title="Check-in from" />
                <input v-model="filterDateTo" type="date" class="input" title="Check-in to" />
                <input v-model="search" type="text" placeholder="Search ref / name / phone..." class="input min-w-[220px]" />
            </div>

            <!-- Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
                <table class="w-full text-sm min-w-[1000px]">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600">#</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600">Reference</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600">Guest</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600">Room Type</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600">Room #</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600">Check-In</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600">Check-Out</th>
                            <th class="px-3 py-3 text-center text-xs font-semibold text-gray-600">Nights</th>
                            <th class="px-3 py-3 text-right text-xs font-semibold text-gray-600">Amount</th>
                            <th class="px-3 py-3 text-center text-xs font-semibold text-gray-600">Status</th>
                            <th class="px-3 py-3 text-center text-xs font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-if="filtered.length === 0">
                            <td colspan="11" class="px-3 py-8 text-center text-gray-400 text-sm">No manual bookings found.</td>
                        </tr>
                        <tr v-for="(b, i) in filtered" :key="b.id" class="hover:bg-gray-50">
                            <td class="px-3 py-3 text-xs text-gray-400">{{ i + 1 }}</td>
                            <td class="px-3 py-3 font-mono text-xs text-gray-700">{{ b.booking_reference }}</td>
                            <td class="px-3 py-3">
                                <div class="font-medium text-gray-800 text-xs">{{ b.customer?.name }}</div>
                                <div class="text-xs text-gray-400">{{ b.customer?.phone }}</div>
                            </td>
                            <td class="px-3 py-3 text-xs text-gray-600">{{ roomTypeSummary(b) }}</td>
                            <td class="px-3 py-3 text-xs text-gray-600">{{ roomNumberSummary(b) }}</td>
                            <td class="px-3 py-3 text-xs text-gray-600">{{ formatDate(b.check_in_date) }}</td>
                            <td class="px-3 py-3 text-xs text-gray-600">{{ formatDate(b.check_out_date) }}</td>
                            <td class="px-3 py-3 text-center text-xs text-gray-600">{{ b.total_nights }}</td>
                            <td class="px-3 py-3 text-right text-xs font-medium text-gray-800">
                                {{ b.currency === 'USD' ? '$' : '৳' }}{{ Number(b.total_amount).toLocaleString() }}
                            </td>
                            <td class="px-3 py-3 text-center">
                                <span :class="statusBadge(b.booking_status)"
                                    class="px-2 py-0.5 rounded-full text-xs font-medium whitespace-nowrap">
                                    {{ statusLabel(b.booking_status) }}
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
import BookingPdfExportModal from '@/Components/Admin/Shared/BookingPdfExportModal.vue';
import { roomTypeSummary, roomNumberSummary } from '@/Composables/bookingRoomsSummary';

const props = defineProps({
    bookings:  Array,
    roomTypes: Array,
});

const filterStatus   = ref('');
const filterRoomType = ref('');
const filterDateFrom = ref('');
const filterDateTo   = ref('');
const search         = ref('');

function statusCount(s) {
    return props.bookings.filter(b => b.booking_status === s).length;
}

const filtered = computed(() => props.bookings.filter(b => {
    if (filterStatus.value && b.booking_status !== filterStatus.value) return false;
    if (filterRoomType.value && !b.rooms?.some(r => r.room_type_id === filterRoomType.value)) return false;
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

function statusLabel(s) {
    return {
        pending:         'Pending',
        confirmed:       'Confirmed',
        payment_pending: 'Pay Pending',
        checked_in:      'Checked In',
        checked_out:     'Checked Out',
        cancelled:       'Cancelled',
    }[s] ?? s;
}

function statusBadge(s) {
    return {
        pending:         'bg-yellow-100 text-yellow-700',
        confirmed:       'bg-blue-100 text-blue-700',
        payment_pending: 'bg-purple-100 text-purple-700',
        checked_in:      'bg-green-100 text-green-700',
        checked_out:     'bg-gray-100 text-gray-600',
        cancelled:       'bg-red-100 text-red-600',
    }[s] ?? 'bg-gray-100 text-gray-600';
}
</script>

<style scoped>
.input { @apply border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors bg-white; }
select.input { @apply cursor-pointer; }
</style>
