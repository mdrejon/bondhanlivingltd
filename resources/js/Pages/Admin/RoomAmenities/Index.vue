<template>
    <AdminLayout>
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <h1 class="text-lg font-semibold text-gray-800">Room Amenities</h1>
                <button
                    v-if="!showAddForm"
                    type="button"
                    @click="showAddForm = true"
                    class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors"
                >
                    + Add New Amenity
                </button>
            </div>

            <!-- Flash messages -->
            <div v-if="$page.props.flash?.success"
                class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded">
                {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash?.error"
                class="px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded">
                {{ $page.props.flash.error }}
            </div>

            <!-- Add Form -->
            <div v-if="showAddForm" class="bg-white rounded-lg shadow-sm p-6 border border-blue-100">
                <h2 class="text-sm font-semibold text-gray-700 mb-4">New Amenity</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">Name <span class="text-red-500">*</span></label>
                        <input v-model="addForm.name" type="text" class="input" placeholder="e.g. Gym" />
                        <p v-if="addForm.errors.name" class="text-xs text-red-500 mt-1">{{ addForm.errors.name }}</p>
                    </div>
                    <div>
                        <label class="label">Sort Order</label>
                        <input v-model.number="addForm.sort_order" type="number" min="0" class="input" />
                    </div>
                    <div class="col-span-2">
                        <label class="label">SVG Icon <span class="text-gray-400 font-normal text-xs">(optional)</span></label>
                        <div class="flex gap-3">
                            <textarea v-model="addForm.icon_svg" rows="3" class="input flex-1 text-xs font-mono" placeholder="<svg xmlns=&quot;...&quot;>...</svg>"></textarea>
                            <div class="w-12 h-12 flex items-center justify-center border border-gray-200 rounded bg-gray-50 flex-shrink-0">
                                <span v-html="addForm.icon_svg" class="w-6 h-6 text-gray-600"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input v-model="addForm.is_active" type="checkbox" class="w-4 h-4 rounded text-blue-600" />
                            <span class="text-sm text-gray-600">Active</span>
                        </label>
                    </div>
                </div>
                <div class="flex gap-3 mt-4">
                    <button type="button" @click="submitAdd" :disabled="addForm.processing"
                        class="px-5 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-60">
                        {{ addForm.processing ? 'Saving...' : 'Save Amenity' }}
                    </button>
                    <button type="button" @click="cancelAdd"
                        class="px-4 py-2 text-sm border border-gray-300 rounded text-gray-600 hover:bg-gray-50">
                        Cancel
                    </button>
                </div>
            </div>

            <!-- Amenities Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide w-16">Icon</th>
                            <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Name</th>
                            <th class="text-center px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide w-24">Sort</th>
                            <th class="text-center px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide w-24">Active</th>
                            <th class="text-right px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide w-32">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-if="amenities.length === 0">
                            <td colspan="5" class="text-center px-4 py-8 text-gray-400">No amenities yet. Add one above.</td>
                        </tr>

                        <template v-for="amenity in amenities" :key="amenity.id">
                            <!-- View row -->
                            <tr v-if="editingId !== amenity.id" class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <span v-html="amenity.icon_svg" class="w-6 h-6 text-gray-600 inline-flex items-center justify-center"></span>
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-800">{{ amenity.name }}</td>
                                <td class="px-4 py-3 text-center text-gray-500">{{ amenity.sort_order }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span :class="amenity.is_active
                                        ? 'bg-green-100 text-green-700'
                                        : 'bg-gray-100 text-gray-500'"
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium">
                                        {{ amenity.is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <button type="button" @click="startEdit(amenity)"
                                        class="text-blue-600 hover:text-blue-800 text-xs font-medium mr-3">Edit</button>
                                    <button type="button" @click="deleteAmenity(amenity)"
                                        class="text-red-500 hover:text-red-700 text-xs font-medium">Delete</button>
                                </td>
                            </tr>

                            <!-- Edit row -->
                            <tr v-else class="bg-blue-50">
                                <td class="px-4 py-3">
                                    <div class="w-10 h-10 flex items-center justify-center border border-gray-200 rounded bg-white">
                                        <span v-html="editForm.icon_svg" class="w-5 h-5 text-gray-600"></span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <input v-model="editForm.name" type="text" class="input w-full" placeholder="Name" />
                                    <p v-if="editForm.errors.name" class="text-xs text-red-500 mt-1">{{ editForm.errors.name }}</p>
                                    <div class="mt-2">
                                        <textarea v-model="editForm.icon_svg" rows="2" class="input w-full text-xs font-mono" placeholder="<svg>...</svg>"></textarea>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <input v-model.number="editForm.sort_order" type="number" min="0" class="input w-20 text-center" />
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <input v-model="editForm.is_active" type="checkbox" class="w-4 h-4 rounded text-blue-600" />
                                </td>
                                <td class="px-4 py-3 text-right space-y-1">
                                    <button type="button" @click="submitEdit(amenity.id)" :disabled="editForm.processing"
                                        class="block w-full px-3 py-1.5 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 disabled:opacity-60 text-center">
                                        {{ editForm.processing ? 'Saving...' : 'Save' }}
                                    </button>
                                    <button type="button" @click="cancelEdit"
                                        class="block w-full px-3 py-1.5 border border-gray-300 text-gray-600 text-xs rounded hover:bg-gray-50 text-center">
                                        Cancel
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';

const props = defineProps({
    amenities: { type: Array, default: () => [] },
});

// ── Add form ──────────────────────────────────────────────────────────────────
const showAddForm = ref(false);

const addForm = useForm({
    name:       '',
    icon_svg:   '',
    sort_order: 0,
    is_active:  true,
});

function submitAdd() {
    addForm.post(route('admin.room-amenities.store'), {
        onSuccess: () => {
            addForm.reset();
            showAddForm.value = false;
        },
    });
}

function cancelAdd() {
    addForm.reset();
    showAddForm.value = false;
}

// ── Edit form ─────────────────────────────────────────────────────────────────
const editingId = ref(null);

const editForm = useForm({
    name:       '',
    icon_svg:   '',
    sort_order: 0,
    is_active:  true,
});

function startEdit(amenity) {
    editingId.value     = amenity.id;
    editForm.name       = amenity.name;
    editForm.icon_svg   = amenity.icon_svg ?? '';
    editForm.sort_order = amenity.sort_order;
    editForm.is_active  = amenity.is_active;
}

function submitEdit(id) {
    editForm.put(route('admin.room-amenities.update', id), {
        onSuccess: () => {
            editingId.value = null;
        },
    });
}

function cancelEdit() {
    editingId.value = null;
    editForm.reset();
}

// ── Delete ────────────────────────────────────────────────────────────────────
function deleteAmenity(amenity) {
    if (!confirm(`Delete "${amenity.name}"? This cannot be undone.`)) return;
    router.delete(route('admin.room-amenities.destroy', amenity.id));
}
</script>

<style scoped>
.label { @apply block text-sm font-medium text-gray-700 mb-1.5; }
.input { @apply w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors bg-white; }
textarea.input { @apply resize-none; }
</style>
