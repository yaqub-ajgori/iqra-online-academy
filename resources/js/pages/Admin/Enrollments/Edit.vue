<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import EnrollmentForm from '@/components/Admin/Forms/EnrollmentForm.vue'
import { Card, CardContent } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import Icon from '@/components/Icon.vue'
import { useToast } from '@/composables/useToast'

const props = defineProps<{
  enrollment: App.Data.EnrollmentData
  students: { id: number; name: string; email: string }[]
  courses: { id: number; title: string; price: number; is_free: boolean }[]
  payments: { id: number; transaction_id: string; amount: number; student_name: string; course_title: string }[]
}>()

const { success, error } = useToast()

const form = useForm({
  student_id: props.enrollment.student_id.toString(),
  course_id: props.enrollment.course_id.toString(),
  enrolled_at: new Date(props.enrollment.enrolled_at).toISOString().slice(0, 16),
  enrollment_type: props.enrollment.enrollment_type,
  payment_id: props.enrollment.payment_id?.toString() || '',
  amount_paid: props.enrollment.amount_paid.toString(),
  currency: props.enrollment.currency,
  payment_status: props.enrollment.payment_status,
  progress_percentage: props.enrollment.progress_percentage.toString(),
  lessons_completed: props.enrollment.lessons_completed.toString(),
  is_completed: props.enrollment.is_completed,
  is_active: props.enrollment.is_active,
})

function submit() {
  form.put(route('admin.enrollments.update', props.enrollment.id), {
    onSuccess: () => {
      success('Enrollment updated successfully.')
    },
    onError: () => {
      error('Failed to update enrollment. Please check the form and try again.')
    }
  })
}
</script>

<template>
  <AdminLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Edit Enrollment</h1>
          <p class="text-gray-600 mt-1">Update the details of an existing enrollment</p>
        </div>
        <Link
          :href="route('admin.enrollments.index')"
          class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all h-9 px-4 py-2 border bg-background shadow-xs hover:bg-accent hover:text-accent-foreground"
        >
          <Icon name="ArrowLeft" class="h-4 w-4 mr-2" />
          Back
        </Link>
      </div>

      <form @submit.prevent="submit" class="space-y-8">
        <EnrollmentForm
          :form="form"
          :students="students"
          :courses="courses"
          :payments="payments"
          mode="edit"
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
                <Icon name="Save" class="h-4 w-4 mr-2" />
                {{ form.processing ? 'Updating...' : 'Update Enrollment' }}
              </Button>
            </div>
          </CardContent>
        </Card>
      </form>
    </div>
  </AdminLayout>
</template> 