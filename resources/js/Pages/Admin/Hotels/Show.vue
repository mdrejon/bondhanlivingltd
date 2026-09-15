<template>
    <AdminLayout :title="hotel.name">
        <div class="max-w-5xl space-y-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Link :href="route('admin.hotels.index')" class="text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><polyline points="15 18 9 12 15 6"/></svg>
                    </Link>
                    <div class="flex items-center gap-3">
                        <img v-if="hotel.logo" :src="`/storage/${hotel.logo}`" class="w-12 h-12 rounded-lg object-cover border border-gray-200" />
                        <div>
                            <h1 class="text-lg font-semibold text-gray-800">{{ hotel.name }}</h1>
                            <p class="text-xs text-gray-400" v-if="hotel.is_primary_site">Primary public website hotel</p>
                        </div>
                    </div>
                </div>
                <Link :href="route('admin.hotels.edit', hotel.id)"
                    class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                    Edit Hotel
                </Link>
            </div>

            <div v-if="$page.props.flash?.success"
                class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded">
                {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash?.error"
                class="px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded">
                {{ $page.props.flash.error }}
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="bg-white rounded-lg shadow-sm p-4 text-center">
                    <p class="text-xl font-bold text-gray-800">{{ hotel.room_types_count }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Room Types</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-4 text-center">
                    <p class="text-xl font-bold text-gray-800">{{ hotel.rooms_count }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Rooms</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-4 text-center">
                    <p class="text-xl font-bold text-gray-800">{{ hotel.customers_count }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Guests</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-4 text-center">
                    <p class="text-xl font-bold text-gray-800">{{ hotel.bookings_count }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Bookings</p>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-6">
                <!-- Details -->
                <div class="col-span-2 space-y-6">
                    <section class="bg-white rounded-lg shadow-sm p-6 space-y-3">
                        <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Details</h2>
                        <div class="grid grid-cols-2 gap-x-6 gap-y-3">
                            <div v-for="f in detailFields" :key="f.label">
                                <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">{{ f.label }}</p>
                                <p class="text-sm text-gray-700 mt-0.5">{{ f.value || '—' }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Address</p>
                            <p class="text-sm text-gray-700 mt-0.5">{{ hotel.address }}</p>
                        </div>
                    </section>

                    <section class="bg-white rounded-lg shadow-sm p-6 space-y-3">
                        <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Documents</h2>
                        <div v-if="hotel.documents.length === 0" class="text-sm text-gray-400">No documents uploaded yet.</div>
                        <ul v-else class="divide-y divide-gray-100">
                            <li v-for="doc in hotel.documents" :key="doc.id" class="py-2 flex items-center justify-between text-sm">
                                <div>
                                    <span class="font-medium text-gray-700 capitalize">{{ doc.category.replaceAll('_', ' ') }}</span>
                                    <span class="text-gray-400 ml-2">{{ doc.original_filename }}</span>
                                </div>
                                <a :href="route('admin.documents.show', doc.id)" target="_blank" class="text-blue-600 hover:underline text-xs">View</a>
                            </li>
                        </ul>
                    </section>

                    <section class="bg-white rounded-lg shadow-sm p-6 space-y-3">
                        <div class="flex items-center justify-between border-b pb-2">
                            <h2 class="text-sm font-semibold text-gray-700">Staff</h2>
                            <Link :href="route('admin.users.create')" class="text-xs text-blue-600 hover:underline">+ Add User</Link>
                        </div>
                        <div v-if="staff.length === 0" class="text-sm text-gray-400">
                            No staff assigned yet — assign a user to this hotel from the Users screen.
                        </div>
                        <ul v-else class="divide-y divide-gray-100">
                            <li v-for="user in staff" :key="user.id" class="py-2 flex items-center justify-between text-sm">
                                <div>
                                    <span class="font-medium text-gray-700">{{ user.name }}</span>
                                    <span class="text-gray-400 ml-2">{{ user.email }}</span>
                                </div>
                                <span class="text-xs text-gray-500">{{ user.role?.name ?? 'Super Admin' }}</span>
                            </li>
                        </ul>
                    </section>
                </div>

                <!-- Status control -->
                <div class="space-y-6">
                    <section class="bg-white rounded-lg shadow-sm p-6 space-y-3">
                        <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Public Website</h2>
                        <div>
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Site URL</p>
                            <a :href="publicUrl" target="_blank" class="text-sm text-blue-600 hover:underline break-all">{{ publicUrl }}</a>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Template</p>
                            <p class="text-sm text-gray-700 mt-0.5 capitalize">{{ hotel.template || 'default' }}</p>
                        </div>
                        <p v-if="hotel.is_primary_site" class="text-xs text-gray-400">
                            Also served at the root site (/) as the primary hotel.
                        </p>
                    </section>

                    <section class="bg-white rounded-lg shadow-sm p-6 space-y-3">
                        <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Status</h2>
                        <span :class="statusBadge(hotel.status)" class="inline-block px-3 py-1 rounded-full text-xs font-medium capitalize">
                            {{ hotel.status }}
                        </span>
                        <div class="pt-2 space-y-2">
                            <button v-for="s in otherStatuses" :key="s"
                                @click="changeStatus(s)"
                                class="w-full text-left text-sm px-3 py-2 rounded border border-gray-200 hover:bg-gray-50 capitalize">
                                Mark as {{ s }}
                            </button>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';

const props = defineProps({
    hotel: Object,
    staff: Array,
});

const publicUrl = computed(() => `${window.location.origin}/hotel/${props.hotel.slug}`);

const detailFields = computed(() => [
    { label: 'Category', value: props.hotel.category },
    { label: 'Total Rooms', value: props.hotel.total_rooms },
    { label: 'Mobile', value: props.hotel.mobile },
    { label: 'Email', value: props.hotel.email },
    { label: 'District', value: props.hotel.district?.name },
    { label: 'Upazila', value: props.hotel.upazila?.name },
    { label: 'Police Station', value: props.hotel.police_station?.name },
    { label: 'Trade License No.', value: props.hotel.trade_license_no },
    { label: 'BIN No.', value: props.hotel.bin_no },
    { label: 'TIN No.', value: props.hotel.tin_no },
    { label: 'Owner Name', value: props.hotel.owner_name },
    { label: 'Owner NID', value: props.hotel.owner_nid },
]);

const allStatuses = ['pending', 'active', 'suspended', 'rejected'];
const otherStatuses = computed(() => allStatuses.filter(s => s !== props.hotel.status));

function statusBadge(status) {
    return {
        active:    'bg-green-100 text-green-700',
        pending:   'bg-yellow-100 text-yellow-700',
        suspended: 'bg-orange-100 text-orange-700',
        rejected:  'bg-red-100 text-red-600',
    }[status] ?? 'bg-gray-100 text-gray-600';
}

function changeStatus(status) {
    if (['suspended', 'rejected'].includes(status)) {
        if (!confirm(`Are you sure you want to mark "${props.hotel.name}" as ${status}? Staff at this hotel may lose access.`)) {
            return;
        }
    }
    router.patch(route('admin.hotels.update-status', props.hotel.id), { status });
}
</script>
