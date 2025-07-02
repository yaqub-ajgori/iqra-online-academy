<template>
  <AdminLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Edit Course</h1>
          <p class="text-gray-600 mt-1">Update course information</p>
        </div>
        <div class="flex space-x-3">
          <Link
            :href="route('admin.courses.builder', course)"
            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all h-9 px-4 py-2 bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] text-white hover:opacity-90 shadow-xs"
          >
            <Icon name="Layers" class="h-4 w-4 mr-2" />
            Open Course Builder
          </Link>
          <Link
            :href="route('admin.courses.index')"
            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all h-9 px-4 py-2 border bg-background shadow-xs hover:bg-accent hover:text-accent-foreground"
          >
            <Icon name="ArrowLeft" class="h-4 w-4 mr-2" />
            Back
          </Link>
        </div>
      </div>

      <!-- Form -->
      <form @submit.prevent="submit" class="space-y-8">
        <!-- Section 1: Core Details -->
        <Card>
          <CardHeader>
            <CardTitle class="flex items-center gap-2 text-gray-900">
              <Icon name="BookOpen" class="w-5 h-5 text-blue-600" />
              Basic Information
            </CardTitle>
          </CardHeader>
          <CardContent class="space-y-6">
            <!-- Course Title (full width) -->
            <div>
              <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                Course Title <span class="text-red-500">*</span>
              </label>
              <input
                id="title"
                v-model="form.title"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="e.g., Quran Learning - Beginner Level"
                required
              />
              <div v-if="errors?.title" class="mt-1 text-sm text-red-600">{{ errors.title }}</div>
            </div>
            <!-- Category & Level (side by side) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                  Category
                </label>
                <select
                  id="category_id"
                  v-model="form.category_id"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                  <option value="">Select Category (Optional)</option>
                  <option v-for="category in categories" :key="category.id" :value="category.id">
                    {{ category.name }}
                  </option>
                </select>
                <div v-if="errors?.category_id" class="mt-1 text-sm text-red-600">{{ errors.category_id }}</div>
              </div>
              <div>
                <label for="level" class="block text-sm font-medium text-gray-700 mb-2">
                  Course Level <span class="text-red-500">*</span>
                </label>
                <select
                  id="level"
                  v-model="form.level"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  required
                >
                  <option value="">Select Level</option>
                  <option value="beginner">Beginner</option>
                  <option value="intermediate">Intermediate</option>
                  <option value="advanced">Advanced</option>
                </select>
                <div v-if="errors?.level" class="mt-1 text-sm text-red-600">{{ errors.level }}</div>
              </div>
            </div>
            <!-- Primary Instructor (full width) -->
            <div>
              <label for="instructor_id" class="block text-sm font-medium text-gray-700 mb-2">
                Primary Instructor <span class="text-red-500">*</span>
              </label>
              <select
                id="instructor_id"
                v-model="form.instructor_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                required
              >
                <option value="">Select Instructor</option>
                <option v-for="instructor in instructors" :key="instructor.id" :value="instructor.id">
                  {{ instructor.full_name }}
                </option>
              </select>
              <div v-if="errors?.instructor_id" class="mt-1 text-sm text-red-600">{{ errors.instructor_id }}</div>
            </div>
          </CardContent>
        </Card>
        <!-- Section 2: Description -->
        <Card>
          <CardHeader>
            <CardTitle class="flex items-center gap-2 text-gray-900">
              <Icon name="AlignLeft" class="w-5 h-5 text-indigo-600" />
              Description
            </CardTitle>
          </CardHeader>
          <CardContent>
            <RichTextEditor
              v-model="form.full_description"
              label="Course Description"
              placeholder="Write a detailed description of the course..."
              :error="errors?.full_description"
              input-id="full_description"
              min-height="250px"
            />
          </CardContent>
        </Card>
        <!-- Section 3: Media -->
        <Card>
          <CardHeader>
            <CardTitle class="flex items-center gap-2 text-gray-900">
              <Icon name="Image" class="w-5 h-5 text-purple-600" />
              Media
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="thumbnail_image" class="block text-sm font-medium text-gray-700 mb-2">
                  Thumbnail Image
                  <span class="ml-1 text-xs text-gray-400">(JPG, PNG, WEBP max 2MB)</span>
                </label>
                <div class="flex flex-col gap-2">
                  <input
                    id="thumbnail_image"
                    @change="handleImageUpload"
                    type="file"
                    accept="image/*"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 file:mr-3 file:py-1 file:px-3 file:border-0 file:text-sm file:font-medium file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100"
                  />
                  <div v-if="imagePreview || currentImageUrl" class="flex items-center gap-3 mt-2">
                    <img :src="imagePreview || currentImageUrl" alt="Preview" class="w-32 h-20 object-cover rounded-lg border" />
                    <Button type="button" variant="ghost" size="sm" @click="removeImage">
                      <Icon name="X" class="h-4 w-4 mr-1" /> Remove
                    </Button>
                  </div>
                </div>
                <p class="mt-1 text-xs text-gray-500">Optional. Upload a course thumbnail image for better visibility.</p>
                <div v-if="errors?.thumbnail_image" class="mt-1 text-sm text-red-600">{{ errors.thumbnail_image }}</div>
              </div>
              <div></div>
            </div>
          </CardContent>
        </Card>
        <!-- Section 4: Pricing & Duration -->
        <Card>
          <CardHeader>
            <CardTitle class="flex items-center gap-2 text-gray-900">
              <Icon name="CreditCard" class="w-5 h-5 text-green-600" />
              Pricing & Duration
            </CardTitle>
          </CardHeader>
          <CardContent class="space-y-6">
            <!-- Free Course Toggle (full width) -->
            <div class="flex items-center gap-2 mb-2">
              <input
                id="is_free"
                v-model="form.is_free"
                type="checkbox"
                class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
              />
              <label for="is_free" class="block text-sm text-gray-900">
                This is a free course
              </label>
              <span class="ml-2 text-xs text-gray-400">(If checked, price fields will be hidden)</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Price (left) -->
              <div v-if="!form.is_free">
                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                  Course Price <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 sm:text-sm">৳</span>
                  </div>
                  <input
                    id="price"
                    v-model="form.price"
                    type="number"
                    min="0"
                    step="0.01"
                    class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    placeholder="0.00"
                    :required="!form.is_free"
                  />
                </div>
                <div v-if="errors?.price" class="mt-1 text-sm text-red-600">{{ errors.price }}</div>
              </div>
              <!-- Discount Price (right) -->
              <div v-if="!form.is_free">
                <label for="discount_price" class="block text-sm font-medium text-gray-700 mb-2">
                  Discount Price
                </label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 sm:text-sm">৳</span>
                  </div>
                  <input
                    id="discount_price"
                    v-model="form.discount_price"
                    type="number"
                    min="0"
                    step="0.01"
                    class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    placeholder="0.00"
                  />
                </div>
                <div v-if="errors?.discount_price" class="mt-1 text-sm text-red-600">{{ errors.discount_price }}</div>
              </div>
              <!-- Currency (left) -->
              <div v-if="!form.is_free">
                <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">
                  Currency
                </label>
                <select
                  id="currency"
                  v-model="form.currency"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                >
                  <option value="BDT">BDT (৳)</option>
                  <option value="USD">USD ($)</option>
                  <option value="EUR">EUR (€)</option>
                </select>
                <div v-if="errors?.currency" class="mt-1 text-sm text-red-600">{{ errors.currency }}</div>
              </div>
              <!-- Discount Expires At (right) -->
              <div v-if="!form.is_free && form.discount_price">
                <label for="discount_expires_at" class="block text-sm font-medium text-gray-700 mb-2">
                  Discount Expires At
                </label>
                <input
                  id="discount_expires_at"
                  v-model="form.discount_expires_at"
                  type="datetime-local"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                />
                <div v-if="errors?.discount_expires_at" class="mt-1 text-sm text-red-600">{{ errors.discount_expires_at }}</div>
              </div>
              <!-- Duration (right) -->
              <div>
                <label for="duration_hours" class="block text-sm font-medium text-gray-700 mb-2">
                  Duration (Hours)
                </label>
                <input
                  id="duration_hours"
                  v-model="form.duration_hours"
                  type="number"
                  min="1"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                  placeholder="e.g., 40"
                />
                <div v-if="errors?.duration_hours" class="mt-1 text-sm text-red-600">{{ errors.duration_hours }}</div>
              </div>
            </div>
          </CardContent>
        </Card>
        <!-- Section 5: Settings -->
        <Card>
          <CardHeader>
            <CardTitle class="flex items-center gap-2 text-gray-900">
              <Icon name="Settings" class="w-5 h-5 text-gray-700" />
              Course Settings
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Status (left) -->
              <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                  Course Status <span class="text-red-500">*</span>
                </label>
                <select
                  id="status"
                  v-model="form.status"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700 focus:border-gray-700"
                  required
                >
                  <option value="draft">Draft</option>
                  <option value="review">Under Review</option>
                  <option value="published">Published</option>
                  <option value="archived">Archived</option>
                </select>
                <div v-if="errors?.status" class="mt-1 text-sm text-red-600">{{ errors.status }}</div>
              </div>
              <!-- Featured (right) -->
              <div class="flex items-center pt-8">
                <input
                  id="is_featured"
                  v-model="form.is_featured"
                  type="checkbox"
                  class="h-4 w-4 text-gray-700 focus:ring-gray-700 border-gray-300 rounded"
                />
                <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                  Mark as Featured Course
                </label>
                <span class="ml-2 text-xs text-gray-400">(Featured courses are highlighted in listings)</span>
              </div>
            </div>
          </CardContent>
        </Card>
        <!-- Section 6: Actions -->
        <Card>
          <CardContent class="pt-6">
            <div class="flex flex-col md:flex-row md:justify-between gap-3">
              <Link 
                :href="route('admin.courses.index')"
                :class="buttonVariants({ variant: 'outline' })"
              >
                Cancel
              </Link>
              <Button
                type="submit"
                variant="primary"
                :disabled="processing"
              >
                {{ processing ? 'Updating...' : 'Update Course' }}
              </Button>
            </div>
          </CardContent>
        </Card>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import Icon from '@/components/Icon.vue'
import RichTextEditor from '@/components/RichTextEditor.vue'
import { useToast } from '@/composables/useToast'
import { buttonVariants } from '@/lib/utils'

// Toast composable
const { success, error } = useToast()

// Props
interface Props {
  course: any
  categories: Array<{
    id: number
    name: string
  }>
  instructors: Array<{
    id: number
    full_name: string
    user_id: number
  }>
  errors?: Record<string, string>
}

const props = defineProps<Props>()

const processing = ref(false)
const imagePreview = ref<string | null>(null)
const currentImageUrl = ref<string | undefined>(props.course.thumbnail_image || undefined)

// Helper function to format datetime for datetime-local input
const formatDateTime = (datetime: string | null | undefined): string => {
  if (!datetime) return ''
  return new Date(datetime).toISOString().slice(0, 16)
}

// Form state using Inertia's useForm helper
const form = useForm({
  title: props.course.title || '',
  full_description: props.course.full_description || '',
  category_id: props.course.category_id || '',
  level: props.course.level || '',
  instructor_id: props.course.instructor_id || '',
  thumbnail_image: null as File | null, // Changed to handle file uploads

  price: props.course.price || 0,
  currency: props.course.currency || 'BDT',
  discount_price: props.course.discount_price || null,
  discount_expires_at: formatDateTime(props.course.discount_expires_at),
  duration_hours: props.course.duration_hours || null,
  status: props.course.status || 'draft',
  is_featured: props.course.is_featured || false,
  is_free: props.course.is_free || false,
})

// Image upload handling
const handleImageUpload = (event: Event) => {
  const target = event.target as HTMLInputElement
  const file = target.files?.[0]
  
  if (file) {
    // Validate file size (max 2MB)
    if (file.size > 2 * 1024 * 1024) {
      error('Image size must be less than 2MB')
      target.value = ''
      return
    }
    
    // Validate file type
    if (!file.type.startsWith('image/')) {
      error('Please select a valid image file')
      target.value = ''
      return
    }
    
    form.thumbnail_image = file
    
    // Create preview
    const reader = new FileReader()
    reader.onload = (e) => {
      imagePreview.value = e.target?.result as string
    }
    reader.readAsDataURL(file)
  }
}

const removeImage = () => {
  form.thumbnail_image = null
  imagePreview.value = null
  
  // Clear the file input
  const fileInput = document.getElementById('thumbnail_image') as HTMLInputElement
  if (fileInput) {
    fileInput.value = ''
  }
}

const submit = () => {
  processing.value = true
  
  form.put(route('admin.courses.update', props.course), {
    onSuccess: () => {
      success('কোর্স সফলভাবে আপডেট হয়েছে।')
    },
    onError: () => {
      error('কোর্স আপডেট করতে সমস্যা হয়েছে।')
    },
    onFinish: () => {
      processing.value = false
    }
  })
}
</script> 