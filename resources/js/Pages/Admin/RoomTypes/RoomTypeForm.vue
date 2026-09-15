<template>
    <form @submit.prevent="$emit('submit')" class="space-y-6">

        <!-- Basic Info -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Basic Information</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Room Type Name <span class="text-red-500">*</span></label>
                    <input v-model="form.name" type="text" class="input" placeholder="Deluxe Double Room" />
                    <p v-if="form.errors.name" class="text-xs text-red-500 mt-1">{{ form.errors.name }}</p>
                </div>
                <div>
                    <label class="label">URL Slug <span class="text-gray-400 font-normal text-xs">(auto-generated)</span></label>
                    <div class="flex items-center gap-2">
                        <input :value="slugPreview" type="text" class="input bg-gray-50 text-gray-500 cursor-not-allowed" readonly />
                        <span class="text-xs text-gray-400 whitespace-nowrap">/rooms/{{ slugPreview || '…' }}</span>
                    </div>
                </div>
                <div>
                    <label class="label">Bed Type</label>
                    <input v-model="form.bed_type" type="text" class="input" placeholder="King size bed" />
                </div>
                <div>
                    <label class="label">Price (BDT ৳) <span class="text-red-500">*</span></label>
                    <input v-model="form.price" type="number" min="0" step="0.01" class="input" placeholder="4500" />
                    <p v-if="form.errors.price" class="text-xs text-red-500 mt-1">{{ form.errors.price }}</p>
                </div>
                <div>
                    <label class="label">Price (USD $)</label>
                    <input v-model="form.price_usd" type="number" min="0" step="0.01" class="input" placeholder="40.00" />
                    <p v-if="form.errors.price_usd" class="text-xs text-red-500 mt-1">{{ form.errors.price_usd }}</p>
                </div>
                <div>
                    <label class="label">Price Unit</label>
                    <select v-model="form.price_unit" class="input">
                        <option value="/Night">/Night</option>
                        <option value="/Event">/Event</option>
                        <option value="/Day">/Day</option>
                    </select>
                </div>
                <div>
                    <label class="label">Check-In Time</label>
                    <input v-model="form.check_in_time" type="text" class="input" placeholder="12:30" />
                </div>
                <div>
                    <label class="label">Check-Out Time</label>
                    <input v-model="form.check_out_time" type="text" class="input" placeholder="11:30" />
                </div>
                <div>
                    <label class="label">Max Adults <span class="text-red-500">*</span></label>
                    <input v-model.number="form.max_adults" type="number" min="1" max="20" class="input" />
                </div>
                <div>
                    <label class="label">Max Children</label>
                    <input v-model.number="form.max_children" type="number" min="0" max="20" class="input" />
                </div>
                <div>
                    <label class="label">Rating (0–5)</label>
                    <input v-model.number="form.rating" type="number" min="0" max="5" step="0.1" class="input" />
                </div>
                <div>
                    <label class="label">Sort Order</label>
                    <input v-model.number="form.sort_order" type="number" min="0" class="input" />
                </div>
            </div>
            <div class="flex gap-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input v-model="form.is_active" type="checkbox" class="w-4 h-4 rounded text-blue-600" />
                    <span class="text-sm text-gray-600">Active</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input v-model="form.is_featured" type="checkbox" class="w-4 h-4 rounded text-yellow-500" />
                    <span class="text-sm text-gray-600">Featured</span>
                </label>
            </div>
        </section>

        <!-- Offer & Discount -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-5">
            <div class="flex items-center justify-between border-b pb-2">
                <h2 class="text-sm font-semibold text-gray-700">Offer &amp; Discount</h2>
                <span class="text-xs text-gray-400">Leave discount empty to disable offer</span>
            </div>

            <!-- BDT Discount -->
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">BDT Discount</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">Discount Type</label>
                        <select v-model="form.discount_type_bdt" class="input">
                            <option value="">— None —</option>
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed">Fixed Amount (৳)</option>
                        </select>
                    </div>
                    <div>
                        <label class="label">Discount Value</label>
                        <input v-model="form.discount_value_bdt" type="number" min="0" step="0.01" class="input"
                            :placeholder="form.discount_type_bdt === 'percentage' ? 'e.g. 10 for 10%' : 'e.g. 500'" />
                    </div>
                </div>
                <!-- BDT Calculated preview -->
                <div v-if="discountedBdt !== null"
                    class="mt-2 flex items-center gap-3 text-sm bg-green-50 border border-green-200 rounded-lg px-4 py-2">
                    <span class="text-gray-400 line-through">৳{{ Number(form.price).toLocaleString() }}</span>
                    <span class="text-green-700 font-bold">৳{{ Number(discountedBdt).toLocaleString('en-BD', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}</span>
                    <span class="text-xs text-green-600 bg-green-100 px-2 py-0.5 rounded-full">
                        {{ form.discount_type_bdt === 'percentage' ? form.discount_value_bdt + '% off' : '৳' + Number(form.discount_value_bdt).toLocaleString() + ' off' }}
                    </span>
                </div>
            </div>

            <!-- USD Discount -->
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">USD Discount</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">Discount Type</label>
                        <select v-model="form.discount_type_usd" class="input">
                            <option value="">— None —</option>
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed">Fixed Amount ($)</option>
                        </select>
                    </div>
                    <div>
                        <label class="label">Discount Value</label>
                        <input v-model="form.discount_value_usd" type="number" min="0" step="0.01" class="input"
                            :placeholder="form.discount_type_usd === 'percentage' ? 'e.g. 10 for 10%' : 'e.g. 5.00'" />
                    </div>
                </div>
                <!-- USD Calculated preview -->
                <div v-if="discountedUsd !== null"
                    class="mt-2 flex items-center gap-3 text-sm bg-green-50 border border-green-200 rounded-lg px-4 py-2">
                    <span class="text-gray-400 line-through">${{ Number(form.price_usd).toFixed(2) }}</span>
                    <span class="text-green-700 font-bold">${{ Number(discountedUsd).toFixed(2) }}</span>
                    <span class="text-xs text-green-600 bg-green-100 px-2 py-0.5 rounded-full">
                        {{ form.discount_type_usd === 'percentage' ? form.discount_value_usd + '% off' : '$' + Number(form.discount_value_usd).toFixed(2) + ' off' }}
                    </span>
                </div>
            </div>

            <!-- Offer Expiry -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Offer Expiry Date &amp; Time</label>
                    <FlatpickrInput
                        v-model="form.offer_expires_at"
                        placeholder="Pick expiry date & time…"
                    />
                    <p class="text-xs text-gray-400 mt-1">Leave empty for no expiry. After this time the original price is shown.</p>
                </div>
                <!-- Expiry status indicator -->
                <div v-if="form.offer_expires_at" class="flex items-end pb-7">
                    <span v-if="isOfferStillActive" class="flex items-center gap-1.5 text-xs text-green-700 bg-green-50 border border-green-200 rounded-lg px-3 py-2">
                        <span class="w-2 h-2 rounded-full bg-green-500 inline-block"></span>
                        Offer active until {{ formatExpiry(form.offer_expires_at) }}
                    </span>
                    <span v-else class="flex items-center gap-1.5 text-xs text-red-700 bg-red-50 border border-red-200 rounded-lg px-3 py-2">
                        <span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span>
                        Offer expired — original price will show
                    </span>
                </div>
            </div>
        </section>

        <!-- Description -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Description</h2>
            <div>
                <label class="label">Short Description</label>
                <textarea v-model="form.short_desc" rows="2" class="input" placeholder="Brief overview shown in room listing cards..."></textarea>
            </div>
            <div>
                <label class="label">Full Description</label>
                <textarea v-model="form.description" rows="4" class="input" placeholder="Detailed description shown on the room details page..."></textarea>
            </div>
        </section>

        <!-- Feature Image -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Feature Image <span class="text-red-500">*</span> <span class="text-gray-400 font-normal text-xs">(main card &amp; hero image)</span></h2>

            <!-- Existing feature image -->
            <div v-if="existingFeatureImage && !removeFeatureImageFlag" class="flex items-start gap-4">
                <img :src="'/storage/' + existingFeatureImage" class="w-40 h-28 object-cover rounded border border-gray-200" alt="Feature image" />
                <div class="flex flex-col gap-2">
                    <span class="text-xs text-gray-500">Current feature image</span>
                    <button type="button" @click="onRemoveFeatureImage"
                        class="text-xs text-red-500 hover:text-red-700 underline w-fit">
                        Remove image
                    </button>
                </div>
            </div>
            <div v-if="removeFeatureImageFlag && existingFeatureImage" class="text-xs text-amber-600 italic">
                Current feature image will be removed on save.
                <button type="button" @click="removeFeatureImageFlag = false" class="underline ml-1">Undo</button>
            </div>

            <!-- Upload / drop feature image -->
            <DropZone
                @change="onFeatureImageChange"
                hint="JPEG/PNG/WebP, max 3MB — recommended 800×600px"
                preview-class="w-full h-44 object-cover"
            />
            <p v-if="form.errors.feature_image" class="text-xs text-red-500 mt-1">{{ form.errors.feature_image }}</p>
        </section>

        <!-- Features -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Room Features</h2>
            <p class="text-xs text-gray-500">List each feature on a separate row (e.g. King size bed, Sofa, Balcony).</p>
            <div v-for="(feat, i) in form.features" :key="i" class="flex gap-2">
                <input v-model="form.features[i]" type="text" class="input flex-1" placeholder="Feature..." />
                <button type="button" @click="form.features.splice(i, 1)"
                    class="px-2 py-1 text-red-400 hover:text-red-600 text-lg leading-none">✕</button>
            </div>
            <button type="button" @click="form.features.push('')"
                class="text-sm text-blue-600 hover:underline">+ Add Feature</button>
        </section>

        <!-- Room Rules -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Room Rules</h2>
            <p class="text-xs text-gray-500">
                Add custom rules for this room type. If left empty, default hotel rules will be shown on the page.
            </p>
            <div v-for="(rule, i) in form.room_rules" :key="i" class="flex gap-2">
                <input v-model="form.room_rules[i]" type="text" class="input flex-1" placeholder="e.g. No smoking allowed" />
                <button type="button" @click="form.room_rules.splice(i, 1)"
                    class="px-2 py-1 text-red-400 hover:text-red-600 text-lg leading-none">✕</button>
            </div>
            <button type="button" @click="form.room_rules.push('')"
                class="text-sm text-blue-600 hover:underline">+ Add Rule</button>
        </section>

        <!-- Amenities -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Amenities</h2>

            <!-- Checkbox grid of DB amenities -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                <label v-for="amenity in allAmenities" :key="amenity.id"
                    class="flex items-center gap-2 p-2.5 border rounded-lg cursor-pointer hover:bg-blue-50 transition-colors"
                    :class="form.amenity_ids.includes(amenity.id) ? 'border-blue-500 bg-blue-50' : 'border-gray-200'">
                    <input type="checkbox" :value="amenity.id" v-model="form.amenity_ids" class="w-4 h-4 text-blue-600 rounded" />
                    <span v-html="amenity.icon_svg" class="w-4 h-4 flex-shrink-0 text-gray-600"></span>
                    <span class="text-sm text-gray-700">{{ amenity.name }}</span>
                </label>
            </div>

            <!-- Quick Add inline -->
            <div class="border-t pt-3">
                <button v-if="!showQuickAdd" type="button" @click="showQuickAdd = true"
                    class="text-sm text-blue-600 hover:underline">+ Create New Amenity</button>
                <div v-else class="flex gap-2 items-end flex-wrap">
                    <div>
                        <label class="label text-xs">Name</label>
                        <input v-model="quickAdd.name" type="text" class="input w-40" placeholder="e.g. Gym" />
                    </div>
                    <div>
                        <label class="label text-xs">Icon SVG (optional)</label>
                        <textarea v-model="quickAdd.icon_svg" rows="1" class="input w-48 text-xs font-mono" placeholder="<svg>...</svg>"></textarea>
                    </div>
                    <button type="button" @click="submitQuickAdd" :disabled="!quickAdd.name.trim()"
                        class="px-4 py-2.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-50">Save &amp; Select</button>
                    <button type="button" @click="showQuickAdd = false; quickAdd = { name: '', icon_svg: '' }"
                        class="px-3 py-2.5 text-sm border border-gray-300 rounded text-gray-600 hover:bg-gray-50">Cancel</button>
                </div>
            </div>
        </section>

        <!-- Gallery -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between border-b pb-2">
                <h2 class="text-sm font-semibold text-gray-700">
                    Gallery Images
                    <span v-if="localGallery.length" class="text-gray-400 font-normal">({{ localGallery.length }})</span>
                </h2>
                <span v-if="localGallery.length" class="text-xs text-gray-400">
                    First image = featured (tall slot on homepage). Drag arrows to reorder.
                </span>
            </div>

            <!-- Saved images with reorder controls -->
            <div v-if="localGallery.length > 0">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    <div v-for="(img, i) in localGallery" :key="img" class="relative group">
                        <!-- Featured badge on first image -->
                        <span v-if="i === 0"
                            class="absolute top-1 left-1 z-10 bg-yellow-400 text-yellow-900 text-xs px-1.5 py-0.5 rounded font-semibold leading-none shadow pointer-events-none">
                            Featured
                        </span>
                        <img :src="'/storage/' + img"
                            :class="['w-full h-24 object-cover rounded-lg border transition-opacity',
                                deletingImage === img ? 'opacity-40 border-red-300'
                                : i === 0 ? 'border-yellow-400 border-2'
                                : 'border-gray-200']" />

                        <!-- Deleting spinner -->
                        <div v-if="deletingImage === img"
                            class="absolute inset-0 flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-500 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                            </svg>
                        </div>

                        <template v-else>
                            <!-- Remove button (top-right) -->
                            <button type="button" @click="$emit('remove-image', img)"
                                class="absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all shadow">
                                ✕
                            </button>

                            <!-- Up / Down reorder arrows (bottom-right) -->
                            <div class="absolute bottom-1 right-1 flex flex-col gap-0.5 opacity-0 group-hover:opacity-100 transition-all">
                                <button type="button"
                                    :disabled="i === 0"
                                    @click="moveGallery(i, i - 1)"
                                    class="bg-white/90 hover:bg-white text-gray-700 rounded w-5 h-5 flex items-center justify-center shadow text-xs disabled:opacity-25 disabled:cursor-not-allowed leading-none">
                                    ▲
                                </button>
                                <button type="button"
                                    :disabled="i === localGallery.length - 1"
                                    @click="moveGallery(i, i + 1)"
                                    class="bg-white/90 hover:bg-white text-gray-700 rounded w-5 h-5 flex items-center justify-center shadow text-xs disabled:opacity-25 disabled:cursor-not-allowed leading-none">
                                    ▼
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Pending new images (not yet saved) -->
            <div v-if="pendingFiles.length > 0">
                <p class="text-xs font-medium text-blue-600 mb-2">Pending — will be uploaded on save</p>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    <div v-for="(item, i) in pendingFiles" :key="i" class="relative group">
                        <img :src="item.preview" class="w-full h-24 object-cover rounded-lg border-2 border-blue-300" />
                        <button type="button" @click="removePendingFile(i)"
                            class="absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all shadow">
                            ✕
                        </button>
                        <span class="absolute bottom-1 left-1 text-xs bg-blue-600 text-white px-1.5 py-0.5 rounded font-medium leading-none">
                            New
                        </span>
                    </div>
                </div>
            </div>

            <!-- Upload / drop zone -->
            <DropZone
                multiple
                @change="onGalleryChange"
                hint="JPEG/PNG/WebP, max 5MB each"
            />
        </section>

        <!-- SEO Settings -->
        <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">SEO Settings</h2>
            <p class="text-xs text-gray-500">Optimise how this room page appears in search engines and social media previews.</p>

            <div>
                <label class="label">Meta Title <span class="text-gray-400 font-normal text-xs">(max 160 chars)</span></label>
                <input v-model="form.meta_title" @input="onMetaTitleInput" type="text" class="input" maxlength="160"
                    :placeholder="form.name ? form.name + ' – Hotel Beach Way' : 'Deluxe Double Room – Hotel Beach Way'" />
                <p class="text-xs text-gray-400 mt-1">{{ (form.meta_title || '').length }}/160 characters</p>
            </div>

            <div>
                <label class="label">Meta Description <span class="text-gray-400 font-normal text-xs">(max 320 chars)</span></label>
                <textarea v-model="form.meta_description" @input="onMetaDescInput" rows="3" class="input" maxlength="320"
                    placeholder="A concise description of the room for search engine results..."></textarea>
                <p class="text-xs text-gray-400 mt-1">{{ (form.meta_description || '').length }}/320 characters</p>
            </div>

            <div>
                <label class="label">Meta Keywords <span class="text-gray-400 font-normal text-xs">(comma-separated)</span></label>
                <input v-model="form.meta_keywords" @input="onMetaKeywordsInput" type="text" class="input" maxlength="500"
                    placeholder="hotel room cox's bazar, deluxe room, sea view room" />
            </div>

            <!-- OG Image -->
            <div class="border-t border-gray-100 pt-4 space-y-3">
                <label class="label">Open Graph / Social Share Image <span class="text-gray-400 font-normal text-xs">(recommended 1200×630px)</span></label>

                <div v-if="existingOgImage && !removeOgImageFlag" class="flex items-start gap-4">
                    <img :src="'/storage/' + existingOgImage" class="w-40 h-24 object-cover rounded border border-gray-200" alt="OG image" />
                    <div class="flex flex-col gap-2">
                        <span class="text-xs text-gray-500">Current OG image</span>
                        <button type="button" @click="onRemoveOgImage" class="text-xs text-red-500 hover:text-red-700 underline w-fit">Remove</button>
                    </div>
                </div>
                <div v-if="removeOgImageFlag && existingOgImage" class="text-xs text-amber-600 italic">
                    OG image will be removed on save.
                    <button type="button" @click="removeOgImageFlag = false" class="underline ml-1">Undo</button>
                </div>

                <DropZone
                    @change="onOgImageChange"
                    hint="JPEG/PNG/WebP, max 3MB — recommended 1200×630px"
                    preview-class="w-full h-36 object-cover"
                />
            </div>
        </section>

        <!-- Submit -->
        <div class="flex justify-end gap-3">
            <Link :href="route('admin.room-types.index')"
                class="px-5 py-2 text-sm border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                Cancel
            </Link>
            <button type="submit" :disabled="form.processing"
                class="px-6 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-60">
                {{ form.processing ? 'Saving...' : 'Save Room Type' }}
            </button>
        </div>
    </form>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import FlatpickrInput from '@/Components/Admin/Shared/FlatpickrInput.vue';
import DropZone from '@/Components/Admin/Shared/DropZone.vue';
import { useSeoAutoFill } from '@/Composables/useSeoAutoFill';

const props = defineProps({
    form:                 Object,
    allAmenities:         { type: Array,  default: () => [] },
    existingGallery:      { type: Array,  default: () => [] },
    deletingImage:        { type: String, default: null },
    existingFeatureImage: { type: String, default: null },
    existingOgImage:      { type: String, default: null },
});

// Quick-add a new amenity inline
const showQuickAdd = ref(false);
const quickAdd = ref({ name: '', icon_svg: '' });

function submitQuickAdd() {
    if (!quickAdd.value.name.trim()) return;
    router.post(route('admin.room-amenities.store'), {
        name:       quickAdd.value.name.trim(),
        icon_svg:   quickAdd.value.icon_svg.trim(),
        sort_order: 0,
        is_active:  true,
    }, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (page) => {
            // The new amenity will appear after the page refreshes via Inertia
            // Find it in the updated allAmenities and select it
            const newAmenity = page.props.allAmenities?.find(
                a => a.name === quickAdd.value.name.trim()
            );
            if (newAmenity && !props.form.amenity_ids.includes(newAmenity.id)) {
                props.form.amenity_ids.push(newAmenity.id);
            }
            quickAdd.value = { name: '', icon_svg: '' };
            showQuickAdd.value = false;
        },
    });
}

const discountedBdt = computed(() => {
    const price = parseFloat(props.form.price);
    const val   = parseFloat(props.form.discount_value_bdt);
    if (!price || !val || !props.form.discount_type_bdt) return null;
    const result = props.form.discount_type_bdt === 'percentage'
        ? price - (price * val / 100)
        : price - val;
    return Math.max(result, 0);
});

const discountedUsd = computed(() => {
    const price = parseFloat(props.form.price_usd);
    const val   = parseFloat(props.form.discount_value_usd);
    if (!price || !val || !props.form.discount_type_usd) return null;
    const result = props.form.discount_type_usd === 'percentage'
        ? price - (price * val / 100)
        : price - val;
    return Math.max(result, 0);
});

const isOfferStillActive = computed(() => {
    if (!props.form.offer_expires_at) return true;
    return new Date(props.form.offer_expires_at) > new Date();
});

function formatExpiry(val) {
    if (!val) return '';
    return new Date(val).toLocaleString('en-GB', {
        day: '2-digit', month: 'short', year: 'numeric',
        hour: '2-digit', minute: '2-digit',
    });
}

const emit = defineEmits(['submit', 'remove-image', 'remove-feature-image', 'remove-og-image', 'reorder']);

const { onMetaTitleInput, onMetaDescInput, onMetaKeywordsInput } = useSeoAutoFill(props.form, {
    titleSource: () => props.form.name,
    descSource:  () => props.form.short_desc,
});

// Local mutable copy of saved gallery for reordering
const localGallery = ref([...props.existingGallery]);
watch(() => props.existingGallery, (val) => { localGallery.value = [...val]; });

function moveGallery(from, to) {
    const arr = [...localGallery.value];
    const [item] = arr.splice(from, 1);
    arr.splice(to, 0, item);
    localGallery.value = arr;
    emit('reorder', arr);
}

const pendingFiles           = ref([]);   // [{ file: File, preview: string }]
const removeFeatureImageFlag = ref(false);
const removeOgImageFlag      = ref(false);

const slugPreview = computed(() => {
    return (props.form.name || '')
        .toLowerCase()
        .trim()
        .replace(/[^\w\s-]/g, '')
        .replace(/[\s_-]+/g, '-')
        .replace(/^-+|-+$/g, '');
});

function onGalleryChange(files) {
    // Append new selections — don't replace existing pending files
    files.forEach(f => pendingFiles.value.push({ file: f, preview: URL.createObjectURL(f) }));
    props.form.gallery_images = pendingFiles.value.map(p => p.file);
}

function removePendingFile(index) {
    URL.revokeObjectURL(pendingFiles.value[index].preview);
    pendingFiles.value.splice(index, 1);
    props.form.gallery_images = pendingFiles.value.map(p => p.file);
}

function onFeatureImageChange(file) {
    props.form.feature_image = file;
    removeFeatureImageFlag.value = false;
    props.form.remove_feature_image = false;
}

function onRemoveFeatureImage() {
    removeFeatureImageFlag.value = true;
    props.form.remove_feature_image = true;
    props.form.feature_image = null;
    emit('remove-feature-image');
}

function onOgImageChange(file) {
    props.form.og_image = file;
    removeOgImageFlag.value = false;
    props.form.remove_og_image = false;
}

function onRemoveOgImage() {
    removeOgImageFlag.value = true;
    props.form.remove_og_image = true;
    props.form.og_image = null;
    emit('remove-og-image');
}
</script>

<style scoped>
.label { @apply block text-sm font-medium text-gray-700 mb-1.5; }
.input { @apply w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors bg-white; }
select.input { @apply cursor-pointer; }
textarea.input { @apply resize-none; }
</style>
