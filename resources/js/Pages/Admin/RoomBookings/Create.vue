<template>
    <AdminLayout>
        <div class="max-w-5xl space-y-6">
            <div class="flex items-center gap-4">
                <Link :href="route('admin.room-bookings.index')" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><polyline points="15 18 9 12 15 6"/></svg>
                </Link>
                <h1 class="text-lg font-semibold text-gray-800">New Room Booking</h1>
            </div>

            <form @submit.prevent="submit" class="space-y-6">

                <!-- ── Stay Details ── -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Stay Details</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                        <div>
                            <label class="label">Check-In Date <span class="text-red-500">*</span></label>
                            <input v-model="form.check_in_date" type="date" class="input" :min="today" />
                            <p v-if="form.errors.check_in_date" class="text-xs text-red-500 mt-1">{{ form.errors.check_in_date }}</p>
                        </div>
                        <div>
                            <label class="label">Check-Out Date <span class="text-red-500">*</span></label>
                            <input v-model="form.check_out_date" type="date" class="input" :min="form.check_in_date || today" />
                            <p v-if="form.errors.check_out_date" class="text-xs text-red-500 mt-1">{{ form.errors.check_out_date }}</p>
                        </div>
                        <div>
                            <label class="label">Booking Status</label>
                            <select v-model="form.booking_status" class="input">
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                            </select>
                        </div>
                        <div>
                            <label class="label">Currency</label>
                            <select v-model="form.currency" class="input">
                                <option value="BDT">BDT ৳</option>
                                <option value="USD">USD $</option>
                            </select>
                        </div>
                    </div>
                </section>

                <!-- ── Room Lines ── -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <div class="flex items-center justify-between border-b pb-2">
                        <h2 class="text-sm font-semibold text-gray-700">Rooms</h2>
                        <button type="button" @click="addLine" class="text-xs font-medium text-blue-600 hover:text-blue-800">+ Add Room</button>
                    </div>
                    <p v-if="form.errors.rooms" class="text-xs text-red-500">{{ form.errors.rooms }}</p>

                    <div v-for="(line, idx) in form.rooms" :key="idx"
                        class="rounded-lg border border-gray-200 p-4 space-y-3 relative">
                        <button v-if="form.rooms.length > 1" type="button" @click="removeLine(idx)"
                            class="absolute top-3 right-3 text-gray-300 hover:text-red-500" aria-label="Remove room">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pr-6">
                            <div>
                                <label class="label">Room Type <span class="text-red-500">*</span></label>
                                <select v-model="line.room_type_id" class="input" @change="onRoomTypeChange(idx)">
                                    <option value="">— Select —</option>
                                    <option v-for="rt in roomTypes" :key="rt.id" :value="rt.id">
                                        {{ roomTypeOptionLabel(rt) }}
                                    </option>
                                </select>
                                <p v-if="form.errors[`rooms.${idx}.room_type_id`]" class="text-xs text-red-500 mt-1">{{ form.errors[`rooms.${idx}.room_type_id`] }}</p>
                            </div>
                            <div>
                                <label class="label">
                                    Assign Room
                                    <span class="text-gray-400 font-normal">(optional)</span>
                                </label>
                                <RoomPickerSelect v-model="line.room_id"
                                    :rooms="availableRoomsByIndex[idx] ?? []"
                                    :disabled="!line.room_type_id"
                                    @update:modelValue="refreshAllAvailability" />
                                <p v-if="form.errors[`rooms.${idx}.room_id`]" class="text-xs text-red-500 mt-1">{{ form.errors[`rooms.${idx}.room_id`] }}</p>
                            </div>
                            <div>
                                <label class="label">Adults</label>
                                <input v-model.number="line.adults" type="number" min="1" max="20" class="input" />
                            </div>
                            <div>
                                <label class="label">Children</label>
                                <input v-model.number="line.children" type="number" min="0" max="20" class="input" />
                            </div>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pr-6">
                            <div>
                                <label class="label">Price / Night ({{ currencySymbol() }})</label>
                                <input v-model.number="line.price_per_night" type="number" min="0" step="0.01" class="input" />
                                <p v-if="form.errors[`rooms.${idx}.price_per_night`]" class="text-xs text-red-500 mt-1">{{ form.errors[`rooms.${idx}.price_per_night`] }}</p>
                                <p v-if="roomTypeDiscountInfo(typeById(line.room_type_id))" class="text-xs mt-1">
                                    <span class="text-gray-400 line-through">{{ currencySymbol() }}{{ formatAmount(roomTypeDiscountInfo(typeById(line.room_type_id)).original) }}</span>
                                    <span class="text-emerald-600 font-medium ml-1">{{ currencySymbol() }}{{ formatAmount(roomTypeDiscountInfo(typeById(line.room_type_id)).effective) }}</span>
                                    <span class="text-emerald-600"> ({{ roomTypeDiscountInfo(typeById(line.room_type_id)).percentOff }}% off)</span>
                                </p>
                            </div>
                            <div v-if="totalNights > 0" class="col-span-3 flex items-end">
                                <span class="text-xs text-gray-500">Line total: <strong class="text-emerald-700">{{ currencySymbol() }}{{ formatAmount(totalNights * (line.price_per_night || 0)) }}</strong> ({{ totalNights }} night{{ totalNights > 1 ? 's' : '' }})</span>
                            </div>
                        </div>
                    </div>

                    <!-- Nights + Amount Summary -->
                    <div v-if="totalNights > 0" class="mt-2 p-3 bg-blue-50 rounded-lg border border-blue-100 flex gap-6 text-sm">
                        <div><span class="text-gray-500">Nights:</span> <strong class="text-blue-700">{{ totalNights }}</strong></div>
                        <div><span class="text-gray-500">Rooms:</span> <strong class="text-blue-700">{{ form.rooms.length }}</strong></div>
                        <div><span class="text-gray-500">Guests:</span> <strong class="text-blue-700">{{ totalAdults }} adults, {{ totalChildren }} children</strong></div>
                        <div><span class="text-gray-500">Total:</span> <strong class="text-emerald-700 text-base">{{ currencySymbol() }}{{ formatAmount(totalAmount) }}</strong></div>
                    </div>
                </section>

                <!-- ── Customer & Guest Registration Details ── -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <div class="flex items-center justify-between border-b pb-2">
                        <h2 class="text-sm font-semibold text-gray-700">Customer &amp; Guest Details</h2>
                        <button type="button" @click="showFullKyc = !showFullKyc" class="text-xs font-medium text-blue-600 hover:text-blue-800">
                            {{ showFullKyc ? '− Hide Extended Details & Documents' : '+ Extended Guest Registration & Documents' }}
                        </button>
                    </div>

                    <!-- Phone Lookup -->
                    <div class="flex gap-3 items-end">
                        <div class="flex-1">
                            <label class="label">Phone Number <span class="text-red-500">*</span></label>
                            <input v-model="lookupPhone" type="text" class="input" placeholder="01XXXXXXXXX"
                                @input="onPhoneInput" />
                        </div>
                        <div v-if="lookupResult === 'found'" class="pb-2 text-xs text-green-600 font-medium">✓ Existing customer</div>
                        <div v-if="lookupResult === 'new'" class="pb-2 text-xs text-blue-600 font-medium">+ New customer</div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="label">Full Name <span class="text-red-500">*</span></label>
                            <input v-model="form.customer_name" type="text" class="input" placeholder="Customer name" />
                            <p v-if="form.errors.customer_name" class="text-xs text-red-500 mt-1">{{ form.errors.customer_name }}</p>
                        </div>
                        <div>
                            <label class="label">Email</label>
                            <input v-model="form.customer_email" type="email" class="input" placeholder="email@example.com" />
                        </div>
                        <div>
                            <label class="label">Nationality</label>
                            <input v-model="form.customer_nationality" type="text" class="input" placeholder="Bangladeshi" />
                        </div>
                        <div>
                            <label class="label">Address</label>
                            <input v-model="form.customer_address" type="text" class="input" placeholder="Dhaka, Bangladesh" />
                        </div>
                        <div>
                            <label class="label">Primary Document Type</label>
                            <select v-model="form.document_type" class="input">
                                <option value="nid">NID</option>
                                <option value="passport">Passport</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="label">{{ form.document_type === 'passport' ? 'Passport Number' : 'NID Number' }}</label>
                            <input v-model="form.nid_number" v-if="form.document_type !== 'passport'" type="text" class="input" placeholder="NID number" />
                            <input v-model="form.passport_number" v-else type="text" class="input" placeholder="Passport number" />
                        </div>
                    </div>

                    <!-- Extended KYC Details & Document Uploads -->
                    <div v-if="showFullKyc" class="pt-4 border-t border-gray-100 space-y-6">
                        <!-- Extended Personal Details -->
                        <div class="space-y-4">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Extended Personal Profile</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
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
                                    <label class="label">Occupation</label>
                                    <input v-model="form.occupation" type="text" class="input" />
                                </div>
                                <div>
                                    <label class="label">Emergency Contact</label>
                                    <input v-model="form.emergency_contact" type="text" class="input" />
                                </div>
                                <div>
                                    <label class="label">Present Address</label>
                                    <input v-model="form.present_address" type="text" class="input" />
                                </div>
                                <div>
                                    <label class="label">Permanent Address</label>
                                    <input v-model="form.permanent_address" type="text" class="input" />
                                </div>
                                <div>
                                    <label class="label">District</label>
                                    <select v-model="form.district_id" class="input">
                                        <option :value="null">— Select District —</option>
                                        <option v-for="d in (districts || [])" :key="d.id" :value="d.id">{{ d.name }}</option>
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
                                <div>
                                    <label class="label">Birth Certificate Number</label>
                                    <input v-model="form.birth_certificate_number" type="text" class="input" />
                                </div>
                                <div>
                                    <label class="label">Driving License Number</label>
                                    <input v-model="form.driving_license_number" type="text" class="input" />
                                </div>
                            </div>
                        </div>

                        <!-- Identity Document Scans -->
                        <div class="space-y-4 pt-4 border-t border-gray-100">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Identity Document Uploads</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="label">NID — Front</label>
                                    <DocumentSlot @change="f => form.nid_front_doc = f" />
                                </div>
                                <div>
                                    <label class="label">NID — Back</label>
                                    <DocumentSlot @change="f => form.nid_back_doc = f" />
                                </div>
                                <div>
                                    <label class="label">Passport Scan</label>
                                    <DocumentSlot @change="f => form.passport_doc = f" />
                                </div>
                            </div>
                        </div>

                        <!-- Foreign Guest Section -->
                        <div class="space-y-4 pt-4 border-t border-gray-100">
                            <div class="flex items-center gap-3">
                                <input v-model="form.is_foreign_guest" type="checkbox" id="is_foreign_guest" class="w-4 h-4 rounded text-blue-600" />
                                <label for="is_foreign_guest" class="text-sm font-semibold text-gray-700">This guest is a foreign national</label>
                            </div>
                            <div v-if="form.is_foreign_guest" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="label">Visa Number <span class="text-red-500">*</span></label>
                                    <input v-model="form.visa_number" type="text" class="input" />
                                </div>
                                <div>
                                    <label class="label">Date of Arrival in BD <span class="text-red-500">*</span></label>
                                    <input v-model="form.arrival_date_bd" type="date" class="input" />
                                </div>
                                <div>
                                    <label class="label">Visa Copy</label>
                                    <DocumentSlot @change="f => form.visa_doc = f" />
                                </div>
                            </div>
                        </div>

                        <!-- Marriage Section -->
                        <div class="space-y-4 pt-4 border-t border-gray-100">
                            <div class="flex items-center gap-3">
                                <input v-model="form.is_couple" type="checkbox" id="is_couple" class="w-4 h-4 rounded text-blue-600" />
                                <label for="is_couple" class="text-sm font-semibold text-gray-700">Checking in as a couple (husband &amp; wife)</label>
                            </div>
                            <div v-if="form.is_couple" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="label">Spouse Name <span class="text-red-500">*</span></label>
                                    <input v-model="form.spouse_name" type="text" class="input" />
                                </div>
                                <div>
                                    <label class="label">Marriage Date</label>
                                    <input v-model="form.marriage_date" type="date" class="input" />
                                </div>
                                <div>
                                    <label class="label">Nikahnama / Marriage Cert</label>
                                    <DocumentSlot @change="f => form.marriage_cert_doc = f" />
                                </div>
                            </div>
                        </div>

                        <!-- Guest Photo -->
                        <div class="space-y-4 pt-4 border-t border-gray-100">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Guest Photo</h3>
                            <div class="flex gap-2 text-sm">
                                <button type="button" @click="photoMode = 'upload'"
                                    :class="photoMode === 'upload' ? 'bg-blue-600 text-white' : 'border border-gray-300 text-gray-600'"
                                    class="px-3 py-1.5 rounded text-xs">Upload Photo</button>
                                <button type="button" @click="photoMode = 'webcam'"
                                    :class="photoMode === 'webcam' ? 'bg-blue-600 text-white' : 'border border-gray-300 text-gray-600'"
                                    class="px-3 py-1.5 rounded text-xs">Use Webcam</button>
                            </div>
                            <div class="max-w-sm">
                                <DropZone v-if="photoMode === 'upload'"
                                    @change="f => (form.guest_photo_doc = f)" hint="JPEG/PNG/WebP, max 5MB" preview-class="w-full h-48 object-cover" />
                                <WebcamCapture v-else @capture="f => (form.guest_photo_doc = f)" @clear="() => (form.guest_photo_doc = null)" />
                            </div>
                        </div>

                        <!-- Other Documents -->
                        <div class="space-y-4 pt-4 border-t border-gray-100">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Other Supporting Documents</h3>
                            <DropZone :multiple="true" hint="Any supporting document" @change="files => (form.other_documents = files)" />
                        </div>
                    </div>
                </section>

                <!-- ── Payment ── -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Payment Information</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="label">Advance Payment ({{ currencySymbol() }})</label>
                            <input v-model.number="form.advance_payment" type="number" min="0" step="0.01" class="input" />
                        </div>
                        <div>
                            <label class="label">Payment Method</label>
                            <select v-model="form.payment_method" class="input">
                                <option value="cash">Cash</option>
                                <option value="card">Card</option>
                                <option value="bkash">bKash</option>
                                <option value="nagad">Nagad</option>
                                <option value="bank_transfer">Bank Transfer</option>
                            </select>
                        </div>
                    </div>
                </section>

                <!-- ── Notes ── -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Additional Information</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Special Requests</label>
                            <textarea v-model="form.special_requests" rows="3" class="input" placeholder="Any special requests from the guest..."></textarea>
                        </div>
                        <div>
                            <label class="label">Internal Notes</label>
                            <textarea v-model="form.notes" rows="3" class="input" placeholder="Internal notes for staff only..."></textarea>
                        </div>
                    </div>
                </section>

                <!-- Actions -->
                <div class="flex justify-end gap-3">
                    <Link :href="route('admin.room-bookings.index')"
                        class="px-5 py-2 text-sm border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                        Cancel
                    </Link>
                    <button type="submit" :disabled="form.processing"
                        class="px-6 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-60">
                        {{ form.processing ? 'Creating...' : 'Create Booking' }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import RoomPickerSelect from '@/Components/Admin/Shared/RoomPickerSelect.vue';
import DocumentSlot from '@/Components/Admin/Shared/DocumentSlot.vue';
import DropZone from '@/Components/Admin/Shared/DropZone.vue';
import WebcamCapture from '@/Components/Admin/Shared/WebcamCapture.vue';

const props = defineProps({
    roomTypes: Array,
    rooms:     Array,
    customers: Array,
    districts: { type: Array, default: () => [] },
    upazilas: { type: Array, default: () => [] },
    policeStations: { type: Array, default: () => [] },
    canManageFlag: Boolean,
    prefill:   { type: Object, default: () => ({}) },
});

const today        = new Date().toISOString().split('T')[0];
const lookupPhone  = ref('');
const lookupResult = ref('');
const showFullKyc  = ref(false);
const photoMode    = ref('upload');
const availableRoomsByIndex = ref({});

function blankLine() {
    return { room_type_id: '', room_id: '', adults: 2, children: 0, price_per_night: 0 };
}

const form = useForm({
    check_in_date:        props.prefill?.check_in_date ?? '',
    check_out_date:       props.prefill?.check_out_date ?? '',
    booking_status:       'confirmed',
    currency:             'BDT',
    advance_payment:      0,
    payment_method:       'cash',
    special_requests:     '',
    notes:                '',
    rooms: [{
        room_type_id:    props.prefill?.room_type_id ?? '',
        room_id:         props.prefill?.room_id ?? '',
        adults:          2,
        children:        0,
        price_per_night: 0,
    }],
    // customer basic
    customer_id:          null,
    customer_name:        '',
    customer_phone:       '',
    customer_email:       '',
    customer_nationality: 'Bangladeshi',
    customer_address:     '',

    // extended KYC
    father_name:          '',
    mother_name:          '',
    gender:               null,
    date_of_birth:        '',
    occupation:           '',
    emergency_contact:    '',
    present_address:      '',
    permanent_address:    '',
    district_id:          null,
    upazila_id:           null,
    police_station_id:    null,
    post_code:            '',

    document_type:        'nid',
    nid_number:           '',
    birth_certificate_number: '',
    passport_number:      '',
    driving_license_number: '',

    is_foreign_guest:     false,
    visa_number:          '',
    arrival_date_bd:      '',

    is_couple:            false,
    spouse_name:          '',
    marriage_date:        '',

    is_flagged:           false,
    flagged_note:         '',

    // document files
    nid_front_doc:        null,
    nid_back_doc:         null,
    passport_doc:         null,
    visa_doc:             null,
    marriage_cert_doc:    null,
    guest_photo_doc:      null,
    other_documents:      [],
});

const filteredUpazilas = computed(() => (props.upazilas || []).filter(u => u.district_id === form.district_id));
const filteredPoliceStations = computed(() => (props.policeStations || []).filter(ps => {
    if (ps.district_id !== form.district_id) return false;
    if (!form.upazila_id) return true;
    return ps.upazila_id === form.upazila_id || ps.upazila_id === null;
}));

const totalNights = computed(() => {
    if (!form.check_in_date || !form.check_out_date) return 0;
    const diff = new Date(form.check_out_date) - new Date(form.check_in_date);
    return Math.max(0, Math.floor(diff / 86400000));
});

function currencySymbol() {
    return form.currency === 'USD' ? '$' : '৳';
}
function formatAmount(n) {
    const decimals = form.currency === 'USD' ? 2 : 0;
    return Number(n || 0).toLocaleString(undefined, { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
}
function roomTypeEffectivePrice(rt) {
    if (!rt) return null;
    if (form.currency === 'USD') {
        if (!rt.price_usd) return null;
        return rt.discounted_price_usd ?? parseFloat(rt.price_usd);
    }
    return rt.discounted_price_bdt ?? parseFloat(rt.price);
}
function roomTypeOptionLabel(rt) {
    const eff = roomTypeEffectivePrice(rt);
    if (eff !== null) {
        return rt.name + ' — ' + currencySymbol() + formatAmount(eff) + rt.price_unit;
    }
    return rt.name + (form.currency === 'USD' ? ' — USD N/A' : '');
}
function typeById(id) {
    return props.roomTypes.find(r => r.id == id);
}
function roomTypeDiscountInfo(rt) {
    if (!rt) return null;
    const eff = roomTypeEffectivePrice(rt);
    if (eff === null) return null;
    const original = form.currency === 'USD' ? parseFloat(rt.price_usd) : parseFloat(rt.price);
    if (!original || original === eff) return null;
    return { original, effective: eff, percentOff: Math.round((1 - eff / original) * 100) };
}

watch(() => form.currency, () => {
    form.rooms.forEach((line) => {
        const rt = props.roomTypes.find(r => r.id == line.room_type_id);
        const eff = roomTypeEffectivePrice(rt);
        if (eff !== null) line.price_per_night = eff;
    });
});

const totalAmount   = computed(() => form.rooms.reduce((sum, l) => sum + totalNights.value * (l.price_per_night || 0), 0));
const totalAdults   = computed(() => form.rooms.reduce((sum, l) => sum + (l.adults || 0), 0));
const totalChildren = computed(() => form.rooms.reduce((sum, l) => sum + (l.children || 0), 0));

function addLine() {
    form.rooms.push(blankLine());
}

function removeLine(idx) {
    form.rooms.splice(idx, 1);
    refreshAllAvailability();
}

async function fetchRoomAvailability(idx) {
    const line = form.rooms[idx];
    if (!line || !line.room_type_id || !form.check_in_date || !form.check_out_date) {
        availableRoomsByIndex.value = {
            ...availableRoomsByIndex.value,
            [idx]: props.rooms.filter(r => r.room_type_id == line?.room_type_id).map(r => ({ ...r, available: true })),
        };
        return;
    }
    try {
        const excludeIds = form.rooms.filter((l, i) => i !== idx && l.room_id).map(l => l.room_id);
        const params = new URLSearchParams({
            room_type_id:   line.room_type_id,
            check_in_date:  form.check_in_date,
            check_out_date: form.check_out_date,
        });
        excludeIds.forEach(id => params.append('exclude_room_ids[]', id));
        const res  = await fetch(route('admin.room-bookings.room-availability') + '?' + params, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        });
        const data = await res.json();
        availableRoomsByIndex.value = { ...availableRoomsByIndex.value, [idx]: data.rooms ?? [] };
    } catch {
        availableRoomsByIndex.value = {
            ...availableRoomsByIndex.value,
            [idx]: props.rooms.filter(r => r.room_type_id == line.room_type_id).map(r => ({ ...r, available: true })),
        };
    }
}

function refreshAllAvailability() {
    form.rooms.forEach((_, idx) => fetchRoomAvailability(idx));
}

watch([() => form.check_in_date, () => form.check_out_date], refreshAllAvailability);

function onRoomTypeChange(idx) {
    const line = form.rooms[idx];
    const rt = props.roomTypes.find(r => r.id == line.room_type_id);
    const eff = roomTypeEffectivePrice(rt);
    if (eff !== null) line.price_per_night = eff;
    line.room_id = '';
    refreshAllAvailability();
}

onMounted(() => {
    if (props.prefill?.room_type_id) {
        onRoomTypeChange(0);
    }
    refreshAllAvailability();
});

function onPhoneInput() {
    const phone = lookupPhone.value.trim();
    form.customer_phone = phone;

    if (phone.length < 5) { lookupResult.value = ''; return; }

    const existing = props.customers.find(c => c.phone === phone);
    if (existing) {
        lookupResult.value      = 'found';
        form.customer_id        = existing.id;
        form.customer_name      = existing.name;
        form.customer_email     = existing.email ?? '';
        form.customer_nationality = existing.nationality ?? 'Bangladeshi';
        form.customer_address   = existing.address ?? '';
        form.father_name        = existing.father_name ?? '';
        form.mother_name        = existing.mother_name ?? '';
        form.gender             = existing.gender ?? null;
        form.date_of_birth      = existing.date_of_birth ?? '';
        form.occupation         = existing.occupation ?? '';
        form.emergency_contact  = existing.emergency_contact ?? '';
        form.present_address    = existing.present_address ?? '';
        form.permanent_address  = existing.permanent_address ?? '';
        form.district_id        = existing.district_id ?? null;
        form.upazila_id         = existing.upazila_id ?? null;
        form.police_station_id  = existing.police_station_id ?? null;
        form.post_code          = existing.post_code ?? '';
        form.document_type      = existing.document_type ?? 'nid';
        form.nid_number         = existing.nid_number ?? '';
        form.birth_certificate_number = existing.birth_certificate_number ?? '';
        form.passport_number    = existing.passport_number ?? '';
        form.driving_license_number = existing.driving_license_number ?? '';
        form.is_foreign_guest   = existing.is_foreign_guest ?? false;
        form.visa_number        = existing.visa_number ?? '';
        form.arrival_date_bd    = existing.arrival_date_bd ?? '';
        form.is_couple          = existing.is_couple ?? false;
        form.spouse_name        = existing.spouse_name ?? '';
        form.marriage_date      = existing.marriage_date ?? '';
        form.is_flagged         = existing.is_flagged ?? false;
        form.flagged_note       = existing.flagged_note ?? '';
        if (existing.father_name || existing.is_foreign_guest || existing.is_couple) {
            showFullKyc.value = true;
        }
    } else {
        lookupResult.value   = phone.length >= 10 ? 'new' : '';
        form.customer_id     = null;
        form.customer_name   = '';
        form.customer_email  = '';
        form.nid_number      = '';
        form.passport_number = '';
    }
}

function submit() {
    form.post(route('admin.room-bookings.store'), { forceFormData: true });
}
</script>

<style scoped>
.label { @apply block text-sm font-medium text-gray-700 mb-1.5; }
.input { @apply w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors bg-white; }
select.input { @apply cursor-pointer; }
textarea.input { @apply resize-none; }
</style>
