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

      <!-- Form -->
      <form @submit.prevent="submit" class="space-y-8">
        <!-- Basic Information -->
        <Card>
          <CardHeader>
            <CardTitle class="flex items-center text-gray-900">
              <Icon name="Users" class="w-5 h-5 mr-2 text-[#d4a574]" />
              Basic Information
            </CardTitle>
            <CardDescription>
              Fill in the enrollment details
            </CardDescription>
          </CardHeader>
          <CardContent class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Student -->
              <div>
                <Label for="student_id">Student</Label>
                <Select v-model="form.student_id">
                  <SelectTrigger>
                    <SelectValue :value="form.student_id">
                      {{ selectedStudentText || 'Select a student' }}
                    </SelectValue>
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="">Select Student</SelectItem>
                    <SelectItem
                      v-for="student in students"
                      :key="student.id"
                      :value="student.id.toString()"
                    >
                      {{ student.name }} ({{ student.email }})
                    </SelectItem>
                  </SelectContent>
                </Select>
                <InputError :message="form.errors.student_id" />
              </div>

              <!-- Course -->
              <div>
                <Label for="course_id">Course</Label>
                <Select v-model="form.course_id" @update:modelValue="updateCoursePrice">
                  <SelectTrigger>
                    <SelectValue :value="form.course_id">
                      {{ selectedCourseText || 'Select a course' }}
                    </SelectValue>
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="">Select Course</SelectItem>
                    <SelectItem
                      v-for="course in courses"
                      :key="course.id"
                      :value="course.id.toString()"
                    >
                      {{ course.title }} (৳{{ course.price }})
                    </SelectItem>
                  </SelectContent>
                </Select>
                <InputError :message="form.errors.course_id" />
              </div>

              <!-- Enrollment Date -->
              <div>
                <Label for="enrolled_at">Enrollment Date</Label>
                <Input id="enrolled_at" v-model="form.enrolled_at" type="datetime-local" />
                <InputError :message="form.errors.enrolled_at" />
              </div>

              <!-- Enrollment Type -->
              <div>
                <Label for="enrollment_type">Enrollment Type</Label>
                <Select v-model="form.enrollment_type" @update:modelValue="updateEnrollmentType">
                  <SelectTrigger>
                    <SelectValue :value="form.enrollment_type">
                      {{ form.enrollment_type ? form.enrollment_type.charAt(0).toUpperCase() + form.enrollment_type.slice(1) : 'Select a type' }}
                    </SelectValue>
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="">Select Type</SelectItem>
                    <SelectItem value="paid">Paid</SelectItem>
                    <SelectItem value="free">Free</SelectItem>
                    <SelectItem value="scholarship">Scholarship</SelectItem>
                    <SelectItem value="trial">Trial</SelectItem>
                  </SelectContent>
                </Select>
                <InputError :message="form.errors.enrollment_type" />
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Payment Information -->
        <Card>
          <CardHeader>
            <CardTitle class="flex items-center text-gray-900">
              <Icon name="CreditCard" class="w-5 h-5 mr-2 text-[#d4a574]" />
              Payment Information
            </CardTitle>
            <CardDescription>
              Enter payment details and status
            </CardDescription>
          </CardHeader>
          <CardContent class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Associated Payment -->
              <div>
                <Label for="payment_id">Associated Payment</Label>
                <Select v-model="form.payment_id">
                  <SelectTrigger>
                    <SelectValue :value="form.payment_id">
                      {{ selectedPaymentText || 'No payment selected' }}
                    </SelectValue>
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="">No Payment Selected</SelectItem>
                    <SelectItem
                      v-for="payment in payments"
                      :key="payment.id"
                      :value="payment.id.toString()"
                    >
                      {{ payment.transaction_id }} - ৳{{ payment.amount }} ({{ payment.student_name }})
                    </SelectItem>
                  </SelectContent>
                </Select>
                <InputError :message="form.errors.payment_id" />
              </div>

              <!-- Amount Paid -->
              <div>
                <Label for="amount_paid">Amount Paid</Label>
                <Input id="amount_paid" v-model="form.amount_paid" type="number" step="0.01" min="0" />
                <InputError :message="form.errors.amount_paid" />
              </div>

              <!-- Currency -->
              <div>
                <Label for="currency">Currency</Label>
                <Input id="currency" v-model="form.currency" type="text" maxlength="3" />
                <InputError :message="form.errors.currency" />
              </div>

              <!-- Payment Status -->
              <div>
                <Label for="payment_status">Payment Status</Label>
                <Select v-model="form.payment_status">
                  <SelectTrigger>
                    <SelectValue :value="form.payment_status">
                      {{ form.payment_status ? form.payment_status.charAt(0).toUpperCase() + form.payment_status.slice(1) : 'Select a status' }}
                    </SelectValue>
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="pending">Pending</SelectItem>
                    <SelectItem value="completed">Completed</SelectItem>
                    <SelectItem value="failed">Failed</SelectItem>
                    <SelectItem value="refunded">Refunded</SelectItem>
                  </SelectContent>
                </Select>
                <InputError :message="form.errors.payment_status" />
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Progress Information -->
        <Card>
          <CardHeader>
            <CardTitle class="flex items-center text-gray-900">
              <Icon name="BarChart3" class="w-5 h-5 mr-2 text-[#d4a574]" />
              Progress Information
            </CardTitle>
            <CardDescription>
              Enter course progress and completion status
            </CardDescription>
          </CardHeader>
          <CardContent class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Progress Percentage -->
              <div>
                <Label for="progress_percentage">Progress Percentage</Label>
                <Input id="progress_percentage" v-model="form.progress_percentage" type="number" step="0.01" min="0" max="100" />
                <InputError :message="form.errors.progress_percentage" />
              </div>

              <!-- Lessons Completed -->
              <div>
                <Label for="lessons_completed">Lessons Completed</Label>
                <Input id="lessons_completed" v-model="form.lessons_completed" type="number" min="0" />
                <InputError :message="form.errors.lessons_completed" />
              </div>
            </div>

            <!-- Status Checkboxes -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="flex items-center space-x-2">
                <Checkbox id="is_completed" v-model:checked="form.is_completed" />
                <Label for="is_completed">Course Completed</Label>
              </div>
              <div class="flex items-center space-x-2">
                <Checkbox id="is_active" v-model:checked="form.is_active" />
                <Label for="is_active">Active Enrollment</Label>
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Action Buttons -->
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
                <Icon v-if="form.processing" name="Loader2" class="h-4 w-4 animate-spin mr-2" />
                <Icon v-else name="Check" class="h-4 w-4 mr-2" />
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
import { ref, computed } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import { Checkbox } from '@/components/ui/checkbox'
import Icon from '@/components/Icon.vue'
import InputError from '@/components/InputError.vue'
import { useToast } from '@/composables/useToast'
import { buttonVariants } from '@/lib/utils'

// Types
interface Student {
  id: number
  name: string
  email: string
}

interface Course {
  id: number
  title: string
  price: number
  is_free: boolean
}

interface Payment {
  id: number
  transaction_id: string
  amount: number
  student_name: string
  course_title: string
}

interface Props {
  students: Student[]
  courses: Course[]
  payments: Payment[]
}

const props = defineProps<Props>()
const { success, error } = useToast()

// Form
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

const selectedStudentText = computed(() => {
  if (!form.student_id) return ''
  const student = props.students.find(s => s.id.toString() === form.student_id)
  return student ? `${student.name} (${student.email})` : ''
})

const selectedCourseText = computed(() => {
  if (!form.course_id) return ''
  const course = props.courses.find(c => c.id.toString() === form.course_id)
  return course ? `${course.title} - ৳${course.price}` : ''
})

const selectedPaymentText = computed(() => {
  if (!form.payment_id) return ''
  const payment = props.payments.find(p => p.id.toString() === form.payment_id)
  return payment ? `${payment.transaction_id} - ৳${payment.amount} (${payment.student_name})` : ''
})

// Methods
const updateCoursePrice = () => {
  const selectedCourse = props.courses.find(course => course.id === Number(form.course_id))
  if (selectedCourse) {
    form.amount_paid = selectedCourse.is_free ? '0' : selectedCourse.price.toString()
  }
}

const updateEnrollmentType = () => {
  if (form.enrollment_type === 'free') {
    form.amount_paid = '0'
    form.payment_status = 'completed'
  }
}

const submit = () => {
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