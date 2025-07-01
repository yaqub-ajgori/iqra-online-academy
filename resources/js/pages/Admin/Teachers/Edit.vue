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
              <h1 class="text-2xl font-bold text-gray-900">Edit Teacher</h1>
              <p class="text-sm text-gray-600">Update teacher information</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Form -->
    <form @submit.prevent="submit" class="space-y-6">
      <TeacherForm 
        :form="form" 
        mode="edit" 
        :teacher="teacher"
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
              <Icon v-if="form.processing" name="Loader2" class="h-4 w-4 animate-spin mr-2" />
              <Icon v-else name="Save" class="h-4 w-4 mr-2" />
              {{ form.processing ? 'Updating...' : 'Update Teacher' }}
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
// Teacher type is available globally as App.Data.Teacher

const props = defineProps<{
  teacher: App.Data.Teacher
}>()

// Toast composable
const { success, error } = useToast()

const form = useForm({
  full_name: props.teacher.full_name,
  speciality: props.teacher.speciality || '',
  experience: props.teacher.experience || '',
  profile_picture: null as File | null,
  phone: props.teacher.phone || '',
  is_active: props.teacher.is_active,
})

function submit() {
  form.put(route('admin.teachers.update', props.teacher.id), {
    onSuccess: () => {
      success('Teacher updated successfully.')
    },
    onError: () => {
      error('Failed to update teacher.')
    }
  })
}
</script> 