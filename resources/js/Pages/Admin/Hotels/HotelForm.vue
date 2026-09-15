<template>
    <form @submit.prevent="$emit('submit')" class="space-y-6">

        <!-- Basic Information -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Basic Information</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="label">Hotel Name <span class="text-red-500">*</span></label>
                    <input v-model="form.name" type="text" class="input" placeholder="Hotel Beach Way" />
                    <p v-if="form.errors.name" class="text-xs text-red-500 mt-1">{{ form.errors.name }}</p>
                </div>
                <div>
                    <label class="label">Hotel Category</label>
                    <input v-model="form.category" type="text" class="input" placeholder="e.g. 4 Star, Deluxe, Budget" />
                    <p class="text-xs text-gray-400 mt-1">No fixed classification yet — enter whatever the client uses today.</p>
                </div>
                <div>
                    <label class="label">Total Rooms</label>
                    <input v-model.number="form.total_rooms" type="number" min="0" class="input" placeholder="58" />
                </div>
                <div>
                    <label class="label">Mobile <span class="text-red-500">*</span></label>
                    <input v-model="form.mobile" type="text" class="input" placeholder="+880 1XXX-XXXXXX" />
                    <p v-if="form.errors.mobile" class="text-xs text-red-500 mt-1">{{ form.errors.mobile }}</p>
                </div>
                <div>
                    <label class="label">Email</label>
                    <input v-model="form.email" type="email" class="input" placeholder="info@hotel.com" />
                </div>
                <div class="col-span-2">
                    <label class="label">Address <span class="text-red-500">*</span></label>
                    <textarea v-model="form.address" rows="2" class="input" placeholder="Full street address"></textarea>
                    <p v-if="form.errors.address" class="text-xs text-red-500 mt-1">{{ form.errors.address }}</p>
                </div>
            </div>
        </section>

        <!-- Public Website -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Public Website</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Website URL</label>
                    <p class="input bg-gray-50 text-gray-500 select-all">
                        /hotel/{{ existingSlug || previewSlug || '…' }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">
                        {{ existingSlug ? 'This hotel\'s live site.' : 'Generated automatically from the hotel name once saved.' }}
                    </p>
                </div>
                <div>
                    <label class="label">Template</label>
                    <select v-model="form.template" class="input">
                        <option value="default">Default</option>
                    </select>
                    <p class="text-xs text-gray-400 mt-1">More themes are coming — every hotel uses Default for now.</p>
                </div>
            </div>
        </section>

        <!-- Legal / Registration -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Legal &amp; Registration</h2>
            <p class="text-xs text-gray-500">
                Leave blank if not on hand yet — these can be filled in later once the paperwork is available.
            </p>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Trade License No.</label>
                    <input v-model="form.trade_license_no" type="text" class="input" />
                    <p v-if="form.errors.trade_license_no" class="text-xs text-red-500 mt-1">{{ form.errors.trade_license_no }}</p>
                </div>
                <div>
                    <label class="label">BIN No.</label>
                    <input v-model="form.bin_no" type="text" class="input" />
                    <p v-if="form.errors.bin_no" class="text-xs text-red-500 mt-1">{{ form.errors.bin_no }}</p>
                </div>
                <div>
                    <label class="label">TIN No.</label>
                    <input v-model="form.tin_no" type="text" class="input" />
                    <p v-if="form.errors.tin_no" class="text-xs text-red-500 mt-1">{{ form.errors.tin_no }}</p>
                </div>
                <div>
                    <label class="label">Owner Name</label>
                    <input v-model="form.owner_name" type="text" class="input" />
                </div>
                <div>
                    <label class="label">Owner NID No.</label>
                    <input v-model="form.owner_nid" type="text" class="input" />
                </div>
            </div>
        </section>

        <!-- Location / Jurisdiction -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Location</h2>
            <p class="text-xs text-gray-500">
                Determines which government offices (DC / UNO / Police) will see this hotel.
            </p>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="label">District <span class="text-red-500">*</span></label>
                    <select v-model="form.district_id" class="input">
                        <option :value="null">— Select District —</option>
                        <option v-for="d in districts" :key="d.id" :value="d.id">{{ d.name }}</option>
                    </select>
                    <p v-if="form.errors.district_id" class="text-xs text-red-500 mt-1">{{ form.errors.district_id }}</p>
                </div>
                <div>
                    <label class="label">Upazila</label>
                    <select v-model="form.upazila_id" class="input" :disabled="!form.district_id">
                        <option :value="null">— None / Metro Area —</option>
                        <option v-for="u in filteredUpazilas" :key="u.id" :value="u.id">{{ u.name }}</option>
                    </select>
                </div>
                <div>
                    <label class="label">Police Station <span class="text-red-500">*</span></label>
                    <select v-model="form.police_station_id" class="input" :disabled="!form.district_id">
                        <option :value="null">— Select Police Station —</option>
                        <option v-for="ps in filteredPoliceStations" :key="ps.id" :value="ps.id">{{ ps.name }}</option>
                    </select>
                    <p v-if="form.errors.police_station_id" class="text-xs text-red-500 mt-1">{{ form.errors.police_station_id }}</p>
                </div>
            </div>
        </section>

        <!-- Hotel Photo (logo) -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Hotel Photo</h2>
            <div class="max-w-sm">
                <DropZone
                    :existing-preview="existingLogo ? `/storage/${existingLogo}` : null"
                    @change="(f) => (form.logo = f)"
                    hint="JPEG/PNG/WebP, max 5MB"
                    preview-class="w-full h-40 object-cover"
                />
            </div>
        </section>

        <!-- Hotel Documents -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Hotel Documents</h2>
            <div class="grid grid-cols-2 gap-4">
                <div v-for="doc in singleDocFields" :key="doc.field">
                    <label class="label">{{ doc.label }}</label>
                    <DocumentSlot
                        :doc="existingDocByCategory(doc.category)"
                        @change="f => (form[doc.field] = f)"
                        @remove="$emit('delete-document', existingDocByCategory(doc.category).id)"
                    />
                    <p v-if="form.errors[doc.field]" class="text-xs text-red-500 mt-1">{{ form.errors[doc.field] }}</p>
                </div>
            </div>
        </section>

        <!-- Hotel Gallery Photos -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Hotel Gallery Photos</h2>
            <div v-if="existingPhotos.length" class="grid grid-cols-4 gap-3">
                <div v-for="photo in existingPhotos" :key="photo.id" class="relative group">
                    <img :src="route('admin.documents.show', photo.id)" class="w-full h-24 object-cover rounded-lg border border-gray-200" />
                    <button type="button"
                        class="absolute top-1 right-1 w-5 h-5 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center text-xs"
                        @click="$emit('delete-document', photo.id)">✕</button>
                </div>
            </div>
            <DropZone :multiple="true" hint="JPEG/PNG/WebP, max 5MB each"
                @change="(files) => (form.photos = files)" />
        </section>

        <!-- Submit -->
        <div class="flex justify-end gap-3">
            <Link :href="route('admin.hotels.index')"
                class="px-5 py-2 text-sm border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                Cancel
            </Link>
            <button type="submit" :disabled="form.processing"
                class="px-6 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-60">
                {{ form.processing ? 'Saving...' : 'Save Hotel' }}
            </button>
        </div>
    </form>
</template>

<script setup>
import { computed, watch } from 'vue';
import { Link } from '@inertiajs/vue3';
import DropZone from '@/Components/Admin/Shared/DropZone.vue';
import DocumentSlot from '@/Components/Admin/Shared/DocumentSlot.vue';

const props = defineProps({
    form:            Object,
    districts:       { type: Array, default: () => [] },
    upazilas:        { type: Array, default: () => [] },
    policeStations:  { type: Array, default: () => [] },
    existingLogo:    { type: String, default: null },
    existingDocuments: { type: Array, default: () => [] },
    existingSlug:    { type: String, default: null },
});

/** Client-side preview only — the real slug is generated/uniquified server-side. */
const previewSlug = computed(() =>
    (props.form.name || '')
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '')
);

defineEmits(['submit', 'delete-document']);

const singleDocFields = [
    { field: 'trade_license_doc', category: 'trade_license',   label: 'Trade License Copy' },
    { field: 'bin_doc',           category: 'bin_certificate', label: 'BIN Certificate' },
    { field: 'tin_doc',           category: 'tin_certificate', label: 'TIN Certificate' },
    { field: 'owner_nid_doc',     category: 'owner_nid_copy',  label: 'Owner NID Copy' },
];

function existingDocByCategory(category) {
    return props.existingDocuments.find(d => d.category === category) ?? null;
}

const existingPhotos = computed(() => props.existingDocuments.filter(d => d.category === 'hotel_photo'));

const filteredUpazilas = computed(() =>
    props.upazilas.filter(u => u.district_id === props.form.district_id)
);

const filteredPoliceStations = computed(() =>
    props.policeStations.filter(ps => {
        if (ps.district_id !== props.form.district_id) return false;
        if (!props.form.upazila_id) return true;
        return ps.upazila_id === props.form.upazila_id || ps.upazila_id === null;
    })
);

watch(() => props.form.district_id, () => {
    props.form.upazila_id = null;
    props.form.police_station_id = null;
});

watch(() => props.form.upazila_id, () => {
    props.form.police_station_id = null;
});
</script>

<style scoped>
.label { @apply block text-sm font-medium text-gray-700 mb-1.5; }
.input { @apply w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors bg-white; }
select.input { @apply cursor-pointer; }
textarea.input { @apply resize-none; }
</style>
