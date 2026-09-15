<template>
    <AdminLayout title="Add Hotel">
        <div class="max-w-4xl space-y-6">
            <div class="flex items-center gap-4">
                <Link :href="route('admin.hotels.index')" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><polyline points="15 18 9 12 15 6"/></svg>
                </Link>
                <h1 class="text-lg font-semibold text-gray-800">Register New Hotel</h1>
            </div>

            <HotelForm
                :form="form"
                :districts="districts"
                :upazilas="upazilas"
                :police-stations="policeStations"
                @submit="submit"
            />
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import HotelForm from './HotelForm.vue';

defineProps({
    districts: Array,
    upazilas: Array,
    policeStations: Array,
});

const form = useForm({
    name: '',
    template: 'default',
    category: '',
    total_rooms: null,
    trade_license_no: '',
    bin_no: '',
    tin_no: '',
    owner_name: '',
    owner_nid: '',
    mobile: '',
    email: '',
    address: '',
    district_id: null,
    upazila_id: null,
    police_station_id: null,
    logo: null,
    trade_license_doc: null,
    bin_doc: null,
    tin_doc: null,
    owner_nid_doc: null,
    photos: [],
});

function submit() {
    form.post(route('admin.hotels.store'), { forceFormData: true });
}
</script>
