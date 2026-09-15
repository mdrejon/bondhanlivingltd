<template>
    <div class="space-y-2">
        <!-- Document Card / Upload Box -->
        <div
            @dragenter.prevent="isDragging = true"
            @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false"
            @drop.prevent="onDrop"
            :class="[
                'relative rounded-xl border-2 transition-all duration-200 overflow-hidden select-none',
                isDragging
                    ? 'border-blue-500 bg-blue-50 scale-[1.01]'
                    : hasFile
                        ? 'border-emerald-300 bg-emerald-50/20 shadow-sm'
                        : 'border-dashed border-gray-300 bg-gray-50 hover:border-blue-400 hover:bg-blue-50/40 cursor-pointer'
            ]"
            @click="!hasFile && triggerBrowse()"
        >
            <!-- File Present: Image or PDF Preview -->
            <div v-if="hasFile" class="p-3">
                <!-- IMAGE PREVIEW -->
                <div v-if="!isPdf" class="space-y-2">
                    <div class="relative group rounded-lg overflow-hidden border border-gray-200 bg-gray-900 max-h-40 flex items-center justify-center">
                        <img :src="fileUrl" alt="Document preview" class="w-full h-36 object-contain" />
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                            <button
                                type="button"
                                @click.stop="openPopupView"
                                class="px-3 py-1.5 bg-white text-gray-900 text-xs font-semibold rounded-lg shadow hover:bg-gray-100 flex items-center gap-1"
                            >
                                <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Popup View
                            </button>
                            <button
                                type="button"
                                @click.stop="triggerBrowse"
                                class="px-3 py-1.5 bg-blue-600 text-white text-xs font-semibold rounded-lg shadow hover:bg-blue-700"
                            >
                                Replace
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-xs pt-1">
                        <span class="font-medium text-gray-800 truncate pr-2" :title="fileName">{{ fileName }}</span>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <button type="button" @click.stop="openPopupView" class="text-blue-600 hover:underline font-medium text-xs">
                                Popup View
                            </button>
                            <button type="button" @click.stop="removeFile" class="text-red-500 hover:text-red-700 font-bold">
                                ✕ Remove
                            </button>
                        </div>
                    </div>
                </div>

                <!-- PDF PREVIEW -->
                <div v-else class="space-y-2">
                    <div class="p-3 bg-red-50/70 border border-red-200 rounded-lg flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 overflow-hidden">
                            <div class="w-10 h-10 rounded-lg bg-red-600 text-white flex items-center justify-center font-bold text-xs shadow-sm flex-shrink-0">
                                PDF
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-gray-800 truncate" :title="fileName">{{ fileName }}</p>
                                <p class="text-[10px] text-gray-500">{{ fileSize ? fileSize : 'PDF Document' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <button
                                type="button"
                                @click.stop="openPopupView"
                                class="px-3 py-1.5 bg-red-600 text-white text-xs font-semibold rounded-lg hover:bg-red-700 transition-colors shadow-sm flex items-center gap-1"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Popup View
                            </button>
                            <button
                                type="button"
                                @click.stop="removeFile"
                                class="p-1.5 text-gray-400 hover:text-red-600 font-bold"
                                title="Remove file"
                            >
                                ✕
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty Drop Zone -->
            <div v-else class="py-6 px-4 flex flex-col items-center gap-2 text-center">
                <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-700">
                        <span class="text-blue-600 font-semibold">Click to browse</span> or drag &amp; drop
                    </p>
                    <p class="text-[10px] text-gray-400 mt-0.5">PDF, JPG, PNG, WebP (max 5MB)</p>
                </div>
            </div>

            <!-- Drag overlay -->
            <div v-if="isDragging" class="absolute inset-0 bg-blue-50/90 rounded-xl flex items-center justify-center pointer-events-none z-10">
                <span class="text-xs font-semibold text-blue-600">Drop file here</span>
            </div>
        </div>

        <input
            ref="fileInput"
            type="file"
            accept=".jpg,.jpeg,.png,.webp,.pdf,image/*,application/pdf"
            class="hidden"
            @change="onInputChange"
        />

        <!-- Popup View Modal -->
        <Teleport to="body">
            <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/75 p-4" @click.self="showModal = false">
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-4xl w-full max-h-[92vh] flex flex-col border border-gray-100">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between px-6 py-4 border-b bg-gray-50">
                        <div class="flex items-center gap-3 truncate">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider" :class="isPdf ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700'">
                                {{ isPdf ? 'PDF Document' : 'Image Preview' }}
                            </span>
                            <h3 class="font-semibold text-sm text-gray-800 truncate" :title="fileName">{{ fileName }}</h3>
                        </div>
                        <div class="flex items-center gap-3">
                            <a v-if="fileUrl" :href="fileUrl" target="_blank" class="px-3 py-1.5 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors inline-flex items-center gap-1">
                                Open in New Tab ↗
                            </a>
                            <button type="button" class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-600 flex items-center justify-center font-bold transition-colors" @click="showModal = false">
                                ✕
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="flex-1 overflow-auto p-4 flex items-center justify-center bg-gray-900 min-h-[450px]">
                        <iframe v-if="isPdf" :src="fileUrl" class="w-full h-[72vh] rounded-lg border-0 bg-white"></iframe>
                        <img v-else :src="fileUrl" alt="Document Preview" class="max-w-full max-h-[75vh] object-contain rounded-lg shadow-xl" />
                    </div>
                </div>
            </div>
        </Teleport>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    doc: { type: Object, default: null },
});

const emit = defineEmits(['change', 'remove']);

const fileInput    = ref(null);
const isDragging   = ref(false);
const selectedFile = ref(null);
const removedDoc   = ref(false);
const showModal    = ref(false);

const hasFile = computed(() => {
    if (selectedFile.value) return true;
    if (props.doc && !removedDoc.value) return true;
    return false;
});

const fileName = computed(() => {
    if (selectedFile.value) return selectedFile.value.name;
    if (props.doc && !removedDoc.value) return props.doc.original_filename || 'Saved Document';
    return '';
});

const fileSize = computed(() => {
    if (selectedFile.value) {
        return (selectedFile.value.size / 1024 / 1024).toFixed(2) + ' MB';
    }
    return '';
});

const fileUrl = computed(() => {
    if (selectedFile.value) {
        return URL.createObjectURL(selectedFile.value);
    }
    if (props.doc && !removedDoc.value) {
        return route('admin.documents.show', props.doc.id);
    }
    return '';
});

const isPdf = computed(() => {
    if (selectedFile.value) {
        return selectedFile.value.type === 'application/pdf' || selectedFile.value.name?.toLowerCase().endsWith('.pdf');
    }
    if (props.doc && !removedDoc.value) {
        const name = (props.doc.original_filename || '').toLowerCase();
        return props.doc.category === 'pdf' || name.endsWith('.pdf');
    }
    return false;
});

function triggerBrowse() {
    fileInput.value?.click();
}

function processFile(file) {
    if (!file) return;
    selectedFile.value = file;
    removedDoc.value = false;
    emit('change', file);
    if (fileInput.value) fileInput.value.value = '';
}

function onDrop(e) {
    isDragging.value = false;
    const file = e.dataTransfer.files[0];
    if (file) processFile(file);
}

function onInputChange(e) {
    const file = e.target.files[0];
    if (file) processFile(file);
}

function removeFile() {
    selectedFile.value = null;
    removedDoc.value = true;
    if (fileInput.value) fileInput.value.value = '';
    emit('change', null);
    emit('remove');
}

function openPopupView() {
    if (hasFile.value) {
        showModal.value = true;
    }
}
</script>
