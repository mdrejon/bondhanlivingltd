<template>
    <AdminLayout>
        <div class="max-w-4xl space-y-6">
            <h1 class="text-lg font-semibold text-gray-800">Booking History</h1>

            <!-- Search Bar -->
            <form @submit.prevent="doSearch" class="flex gap-3">
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Search by Booking Reference or ID..."
                    class="input flex-1"
                />
                <button type="submit"
                    class="px-5 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                    Search
                </button>
            </form>

            <!-- No search yet -->
            <div v-if="!search" class="bg-white rounded-xl border border-gray-200 p-10 text-center">
                <div class="text-gray-300 text-5xl mb-3">&#128269;</div>
                <p class="text-gray-500 text-sm">Enter a booking reference (e.g. HBW-20260628-ABC12) or booking ID to view its full history.</p>
            </div>

            <!-- Not found -->
            <div v-else-if="search && !booking"
                class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3">
                No booking found for "<strong>{{ search }}</strong>".
            </div>

            <!-- Booking found -->
            <template v-else-if="booking">
                <!-- Summary Card -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Booking Reference</p>
                            <p class="font-mono text-base font-bold text-gray-800">{{ booking.booking_reference }}</p>
                        </div>
                        <div class="flex gap-2">
                            <span :class="statusBadge(booking.booking_status)"
                                class="px-3 py-1 rounded-full text-xs font-semibold">
                                {{ statusLabel(booking.booking_status) }}
                            </span>
                            <span :class="booking.source === 'web' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700'"
                                class="px-3 py-1 rounded-full text-xs font-semibold">
                                {{ booking.source === 'web' ? 'Online' : 'Manual' }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                        <div>
                            <p class="text-xs text-gray-400">Guest</p>
                            <p class="font-medium text-gray-800">{{ booking.customer?.name }}</p>
                            <p class="text-xs text-gray-500">{{ booking.customer?.phone }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Rooms ({{ booking.rooms?.length ?? 0 }})</p>
                            <p v-for="line in booking.rooms" :key="line.id" class="font-medium text-gray-800">
                                {{ line.room_type?.name ?? 'Unassigned' }}
                                <span class="text-xs text-gray-500 font-normal">— {{ line.room?.room_number ?? 'unassigned' }}</span>
                            </p>
                            <p v-if="!booking.rooms?.length" class="text-xs text-gray-400 italic">No rooms</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Stay</p>
                            <p class="font-medium text-gray-800">{{ formatDate(booking.check_in_date) }} — {{ formatDate(booking.check_out_date) }}</p>
                            <p class="text-xs text-gray-500">{{ booking.total_nights }} night(s)</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Amount</p>
                            <p class="font-medium text-gray-800">৳{{ Number(booking.total_amount).toLocaleString() }}</p>
                            <p class="text-xs text-gray-500">Advance: ৳{{ Number(booking.advance_payment ?? 0).toLocaleString() }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Created At</p>
                            <p class="font-medium text-gray-800">{{ formatDateTime(booking.created_at) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <h2 class="text-sm font-semibold text-gray-700 mb-5">Activity Timeline &amp; Follow-ups</h2>

                    <div v-if="logs.length === 0" class="text-sm text-gray-400 text-center py-6">
                        No activity logs found.
                    </div>

                    <ol v-else class="relative border-l border-gray-200 space-y-6 ml-3">
                        <li v-for="(log, i) in logs" :key="i" class="ml-6">
                            <span :class="timelineIconBg(log.action)"
                                class="absolute -left-3 flex items-center justify-center w-6 h-6 rounded-full ring-4 ring-white text-xs">
                                {{ timelineIcon(log.action) }}
                            </span>

                            <!-- Follow-up entry -->
                            <div v-if="log.action === 'follow_up'"
                                class="bg-indigo-50 rounded-lg border border-indigo-200 p-3">
                                <div class="flex flex-wrap items-center justify-between gap-2 mb-1">
                                    <span class="text-xs font-bold text-indigo-700 uppercase tracking-wide">Follow-up Email Sent</span>
                                    <time class="text-xs text-gray-400">{{ formatDateTime(log.time) }}</time>
                                </div>
                                <p class="text-sm font-semibold text-gray-800 mb-1">{{ log.title }}</p>
                                <p class="text-xs text-gray-600 whitespace-pre-line">{{ log.description }}</p>
                            </div>

                            <!-- Regular activity entry -->
                            <div v-else class="bg-gray-50 rounded-lg border border-gray-100 p-3">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <p class="text-xs font-semibold text-gray-700 capitalize">
                                        {{ log.action.replace(/_/g, ' ') }}
                                    </p>
                                    <time class="text-xs text-gray-400">{{ formatDateTime(log.time) }}</time>
                                </div>
                                <p class="text-xs text-gray-600 mt-1">{{ log.description }}</p>
                                <div v-if="log.old_value || log.new_value" class="flex items-center gap-2 mt-1.5">
                                    <span v-if="log.old_value" class="text-xs bg-red-50 text-red-600 px-2 py-0.5 rounded">{{ log.old_value }}</span>
                                    <span v-if="log.old_value && log.new_value" class="text-gray-400 text-xs">&#8594;</span>
                                    <span v-if="log.new_value" class="text-xs bg-green-50 text-green-600 px-2 py-0.5 rounded">{{ log.new_value }}</span>
                                </div>
                                <p v-if="log.by" class="text-xs text-gray-400 mt-1">By: {{ log.by }}</p>
                            </div>
                        </li>
                    </ol>
                </div>
            </template>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';

const props = defineProps({
    booking: Object,
    logs:    Array,
    search:  String,
});

const searchQuery = ref(props.search ?? '');

function doSearch() {
    router.get(route('admin.bookings.history'), { search: searchQuery.value }, {
        preserveState: false,
        replace: true,
    });
}

function formatDate(d) {
    if (!d) return '—';
    return new Date(d).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
}

function formatDateTime(d) {
    if (!d) return '—';
    return new Date(d).toLocaleString('en-GB', {
        day: '2-digit', month: 'short', year: 'numeric',
        hour: '2-digit', minute: '2-digit',
    });
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

function timelineIcon(action) {
    return {
        created:        '+',
        status_changed: '~',
        checked_in:     'I',
        checked_out:    'O',
        follow_up:      '✉',
    }[action] ?? '*';
}

function timelineIconBg(action) {
    return {
        created:        'bg-blue-500 text-white',
        status_changed: 'bg-purple-500 text-white',
        checked_in:     'bg-green-500 text-white',
        checked_out:    'bg-gray-400 text-white',
        follow_up:      'bg-indigo-500 text-white',
    }[action] ?? 'bg-gray-300 text-gray-700';
}
</script>

<style scoped>
.input { @apply border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors bg-white; }
</style>
