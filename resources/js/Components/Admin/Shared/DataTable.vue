<template>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th
                        v-for="col in columns"
                        :key="col.key"
                        class="text-left py-3 px-4 text-gray-500 font-semibold text-xs uppercase tracking-wide"
                    >
                        {{ col.label }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="(row, i) in rows"
                    :key="i"
                    class="border-b border-gray-50 hover:bg-gray-50 transition-colors"
                >
                    <td
                        v-for="col in columns"
                        :key="col.key"
                        class="py-3 px-4 text-gray-700"
                    >
                        <slot :name="col.key" :row="row" :value="row[col.key]">
                            {{ row[col.key] }}
                        </slot>
                    </td>
                </tr>
                <tr v-if="!rows.length">
                    <td :colspan="columns.length" class="py-8 text-center text-gray-400">No records found.</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
defineProps({
    columns: { type: Array, required: true },
    rows:    { type: Array, default: () => [] },
});
</script>
