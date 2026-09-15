<template>
    <div class="space-y-6">

        <!-- Group settings -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">FAQ Group Settings</h2>

            <div class="grid grid-cols-2 gap-4">
                <!-- Page selector -->
                <div class="col-span-2 sm:col-span-1">
                    <label class="label">Page <span class="text-red-500">*</span></label>
                    <div class="flex gap-3">
                        <label v-for="p in pages" :key="p.value"
                            class="flex items-center gap-2 cursor-pointer px-4 py-2 border rounded-lg transition-colors"
                            :class="form.page === p.value
                                ? 'border-blue-500 bg-blue-50 text-blue-700'
                                : 'border-gray-200 text-gray-600 hover:border-gray-300'">
                            <input type="radio" v-model="form.page" :value="p.value" class="sr-only" />
                            <span class="w-2 h-2 rounded-full flex-shrink-0"
                                :class="form.page === p.value ? 'bg-blue-500' : 'bg-gray-300'"></span>
                            <span class="text-sm font-medium">{{ p.label }}</span>
                        </label>
                    </div>
                    <InputError :message="form.errors.page" />
                </div>

                <div>
                    <label class="label">Sort Order</label>
                    <input v-model.number="form.sort_order" type="number" min="0" class="input" />
                </div>

                <div>
                    <label class="label">Badge Text</label>
                    <input v-model="form.badge" type="text" class="input" placeholder="FAQ'S" />
                    <InputError :message="form.errors.badge" />
                </div>

                <div class="col-span-2">
                    <label class="label">Section Title</label>
                    <input v-model="form.title" type="text" class="input"
                        placeholder="e.g. Clear Answers To Your Questions" />
                    <InputError :message="form.errors.title" />
                </div>

                <div class="col-span-2">
                    <label class="label">
                        Section Description
                        <span class="text-xs text-gray-400 ml-1">(shown on about page below title)</span>
                    </label>
                    <textarea v-model="form.description" rows="2" class="input resize-none"
                        placeholder="Optional short description..."></textarea>
                    <InputError :message="form.errors.description" />
                </div>

                <!-- Side Image -->
                <div class="col-span-2 grid grid-cols-2 gap-4 pt-1 border-t border-gray-100">
                    <div>
                        <label class="label">Side Image</label>
                        <DropZone @change="onImageChange" hint="JPEG / PNG / WebP — max 5 MB" preview-class="w-full h-44 object-cover"
                            :existing-preview="existingImage ? '/storage/' + existingImage : null" />
                        <InputError :message="form.errors.image" />
                    </div>
                    <div>
                        <label class="label">Image Alt Text</label>
                        <input v-model="form.image_alt" type="text" class="input"
                            placeholder="e.g. FAQ section image" />
                        <InputError :message="form.errors.image_alt" />
                    </div>
                </div>

                <div class="col-span-2 flex items-center gap-2">
                    <input v-model="form.is_active" type="checkbox" id="is_active" class="w-4 h-4 rounded text-blue-600" />
                    <label for="is_active" class="text-sm text-gray-600 cursor-pointer">Active (visible on frontend)</label>
                </div>
            </div>
        </section>

        <!-- FAQ Items -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between border-b pb-2">
                <h2 class="text-sm font-semibold text-gray-700">
                    FAQ Items
                    <span class="ml-2 px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded-full">
                        {{ form.items.length }}
                    </span>
                </h2>
                <span class="text-xs text-gray-400">Stored as JSON in one column</span>
            </div>

            <InputError :message="form.errors.items" />

            <!-- Items list -->
            <div class="space-y-3">
                <div v-for="(item, i) in form.items" :key="i"
                    class="border border-gray-200 rounded-lg overflow-hidden">

                    <!-- Item header -->
                    <div class="flex items-center justify-between bg-gray-50 px-3 py-2 border-b border-gray-200">
                        <div class="flex items-center gap-2">
                            <span class="w-5 h-5 bg-blue-100 text-blue-700 text-xs rounded-full flex items-center justify-center font-semibold">
                                {{ i + 1 }}
                            </span>
                            <span class="text-xs font-medium text-gray-600 truncate max-w-xs">
                                {{ item.question || 'New FAQ Item' }}
                            </span>
                        </div>
                        <div class="flex items-center gap-1">
                            <button type="button" @click="moveUp(i)" :disabled="i === 0"
                                class="w-6 h-6 flex items-center justify-center text-gray-400 hover:text-gray-700 disabled:opacity-30 text-xs">↑</button>
                            <button type="button" @click="moveDown(i)" :disabled="i === form.items.length - 1"
                                class="w-6 h-6 flex items-center justify-center text-gray-400 hover:text-gray-700 disabled:opacity-30 text-xs">↓</button>
                            <button type="button" @click="removeItem(i)"
                                class="ml-1 w-6 h-6 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 rounded text-xs">✕</button>
                        </div>
                    </div>

                    <!-- Item fields -->
                    <div class="p-3 space-y-2">
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">Question</label>
                            <input v-model="form.items[i].question" type="text" class="input"
                                :placeholder="`e.g. Can I Check In Early?`" />
                            <InputError :message="form.errors[`items.${i}.question`]" />
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">Answer</label>
                            <textarea v-model="form.items[i].answer" rows="3" class="input resize-none"
                                placeholder="Write the answer..."></textarea>
                            <InputError :message="form.errors[`items.${i}.answer`]" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add button -->
            <button type="button" @click="addItem"
                class="w-full py-2.5 border-2 border-dashed border-gray-300 rounded-lg text-sm text-blue-600 hover:border-blue-400 hover:bg-blue-50 transition-colors flex items-center justify-center gap-2">
                <span class="text-lg leading-none">+</span>
                Add FAQ Item
            </button>
        </section>

        <!-- Live preview -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-3">
            <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Preview</h2>
            <div v-if="!form.items.length" class="text-sm text-gray-400 py-4 text-center">
                Add FAQ items above to see preview
            </div>
            <div v-else class="space-y-2">
                <p v-if="form.badge" class="text-xs font-semibold tracking-widest text-amber-600 uppercase">
                    {{ form.badge }}
                </p>
                <h3 v-if="form.title" class="text-base font-semibold text-gray-800">{{ form.title }}</h3>
                <p v-if="form.description" class="text-xs text-gray-500">{{ form.description }}</p>
                <div class="mt-3 space-y-2">
                    <div v-for="(item, i) in form.items" :key="i"
                        class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="px-3 py-2 bg-gray-50 flex justify-between items-center cursor-pointer"
                            @click="togglePreview(i)">
                            <span class="text-sm font-medium text-gray-700">{{ item.question || '—' }}</span>
                            <span class="text-gray-400 text-sm">{{ openPreview === i ? '−' : '+' }}</span>
                        </div>
                        <div v-if="openPreview === i" class="px-3 py-2 text-sm text-gray-600">
                            {{ item.answer || '—' }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
</template>

<script setup>
import { ref } from 'vue';
import InputError from '@/Components/InputError.vue';
import DropZone from '@/Components/Admin/Shared/DropZone.vue';

const props = defineProps({
    form:          { type: Object, required: true },
    pages:         { type: Array,  default: () => [] },
    existingImage: { type: String, default: null },
});

const openPreview  = ref(null);

function onImageChange(file) {
    if (!file) return;
    props.form.image = file;
}

function togglePreview(i) {
    openPreview.value = openPreview.value === i ? null : i;
}

function addItem() {
    props.form.items.push({ question: '', answer: '' });
}

function removeItem(i) {
    props.form.items.splice(i, 1);
}

function moveUp(i) {
    if (i === 0) return;
    const arr = props.form.items;
    [arr[i - 1], arr[i]] = [arr[i], arr[i - 1]];
}

function moveDown(i) {
    const arr = props.form.items;
    if (i === arr.length - 1) return;
    [arr[i], arr[i + 1]] = [arr[i + 1], arr[i]];
}
</script>

<style scoped>
.label { @apply block text-sm text-gray-600 mb-1; }
.input { @apply w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:outline-none; }
</style>
