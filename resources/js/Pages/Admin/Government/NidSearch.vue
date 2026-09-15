<template>
    <AdminLayout title="NID / Passport Search">
        <div class="max-w-4xl space-y-6">
            <div>
                <h1 class="text-lg font-semibold text-gray-800">NID / Passport Search</h1>
                <p class="text-sm text-gray-500">Search guests by NID or passport number. Leave hotel unselected to search every hotel in your jurisdiction, or pick one to narrow the results.</p>
            </div>

            <form @submit.prevent="search" class="bg-white rounded-lg shadow-sm p-4 flex flex-wrap items-end gap-3">
                <div class="flex-1 min-w-[16rem]">
                    <label class="label">NID or passport number</label>
                    <input v-model="query" type="text" placeholder="Enter full or partial NID / passport number…" class="input w-full" />
                </div>
                <div class="min-w-[12rem]">
                    <label class="label">Hotel</label>
                    <select v-model="hotelId" class="input w-full">
                        <option value="">All Hotels</option>
                        <option v-for="h in hotels" :key="h.id" :value="h.id">{{ h.name }}</option>
                    </select>
                </div>
                <button type="submit" class="px-5 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">Search</button>
            </form>

            <div v-if="props.query" class="bg-white rounded-lg shadow-sm overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">NID</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Passport</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Mobile</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Nationality</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Hotel</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-if="results.length === 0">
                            <td colspan="7" class="px-4 py-8 text-center text-gray-400 text-sm">No matches found in your jurisdiction for "{{ props.query }}".</td>
                        </tr>
                        <tr v-for="r in results" :key="r.id" class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-800">
                                {{ r.name }}
                                <span v-if="r.is_foreign_guest" class="text-xs text-purple-600 ml-1">🌍</span>
                            </td>
                            <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ r.nid_number || '—' }}</td>
                            <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ r.passport_number || '—' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ r.phone }}</td>
                            <td class="px-4 py-3 text-gray-600 text-xs">{{ r.nationality || '—' }}</td>
                            <td class="px-4 py-3 text-gray-600 text-xs">{{ r.hotel?.name || '—' }}</td>
                            <td class="px-4 py-3 text-center">
                                <Link :href="route('admin.customers.show', r.id)" class="px-2 py-1 text-xs border border-gray-300 rounded text-gray-600 hover:bg-gray-50">View</Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';

const props = defineProps({
    query: String,
    hotelId: [String, Number],
    hotels: Array,
    results: Array,
});

const query = ref(props.query || '');
const hotelId = ref(props.hotelId || '');

function search() {
    router.get(route('admin.government.nid-search'), { query: query.value, hotel_id: hotelId.value }, { preserveState: true });
}
</script>

<style scoped>
.label { @apply block text-xs font-medium text-gray-600 mb-1; }
.input { @apply border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none bg-white; }
</style>
