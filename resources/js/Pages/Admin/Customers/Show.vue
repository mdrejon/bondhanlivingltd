<template>
    <AdminLayout>
        <div class="max-w-4xl space-y-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Link :href="route('admin.customers.index')" class="text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><polyline points="15 18 9 12 15 6"/></svg>
                    </Link>
                    <h1 class="text-lg font-semibold text-gray-800">{{ customer.name }}</h1>
                    <span v-if="customer.is_flagged" class="px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                        ⚑ Flagged
                    </span>
                </div>
            </div>

            <!-- Profile Card -->
            <section class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-start gap-4 mb-4">
                    <div v-if="guestPhotoDoc" class="flex-shrink-0">
                        <img :src="route('admin.documents.show', guestPhotoDoc.id)" alt="Guest Photo"
                            class="w-20 h-20 rounded-lg object-cover border-2 border-blue-100 shadow-sm" />
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-4">Customer Profile</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                            <div>
                                <p class="text-xs text-gray-400">Phone</p>
                                <p class="font-medium text-gray-800">{{ customer.phone }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Email</p>
                                <p class="text-gray-700">{{ customer.email || '—' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Nationality</p>
                                <p class="text-gray-700">{{ customer.nationality || '—' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Address</p>
                                <p class="text-gray-700">{{ customer.address || '—' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Document Type</p>
                                <p class="text-gray-700 capitalize">{{ customer.document_type }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">{{ customer.document_type === 'passport' ? 'Passport' : 'NID' }}</p>
                                <p class="font-mono text-gray-700">
                                    {{ customer.document_type === 'passport' ? customer.passport_number : customer.nid_number || '—' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Member Since</p>
                                <p class="text-gray-700">{{ formatDate(customer.created_at) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Total Bookings</p>
                                <p class="font-bold text-blue-700 text-lg">{{ customer.bookings?.length ?? 0 }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Total Spent</p>
                                <p class="font-bold text-emerald-700 text-lg">
                                    ৳{{ totalSpent.toLocaleString() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Full KYC Details -->
            <section class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-4">Guest Registration Details</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                    <div v-for="f in kycFields" :key="f.label">
                        <p class="text-xs text-gray-400">{{ f.label }}</p>
                        <p class="text-gray-700">{{ f.value || '—' }}</p>
                    </div>
                </div>
                <div v-if="customer.is_foreign_guest" class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Foreign Guest</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                        <div><p class="text-xs text-gray-400">Visa Number</p><p class="text-gray-700">{{ customer.visa_number || '—' }}</p></div>
                        <div><p class="text-xs text-gray-400">Arrival in Bangladesh</p><p class="text-gray-700">{{ formatDate(customer.arrival_date_bd) }}</p></div>
                    </div>
                </div>
                <div v-if="customer.is_couple" class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Marriage / Companion</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                        <div><p class="text-xs text-gray-400">Spouse Name</p><p class="text-gray-700">{{ customer.spouse_name || '—' }}</p></div>
                        <div><p class="text-xs text-gray-400">Marriage Date</p><p class="text-gray-700">{{ formatDate(customer.marriage_date) }}</p></div>
                    </div>
                </div>
                <div v-if="canManageFlag && customer.is_flagged" class="mt-4 pt-4 border-t border-red-100 bg-red-50 -mx-6 -mb-6 px-6 pb-6 rounded-b-lg">
                    <p class="text-xs font-semibold text-red-600 uppercase tracking-wide mb-2">⚑ Police Flag Note</p>
                    <p class="text-sm text-red-700">{{ customer.flagged_note || 'No note provided.' }}</p>
                </div>
            </section>

            <!-- Documents -->
            <section class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Uploaded Documents ({{ customer.documents?.length || 0 }})</h2>
                </div>
                <div v-if="!customer.documents || customer.documents.length === 0" class="text-sm text-gray-400 italic">
                    No documents uploaded yet.
                </div>
                <div v-else class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div v-for="doc in customer.documents" :key="doc.id"
                        class="p-3 border border-gray-200 rounded-lg flex items-center justify-between bg-gray-50 hover:bg-white transition-colors">
                        <div class="flex items-center gap-3 overflow-hidden">
                            <div class="w-10 h-10 rounded bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0 font-bold text-xs uppercase">
                                {{ isDocPdf(doc) ? 'PDF' : 'IMG' }}
                            </div>
                            <div class="min-w-0">
                                <span class="block font-medium text-xs text-gray-800 capitalize truncate">
                                    {{ formatDocCategory(doc.category) }}
                                </span>
                                <span class="block text-xs text-gray-400 truncate">{{ doc.original_filename }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5 ml-2 flex-shrink-0">
                            <button type="button" @click="openDocModal(doc)"
                                class="px-3 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700 font-medium">
                                Popup View
                            </button>
                            <a :href="route('admin.documents.show', doc.id)" target="_blank"
                                class="px-3 py-1 bg-white border border-blue-200 text-blue-600 rounded text-xs hover:bg-blue-50 font-medium"
                                title="Open in new tab">
                                ↗
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Popup View Modal -->
            <Teleport to="body">
                <div v-if="previewModalDoc" class="fixed inset-0 z-50 flex items-center justify-center bg-black/75 p-4" @click.self="previewModalDoc = null">
                    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-4xl w-full max-h-[92vh] flex flex-col border border-gray-100">
                        <div class="flex items-center justify-between px-6 py-4 border-b bg-gray-50">
                            <div class="flex items-center gap-3 truncate">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider" :class="isDocPdf(previewModalDoc) ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700'">
                                    {{ isDocPdf(previewModalDoc) ? 'PDF Document' : 'Image Preview' }}
                                </span>
                                <h3 class="font-semibold text-sm text-gray-800 truncate" :title="previewModalDoc.original_filename">{{ previewModalDoc.original_filename }}</h3>
                            </div>
                            <div class="flex items-center gap-3">
                                <a :href="route('admin.documents.show', previewModalDoc.id)" target="_blank" class="px-3 py-1.5 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors inline-flex items-center gap-1">
                                    Open in New Tab ↗
                                </a>
                                <button type="button" class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-600 flex items-center justify-center font-bold transition-colors" @click="previewModalDoc = null">
                                    ✕
                                </button>
                            </div>
                        </div>
                        <div class="flex-1 overflow-auto p-4 flex items-center justify-center bg-gray-900 min-h-[450px]">
                            <iframe v-if="isDocPdf(previewModalDoc)" :src="route('admin.documents.show', previewModalDoc.id)" class="w-full h-[72vh] rounded-lg border-0 bg-white"></iframe>
                            <img v-else :src="route('admin.documents.show', previewModalDoc.id)" alt="Document Preview" class="max-w-full max-h-[75vh] object-contain rounded-lg shadow-xl" />
                        </div>
                    </div>
                </div>
            </Teleport>

            <!-- Booking History -->
            <section class="bg-white rounded-lg shadow-sm overflow-x-auto">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Booking History</h2>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Reference</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Room</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Check In</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Check Out</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600">Nights</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600">Amount</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600">Status</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-if="!customer.bookings || customer.bookings.length === 0">
                            <td colspan="8" class="px-4 py-6 text-center text-gray-400 text-sm">No bookings yet.</td>
                        </tr>
                        <tr v-for="b in customer.bookings" :key="b.id" class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-mono text-xs text-gray-700">{{ b.booking_reference }}</td>
                            <td class="px-4 py-3 text-gray-600 text-xs">
                                {{ roomNumberSummary(b) }}
                                <span class="text-gray-400">{{ roomTypeSummary(b) !== '—' ? '/ ' + roomTypeSummary(b) : '' }}</span>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-600">{{ formatDate(b.check_in_date) }}</td>
                            <td class="px-4 py-3 text-xs text-gray-600">{{ formatDate(b.check_out_date) }}</td>
                            <td class="px-4 py-3 text-center text-xs text-gray-600">{{ b.total_nights }}</td>
                            <td class="px-4 py-3 text-right text-xs font-medium text-gray-800">
                                ৳{{ Number(b.total_amount).toLocaleString() }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span :class="statusBadge(b.booking_status)" class="px-2 py-0.5 rounded-full text-xs font-medium">
                                    {{ statusLabel(b.booking_status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <Link :href="route('admin.room-bookings.show', b.id)"
                                    class="text-xs text-blue-600 hover:underline">View</Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </section>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import { roomTypeSummary, roomNumberSummary } from '@/Composables/bookingRoomsSummary';

const previewModalDoc = ref(null);

function openDocModal(doc) {
    previewModalDoc.value = doc;
}

function isDocPdf(doc) {
    if (!doc) return false;
    const name = (doc.original_filename || '').toLowerCase();
    return doc.category === 'pdf' || name.endsWith('.pdf');
}

const props = defineProps({
    customer: Object,
    canManageFlag: { type: Boolean, default: false },
});

const kycFields = computed(() => [
    { label: "Father's Name", value: props.customer.father_name },
    { label: "Mother's Name", value: props.customer.mother_name },
    { label: 'Gender', value: props.customer.gender },
    { label: 'Date of Birth', value: formatDate(props.customer.date_of_birth) === '—' ? null : formatDate(props.customer.date_of_birth) },
    { label: 'Occupation', value: props.customer.occupation },
    { label: 'Emergency Contact', value: props.customer.emergency_contact },
    { label: 'Present Address', value: props.customer.present_address },
    { label: 'Permanent Address', value: props.customer.permanent_address },
    { label: 'District', value: props.customer.district?.name },
    { label: 'Upazila', value: props.customer.upazila?.name },
    { label: 'Police Station', value: props.customer.police_station?.name },
    { label: 'Post Code', value: props.customer.post_code },
    { label: 'Birth Certificate No.', value: props.customer.birth_certificate_number },
    { label: 'Driving License No.', value: props.customer.driving_license_number },
]);

const totalSpent = computed(() =>
    (props.customer.bookings || [])
        .filter(b => b.booking_status !== 'cancelled')
        .reduce((sum, b) => sum + Number(b.total_amount), 0)
);

const guestPhotoDoc = computed(() => (props.customer.documents || []).find(d => d.category === 'guest_photo'));

function formatDocCategory(cat) {
    const labels = {
        nid_front: 'NID — Front',
        nid_back: 'NID — Back',
        passport_scan: 'Passport Scan',
        visa: 'Visa Copy',
        marriage_certificate: 'Marriage Certificate / Nikahnama',
        guest_photo: 'Guest Photo',
        other: 'Other Document',
    };
    return labels[cat] ?? cat.replaceAll('_', ' ');
}

function formatDate(d) {
    if (!d) return '—';
    return new Date(d).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
}

function statusLabel(s) {
    return { pending: 'Pending', confirmed: 'Confirmed', checked_in: 'Checked In', checked_out: 'Checked Out', cancelled: 'Cancelled' }[s] ?? s;
}

function statusBadge(s) {
    return {
        pending:     'bg-yellow-100 text-yellow-700',
        confirmed:   'bg-blue-100 text-blue-700',
        checked_in:  'bg-green-100 text-green-700',
        checked_out: 'bg-gray-100 text-gray-600',
        cancelled:   'bg-red-100 text-red-600',
    }[s] ?? 'bg-gray-100 text-gray-600';
}
</script>
