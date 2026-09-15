<template>
    <AdminLayout>
        <div class="max-w-7xl space-y-6">
            <!-- Header -->
            <div class="flex items-center gap-4">
                <Link :href="route('admin.room-bookings.index')" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><polyline points="15 18 9 12 15 6"/></svg>
                </Link>
                <div>
                    <h1 class="text-lg font-semibold text-gray-800">{{ booking.booking_reference }}</h1>
                    <p class="text-xs text-gray-400">Created {{ formatDateTime(booking.created_at) }}</p>
                </div>
                <div class="ml-auto flex items-center gap-3">
                    <button @click="sendConfirmationMessage" :disabled="!customerPhone"
                        :title="!customerPhone ? 'Customer has no phone number on file' : ''"
                        class="px-4 py-1.5 text-sm bg-green-600 text-white rounded hover:bg-green-700 font-medium disabled:opacity-50 disabled:cursor-not-allowed inline-flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4 fill-current"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12.004 2C6.486 2 2 6.486 2 12.004c0 1.94.526 3.815 1.523 5.462L2 22l4.657-1.505A9.958 9.958 0 0 0 12.004 22C17.523 22 22 17.523 22 12.004 22 6.486 17.523 2 12.004 2zm0 18.15a8.13 8.13 0 0 1-4.146-1.13l-.298-.177-2.764.893.907-2.694-.194-.28A8.13 8.13 0 1 1 20.15 12.004a8.14 8.14 0 0 1-8.146 8.146z"/></svg>
                        Send Confirmation
                    </button>
                    <button @click="openCustomMessageModal" :disabled="!customerPhone"
                        :title="!customerPhone ? 'Customer has no phone number on file' : ''"
                        class="px-4 py-1.5 text-sm border border-green-600 text-green-700 rounded hover:bg-green-50 font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                        Custom Message
                    </button>
                    <a :href="route('admin.room-bookings.invoice', booking.id)"
                        class="px-4 py-1.5 text-sm border border-gray-300 text-gray-700 rounded hover:bg-gray-50 font-medium inline-flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Download Invoice
                    </a>
                    <a v-if="Number(booking.advance_payment) > 0"
                        :href="route('admin.room-bookings.receipt', booking.id)"
                        class="px-4 py-1.5 text-sm border border-gray-300 text-gray-700 rounded hover:bg-gray-50 font-medium inline-flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="15" x2="15" y2="15"/><line x1="9" y1="11" x2="11" y2="11"/></svg>
                        Money Receipt
                    </a>
                    <Link :href="route('admin.room-bookings.edit', booking.id)"
                        class="px-4 py-1.5 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 font-medium">
                        Edit Booking
                    </Link>
                    <span :class="statusBadge(booking.booking_status)"
                        class="px-3 py-1 rounded-full text-sm font-semibold">
                        {{ statusLabel(booking.booking_status) }}
                    </span>
                </div>
            </div>

            <!-- Flash -->
            <div v-if="$page.props.flash?.success"
                class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded">
                {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash?.error"
                class="px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded">
                {{ $page.props.flash.error }}
            </div>

            <!-- Missing Document Reminder Alert -->
            <div v-if="!hasUploadedDocs"
                class="px-5 py-4 bg-amber-50 border-2 border-amber-300 text-amber-900 rounded-lg flex items-center justify-between gap-4 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center font-bold text-lg flex-shrink-0">
                        ⚠️
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-amber-900">Guest Identity Documents Pending</h3>
                        <p class="text-xs text-amber-700 mt-0.5">No identity documents or guest photo have been uploaded for {{ booking.customer?.name || 'this guest' }}. Please attach required documents.</p>
                    </div>
                </div>
                <Link :href="route('admin.room-bookings.edit', booking.id)"
                    class="px-4 py-2 bg-amber-600 text-white text-xs font-semibold rounded hover:bg-amber-700 transition-colors flex-shrink-0">
                    Upload Documents Now
                </Link>
            </div>

            <!-- Status Actions -->
            <section class="bg-white rounded-lg shadow-sm p-5">
                <h2 class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-3">Update Booking Status</h2>
                <div class="flex flex-wrap gap-2 mb-4">
                    <button v-for="action in availableActions" :key="action.status"
                        @click="changeStatus(action.status)"
                        :class="action.btnClass"
                        class="px-4 py-1.5 text-sm rounded font-medium transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                        {{ action.label }}
                    </button>
                </div>
                <div v-if="showNoteInput" class="flex gap-2">
                    <input v-model="statusNote" type="text" placeholder="Add a note (optional)..." class="input flex-1" />
                    <button @click="submitStatus" :disabled="statusProcessing"
                        class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-60">
                        {{ statusProcessing ? '...' : 'Confirm' }}
                    </button>
                    <button @click="cancelStatusChange" class="px-3 py-2 text-gray-500 hover:text-gray-700">✕</button>
                </div>
            </section>

            <!-- Rooms -->
            <section class="bg-white rounded-lg shadow-sm p-5">
                <h2 class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">
                    Rooms ({{ booking.rooms?.length ?? 0 }})
                </h2>
                <p class="text-xs text-gray-400 mb-3">Status applies to the whole booking — use "Update Booking Status" above. Assign specific room numbers from Edit Booking.</p>
                <div class="space-y-3">
                    <div v-for="line in booking.rooms" :key="line.id"
                        class="border border-gray-200 rounded-lg p-3 flex items-center justify-between gap-4 flex-wrap">
                        <div>
                            <div class="font-medium text-gray-800 text-sm">
                                {{ line.room_type?.name ?? 'Unassigned type' }}
                                <span class="text-gray-400 font-normal">— {{ line.room?.room_number ?? 'Room not assigned' }}</span>
                            </div>
                            <div class="text-xs text-gray-500 mt-0.5">
                                {{ line.adults }} adult{{ line.adults > 1 ? 's' : '' }}<span v-if="line.children">, {{ line.children }} child{{ line.children > 1 ? 'ren' : '' }}</span>
                                · {{ currencySymbol }}{{ Number(line.price_per_night).toLocaleString() }}/night × {{ line.nights }} = {{ currencySymbol }}{{ Number(line.line_total).toLocaleString() }}
                            </div>
                            <div v-if="line.actual_check_in_at || line.actual_check_out_at" class="text-xs text-gray-400 mt-0.5">
                                <span v-if="line.actual_check_in_at">In: {{ formatDateTime(line.actual_check_in_at) }}</span>
                                <span v-if="line.actual_check_out_at"> · Out: {{ formatDateTime(line.actual_check_out_at) }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span :class="statusBadge(line.status)" class="px-2.5 py-1 rounded-full text-xs font-semibold">{{ statusLabel(line.status) }}</span>
                        </div>
                    </div>
                    <p v-if="!booking.rooms?.length" class="text-sm text-gray-400 italic">No rooms on this booking.</p>
                </div>
            </section>

            <!-- Booking Info Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">

                <!-- Stay Details -->
                <section class="bg-white rounded-lg shadow-sm p-5">
                    <h2 class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-3">Stay Details</h2>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Check-In</dt>
                            <dd class="font-medium text-gray-800">{{ formatDate(booking.check_in_date) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Check-Out</dt>
                            <dd class="font-medium text-gray-800">{{ formatDate(booking.check_out_date) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Total Nights</dt>
                            <dd class="font-medium text-gray-800">{{ booking.total_nights }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Guests</dt>
                            <dd class="text-gray-800">{{ booking.adults }} adults{{ booking.children ? ', ' + booking.children + ' children' : '' }}</dd>
                        </div>
                    </dl>
                </section>

                <!-- Customer Details -->
                <section class="bg-white rounded-lg shadow-sm p-5">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</h2>
                        <Link :href="route('admin.customers.show', booking.customer_id)" class="text-xs text-blue-600 hover:underline font-medium">View Full Profile →</Link>
                    </div>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Name</dt>
                            <dd class="font-medium text-gray-800">
                                <Link :href="route('admin.customers.show', booking.customer_id)"
                                    class="text-blue-600 hover:underline">
                                    {{ booking.customer?.name }}
                                </Link>
                            </dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Phone</dt>
                            <dd class="text-gray-800">{{ booking.customer?.phone }}</dd>
                        </div>
                        <div v-if="booking.customer?.email" class="flex justify-between">
                            <dt class="text-gray-500">Email</dt>
                            <dd class="text-gray-800">{{ booking.customer?.email }}</dd>
                        </div>
                        <div v-if="booking.customer?.nationality" class="flex justify-between">
                            <dt class="text-gray-500">Nationality</dt>
                            <dd class="text-gray-800">{{ booking.customer?.nationality }}</dd>
                        </div>
                        <div v-if="booking.customer?.nid_number" class="flex justify-between">
                            <dt class="text-gray-500">NID</dt>
                            <dd class="text-gray-800">{{ booking.customer?.nid_number }}</dd>
                        </div>
                        <div v-if="booking.customer?.passport_number" class="flex justify-between">
                            <dt class="text-gray-500">Passport</dt>
                            <dd class="text-gray-800">{{ booking.customer?.passport_number }}</dd>
                        </div>
                    </dl>

                    <div v-if="customerDocs.length > 0" class="pt-3 border-t border-gray-100 mt-3 space-y-1.5">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Attached Documents ({{ customerDocs.length }})</p>
                        <div v-for="doc in customerDocs" :key="doc.id" class="flex items-center justify-between text-xs py-1 px-2 bg-gray-50 rounded border border-gray-100">
                            <span class="text-gray-700 font-medium capitalize truncate">{{ formatDocCategory(doc.category) }}</span>
                            <a :href="route('admin.documents.show', doc.id)" target="_blank" class="text-blue-600 hover:underline flex-shrink-0 font-medium">View</a>
                        </div>
                    </div>
                </section>

                <!-- Payment -->
                <section class="bg-white rounded-lg shadow-sm p-5">
                    <h2 class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-3">Payment</h2>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Currency</dt>
                            <dd class="font-medium text-gray-800">{{ booking.currency ?? 'BDT' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Total Amount</dt>
                            <dd class="font-bold text-emerald-700 text-base">{{ currencySymbol }}{{ Number(booking.total_amount).toLocaleString() }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Advance Paid</dt>
                            <dd class="text-gray-800">{{ currencySymbol }}{{ Number(booking.advance_payment).toLocaleString() }}</dd>
                        </div>
                        <div v-if="Number(booking.discount_amount) > 0" class="flex justify-between">
                            <dt class="text-gray-500">Discount</dt>
                            <dd class="text-green-600">− {{ currencySymbol }}{{ Number(booking.discount_amount).toLocaleString() }}</dd>
                        </div>
                        <div class="flex justify-between border-t pt-2 mt-1">
                            <dt class="text-gray-500 font-medium">Balance Due</dt>
                            <dd class="font-semibold" :class="balanceDue > 0 ? 'text-red-600' : 'text-green-700'">
                                {{ currencySymbol }}{{ balanceDue.toLocaleString() }}
                            </dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Method</dt>
                            <dd class="capitalize text-gray-800">{{ booking.payment_method }}</dd>
                        </div>
                    </dl>
                </section>

                <!-- Misc -->
                <section class="bg-white rounded-lg shadow-sm p-5">
                    <h2 class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-3">Notes</h2>
                    <div v-if="booking.special_requests" class="mb-3">
                        <p class="text-xs text-gray-500 mb-1">Special Requests</p>
                        <p class="text-sm text-gray-700">{{ booking.special_requests }}</p>
                    </div>
                    <div v-if="booking.notes">
                        <p class="text-xs text-gray-500 mb-1">Internal Notes</p>
                        <p class="text-sm text-gray-700">{{ booking.notes }}</p>
                    </div>
                    <div v-if="!booking.special_requests && !booking.notes" class="text-sm text-gray-400 italic">
                        No notes.
                    </div>
                    <div v-if="booking.booked_by" class="mt-3 pt-3 border-t text-xs text-gray-400">
                        Booked by: {{ booking.booked_by?.name ?? 'Admin' }}
                    </div>
                </section>
            </div>

            <!-- Delete -->
            <div class="flex justify-end">
                <button @click="confirmDelete = true"
                    class="px-4 py-2 text-sm border border-red-300 text-red-500 rounded hover:bg-red-50">
                    Delete Booking
                </button>
            </div>
        </div>

        <!-- Custom WhatsApp Message Modal -->
        <div v-if="showCustomMessageModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md mx-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-semibold text-gray-800">Send WhatsApp Message</h3>
                    <button @click="showCustomMessageModal = false" class="text-gray-400 hover:text-gray-600 text-xl leading-none">✕</button>
                </div>
                <p class="text-xs text-gray-500 mb-3">
                    To: {{ booking.customer?.name }} ({{ customerPhone }})
                </p>
                <textarea v-model="customMessageText" rows="6"
                    placeholder="Write your message..."
                    class="input w-full resize-none"></textarea>
                <div class="flex gap-3 mt-5">
                    <button @click="showCustomMessageModal = false"
                        class="flex-1 px-4 py-2 text-sm border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button @click="sendCustomMessage" :disabled="!customMessageText.trim()"
                        class="flex-1 px-4 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold disabled:opacity-60">
                        Send via WhatsApp
                    </button>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div v-if="confirmDelete" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="bg-white rounded-lg shadow-xl p-6 max-w-sm w-full mx-4">
                <h3 class="text-base font-semibold text-gray-800 mb-2">Delete Booking</h3>
                <p class="text-sm text-gray-600 mb-4">Delete booking <strong>{{ booking.booking_reference }}</strong>? This cannot be undone.</p>
                <div class="flex gap-3 justify-end">
                    <button @click="confirmDelete = false"
                        class="px-4 py-2 text-sm border border-gray-300 rounded text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button @click="doDelete"
                        class="px-4 py-2 text-sm bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
                </div>
            </div>
        </div>

        <!-- Checkout Modal -->
        <div v-if="showCheckoutModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md mx-4 overflow-hidden">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-base font-semibold text-gray-800">Checkout — {{ booking.booking_reference }}</h3>
                    <button @click="showCheckoutModal = false" class="text-gray-400 hover:text-gray-600 text-xl leading-none">✕</button>
                </div>

                <!-- Payment Summary -->
                <div class="bg-gray-50 rounded-lg p-4 mb-5 text-sm space-y-2">
                    <div class="flex justify-between text-gray-600">
                        <span>Total Booking Amount</span>
                        <span class="font-medium text-gray-800">{{ currencySymbol }}{{ Number(booking.total_amount).toLocaleString() }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Already Paid (Advance)</span>
                        <span class="text-gray-800">{{ currencySymbol }}{{ Number(booking.advance_payment).toLocaleString() }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600 border-t pt-2">
                        <span>Remaining Balance</span>
                        <span class="font-semibold text-red-600">{{ currencySymbol }}{{ Math.max(0, Number(booking.total_amount) - Number(booking.advance_payment)).toLocaleString() }}</span>
                    </div>
                </div>

                <!-- Discount -->
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Apply Discount (Optional)</label>
                    <div class="flex gap-2 w-full">
                        <select v-model="checkoutForm.discount_type" class="input flex-shrink-0" style="width:140px">
                            <option value="fixed">Fixed ({{ currencySymbol }})</option>
                            <option value="percent">Percent (%)</option>
                        </select>
                        <input v-model="checkoutForm.discount_value" type="number" min="0" step="any"
                            :placeholder="checkoutForm.discount_type === 'percent' ? 'e.g. 10' : 'e.g. 500'"
                            class="input flex-1 min-w-0" />
                    </div>
                    <p v-if="checkoutDiscountAmount > 0" class="text-xs text-green-600 mt-1">
                        Discount: − {{ currencySymbol }}{{ checkoutDiscountAmount.toLocaleString() }}
                    </p>
                </div>

                <!-- Collect Amount -->
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Collect Remaining Amount (Optional)</label>
                    <input v-model="checkoutForm.collected_amount" type="number" min="0" step="any"
                        :placeholder="`Amount collected now (${currencySymbol})`"
                        class="input w-full" />
                </div>

                <!-- Notes -->
                <div class="mb-5">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Notes (Optional)</label>
                    <input v-model="checkoutForm.notes" type="text" placeholder="e.g. paid by cash, no damage, etc." class="input w-full" />
                </div>

                <!-- Final Balance Preview -->
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 mb-5 text-sm space-y-1">
                    <div v-if="checkoutDiscountAmount > 0" class="flex justify-between text-green-700">
                        <span>After Discount</span>
                        <span class="font-medium">{{ currencySymbol }}{{ (Number(booking.total_amount) - checkoutDiscountAmount).toLocaleString() }}</span>
                    </div>
                    <div v-if="parseFloat(checkoutForm.collected_amount) > 0" class="flex justify-between text-blue-700">
                        <span>Collected Now</span>
                        <span class="font-medium">+ {{ currencySymbol }}{{ Number(checkoutForm.collected_amount).toLocaleString() }}</span>
                    </div>
                    <div class="flex justify-between font-semibold border-t pt-2 mt-1"
                        :class="checkoutFinalBalance > 0 ? 'text-red-700' : 'text-green-700'">
                        <span>Final Balance Due</span>
                        <span>{{ currencySymbol }}{{ checkoutFinalBalance.toLocaleString() }}</span>
                    </div>
                </div>

                <!-- Error message -->
                <div v-if="checkoutError" class="mb-4 px-3 py-2 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">
                    {{ checkoutError }}
                </div>

                <div class="flex gap-3">
                    <button @click="showCheckoutModal = false"
                        class="flex-1 px-4 py-2 text-sm border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button @click="submitCheckout" :disabled="checkoutProcessing"
                        class="flex-1 px-4 py-2 text-sm bg-orange-500 text-white rounded-lg hover:bg-orange-600 font-semibold disabled:opacity-60">
                        {{ checkoutProcessing ? 'Processing...' : 'Confirm Checkout' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Assign Room & Check In Modal -->
        <div v-if="showAssignRoomModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md mx-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-base font-semibold text-gray-800">Assign Room &amp; Check In</h3>
                    <button @click="showAssignRoomModal = false" class="text-gray-400 hover:text-gray-600 text-xl leading-none">✕</button>
                </div>
                <p class="text-xs text-gray-500 mb-4">Every room line needs a physical room number before check-in. Assign them below.</p>

                <div class="space-y-3 mb-4">
                    <div v-for="line in assignLines" :key="line.id" class="border border-gray-200 rounded-lg p-3">
                        <div class="text-sm font-medium text-gray-800 mb-2">
                            {{ line.room_type?.name ?? 'Unassigned type' }}
                            <span class="text-gray-400 font-normal">— {{ line.adults }} adult{{ line.adults > 1 ? 's' : '' }}<span v-if="line.children">, {{ line.children }} child{{ line.children > 1 ? 'ren' : '' }}</span></span>
                        </div>
                        <RoomPickerSelect v-model="assignments[line.id]"
                            :rooms="availableRoomsByLine[line.id] ?? []"
                            placeholder="— Select room —" />
                    </div>
                </div>

                <div v-if="assignError" class="mb-4 px-3 py-2 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">
                    {{ assignError }}
                </div>

                <div class="flex gap-3">
                    <button @click="showAssignRoomModal = false"
                        class="flex-1 px-4 py-2 text-sm border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button @click="submitAssignAndCheckIn" :disabled="assignProcessing || !allLinesAssigned"
                        class="flex-1 px-4 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold disabled:opacity-60">
                        {{ assignProcessing ? 'Checking In...' : 'Assign & Check In' }}
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import RoomPickerSelect from '@/Components/Admin/Shared/RoomPickerSelect.vue';

const props = defineProps({ booking: Object });
const page  = usePage();

const customerDocs = computed(() => props.booking.customer?.documents || []);
const hasUploadedDocs = computed(() => customerDocs.value.length > 0);

function formatDocCategory(cat) {
    const labels = {
        nid_front: 'NID — Front',
        nid_back: 'NID — Back',
        passport_scan: 'Passport Scan',
        visa: 'Visa Copy',
        marriage_certificate: 'Marriage Certificate',
        guest_photo: 'Guest Photo',
        other: 'Other Document',
    };
    return labels[cat] ?? (cat ? cat.replaceAll('_', ' ') : '');
}

const showCustomMessageModal = ref(false);
const customMessageText      = ref('');

const confirmDelete    = ref(false);
const showNoteInput    = ref(false);
const statusNote       = ref('');
const statusProcessing = ref(false);
const pendingStatus    = ref('');

// Checkout modal state
const showCheckoutModal  = ref(false);
const checkoutProcessing = ref(false);
const checkoutError      = ref('');
const checkoutForm       = ref({ collected_amount: '', discount_type: 'fixed', discount_value: '', notes: '' });

// Assign Room & Check In modal state
const showAssignRoomModal  = ref(false);
const assignLines          = ref([]);
const availableRoomsByLine = ref({});
const assignments          = ref({});
const assignProcessing     = ref(false);
const assignError          = ref('');

const hasUnassignedRooms = computed(() =>
    (props.booking.rooms ?? []).some(r => r.status !== 'cancelled' && !r.room)
);

const allLinesAssigned = computed(() =>
    assignLines.value.every(line => !!assignments.value[line.id])
);

async function fetchAvailableRoomsForLine(line) {
    const params = new URLSearchParams({
        room_type_id:       line.room_type?.id ?? line.room_type_id,
        check_in_date:      props.booking.check_in_date,
        check_out_date:     props.booking.check_out_date,
        exclude_booking_id: props.booking.id,
    });
    try {
        const res  = await fetch(route('admin.room-bookings.room-availability') + '?' + params, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        });
        const data = await res.json();
        availableRoomsByLine.value = { ...availableRoomsByLine.value, [line.id]: data.rooms ?? [] };
    } catch {
        availableRoomsByLine.value = { ...availableRoomsByLine.value, [line.id]: [] };
    }
}

function openAssignRoomModal() {
    assignLines.value = (props.booking.rooms ?? []).filter(r => r.status !== 'cancelled' && !r.room);
    assignments.value = {};
    assignError.value = '';
    showAssignRoomModal.value = true;
    assignLines.value.forEach(fetchAvailableRoomsForLine);
}

function submitAssignAndCheckIn() {
    assignError.value = '';
    const roomAssignments = assignLines.value.map(line => ({
        line_id: line.id,
        room_id: assignments.value[line.id],
    }));

    if (roomAssignments.some(a => !a.room_id)) {
        assignError.value = 'Please select a room for every line.';
        return;
    }

    assignProcessing.value = true;
    router.patch(route('admin.room-bookings.update-status', props.booking.id), {
        booking_status:    'checked_in',
        notes:             '',
        room_assignments:  roomAssignments,
    }, {
        onSuccess: () => {
            showAssignRoomModal.value = false;
        },
        onError: (errors) => {
            assignError.value = Object.values(errors).flat().join(' ') || 'Could not check in. Please try again.';
        },
        onFinish: () => {
            assignProcessing.value = false;
        },
    });
}

const balanceDue = computed(() =>
    Math.max(0, Number(props.booking.total_amount) - Number(props.booking.advance_payment) - Number(props.booking.discount_amount || 0))
);

const currencySymbol = computed(() => props.booking.currency === 'USD' ? '$' : '৳');

const checkoutDiscountAmount = computed(() => {
    const val = parseFloat(checkoutForm.value.discount_value) || 0;
    if (!val) return 0;
    if (checkoutForm.value.discount_type === 'percent') {
        return Math.round(Number(props.booking.total_amount) * val / 100 * 100) / 100;
    }
    return val;
});

const checkoutFinalBalance = computed(() => {
    const total     = Number(props.booking.total_amount);
    const paid      = Number(props.booking.advance_payment);
    const collected = parseFloat(checkoutForm.value.collected_amount) || 0;
    const discount  = checkoutDiscountAmount.value;
    return Math.max(0, total - paid - collected - discount);
});

const customerPhone = computed(() => formatWhatsAppPhone(props.booking.customer?.phone));

function formatWhatsAppPhone(phone) {
    const digits = (phone || '').replace(/\D/g, '');
    if (!digits) return '';
    if (digits.startsWith('880')) return digits;
    if (digits.startsWith('0')) return '880' + digits.slice(1);
    return digits;
}

function openWhatsApp(phone, text) {
    if (!phone) return;
    const url = `https://api.whatsapp.com/send?phone=${phone}&text=${encodeURIComponent(text)}`;
    window.open(url, '_blank');
}

function buildConfirmationMessage() {
    const hotelName = page.props.site?.name ?? 'Hotel Beach Way';
    const b = props.booking;
    const rooms = (b.rooms ?? [])
        .map(line => `${line.room_type?.name ?? 'Room'}${line.room?.room_number ? ' - ' + line.room.room_number : ''}`)
        .join(', ') || 'N/A';

    return `Dear ${b.customer?.name ?? 'Guest'},

Your booking at ${hotelName} is confirmed! ✅

Booking Reference: ${b.booking_reference}
Check-In: ${formatDate(b.check_in_date)}
Check-Out: ${formatDate(b.check_out_date)}
Rooms: ${rooms}
Guests: ${b.adults} adult${b.adults > 1 ? 's' : ''}${b.children ? ', ' + b.children + ' children' : ''}

Total Amount: ${currencySymbol.value}${Number(b.total_amount).toLocaleString()}
Advance Paid: ${currencySymbol.value}${Number(b.advance_payment).toLocaleString()}
Balance Due: ${currencySymbol.value}${balanceDue.value.toLocaleString()}

Thank you for choosing ${hotelName}. We look forward to welcoming you!`;
}

function sendConfirmationMessage() {
    if (!customerPhone.value) return;
    openWhatsApp(customerPhone.value, buildConfirmationMessage());
}

function openCustomMessageModal() {
    if (!customerPhone.value) return;
    customMessageText.value = '';
    showCustomMessageModal.value = true;
}

function sendCustomMessage() {
    if (!customMessageText.value.trim()) return;
    openWhatsApp(customerPhone.value, customMessageText.value.trim());
    showCustomMessageModal.value = false;
}

const allActions = {
    pending: [
        { status: 'confirmed',        label: 'Confirm Booking',  btnClass: 'bg-blue-600 text-white hover:bg-blue-700' },
        { status: 'payment_pending',  label: 'Await Payment',    btnClass: 'bg-purple-600 text-white hover:bg-purple-700' },
        { status: 'cancelled',        label: 'Cancel',           btnClass: 'border border-red-300 text-red-500 hover:bg-red-50' },
    ],
    confirmed: [
        { status: 'checked_in',      label: 'Check In',         btnClass: 'bg-green-600 text-white hover:bg-green-700' },
        { status: 'payment_pending', label: 'Await Payment',    btnClass: 'bg-purple-600 text-white hover:bg-purple-700' },
        { status: 'cancelled',       label: 'Cancel',           btnClass: 'border border-red-300 text-red-500 hover:bg-red-50' },
    ],
    payment_pending: [
        { status: 'confirmed',  label: 'Mark Confirmed', btnClass: 'bg-blue-600 text-white hover:bg-blue-700' },
        { status: 'cancelled',  label: 'Cancel',         btnClass: 'border border-red-300 text-red-500 hover:bg-red-50' },
    ],
    checked_in: [
        { status: 'checked_out', label: 'Check Out', btnClass: 'bg-orange-500 text-white hover:bg-orange-600' },
    ],
    checked_out: [],
    cancelled: [],
};

const availableActions = computed(() => allActions[props.booking.booking_status] ?? []);

function changeStatus(status) {
    if (status === 'checked_in' && hasUnassignedRooms.value) {
        openAssignRoomModal();
        return;
    }
    if (status === 'checked_out') {
        checkoutForm.value  = { collected_amount: '', discount_type: 'fixed', discount_value: '', notes: '' };
        checkoutError.value = '';
        showCheckoutModal.value = true;
        return;
    }
    pendingStatus.value = status;
    showNoteInput.value = true;
}

function cancelStatusChange() {
    showNoteInput.value = false;
    pendingStatus.value = '';
    statusNote.value    = '';
}

function submitStatus() {
    statusProcessing.value = true;
    router.patch(route('admin.room-bookings.update-status', props.booking.id), {
        booking_status: pendingStatus.value,
        notes:          statusNote.value,
    }, {
        onFinish: () => {
            statusProcessing.value = false;
            showNoteInput.value    = false;
            statusNote.value       = '';
            pendingStatus.value    = '';
        },
    });
}

function submitCheckout() {
    checkoutError.value = '';
    if (checkoutFinalBalance.value > 0) {
        checkoutError.value = `Please collect the remaining balance of ${currencySymbol.value}${checkoutFinalBalance.value.toLocaleString()} before checking out.`;
        return;
    }
    checkoutProcessing.value = true;
    router.post(route('admin.room-bookings.checkout-payment', props.booking.id), {
        collected_amount: checkoutForm.value.collected_amount || null,
        discount_type:    checkoutForm.value.discount_value ? checkoutForm.value.discount_type : null,
        discount_value:   checkoutForm.value.discount_value || null,
        notes:            checkoutForm.value.notes || null,
    }, {
        onSuccess: () => {
            showCheckoutModal.value = false;
        },
        onError: (errors) => {
            checkoutError.value = Object.values(errors).flat().join(' ') || 'Checkout failed. Please try again.';
        },
        onFinish: () => {
            checkoutProcessing.value = false;
        },
    });
}

function doDelete() {
    router.delete(route('admin.room-bookings.destroy', props.booking.id));
}

function formatDate(d) {
    if (!d) return '—';
    return new Date(d).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
}

function formatDateTime(d) {
    if (!d) return '—';
    return new Date(d).toLocaleString('en-GB', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
}

function statusLabel(s) {
    return {
        pending:         'Pending',
        confirmed:       'Confirmed',
        payment_pending: 'Payment Pending',
        checked_in:      'Checked In',
        checked_out:     'Checked Out',
        cancelled:       'Cancelled',
    }[s] ?? s;
}

function statusBadge(s) {
    return {
        pending:         'bg-yellow-100 text-yellow-700',
        confirmed:       'bg-blue-100 text-blue-700',
        payment_pending: 'bg-purple-100 text-purple-700',
        checked_in:      'bg-green-100 text-green-700',
        checked_out:     'bg-gray-100 text-gray-600',
        cancelled:       'bg-red-100 text-red-600',
    }[s] ?? 'bg-gray-100 text-gray-600';
}
</script>

<style scoped>
.input { @apply w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors bg-white; }
</style>
