<template>
    <AdminLayout>
        <div class="max-w-3xl space-y-4">
            <h1 class="text-lg font-semibold text-gray-800">Email / SMTP Settings</h1>

            <div v-if="$page.props.flash?.success"
                class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded">
                {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash?.error"
                class="px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded">
                {{ $page.props.flash.error }}
            </div>

            <form @submit.prevent="submit" class="space-y-5">

                <!-- ── Status Toggle ── -->
                <section class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-semibold text-gray-700">Custom SMTP Configuration</h2>
                            <p class="text-xs text-gray-400 mt-0.5">When enabled, all emails will use these settings instead of the .env defaults.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input v-model="form.mail_enabled" type="checkbox" class="sr-only peer" />
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer
                                peer-checked:bg-blue-600 after:content-[''] after:absolute after:top-[2px]
                                after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5
                                after:transition-all peer-checked:after:translate-x-full"></div>
                        </label>
                    </div>
                </section>

                <!-- ── SMTP Server ── -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">SMTP Server</h2>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Mail Driver</label>
                            <select v-model="form.mail_driver" class="input">
                                <option value="smtp">SMTP</option>
                                <option value="sendmail">Sendmail</option>
                                <option value="log">Log (testing only)</option>
                            </select>
                            <InputError :message="form.errors.mail_driver" />
                        </div>
                        <div>
                            <label class="label">Encryption</label>
                            <select v-model="form.mail_encryption" class="input">
                                <option value="tls">TLS (port 587)</option>
                                <option value="ssl">SSL (port 465)</option>
                                <option value="starttls">STARTTLS</option>
                                <option value="">None</option>
                            </select>
                            <InputError :message="form.errors.mail_encryption" />
                        </div>
                        <div>
                            <label class="label">SMTP Host</label>
                            <input v-model="form.mail_host" type="text" class="input"
                                placeholder="smtp.gmail.com" />
                            <InputError :message="form.errors.mail_host" />
                        </div>
                        <div>
                            <label class="label">SMTP Port</label>
                            <input v-model.number="form.mail_port" type="number" class="input"
                                placeholder="587" />
                            <InputError :message="form.errors.mail_port" />
                        </div>
                        <div>
                            <label class="label">SMTP Username</label>
                            <input v-model="form.mail_username" type="text" class="input"
                                placeholder="your@email.com" autocomplete="off" />
                            <InputError :message="form.errors.mail_username" />
                        </div>
                        <div>
                            <label class="label">SMTP Password</label>
                            <div class="relative">
                                <input v-model="form.mail_password" :type="showPassword ? 'text' : 'password'"
                                    class="input pr-10" placeholder="Leave blank to keep existing"
                                    autocomplete="new-password" />
                                <button type="button" @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 px-3 text-gray-400 hover:text-gray-600 text-xs">
                                    {{ showPassword ? 'Hide' : 'Show' }}
                                </button>
                            </div>
                            <InputError :message="form.errors.mail_password" />
                        </div>
                    </div>

                    <!-- Provider presets -->
                    <div>
                        <p class="text-xs text-gray-500 mb-2">Quick Presets:</p>
                        <div class="flex flex-wrap gap-2">
                            <button v-for="p in presets" :key="p.name" type="button"
                                @click="applyPreset(p)"
                                class="px-3 py-1 text-xs border rounded hover:bg-blue-50 hover:border-blue-300 text-gray-600 transition-colors">
                                {{ p.name }}
                            </button>
                        </div>
                    </div>
                </section>

                <!-- ── Sender ── -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Sender Identity</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">From Email Address</label>
                            <input v-model="form.mail_from_address" type="email" class="input"
                                placeholder="noreply@hotelbeachway.com" />
                            <InputError :message="form.errors.mail_from_address" />
                        </div>
                        <div>
                            <label class="label">From Name</label>
                            <input v-model="form.mail_from_name" type="text" class="input"
                                placeholder="Hotel Beach Way" />
                            <InputError :message="form.errors.mail_from_name" />
                        </div>
                    </div>
                </section>

                <!-- ── Admin Receiver Emails ── -->
                <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <div class="flex items-center justify-between border-b pb-2">
                        <div>
                            <h2 class="text-sm font-semibold text-gray-700">Admin Receiver Emails</h2>
                            <p class="text-xs text-gray-400 mt-0.5">These addresses receive booking alerts, inquiries, and system notifications.</p>
                        </div>
                        <button type="button" @click="addAdminEmail"
                            class="px-3 py-1.5 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                            + Add Email
                        </button>
                    </div>

                    <div v-if="!form.mail_admin_emails.length" class="text-sm text-gray-400 py-2 text-center">
                        No admin emails added yet.
                    </div>

                    <div v-for="(email, i) in form.mail_admin_emails" :key="i" class="flex gap-2">
                        <input v-model="form.mail_admin_emails[i]" type="email" class="input"
                            :placeholder="`admin${i + 1}@hotelbeachway.com`" />
                        <button type="button" @click="removeAdminEmail(i)"
                            class="px-3 py-2 text-red-500 hover:bg-red-50 rounded text-sm flex-shrink-0">✕</button>
                    </div>

                    <InputError :message="form.errors['mail_admin_emails']" />
                </section>

                <!-- ── Save ── -->
                <div class="flex justify-end">
                    <button type="submit" :disabled="form.processing"
                        class="px-6 py-2.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-60">
                        {{ form.processing ? 'Saving...' : 'Save Mail Settings' }}
                    </button>
                </div>
            </form>

            <!-- ── Test Email ── -->
            <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Send Test Email</h2>
                <p class="text-xs text-gray-500">Send a test email using the current saved settings to verify your SMTP configuration.</p>
                <div class="flex gap-3">
                    <input v-model="testEmail" type="email" class="input"
                        placeholder="test@example.com" />
                    <button type="button" @click="sendTest" :disabled="testForm.processing"
                        class="px-5 py-2 bg-emerald-600 text-white text-sm rounded hover:bg-emerald-700 disabled:opacity-60 whitespace-nowrap flex-shrink-0">
                        {{ testForm.processing ? 'Sending...' : 'Send Test' }}
                    </button>
                </div>
            </section>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import InputError   from '@/Components/InputError.vue';

const props = defineProps({
    settings: { type: Object, required: true },
});

const s = props.settings;

const showPassword = ref(false);
const testEmail    = ref('');

const form = useForm({
    mail_enabled:       s.mail_enabled === '1',
    mail_driver:        s.mail_driver        ?? 'smtp',
    mail_host:          s.mail_host          ?? '',
    mail_port:          s.mail_port          ? parseInt(s.mail_port) : 587,
    mail_encryption:    s.mail_encryption    ?? 'tls',
    mail_username:      s.mail_username      ?? '',
    mail_password:      '',
    mail_from_address:  s.mail_from_address  ?? '',
    mail_from_name:     s.mail_from_name     ?? 'Hotel Beach Way',
    mail_admin_emails:  Array.isArray(s.mail_admin_emails) ? [...s.mail_admin_emails] : [],
});

const testForm = useForm({ test_email: '' });

const presets = [
    { name: 'Gmail',     host: 'smtp.gmail.com',      port: 587, encryption: 'tls' },
    { name: 'Outlook',   host: 'smtp.office365.com',  port: 587, encryption: 'tls' },
    { name: 'Yahoo',     host: 'smtp.mail.yahoo.com', port: 587, encryption: 'tls' },
    { name: 'Mailgun',   host: 'smtp.mailgun.org',    port: 587, encryption: 'tls' },
    { name: 'SendGrid',  host: 'smtp.sendgrid.net',   port: 587, encryption: 'tls' },
    { name: 'Mailtrap',  host: 'sandbox.smtp.mailtrap.io', port: 2525, encryption: 'tls' },
];

function applyPreset(p) {
    form.mail_host       = p.host;
    form.mail_port       = p.port;
    form.mail_encryption = p.encryption;
}

function addAdminEmail()      { form.mail_admin_emails.push(''); }
function removeAdminEmail(i)  { form.mail_admin_emails.splice(i, 1); }

function submit() {
    form.post(route('admin.website-settings.mail.update'));
}

function sendTest() {
    if (!testEmail.value) return;
    testForm.test_email = testEmail.value;
    testForm.post(route('admin.website-settings.mail.test'));
}
</script>

<style scoped>
.label { @apply block text-sm text-gray-600 mb-1; }
.input { @apply w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none; }
</style>
