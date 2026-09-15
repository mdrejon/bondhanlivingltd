<template>
    <AdminLayout :title="`Edit Guest — ${customer.name}`">
        <div class="max-w-4xl space-y-6">
            <div class="flex items-center gap-4">
                <Link :href="route('admin.customers.show', customer.id)" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><polyline points="15 18 9 12 15 6"/></svg>
                </Link>
                <h1 class="text-lg font-semibold text-gray-800">Guest Registration — {{ customer.name }}</h1>
            </div>

            <form @submit.prevent="submit" class="space-y-6">

                <!-- Personal Details -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Personal Details</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Full Name <span class="text-red-500">*</span></label>
                            <input v-model="form.name" type="text" class="input" />
                            <p v-if="form.errors.name" class="text-xs text-red-500 mt-1">{{ form.errors.name }}</p>
                        </div>
                        <div>
                            <label class="label">Mobile <span class="text-red-500">*</span></label>
                            <input v-model="form.phone" type="text" class="input" />
                            <p v-if="form.errors.phone" class="text-xs text-red-500 mt-1">{{ form.errors.phone }}</p>
                        </div>
                        <div>
                            <label class="label">Father's Name</label>
                            <input v-model="form.father_name" type="text" class="input" />
                        </div>
                        <div>
                            <label class="label">Mother's Name</label>
                            <input v-model="form.mother_name" type="text" class="input" />
                        </div>
                        <div>
                            <label class="label">Gender</label>
                            <select v-model="form.gender" class="input">
                                <option :value="null">— Select —</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="label">Date of Birth</label>
                            <input v-model="form.date_of_birth" type="date" class="input" />
                        </div>
                        <div>
                            <label class="label">Nationality</label>
                            <input v-model="form.nationality" type="text" class="input" placeholder="Bangladeshi" />
                        </div>
                        <div>
                            <label class="label">Occupation</label>
                            <input v-model="form.occupation" type="text" class="input" />
                        </div>
                        <div>
                            <label class="label">Email</label>
                            <input v-model="form.email" type="email" class="input" />
                        </div>
                        <div>
                            <label class="label">Emergency Contact</label>
                            <input v-model="form.emergency_contact" type="text" class="input" />
                        </div>
                        <div class="col-span-2">
                            <label class="label">Present Address</label>
                            <textarea v-model="form.present_address" rows="2" class="input"></textarea>
                        </div>
                        <div class="col-span-2">
                            <label class="label">Permanent Address</label>
                            <textarea v-model="form.permanent_address" rows="2" class="input"></textarea>
                        </div>
                        <div>
                            <label class="label">District</label>
                            <select v-model="form.district_id" class="input">
                                <option :value="null">— Select District —</option>
                                <option v-for="d in districts" :key="d.id" :value="d.id">{{ d.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="label">Upazila</label>
                            <select v-model="form.upazila_id" class="input" :disabled="!form.district_id">
                                <option :value="null">— None —</option>
                                <option v-for="u in filteredUpazilas" :key="u.id" :value="u.id">{{ u.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="label">Police Station</label>
                            <select v-model="form.police_station_id" class="input" :disabled="!form.district_id">
                                <option :value="null">— Select —</option>
                                <option v-for="ps in filteredPoliceStations" :key="ps.id" :value="ps.id">{{ ps.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="label">Post Code</label>
                            <input v-model="form.post_code" type="text" class="input" />
                        </div>
                    </div>
                </section>

                <!-- Identity Information -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Identity Information</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Primary Document Type</label>
                            <select v-model="form.document_type" class="input">
                                <option value="nid">NID</option>
                                <option value="passport">Passport</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div></div>
                        <div>
                            <label class="label">NID Number</label>
                            <input v-model="form.nid_number" type="text" class="input" />
                        </div>
                        <div>
                            <label class="label">Birth Certificate Number</label>
                            <input v-model="form.birth_certificate_number" type="text" class="input" />
                        </div>
                        <div>
                            <label class="label">Passport Number</label>
                            <input v-model="form.passport_number" type="text" class="input" />
                        </div>
                        <div>
                            <label class="label">Driving License Number</label>
                            <input v-model="form.driving_license_number" type="text" class="input" />
                        </div>
                        <div>
                            <label class="label">NID — Front</label>
                            <DocumentSlot :doc="existingDoc('nid_front')" @change="f => form.nid_front_doc = f" @remove="() => removeDoc('nid_front')" />
                        </div>
                        <div>
                            <label class="label">NID — Back</label>
                            <DocumentSlot :doc="existingDoc('nid_back')" @change="f => form.nid_back_doc = f" @remove="() => removeDoc('nid_back')" />
                        </div>
                        <div>
                            <label class="label">Passport Scan</label>
                            <DocumentSlot :doc="existingDoc('passport_scan')" @change="f => form.passport_doc = f" @remove="() => removeDoc('passport_scan')" />
                        </div>
                    </div>
                </section>

                <!-- Foreign Guest -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <div class="flex items-center gap-3 border-b pb-2">
                        <input v-model="form.is_foreign_guest" type="checkbox" id="is_foreign_guest" class="w-4 h-4 rounded text-blue-600" />
                        <label for="is_foreign_guest" class="text-sm font-semibold text-gray-700">This guest is a foreign national</label>
                    </div>
                    <div v-if="form.is_foreign_guest" class="grid grid-cols-2 gap-4">
                        <div class="col-span-2 text-xs text-gray-500 -mt-2">
                            Country is captured via Nationality above. Passport number is captured under Identity Information.
                        </div>
                        <div>
                            <label class="label">Visa Number <span class="text-red-500">*</span></label>
                            <input v-model="form.visa_number" type="text" class="input" />
                            <p v-if="form.errors.visa_number" class="text-xs text-red-500 mt-1">{{ form.errors.visa_number }}</p>
                        </div>
                        <div>
                            <label class="label">Date of Arrival in Bangladesh <span class="text-red-500">*</span></label>
                            <input v-model="form.arrival_date_bd" type="date" class="input" />
                            <p v-if="form.errors.arrival_date_bd" class="text-xs text-red-500 mt-1">{{ form.errors.arrival_date_bd }}</p>
                        </div>
                        <div>
                            <label class="label">Visa Copy</label>
                            <DocumentSlot :doc="existingDoc('visa')" @change="f => form.visa_doc = f" @remove="() => removeDoc('visa')" />
                        </div>
                    </div>
                </section>

                <!-- Marriage Information -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <div class="flex items-center gap-3 border-b pb-2">
                        <input v-model="form.is_couple" type="checkbox" id="is_couple" class="w-4 h-4 rounded text-blue-600" />
                        <label for="is_couple" class="text-sm font-semibold text-gray-700">Checking in as a couple (husband &amp; wife)</label>
                    </div>
                    <div v-if="form.is_couple" class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Spouse Name <span class="text-red-500">*</span></label>
                            <input v-model="form.spouse_name" type="text" class="input" placeholder="Name of the accompanying husband/wife" />
                            <p v-if="form.errors.spouse_name" class="text-xs text-red-500 mt-1">{{ form.errors.spouse_name }}</p>
                        </div>
                        <div>
                            <label class="label">Marriage Date</label>
                            <input v-model="form.marriage_date" type="date" class="input" />
                        </div>
                        <div>
                            <label class="label">Nikahnama / Marriage Certificate</label>
                            <DocumentSlot :doc="existingDoc('marriage_certificate')" @change="f => form.marriage_cert_doc = f" @remove="() => removeDoc('marriage_certificate')" />
                        </div>
                    </div>
                </section>

                <!-- Photo -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Guest Photo</h2>
                    <div class="flex gap-2 text-sm">
                        <button type="button" @click="photoMode = 'upload'"
                            :class="photoMode === 'upload' ? 'bg-blue-600 text-white' : 'border border-gray-300 text-gray-600'"
                            class="px-3 py-1.5 rounded">Upload Photo</button>
                        <button type="button" @click="photoMode = 'webcam'"
                            :class="photoMode === 'webcam' ? 'bg-blue-600 text-white' : 'border border-gray-300 text-gray-600'"
                            class="px-3 py-1.5 rounded">Use Webcam</button>
                    </div>
                    <div class="max-w-sm">
                        <DropZone v-if="photoMode === 'upload'"
                            :existing-preview="existingDoc('guest_photo') ? route('admin.documents.show', existingDoc('guest_photo').id) : null"
                            @change="f => (form.guest_photo_doc = f)" hint="JPEG/PNG/WebP, max 5MB" preview-class="w-full h-48 object-cover" />
                        <WebcamCapture v-else @capture="f => (form.guest_photo_doc = f)" @clear="() => (form.guest_photo_doc = null)" />
                    </div>
                </section>

                <!-- Other Documents -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Other Documents</h2>
                    <div v-if="otherDocs.length" class="space-y-2">
                        <div v-for="doc in otherDocs" :key="doc.id" class="flex items-center justify-between text-xs bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">
                            <a :href="route('admin.documents.show', doc.id)" target="_blank" class="text-blue-600 hover:underline truncate">{{ doc.original_filename }}</a>
                            <button type="button" class="text-red-400 hover:text-red-600 ml-2" @click="$emit('delete-document', doc.id)">✕</button>
                        </div>
                    </div>
                    <DropZone :multiple="true" hint="Any supporting document" @change="files => (form.other_documents = files)" />
                </section>

                <!-- Police Flag (police-scoped roles / super admin only) -->
                <section v-if="canManageFlag" class="bg-white rounded-lg shadow-sm p-6 space-y-4 border-2 border-orange-200">
                    <h2 class="text-sm font-semibold text-orange-700 border-b pb-2">Police Use Only</h2>
                    <div class="flex items-center gap-3">
                        <input v-model="form.is_flagged" type="checkbox" id="is_flagged" class="w-4 h-4 rounded text-orange-600" />
                        <label for="is_flagged" class="text-sm text-gray-700 font-medium">Flag as suspicious person</label>
                    </div>
                    <div v-if="form.is_flagged">
                        <label class="label">Note</label>
                        <textarea v-model="form.flagged_note" rows="2" class="input"></textarea>
                    </div>
                </section>

                <!-- Submit -->
                <div class="flex justify-end gap-3">
                    <Link :href="route('admin.customers.show', customer.id)"
                        class="px-5 py-2 text-sm border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                        Cancel
                    </Link>
                    <button type="submit" :disabled="form.processing"
                        class="px-6 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-60">
                        {{ form.processing ? 'Saving...' : 'Save Guest Record' }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { computed, ref } from 'vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import DropZone from '@/Components/Admin/Shared/DropZone.vue';
import WebcamCapture from '@/Components/Admin/Shared/WebcamCapture.vue';
import DocumentSlot from '@/Components/Admin/Shared/DocumentSlot.vue';

const props = defineProps({
    customer: Object,
    districts: Array,
    upazilas: Array,
    policeStations: Array,
    canManageFlag: Boolean,
});

const photoMode = ref('upload');

const form = useForm({
    name: props.customer.name,
    phone: props.customer.phone,
    father_name: props.customer.father_name ?? '',
    mother_name: props.customer.mother_name ?? '',
    gender: props.customer.gender,
    date_of_birth: props.customer.date_of_birth,
    nationality: props.customer.nationality ?? '',
    occupation: props.customer.occupation ?? '',
    email: props.customer.email ?? '',
    emergency_contact: props.customer.emergency_contact ?? '',
    present_address: props.customer.present_address ?? '',
    permanent_address: props.customer.permanent_address ?? '',
    district_id: props.customer.district_id,
    upazila_id: props.customer.upazila_id,
    police_station_id: props.customer.police_station_id,
    post_code: props.customer.post_code ?? '',

    document_type: props.customer.document_type ?? 'nid',
    nid_number: props.customer.nid_number ?? '',
    birth_certificate_number: props.customer.birth_certificate_number ?? '',
    passport_number: props.customer.passport_number ?? '',
    driving_license_number: props.customer.driving_license_number ?? '',

    is_foreign_guest: props.customer.is_foreign_guest ?? false,
    visa_number: props.customer.visa_number ?? '',
    arrival_date_bd: props.customer.arrival_date_bd,

    is_couple: props.customer.is_couple ?? false,
    spouse_name: props.customer.spouse_name ?? '',
    marriage_date: props.customer.marriage_date,

    is_flagged: props.customer.is_flagged ?? false,
    flagged_note: props.customer.flagged_note ?? '',

    nid_front_doc: null,
    nid_back_doc: null,
    passport_doc: null,
    visa_doc: null,
    marriage_cert_doc: null,
    guest_photo_doc: null,
    other_documents: [],
});

const filteredUpazilas = computed(() => props.upazilas.filter(u => u.district_id === form.district_id));
const filteredPoliceStations = computed(() => props.policeStations.filter(ps => {
    if (ps.district_id !== form.district_id) return false;
    if (!form.upazila_id) return true;
    return ps.upazila_id === form.upazila_id || ps.upazila_id === null;
}));

const otherDocs = computed(() => (props.customer.documents || []).filter(d => d.category === 'other'));

function existingDoc(category) {
    return (props.customer.documents || []).find(d => d.category === category) ?? null;
}

function removeDoc(category) {
    const doc = existingDoc(category);
    if (doc) router.delete(route('admin.customers.documents.delete', doc.id), { preserveScroll: true });
}

function submit() {
    form.post(route('admin.customers.update', props.customer.id), { forceFormData: true });
}
</script>

<style scoped>
.label { @apply block text-sm font-medium text-gray-700 mb-1.5; }
.input { @apply w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors bg-white; }
select.input { @apply cursor-pointer; }
textarea.input { @apply resize-none; }
</style>
