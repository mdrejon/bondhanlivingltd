<template>
    <AdminLayout>
        <div class="max-w-4xl space-y-6">
            <div class="flex items-center gap-4">
                <Link :href="route('admin.room-types.index')" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><polyline points="15 18 9 12 15 6"/></svg>
                </Link>
                <h1 class="text-lg font-semibold text-gray-800">Add New Room Type</h1>
            </div>

            <RoomTypeForm :form="form" :all-amenities="allAmenities" @submit="submit" />
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import RoomTypeForm from './RoomTypeForm.vue';

const props = defineProps({
    allAmenities: { type: Array, default: () => [] },
});

const form = useForm({
    name: '',
    short_desc: '',
    description: '',
    price: '',
    price_usd: '',
    discount_type_bdt: '',
    discount_value_bdt: '',
    discount_type_usd: '',
    discount_value_usd: '',
    offer_expires_at: '',
    price_unit: '/Night',
    check_in_time: '12:30',
    check_out_time: '11:30',
    max_adults: 2,
    max_children: 0,
    bed_type: '',
    amenity_ids: [],
    amenities: [],
    features: [],
    room_rules: [],
    gallery_images: [],
    feature_image: null,
    rating: 5.0,
    is_featured: false,
    sort_order: 0,
    is_active: true,
    meta_title: '',
    meta_description: '',
    meta_keywords: '',
    og_image: null,
});

function submit() {
    form.post(route('admin.room-types.store'), {
        forceFormData: true,
    });
}
</script>
