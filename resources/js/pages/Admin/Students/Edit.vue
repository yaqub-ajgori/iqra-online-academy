<template>
  <AdminLayout>
    <!-- Page Header -->
    <div class="mb-8">
      <div class="flex items-center justify-between">
        <div>
          <div class="flex items-center space-x-3">
            <Link 
              :href="route('admin.students.index')"
              :class="buttonVariants({ variant: 'ghost', size: 'icon-sm' })"
              title="Back to Students"
            >
              <Icon name="ArrowLeft" class="h-4 w-4" />
            </Link>
            <div>
              <h1 class="text-2xl font-bold text-gray-900">Edit Student</h1>
              <p class="text-sm text-gray-600">Update student information</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <form @submit.prevent="submit" class="space-y-6">
      <StudentForm :form="form" mode="edit" :current-profile-picture="student.profile_picture" />
      <Card>
        <CardContent class="pt-6">
          <div class="flex space-x-3 justify-end">
            <Button
              type="button"
              variant="outline"
              @click="$inertia.visit(route('admin.students.index'))"
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
              {{ form.processing ? 'Updating...' : 'Update Student' }}
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
import StudentForm from '@/components/Admin/Forms/StudentForm.vue'
import { Card, CardContent } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import Icon from '@/components/Icon.vue'
import { useToast } from '@/composables/useToast'
import { buttonVariants } from '@/lib/utils'

const props = defineProps<{
  student: App.Data.Student
}>()

const { success, error } = useToast()

const form = useForm({
  full_name: props.student.full_name,
  phone: props.student.phone,
  date_of_birth: props.student.date_of_birth,
  gender: props.student.gender,
  address: props.student.address,
  city: props.student.city,
  district: props.student.district,
  country: props.student.country,
  postal_code: props.student.postal_code,
  profile_picture: null as File | null,
  bio: props.student.bio,
  occupation: props.student.occupation,
  education_level: props.student.education_level,
  is_active: props.student.is_active,
  is_verified: props.student.is_verified,
})

function submit() {
  form.put(route('admin.students.update', props.student.id), {
    onSuccess: () => {
      success('Student updated successfully.')
    },
    onError: () => {
      error('Failed to update student.')
    }
  })
}
</script> 