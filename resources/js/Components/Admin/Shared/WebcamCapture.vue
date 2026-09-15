<template>
    <div>
        <div v-if="!capturedPreview" class="rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 p-4 text-center space-y-3">
            <video v-if="streaming" ref="videoEl" autoplay playsinline class="w-full max-h-64 rounded-lg bg-black mx-auto"></video>
            <p v-else class="text-sm text-gray-500">Camera is off.</p>
            <p v-if="error" class="text-xs text-red-500">{{ error }}</p>
            <div class="flex justify-center gap-2">
                <button v-if="!streaming" type="button" @click="startCamera"
                    class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                    Turn On Camera
                </button>
                <button v-else type="button" @click="capture"
                    class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                    Capture Photo
                </button>
                <button v-if="streaming" type="button" @click="stopCamera"
                    class="px-4 py-2 text-sm border border-gray-300 rounded text-gray-600 hover:bg-gray-50">
                    Cancel
                </button>
            </div>
        </div>

        <div v-else class="relative">
            <img :src="capturedPreview" class="w-full max-h-64 object-cover rounded-lg border border-gray-200" />
            <button type="button" @click="retake"
                class="absolute top-2 right-2 w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-md">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <canvas ref="canvasEl" class="hidden"></canvas>
    </div>
</template>

<script setup>
import { ref, onBeforeUnmount } from 'vue';

const emit = defineEmits(['capture', 'clear']);

const videoEl  = ref(null);
const canvasEl = ref(null);
const streaming = ref(false);
const capturedPreview = ref(null);
const error = ref('');
let mediaStream = null;

async function startCamera() {
    error.value = '';
    try {
        mediaStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
        streaming.value = true;
        await new Promise(resolve => setTimeout(resolve, 0)); // let the <video> render
        if (videoEl.value) videoEl.value.srcObject = mediaStream;
    } catch (e) {
        error.value = 'Could not access the camera. Check browser permissions, or upload a photo instead.';
    }
}

function stopCamera() {
    mediaStream?.getTracks().forEach(track => track.stop());
    mediaStream = null;
    streaming.value = false;
}

function capture() {
    const video  = videoEl.value;
    const canvas = canvasEl.value;
    if (!video || !canvas) return;

    canvas.width  = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);

    canvas.toBlob(blob => {
        const file = new File([blob], `guest-photo-${Date.now()}.jpg`, { type: 'image/jpeg' });
        capturedPreview.value = URL.createObjectURL(blob);
        emit('capture', file);
        stopCamera();
    }, 'image/jpeg', 0.9);
}

function retake() {
    capturedPreview.value = null;
    emit('clear');
}

onBeforeUnmount(stopCamera);
</script>
