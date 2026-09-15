<template>
    <AdminLayout>
        <div class="max-w-2xl space-y-6">
            <div class="flex items-center gap-4">
                <Link :href="route('admin.rooms.index')" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><polyline points="15 18 9 12 15 6"/></svg>
                </Link>
                <h1 class="text-lg font-semibold text-gray-800">Add New Room</h1>
            </div>

            <form @submit.prevent="submit" class="bg-white rounded-lg shadow-sm p-6 space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">Room Number <span class="text-red-500">*</span></label>
                        <input v-model="form.room_number" type="text" class="input" placeholder="101" />
                        <p v-if="form.errors.room_number" class="text-xs text-red-500 mt-1">{{ form.errors.room_number }}</p>
                    </div>
                    <div>
                        <label class="label">Room Type <span class="text-red-500">*</span></label>
                        <select v-model="form.room_type_id" class="input">
                            <option value="">— Select Type —</option>
                            <option v-for="rt in roomTypes" :key="rt.id" :value="rt.id">{{ rt.name }}</option>
                        </select>
                        <p v-if="form.errors.room_type_id" class="text-xs text-red-500 mt-1">{{ form.errors.room_type_id }}</p>
                    </div>
                    <div>
                        <label class="label">Floor</label>
                        <select v-model="form.floor" class="input">
                            <option value="">— Select Floor —</option>
                            <option v-for="f in floors" :key="f.value" :value="f.value">{{ f.label }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="label">Status</label>
                        <select v-model="form.status" class="input">
                            <option value="available">Available</option>
                            <option value="occupied">Occupied</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="out_of_order">Out of Order</option>
                        </select>
                    </div>
                    <div>
                        <label class="label">Building / Block</label>
                        <input v-model="form.building" type="text" class="input" placeholder="Main Block" />
                    </div>
                </div>

                <div>
                    <label class="label">Room Image</label>
                    <DropZone @change="onImageChange" hint="JPEG / PNG / WebP — max 5 MB" />
                </div>

                <div>
                    <label class="label">Notes</label>
                    <textarea v-model="form.notes" rows="2" class="input" placeholder="Internal notes about this room..."></textarea>
                </div>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input v-model="form.is_active" type="checkbox" class="w-4 h-4 rounded text-blue-600" />
                    <span class="text-sm text-gray-600">Active (visible in booking)</span>
                </label>

                <div class="flex justify-end gap-3 pt-2">
                    <Link :href="route('admin.rooms.index')"
                        class="px-5 py-2 text-sm border border-gray-300 rounded text-gray-700 hover:bg-gray-50">Cancel</Link>
                    <button type="submit" :disabled="form.processing"
                        class="px-6 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-60">
                        {{ form.processing ? 'Saving...' : 'Create Room' }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import DropZone from '@/Components/Admin/Shared/DropZone.vue';

defineProps({ roomTypes: Array });

const floors = [
    { value: 'Ground Floor', label: 'Ground Floor' },
    { value: '1st Floor',    label: '1st Floor' },
    { value: '2nd Floor',    label: '2nd Floor' },
    { value: '3rd Floor',    label: '3rd Floor' },
    { value: '4th Floor',    label: '4th Floor' },
    { value: '5th Floor',    label: '5th Floor' },
    { value: '6th Floor',    label: '6th Floor' },
    { value: '7th Floor',    label: '7th Floor' },
    { value: '8th Floor',    label: '8th Floor' },
    { value: '9th Floor',    label: '9th Floor' },
    { value: '10th Floor',   label: '10th Floor' },
];

const form = useForm({
    room_type_id: '',
    room_number:  '',
    floor:        '',
    building:     '',
    image:        null,
    status:       'available',
    notes:        '',
    is_active:    true,
});

function onImageChange(file) {
    form.image = file;
}

function submit() {
    form.post(route('admin.rooms.store'), { forceFormData: true });
}
</script>

<style scoped>
.label { @apply block text-sm font-medium text-gray-700 mb-1.5; }
.input { @apply w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors bg-white; }
select.input { @apply cursor-pointer; }
textarea.input { @apply resize-none; }
</style>
