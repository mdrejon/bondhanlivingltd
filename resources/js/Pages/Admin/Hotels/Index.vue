<template>
    <AdminLayout title="Hotel Registration">
        <div class="max-w-6xl space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-gray-800">Hotel Registration</h1>
                    <p class="text-sm text-gray-500">Every hotel tenant onboarded onto this platform.</p>
                </div>
                <Link :href="route('admin.hotels.create')"
                    class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                    + Add Hotel
                </Link>
            </div>

            <div v-if="$page.props.flash?.success"
                class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded">
                {{ $page.props.flash.success }}
            </div>

            <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Hotel</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Location</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Category</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600">Rooms</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600">Staff</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-if="hotels.length === 0">
                            <td colspan="7" class="px-4 py-8 text-center text-gray-400 text-sm">No hotels registered yet.</td>
                        </tr>
                        <tr v-for="hotel in hotels" :key="hotel.id" class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <img v-if="hotel.logo" :src="`/storage/${hotel.logo}`" class="w-9 h-9 rounded object-cover border border-gray-200" />
                                    <div v-else class="w-9 h-9 rounded bg-gray-100 flex items-center justify-center text-gray-400 text-xs">🏨</div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ hotel.name }}</p>
                                        <p v-if="hotel.is_primary_site" class="text-[11px] text-blue-500">Primary public site</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ hotel.district?.name }}<span v-if="hotel.upazila">, {{ hotel.upazila.name }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ hotel.category || '—' }}</td>
                            <td class="px-4 py-3 text-center text-gray-600">{{ hotel.rooms_count }}</td>
                            <td class="px-4 py-3 text-center text-gray-600">{{ hotel.users_count }}</td>
                            <td class="px-4 py-3 text-center">
                                <span :class="statusBadge(hotel.status)" class="px-2 py-0.5 rounded-full text-xs font-medium capitalize">
                                    {{ hotel.status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <Link :href="route('admin.hotels.show', hotel.id)"
                                        class="text-xs px-2 py-1 rounded border border-indigo-300 text-indigo-600 hover:bg-indigo-50">
                                        View
                                    </Link>
                                    <Link :href="route('admin.hotels.edit', hotel.id)"
                                        class="text-xs px-2 py-1 rounded border border-blue-300 text-blue-600 hover:bg-blue-50">
                                        Edit
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';

defineProps({
    hotels: Array,
});

function statusBadge(status) {
    return {
        active:    'bg-green-100 text-green-700',
        pending:   'bg-yellow-100 text-yellow-700',
        suspended: 'bg-orange-100 text-orange-700',
        rejected:  'bg-red-100 text-red-600',
    }[status] ?? 'bg-gray-100 text-gray-600';
}
</script>
