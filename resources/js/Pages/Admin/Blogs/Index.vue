<template>
    <AdminLayout>
        <Head title="Blogs" />

        <div class="max-w-7xl mx-auto space-y-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h2 class="text-2xl font-bold text-gray-800">Blogs</h2>
                <div class="flex gap-4 w-full sm:w-auto">
                    <TextInput
                        v-model="search"
                        type="text"
                        placeholder="Search blogs..."
                        class="w-full sm:w-64"
                    />
                    <Link
                        :href="route('admin.blogs.create')"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shrink-0"
                    >
                        Add Blog
                    </Link>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-nowrap">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Image</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Author</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="blog in blogs.data" :key="blog.id" class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <img :src="getImageUrl(blog.image)" class="h-12 w-16 object-cover rounded shadow-sm border border-gray-100" />
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 truncate max-w-[200px]" :title="blog.title">
                                        {{ blog.title }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ blog.category }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ blog.author }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ formatDate(blog.published_at) }}
                                </td>
                                <td class="px-6 py-4">
                                    <button
                                        @click="toggleStatus(blog)"
                                        :class="[
                                            'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2',
                                            blog.status ? 'bg-indigo-600' : 'bg-gray-200'
                                        ]"
                                    >
                                        <span
                                            :class="[
                                                'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                                                blog.status ? 'translate-x-5' : 'translate-x-0'
                                            ]"
                                        />
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium space-x-3">
                                    <Link
                                        :href="route('admin.blogs.edit', blog.id)"
                                        class="text-indigo-600 hover:text-indigo-900 transition-colors"
                                    >
                                        Edit
                                    </Link>
                                    <button
                                        @click="confirmDelete(blog)"
                                        class="text-red-600 hover:text-red-900 transition-colors"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="blogs.data.length === 0">
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    No blogs found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50" v-if="blogs.links && blogs.links.length > 3">
                    <Pagination :links="blogs.links" />
                </div>
            </div>
        </div>

        <ConfirmModal
            :show="showDeleteModal"
            title="Delete Blog"
            content="Are you sure you want to delete this blog? This action cannot be undone."
            @close="showDeleteModal = false"
            @confirm="deleteBlog"
        />
    </AdminLayout>
</template>

<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import Pagination from '@/Components/Admin/Shared/Pagination.vue';
import ConfirmModal from '@/Components/Admin/Shared/ConfirmModal.vue';
import TextInput from '@/Components/TextInput.vue';
import debounce from 'lodash/debounce';

const props = defineProps({
    blogs: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');

watch(
    search,
    debounce((value) => {
        router.get(
            route('admin.blogs.index'),
            { search: value },
            { preserveState: true, replace: true }
        );
    }, 300)
);

const showDeleteModal = ref(false);
const blogToDelete = ref(null);

const confirmDelete = (blog) => {
    blogToDelete.value = blog;
    showDeleteModal.value = true;
};

const deleteBlog = () => {
    router.delete(route('admin.blogs.destroy', blogToDelete.value.id), {
        onSuccess: () => {
            showDeleteModal.value = false;
        }
    });
};

const toggleStatus = (blog) => {
    router.patch(route('admin.blogs.toggle', blog.id), {}, { preserveScroll: true });
};

const getImageUrl = (path) => {
    if (!path) return '';
    if (path.startsWith('assets/')) return '/' + path;
    if (path.startsWith('http')) return path;
    return '/storage/' + path;
};

const formatDate = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
};
</script>
