<template>
    <AdminLayout>
        <div class="max-w-6xl space-y-6">
            <div class="flex items-center justify-between">
                <h1 class="text-lg font-semibold text-gray-800">Individual Rooms</h1>
                <Link :href="route('admin.rooms.create')"
                    class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                    + Add Room
                </Link>
            </div>

            <!-- Flash -->
            <div v-if="$page.props.flash?.success"
                class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded">
                {{ $page.props.flash.success }}
            </div>

            <!-- Status Stats -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                <button v-for="s in statusOptions" :key="s.key"
                    @click="filterStatus = filterStatus === s.key ? '' : s.key"
                    :class="[
                        'rounded-lg p-3 text-center border-2 transition-colors',
                        filterStatus === s.key ? s.activeClass : 'bg-white border-transparent shadow-sm hover:border-gray-200'
                    ]">
                    <div class="text-xl font-bold" :class="s.textClass">{{ countByStatus(s.key) }}</div>
                    <div class="text-xs text-gray-500 mt-0.5">{{ s.label }}</div>
                </button>
            </div>

            <!-- Filter Bar -->
            <div class="flex gap-3">
                <select v-model="filterType" class="input max-w-xs">
                    <option value="">All Room Types</option>
                    <option v-for="rt in roomTypes" :key="rt.id" :value="rt.id">{{ rt.name }}</option>
                </select>
                <input v-model="search" type="text" placeholder="Search room number..." class="input max-w-xs" />
            </div>

            <!-- Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Room #</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Floor</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600">Active</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-if="filtered.length === 0">
                            <td colspan="6" class="px-4 py-8 text-center text-gray-400 text-sm">No rooms found.</td>
                        </tr>
                        <tr v-for="room in filtered" :key="room.id" class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-mono font-medium text-gray-800">{{ room.room_number }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ room.room_type?.name }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ room.floor || '—' }}</td>
                            <td class="px-4 py-3 text-center">
                                <span :class="statusBadge(room.status)" class="px-2 py-0.5 rounded-full text-xs font-medium">
                                    {{ statusLabel(room.status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span :class="room.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'"
                                    class="px-2 py-0.5 rounded-full text-xs font-medium">
                                    {{ room.is_active ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button @click="viewRoom(room)"
                                        class="text-xs px-2 py-1 rounded border border-indigo-300 text-indigo-600 hover:bg-indigo-50">
                                        View
                                    </button>
                                    <button @click="toggleStatus(room)"
                                        class="text-xs px-2 py-1 rounded border border-gray-300 text-gray-600 hover:bg-gray-50">
                                        {{ room.is_active ? 'Disable' : 'Enable' }}
                                    </button>
                                    <Link :href="route('admin.rooms.edit', room.id)"
                                        class="text-xs px-2 py-1 rounded border border-blue-300 text-blue-600 hover:bg-blue-50">
                                        Edit
                                    </Link>
                                    <button @click="confirmDelete(room)"
                                        class="text-xs px-2 py-1 rounded border border-red-300 text-red-500 hover:bg-red-50">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Delete Modal -->
        <div v-if="deleteTarget" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="bg-white rounded-lg shadow-xl p-6 max-w-sm w-full mx-4">
                <h3 class="text-base font-semibold text-gray-800 mb-2">Delete Room</h3>
                <p class="text-sm text-gray-600 mb-4">Delete room <strong>{{ deleteTarget.room_number }}</strong>?</p>
                <div class="flex gap-3 justify-end">
                    <button @click="deleteTarget = null"
                        class="px-4 py-2 text-sm border border-gray-300 rounded text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button @click="doDelete"
                        class="px-4 py-2 text-sm bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
                </div>
            </div>
        </div>

        <!-- View Room Modal -->
        <div v-if="viewTarget" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4" @click.self="viewTarget = null">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg">
                <!-- Modal header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-indigo-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H3m14 0h2M3 21h2M9 7h6M9 11h6M9 15h4"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-gray-800">Room {{ viewTarget.room_number }}</h2>
                            <p class="text-xs text-gray-400">{{ viewTarget.room_type?.name ?? '—' }}</p>
                        </div>
                    </div>
                    <button @click="viewTarget = null" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Modal body -->
                <div class="px-6 py-5 space-y-4">
                    <!-- Status badges row -->
                    <div class="flex gap-2 flex-wrap">
                        <span :class="statusBadge(viewTarget.status)" class="px-3 py-1 rounded-full text-xs font-semibold">
                            {{ statusLabel(viewTarget.status) }}
                        </span>
                        <span :class="viewTarget.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'"
                            class="px-3 py-1 rounded-full text-xs font-semibold">
                            {{ viewTarget.is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <!-- Info grid -->
                    <div class="grid grid-cols-2 gap-x-6 gap-y-3">
                        <div>
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Room Number</p>
                            <p class="text-sm font-medium text-gray-800 mt-0.5">{{ viewTarget.room_number }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Room Name</p>
                            <p class="text-sm text-gray-700 mt-0.5">{{ viewTarget.room_name || '—' }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Room Type</p>
                            <p class="text-sm text-gray-700 mt-0.5">{{ viewTarget.room_type?.name || '—' }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Price / Night</p>
                            <p class="text-sm text-gray-700 mt-0.5">
                                <span v-if="viewTarget.room_type?.price">৳{{ Number(viewTarget.room_type.price).toLocaleString() }}{{ viewTarget.room_type.price_unit }}</span>
                                <span v-else>—</span>
                            </p>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Floor</p>
                            <p class="text-sm text-gray-700 mt-0.5">{{ viewTarget.floor || '—' }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Building / Block</p>
                            <p class="text-sm text-gray-700 mt-0.5">{{ viewTarget.building || '—' }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Max Adults</p>
                            <p class="text-sm text-gray-700 mt-0.5">{{ viewTarget.room_type?.max_adults ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Max Children</p>
                            <p class="text-sm text-gray-700 mt-0.5">{{ viewTarget.room_type?.max_children ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Check-In Time</p>
                            <p class="text-sm text-gray-700 mt-0.5">{{ viewTarget.room_type?.check_in_time || '—' }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Check-Out Time</p>
                            <p class="text-sm text-gray-700 mt-0.5">{{ viewTarget.room_type?.check_out_time || '—' }}</p>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div v-if="viewTarget.notes">
                        <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide mb-1">Internal Notes</p>
                        <p class="text-sm text-gray-600 bg-gray-50 rounded-lg px-3 py-2 border border-gray-100">{{ viewTarget.notes }}</p>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
                    <button @click="viewTarget = null"
                        class="px-4 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors">
                        Close
                    </button>
                    <Link :href="route('admin.rooms.edit', viewTarget.id)"
                        class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Edit Room
                    </Link>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';

const props = defineProps({
    rooms:     Array,
    roomTypes: Array,
});

const filterStatus = ref('');
const filterType   = ref('');
const search       = ref('');
const deleteTarget = ref(null);
const viewTarget   = ref(null);

const statusOptions = [
    { key: 'available',    label: 'Available',    textClass: 'text-green-600',  activeClass: 'bg-green-50 border-green-400' },
    { key: 'occupied',     label: 'Occupied',     textClass: 'text-blue-600',   activeClass: 'bg-blue-50 border-blue-400' },
    { key: 'maintenance',  label: 'Maintenance',  textClass: 'text-yellow-600', activeClass: 'bg-yellow-50 border-yellow-400' },
    { key: 'out_of_order', label: 'Out of Order', textClass: 'text-red-600',    activeClass: 'bg-red-50 border-red-400' },
    { key: '',             label: 'All Rooms',    textClass: 'text-gray-600',   activeClass: 'bg-gray-100 border-gray-400' },
];

function countByStatus(key) {
    if (!key) return props.rooms.length;
    return props.rooms.filter(r => r.status === key).length;
}

const filtered = computed(() => props.rooms.filter(r => {
    if (filterStatus.value && r.status !== filterStatus.value) return false;
    if (filterType.value && r.room_type_id !== filterType.value) return false;
    if (search.value && !r.room_number.toLowerCase().includes(search.value.toLowerCase())) return false;
    return true;
}));

function statusLabel(s) {
    return { available: 'Available', occupied: 'Occupied', maintenance: 'Maintenance', out_of_order: 'Out of Order' }[s] ?? s;
}

function statusBadge(s) {
    return {
        available:    'bg-green-100 text-green-700',
        occupied:     'bg-blue-100 text-blue-700',
        maintenance:  'bg-yellow-100 text-yellow-700',
        out_of_order: 'bg-red-100 text-red-600',
    }[s] ?? 'bg-gray-100 text-gray-600';
}

function viewRoom(room) { viewTarget.value = room; }
function toggleStatus(room) { router.patch(route('admin.rooms.toggle', room.id)); }
function confirmDelete(room) { deleteTarget.value = room; }
function doDelete() {
    router.delete(route('admin.rooms.destroy', deleteTarget.value.id), {
        onFinish: () => { deleteTarget.value = null; },
    });
}
</script>

<style scoped>
.input { @apply border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors bg-white; }
select.input { @apply cursor-pointer; }
</style>
