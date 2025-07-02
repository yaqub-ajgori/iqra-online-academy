<template>
  <div class="space-y-8">
    <!-- Basic Information -->
    <Card>
      <CardHeader>
        <CardTitle class="flex items-center text-gray-900">
          <Icon name="Users" class="w-5 h-5 mr-2 text-[#d4a574]" />
          Basic Information
        </CardTitle>
        <CardDescription>
          <span v-if="mode === 'create'">Fill in the enrollment details</span>
          <span v-else>Update the enrollment details for the student</span>
        </CardDescription>
      </CardHeader>
      <CardContent class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Student -->
          <div class="mb-4">
            <label for="student_id" class="block text-sm font-medium text-gray-700 mb-1">Student</label>
            <select
              id="student_id"
              v-model="form.student_id"
              class="block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 text-sm"
              required
            >
              <option value="">Select Student</option>
              <template v-if="Array.isArray(students) && students.length">
                <option v-for="student in students" :key="student.id" :value="student.id.toString()">
                  {{ student.name }} ({{ student.email }})
                </option>
              </template>
              <option v-else value="_no_student" disabled>No students available</option>
            </select>
            <InputError :message="form.errors.student_id" class="mt-1" />
          </div>

          <!-- Course -->
          <div class="mb-4">
            <label for="course_id" class="block text-sm font-medium text-gray-700 mb-1">Course</label>
            <select
              id="course_id"
              v-model="form.course_id"
              class="block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 text-sm"
              required
            >
              <option value="">Select Course</option>
              <template v-if="Array.isArray(courses) && courses.length">
                <option v-for="course in courses" :key="course.id" :value="course.id.toString()">
                  {{ course.title }}
                </option>
              </template>
              <option v-else value="_no_course" disabled>No courses available</option>
            </select>
            <InputError :message="form.errors.course_id" class="mt-1" />
          </div>

          <!-- Enrollment Date -->
          <div>
            <Label for="enrolled_at">Enrollment Date</Label>
            <Input id="enrolled_at" v-model="form.enrolled_at" type="datetime-local" />
            <InputError :message="form.errors.enrolled_at" />
          </div>

          <!-- Enrollment Type -->
          <div class="mb-4">
            <label for="enrollment_type" class="block text-sm font-medium text-gray-700 mb-1">Enrollment Type</label>
            <select
              id="enrollment_type"
              v-model="form.enrollment_type"
              class="block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 text-sm"
              required
            >
              <option value="">Select Type</option>
              <option value="paid">Paid</option>
              <option value="free">Free</option>
              <option value="scholarship">Scholarship</option>
              <option value="trial">Trial</option>
            </select>
            <InputError :message="form.errors.enrollment_type" class="mt-1" />
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
          <div class="mb-4">
            <label for="payment_id" class="block text-sm font-medium text-gray-700 mb-1">Associated Payment</label>
            <select
              id="payment_id"
              v-model="form.payment_id"
              class="block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 text-sm"
            >
              <option value="">Select Payment (optional)</option>
              <template v-if="Array.isArray(payments) && payments.length">
                <option v-for="payment in payments" :key="payment.id" :value="payment.id.toString()">
                  {{ payment.reference || payment.id }} - {{ payment.amount }}
                </option>
              </template>
              <option v-else value="_no_payment" disabled>No payments available</option>
            </select>
            <InputError :message="form.errors.payment_id" class="mt-1" />
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
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Checkbox } from '@/components/ui/checkbox'
import Icon from '@/components/Icon.vue'
import InputError from '@/components/InputError.vue'

const props = defineProps<{
  form: any
  students: any[]
  courses: any[]
  payments: any[]
  mode: 'create' | 'edit'
}>()

const selectedStudentText = computed(() => {
  if (!props.form.student_id) return ''
  const student = props.students.find(s => s.id.toString() === props.form.student_id)
  return student ? `${student.name} (${student.email})` : ''
})

const selectedCourseText = computed(() => {
  if (!props.form.course_id) return ''
  const course = props.courses.find(c => c.id.toString() === props.form.course_id)
  return course ? `${course.title} - ৳${course.price}` : ''
})

const selectedPaymentText = computed(() => {
  if (!props.form.payment_id) return ''
  const payment = props.payments.find(p => p.id.toString() === props.form.payment_id)
  return payment ? `${payment.transaction_id} - ৳${payment.amount} (${payment.student_name})` : ''
})

function updateCoursePrice() {
  const selectedCourse = props.courses.find(course => course.id === Number(props.form.course_id))
  if (selectedCourse) {
    props.form.amount_paid = selectedCourse.is_free ? '0' : selectedCourse.price.toString()
  }
}

function updateEnrollmentType() {
  if (props.form.enrollment_type === 'free') {
    props.form.amount_paid = '0'
    props.form.payment_status = 'completed'
  }
}
</script> 