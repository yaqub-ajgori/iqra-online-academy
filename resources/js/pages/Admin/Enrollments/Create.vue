<template>
  <AdminLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Create New Enrollment</h1>
          <p class="text-gray-600 mt-1">Add a new student enrollment to a course</p>
        </div>
        <Link
          :href="route('admin.enrollments.index')"
          class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
        >
          <Icon name="ArrowLeft" class="h-4 w-4" />
          Back to Enrollments
        </Link>
      </div>

      <form @submit.prevent="submit" class="space-y-8">
        <EnrollmentForm
          :form="form"
          :students="students"
          :courses="courses"
          :payments="payments"
          mode="create"
        />
        <Card>
          <CardContent class="pt-6">
            <div class="flex space-x-3 justify-end">
              <Button
                type="button"
                variant="outline"
                @click="$inertia.visit(route('admin.enrollments.index'))"
              >
                <Icon name="X" class="h-4 w-4 mr-2" />
                Cancel
              </Button>
              <Button 
                type="submit" 
                :disabled="form.processing"
                variant="primary"
              >
                <Icon name="Check" class="h-4 w-4 mr-2" />
                {{ form.processing ? 'Creating...' : 'Create Enrollment' }}
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
import EnrollmentForm from '@/components/Admin/Forms/EnrollmentForm.vue'
import { Card, CardContent } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import Icon from '@/components/Icon.vue'
import { useToast } from '@/composables/useToast'

const props = defineProps<{ students: any[]; courses: any[]; payments: any[] }>()
const { success, error } = useToast()

const form = useForm({
  student_id: '',
  course_id: '',
  enrolled_at: new Date().toISOString().slice(0, 16),
  enrollment_type: '',
  payment_id: '',
  amount_paid: '0',
  currency: 'BDT',
  payment_status: 'pending',
  progress_percentage: '0',
  lessons_completed: '0',
  is_completed: false,
  is_active: true,
})

function submit() {
  form.post(route('admin.enrollments.store'), {
    onSuccess: () => {
      success('Enrollment created successfully!')
    },
    onError: () => {
      error('Failed to create enrollment. Please check the form and try again.')
    }
  })
}
</script> 