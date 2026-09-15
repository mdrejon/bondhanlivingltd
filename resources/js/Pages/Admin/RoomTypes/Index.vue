<template>
    <AdminLayout>
        <div class="max-w-6xl space-y-6">
            <div class="flex items-center justify-between">
                <h1 class="text-lg font-semibold text-gray-800">Room Types</h1>
                <Link :href="route('admin.room-types.create')"
                    class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                    + Add Room Type
                </Link>
            </div>

            <!-- Flash -->
            <div v-if="$page.props.flash?.success"
                class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded">
                {{ $page.props.flash.success }}
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg shadow-sm p-4 text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ roomTypes.length }}</div>
                    <div class="text-xs text-gray-500 mt-1">Total Types</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-4 text-center">
                    <div class="text-2xl font-bold text-green-600">{{ roomTypes.filter(r => r.is_active).length }}</div>
                    <div class="text-xs text-gray-500 mt-1">Active</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-4 text-center">
                    <div class="text-2xl font-bold text-yellow-600">{{ roomTypes.filter(r => r.is_featured).length }}</div>
                    <div class="text-xs text-gray-500 mt-1">Featured</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-4 text-center">
                    <div class="text-2xl font-bold text-gray-700">{{ roomTypes.reduce((s, r) => s + (r.rooms_count || 0), 0) }}</div>
                    <div class="text-xs text-gray-500 mt-1">Total Rooms</div>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">#</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Room Type</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Bed Type</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600">Price</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600">Max Guests</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600">Rooms</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600">Rating</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-if="roomTypes.length === 0">
                            <td colspan="9" class="px-4 py-8 text-center text-gray-400 text-sm">No room types yet. Add your first one.</td>
                        </tr>
                        <tr v-for="(rt, index) in roomTypes" :key="rt.id" class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-500">{{ index + 1 }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-800">{{ rt.name }}</div>
                                <div class="text-xs text-gray-400">{{ rt.short_desc?.substring(0, 60) }}</div>
                                <span v-if="rt.is_featured"
                                    class="inline-block mt-1 px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs rounded-full">
                                    ★ Featured
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ rt.bed_type || '—' }}</td>
                            <td class="px-4 py-3 text-right font-medium text-gray-800">
                                ৳{{ Number(rt.price).toLocaleString() }}
                                <span class="text-xs text-gray-400">{{ rt.price_unit }}</span>
                            </td>
                            <td class="px-4 py-3 text-center text-gray-600">
                                {{ rt.max_adults }}A + {{ rt.max_children }}C
                            </td>
                            <td class="px-4 py-3 text-center text-gray-700 font-medium">{{ rt.rooms_count }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-yellow-500">★</span>
                                <span class="text-gray-700 text-xs">{{ rt.rating }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span :class="rt.is_active
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-gray-100 text-gray-500'"
                                    class="px-2 py-0.5 rounded-full text-xs font-medium">
                                    {{ rt.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button @click="viewTarget = rt"
                                        class="text-xs px-2 py-1 rounded border border-indigo-300 text-indigo-600 hover:bg-indigo-50">
                                        View
                                    </button>
                                    <button @click="toggleStatus(rt)"
                                        class="text-xs px-2 py-1 rounded border"
                                        :class="rt.is_active ? 'border-gray-300 text-gray-600 hover:bg-gray-50' : 'border-green-300 text-green-600 hover:bg-green-50'">
                                        {{ rt.is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                    <Link :href="route('admin.room-types.edit', rt.id)"
                                        class="text-xs px-2 py-1 rounded border border-blue-300 text-blue-600 hover:bg-blue-50">
                                        Edit
                                    </Link>
                                    <button @click="confirmDelete(rt)"
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
                <h3 class="text-base font-semibold text-gray-800 mb-2">Delete Room Type</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Delete <strong>{{ deleteTarget.name }}</strong>? This will also remove all associated rooms.
                </p>
                <div class="flex gap-3 justify-end">
                    <button @click="deleteTarget = null"
                        class="px-4 py-2 text-sm border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button @click="doDelete"
                        class="px-4 py-2 text-sm bg-red-600 text-white rounded hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </div>
        </div>

        <!-- View Room Type Modal -->
        <div v-if="viewTarget" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4" @click.self="viewTarget = null">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col">
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 flex-shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H3m14 0h2M3 21h2M9 7h6M9 11h6M9 15h4"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-gray-800">{{ viewTarget.name }}</h2>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span :class="viewTarget.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'"
                                    class="px-2 py-0.5 rounded-full text-[10px] font-semibold">
                                    {{ viewTarget.is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <span v-if="viewTarget.is_featured"
                                    class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-yellow-100 text-yellow-700">
                                    ★ Featured
                                </span>
                            </div>
                        </div>
                    </div>
                    <button @click="viewTarget = null" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Scrollable body -->
                <div class="overflow-y-auto px-6 py-5 space-y-5">
                    <!-- Key info grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-4">
                        <div>
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Price</p>
                            <p class="text-sm font-semibold text-gray-800 mt-0.5">
                                ৳{{ Number(viewTarget.price).toLocaleString() }}
                                <span class="text-xs text-gray-400 font-normal">{{ viewTarget.price_unit }}</span>
                            </p>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Bed Type</p>
                            <p class="text-sm text-gray-700 mt-0.5">{{ viewTarget.bed_type || '—' }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Total Rooms</p>
                            <p class="text-sm text-gray-700 mt-0.5">{{ viewTarget.rooms_count ?? 0 }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Max Adults</p>
                            <p class="text-sm text-gray-700 mt-0.5">{{ viewTarget.max_adults }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Max Children</p>
                            <p class="text-sm text-gray-700 mt-0.5">{{ viewTarget.max_children }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Rating</p>
                            <p class="text-sm text-gray-700 mt-0.5">
                                <span class="text-yellow-500">★</span> {{ viewTarget.rating }}
                            </p>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Check-In Time</p>
                            <p class="text-sm text-gray-700 mt-0.5">{{ viewTarget.check_in_time || '—' }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Check-Out Time</p>
                            <p class="text-sm text-gray-700 mt-0.5">{{ viewTarget.check_out_time || '—' }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Sort Order</p>
                            <p class="text-sm text-gray-700 mt-0.5">{{ viewTarget.sort_order }}</p>
                        </div>
                    </div>

                    <!-- Short description -->
                    <div v-if="viewTarget.short_desc">
                        <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide mb-1">Short Description</p>
                        <p class="text-sm text-gray-600 bg-gray-50 rounded-lg px-3 py-2 border border-gray-100">{{ viewTarget.short_desc }}</p>
                    </div>

                    <!-- Full description -->
                    <div v-if="viewTarget.description">
                        <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide mb-1">Full Description</p>
                        <p class="text-sm text-gray-600 bg-gray-50 rounded-lg px-3 py-2 border border-gray-100 whitespace-pre-line">{{ viewTarget.description }}</p>
                    </div>

                    <!-- Features -->
                    <div v-if="viewTarget.features?.length">
                        <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide mb-2">Features</p>
                        <div class="flex flex-wrap gap-2">
                            <span v-for="(f, i) in viewTarget.features" :key="i"
                                class="px-2.5 py-1 bg-blue-50 text-blue-700 text-xs rounded-full border border-blue-100">
                                {{ f }}
                            </span>
                        </div>
                    </div>

                    <!-- Amenities -->
                    <div v-if="viewTarget.amenities?.length">
                        <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide mb-2">Amenities</p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            <div v-for="(am, i) in viewTarget.amenities" :key="i"
                                class="flex items-center gap-2 text-xs text-gray-700 bg-gray-50 rounded-lg px-2.5 py-1.5 border border-gray-100">
                                <span v-html="am.icon_svg" class="w-4 h-4 flex-shrink-0 text-gray-500"></span>
                                {{ am.label }}
                            </div>
                        </div>
                    </div>

                    <!-- Meta info -->
                    <div v-if="viewTarget.meta_title || viewTarget.meta_description || viewTarget.meta_keywords" class="border-t border-gray-100 pt-4">
                        <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide mb-3">SEO / Meta</p>
                        <div class="space-y-2">
                            <div v-if="viewTarget.meta_title">
                                <p class="text-[10px] text-gray-400 font-medium">Title</p>
                                <p class="text-xs text-gray-700">{{ viewTarget.meta_title }}</p>
                            </div>
                            <div v-if="viewTarget.meta_description">
                                <p class="text-[10px] text-gray-400 font-medium">Description</p>
                                <p class="text-xs text-gray-700">{{ viewTarget.meta_description }}</p>
                            </div>
                            <div v-if="viewTarget.meta_keywords">
                                <p class="text-[10px] text-gray-400 font-medium">Keywords</p>
                                <p class="text-xs text-gray-700">{{ viewTarget.meta_keywords }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 flex-shrink-0">
                    <button @click="viewTarget = null"
                        class="px-4 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors">
                        Close
                    </button>
                    <Link :href="route('admin.room-types.edit', viewTarget.id)"
                        class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Edit Room Type
                    </Link>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';

const props = defineProps({
    roomTypes: Array,
});

const deleteTarget = ref(null);
const viewTarget   = ref(null);

function confirmDelete(rt) {
    deleteTarget.value = rt;
}

function doDelete() {
    router.delete(route('admin.room-types.destroy', deleteTarget.value.id), {
        onFinish: () => { deleteTarget.value = null; },
    });
}

function toggleStatus(rt) {
    router.patch(route('admin.room-types.toggle', rt.id));
}
</script>

<style scoped>
.input { @apply border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors bg-white; }
</style>
