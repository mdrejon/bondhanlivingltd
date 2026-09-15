<template>
    <AdminLayout>
        <div class="max-w-4xl space-y-6">

            <div class="flex items-center gap-4">
                <Link :href="route('admin.customers.index')" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><polyline points="15 18 9 12 15 6"/></svg>
                </Link>
                <h1 class="text-lg font-semibold text-gray-800">Customer History</h1>
            </div>

            <!-- Search bar -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <form @submit.prevent="doSearch" class="flex gap-3">
                    <div class="flex-1 relative">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Search Customer</label>
                        <input
                            v-model="query"
                            type="text"
                            placeholder="Enter name, email or phone number..."
                            class="input w-full"
                            autocomplete="off"
                            autofocus
                            @input="onInput"
                            @keydown.down.prevent="moveSuggestion(1)"
                            @keydown.up.prevent="moveSuggestion(-1)"
                            @keydown.enter.prevent="selectActive"
                            @keydown.escape="closeSuggestions"
                            @blur="onBlur"
                            @focus="onFocus"
                        />

                        <!-- Suggestions dropdown -->
                        <div v-if="showSuggestions && suggestions.length"
                            class="absolute left-0 right-0 top-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg z-50 overflow-hidden">
                            <ul>
                                <li v-for="(s, i) in suggestions" :key="s.id"
                                    @mousedown.prevent="pickSuggestion(s)"
                                    :class="[
                                        'flex items-center gap-3 px-4 py-2.5 cursor-pointer transition-colors text-sm',
                                        i === activeIndex ? 'bg-blue-50' : 'hover:bg-gray-50'
                                    ]">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0 text-xs font-bold text-indigo-600">
                                        {{ s.name?.charAt(0)?.toUpperCase() }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-800 truncate">{{ s.name }}</p>
                                        <p class="text-xs text-gray-400 truncate">
                                            {{ s.phone || '' }}{{ s.phone && s.email ? ' · ' : '' }}{{ s.email || '' }}
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <!-- Loading indicator -->
                        <div v-if="loadingSuggestions" class="absolute right-3 top-9">
                            <svg class="w-4 h-4 text-gray-400 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                            </svg>
                        </div>

                        <!-- No suggestions found -->
                        <div v-if="showSuggestions && !suggestions.length && query.length >= 2 && !loadingSuggestions"
                            class="absolute left-0 right-0 top-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg z-50 px-4 py-3 text-xs text-gray-400">
                            No customers found matching "{{ query }}"
                        </div>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            Search
                        </button>
                        <button v-if="searched" type="button" @click="clearSearch"
                            class="px-4 py-2.5 bg-gray-100 text-gray-600 text-sm rounded-lg hover:bg-gray-200 transition-colors">
                            Clear
                        </button>
                    </div>
                </form>
            </div>

            <!-- Not searched yet -->
            <div v-if="!searched" class="bg-white rounded-xl shadow-sm border border-gray-100 p-16 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-blue-50 flex items-center justify-center">
                    <svg class="w-8 h-8 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                        <circle cx="9" cy="7" r="4" stroke-width="1.5"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-gray-700 mb-1">Search for a customer</h3>
                <p class="text-xs text-gray-400">Enter a customer's name, email or phone number to view their full profile and booking history.</p>
            </div>

            <!-- No customer found -->
            <div v-else-if="searched && !customer" class="bg-white rounded-xl shadow-sm border border-gray-100 p-16 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-50 flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="11" cy="11" r="8" stroke-width="1.5"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35"/>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-gray-700 mb-1">No customer found</h3>
                <p class="text-xs text-gray-400">No customer matched "<strong>{{ search }}</strong>". Try a different name, email, or phone number.</p>
            </div>

            <!-- Customer found -->
            <template v-else-if="customer">

                <!-- Profile Card -->
                <section class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-start justify-between mb-4">
                        <h2 class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer Profile</h2>
                        <Link :href="route('admin.customers.show', customer.id)"
                            class="text-xs text-blue-600 hover:underline flex items-center gap-1">
                            View Full Profile
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </Link>
                    </div>

                    <div class="flex items-center gap-4 mb-5 pb-5 border-b border-gray-100">
                        <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-indigo-600 font-bold text-lg">{{ customer.name?.charAt(0)?.toUpperCase() }}</span>
                        </div>
                        <div>
                            <p class="text-base font-bold text-gray-800">{{ customer.name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">Member since {{ formatDate(customer.created_at) }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                        <div>
                            <p class="text-xs text-gray-400">Phone</p>
                            <p class="font-medium text-gray-800">{{ customer.phone || '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Email</p>
                            <p class="text-gray-700">{{ customer.email || '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Nationality</p>
                            <p class="text-gray-700">{{ customer.nationality || '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Address</p>
                            <p class="text-gray-700">{{ customer.address || '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Document Type</p>
                            <p class="text-gray-700 capitalize">{{ customer.document_type || '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">{{ customer.document_type === 'passport' ? 'Passport' : 'NID' }}</p>
                            <p class="font-mono text-gray-700">
                                {{ customer.document_type === 'passport' ? customer.passport_number : customer.nid_number || '—' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Total Bookings</p>
                            <p class="font-bold text-blue-700 text-lg">{{ customer.bookings?.length ?? 0 }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Total Spent</p>
                            <p class="font-bold text-emerald-700 text-lg">৳{{ totalSpent.toLocaleString() }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Last Booking</p>
                            <p class="text-gray-700 text-xs">{{ lastBookingDate }}</p>
                        </div>
                    </div>
                </section>

                <!-- Booking History -->
                <section class="bg-white rounded-lg shadow-sm overflow-x-auto">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Booking History</h2>
                        <span class="text-xs text-gray-400">{{ customer.bookings?.length ?? 0 }} booking(s)</span>
                    </div>

                    <div v-if="!customer.bookings || customer.bookings.length === 0"
                        class="px-6 py-10 text-center text-gray-400 text-sm">
                        No bookings found for this customer.
                    </div>

                    <table v-else class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Reference</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Room</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Check In</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Check Out</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600">Nights</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600">Amount</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600">Status</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="b in customer.bookings" :key="b.id" class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-mono text-xs text-gray-700">{{ b.booking_reference }}</td>
                                <td class="px-4 py-3 text-gray-600 text-xs">
                                    <span class="font-medium">{{ roomNumberSummary(b) }}</span>
                                    <span v-if="roomTypeSummary(b) !== '—'" class="text-gray-400"> / {{ roomTypeSummary(b) }}</span>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-600">{{ formatDate(b.check_in_date) }}</td>
                                <td class="px-4 py-3 text-xs text-gray-600">{{ formatDate(b.check_out_date) }}</td>
                                <td class="px-4 py-3 text-center text-xs text-gray-600">{{ b.total_nights }}</td>
                                <td class="px-4 py-3 text-right text-xs font-medium text-gray-800">
                                    ৳{{ Number(b.total_amount).toLocaleString() }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span :class="statusBadge(b.booking_status)" class="px-2 py-0.5 rounded-full text-xs font-medium whitespace-nowrap">
                                        {{ statusLabel(b.booking_status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <Link :href="route('admin.room-bookings.show', b.id)"
                                        class="text-xs text-blue-600 hover:underline">View</Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </section>

            </template>

        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import { roomTypeSummary, roomNumberSummary } from '@/Composables/bookingRoomsSummary';

const props = defineProps({
    customer: { type: Object, default: null },
    search:   { type: String, default: '' },
    searched: { type: Boolean, default: false },
});

const query            = ref(props.search);
const suggestions      = ref([]);
const showSuggestions  = ref(false);
const loadingSuggestions = ref(false);
const activeIndex      = ref(-1);
let debounceTimer      = null;

function onInput() {
    activeIndex.value = -1;
    clearTimeout(debounceTimer);
    if (query.value.length < 2) {
        suggestions.value = [];
        showSuggestions.value = false;
        return;
    }
    loadingSuggestions.value = true;
    debounceTimer = setTimeout(fetchSuggestions, 280);
}

async function fetchSuggestions() {
    try {
        const res  = await fetch(route('admin.customers.suggest') + '?q=' + encodeURIComponent(query.value), {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        });
        suggestions.value     = await res.json();
        showSuggestions.value = true;
    } catch {
        suggestions.value = [];
    } finally {
        loadingSuggestions.value = false;
    }
}

function moveSuggestion(dir) {
    if (!suggestions.value.length) return;
    activeIndex.value = Math.max(-1, Math.min(suggestions.value.length - 1, activeIndex.value + dir));
}

function selectActive() {
    if (activeIndex.value >= 0 && suggestions.value[activeIndex.value]) {
        pickSuggestion(suggestions.value[activeIndex.value]);
    } else {
        doSearch();
    }
}

function pickSuggestion(s) {
    query.value = s.name;
    closeSuggestions();
    router.get(route('admin.customers.history'), { search: s.phone || s.email || s.name }, { preserveState: false });
}

function closeSuggestions() {
    showSuggestions.value = false;
    activeIndex.value = -1;
}

function onBlur() {
    setTimeout(closeSuggestions, 150);
}

function onFocus() {
    if (suggestions.value.length) showSuggestions.value = true;
}

function doSearch() {
    closeSuggestions();
    if (!query.value.trim()) return;
    router.get(route('admin.customers.history'), { search: query.value.trim() }, { preserveState: false });
}

function clearSearch() {
    query.value = '';
    suggestions.value = [];
    showSuggestions.value = false;
    router.get(route('admin.customers.history'));
}

const totalSpent = computed(() =>
    (props.customer?.bookings || [])
        .filter(b => b.booking_status !== 'cancelled')
        .reduce((sum, b) => sum + Number(b.total_amount), 0)
);

const lastBookingDate = computed(() => {
    const bookings = props.customer?.bookings;
    if (!bookings || bookings.length === 0) return '—';
    return formatDate(bookings[0].check_in_date);
});

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
.input { @apply border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors bg-white; }
</style>
