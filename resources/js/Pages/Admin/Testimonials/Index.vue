<template>
    <AdminLayout>
        <div class="max-w-5xl space-y-6">
            <h1 class="text-lg font-semibold text-gray-800">Testimonials Management</h1>

            <!-- Flash -->
            <div v-if="$page.props.flash?.success"
                class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded">
                {{ $page.props.flash.success }}
            </div>

            <!-- ── Section Settings ── -->
            <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Section Settings</h2>
                <form @submit.prevent="saveSettings" enctype="multipart/form-data" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Badge Text</label>
                            <input v-model="settingsForm.testi_badge" type="text" class="input" placeholder="TESTIMONIALS" />
                        </div>
                        <div>
                            <label class="label">Section Title</label>
                            <input v-model="settingsForm.testi_title" type="text" class="input"
                                placeholder="Hear From Our Satisfied Customers" />
                        </div>
                        <div class="col-span-2 grid grid-cols-2 gap-4">
                            <div>
                                <label class="label">Left Side Image</label>
                                <DropZone @change="onImgChange" hint="JPEG / PNG / WebP — max 5 MB" preview-class="w-full h-44 object-cover"
                                    :existing-preview="settings.testi_image ? '/storage/' + settings.testi_image : null" />
                            </div>
                            <div>
                                <label class="label">Image Alt Text</label>
                                <input v-model="settingsForm.testi_image_alt" type="text" class="input"
                                    placeholder="Happy guests at Hotel Beach Way" />
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" :disabled="settingsForm.processing"
                            class="px-5 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-60">
                            {{ settingsForm.processing ? 'Saving...' : 'Save Settings' }}
                        </button>
                    </div>
                </form>
            </section>

            <!-- ── Add Testimonial ── -->
            <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">
                    {{ editingId ? 'Edit Testimonial' : 'Add New Testimonial' }}
                </h2>
                <form @submit.prevent="submitTestimonial" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Review -->
                        <div class="col-span-2">
                            <label class="label">Review Text <span class="text-red-500">*</span></label>
                            <textarea v-model="form.review" rows="3" class="input resize-none"
                                placeholder='"Very clean place — staffs are really nice..."'></textarea>
                            <InputError :message="form.errors.review" />
                        </div>
                        <!-- Name -->
                        <div>
                            <label class="label">Reviewer Name <span class="text-red-500">*</span></label>
                            <input v-model="form.name" type="text" class="input" placeholder="Shaheda" />
                            <InputError :message="form.errors.name" />
                        </div>
                        <!-- Role -->
                        <div>
                            <label class="label">Role / Location</label>
                            <input v-model="form.role" type="text" class="input"
                                placeholder="Leisure Trip, United Kingdom" />
                            <InputError :message="form.errors.role" />
                        </div>
                        <!-- Rating -->
                        <div>
                            <label class="label">Rating (1–5) <span class="text-red-500">*</span></label>
                            <div class="flex items-center gap-3">
                                <input v-model.number="form.rating" type="range" min="1" max="5" step="0.1"
                                    class="flex-1 h-2 rounded-lg accent-yellow-500" />
                                <div class="flex items-center gap-1 w-16">
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-gray-700">{{ form.rating.toFixed(1) }}</span>
                                </div>
                            </div>
                            <InputError :message="form.errors.rating" />
                        </div>
                        <!-- Sort + active -->
                        <div class="flex items-end gap-4">
                            <div class="flex-1">
                                <label class="label">Sort Order</label>
                                <input v-model.number="form.sort_order" type="number" min="0" class="input" />
                            </div>
                            <label class="flex items-center gap-2 cursor-pointer pb-2">
                                <input v-model="form.is_active" type="checkbox" class="w-4 h-4 rounded text-blue-600" />
                                <span class="text-sm text-gray-600">Active</span>
                            </label>
                        </div>
                        <!-- Avatar -->
                        <div class="col-span-2">
                            <label class="label">Avatar Photo</label>
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    <div v-if="editingId && form.existingAvatar"
                                        class="w-16 h-16 rounded-full overflow-hidden border-2 border-gray-200">
                                        <img :src="`/storage/${form.existingAvatar}`"
                                            class="w-full h-full object-cover" />
                                    </div>
                                    <div v-else
                                        class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xl">
                                        {{ form.name ? form.name[0].toUpperCase() : '?' }}
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <DropZone @change="onAvatarChange" hint="JPG, PNG — if no photo, initials will be shown" preview-class="w-full h-32 object-cover" />
                                    <InputError :message="form.errors.avatar" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 justify-end">
                        <button v-if="editingId" type="button" @click="resetForm"
                            class="px-4 py-2 text-sm text-gray-600 border rounded hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" :disabled="form.processing"
                            class="px-6 py-2.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-60">
                            {{ form.processing ? 'Saving...' : (editingId ? 'Update Testimonial' : 'Add Testimonial') }}
                        </button>
                    </div>
                </form>
            </section>

            <!-- ── Testimonials List ── -->
            <section class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-700">All Testimonials ({{ testimonials.length }})</h2>
                </div>
                <div v-if="!testimonials.length" class="px-6 py-10 text-center text-gray-400 text-sm">
                    No testimonials yet — add one above.
                </div>
                <div v-else class="divide-y divide-gray-50">
                    <div v-for="(t, i) in testimonials" :key="t.id"
                        class="px-6 py-4 flex items-start gap-4 hover:bg-gray-50 transition-colors">
                        <!-- Avatar -->
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full overflow-hidden border border-gray-200">
                                <img v-if="t.avatar" :src="`/storage/${t.avatar}`"
                                    class="w-full h-full object-cover" :alt="t.name" />
                                <div v-else
                                    class="w-full h-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-lg">
                                    {{ t.name[0].toUpperCase() }}
                                </div>
                            </div>
                        </div>
                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-semibold text-gray-800 text-sm">{{ t.name }}</span>
                                <span class="text-xs text-gray-400">{{ t.role }}</span>
                                <!-- Stars -->
                                <div class="flex items-center gap-0.5 ml-auto">
                                    <svg v-for="n in 5" :key="n" class="w-3 h-3"
                                        :class="n <= Math.round(t.rating) ? 'text-yellow-400' : 'text-gray-200'"
                                        fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    <span class="text-xs text-gray-500 ml-1">{{ t.rating.toFixed(1) }}</span>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 italic line-clamp-2">"{{ t.review }}"</p>
                        </div>
                        <!-- Actions -->
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <button @click="toggleStatus(t)"
                                :class="t.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'"
                                class="px-2 py-0.5 rounded-full text-xs font-semibold">
                                {{ t.is_active ? 'Active' : 'Inactive' }}
                            </button>
                            <button @click="startEdit(t)"
                                class="px-3 py-1.5 bg-indigo-600 text-white text-xs rounded hover:bg-indigo-700">
                                Edit
                            </button>
                            <button @click="confirmDelete(t)"
                                class="px-3 py-1.5 bg-red-600 text-white text-xs rounded hover:bg-red-700">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Delete modal -->
        <div v-if="deleteTarget" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-80 shadow-xl">
                <h3 class="font-semibold text-gray-800 mb-2">Delete Testimonial</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Remove review by <strong>{{ deleteTarget.name }}</strong>? Cannot be undone.
                </p>
                <div class="flex justify-end gap-2">
                    <button @click="deleteTarget = null"
                        class="px-4 py-2 text-sm text-gray-600 border rounded hover:bg-gray-50">Cancel</button>
                    <button @click="doDelete"
                        class="px-4 py-2 text-sm bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import InputError  from '@/Components/InputError.vue';
import DropZone from '@/Components/Admin/Shared/DropZone.vue';

const props = defineProps({
    testimonials: { type: Array,  default: () => [] },
    settings:     { type: Object, default: () => ({}) },
});

// ── Section settings ──
const settingsForm = useForm({
    testi_badge:     props.settings.testi_badge     ?? 'TESTIMONIALS',
    testi_title:     props.settings.testi_title     ?? 'Hear From Our Satisfied Customers',
    testi_image:     null,
    testi_image_alt: props.settings.testi_image_alt ?? 'Happy guests at Hotel Beach Way',
});

function onImgChange(file) {
    if (!file) return;
    settingsForm.testi_image = file;
}

function saveSettings() {
    settingsForm.post(route('admin.testimonials.settings'), { forceFormData: true });
}

// ── Add / Edit form ──
const editingId    = ref(null);

const form = useForm({
    review:         '',
    name:           '',
    role:           '',
    avatar:         null,
    existingAvatar: null,
    rating:         5.0,
    sort_order:     0,
    is_active:      true,
});

function onAvatarChange(file) {
    if (!file) return;
    form.avatar = file;
}

function startEdit(t) {
    editingId.value = t.id;
    form.review         = t.review;
    form.name           = t.name;
    form.role           = t.role ?? '';
    form.avatar         = null;
    form.existingAvatar = t.avatar;
    form.rating         = t.rating;
    form.sort_order     = t.sort_order;
    form.is_active      = t.is_active;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function resetForm() {
    editingId.value = null;
    form.reset();
    form.rating = 5.0;
    form.is_active = true;
}

function submitTestimonial() {
    if (editingId.value) {
        form.transform(data => ({ ...data, _method: 'PUT' }))
            .post(route('admin.testimonials.update', editingId.value), {
                forceFormData: true,
                onSuccess: () => resetForm(),
            });
    } else {
        form.post(route('admin.testimonials.store'), {
            forceFormData: true,
            onSuccess: () => resetForm(),
        });
    }
}

// ── Status / Delete ──
function toggleStatus(t) {
    router.patch(route('admin.testimonials.toggle', t.id));
}

const deleteTarget = ref(null);
function confirmDelete(t) { deleteTarget.value = t; }
function doDelete() {
    router.delete(route('admin.testimonials.destroy', deleteTarget.value.id), {
        onFinish: () => { deleteTarget.value = null; },
    });
}
</script>

<style scoped>
.label { @apply block text-sm text-gray-600 mb-1; }
.input { @apply w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none; }
</style>
