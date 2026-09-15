<template>
    <div>
        <!-- Drop zone -->
        <div
            @dragenter.prevent="isDragging = true"
            @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false"
            @drop.prevent="onDrop"
            @click="fileInput.click()"
            :class="[
                'relative cursor-pointer rounded-xl border-2 border-dashed transition-all duration-200 overflow-hidden select-none',
                isDragging
                    ? 'border-blue-500 bg-blue-50 scale-[1.01]'
                    : hasPreview
                        ? 'border-gray-200 hover:border-blue-400'
                        : 'border-gray-300 bg-gray-50 hover:border-blue-400 hover:bg-blue-50/40'
            ]"
        >
            <!-- Single-file: show preview when available -->
            <template v-if="!multiple">
                <div v-if="hasPreview" class="relative group">
                    <div v-if="isPdf" class="w-full h-44 bg-blue-50/60 border border-blue-100 flex flex-col items-center justify-center gap-2 p-4 text-center">
                        <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center font-bold text-xs uppercase shadow-sm">
                            PDF
                        </div>
                        <span class="text-xs text-gray-700 font-medium truncate max-w-full px-2">{{ selectedFileName || 'Document PDF' }}</span>
                    </div>
                    <img v-else :src="previewUrl" :class="previewClass" />
                    <!-- Remove button -->
                    <button
                        type="button"
                        @click.stop="clearImage"
                        title="Remove file"
                        class="absolute top-2 right-2 z-10 w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-md transition-colors"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <!-- Hover Actions Overlay -->
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-2 rounded-[10px]">
                        <button
                            type="button"
                            @click.stop="showModal = true"
                            class="px-3 py-1.5 bg-white text-gray-900 text-xs font-semibold rounded-lg shadow hover:bg-gray-100 flex items-center gap-1"
                        >
                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Popup View
                        </button>
                        <span class="text-white text-[11px]">Click background to replace</span>
                    </div>
                </div>
                <div v-else class="py-9 flex flex-col items-center gap-2.5 px-4 text-center">
                    <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">
                            <span class="text-blue-600 font-semibold">Click to browse</span>
                            <span class="text-gray-400"> or drag &amp; drop</span>
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ hint }}</p>
                    </div>
                </div>
            </template>

            <!-- Multiple-file: always show drop zone (parent manages previews) -->
            <template v-else>
                <div class="py-9 flex flex-col items-center gap-2.5 px-4 text-center">
                    <div class="w-14 h-14 rounded-full bg-blue-50 flex items-center justify-center">
                        <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">
                            <span class="text-blue-600 font-semibold">Click to browse</span>
                            <span class="text-gray-400"> or drag &amp; drop</span>
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ hint }} — multiple files allowed</p>
                    </div>
                </div>
            </template>

            <!-- Drag-over overlay -->
            <div v-if="isDragging"
                class="absolute inset-0 flex flex-col items-center justify-center gap-2 bg-blue-50/90 rounded-[10px] pointer-events-none">
                <svg class="w-10 h-10 text-blue-500 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                <p class="text-blue-600 font-semibold text-sm">Drop to upload</p>
            </div>
        </div>

        <!-- Hidden native input -->
        <input
            ref="fileInput"
            type="file"
            class="hidden"
            :multiple="multiple"
            :accept="accept"
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
                            <h3 class="font-semibold text-sm text-gray-800 truncate" :title="selectedFileName || 'Document Preview'">{{ selectedFileName || 'Document Preview' }}</h3>
                        </div>
                        <div class="flex items-center gap-3">
                            <a v-if="previewUrl" :href="previewUrl" target="_blank" class="px-3 py-1.5 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors inline-flex items-center gap-1">
                                Open in New Tab ↗
                            </a>
                            <button type="button" class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-600 flex items-center justify-center font-bold transition-colors" @click="showModal = false">
                                ✕
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="flex-1 overflow-auto p-4 flex items-center justify-center bg-gray-900 min-h-[450px]">
                        <iframe v-if="isPdf" :src="previewUrl" class="w-full h-[72vh] rounded-lg border-0 bg-white"></iframe>
                        <img v-else :src="previewUrl" alt="Document Preview" class="max-w-full max-h-[75vh] object-contain rounded-lg shadow-xl" />
                    </div>
                </div>
            </div>
        </Teleport>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    multiple:        { type: Boolean, default: false },
    accept:          { type: String,  default: 'image/*,application/pdf,.pdf,.jpg,.jpeg,.png,.webp' },
    existingPreview: { type: String,  default: null },
    previewClass:    { type: String,  default: 'w-full h-44 object-cover' },
    hint:            { type: String,  default: 'PDF, JPG, PNG, WebP — max 5 MB' },
});

const emit = defineEmits(['change', 'remove']);

const fileInput        = ref(null);
const isDragging       = ref(false);
const localPreview     = ref(null);
const selectedFileName = ref('');
const cleared          = ref(false);
const showModal        = ref(false);
const realFile         = ref(null);

const currentPreview = computed(() => cleared.value ? null : (localPreview.value || props.existingPreview));
const hasPreview     = computed(() => !!currentPreview.value);

const previewUrl = computed(() => {
    if (realFile.value) {
        return URL.createObjectURL(realFile.value);
    }
    return props.existingPreview;
});

const isPdf = computed(() => {
    if (selectedFileName.value) {
        return selectedFileName.value.toLowerCase().endsWith('.pdf');
    }
    if (props.existingPreview) {
        return props.existingPreview.toLowerCase().includes('.pdf');
    }
    return false;
});

function clearImage(e) {
    e?.stopPropagation();
    localPreview.value = null;
    selectedFileName.value = '';
    realFile.value = null;
    cleared.value = true;
    if (fileInput.value) fileInput.value.value = '';
    emit('change', null);
    emit('remove');
}

function processFiles(fileList) {
    if (!fileList || fileList.length === 0) return;

    cleared.value = false;

    if (props.multiple) {
        emit('change', Array.from(fileList));
    } else {
        const file = fileList[0];
        realFile.value = file;
        selectedFileName.value = file.name;
        if (file.type === 'application/pdf' || file.name.endsWith('.pdf')) {
            localPreview.value = 'pdf_file';
        } else {
            localPreview.value = URL.createObjectURL(file);
        }
        emit('change', file);
    }

    if (fileInput.value) fileInput.value.value = '';
}

function onDrop(e) {
    isDragging.value = false;
    processFiles(e.dataTransfer.files);
}

function onInputChange(e) {
    processFiles(e.target.files);
}
</script>
