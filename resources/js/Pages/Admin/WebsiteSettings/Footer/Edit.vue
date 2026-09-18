<template>
    <AdminLayout>
        <Head title="Footer Settings" />

        <div class="max-w-7xl mx-auto space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-800">Footer Settings</h2>
            </div>

            <!-- Flash -->
            <div v-if="$page.props.flash?.success" class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg">
                {{ $page.props.flash.success }}
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <!-- Tabs -->
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px px-6" aria-label="Tabs">
                        <button v-for="tab in tabs" :key="tab.id" @click="activeTab = tab.id"
                            :class="[
                                activeTab === tab.id ? 'border-indigo-500 text-indigo-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                                'whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm transition-colors'
                            ]">
                            {{ tab.name }}
                        </button>
                    </nav>
                </div>

                <div class="p-6">
                    <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-6">
                        
                        <!-- General Tab -->
                        <div v-show="activeTab === 'general'" class="space-y-6">
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Footer Basic Info</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-2">
                                    <InputLabel value="Footer Logo" />
                                    <DropZone 
                                        @change="f => form.footer_logo = f" 
                                        hint="PNG or SVG format recommended for logos." 
                                        :existing-preview="getImageUrl(settings.footer_logo)" 
                                        preview-class="h-16 object-contain bg-gray-900 p-2 rounded" 
                                    />
                                    <InputError class="mt-2" :message="form.errors.footer_logo" />
                                </div>
                                
                                <div class="col-span-2">
                                    <InputLabel for="footer_about_text" value="About Company Text" />
                                    <textarea id="footer_about_text" v-model="form.footer_about_text" class="input w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3"></textarea>
                                    <InputError class="mt-2" :message="form.errors.footer_about_text" />
                                </div>

                                <div class="col-span-2">
                                    <InputLabel for="footer_copyright" value="Copyright Text" />
                                    <TextInput id="footer_copyright" v-model="form.footer_copyright" type="text" class="mt-1 block w-full" />
                                    <InputError class="mt-2" :message="form.errors.footer_copyright" />
                                </div>

                                <div>
                                    <InputLabel for="footer_privacy_url" value="Privacy Policy URL" />
                                    <TextInput id="footer_privacy_url" v-model="form.footer_privacy_url" type="text" class="mt-1 block w-full" />
                                </div>

                                <div>
                                    <InputLabel for="footer_terms_url" value="Terms of Use URL" />
                                    <TextInput id="footer_terms_url" v-model="form.footer_terms_url" type="text" class="mt-1 block w-full" />
                                </div>
                            </div>
                        </div>

                        <!-- Newsletter Tab -->
                        <div v-show="activeTab === 'newsletter'" class="space-y-6">
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Newsletter Section</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <InputLabel for="footer_newsletter_subtitle" value="Subtitle" />
                                    <TextInput id="footer_newsletter_subtitle" v-model="form.footer_newsletter_subtitle" type="text" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <InputLabel for="footer_newsletter_title" value="Title" />
                                    <TextInput id="footer_newsletter_title" v-model="form.footer_newsletter_title" type="text" class="mt-1 block w-full" />
                                </div>
                            </div>
                        </div>

                        <!-- Contact & Social Tab -->
                        <div v-show="activeTab === 'contact'" class="space-y-6">
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Contact Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-2">
                                    <InputLabel for="footer_address" value="Physical Address" />
                                    <TextInput id="footer_address" v-model="form.footer_address" type="text" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <InputLabel for="footer_email" value="Email Address" />
                                    <TextInput id="footer_email" v-model="form.footer_email" type="email" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <InputLabel for="footer_phone" value="Phone Number" />
                                    <TextInput id="footer_phone" v-model="form.footer_phone" type="text" class="mt-1 block w-full" />
                                </div>
                            </div>

                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mt-8">Social Links</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <InputLabel for="social_facebook" value="Facebook URL" />
                                    <TextInput id="social_facebook" v-model="form.social_facebook" type="text" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <InputLabel for="social_twitter" value="Twitter URL" />
                                    <TextInput id="social_twitter" v-model="form.social_twitter" type="text" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <InputLabel for="social_linkedin" value="LinkedIn URL" />
                                    <TextInput id="social_linkedin" v-model="form.social_linkedin" type="text" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <InputLabel for="footer_whatsapp" value="WhatsApp URL" />
                                    <TextInput id="footer_whatsapp" v-model="form.footer_whatsapp" type="text" class="mt-1 block w-full" />
                                </div>
                            </div>
                        </div>

                        <!-- Quick Links Tab -->
                        <div v-show="activeTab === 'links'" class="space-y-6">
                            <div class="flex items-center justify-between border-b pb-2">
                                <h3 class="text-lg font-medium text-gray-900">Quick Links</h3>
                                <button type="button" @click="addLink" class="text-sm bg-indigo-50 text-indigo-600 px-3 py-1 rounded hover:bg-indigo-100 transition">
                                    + Add Link
                                </button>
                            </div>

                            <div v-if="form.footer_quick_links.length === 0" class="text-center text-gray-500 py-4">
                                No links added yet.
                            </div>

                            <div v-for="(link, index) in form.footer_quick_links" :key="index" class="flex items-start gap-4 p-4 border border-gray-100 rounded-lg bg-gray-50">
                                <div class="flex-1 grid grid-cols-2 gap-4">
                                    <div>
                                        <InputLabel :for="'link_name_'+index" value="Link Label" />
                                        <TextInput :id="'link_name_'+index" v-model="link.name" type="text" class="mt-1 block w-full" placeholder="e.g. About Us" required />
                                    </div>
                                    <div>
                                        <InputLabel :for="'link_url_'+index" value="Link URL" />
                                        <TextInput :id="'link_url_'+index" v-model="link.url" type="text" class="mt-1 block w-full" placeholder="e.g. /about or https://..." required />
                                    </div>
                                </div>
                                <button type="button" @click="removeLink(index)" class="mt-7 text-red-500 hover:text-red-700 bg-red-50 p-2 rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Gallery Tab -->
                        <div v-show="activeTab === 'gallery'" class="space-y-6">
                            <div class="flex items-center justify-between border-b pb-2">
                                <h3 class="text-lg font-medium text-gray-900">Gallery Posts (Max 6)</h3>
                                <button type="button" @click="addGallery" v-if="form.footer_gallery.length < 6" class="text-sm bg-indigo-50 text-indigo-600 px-3 py-1 rounded hover:bg-indigo-100 transition">
                                    + Add Image
                                </button>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                                <div v-for="(item, index) in form.footer_gallery" :key="index" class="border border-gray-200 rounded-lg p-4 relative bg-gray-50">
                                    <button type="button" @click="removeGallery(index)" class="absolute top-2 right-2 z-10 bg-white rounded-full p-1 text-red-500 shadow hover:text-red-700 hover:bg-red-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                    <InputLabel :value="'Gallery Image ' + (index + 1)" class="mb-2" />
                                    <DropZone 
                                        @change="f => item.newImage = f" 
                                        hint="JPG, PNG" 
                                        :existing-preview="getImageUrl(item.image)" 
                                        preview-class="h-24 w-full object-cover rounded" 
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Partners Tab -->
                        <div v-show="activeTab === 'partners'" class="space-y-6">
                            <div class="flex items-center justify-between border-b pb-2">
                                <h3 class="text-lg font-medium text-gray-900">Partner Logos</h3>
                                <button type="button" @click="addPartner" class="text-sm bg-indigo-50 text-indigo-600 px-3 py-1 rounded hover:bg-indigo-100 transition">
                                    + Add Partner
                                </button>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                                <div v-for="(partner, index) in form.footer_partners" :key="index" class="border border-gray-200 rounded-lg p-4 relative bg-gray-50 space-y-4">
                                    <button type="button" @click="removePartner(index)" class="absolute top-2 right-2 z-10 bg-white rounded-full p-1 text-red-500 shadow hover:text-red-700 hover:bg-red-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                    
                                    <div>
                                        <InputLabel :value="'Partner Logo ' + (index + 1)" class="mb-2" />
                                        <DropZone 
                                            @change="f => partner.newImage = f" 
                                            hint="JPG, PNG" 
                                            :existing-preview="getImageUrl(partner.image)" 
                                            preview-class="h-24 w-full object-contain bg-white rounded border border-gray-200 p-2" 
                                        />
                                    </div>
                                    
                                    <div>
                                        <InputLabel :for="'partner_url_'+index" value="Partner URL (Optional)" />
                                        <TextInput :id="'partner_url_'+index" v-model="partner.url" type="text" class="mt-1 block w-full text-sm" placeholder="https://..." />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 pt-6 border-t border-gray-200">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Save All Footer Settings
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DropZone from '@/Components/Admin/Shared/DropZone.vue';

const props = defineProps({
    settings: {
        type: Object,
        required: true
    }
});

const activeTab = ref('general');
const tabs = [
    { id: 'general', name: 'General' },
    { id: 'newsletter', name: 'Newsletter' },
    { id: 'contact', name: 'Contact & Social' },
    { id: 'links', name: 'Quick Links' },
    { id: 'gallery', name: 'Gallery Posts' },
    { id: 'partners', name: 'Partner Logos' },
];

function getImageUrl(path) {
    if (!path) return null;
    if (path.startsWith('assets/')) return `/${path}`;
    return `/storage/${path}`;
}

const form = useForm({
    footer_logo: null,
    
    footer_about_text: props.settings.footer_about_text,
    footer_copyright: props.settings.footer_copyright,
    footer_privacy_url: props.settings.footer_privacy_url,
    footer_terms_url: props.settings.footer_terms_url,
    
    footer_newsletter_subtitle: props.settings.footer_newsletter_subtitle,
    footer_newsletter_title: props.settings.footer_newsletter_title,
    
    footer_address: props.settings.footer_address,
    footer_email: props.settings.footer_email,
    footer_phone: props.settings.footer_phone,
    
    social_facebook: props.settings.social_facebook,
    social_twitter: props.settings.social_twitter,
    social_linkedin: props.settings.social_linkedin,
    footer_whatsapp: props.settings.footer_whatsapp,
    
    footer_quick_links: JSON.parse(JSON.stringify(props.settings.footer_quick_links)),
    footer_gallery: JSON.parse(JSON.stringify(props.settings.footer_gallery)),
    footer_partners: JSON.parse(JSON.stringify(props.settings.footer_partners)),
});

// Array helpers
function addLink() {
    form.footer_quick_links.push({ name: '', url: '' });
}
function removeLink(index) {
    form.footer_quick_links.splice(index, 1);
}

function addGallery() {
    if (form.footer_gallery.length < 6) {
        form.footer_gallery.push({ image: null, newImage: null });
    }
}
function removeGallery(index) {
    form.footer_gallery.splice(index, 1);
}

function addPartner() {
    form.footer_partners.push({ image: null, newImage: null, url: '' });
}
function removePartner(index) {
    form.footer_partners.splice(index, 1);
}

const submit = () => {
    // Transform arrays into the format we want to send
    const dataToSend = new FormData();
    
    // Add simple fields
    Object.keys(form).forEach(key => {
        if (!['footer_logo', 'footer_quick_links', 'footer_gallery', 'footer_partners'].includes(key)) {
            dataToSend.append(key, form[key] || '');
        }
    });

    if (form.footer_logo) {
        dataToSend.append('footer_logo', form.footer_logo);
    }
    
    // Stringify JSON data that doesn't have files inside the array
    dataToSend.append('footer_quick_links', JSON.stringify(form.footer_quick_links));
    
    // Handle gallery array
    const galleryProcessed = form.footer_gallery.map(g => ({ image: g.image }));
    dataToSend.append('footer_gallery', JSON.stringify(galleryProcessed));
    
    form.footer_gallery.forEach((g, idx) => {
        if (g.newImage) dataToSend.append(`footer_gallery_${idx}_image`, g.newImage);
    });

    // Handle partners array
    const partnersProcessed = form.footer_partners.map(p => ({ image: p.image, url: p.url }));
    dataToSend.append('footer_partners', JSON.stringify(partnersProcessed));
    
    form.footer_partners.forEach((p, idx) => {
        if (p.newImage) dataToSend.append(`footer_partners_${idx}_image`, p.newImage);
    });

    router.post(route('admin.website-settings.footer.update'), dataToSend, {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            // Clean up newImage fields after success so previews work normally on next interaction
            form.footer_gallery.forEach(g => g.newImage = null);
            form.footer_partners.forEach(p => p.newImage = null);
            form.footer_logo = null;
        }
    });
};
</script>
