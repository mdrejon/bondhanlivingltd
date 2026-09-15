<template>
    <AdminLayout>
        <div class="max-w-6xl space-y-6">

            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-gray-800">Blog Comments</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Review and moderate reader comments.</p>
                </div>
            </div>

            <!-- Flash -->
            <div v-if="$page.props.flash?.success"
                class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded">
                {{ $page.props.flash.success }}
            </div>

            <!-- Stats + filter tabs -->
            <div class="flex items-center gap-3">
                <button v-for="tab in tabs" :key="tab.key"
                    @click="setFilter(tab.key)"
                    :class="[
                        'px-4 py-2 rounded-lg text-sm font-medium border transition-colors',
                        activeFilter === tab.key
                            ? 'bg-blue-600 text-white border-blue-600'
                            : 'bg-white text-gray-600 border-gray-200 hover:border-gray-300'
                    ]"
                >
                    {{ tab.label }}
                    <span :class="[
                        'ml-1.5 text-xs rounded-full px-1.5 py-0.5',
                        activeFilter === tab.key ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-500'
                    ]">{{ tab.count }}</span>
                </button>
            </div>

            <!-- Comments table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Author</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Comment</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Post</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Date</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-if="comments.length === 0">
                            <td colspan="6" class="px-4 py-12 text-center text-gray-400 text-sm">
                                No comments found.
                            </td>
                        </tr>
                        <tr v-for="comment in comments" :key="comment.id"
                            :class="['hover:bg-gray-50 transition-colors', !comment.is_approved && 'bg-amber-50/40']"
                        >
                            <!-- Author -->
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2.5">
                                    <img
                                        :src="`https://ui-avatars.com/api/?name=${encodeURIComponent(comment.name)}&background=0D3D2E&color=fff&size=40`"
                                        class="w-8 h-8 rounded-full flex-shrink-0"
                                        :alt="comment.name"
                                    />
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-800 truncate">{{ comment.name }}</p>
                                        <p class="text-xs text-gray-400 truncate max-w-[140px]">{{ comment.email }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Comment text -->
                            <td class="px-4 py-3 max-w-xs">
                                <p class="text-gray-700 line-clamp-2 text-sm">{{ comment.message }}</p>
                                <span v-if="comment.replies_count > 0" class="inline-flex items-center gap-1 text-xs text-blue-500 mt-1">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                    {{ comment.replies_count }} {{ comment.replies_count === 1 ? 'reply' : 'replies' }}
                                </span>
                            </td>

                            <!-- Post -->
                            <td class="px-4 py-3">
                                <p class="text-gray-700 text-xs line-clamp-2 max-w-[160px]">{{ comment.blog?.title ?? '—' }}</p>
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-3 text-center">
                                <span :class="[
                                    'inline-block text-xs rounded-full px-2.5 py-1 font-medium',
                                    comment.is_approved
                                        ? 'bg-green-50 text-green-700 border border-green-200'
                                        : 'bg-amber-50 text-amber-700 border border-amber-200'
                                ]">
                                    {{ comment.is_approved ? 'Approved' : 'Pending' }}
                                </span>
                            </td>

                            <!-- Date -->
                            <td class="px-4 py-3 text-xs text-gray-400 whitespace-nowrap">
                                {{ formatDate(comment.created_at) }}
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="toggleApprove(comment)"
                                        :class="[
                                            'text-xs px-2.5 py-1 rounded border font-medium transition-colors',
                                            comment.is_approved
                                                ? 'border-amber-300 text-amber-700 hover:bg-amber-50'
                                                : 'border-green-300 text-green-700 hover:bg-green-50'
                                        ]"
                                    >
                                        {{ comment.is_approved ? 'Unapprove' : 'Approve' }}
                                    </button>
                                    <button @click="deleteComment(comment)"
                                        class="text-xs px-2.5 py-1 rounded border border-red-300 text-red-600 hover:bg-red-50 font-medium transition-colors"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </AdminLayout>
</template>

<script setup>
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';

const props = defineProps({
    comments:      { type: Array,  default: () => [] },
    filter:        { type: String, default: 'all' },
    pendingCount:  { type: Number, default: 0 },
    approvedCount: { type: Number, default: 0 },
    totalCount:    { type: Number, default: 0 },
});

const activeFilter = computed(() => props.filter);

const tabs = computed(() => [
    { key: 'all',      label: 'All',      count: props.totalCount },
    { key: 'pending',  label: 'Pending',  count: props.pendingCount },
    { key: 'approved', label: 'Approved', count: props.approvedCount },
]);

function setFilter(key) {
    router.get(route('admin.blog-comments.index'), { filter: key }, { preserveState: false });
}

function formatDate(dateStr) {
    if (!dateStr) return '—';
    return new Date(dateStr).toLocaleDateString('en-GB', {
        day: 'numeric', month: 'short', year: 'numeric',
    });
}

function toggleApprove(comment) {
    router.patch(route('admin.blog-comments.approve', comment.id), {}, { preserveScroll: true });
}

function deleteComment(comment) {
    if (!confirm('Delete this comment and all its replies?')) return;
    router.delete(route('admin.blog-comments.destroy', comment.id), { preserveScroll: true });
}
</script>
