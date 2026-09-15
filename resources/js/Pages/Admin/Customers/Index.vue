<template>
    <AdminLayout>
        <div class="max-w-7xl space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-gray-800">Guest Register / Customers</h1>
                    <p class="text-xs text-gray-500">Comprehensive view of all registered hotel guests and their identity records.</p>
                </div>
                <span class="text-xs font-semibold px-3 py-1 bg-gray-100 text-gray-700 rounded-full">{{ customers.length }} Total Guests</span>
            </div>

            <!-- Flash -->
            <div v-if="$page.props.flash?.success"
                class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg">
                {{ $page.props.flash.success }}
            </div>

            <!-- Search Bar & Controls -->
            <div class="flex flex-wrap items-center justify-between gap-4 bg-white p-3.5 rounded-xl shadow-sm border border-gray-200">
                <input v-model="search" type="text" placeholder="Search name, phone, NID, passport, father/mother name..." class="input max-w-lg flex-1" />
                <span class="text-xs text-gray-400 font-medium">Matching: {{ filtered.length }} guests</span>
            </div>

            <!-- Group By Selector Bar -->
            <div class="bg-white rounded-xl shadow-sm p-3.5 border border-gray-200 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2 text-xs flex-wrap">
                    <span class="text-gray-500 font-bold uppercase tracking-wider">Group Guests By:</span>
                    <button type="button" @click="groupByMode = 'district'"
                        :class="groupByMode === 'district' ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-3 py-1.5 rounded-lg transition-colors inline-flex items-center gap-1.5">
                        <span>📍</span> District / Region
                    </button>
                    <button type="button" @click="groupByMode = 'nationality'"
                        :class="groupByMode === 'nationality' ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-3 py-1.5 rounded-lg transition-colors inline-flex items-center gap-1.5">
                        <span>🌍</span> Nationality
                    </button>
                    <button type="button" @click="groupByMode = 'status'"
                        :class="groupByMode === 'status' ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-3 py-1.5 rounded-lg transition-colors inline-flex items-center gap-1.5">
                        <span>📊</span> Last Booking Status
                    </button>
                    <button type="button" @click="groupByMode = 'none'"
                        :class="groupByMode === 'none' ? 'bg-gray-800 text-white font-semibold shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-3 py-1.5 rounded-lg transition-colors">
                        Flat List (All)
                    </button>
                </div>
            </div>

            <!-- Grouped Sections -->
            <div v-if="filtered.length === 0" class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center text-gray-400 text-sm">
                No guest records found.
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

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50/60 border-b border-gray-200 text-xs uppercase font-semibold text-gray-600 tracking-wider">
                                <tr>
                                    <th class="px-4 py-3">Full Name</th>
                                    <th class="px-4 py-3">Gender &amp; Family</th>
                                    <th class="px-4 py-3">Contact Info</th>
                                    <th class="px-4 py-3">Address</th>
                                    <th class="px-4 py-3">Identity Records</th>
                                    <th class="px-4 py-3">Nationality</th>
                                    <th class="px-4 py-3">Check-in / Last Stay</th>
                                    <th class="px-4 py-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr v-for="c in section.rows" :key="c.id" class="hover:bg-gray-50/80 transition-colors" :class="c.is_flagged ? 'bg-red-50/50' : ''">
                                    
                                    <!-- Full Name + Photo Avatar -->
                                    <td class="px-4 py-3.5 align-top">
                                        <div class="flex items-center gap-3">
                                            <div v-if="guestPhotoDoc(c)" class="flex-shrink-0">
                                                <img :src="route('admin.documents.show', guestPhotoDoc(c).id)" alt="Photo" class="w-9 h-9 rounded-full object-cover border border-gray-200 shadow-sm" />
                                            </div>
                                            <div v-else class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                                {{ (c.name || 'G').charAt(0).toUpperCase() }}
                                            </div>
                                            <div>
                                                <Link :href="route('admin.customers.show', c.id)" class="font-semibold text-gray-900 hover:text-blue-600 block">
                                                    {{ c.name }}
                                                </Link>
                                                <span v-if="canManageFlag && c.is_flagged" title="Flagged" class="inline-flex items-center gap-0.5 text-[10px] font-bold text-red-600 bg-red-100 px-1.5 py-0.5 rounded mt-0.5">
                                                    ⚑ Flagged
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Gender & Family Details -->
                                    <td class="px-4 py-3.5 align-top text-xs space-y-1">
                                        <div v-if="c.gender" class="capitalize">
                                            <span class="text-gray-400">Gender:</span> <span class="font-medium text-gray-800">{{ c.gender }}</span>
                                        </div>
                                        <div v-if="c.father_name">
                                            <span class="text-gray-400">Father:</span> <span class="text-gray-700">{{ c.father_name }}</span>
                                        </div>
                                        <div v-if="c.mother_name">
                                            <span class="text-gray-400">Mother:</span> <span class="text-gray-700">{{ c.mother_name }}</span>
                                        </div>
                                        <div v-if="c.is_couple && c.spouse_name">
                                            <span class="text-gray-400">Spouse:</span> <span class="text-gray-700">💑 {{ c.spouse_name }}</span>
                                        </div>
                                        <div v-if="!c.gender && !c.father_name && !c.mother_name" class="text-gray-300 italic">—</div>
                                    </td>

                                    <!-- Contact Info -->
                                    <td class="px-4 py-3.5 align-top text-xs space-y-1">
                                        <div>
                                            <span class="text-gray-400">Mobile:</span> <span class="font-mono font-medium text-gray-800">{{ c.phone }}</span>
                                        </div>
                                        <div v-if="c.emergency_contact">
                                            <span class="text-gray-400">Emergency:</span> <span class="font-mono text-gray-700">{{ c.emergency_contact }}</span>
                                        </div>
                                        <div v-if="c.email">
                                            <span class="text-gray-400">Email:</span> <span class="text-gray-600 truncate max-w-[140px] inline-block align-bottom">{{ c.email }}</span>
                                        </div>
                                    </td>

                                    <!-- Address -->
                                    <td class="px-4 py-3.5 align-top text-xs max-w-[180px] space-y-1">
                                        <p class="text-gray-800 leading-snug line-clamp-2" :title="c.present_address || c.address">
                                            {{ c.present_address || c.address || '—' }}
                                        </p>
                                        <p v-if="c.district?.name" class="text-[11px] text-gray-400">
                                            {{ c.district.name }}<span v-if="c.upazila?.name">, {{ c.upazila.name }}</span>
                                        </p>
                                    </td>

                                    <!-- Stacked Identity Records Column -->
                                    <td class="px-4 py-3.5 align-top text-xs space-y-1">
                                        <div class="bg-gray-50 border border-gray-200/80 rounded-lg p-2 space-y-0.5 font-mono text-[11px]">
                                            <div v-if="c.nid_number">
                                                <span class="text-gray-400 font-sans font-medium">NID:</span> <span class="text-gray-800 font-semibold">{{ c.nid_number }}</span>
                                            </div>
                                            <div v-if="c.passport_number">
                                                <span class="text-gray-400 font-sans font-medium">Passport:</span> <span class="text-gray-800 font-semibold">{{ c.passport_number }}</span>
                                            </div>
                                            <div v-if="c.driving_license_number">
                                                <span class="text-gray-400 font-sans font-medium">Driving Lic:</span> <span class="text-gray-800 font-semibold">{{ c.driving_license_number }}</span>
                                            </div>
                                            <div v-if="c.birth_certificate_number">
                                                <span class="text-gray-400 font-sans font-medium">Birth Cert:</span> <span class="text-gray-800 font-semibold">{{ c.birth_certificate_number }}</span>
                                            </div>
                                            <div v-if="!c.nid_number && !c.passport_number && !c.driving_license_number && !c.birth_certificate_number" class="text-gray-400 italic font-sans text-[11px]">
                                                No identity record
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Nationality -->
                                    <td class="px-4 py-3.5 align-top text-xs text-gray-700">
                                        <span class="inline-flex items-center gap-1 font-medium" :class="c.is_foreign_guest ? 'text-purple-700 font-semibold' : 'text-gray-700'">
                                            <span v-if="c.is_foreign_guest">🌍</span>
                                            {{ c.nationality || 'Bangladeshi' }}
                                        </span>
                                    </td>

                                    <!-- Check-in / Last Booking -->
                                    <td class="px-4 py-3.5 align-top text-xs space-y-1">
                                        <template v-if="c.bookings && c.bookings[0]">
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-mono text-gray-800 font-medium">{{ c.bookings[0].booking_reference }}</span>
                                                <span :class="statusBadge(c.bookings[0].booking_status)" class="px-1.5 py-0.5 rounded text-[10px] font-semibold">
                                                    {{ statusLabel(c.bookings[0].booking_status) }}
                                                </span>
                                            </div>
                                            <div class="text-[11px] text-gray-500">
                                                <span class="text-gray-400">Date:</span> {{ formatDate(c.bookings[0].check_in_date) }}
                                            </div>
                                        </template>
                                        <span v-else class="text-gray-300 italic">No bookings</span>
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-4 py-3.5 align-top text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <Link :href="route('admin.customers.show', c.id)"
                                                class="text-xs px-2.5 py-1 rounded-md border border-blue-200 text-blue-600 hover:bg-blue-50 font-medium transition-colors">
                                                View
                                            </Link>
                                            <Link :href="route('admin.customers.edit', c.id)"
                                                class="text-xs px-2.5 py-1 rounded-md border border-gray-200 text-gray-600 hover:bg-gray-50 font-medium transition-colors">
                                                Edit
                                            </Link>
                                        </div>
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
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';

const props = defineProps({
    customers: Array,
    canManageFlag: { type: Boolean, default: false },
});

const search = ref('');
const groupByMode = ref('none'); // Options: 'none', 'district', 'nationality', 'status'

function guestPhotoDoc(c) {
    return (c.documents || []).find(d => d.category === 'guest_photo');
}

const filtered = computed(() => {
    if (!search.value) return props.customers;
    const q = search.value.toLowerCase().trim();
    return props.customers.filter(c =>
        c.name?.toLowerCase().includes(q) ||
        c.phone?.includes(q) ||
        c.email?.toLowerCase().includes(q) ||
        c.nid_number?.toLowerCase().includes(q) ||
        c.passport_number?.toLowerCase().includes(q) ||
        c.driving_license_number?.toLowerCase().includes(q) ||
        c.father_name?.toLowerCase().includes(q) ||
        c.mother_name?.toLowerCase().includes(q) ||
        c.nationality?.toLowerCase().includes(q)
    );
});

const groupedSections = computed(() => {
    if (groupByMode.value === 'none') {
        return [{ title: 'All Registered Guests (Flat List)', icon: '👥', key: 'all', rows: filtered.value }];
    }

    const groups = {};

    filtered.value.forEach(c => {
        let groupKey = '';
        let title = '';
        let icon = '👥';

        if (groupByMode.value === 'district') {
            groupKey = c.district?.name || 'Unspecified Region';
            title = groupKey;
            icon = '📍';
        } else if (groupByMode.value === 'nationality') {
            groupKey = c.nationality || 'Unspecified';
            title = groupKey;
            icon = '🌍';
        } else if (groupByMode.value === 'status') {
            const status = c.bookings && c.bookings[0] ? c.bookings[0].booking_status : 'no_bookings';
            groupKey = status;
            title = statusLabel(status);
            icon = '📊';
        }

        if (!groups[groupKey]) {
            groups[groupKey] = { title, icon, key: groupKey, rows: [] };
        }
        groups[groupKey].rows.push(c);
    });

    return Object.values(groups);
});

function formatDate(d) {
    if (!d) return '—';
    return new Date(d).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
}

function statusLabel(s) {
    return { pending: 'Pending', confirmed: 'Confirmed', checked_in: 'Checked In', checked_out: 'Checked Out', cancelled: 'Cancelled', no_show: 'No Show', no_bookings: 'No Bookings' }[s] ?? s;
}

function statusBadge(s) {
    return {
        pending:     'bg-yellow-100 text-yellow-700',
        confirmed:   'bg-blue-100 text-blue-700',
        checked_in:  'bg-green-100 text-green-700',
        checked_out: 'bg-gray-100 text-gray-500',
        cancelled:   'bg-red-100 text-red-500',
    }[s] ?? 'bg-gray-100 text-gray-500';
}
</script>

<style scoped>
.input { @apply w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors bg-white; }
</style>
