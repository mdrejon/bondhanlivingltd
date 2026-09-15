<template>
    <AdminLayout>
        <div class="space-y-6">
            <!-- Page header -->
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div>
                    <h1 class="text-lg font-semibold text-gray-800">Discount Report</h1>
                    <p class="text-xs text-gray-400 mt-0.5">Bookings with discounts grouped by date</p>
                </div>
                <div class="flex gap-2">
                    <a :href="exportUrl('csv')"
                        class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium rounded border border-green-300 text-green-700 bg-green-50 hover:bg-green-100 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Export CSV
                    </a>
                    <a :href="exportUrl('pdf')"
                        class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium rounded border border-red-300 text-red-700 bg-red-50 hover:bg-red-100 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Export PDF
                    </a>
                </div>
            </div>

            <!-- Filter bar -->
            <div class="bg-white rounded-lg shadow-sm p-4 flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Period</label>
                    <div class="flex rounded overflow-hidden border border-gray-200">
                        <button v-for="p in periods" :key="p.value"
                            @click="setFilter('period', p.value)"
                            :class="['px-3 py-1.5 text-xs font-medium transition',
                                localFilters.period === p.value
                                    ? 'bg-indigo-600 text-white'
                                    : 'bg-white text-gray-600 hover:bg-gray-50']">
                            {{ p.label }}
                        </button>
                    </div>
                </div>

                <div v-if="localFilters.period !== 'custom'">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Year</label>
                    <select v-model="localFilters.year" @change="applyFilters"
                        class="text-sm border border-gray-200 rounded px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <option v-for="y in years" :key="y" :value="y">{{ y }}</option>
                    </select>
                </div>

                <div v-if="localFilters.period === 'monthly'">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Month</label>
                    <select v-model="localFilters.month" @change="applyFilters"
                        class="text-sm border border-gray-200 rounded px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <option v-for="m in months" :key="m.value" :value="m.value">{{ m.label }}</option>
                    </select>
                </div>

                <template v-if="localFilters.period === 'custom'">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">From</label>
                        <input type="date" v-model="localFilters.date_from"
                            class="text-sm border border-gray-200 rounded px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">To</label>
                        <input type="date" v-model="localFilters.date_to"
                            class="text-sm border border-gray-200 rounded px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                    </div>
                    <button @click="applyFilters"
                        class="px-4 py-1.5 text-xs font-medium bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                        Apply
                    </button>
                </template>
            </div>

            <!-- Summary cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-indigo-500">
                    <p class="text-xs text-gray-400 font-medium">Total Bookings</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ summary.total_bookings }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-yellow-500">
                    <p class="text-xs text-gray-400 font-medium">Discounted Bookings</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ summary.discount_bookings }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-red-500">
                    <p class="text-xs text-gray-400 font-medium">Total Discount Given</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ fmt(summary.total_discount) }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-green-500">
                    <p class="text-xs text-gray-400 font-medium">Original &rarr; Final</p>
                    <p class="text-base font-bold text-gray-800 mt-1">
                        {{ fmt(summary.total_original) }}
                        <span class="text-gray-400 text-sm font-normal mx-1">&rarr;</span>
                        {{ fmt(summary.total_final) }}
                    </p>
                </div>
            </div>

            <!-- Data table -->
            <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Date</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600">Bookings</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600">Discounted</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600">Discount Amount</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600">Original Amount</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600">Final Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-if="rows.length === 0">
                            <td colspan="6" class="px-4 py-10 text-center text-gray-400 text-sm">
                                No discounted bookings found for the selected period.
                            </td>
                        </tr>
                        <tr v-for="row in rows" :key="row.date_label" class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-700">{{ row.date_label }}</td>
                            <td class="px-4 py-3 text-right">
                                <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded-full text-xs font-medium">
                                    {{ row.bookings_count }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <span class="px-2 py-0.5 bg-yellow-50 text-yellow-700 rounded-full text-xs font-medium">
                                    {{ row.discount_count }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-medium text-red-600">{{ fmt(row.total_discount) }}</td>
                            <td class="px-4 py-3 text-right text-gray-500 line-through">{{ fmt(row.original_amount) }}</td>
                            <td class="px-4 py-3 text-right font-medium text-green-700">{{ fmt(row.final_amount) }}</td>
                        </tr>

                        <!-- Totals row -->
                        <tr v-if="rows.length > 0" class="bg-gray-50 font-semibold border-t-2 border-gray-300">
                            <td class="px-4 py-3 text-xs text-gray-500 uppercase">Total</td>
                            <td class="px-4 py-3 text-right text-gray-800">{{ summary.total_bookings }}</td>
                            <td class="px-4 py-3 text-right text-gray-800">{{ summary.discount_bookings }}</td>
                            <td class="px-4 py-3 text-right text-red-600">{{ fmt(summary.total_discount) }}</td>
                            <td class="px-4 py-3 text-right text-gray-500">{{ fmt(summary.total_original) }}</td>
                            <td class="px-4 py-3 text-right text-green-700">{{ fmt(summary.total_final) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue'

const props = defineProps({
    filters:     { type: Object, default: () => ({}) },
    summary:     { type: Object, default: () => ({}) },
    rows:        { type: Array,  default: () => [] },
    years:       { type: Array,  default: () => [] },
    report_type: { type: String, default: 'discount' },
})

const localFilters = ref({
    period:    props.filters.period    || 'monthly',
    year:      props.filters.year      || new Date().getFullYear(),
    month:     props.filters.month     || (new Date().getMonth() + 1),
    date_from: props.filters.date_from || '',
    date_to:   props.filters.date_to   || '',
})

const periods = [
    { value: 'monthly', label: 'Monthly' },
    { value: 'yearly',  label: 'Yearly'  },
    { value: 'custom',  label: 'Custom'  },
]

const months = [
    { value: 1,  label: 'January'   },
    { value: 2,  label: 'February'  },
    { value: 3,  label: 'March'     },
    { value: 4,  label: 'April'     },
    { value: 5,  label: 'May'       },
    { value: 6,  label: 'June'      },
    { value: 7,  label: 'July'      },
    { value: 8,  label: 'August'    },
    { value: 9,  label: 'September' },
    { value: 10, label: 'October'   },
    { value: 11, label: 'November'  },
    { value: 12, label: 'December'  },
]

function setFilter(key, value) {
    localFilters.value[key] = value
    if (key === 'period' && value !== 'custom') {
        applyFilters()
    }
}

function applyFilters() {
    router.get(route('admin.reports.discount'), { ...localFilters.value }, {
        preserveState: true,
        replace: true,
    })
}

function exportUrl(format) {
    const params = new URLSearchParams({
        type:      'discount',
        period:    localFilters.value.period,
        year:      localFilters.value.year,
        month:     localFilters.value.month,
        date_from: localFilters.value.date_from,
        date_to:   localFilters.value.date_to,
    })
    return format === 'csv'
        ? route('admin.reports.export.csv') + '?' + params.toString()
        : route('admin.reports.export.pdf') + '?' + params.toString()
}

function fmt(value) {
    const num = parseFloat(value) || 0
    return '৳ ' + num.toLocaleString('en-BD', { minimumFractionDigits: 0, maximumFractionDigits: 0 })
}
</script>
