<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';

const props = defineProps({
    features: Array,
    amenities: Array,
});

const form = useForm({
    features: [...(props.features || [])],
    amenities: [...(props.amenities || [])],
});

// Feature Methods
const addFeature = () => {
    form.features.push('');
};
const removeFeature = (index) => {
    form.features.splice(index, 1);
};

// Amenity Category Methods
const addAmenityCategory = () => {
    form.amenities.push({ category: '', items: [''] });
};
const removeAmenityCategory = (index) => {
    form.amenities.splice(index, 1);
};

// Amenity Item Methods
const addAmenityItem = (categoryIndex) => {
    form.amenities[categoryIndex].items.push('');
};
const removeAmenityItem = (categoryIndex, itemIndex) => {
    form.amenities[categoryIndex].items.splice(itemIndex, 1);
};

const submit = () => {
    form.post(route('admin.website-settings.features-amenities.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Features & Amenities Settings" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Features & Amenities Settings
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <form @submit.prevent="submit" class="space-y-6">
                    
                    <!-- Features Section -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <div class="flex justify-between items-center mb-4 border-b pb-2">
                                <h3 class="text-lg font-medium text-gray-900">Features List</h3>
                                <button type="button" @click="addFeature" class="px-3 py-1 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">
                                    + Add Feature
                                </button>
                            </div>
                            
                            <div v-if="form.features.length === 0" class="text-gray-500 text-sm">No features added.</div>
                            
                            <div class="space-y-3">
                                <div v-for="(feature, index) in form.features" :key="'feature-'+index" class="flex items-start gap-2">
                                    <input type="text" v-model="form.features[index]" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Feature text..." />
                                    <button type="button" @click="removeFeature(index)" class="mt-2 text-red-500 hover:text-red-700 font-bold" title="Remove Feature">
                                        &times;
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Amenities Section -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <div class="flex justify-between items-center mb-4 border-b pb-2">
                                <h3 class="text-lg font-medium text-gray-900">Amenities Categories</h3>
                                <button type="button" @click="addAmenityCategory" class="px-3 py-1 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">
                                    + Add Category
                                </button>
                            </div>

                            <div v-if="form.amenities.length === 0" class="text-gray-500 text-sm">No amenities added.</div>

                            <div class="space-y-6">
                                <div v-for="(amenity, catIndex) in form.amenities" :key="'cat-'+catIndex" class="p-4 border rounded-md bg-gray-50 relative">
                                    <button type="button" @click="removeAmenityCategory(catIndex)" class="absolute top-2 right-3 text-red-500 hover:text-red-700 font-bold" title="Remove Category">
                                        &times; Remove Category
                                    </button>

                                    <!-- Category Title -->
                                    <div class="mb-4 w-3/4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Category Title</label>
                                        <input type="text" v-model="form.amenities[catIndex].category" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="e.g. DOOR, WINDOWS..." />
                                    </div>

                                    <!-- Category Items -->
                                    <div class="pl-4 border-l-2 border-indigo-200 space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Amenities Items</label>
                                        <div v-for="(item, itemIndex) in amenity.items" :key="'item-'+catIndex+'-'+itemIndex" class="flex items-start gap-2">
                                            <input type="text" v-model="form.amenities[catIndex].items[itemIndex]" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Amenity item text..." />
                                            <button type="button" @click="removeAmenityItem(catIndex, itemIndex)" class="mt-2 text-red-500 hover:text-red-700 font-bold" title="Remove Item">
                                                &times;
                                            </button>
                                        </div>
                                        <button type="button" @click="addAmenityItem(catIndex)" class="mt-2 text-sm text-indigo-600 hover:text-indigo-800">
                                            + Add Item
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" :disabled="form.processing">
                            Save Settings
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </AdminLayout>
</template>
