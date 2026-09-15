<template>
    <AdminLayout title="Edit Hotel">
        <div class="max-w-4xl space-y-6">
            <div class="flex items-center gap-4">
                <Link :href="route('admin.hotels.index')" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><polyline points="15 18 9 12 15 6"/></svg>
                </Link>
                <h1 class="text-lg font-semibold text-gray-800">Edit Hotel — {{ hotel.name }}</h1>
            </div>

            <HotelForm
                :form="form"
                :districts="districts"
                :upazilas="upazilas"
                :police-stations="policeStations"
                :existing-logo="hotel.logo"
                :existing-documents="hotel.documents"
                :existing-slug="hotel.slug"
                @submit="submit"
                @delete-document="deleteDocument"
            />
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link, useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import HotelForm from './HotelForm.vue';

const props = defineProps({
    hotel: Object,
    districts: Array,
    upazilas: Array,
    policeStations: Array,
});

const form = useForm({
    name: props.hotel.name,
    template: props.hotel.template ?? 'default',
    category: props.hotel.category ?? '',
    total_rooms: props.hotel.total_rooms,
    trade_license_no: props.hotel.trade_license_no ?? '',
    bin_no: props.hotel.bin_no ?? '',
    tin_no: props.hotel.tin_no ?? '',
    owner_name: props.hotel.owner_name ?? '',
    owner_nid: props.hotel.owner_nid ?? '',
    mobile: props.hotel.mobile,
    email: props.hotel.email ?? '',
    address: props.hotel.address,
    district_id: props.hotel.district_id,
    upazila_id: props.hotel.upazila_id,
    police_station_id: props.hotel.police_station_id,
    logo: null,
    trade_license_doc: null,
    bin_doc: null,
    tin_doc: null,
    owner_nid_doc: null,
    photos: [],
});

function submit() {
    form.post(route('admin.hotels.update', props.hotel.id), { forceFormData: true });
}

function deleteDocument(documentId) {
    router.delete(route('admin.hotels.documents.delete', documentId), { preserveScroll: true });
}
</script>
