<template>
    <AdminLayout title="Guest Report">
        <div class="max-w-6xl space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-gray-800">Guest Report</h1>
                    <p class="text-sm text-gray-500">Guests across every hotel in your jurisdiction. Filter by date, status, hotel, or search by identity.</p>
                </div>
                <ExportLinks type="guests" :filters="form" />
            </div>

            <!-- Filters -->
            <form @submit.prevent="applyFilters" class="bg-white rounded-xl shadow-sm p-4 flex flex-wrap items-end gap-3 border border-gray-200">
                <div>
                    <label class="label">Hotel</label>
                    <select v-model="form.hotel_id" class="input">
                        <option value="">All Hotels</option>
                        <option v-for="h in hotels" :key="h.id" :value="h.id">{{ h.name }}</option>
                    </select>
                </div>
                <div>
                    <label class="label">From date</label>
                    <input v-model="form.date_from" type="date" class="input" />
                </div>
                <div>
                    <label class="label">To date</label>
                    <input v-model="form.date_to" type="date" class="input" />
                </div>
                <div>
                    <label class="label">Status</label>
                    <select v-model="form.status" class="input">
                        <option value="">Any</option>
                        <option value="checked_in">Checked In</option>
                        <option value="checked_out">Checked Out</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
                <div class="min-w-[14rem]">
                    <label class="label">Search (phone / email / passport / NID)</label>
                    <input v-model="form.search" type="text" placeholder="Search guest…" class="input w-full" />
                </div>
                <label class="flex items-center gap-2 text-sm text-gray-600 pb-2.5">
                    <input v-model="form.foreign_only" type="checkbox" class="w-4 h-4 rounded text-blue-600" />
                    Foreign guests only
                </label>
                <label v-if="canManageFlag" class="flex items-center gap-2 text-sm text-red-600 pb-2.5">
                    <input v-model="form.flagged_only" type="checkbox" class="w-4 h-4 rounded text-red-600" />
                    Flagged only
                </label>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">Apply</button>
                <Link :href="route('admin.government.guests')" class="px-4 py-2 border border-gray-300 text-sm rounded-lg text-gray-600 hover:bg-gray-50">Reset</Link>
            </form>

            <div v-if="form.nationality" class="flex items-center gap-2 text-sm text-purple-700 bg-purple-50 border border-purple-200 rounded-lg px-3 py-2 w-fit">
                Filtering by nationality: <strong>{{ form.nationality }}</strong>
                <button type="button" class="text-purple-500 hover:text-purple-800" @click="clearNationality">✕</button>
            </div>

            <!-- Group By Selector Bar -->
            <div class="bg-white rounded-xl shadow-sm p-3.5 border border-gray-200 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2 text-xs flex-wrap">
                    <span class="text-gray-500 font-bold uppercase tracking-wider">Group Guests By:</span>
                    <button type="button" @click="groupByMode = 'hotel'"
                        :class="groupByMode === 'hotel' ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-3 py-1.5 rounded-lg transition-colors inline-flex items-center gap-1.5">
                        <span>🏨</span> Hotel Name
                    </button>
                    <button type="button" @click="groupByMode = 'booking'"
                        :class="groupByMode === 'booking' ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-3 py-1.5 rounded-lg transition-colors inline-flex items-center gap-1.5">
                        <span>👤</span> Guest / Booking (Combine Rooms)
                    </button>
                    <button type="button" @click="groupByMode = 'status'"
                        :class="groupByMode === 'status' ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-3 py-1.5 rounded-lg transition-colors inline-flex items-center gap-1.5">
                        <span>📊</span> Stay Status
                    </button>
                    <button type="button" @click="groupByMode = 'nationality'"
                        :class="groupByMode === 'nationality' ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-3 py-1.5 rounded-lg transition-colors inline-flex items-center gap-1.5">
                        <span>🌍</span> Nationality
                    </button>
                    <button type="button" @click="groupByMode = 'none'"
                        :class="groupByMode === 'none' ? 'bg-gray-800 text-white font-semibold shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-3 py-1.5 rounded-lg transition-colors">
                        Flat List (All)
                    </button>
                </div>
                <span class="text-xs text-gray-400 font-medium">Total: {{ rows.length }} records</span>
            </div>

            <!-- Grouped Tables Render -->
            <div v-if="rows.length === 0" class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center text-gray-400 text-sm">
                No guests match these filters.
            </div>

            <div v-else class="space-y-6">
                <div v-for="section in groupedSections" :key="section.key" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Section Header -->
                    <div class="px-5 py-3.5 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-base">{{ section.icon }}</span>
                            <h2 class="font-bold text-sm text-gray-800">{{ section.title }}</h2>
                        </div>
                        <span class="px-2.5 py-0.5 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                            {{ section.rows.length }} Guest{{ section.rows.length > 1 ? 's' : '' }}
                        </span>
                    </div>

                    <!-- Section Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50/60 border-b border-gray-200 text-xs uppercase font-semibold text-gray-600 tracking-wider">
                                <tr>
                                    <th class="px-4 py-3">Guest Name</th>
                                    <th class="px-4 py-3">Gender &amp; Family</th>
                                    <th class="px-4 py-3">Contact Info</th>
                                    <th class="px-4 py-3">Address</th>
                                    <th class="px-4 py-3">Identity Records</th>
                                    <th class="px-4 py-3">Hotel / Room</th>
                                    <th class="px-4 py-3">Check-in / Out</th>
                                    <th class="px-4 py-3 text-center">Status</th>
                                    <th class="px-4 py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr v-for="r in section.rows" :key="r.id" class="hover:bg-gray-50/80 transition-colors" :class="r.is_flagged ? 'bg-red-50/50' : ''">
                                    
                                    <!-- Guest Name + Avatar -->
                                    <td class="px-4 py-3.5 align-top">
                                        <div class="flex items-center gap-2.5">
                                            <img v-if="r.photo_document_id" :src="route('admin.documents.show', r.photo_document_id)" class="w-9 h-9 rounded-full object-cover border border-gray-200 shadow-sm" />
                                            <img v-else-if="r.legacy_photo_path" :src="`/storage/${r.legacy_photo_path}`" class="w-9 h-9 rounded-full object-cover border border-gray-200 shadow-sm" />
                                            <div v-else class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                                {{ (r.guest_name || 'G').charAt(0).toUpperCase() }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-800">
                                                    {{ r.guest_name }}
                                                    <span v-if="r.is_flagged" class="text-red-600 font-bold" title="Flagged">⚑</span>
                                                </p>
                                                <p class="text-xs text-gray-400">
                                                    <span v-if="r.is_foreign" class="text-purple-700 font-medium">🌍 {{ r.nationality }}</span>
                                                    <span v-else-if="r.nationality">{{ r.nationality }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Family Details -->
                                    <td class="px-4 py-3.5 align-top text-xs space-y-1">
                                        <div v-if="r.gender" class="capitalize"><span class="text-gray-400">Gender:</span> <span class="font-medium text-gray-800">{{ r.gender }}</span></div>
                                        <div v-if="r.father_name"><span class="text-gray-400">Father:</span> <span class="text-gray-700">{{ r.father_name }}</span></div>
                                        <div v-if="r.mother_name"><span class="text-gray-400">Mother:</span> <span class="text-gray-700">{{ r.mother_name }}</span></div>
                                        <div v-if="r.is_couple && r.spouse_name"><span class="text-gray-400">Spouse:</span> <span class="text-gray-700">💑 {{ r.spouse_name }}</span></div>
                                        <div v-if="!r.gender && !r.father_name && !r.mother_name" class="text-gray-300 italic">—</div>
                                    </td>

                                    <!-- Contact Info -->
                                    <td class="px-4 py-3.5 align-top text-xs space-y-1">
                                        <div><span class="text-gray-400">Mobile:</span> <span class="font-mono font-medium text-gray-800">{{ r.mobile }}</span></div>
                                        <div v-if="r.emergency_contact"><span class="text-gray-400">Emergency:</span> <span class="font-mono text-gray-700">{{ r.emergency_contact }}</span></div>
                                        <div v-if="r.email"><span class="text-gray-400">Email:</span> <span class="text-gray-600 truncate max-w-[130px] inline-block align-bottom">{{ r.email }}</span></div>
                                    </td>

                                    <!-- Address -->
                                    <td class="px-4 py-3.5 align-top text-xs max-w-[160px]">
                                        <p class="text-gray-800 line-clamp-2" :title="r.address">{{ r.address || '—' }}</p>
                                    </td>

                                    <!-- Identity Records -->
                                    <td class="px-4 py-3.5 align-top text-xs space-y-1">
                                        <div class="bg-gray-50 border border-gray-200/80 rounded-lg p-2 space-y-0.5 font-mono text-[11px]">
                                            <div v-if="r.nid"><span class="text-gray-400 font-sans font-medium">NID:</span> <span class="text-gray-800 font-semibold">{{ r.nid }}</span></div>
                                            <div v-if="r.passport"><span class="text-gray-400 font-sans font-medium">Passport:</span> <span class="text-gray-800 font-semibold">{{ r.passport }}</span></div>
                                            <div v-if="r.driving_license"><span class="text-gray-400 font-sans font-medium">Driving Lic:</span> <span class="text-gray-800 font-semibold">{{ r.driving_license }}</span></div>
                                            <div v-if="r.birth_certificate"><span class="text-gray-400 font-sans font-medium">Birth Cert:</span> <span class="text-gray-800 font-semibold">{{ r.birth_certificate }}</span></div>
                                            <div v-if="!r.nid && !r.passport && !r.driving_license && !r.birth_certificate" class="text-gray-400 italic font-sans text-[11px]">
                                                No identity record
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Hotel / Room -->
                                    <td class="px-4 py-3.5 align-top text-xs space-y-1">
                                        <p class="font-medium text-gray-800">{{ r.hotel_name }}</p>
                                        <p class="text-blue-700 font-mono font-medium">Rm {{ r.room }}</p>
                                    </td>

                                    <!-- Check-in / Out -->
                                    <td class="px-4 py-3.5 align-top text-xs space-y-1">
                                        <div><span class="text-gray-400">In:</span> <span class="text-gray-800 font-medium">{{ r.check_in }}</span></div>
                                        <div v-if="r.check_out"><span class="text-gray-400">Out:</span> <span class="text-gray-600">{{ r.check_out }}</span></div>
                                    </td>

                                    <!-- Status -->
                                    <td class="px-4 py-3.5 align-top text-center">
                                        <span :class="statusBadge(r.status)" class="px-2.5 py-0.5 rounded-full text-xs font-semibold">
                                            {{ statusLabel(r.status) }}
                                        </span>
                                    </td>

                                    <!-- Action -->
                                    <td class="px-4 py-3.5 align-top text-center">
                                        <Link v-if="r.customer_id" :href="route('admin.customers.show', r.customer_id)" class="px-2.5 py-1 text-xs border border-blue-200 rounded-md text-blue-600 hover:bg-blue-50 font-medium transition-colors">
                                            View
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import ExportLinks from '@/Components/Admin/Government/ExportLinks.vue';

const props = defineProps({
    rows: Array,
    hotels: Array,
    filters: Object,
    canManageFlag: Boolean,
});

const groupByMode = ref('hotel'); // Default: Group by Hotel Name!

const form = reactive({
    hotel_id: props.filters.hotel_id || '',
    date_from: props.filters.date_from || '',
    date_to: props.filters.date_to || '',
    status: props.filters.status || '',
    search: props.filters.search || '',
    nationality: props.filters.nationality || '',
    foreign_only: !!props.filters.foreign_only,
    flagged_only: !!props.filters.flagged_only,
});

const groupedSections = computed(() => {
    if (groupByMode.value === 'none') {
        return [{ title: 'All Guests (Flat List)', icon: '👥', key: 'all', rows: props.rows }];
    }

    if (groupByMode.value === 'booking') {
        const map = {};
        props.rows.forEach(r => {
            const key = (r.customer_id || r.guest_name) + '_' + (r.check_in || '');
            if (!map[key]) {
                map[key] = {
                    ...r,
                    roomsList: [r.room],
                    hotelNames: [r.hotel_name],
                };
            } else {
                map[key].roomsList.push(r.room);
                if (!map[key].hotelNames.includes(r.hotel_name)) {
                    map[key].hotelNames.push(r.hotel_name);
                }
            }
        });
        const combinedRows = Object.values(map).map(r => ({
            ...r,
            room: Array.from(new Set(r.roomsList)).join(', ') + (r.roomsList.length > 1 ? ` (${r.roomsList.length} rooms)` : ''),
            hotel_name: r.hotelNames.join(', '),
        }));
        return [{ title: 'Combined Guest Bookings', icon: '👤', key: 'booking', rows: combinedRows }];
    }

    const groups = {};

    props.rows.forEach(r => {
        let groupKey = '';
        let title = '';
        let icon = '👥';

        if (groupByMode.value === 'hotel') {
            groupKey = r.hotel_name || 'Unassigned Hotel';
            title = groupKey;
            icon = '🏨';
        } else if (groupByMode.value === 'status') {
            groupKey = r.status || 'unknown';
            title = statusLabel(r.status);
            icon = '📊';
        } else if (groupByMode.value === 'nationality') {
            groupKey = r.nationality || 'Unspecified';
            title = groupKey;
            icon = '🌍';
        }

        if (!groups[groupKey]) {
            groups[groupKey] = { title, icon, key: groupKey, rows: [] };
        }
        groups[groupKey].rows.push(r);
    });

    return Object.values(groups);
});

function applyFilters() {
    router.get(route('admin.government.guests'), form, { preserveState: true });
}

function clearNationality() {
    form.nationality = '';
    applyFilters();
}

function statusLabel(s) {
    return { pending: 'Pending', confirmed: 'Confirmed', payment_pending: 'Payment Pending', checked_in: 'Checked In', checked_out: 'Checked Out', no_show: 'No Show', cancelled: 'Cancelled' }[s] ?? s;
}

function statusBadge(s) {
    return {
        pending: 'bg-yellow-100 text-yellow-700',
        confirmed: 'bg-blue-100 text-blue-700',
        payment_pending: 'bg-orange-100 text-orange-700',
        checked_in: 'bg-green-100 text-green-700',
        checked_out: 'bg-gray-100 text-gray-500',
        no_show: 'bg-red-100 text-red-500',
        cancelled: 'bg-red-100 text-red-500',
    }[s] ?? 'bg-gray-100 text-gray-500';
}
</script>

<style scoped>
.label { @apply block text-xs font-medium text-gray-600 mb-1; }
.input { @apply border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none bg-white; }
</style>
