<template>
    <AdminLayout>
        <div class="max-w-3xl space-y-4">
            <div class="flex items-center gap-3">
                <a :href="route('admin.faqs.index')" class="text-gray-400 hover:text-gray-600 text-sm">← Back</a>
                <h1 class="text-lg font-semibold text-gray-800">Edit FAQ Group</h1>
                <span class="px-2 py-0.5 text-xs rounded-full font-medium"
                    :class="faq.page === 'home' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700'">
                    {{ faq.page === 'home' ? 'Home Page' : 'About Page' }}
                </span>
            </div>

            <div v-if="$page.props.flash?.success"
                class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded">
                {{ $page.props.flash.success }}
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <FaqForm :form="form" :pages="pages" :existing-image="f.image" />
                <div class="flex justify-end">
                    <button type="submit" :disabled="form.processing"
                        class="px-6 py-2.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-60">
                        {{ form.processing ? 'Saving...' : 'Save Changes' }}
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
    faq:   { type: Object, required: true },
    pages: { type: Array,  default: () => [] },
});

const f = props.faq;

const form = useForm({
    page:        f.page        ?? 'home',
    badge:       f.badge       ?? "FAQ'S",
    title:       f.title       ?? '',
    description: f.description ?? '',
    image:       null,
    image_alt:   f.image_alt   ?? '',
    items:       Array.isArray(f.items) ? f.items.map(i => ({ ...i })) : [{ question: '', answer: '' }],
    sort_order:  f.sort_order  ?? 0,
    is_active:   f.is_active   ?? true,
});

function submit() {
    form.transform(data => ({ ...data, _method: 'PUT' }))
        .post(route('admin.faqs.update', f.id), { forceFormData: true });
}
</script>
