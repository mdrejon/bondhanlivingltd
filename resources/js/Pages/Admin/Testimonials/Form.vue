<template>
    <AdminLayout>
        <Head :title="testimonial ? 'Edit Testimonial' : 'Create Testimonial'" />

        <div class="max-w-4xl mx-auto space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-800">
                    {{ testimonial ? 'Edit Testimonial' : 'Create Testimonial' }}
                </h2>
                <Link :href="route('admin.testimonials.index')" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Back to List
                </Link>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <InputLabel for="name" value="Name" />
                            <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required autofocus />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <!-- Designation -->
                        <div>
                            <InputLabel for="designation" value="Designation" />
                            <TextInput id="designation" v-model="form.designation" type="text" class="mt-1 block w-full" />
                            <InputError class="mt-2" :message="form.errors.designation" />
                        </div>
                        
                        <!-- Rating -->
                        <div class="md:col-span-2">
                            <InputLabel for="rating" value="Rating (1-5)" />
                            <TextInput id="rating" v-model="form.rating" type="number" min="1" max="5" class="mt-1 block w-full" required />
                            <InputError class="mt-2" :message="form.errors.rating" />
                        </div>

                        <!-- Text -->
                        <div class="md:col-span-2">
                            <InputLabel for="text" value="Testimonial Text" />
                            <textarea
                                id="text"
                                v-model="form.text"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                                rows="4"
                                required
                            ></textarea>
                            <InputError class="mt-2" :message="form.errors.text" />
                        </div>

                        <!-- Image -->
                        <div class="md:col-span-2">
                            <InputLabel for="image" value="Reviewer Image" />
                            <input
                                id="image"
                                type="file"
                                @change="e => form.image = e.target.files[0]"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                accept="image/*"
                            />
                            <div v-if="testimonial && testimonial.image" class="mt-2">
                                <p class="text-sm text-gray-500 mb-1">Current Image:</p>
                                <img :src="getImageUrl(testimonial.image)" class="h-20 w-20 object-cover rounded" alt="Image">
                            </div>
                            <InputError class="mt-2" :message="form.errors.image" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            {{ testimonial ? 'Update Testimonial' : 'Create Testimonial' }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    testimonial: {
        type: Object,
        default: null
    }
});

const form = useForm({
    name: props.testimonial?.name || '',
    designation: props.testimonial?.designation || '',
    text: props.testimonial?.text || '',
    rating: props.testimonial?.rating || 5,
    image: null,
});

function getImageUrl(path) {
    if (!path) return '';
    if (path.startsWith('assets/')) return `/${path}`;
    return `/storage/${path}`;
}

const submit = () => {
    if (props.testimonial) {
        form.post(route('admin.testimonials.update', props.testimonial.id), {
            forceFormData: true,
            _method: 'PUT'
        });
    } else {
        form.post(route('admin.testimonials.store'), {
            forceFormData: true
        });
    }
};
</script>
