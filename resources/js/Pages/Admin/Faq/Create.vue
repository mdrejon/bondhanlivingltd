<template>
    <AdminLayout>
        <div class="max-w-3xl space-y-4">
            <div class="flex items-center gap-3">
                <a :href="route('admin.faqs.index')" class="text-gray-400 hover:text-gray-600 text-sm">← Back</a>
                <h1 class="text-lg font-semibold text-gray-800">Add FAQ Group</h1>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <FaqForm :form="form" :pages="pages" />
                <div class="flex justify-end">
                    <button type="submit" :disabled="form.processing"
                        class="px-6 py-2.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-60">
                        {{ form.processing ? 'Creating...' : 'Create FAQ Group' }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import FaqForm     from './FaqForm.vue';

const props = defineProps({
    pages: { type: Array, default: () => [] },
});

const form = useForm({
    page:        'home',
    badge:       "FAQ'S",
    title:       '',
    description: '',
    image:       null,
    image_alt:   '',
    items:       [{ question: '', answer: '' }],
    sort_order:  0,
    is_active:   true,
});

function submit() {
    form.post(route('admin.faqs.store'), { forceFormData: true });
}
</script>
