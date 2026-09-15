<template>
    <AdminLayout>
        <div class="max-w-4xl space-y-6">
            <div>
                <h1 class="text-lg font-semibold text-gray-800">Email Notification Settings</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Control which automatic emails are sent, to whom, and customize the static wording in
                    each template. System data (booking reference, dates, room lines, totals) is never
                    editable here and always stays accurate.
                </p>
            </div>

            <div v-if="$page.props.flash?.success"
                class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded">
                {{ $page.props.flash.success }}
            </div>

            <form @submit.prevent="submit" class="space-y-6">

                <!-- New Booking Notifications -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">New Booking Submitted</h2>
                    <p class="text-xs text-gray-400">
                        By default the guest is <strong>not</strong> emailed until admin confirms the
                        booking (see Status Notifications below). Admin is notified immediately so staff
                        know to review it.
                    </p>
                    <div class="flex flex-col gap-2">
                        <label class="toggle-row">
                            <input type="checkbox" v-model="form.email_toggle_new_booking_customer">
                            <span>Send confirmation email to customer immediately on submit</span>
                        </label>
                        <label class="toggle-row">
                            <input type="checkbox" v-model="form.email_toggle_new_booking_admin">
                            <span>Notify admin immediately on new booking</span>
                        </label>
                    </div>
                </section>

                <!-- Booking Status Notifications -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Booking Status Change Notifications</h2>
                    <p class="text-xs text-gray-400">Sent when admin changes a booking's status to one of the values below.</p>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-gray-500 text-xs uppercase">
                                    <th class="py-2 pr-4">Status</th>
                                    <th class="py-2 pr-4 text-center">Notify Customer</th>
                                    <th class="py-2 pr-4 text-center">Notify Admin</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="status in statuses" :key="status" class="border-t">
                                    <td class="py-2.5 pr-4 font-medium text-gray-700">{{ statusLabel(status) }}</td>
                                    <td class="py-2.5 pr-4 text-center">
                                        <input type="checkbox" v-model="form['email_toggle_status_' + status + '_customer']">
                                    </td>
                                    <td class="py-2.5 pr-4 text-center">
                                        <input type="checkbox" v-model="form['email_toggle_status_' + status + '_admin']">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Follow-up + Inquiry -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Follow-up &amp; Inquiry Notifications</h2>
                    <div class="flex flex-col gap-2">
                        <label class="toggle-row">
                            <input type="checkbox" v-model="form.email_toggle_followup_customer">
                            <span>Notify customer when staff log a booking follow-up</span>
                        </label>
                        <label class="toggle-row">
                            <input type="checkbox" v-model="form.email_toggle_new_inquiry_customer">
                            <span>Send confirmation to customer on new contact/inquiry submission</span>
                        </label>
                        <label class="toggle-row">
                            <input type="checkbox" v-model="form.email_toggle_new_inquiry_admin">
                            <span>Notify admin on new contact/inquiry submission</span>
                        </label>
                    </div>
                    <p class="text-xs text-gray-400">
                        Admin recipient addresses are configured on the
                        <a :href="route('admin.website-settings.mail.edit')" class="text-blue-600 hover:underline">Email SMTP Settings</a>
                        page ("Admin Receiver Emails").
                    </p>
                </section>

                <!-- Template Content -->
                <div>
                    <h2 class="text-base font-semibold text-gray-800 mb-1">Email Template Content</h2>
                    <p class="text-xs text-gray-400 mb-4">
                        Edit the static header, intro and footer wording for each template. Leave a field
                        blank to keep using the default text shown as its placeholder.
                    </p>
                </div>

                <!-- Booking Confirmation -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h3 class="text-sm font-semibold text-gray-700 border-b pb-2">Booking Confirmation (to Customer)</h3>
                    <p class="text-xs text-gray-400 -mt-2">Sent when the customer's booking email toggle above is enabled.</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Header Title</label>
                            <input v-model="form.email_tpl_bconf_header_title" type="text" class="input" placeholder="Booking Request Received">
                        </div>
                        <div>
                            <label class="label">Header Subtitle</label>
                            <input v-model="form.email_tpl_bconf_header_subtitle" type="text" class="input" placeholder="Cox's Bazar, Bangladesh">
                        </div>
                        <div class="col-span-2">
                            <label class="label">Intro Text</label>
                            <textarea v-model="form.email_tpl_bconf_intro_text" rows="3" class="input resize-y"
                                placeholder="Thank you for choosing Hotel Beach Way! ..."></textarea>
                        </div>
                        <div class="col-span-2">
                            <label class="label">Footer Address Line</label>
                            <input v-model="form.email_tpl_bconf_footer_text" type="text" class="input" placeholder="Near Kolatoli Beach, Cox's Bazar, Bangladesh">
                        </div>
                    </div>
                </section>

                <!-- Booking Notification (Admin) -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h3 class="text-sm font-semibold text-gray-700 border-b pb-2">Booking Notification (to Admin)</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Header Title</label>
                            <input v-model="form.email_tpl_bnotify_header_title" type="text" class="input" placeholder="New Booking Request">
                        </div>
                        <div>
                            <label class="label">Header Subtitle</label>
                            <input v-model="form.email_tpl_bnotify_header_subtitle" type="text" class="input" placeholder="Submitted via Hotel Beach Way website">
                        </div>
                        <div class="col-span-2">
                            <label class="label">Footer Text</label>
                            <input v-model="form.email_tpl_bnotify_footer_text" type="text" class="input" placeholder="This notification was sent to the Hotel Beach Way admin team.">
                        </div>
                    </div>
                </section>

                <!-- Follow-up -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h3 class="text-sm font-semibold text-gray-700 border-b pb-2">Booking Follow-up (to Customer)</h3>
                    <p class="text-xs text-gray-400 -mt-2">Header title is always the follow-up subject staff enter when logging it.</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Header Subtitle</label>
                            <input v-model="form.email_tpl_bfollowup_header_subtitle" type="text" class="input" placeholder="Cox's Bazar, Bangladesh">
                        </div>
                        <div>
                            <label class="label">Footer Address Line</label>
                            <input v-model="form.email_tpl_bfollowup_footer_text" type="text" class="input" placeholder="Near Kolatoli Beach, Cox's Bazar, Bangladesh">
                        </div>
                    </div>
                </section>

                <!-- Booking Status Update -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-5">
                    <h3 class="text-sm font-semibold text-gray-700 border-b pb-2">Booking Status Update (to Customer)</h3>

                    <div>
                        <label class="label">Shared Footer Address Line</label>
                        <input v-model="form.email_tpl_bstatus_footer_text" type="text" class="input" placeholder="Near Kolatoli Beach, Cox's Bazar, Bangladesh">
                    </div>

                    <div v-for="status in statuses" :key="status" class="border-t pt-4">
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">{{ statusLabel(status) }}</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="label">Title</label>
                                <input v-model="form['email_tpl_bstatus_' + status + '_title']" type="text" class="input">
                            </div>
                            <div class="col-span-2">
                                <label class="label">Body Text</label>
                                <textarea v-model="form['email_tpl_bstatus_' + status + '_body']" rows="2" class="input resize-y"></textarea>
                                <p class="text-xs text-gray-400 mt-1">Basic HTML like &lt;strong&gt; is allowed.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="flex justify-end">
                    <button type="submit" :disabled="form.processing"
                        class="px-6 py-2.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-60">
                        {{ form.processing ? 'Saving...' : 'Save Settings' }}
                    </button>
                </div>

            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';

const props = defineProps({
    settings: { type: Object, default: () => ({}) },
    statusStatuses: { type: Array, default: () => ['confirmed', 'payment_pending', 'checked_in', 'checked_out', 'cancelled'] },
});

const statuses = props.statusStatuses;

const labels = {
    confirmed: 'Confirmed',
    payment_pending: 'Payment Pending',
    checked_in: 'Checked In',
    checked_out: 'Checked Out',
    cancelled: 'Cancelled',
};

function statusLabel(status) {
    return labels[status] ?? status;
}

const form = useForm({ ...props.settings });

function submit() {
    form.transform(data => ({ ...data, _method: 'PUT' }))
        .post(route('admin.website-settings.email-notifications.update'));
}
</script>

<style scoped>
.label { @apply block text-sm text-gray-600 mb-1; }
.input { @apply w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none; }
.toggle-row { @apply flex items-center gap-2.5 text-sm text-gray-700 cursor-pointer; }
.toggle-row input[type="checkbox"] { @apply w-4 h-4; }
</style>
