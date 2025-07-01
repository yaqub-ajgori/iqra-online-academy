<template>
  <AdminLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Edit Category</h1>
          <p class="text-gray-600 mt-1">Edit {{ category.name }} category</p>
        </div>
        <Link
          :href="route('admin.categories.index')"
          :class="buttonVariants({ variant: 'ghost', size: 'icon-sm' })"
          title="Back to Categories"
        >
          <Icon name="ArrowLeft" class="h-4 w-4" />
        </Link>
      </div>

      <!-- Form -->
      <form @submit.prevent="submit" class="space-y-8">
        <!-- Basic Information -->
        <Card>
          <CardHeader>
            <CardTitle class="flex items-center text-gray-900">
              <Icon name="Folder" class="w-5 h-5 mr-2 text-[#d4a574]" />
              Basic Information
            </CardTitle>
            <CardDescription>
              Update the basic details for this category
            </CardDescription>
          </CardHeader>
          <CardContent class="space-y-6">
            <!-- Category Name -->
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                Category Name <span class="text-red-500">*</span>
              </label>
              <input
                id="name"
                v-model="form.name"
                type="text"
                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="e.g., Islamic Studies, Quran, Hadith"
                required
              />
              <div v-if="form.errors?.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</div>
            </div>

            <!-- Slug Display -->
            <div>
              <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                URL Slug
              </label>
              <input
                id="slug"
                :value="category.slug"
                type="text"
                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-50 text-gray-600"
                disabled
              />
              <p class="mt-1 text-xs text-gray-500">URL slug will be automatically updated based on the category name</p>
            </div>

            <!-- Category Description -->
            <div>
              <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                Description
              </label>
              <textarea
                id="description"
                v-model="form.description"
                rows="4"
                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Provide a brief description to help students understand what this category includes..."
              />
              <div v-if="form.errors?.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</div>
              <p class="mt-1 text-xs text-gray-500">Optional. This description will be visible to students browsing categories</p>
            </div>
          </CardContent>
        </Card>

        <!-- Category Settings -->
        <Card>
          <CardHeader>
            <CardTitle class="flex items-center text-gray-900">
              <Icon name="Settings" class="w-5 h-5 mr-2 text-[#d4a574]" />
              Category Settings
            </CardTitle>
            <CardDescription>
              Configure the visibility and status of this category
            </CardDescription>
          </CardHeader>
          <CardContent>
            <div>
              <div class="flex items-center">
                <input
                  id="is_active"
                  v-model="form.is_active"
                  type="checkbox"
                  class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                />
                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                  Make category active
                </label>
              </div>
              <p class="mt-1 text-xs text-gray-500">Active categories will be visible to students on the frontend</p>
            </div>
          </CardContent>
        </Card>

        <!-- Category Overview -->
        <Card>
          <CardHeader>
            <CardTitle class="flex items-center text-gray-900">
              <Icon name="Info" class="w-5 h-5 mr-2 text-[#d4a574]" />
              Category Overview
            </CardTitle>
            <CardDescription>
              Current category information and statistics
            </CardDescription>
          </CardHeader>
          <CardContent class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div class="bg-blue-50 p-4 rounded-lg">
                <div class="flex items-center">
                  <Icon name="BookOpen" class="h-5 w-5 text-blue-600 mr-2" />
                  <div>
                    <p class="text-sm font-medium text-blue-600">Courses</p>
                    <p class="text-lg font-semibold text-blue-900">{{ category.courses_count }}</p>
                  </div>
                </div>
              </div>

              <div class="bg-green-50 p-4 rounded-lg">
                <div class="flex items-center">
                  <Icon name="Eye" class="h-5 w-5 text-green-600 mr-2" />
                  <div>
                    <p class="text-sm font-medium text-green-600">Status</p>
                    <p class="text-lg font-semibold text-green-900">
                      {{ category.is_active ? 'Active' : 'Inactive' }}
                    </p>
                  </div>
                </div>
              </div>

              <div class="bg-gray-50 p-4 rounded-lg">
                <div class="flex items-center">
                  <Icon name="Calendar" class="h-5 w-5 text-gray-600 mr-2" />
                  <div>
                    <p class="text-sm font-medium text-gray-600">Created</p>
                    <p class="text-lg font-semibold text-gray-900">
                      {{ new Date(category.created_at).toLocaleDateString() }}
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Warning for Course Deletion -->
            <div v-if="category.courses_count > 0" class="bg-amber-50 border border-amber-200 rounded-lg p-4">
              <div class="flex items-start">
                <Icon name="AlertTriangle" class="h-5 w-5 text-amber-600 mr-2 mt-0.5" />
                <div>
                  <p class="text-sm font-medium text-amber-800">Category in use</p>
                  <p class="text-sm text-amber-700 mt-1">
                    This category contains {{ category.courses_count }} course{{ category.courses_count === 1 ? '' : 's' }}. 
                    Move courses to another category before deletion.
                  </p>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Action Buttons -->
        <Card>
          <CardContent class="pt-6">
            <div class="flex space-x-3 justify-end">
              <Link
                :href="route('admin.categories.index')"
                :class="buttonVariants({ variant: 'outline' })"
              >
                Cancel
              </Link>
              
              <Button
                type="submit"
                :disabled="form.processing"
                variant="primary"
              >
                <Icon v-if="form.processing" name="Loader2" class="h-4 w-4 animate-spin" />
                <Icon v-else name="Save" class="h-4 w-4" />
                {{ form.processing ? 'Updating...' : 'Update Category' }}
              </Button>
            </div>
          </CardContent>
        </Card>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card'
import Icon from '@/components/Icon.vue'
import { useToast } from '@/composables/useToast'
import { buttonVariants } from '@/lib/utils'

// Toast composable
const { success, error } = useToast()

// Props
interface Props {
  category: {
    id: number
    name: string
    slug: string
    description?: string
    is_active: boolean
    courses_count: number
    created_at: string
    updated_at: string
  }
}

const props = defineProps<Props>()

// Form state
const form = useForm({
  name: props.category.name,
  description: props.category.description || '',
  is_active: props.category.is_active,
})

// Methods
const submit = () => {
  form.put(route('admin.categories.update', props.category.id), {
    onSuccess: () => {
      success('Course category updated successfully.')
    },
    onError: () => {
      error('Failed to update category. Please check the form and try again.')
    }
  })
}
</script> 
