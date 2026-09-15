<template>
    <div class="flex gap-2">
        <a :href="urlFor('pdf')" target="_blank"
            class="px-3 py-1.5 text-xs border border-gray-300 rounded text-gray-600 hover:bg-gray-50">
            Export PDF
        </a>
        <a :href="urlFor('csv')"
            class="px-3 py-1.5 text-xs border border-gray-300 rounded text-gray-600 hover:bg-gray-50">
            Export CSV
        </a>
    </div>
</template>

<script setup>
const props = defineProps({
    type: { type: String, required: true },
    filters: { type: Object, default: () => ({}) },
});

function urlFor(format) {
    const params = new URLSearchParams({ type: props.type, ...cleanFilters() });
    return route(`admin.government.export.${format}`) + '?' + params.toString();
}

function cleanFilters() {
    const out = {};
    for (const [key, value] of Object.entries(props.filters)) {
        if (value !== null && value !== undefined && value !== '' && value !== false) {
            out[key] = value;
        }
    }
    return out;
}
</script>
