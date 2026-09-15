<template>
    <AdminLayout>
        <div class="max-w-3xl space-y-6">
            <!-- Header -->
            <div class="flex items-center gap-4">
                <Link :href="route('admin.inquiries.index')" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><polyline points="15 18 9 12 15 6"/></svg>
                </Link>
                <div>
                    <h1 class="text-lg font-semibold text-gray-800">{{ inquiry.name }}</h1>
                    <p class="text-xs text-gray-400">{{ inquiry.email }} · {{ formatDateTime(inquiry.created_at) }}</p>
                </div>
                <div class="ml-auto flex items-center gap-2">
                    <span :class="typeBadge(inquiry.type)" class="px-2.5 py-1 rounded text-xs font-semibold">
                        {{ typeLabel(inquiry.type) }}
                    </span>
                    <span :class="statusBadge(inquiry.status)" class="px-2.5 py-1 rounded-full text-xs font-semibold">
                        {{ capitalize(inquiry.status) }}
                    </span>
                </div>
            </div>

            <!-- Flash -->
            <div v-if="$page.props.flash?.success"
                class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded">
                {{ $page.props.flash.success }}
            </div>

            <!-- Inquiry Details -->
            <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Inquiry Details</h2>
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-gray-400 text-xs mb-1">Full Name</dt>
                        <dd class="font-medium text-gray-800">{{ inquiry.name }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 text-xs mb-1">Email</dt>
                        <dd class="text-gray-800">
                            <a :href="`mailto:${inquiry.email}`" class="text-blue-600 hover:underline">{{ inquiry.email }}</a>
                        </dd>
                    </div>
                    <div v-if="inquiry.phone">
                        <dt class="text-gray-400 text-xs mb-1">Phone</dt>
                        <dd class="text-gray-800">{{ inquiry.phone }}</dd>
                    </div>
                    <div v-if="inquiry.subject">
                        <dt class="text-gray-400 text-xs mb-1">Subject</dt>
                        <dd class="text-gray-800">{{ inquiry.subject }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 text-xs mb-1">Source</dt>
                        <dd><span :class="typeBadge(inquiry.type)" class="px-2 py-0.5 rounded text-xs font-medium">{{ typeLabel(inquiry.type) }}</span></dd>
                    </div>
                    <div v-if="inquiry.ip_address">
                        <dt class="text-gray-400 text-xs mb-1">IP Address</dt>
                        <dd class="text-gray-500 font-mono text-xs">{{ inquiry.ip_address }}</dd>
                    </div>
                </dl>

                <div>
                    <dt class="text-gray-400 text-xs mb-2">Message</dt>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ inquiry.message }}</div>
                </div>
            </section>

            <!-- Status Management -->
            <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Manage Status</h2>

                <div class="flex flex-wrap gap-2">
                    <button v-for="s in statusOptions" :key="s.value"
                        @click="setStatus(s.value)"
                        :disabled="inquiry.status === s.value"
                        :class="[s.btnClass, inquiry.status === s.value ? 'opacity-50 cursor-not-allowed' : '']"
                        class="px-4 py-1.5 text-sm rounded font-medium transition-colors">
                        {{ s.label }}
                    </button>
                </div>

                <!-- Notes -->
                <div v-if="showNoteInput" class="flex gap-2 mt-2">
                    <textarea v-model="note" rows="2" placeholder="Add a reply note (optional)..."
                        class="input flex-1 resize-none text-sm"></textarea>
                    <div class="flex flex-col gap-2">
                        <button @click="submitStatus" :disabled="processing"
                            class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-60">
                            {{ processing ? '...' : 'Save' }}
                        </button>
                        <button @click="cancelStatus" class="px-3 py-2 text-gray-500 hover:text-gray-700 text-sm">Cancel</button>
                    </div>
                </div>

                <div v-if="inquiry.notes" class="mt-2">
                    <p class="text-xs text-gray-400 mb-1">Internal Notes</p>
                    <div class="bg-yellow-50 border border-yellow-200 rounded p-3 text-sm text-yellow-800 whitespace-pre-wrap">{{ inquiry.notes }}</div>
                </div>
            </section>

            <!-- Quick Actions -->
            <div class="flex items-center justify-between">
                <a :href="`mailto:${inquiry.email}?subject=Re: Your Inquiry to Hotel Beach Way`"
                    class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                    Reply via Email
                </a>
                <button @click="confirmDelete = true"
                    class="px-4 py-2 text-sm border border-red-300 text-red-500 rounded hover:bg-red-50">
                    Delete Inquiry
                </button>
            </div>
        </div>

        <!-- Delete Modal -->
        <div v-if="confirmDelete" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="bg-white rounded-lg shadow-xl p-6 max-w-sm w-full mx-4">
                <h3 class="text-base font-semibold text-gray-800 mb-2">Delete Inquiry</h3>
                <p class="text-sm text-gray-600 mb-4">Delete this inquiry from <strong>{{ inquiry.name }}</strong>? This cannot be undone.</p>
                <div class="flex gap-3 justify-end">
                    <button @click="confirmDelete = false"
                        class="px-4 py-2 text-sm border border-gray-300 rounded text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button @click="doDelete"
                        class="px-4 py-2 text-sm bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';

const props = defineProps({ inquiry: Object });

const confirmDelete = ref(false);
const showNoteInput = ref(false);
const note          = ref('');
const processing    = ref(false);
const pendingStatus = ref('');

const statusOptions = [
    { value: 'new',     label: 'Mark as New',     btnClass: 'bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100' },
    { value: 'read',    label: 'Mark as Read',     btnClass: 'bg-yellow-50 text-yellow-700 border border-yellow-200 hover:bg-yellow-100' },
    { value: 'replied', label: 'Mark as Replied',  btnClass: 'bg-green-600 text-white hover:bg-green-700' },
];

function setStatus(status) {
    pendingStatus.value = status;
    showNoteInput.value = true;
}

function cancelStatus() {
    showNoteInput.value = false;
    note.value = '';
    pendingStatus.value = '';
}

function submitStatus() {
    processing.value = true;
    router.patch(route('admin.inquiries.update-status', props.inquiry.id), {
        status: pendingStatus.value,
        notes:  note.value || undefined,
    }, {
        onFinish: () => {
            processing.value = false;
            showNoteInput.value = false;
            note.value = '';
            pendingStatus.value = '';
        },
    });
}

function doDelete() {
    router.delete(route('admin.inquiries.destroy', props.inquiry.id));
}

function formatDateTime(d) {
    if (!d) return '—';
    return new Date(d).toLocaleString('en-GB', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
}

function capitalize(s) {
    return s ? s.charAt(0).toUpperCase() + s.slice(1) : '';
}

function typeLabel(t) {
    return { quote: 'Get A Free Quote', contact_widget: 'Contact Widget', contact_page: 'Contact Page' }[t] ?? t;
}

function typeBadge(t) {
    return { quote: 'bg-blue-100 text-blue-700', contact_widget: 'bg-purple-100 text-purple-700', contact_page: 'bg-teal-100 text-teal-700' }[t] ?? 'bg-gray-100 text-gray-600';
}

function statusBadge(s) {
    return { new: 'bg-blue-100 text-blue-700', read: 'bg-yellow-100 text-yellow-700', replied: 'bg-green-100 text-green-700' }[s] ?? 'bg-gray-100 text-gray-600';
}
</script>

<style scoped>
.input { @apply w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors bg-white; }
</style>
