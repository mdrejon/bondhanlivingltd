<template>
    <AdminLayout>
        <Head title="Testimonials" />

        <div class="max-w-7xl mx-auto space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-800">Testimonials</h2>
                <Link :href="route('admin.testimonials.create')" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Add New
                </Link>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-sm text-gray-600">
                                <th class="p-4 font-semibold">Image</th>
                                <th class="p-4 font-semibold">Name</th>
                                <th class="p-4 font-semibold">Designation</th>
                                <th class="p-4 font-semibold">Rating</th>
                                <th class="p-4 font-semibold">Status</th>
                                <th class="p-4 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="testimonial in testimonials.data" :key="testimonial.id" class="hover:bg-gray-50/50 transition-colors">
                                <td class="p-4">
                                    <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center">
                                        <img v-if="testimonial.image" :src="getImageUrl(testimonial.image)" class="w-full h-full object-cover" alt="Image" />
                                        <span v-else class="text-gray-400 text-xs">No img</span>
                                    </div>
                                </td>
                                <td class="p-4 font-medium text-gray-900">{{ testimonial.name }}</td>
                                <td class="p-4 text-gray-600">{{ testimonial.designation }}</td>
                                <td class="p-4 text-gray-600">
                                    <div class="flex text-yellow-400 text-sm">
                                        <span v-for="i in testimonial.rating" :key="i">★</span>
                                        <span v-for="i in (5 - testimonial.rating)" :key="'empty'+i" class="text-gray-200">★</span>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <button @click="toggleStatus(testimonial.id)"
                                        :class="['px-3 py-1 rounded-full text-xs font-medium transition-colors', 
                                            testimonial.status ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200']">
                                        {{ testimonial.status ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="p-4 text-right space-x-3">
                                    <Link :href="route('admin.testimonials.edit', testimonial.id)" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">
                                        Edit
                                    </Link>
                                    <button @click="deleteItem(testimonial.id)" class="text-red-600 hover:text-red-900 font-medium text-sm">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="testimonials.data.length === 0">
                                <td colspan="6" class="p-8 text-center text-gray-500">
                                    No testimonials found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div v-if="testimonials.links && testimonials.links.length > 3" class="p-4 border-t border-gray-200 flex justify-center">
                    <div class="flex flex-wrap gap-1">
                        <template v-for="(link, p) in testimonials.links" :key="p">
                            <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded" v-html="link.label" />
                            <Link v-else class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500" :class="{ 'bg-indigo-50 text-indigo-600 border-indigo-200': link.active }" :href="link.url" v-html="link.label" />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';

const props = defineProps({
    testimonials: {
        type: Object,
        required: true
    }
});

function getImageUrl(path) {
    if (!path) return '';
    if (path.startsWith('assets/')) return `/${path}`;
    return `/storage/${path}`;
}

function deleteItem(id) {
    if (confirm('Are you sure you want to delete this testimonial?')) {
        router.delete(route('admin.testimonials.destroy', id));
    }
}

function toggleStatus(id) {
    router.put(route('admin.testimonials.toggle-status', id));
}
</script>
