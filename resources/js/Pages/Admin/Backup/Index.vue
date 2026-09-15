<template>
    <AdminLayout>
        <Head title="Database Backup" />

        <div class="space-y-6">
            <!-- Flash messages -->
            <div v-if="$page.props.flash?.success"
                 class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash?.error"
                 class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                {{ $page.props.flash.error }}
            </div>

            <!-- Header -->
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Database Backup</h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Create, download and restore SQL backups of the database. Files are stored
                        in a private folder that is not accessible from the web. Restoring never
                        touches the backup log, and a safety backup is taken automatically first.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                <input ref="fileInput" type="file" accept=".sql" class="hidden" @change="onFileChosen" />
                <button
                    class="inline-flex items-center gap-2 bg-white hover:bg-amber-50 text-amber-700 border border-amber-300 text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors disabled:opacity-60 disabled:cursor-not-allowed"
                    :disabled="creating || uploading"
                    @click="fileInput.click()"
                >
                    <svg v-if="!uploading" class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 8l5-5 5 5M12 3v12"/>
                    </svg>
                    <svg v-else class="w-4 h-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                    </svg>
                    {{ uploading ? 'Restoring…' : 'Upload & Restore' }}
                </button>
                <button
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors disabled:opacity-60 disabled:cursor-not-allowed"
                    :disabled="creating || uploading"
                    @click="createBackup"
                >
                    <svg v-if="!creating" class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17l4 4 4-4m-4-5v9"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.88 18.09A5 5 0 0018 9h-1.26A8 8 0 103 16.29"/>
                    </svg>
                    <svg v-else class="w-4 h-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                    </svg>
                    {{ creating ? 'Creating Backup…' : 'Create Backup' }}
                </button>
                </div>
            </div>

            <!-- Backups table -->
            <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">#</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">File</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Size</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Created By</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Date</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(b, i) in backups" :key="b.id"
                            class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-gray-400">{{ i + 1 }}</td>
                            <td class="px-4 py-3">
                                <span class="font-mono text-xs text-gray-700">{{ b.filename }}</span>
                                <p v-if="b.status === 'failed' && b.error" class="text-xs text-red-500 mt-0.5 max-w-[360px] truncate" :title="b.error">
                                    {{ b.error }}
                                </p>
                            </td>
                            <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ humanSize(b.size) }}</td>
                            <td class="px-4 py-3">
                                <span v-if="b.status === 'completed'"
                                      class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    Completed
                                </span>
                                <span v-else
                                      class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                    Failed
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ b.created_by }}</td>
                            <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ b.created_at }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a v-if="b.exists"
                                       :href="route('admin.backups.download', b.id)"
                                       class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 text-xs font-semibold border border-blue-200 hover:border-blue-400 px-2.5 py-1.5 rounded transition-colors">
                                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                                        </svg>
                                        Download
                                    </a>
                                    <button v-if="b.exists"
                                        class="inline-flex items-center gap-1 text-amber-600 hover:text-amber-800 text-xs font-semibold border border-amber-200 hover:border-amber-400 px-2.5 py-1.5 rounded transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                        :disabled="restoringId !== null"
                                        @click="restoreBackup(b)"
                                    >
                                        <svg v-if="restoringId !== b.id" class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h5M20 20v-5h-5M4 9a8 8 0 0114.13-3.36M20 15A8 8 0 015.87 18.36"/>
                                        </svg>
                                        <svg v-else class="w-3.5 h-3.5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                                        </svg>
                                        {{ restoringId === b.id ? 'Restoring…' : 'Restore' }}
                                    </button>
                                    <button
                                        class="inline-flex items-center gap-1 text-red-600 hover:text-red-800 text-xs font-semibold border border-red-200 hover:border-red-400 px-2.5 py-1.5 rounded transition-colors disabled:opacity-50"
                                        :disabled="restoringId !== null"
                                        @click="deleteBackup(b)"
                                    >
                                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!backups.length">
                            <td colspan="7" class="px-4 py-10 text-center text-gray-400">
                                No backups yet. Click “Create Backup” to make your first one.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';

defineProps({
    backups: { type: Array, default: () => [] },
});

const creating    = ref(false);
const restoringId = ref(null);
const uploading   = ref(false);
const fileInput   = ref(null);

function onFileChosen(e) {
    const file = e.target.files[0];
    e.target.value = ''; // allow re-selecting the same file next time
    if (!file) return;

    if (!file.name.toLowerCase().endsWith('.sql')) {
        alert('Please choose a .sql backup file.');
        return;
    }

    const warning =
        `Restore the database from the uploaded file "${file.name}"?\n\n` +
        'The file will first be verified against the current database structure — ' +
        'if any table or column differs, nothing is changed.\n\n' +
        'If it matches, all current data will be OVERWRITTEN. A safety backup of ' +
        'the current state is created automatically first, and the backup log ' +
        'itself is never overwritten.\n\n' +
        'Do you want to continue?';
    if (!confirm(warning)) return;

    router.post(route('admin.backups.restore-upload'), { file }, {
        forceFormData: true,
        preserveScroll: true,
        onStart:  () => { uploading.value = true; },
        onFinish: () => { uploading.value = false; },
    });
}

function restoreBackup(b) {
    const warning =
        `Restore the database from "${b.filename}"?\n\n` +
        'This OVERWRITES all current data with the contents of this backup. ' +
        'A safety backup of the current state will be created automatically first, ' +
        'and the backup log itself is never overwritten.\n\n' +
        'Do you want to continue?';
    if (!confirm(warning)) return;

    router.post(route('admin.backups.restore', b.id), {}, {
        preserveScroll: true,
        onStart:  () => { restoringId.value = b.id; },
        onFinish: () => { restoringId.value = null; },
    });
}

function createBackup() {
    router.post(route('admin.backups.store'), {}, {
        preserveScroll: true,
        onStart:  () => { creating.value = true; },
        onFinish: () => { creating.value = false; },
    });
}

function deleteBackup(b) {
    if (!confirm(`Delete backup "${b.filename}"? This cannot be undone.`)) return;
    router.delete(route('admin.backups.destroy', b.id), { preserveScroll: true });
}

function humanSize(bytes) {
    if (!bytes) return '—';
    const units = ['B', 'KB', 'MB', 'GB'];
    let i = 0;
    let n = bytes;
    while (n >= 1024 && i < units.length - 1) { n /= 1024; i++; }
    return `${n.toFixed(n >= 10 || i === 0 ? 0 : 1)} ${units[i]}`;
}
</script>
