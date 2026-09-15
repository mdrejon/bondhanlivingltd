<template>
    <AdminLayout>
        <div class="max-w-4xl space-y-6">
            <div class="flex items-center justify-between">
                <h1 class="text-lg font-semibold text-gray-800">Edit Blog Post</h1>
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
                submit-label="Update Post"
                :existing-feature-image="blog.feature_image"
                :existing-og-image="blog.og_image"
                :existing-author-avatar="blog.author_avatar"
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
    blog:       Object,
    categories: Array,
});

const form = useForm({
    title:             props.blog.title            ?? '',
    excerpt:           props.blog.excerpt          ?? '',
    content:           props.blog.content          ?? '',
    category_id:       props.blog.category_id      ?? null,
    tags:              Array.isArray(props.blog.tags) ? props.blog.tags.join(', ') : (props.blog.tags ?? ''),
    feature_image:     null,
    og_image:          null,
    author_name:       props.blog.author_name      ?? '',
    author_bio:        props.blog.author_bio       ?? '',
    author_avatar:     null,
    status:            props.blog.status           ?? 'draft',
    published_at:      props.blog.published_at     ? props.blog.published_at.slice(0, 16) : '',
    is_featured:       props.blog.is_featured      ?? false,
    sort_order:        props.blog.sort_order       ?? 0,
    meta_title:        props.blog.meta_title       ?? '',
    meta_description:  props.blog.meta_description ?? '',
    meta_keywords:     props.blog.meta_keywords    ?? '',
});

function submit() {
    form.post(route('admin.blog.update', props.blog.id), {
        forceFormData: true,
    });
}
</script>
