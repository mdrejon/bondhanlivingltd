<template>
    <AdminLayout title="Nationality Report">
        <div class="max-w-3xl space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-gray-800">Nationality Report</h1>
                    <p class="text-sm text-gray-500">Registered guests grouped by country/nationality, across your jurisdiction. Search a country below to narrow the list, or view the guests behind any row.</p>
                </div>
                <ExportLinks type="nationality" :filters="form" />
            </div>

            <!-- Filters -->
            <form @submit.prevent="applyFilters" class="bg-white rounded-lg shadow-sm p-4 flex flex-wrap items-end gap-3">
                <div class="min-w-[14rem]">
                    <label class="label">Search by country / nationality</label>
                    <input v-model="form.country" type="text" placeholder="e.g. American, Indian…" class="input w-full" />
                </div>
                <label class="flex items-center gap-2 text-sm text-gray-600 pb-2.5">
                    <input v-model="form.foreign_only" type="checkbox" class="w-4 h-4 rounded text-blue-600" />
                    Foreign guests only
                </label>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">Apply</button>
                <Link :href="route('admin.government.nationality')" class="px-4 py-2 border border-gray-300 text-sm rounded text-gray-600 hover:bg-gray-50">Reset</Link>
            </form>

            <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Nationality</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600">Foreign?</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600">Guest Count</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-if="rows.length === 0">
                            <td colspan="4" class="px-4 py-8 text-center text-gray-400 text-sm">No guest records match this search.</td>
                        </tr>
                        <tr v-for="r in rows" :key="r.nationality" class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-800">{{ r.nationality }}</td>
                            <td class="px-4 py-3 text-center">
                                <span v-if="r.is_foreign" class="px-2 py-0.5 bg-purple-100 text-purple-700 rounded-full text-xs">Foreign</span>
                                <span v-else class="text-gray-300">—</span>
                            </td>
                            <td class="px-4 py-3 text-right font-medium">{{ r.count }}</td>
                            <td class="px-4 py-3 text-center">
                                <Link :href="route('admin.government.guests', { nationality: r.nationality })" class="px-2 py-1 text-xs border border-gray-300 rounded text-gray-600 hover:bg-gray-50">
                                    View Guests
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { reactive } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import ExportLinks from '@/Components/Admin/Government/ExportLinks.vue';

const props = defineProps({
    rows: Array,
    filters: Object,
});

const form = reactive({
    country: props.filters?.country || '',
    foreign_only: !!props.filters?.foreign_only,
});

function applyFilters() {
    router.get(route('admin.government.nationality'), form, { preserveState: true });
}
</script>

<style scoped>
.label { @apply block text-xs font-medium text-gray-600 mb-1; }
.input { @apply border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none bg-white; }
</style>
