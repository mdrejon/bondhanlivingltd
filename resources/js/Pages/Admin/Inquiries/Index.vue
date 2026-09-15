<template>
    <AdminLayout>
        <div class="max-w-7xl space-y-6">
            <div class="flex items-center justify-between">
                <h1 class="text-lg font-semibold text-gray-800">Inquiries</h1>
            </div>

            <!-- Flash -->
            <div v-if="$page.props.flash?.success"
                class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded">
                {{ $page.props.flash.success }}
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg shadow-sm p-4 text-center">
                    <div class="text-2xl font-bold text-gray-700">{{ stats.total }}</div>
                    <div class="text-xs text-gray-400 mt-1">Total</div>
                </div>
                <div class="bg-blue-50 rounded-lg shadow-sm p-4 text-center border border-blue-200">
                    <div class="text-2xl font-bold text-blue-600">{{ stats.new }}</div>
                    <div class="text-xs text-gray-400 mt-1">New</div>
                </div>
                <div class="bg-yellow-50 rounded-lg shadow-sm p-4 text-center border border-yellow-200">
                    <div class="text-2xl font-bold text-yellow-600">{{ stats.read }}</div>
                    <div class="text-xs text-gray-400 mt-1">Read</div>
                </div>
                <div class="bg-green-50 rounded-lg shadow-sm p-4 text-center border border-green-200">
                    <div class="text-2xl font-bold text-green-600">{{ stats.replied }}</div>
                    <div class="text-xs text-gray-400 mt-1">Replied</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="flex flex-wrap gap-3">
                <select v-model="filterStatus" class="input max-w-xs">
                    <option value="">All Statuses</option>
                    <option value="new">New</option>
                    <option value="read">Read</option>
                    <option value="replied">Replied</option>
                </select>
                <select v-model="filterType" class="input max-w-xs">
                    <option value="">All Types</option>
                    <option value="quote">Get A Free Quote</option>
                    <option value="contact_widget">Contact Widget</option>
                    <option value="contact_page">Contact Page</option>
                </select>
                <input v-model="search" type="text" placeholder="Search name / email..." class="input max-w-xs" />
            </div>

            <!-- Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
                <table class="w-full text-sm min-w-[750px]">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Message</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Date</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-if="filtered.length === 0">
                            <td colspan="7" class="px-4 py-8 text-center text-gray-400 text-sm">No inquiries found.</td>
                        </tr>
                        <tr v-for="item in filtered" :key="item.id"
                            class="hover:bg-gray-50"
                            :class="{ 'font-medium': item.status === 'new' }">
                            <td class="px-4 py-3 text-gray-800">
                                <div class="flex items-center gap-2">
                                    <span v-if="item.status === 'new'" class="w-2 h-2 rounded-full bg-blue-500 flex-shrink-0"></span>
                                    {{ item.name }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600 text-xs">{{ item.email }}</td>
                            <td class="px-4 py-3">
                                <span :class="typeBadge(item.type)" class="px-2 py-0.5 rounded text-xs font-medium">
                                    {{ typeLabel(item.type) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs max-w-[200px]">
                                <span class="line-clamp-2">{{ item.message }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span :class="statusBadge(item.status)" class="px-2 py-0.5 rounded-full text-xs font-medium">
                                    {{ item.status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-500">{{ formatDate(item.created_at) }}</td>
                            <td class="px-4 py-3 text-center">
                                <Link :href="route('admin.inquiries.show', item.id)"
                                    class="text-xs px-3 py-1 rounded border border-blue-300 text-blue-600 hover:bg-blue-50">
                                    View
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
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';

const props = defineProps({
    inquiries: Array,
    stats:     Object,
});

const filterStatus = ref('');
const filterType   = ref('');
const search       = ref('');

const filtered = computed(() => props.inquiries.filter(item => {
    if (filterStatus.value && item.status !== filterStatus.value) return false;
    if (filterType.value   && item.type   !== filterType.value)   return false;
    if (search.value) {
        const q = search.value.toLowerCase();
        if (!item.name?.toLowerCase().includes(q) && !item.email?.toLowerCase().includes(q)) return false;
    }
    return true;
}));

function formatDate(d) {
    if (!d) return '—';
    return new Date(d).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
}

function typeLabel(t) {
    return { quote: 'Free Quote', contact_widget: 'Widget', contact_page: 'Contact Page' }[t] ?? t;
}

function typeBadge(t) {
    return { quote: 'bg-blue-100 text-blue-700', contact_widget: 'bg-purple-100 text-purple-700', contact_page: 'bg-teal-100 text-teal-700' }[t] ?? 'bg-gray-100 text-gray-600';
}

function statusBadge(s) {
    return { new: 'bg-blue-100 text-blue-700', read: 'bg-yellow-100 text-yellow-700', replied: 'bg-green-100 text-green-700' }[s] ?? 'bg-gray-100 text-gray-600';
}
</script>

<style scoped>
.input { @apply border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors bg-white; }
select.input { @apply cursor-pointer; }
.line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>
