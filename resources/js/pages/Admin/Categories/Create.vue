<template>
  <AdminLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">New Course Category</h1>
          <p class="text-gray-600 mt-1">Create a new course category</p>
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
              Enter the basic details for your new category
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
                <Icon v-else name="Plus" class="h-4 w-4" />
                {{ form.processing ? 'Creating...' : 'Create Category' }}
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

// Form state
const form = useForm({
  name: '',
  description: '',
  is_active: true,
})

// Methods
const submit = () => {
  form.post(route('admin.categories.store'), {
    onSuccess: () => {
      success('Course category created successfully.')
    },
    onError: () => {
      error('Failed to create category. Please check the form and try again.')
    }
  })
}
</script> 