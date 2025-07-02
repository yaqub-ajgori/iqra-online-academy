<template>
  <AdminLayout>
    <!-- Page Header -->
    <div class="mb-8">
      <div class="flex items-center justify-between">
        <div>
          <div class="flex items-center space-x-3">
            <Link 
              :href="route('admin.teachers.index')"
              :class="buttonVariants({ variant: 'ghost', size: 'icon-sm' })"
              title="Back to Teachers"
            >
              <Icon name="ArrowLeft" class="h-4 w-4" />
            </Link>
            <div>
              <h1 class="text-2xl font-bold text-gray-900">Create New Teacher</h1>
              <p class="text-sm text-gray-600">Add a new teacher to the system</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Form -->
    <form @submit.prevent="submit" class="space-y-6">
      <TeacherForm 
        :form="form" 
        mode="create" 
      />

      <!-- Action Buttons -->
      <Card>
        <CardContent class="pt-6">
          <div class="flex space-x-3 justify-end">
            <Button
              type="button"
              variant="outline"
              @click="$inertia.visit(route('admin.teachers.index'))"
            >
              <Icon name="X" class="h-4 w-4 mr-2" />
              Cancel
            </Button>
            
            <Button
              type="submit"
              :disabled="form.processing"
              variant="primary"
            >
              <Icon v-if="!form.processing" name="Plus" class="h-4 w-4 mr-2" />
              {{ form.processing ? 'Creating...' : 'Create Teacher' }}
            </Button>
          </div>
        </CardContent>
      </Card>
    </form>
  </AdminLayout>
</template>

<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import TeacherForm from '@/components/Admin/Forms/TeacherForm.vue'
import { Card, CardContent } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import Icon from '@/components/Icon.vue'
import { useToast } from '@/composables/useToast'
import { buttonVariants } from '@/lib/utils'

// Toast composable
const { success, error } = useToast()

const form = useForm({
  full_name: '',
  speciality: '',
  experience: '',
  profile_picture: null as File | null,
  phone: '',
  is_active: true,
})

function submit() {
  form.post(route('admin.teachers.store'), {
    onSuccess: () => {
      success('Teacher created successfully.')
    },
    onError: () => {
      error('Failed to create teacher.')
    }
  })
}
</script> 