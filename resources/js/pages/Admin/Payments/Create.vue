<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import PaymentForm from '@/components/Admin/Forms/PaymentForm.vue'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import Icon from '@/components/Icon.vue'
import { useToast } from '@/composables/useToast'

const props = defineProps<{
  students: { id: number; name: string; email: string }[]
  courses: { id: number; title: string; price: number; is_free: boolean; currency: string }[]
}>()

// Toast composable
const { success, error } = useToast()

const form = useForm({
  student_id: null,
  course_id: null,
  amount: 0.00,
  currency: 'BDT',
  payment_method: 'bkash',
  status: 'pending',
  transaction_id: '',
  sender_number: '',
  receiver_number: '',
  bank_name: '',
  account_number: '',
  branch_name: '',
})

function submit() {
  form.post(route('admin.payments.store'), {
    onSuccess: () => {
      success('Payment created successfully.')
    },
    onError: () => {
      error('Failed to create payment. Please check the form and try again.')
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
          <h1 class="text-2xl font-bold text-gray-900">New Payment</h1>
          <p class="text-gray-600 mt-1">Record a new manual payment for a student</p>
        </div>
        <Link
          :href="route('admin.payments.index')"
          class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all h-9 px-4 py-2 border bg-background shadow-xs hover:bg-accent hover:text-accent-foreground"
        >
          <Icon name="ArrowLeft" class="h-4 w-4 mr-2" />
          Back
        </Link>
      </div>

      <!-- Form -->
      <form @submit.prevent="submit" class="space-y-8">
        <!-- Payment Information -->
        <Card>
          <CardHeader>
            <CardTitle class="flex items-center text-gray-900">
              <Icon name="CreditCard" class="w-5 h-5 mr-2 text-[#d4a574]" />
              Payment Information
            </CardTitle>
            <CardDescription>
              Enter the payment details for the student
            </CardDescription>
          </CardHeader>
          <CardContent class="space-y-6">
            <PaymentForm :form="form" :students="students" :courses="courses" />
          </CardContent>
        </Card>

        <!-- Action Buttons -->
        <Card>
          <CardContent class="pt-6">
            <div class="flex space-x-3 justify-end">
              <Button
                type="button"
                variant="outline"
                @click="$inertia.visit(route('admin.payments.index'))"
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
                <Icon v-else name="Plus" class="h-4 w-4 mr-2" />
                {{ form.processing ? 'Creating...' : 'Create Payment' }}
              </Button>
            </div>
          </CardContent>
        </Card>
      </form>
    </div>
  </AdminLayout>
</template> 