<template>
    <AdminLayout>
        <div class="max-w-4xl space-y-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Link :href="route('admin.room-types.index')" class="text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><polyline points="15 18 9 12 15 6"/></svg>
                    </Link>
                    <h1 class="text-lg font-semibold text-gray-800">Edit Room Type: {{ roomType.name }}</h1>
                </div>
                <a :href="route('rooms.detail', { slug: roomType.slug })" target="_blank"
                    class="inline-flex items-center gap-1.5 text-sm text-blue-600 hover:text-blue-800 border border-blue-200 hover:border-blue-400 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    View Page
                </a>
            </div>

            <div v-if="$page.props.flash?.success"
                class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded">
                {{ $page.props.flash.success }}
            </div>

            <RoomTypeForm
                :form="form"
                :all-amenities="allAmenities"
                :existing-gallery="orderedGallery"
                :deleting-image="deletingImage"
                :existing-feature-image="currentFeatureImage"
                :existing-og-image="currentOgImage"
                @remove-image="removeImage"
                @remove-feature-image="removeFeatureImage"
                @remove-og-image="removeOgImage"
                @reorder="orderedGallery = $event"
                @submit="submit"
            />
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, watch } from 'vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import RoomTypeForm from './RoomTypeForm.vue';

const props = defineProps({
    roomType:           Object,
    allAmenities:       { type: Array, default: () => [] },
    selectedAmenityIds: { type: Array, default: () => [] },
});

const deletingImage       = ref(null);
const currentFeatureImage = ref(props.roomType.feature_image ?? null);
const currentOgImage      = ref(props.roomType.og_image ?? null);

// Ordered gallery — updated by reorder arrows or server deletions
const orderedGallery = ref([...(props.roomType.gallery_images || [])]);
watch(() => props.roomType.gallery_images, (serverList) => {
    const keep = new Set(serverList || []);
    orderedGallery.value = orderedGallery.value.filter(p => keep.has(p));
});

const rt = props.roomType;
const form = useForm({
    name:              rt.name,
    short_desc:        rt.short_desc ?? '',
    description:       rt.description ?? '',
    price:               rt.price,
    price_usd:           rt.price_usd ?? '',
    discount_type_bdt:   rt.discount_type_bdt ?? '',
    discount_value_bdt:  rt.discount_value_bdt ?? '',
    discount_type_usd:   rt.discount_type_usd ?? '',
    discount_value_usd:  rt.discount_value_usd ?? '',
    offer_expires_at:    rt.offer_expires_at ? rt.offer_expires_at.slice(0, 16) : '',
    price_unit:          rt.price_unit,
    check_in_time:     rt.check_in_time,
    check_out_time:    rt.check_out_time,
    max_adults:        rt.max_adults,
    max_children:      rt.max_children,
    bed_type:          rt.bed_type ?? '',
    amenity_ids:       [...props.selectedAmenityIds],
    amenities:         Array.isArray(rt.amenities) ? rt.amenities.map(a => ({ ...a })) : [],
    features:          Array.isArray(rt.features)   ? [...rt.features]                  : [],
    room_rules:        Array.isArray(rt.room_rules) ? [...rt.room_rules]                : [],
    gallery_images:    [],
    feature_image:     null,
    remove_feature_image: false,
    rating:            rt.rating,
    is_featured:       rt.is_featured,
    sort_order:        rt.sort_order,
    is_active:         rt.is_active,
    meta_title:        rt.meta_title ?? '',
    meta_description:  rt.meta_description ?? '',
    meta_keywords:     rt.meta_keywords ?? '',
    og_image:          null,
    remove_og_image:   false,
});

function removeImage(path) {
    if (!confirm('Remove this image from the server? This cannot be undone.')) return;
    deletingImage.value = path;
    router.delete(route('admin.room-types.gallery-image.delete', props.roomType.id), {
        data: { path },
        preserveState: true,
        preserveScroll: true,
        onFinish: () => { deletingImage.value = null; },
    });
}

function removeFeatureImage() {
    currentFeatureImage.value = null;
    form.remove_feature_image = true;
}

function removeOgImage() {
    currentOgImage.value = null;
    form.remove_og_image = true;
}

function submit() {
    // Use user-reordered gallery list to preserve custom sort order
    const existingGallery = orderedGallery.value;

    form.transform(data => {
        const fd = new FormData();
        Object.entries(data).forEach(([k, v]) => {
            if (k === 'gallery_images') {
                v.forEach(f => fd.append('gallery_images[]', f));
            } else if (k === 'amenity_ids') {
                v.forEach(id => fd.append('amenity_ids[]', id));
            } else if (k === 'amenities') {
                v.forEach((a, i) => {
                    fd.append(`amenities[${i}][icon_svg]`, a.icon_svg ?? '');
                    fd.append(`amenities[${i}][label]`, a.label ?? '');
                });
            } else if (k === 'features') {
                v.forEach((f, i) => fd.append(`features[${i}]`, f));
            } else if (k === 'room_rules') {
                v.forEach((r, i) => fd.append(`room_rules[${i}]`, r));
            } else if (k === 'feature_image' || k === 'og_image') {
                if (v instanceof File) fd.append(k, v);
            } else if (typeof v === 'boolean') {
                fd.append(k, v ? '1' : '0');
            } else if (v !== null && v !== undefined) {
                fd.append(k, v);
            }
        });
        existingGallery.forEach(p => fd.append('existing_gallery[]', p));
        fd.append('_method', 'POST');
        return fd;
    }).post(route('admin.room-types.update', props.roomType.id), {
        forceFormData: true,
    });
}
</script>
