<template>
    <AdminLayout>
        <div class="max-w-4xl space-y-6">
            <div class="flex items-center justify-between">
                <h1 class="text-lg font-semibold text-gray-800">Add New Blog Post</h1>
                <Link :href="route('admin.blog.index')"
                    class="text-sm text-gray-500 hover:text-gray-700">← Back to Posts</Link>
            </div>

            <div v-if="$page.props.flash?.success"
                class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded">
                {{ $page.props.flash.success }}
            </div>

            <BlogForm
                :form="form"
                :categories="categories"
                submit-label="Create Post"
                @submit="submit"
            />
        </div>
    </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { Link }    from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import BlogForm    from './Form.vue';

const props = defineProps({
    categories: Array,
});

const form = useForm({
    title:             '',
    excerpt:           '',
    content:           '',
    category_id:       null,
    tags:              '',
    feature_image:     null,
    og_image:          null,
    author_name:       '',
    author_bio:        '',
    author_avatar:     null,
    status:            'draft',
    published_at:      '',
    is_featured:       false,
    sort_order:        0,
    meta_title:        '',
    meta_description:  '',
    meta_keywords:     '',
});

function submit() {
    form.post(route('admin.blog.store'), {
        forceFormData: true,
    });
}
</script>
