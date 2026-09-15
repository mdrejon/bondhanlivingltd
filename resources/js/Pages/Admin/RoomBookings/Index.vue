<template>
    <AdminLayout>
        <div class="max-w-7xl space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-gray-800">Room Bookings</h1>
                    <p class="text-xs text-gray-500">Manage all reservations and guest document verification status.</p>
                </div>
                <Link :href="route('admin.room-bookings.create')"
                    class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 font-semibold shadow-sm">
                    + New Booking
                </Link>
            </div>

            <!-- Flash -->
            <div v-if="$page.props.flash?.success"
                class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg">
                {{ $page.props.flash.success }}
            </div>

            <!-- Stats Strip -->
            <div class="grid grid-cols-3 md:grid-cols-5 xl:grid-cols-9 gap-3">
                <div class="bg-white rounded-lg shadow-sm p-3 text-center border border-gray-100">
                    <div class="text-xl font-bold text-gray-700">{{ bookings.length }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">Total</div>
                </div>
                <div class="bg-yellow-50 rounded-lg shadow-sm p-3 text-center border border-yellow-200">
                    <div class="text-xl font-bold text-yellow-600">{{ stats.pending }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">Pending</div>
                </div>
                <div class="bg-blue-50 rounded-lg shadow-sm p-3 text-center border border-blue-200">
                    <div class="text-xl font-bold text-blue-600">{{ stats.confirmed }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">Confirmed</div>
                </div>
                <div class="bg-purple-50 rounded-lg shadow-sm p-3 text-center border border-purple-200">
                    <div class="text-xl font-bold text-purple-600">{{ stats.payment_pending }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">Pay Pending</div>
                </div>
                <div class="bg-green-50 rounded-lg shadow-sm p-3 text-center border border-green-200">
                    <div class="text-xl font-bold text-green-600">{{ stats.checked_in }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">Checked In</div>
                </div>
                <div class="bg-gray-50 rounded-lg shadow-sm p-3 text-center border border-gray-200">
                    <div class="text-xl font-bold text-gray-500">{{ stats.checked_out }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">Checked Out</div>
                </div>
                <div class="bg-red-50 rounded-lg shadow-sm p-3 text-center border border-red-200">
                    <div class="text-xl font-bold text-red-500">{{ stats.cancelled }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">Cancelled</div>
                </div>
                <div class="bg-emerald-50 rounded-lg shadow-sm p-3 text-center border border-emerald-200">
                    <div class="text-sm font-bold text-emerald-600">{{ todayCheckIns }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">Today In</div>
                    <div class="text-sm font-bold text-orange-500 mt-1">{{ todayCheckOuts }}</div>
                    <div class="text-xs text-gray-400">Today Out</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="flex flex-wrap gap-3 bg-white p-3.5 rounded-xl shadow-sm border border-gray-200 items-center">
                <select v-model="filterStatus" class="input max-w-xs">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="payment_pending">Payment Pending</option>
                    <option value="checked_in">Checked In</option>
                    <option value="checked_out">Checked Out</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <select v-model="filterDocs" class="input max-w-xs">
                    <option value="">All Document Statuses</option>
                    <option value="uploaded">✓ Identity Uploaded</option>
                    <option value="pending">⚠️ Identity Pending</option>
                </select>
                <input v-model="search" type="text" placeholder="Search ref / name / phone..." class="input max-w-xs" />
                <input v-model="filterDate" type="date" class="input" title="Filter by check-in date" />
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-x-auto">
                <table class="w-full text-sm min-w-[950px]">
                    <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase font-semibold text-gray-600 tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left">Reference</th>
                            <th class="px-4 py-3 text-left">Guest &amp; Identity</th>
                            <th class="px-4 py-3 text-left">Room #</th>
                            <th class="px-4 py-3 text-left">Check In</th>
                            <th class="px-4 py-3 text-left">Check Out</th>
                            <th class="px-4 py-3 text-center">Nights</th>
                            <th class="px-4 py-3 text-right">Amount</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-if="filtered.length === 0">
                            <td colspan="9" class="px-4 py-8 text-center text-gray-400 text-sm">No bookings found matching filters.</td>
                        </tr>
                        <tr v-for="b in filtered" :key="b.id" class="hover:bg-gray-50/80 transition-colors">
                            <td class="px-4 py-3.5 font-mono text-xs text-gray-700 font-medium">{{ b.booking_reference }}</td>
                            
                            <!-- Guest & Identity Indicator -->
                            <td class="px-4 py-3.5 align-top">
                                <div class="font-semibold text-gray-900 text-xs">{{ b.customer?.name || '—' }}</div>
                                <div class="text-xs text-gray-500 font-mono">{{ b.customer?.phone }}</div>
                                
                                <!-- Identity Upload Indicator Badge -->
                                <div class="mt-1">
                                    <span v-if="hasUploadedDocs(b)" class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 shadow-2xs" :title="`Uploaded ${customerDocCount(b)} document(s)`">
                                        <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        ✓ Docs Uploaded ({{ customerDocCount(b) }})
                                    </span>
                                    <span v-else class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200 shadow-2xs" title="Identity documents not uploaded yet">
                                        <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        ⚠️ Docs Pending
                                    </span>
                                </div>
                            </td>

                            <td class="px-4 py-3.5 align-top">
                                <div class="text-xs font-semibold text-blue-700">{{ roomNumberSummary(b) }}</div>
                                <div class="text-xs text-gray-500">{{ roomTypeSummary(b) }}</div>
                            </td>
                            <td class="px-4 py-3.5 align-top text-xs text-gray-700 font-medium">{{ formatDate(b.check_in_date) }}</td>
                            <td class="px-4 py-3.5 align-top text-xs text-gray-600">{{ formatDate(b.check_out_date) }}</td>
                            <td class="px-4 py-3.5 align-top text-center text-xs text-gray-700 font-medium">{{ b.total_nights }}</td>
                            <td class="px-4 py-3.5 align-top text-right text-xs font-semibold text-gray-800">
                                ৳{{ Number(b.total_amount).toLocaleString() }}
                            </td>
                            <td class="px-4 py-3.5 align-top text-center">
                                <span :class="statusBadge(b.booking_status)" class="px-2.5 py-0.5 rounded-full text-xs font-semibold whitespace-nowrap">
                                    {{ statusLabel(b.booking_status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 align-top text-center">
                                <Link :href="route('admin.room-bookings.show', b.id)"
                                    class="text-xs px-3 py-1.5 rounded-md border border-blue-200 text-blue-600 hover:bg-blue-50 font-medium transition-colors">
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
import { roomTypeSummary, roomNumberSummary } from '@/Composables/bookingRoomsSummary';

const props = defineProps({
    bookings:        Array,
    stats:           Object,
    todayCheckIns:   Number,
    todayCheckOuts:  Number,
});

const filterStatus = ref('');
const filterDocs   = ref('');
const filterDate   = ref('');
const search       = ref('');

function customerDocCount(b) {
    return b.customer?.documents?.length || 0;
}

function hasUploadedDocs(b) {
    return customerDocCount(b) > 0;
}

const filtered = computed(() => props.bookings.filter(b => {
    if (filterStatus.value && b.booking_status !== filterStatus.value) return false;
    if (filterDocs.value === 'uploaded' && !hasUploadedDocs(b)) return false;
    if (filterDocs.value === 'pending' && hasUploadedDocs(b)) return false;
    if (filterDate.value && b.check_in_date !== filterDate.value) return false;
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
    return { pending: 'Pending', confirmed: 'Confirmed', payment_pending: 'Pay Pending', checked_in: 'Checked In', checked_out: 'Checked Out', cancelled: 'Cancelled' }[s] ?? s;
}

function statusBadge(s) {
    return {
        pending:         'bg-yellow-100 text-yellow-700',
        confirmed:       'bg-blue-100 text-blue-700',
        payment_pending: 'bg-purple-100 text-purple-700',
        checked_in:      'bg-green-100 text-green-700',
        checked_out:     'bg-gray-100 text-gray-500',
        cancelled:       'bg-red-100 text-red-500',
    }[s] ?? 'bg-gray-100 text-gray-500';
}
</script>

<style scoped>
.input { @apply border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors bg-white; }
</style>
