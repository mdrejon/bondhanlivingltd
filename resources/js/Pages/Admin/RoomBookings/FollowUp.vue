<template>
    <AdminLayout>
        <div class="max-w-7xl space-y-6">

            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-gray-800">Booking Follow-up</h1>
                    <p class="text-xs text-gray-400 mt-0.5">Send email reminders or messages to guests about their booking.</p>
                </div>
            </div>

            <!-- Flash -->
            <div v-if="$page.props.flash?.success"
                class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ $page.props.flash.success }}
            </div>

            <!-- Filters -->
            <div class="flex flex-wrap gap-3">
                <select v-model="filterStatus" class="input">
                    <option value="">All Booking Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="payment_pending">Payment Pending</option>
                    <option value="checked_in">Checked In</option>
                    <option value="checked_out">Checked Out</option>
                </select>
                <input v-model="search" type="text" placeholder="Search ref / guest name / phone..." class="input min-w-[240px]" />
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
                            <th class="px-3 py-3 text-center text-xs font-semibold text-gray-600">Status</th>
                            <th class="px-3 py-3 text-center text-xs font-semibold text-gray-600">Follow-ups</th>
                            <th class="px-3 py-3 text-center text-xs font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-if="filtered.length === 0">
                            <td colspan="9" class="px-3 py-8 text-center text-gray-400 text-sm">No bookings found.</td>
                        </tr>
                        <tr v-for="(b, i) in filtered" :key="b.id" class="hover:bg-gray-50">
                            <td class="px-3 py-3 text-xs text-gray-400">{{ i + 1 }}</td>
                            <td class="px-3 py-3 font-mono text-xs text-gray-700">{{ b.booking_reference }}</td>
                            <td class="px-3 py-3">
                                <div class="font-medium text-gray-800 text-xs">{{ b.customer?.name }}</div>
                                <div class="text-xs text-gray-400">{{ b.customer?.phone }}</div>
                                <div v-if="b.customer?.email" class="text-xs text-blue-500">{{ b.customer.email }}</div>
                                <div v-else class="text-xs text-amber-500">No email</div>
                            </td>
                            <td class="px-3 py-3 text-xs text-gray-600">{{ roomTypeSummary(b) }}</td>
                            <td class="px-3 py-3 text-xs text-gray-600">{{ formatDate(b.check_in_date) }}</td>
                            <td class="px-3 py-3 text-xs text-gray-600">{{ formatDate(b.check_out_date) }}</td>
                            <td class="px-3 py-3 text-center">
                                <span :class="statusBadge(b.booking_status)" class="px-2 py-0.5 rounded-full text-xs font-medium whitespace-nowrap">
                                    {{ statusLabel(b.booking_status) }}
                                </span>
                            </td>
                            <td class="px-3 py-3 text-center">
                                <button v-if="b.follow_ups?.length"
                                    @click="openHistory(b)"
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors">
                                    <span>{{ b.follow_ups.length }}</span>
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                                <span v-else class="text-xs text-gray-400">0</span>
                            </td>
                            <td class="px-3 py-3 text-center">
                                <button @click="openSend(b)"
                                    class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors whitespace-nowrap">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                    Send Follow-up
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ── Send Follow-up Modal ── -->
        <Teleport to="body">
            <div v-if="showSendModal"
                class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
                @click.self="closeSend">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg">

                    <!-- Header -->
                    <div class="flex items-start justify-between px-6 py-4 border-b border-gray-100">
                        <div>
                            <h2 class="text-base font-semibold text-gray-800">Send Follow-up Email</h2>
                            <p class="text-xs text-gray-500 mt-0.5">
                                <span class="font-mono">{{ activeBooking?.booking_reference }}</span>
                                — {{ activeBooking?.customer?.name }}
                            </p>
                        </div>
                        <button @click="closeSend" class="text-gray-400 hover:text-gray-600 mt-0.5">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <!-- Recipient notice -->
                    <div class="mx-6 mt-4 px-3 py-2.5 rounded-lg text-xs"
                        :class="activeBooking?.customer?.email
                            ? 'bg-green-50 text-green-700 border border-green-200'
                            : 'bg-amber-50 text-amber-700 border border-amber-200'">
                        <span v-if="activeBooking?.customer?.email">
                            ✉ Email will be sent to: <strong>{{ activeBooking.customer.email }}</strong>
                        </span>
                        <span v-else>
                            ⚠ This customer has no email address. The follow-up will be saved but email cannot be sent.
                        </span>
                    </div>

                    <!-- Today's date pill -->
                    <div class="mx-6 mt-3 flex items-center gap-2 text-xs text-gray-500">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        Date: <strong class="text-gray-700">{{ todayFormatted }}</strong>
                        <span class="text-gray-300">·</span> Auto-set by system
                    </div>

                    <form @submit.prevent="submitSend" class="px-6 py-4 space-y-4">
                        <!-- Title -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Title / Heading <span class="text-red-500">*</span>
                            </label>
                            <input v-model="sendForm.title" type="text" class="input w-full" required maxlength="200"
                                placeholder="e.g. Check-in Reminder, Payment Reminder, Special Offer..." />
                            <p v-if="sendErrors.title" class="text-red-500 text-xs mt-1">{{ sendErrors.title }}</p>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Message / Email Content <span class="text-red-500">*</span>
                            </label>
                            <textarea v-model="sendForm.description" class="input w-full resize-none" rows="6" required maxlength="5000"
                                placeholder="Write the message that will be sent to the guest..."></textarea>
                            <p class="text-xs text-gray-400 mt-1 text-right">{{ sendForm.description.length }}/5000</p>
                            <p v-if="sendErrors.description" class="text-red-500 text-xs mt-1">{{ sendErrors.description }}</p>
                        </div>

                        <div class="flex justify-end gap-3 pt-1">
                            <button type="button" @click="closeSend"
                                class="px-4 py-2 text-sm border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" :disabled="sending"
                                class="inline-flex items-center gap-2 px-5 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-60 transition-colors">
                                <svg v-if="!sending" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                <svg v-else class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                                {{ sending ? 'Sending...' : 'Send Email' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Teleport>

        <!-- ── Follow-up History Modal ── -->
        <Teleport to="body">
            <div v-if="showHistoryModal"
                class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
                @click.self="closeHistory">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg max-h-[80vh] flex flex-col">

                    <div class="flex items-start justify-between px-6 py-4 border-b border-gray-100 flex-shrink-0">
                        <div>
                            <h2 class="text-base font-semibold text-gray-800">Follow-up History</h2>
                            <p class="text-xs text-gray-500 mt-0.5">
                                <span class="font-mono">{{ historyBooking?.booking_reference }}</span>
                                — {{ historyBooking?.customer?.name }}
                            </p>
                        </div>
                        <button @click="closeHistory" class="text-gray-400 hover:text-gray-600 mt-0.5">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="overflow-y-auto px-6 py-4 space-y-3 flex-1">
                        <div v-for="fu in historyBooking?.follow_ups" :key="fu.id"
                            class="border border-gray-100 rounded-lg p-4 bg-gray-50">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-800">{{ fu.title }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ formatDateTime(fu.created_at) }}</p>
                                    <p class="text-xs text-gray-600 mt-2 leading-relaxed whitespace-pre-line">{{ fu.description }}</p>
                                </div>
                                <button @click="deleteFollowUp(fu.id)"
                                    class="flex-shrink-0 text-gray-300 hover:text-red-500 transition-colors mt-0.5"
                                    title="Delete">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-100 flex justify-between items-center flex-shrink-0">
                        <span class="text-xs text-gray-400">{{ historyBooking?.follow_ups?.length }} follow-up(s) sent</span>
                        <button @click="closeHistory; openSend(historyBooking)"
                            class="text-xs px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            + Send New
                        </button>
                    </div>
                </div>
            </div>
        </Teleport>

    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import { roomTypeSummary } from '@/Composables/bookingRoomsSummary';

const props = defineProps({
    bookings: Array,
});

const filterStatus = ref('');
const search       = ref('');

// Send modal
const showSendModal  = ref(false);
const activeBooking  = ref(null);
const sending        = ref(false);
const sendErrors     = ref({});
const sendForm       = ref({ title: '', description: '' });

// History modal
const showHistoryModal = ref(false);
const historyBooking   = ref(null);

const todayFormatted = new Date().toLocaleDateString('en-GB', {
    weekday: 'short', day: '2-digit', month: 'short', year: 'numeric',
});

const filtered = computed(() => props.bookings.filter(b => {
    if (filterStatus.value && b.booking_status !== filterStatus.value) return false;
    if (search.value) {
        const q = search.value.toLowerCase();
        const match = b.booking_reference?.toLowerCase().includes(q)
            || b.customer?.name?.toLowerCase().includes(q)
            || b.customer?.phone?.includes(q);
        if (!match) return false;
    }
    return true;
}));

function openSend(booking) {
    activeBooking.value = booking;
    sendForm.value      = { title: '', description: '' };
    sendErrors.value    = {};
    sending.value       = false;
    showSendModal.value = true;
}

function closeSend() {
    showSendModal.value = false;
    activeBooking.value = null;
}

function submitSend() {
    if (!activeBooking.value) return;
    sending.value    = true;
    sendErrors.value = {};

    router.post(route('admin.bookings.follow-up.store', activeBooking.value.id), sendForm.value, {
        onSuccess: () => closeSend(),
        onError:   (errors) => { sendErrors.value = errors; sending.value = false; },
        onFinish:  () => { sending.value = false; },
    });
}

function openHistory(booking) {
    historyBooking.value   = booking;
    showHistoryModal.value = true;
}

function closeHistory() {
    showHistoryModal.value = false;
    historyBooking.value   = null;
}

function deleteFollowUp(id) {
    if (!confirm('Delete this follow-up?')) return;
    router.delete(route('admin.bookings.follow-up.destroy', id), {
        onSuccess: () => {
            if (historyBooking.value) {
                historyBooking.value.follow_ups = historyBooking.value.follow_ups.filter(f => f.id !== id);
                if (!historyBooking.value.follow_ups.length) closeHistory();
            }
        },
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
    }[s] ?? s;
}

function statusBadge(s) {
    return {
        pending:         'bg-yellow-100 text-yellow-700',
        confirmed:       'bg-blue-100 text-blue-700',
        payment_pending: 'bg-purple-100 text-purple-700',
        checked_in:      'bg-green-100 text-green-700',
        checked_out:     'bg-gray-100 text-gray-600',
    }[s] ?? 'bg-gray-100 text-gray-600';
}
</script>

<style scoped>
.input { @apply border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors bg-white; }
select.input { @apply cursor-pointer; }
</style>
