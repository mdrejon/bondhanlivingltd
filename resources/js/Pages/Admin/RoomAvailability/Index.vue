<template>
    <AdminLayout>
        <div class="max-w-7xl space-y-6">

            <!-- Page header -->
            <div>
                <h1 class="text-lg font-semibold text-gray-800">Room Availability</h1>
                <p class="text-sm text-gray-500 mt-0.5">Select check-in and check-out dates to see all rooms at a glance.</p>
            </div>

            <!-- Filter card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <form @submit.prevent="search" class="flex flex-wrap items-end gap-4">
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-medium text-gray-600">Check-In Date <span class="text-red-500">*</span></label>
                        <input type="date" v-model="form.check_in" :min="today"
                            class="input" required />
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-medium text-gray-600">Check-Out Date <span class="text-red-500">*</span></label>
                        <input type="date" v-model="form.check_out" :min="minCheckOut"
                            class="input" required />
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-medium text-gray-600">Room Type</label>
                        <select v-model="form.room_type_id" class="input min-w-[190px]">
                            <option :value="null">All Room Types</option>
                            <option v-for="rt in roomTypes" :key="rt.id" :value="rt.id">{{ rt.name }}</option>
                        </select>
                    </div>
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 font-medium transition-colors">
                        Check Availability
                    </button>
                    <button v-if="checkedSearch" type="button" @click="reset"
                        class="px-4 py-2 bg-gray-100 text-gray-600 text-sm rounded-lg hover:bg-gray-200 transition-colors">
                        Clear
                    </button>
                </form>

                <!-- Date range label when searched -->
                <div v-if="checkedSearch" class="mt-3 text-xs text-gray-500">
                    Showing availability from
                    <span class="font-semibold text-gray-700">{{ formatDate(filters.check_in) }}</span>
                    to
                    <span class="font-semibold text-gray-700">{{ formatDate(filters.check_out) }}</span>
                </div>
            </div>

            <!-- Summary strip (only when dates are searched) -->
            <div v-if="checkedSearch" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="flex items-center gap-3 bg-green-50 border border-green-200 rounded-xl px-5 py-3">
                    <span class="w-3 h-3 rounded-full bg-green-500 flex-shrink-0"></span>
                    <div>
                        <p class="text-xl font-bold text-green-700">{{ availableCount }}</p>
                        <p class="text-xs text-green-600">Available for Booking</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 bg-red-50 border border-red-200 rounded-xl px-5 py-3">
                    <span class="w-3 h-3 rounded-full bg-red-500 flex-shrink-0"></span>
                    <div>
                        <p class="text-xl font-bold text-red-600">{{ unavailableCount }}</p>
                        <p class="text-xs text-red-500">Unavailable / Booked</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 bg-gray-50 border border-gray-200 rounded-xl px-5 py-3">
                    <div>
                        <p class="text-xl font-bold text-gray-700">{{ rooms.length }}</p>
                        <p class="text-xs text-gray-500">Total Active Rooms</p>
                    </div>
                </div>
            </div>

            <!-- Room grid (always visible) -->
            <div v-if="rooms.length === 0" class="bg-white rounded-xl shadow-sm border border-gray-100 p-10 text-center text-sm text-gray-400">
                No active rooms found for the selected type.
            </div>

            <div v-for="group in groupedRooms" :key="group.typeId" class="space-y-3">
                <!-- Group header -->
                <div class="flex items-center gap-3">
                    <h2 class="text-sm font-semibold text-gray-700">{{ group.typeName }}</h2>
                    <span class="text-xs text-gray-400 bg-gray-100 rounded-full px-2 py-0.5">{{ group.rooms.length }} rooms</span>
                    <template v-if="checkedSearch">
                        <span class="text-xs text-green-600 bg-green-50 rounded-full px-2 py-0.5">
                            {{ group.rooms.filter(r => isAvailable(r)).length }} available
                        </span>
                        <span v-if="group.rooms.some(r => !isAvailable(r))" class="text-xs text-red-500 bg-red-50 rounded-full px-2 py-0.5">
                            {{ group.rooms.filter(r => !isAvailable(r)).length }} unavailable
                        </span>
                    </template>
                </div>

                <!-- Room cards -->
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
                    <div v-for="room in group.rooms" :key="room.id"
                        :class="[
                            'relative rounded-xl border-2 p-4 flex flex-col',
                            !checkedSearch
                                ? 'bg-white border-gray-200 shadow-sm'
                                : isAvailable(room)
                                    ? 'bg-white shadow-sm ' + roomStatusMeta(room).border
                                    : 'bg-gray-50 ' + roomStatusMeta(room).border
                        ]"
                    >
                        <!-- Status pill (only when availability is checked) -->
                        <span v-if="checkedSearch" :class="[
                            'absolute top-2.5 right-2.5 text-[9px] font-bold tracking-wide px-2 py-0.5 rounded-full whitespace-nowrap',
                            roomStatusMeta(room).badge
                        ]">
                            {{ roomStatusMeta(room).label.toUpperCase() }}
                        </span>

                        <!-- Room icon -->
                        <div :class="[
                            'w-9 h-9 rounded-lg flex items-center justify-center mb-2.5',
                            !checkedSearch ? 'bg-blue-50' : roomStatusMeta(room).soft
                        ]">
                            <svg class="w-5 h-5"
                                :class="!checkedSearch ? 'text-blue-500' : roomStatusMeta(room).icon"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H3m14 0h2M3 21h2M9 7h6M9 11h6M9 15h4"/>
                            </svg>
                        </div>

                        <p class="font-bold text-gray-800 text-sm leading-tight">
                            Room {{ room.room_number }}
                        </p>
                        <p v-if="room.room_name" class="text-xs text-gray-500 mt-0.5 truncate">{{ room.room_name }}</p>
                        <div class="flex flex-col gap-0.5 mt-1.5">
                            <span v-if="room.floor" class="text-[11px] text-gray-400">Floor {{ room.floor }}</span>
                            <span v-if="room.building" class="text-[11px] text-gray-400">{{ room.building }}</span>
                        </div>

                        <!-- Action button -->
                        <div class="mt-auto pt-3">
                            <Link v-if="!checkedSearch || isAvailable(room)"
                                :href="bookNowHref(room)"
                                class="block text-center text-xs py-1.5 rounded-lg font-semibold bg-green-600 text-white hover:bg-green-700 transition-colors"
                            >
                                Book Now
                            </Link>
                            <div v-else
                                class="text-center text-xs py-1.5 rounded-lg font-medium select-none"
                                :class="roomStatusMeta(room).soft + ' ' + roomStatusMeta(room).icon"
                            >
                                {{ roomStatusMeta(room).label }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </AdminLayout>
</template>

<script setup>
import { computed, ref } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';

const props = defineProps({
    rooms:              { type: Array,   default: () => [] },
    roomTypes:          { type: Array,   default: () => [] },
    unavailableRoomIds: { type: Array,   default: () => [] },
    roomBookingStatus:  { type: Object,  default: () => ({}) },
    checkedSearch:      { type: Boolean, default: false },
    filters:            { type: Object,  default: () => ({}) },
});

const today = new Date().toISOString().slice(0, 10);

// Combined status: the room's own manual status (maintenance/out_of_order takes
// priority — it's an operational override) falls back to whatever booking status
// is active for the searched range, then to the room's manual "occupied" flag,
// then finally "available". Matches the color language already used on the Room
// Management and Bookings pages, extended here with distinct colors per status
// so admin can tell a "confirmed for later" room apart from one with a guest in it.
const STATUS_META = {
    out_of_order:    { label: 'Out of Order',    badge: 'bg-red-500 text-white',    soft: 'bg-red-50',    icon: 'text-red-500',    border: 'border-red-200' },
    maintenance:     { label: 'Maintenance',     badge: 'bg-orange-500 text-white', soft: 'bg-orange-50', icon: 'text-orange-500', border: 'border-orange-200' },
    checked_in:      { label: 'Checked In',      badge: 'bg-teal-600 text-white',   soft: 'bg-teal-50',   icon: 'text-teal-600',   border: 'border-teal-200' },
    payment_pending: { label: 'Payment Pending', badge: 'bg-purple-500 text-white', soft: 'bg-purple-50', icon: 'text-purple-500', border: 'border-purple-200' },
    confirmed:       { label: 'Confirmed',       badge: 'bg-blue-500 text-white',   soft: 'bg-blue-50',   icon: 'text-blue-500',   border: 'border-blue-200' },
    pending:         { label: 'Pending',         badge: 'bg-amber-500 text-white',  soft: 'bg-amber-50',  icon: 'text-amber-500',  border: 'border-amber-200' },
    occupied:        { label: 'Occupied',        badge: 'bg-indigo-500 text-white', soft: 'bg-indigo-50', icon: 'text-indigo-500', border: 'border-indigo-200' },
    available:       { label: 'Available',       badge: 'bg-green-500 text-white',  soft: 'bg-green-50',  icon: 'text-green-600',  border: 'border-green-200' },
};

function roomStatusKey(room) {
    if (room.status === 'out_of_order') return 'out_of_order';
    if (room.status === 'maintenance')  return 'maintenance';
    const bookingStatus = props.roomBookingStatus[room.id];
    if (bookingStatus) return bookingStatus;
    if (room.status === 'occupied') return 'occupied';
    return 'available';
}

function roomStatusMeta(room) {
    return STATUS_META[roomStatusKey(room)] ?? STATUS_META.available;
}

const form = ref({
    check_in:     props.filters?.check_in     ?? '',
    check_out:    props.filters?.check_out    ?? '',
    room_type_id: props.filters?.room_type_id ?? null,
});

const minCheckOut = computed(() => {
    if (!form.value.check_in) return today;
    const d = new Date(form.value.check_in);
    d.setDate(d.getDate() + 1);
    return d.toISOString().slice(0, 10);
});

function isAvailable(room) {
    return roomStatusKey(room) === 'available';
}

const availableCount = computed(() =>
    props.rooms.filter(r => isAvailable(r)).length
);

const unavailableCount = computed(() =>
    props.rooms.filter(r => !isAvailable(r)).length
);

const groupedRooms = computed(() => {
    const map = new Map();
    props.rooms.forEach(room => {
        const typeId   = room.room_type?.id   ?? 0;
        const typeName = room.room_type?.name ?? 'Uncategorised';
        if (!map.has(typeId)) map.set(typeId, { typeId, typeName, rooms: [] });
        map.get(typeId).rooms.push(room);
    });
    return [...map.values()];
});

function formatDate(dateStr) {
    if (!dateStr) return '';
    const d = new Date(dateStr + 'T00:00:00');
    return d.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
}

function search() {
    if (!form.value.check_in || !form.value.check_out) return;
    router.get(route('admin.room-availability.index'), {
        check_in:     form.value.check_in,
        check_out:    form.value.check_out,
        room_type_id: form.value.room_type_id || undefined,
    }, { preserveState: false });
}

function reset() {
    form.value = { check_in: '', check_out: '', room_type_id: null };
    router.get(route('admin.room-availability.index'));
}

function bookNowHref(room) {
    const params = new URLSearchParams();
    params.set('room_id', room.id);
    if (room.room_type?.id) params.set('room_type_id', room.room_type.id);
    if (form.value.check_in)  params.set('check_in_date',  form.value.check_in);
    if (form.value.check_out) params.set('check_out_date', form.value.check_out);
    return route('admin.room-bookings.create') + '?' + params.toString();
}
</script>
