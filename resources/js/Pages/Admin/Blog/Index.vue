<template>
    <AdminLayout>
        <div class="max-w-6xl space-y-6">
            <div class="flex items-center justify-between">
                <h1 class="text-lg font-semibold text-gray-800">Blog Posts</h1>
                <Link :href="route('admin.blog.create')"
                    class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                    + Add New Post
                </Link>
            </div>

            <div v-if="$page.props.flash?.success"
                class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded">
                {{ $page.props.flash.success }}
            </div>

            <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Post</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Category</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Date</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-if="blogs.length === 0">
                            <td colspan="5" class="text-center py-10 text-gray-400 text-sm">No posts yet. <Link :href="route('admin.blog.create')" class="text-blue-600 hover:underline">Create your first post</Link>.</td>
                        </tr>
                        <tr v-for="blog in blogs" :key="blog.id" class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <img v-if="blog.feature_image"
                                        :src="'/storage/' + blog.feature_image"
                                        class="w-14 h-10 object-cover rounded border border-gray-200 flex-shrink-0" />
                                    <div v-else
                                        class="w-14 h-10 bg-gray-100 rounded border border-gray-200 flex-shrink-0 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800 line-clamp-1">{{ blog.title }}</p>
                                        <p class="text-xs text-gray-400">/blog/{{ blog.slug }}</p>
                                        <span v-if="blog.is_featured" class="inline-block text-xs text-yellow-600 bg-yellow-50 border border-yellow-200 rounded px-1.5 py-0.5 mt-0.5">★ Featured</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ blog.category || '—' }}</td>
                            <td class="px-4 py-3">
                                <span :class="[
                                    'inline-block text-xs rounded-full px-2.5 py-1 font-medium',
                                    blog.status === 'published'
                                        ? 'bg-green-50 text-green-700 border border-green-200'
                                        : 'bg-amber-50 text-amber-700 border border-amber-200'
                                ]">
                                    {{ blog.status === 'published' ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ blog.published_at || '—' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" @click="toggleStatus(blog)"
                                        :class="[
                                            'text-xs px-2.5 py-1 rounded border font-medium transition-colors',
                                            blog.status === 'published'
                                                ? 'border-amber-300 text-amber-700 hover:bg-amber-50'
                                                : 'border-green-300 text-green-700 hover:bg-green-50'
                                        ]">
                                        {{ blog.status === 'published' ? 'Unpublish' : 'Publish' }}
                                    </button>
                                    <Link :href="route('admin.blog.comments', blog.id)"
                                        class="relative text-xs px-2.5 py-1 rounded border border-gray-300 text-gray-600 hover:bg-gray-50 font-medium">
                                        Comments
                                        <span v-if="blog.pending_comments > 0"
                                            class="absolute -top-1.5 -right-1.5 w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center">
                                            {{ blog.pending_comments }}
                                        </span>
                                    </Link>
                                    <Link :href="route('admin.blog.edit', blog.id)"
                                        class="text-xs px-2.5 py-1 rounded border border-blue-300 text-blue-700 hover:bg-blue-50 font-medium">
                                        Edit
                                    </Link>
                                    <button type="button" @click="deletePost(blog)"
                                        class="text-xs px-2.5 py-1 rounded border border-red-300 text-red-600 hover:bg-red-50 font-medium">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Delete Confirm Modal -->
        <div v-if="deletingBlog" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-sm">
                <h3 class="text-base font-semibold text-gray-800 mb-2">Delete Post</h3>
                <p class="text-sm text-gray-600 mb-5">
                    Are you sure you want to delete <strong>{{ deletingBlog.title }}</strong>? This cannot be undone.
                </p>
                <div class="flex justify-end gap-3">
                    <button @click="deletingBlog = null"
                        class="px-4 py-2 text-sm border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button @click="confirmDelete"
                        class="px-4 py-2 text-sm bg-red-600 text-white rounded hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';

const props = defineProps({
    blogs: Array,
});

const deletingBlog = ref(null);

function toggleStatus(blog) {
    router.patch(route('admin.blog.toggle', blog.id));
}

function deletePost(blog) {
    deletingBlog.value = blog;
}

function confirmDelete() {
    router.delete(route('admin.blog.destroy', deletingBlog.value.id), {
        onFinish: () => { deletingBlog.value = null; },
    });
}
</script>
