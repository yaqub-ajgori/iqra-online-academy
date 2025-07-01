<script setup lang="ts">
import { watch, computed } from 'vue'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import InputError from '@/components/InputError.vue'
import { formatCurrency } from '@/lib/utils'

const props = defineProps<{
  form: any
  students: { id: number; name: string; email: string }[]
  courses: { id: number; title: string; price: number; is_free: boolean; currency: string }[]
}>()

const paymentMethods = ['bkash', 'nagad', 'rocket', 'bank_transfer']
const paymentStatuses = ['pending', 'processing', 'completed', 'failed', 'cancelled', 'refunded']

// Computed properties for display text
const selectedStudentText = computed(() => {
  if (!props.form.student_id) return ''
  const student = props.students.find(s => s.id.toString() === props.form.student_id)
  return student ? `${student.name} (${student.email})` : ''
})

const selectedCourseText = computed(() => {
  if (!props.form.course_id) return ''
  const course = props.courses.find(c => c.id.toString() === props.form.course_id)
  return course ? `${course.title} - ${formatCurrency(course.price, course.currency)}` : ''
})

const selectedMethodText = computed(() => {
  if (!props.form.payment_method) return ''
  return props.form.payment_method.replace('_', ' ')
})

const selectedStatusText = computed(() => {
  if (!props.form.status) return ''
  return props.form.status
})

watch(
  () => props.form.course_id,
  (newCourseId) => {
    if (newCourseId) {
      const selectedCourse = props.courses.find(c => c.id === Number(newCourseId))
      if (selectedCourse && !selectedCourse.is_free) {
        props.form.amount = selectedCourse.price
        props.form.currency = selectedCourse.currency || 'BDT'
      } else if (selectedCourse && selectedCourse.is_free) {
        props.form.amount = 0
      }
    }
  }
)
</script>

<template>
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
      <Select v-model="form.course_id">
        <SelectTrigger>
          <SelectValue :value="form.course_id">
            {{ selectedCourseText || 'Select a course' }}
          </SelectValue>
        </SelectTrigger>
        <SelectContent>
          <SelectItem
            v-for="course in courses"
            :key="course.id"
            :value="course.id.toString()"
          >
            {{ course.title }} - {{ formatCurrency(course.price, course.currency) }}
          </SelectItem>
        </SelectContent>
      </Select>
      <InputError :message="form.errors.course_id" />
    </div>

    <!-- Amount -->
    <div>
      <Label for="amount">Amount</Label>
      <Input id="amount" v-model="form.amount" type="number" step="0.01" />
      <InputError :message="form.errors.amount" />
    </div>

    <!-- Currency -->
    <div>
      <Label for="currency">Currency</Label>
      <Input id="currency" v-model="form.currency" type="text" />
      <InputError :message="form.errors.currency" />
    </div>

    <!-- Payment Method -->
    <div>
      <Label for="payment_method">Payment Method</Label>
      <Select v-model="form.payment_method">
        <SelectTrigger>
          <SelectValue :value="form.payment_method">
            {{ selectedMethodText ? selectedMethodText.charAt(0).toUpperCase() + selectedMethodText.slice(1) : 'Select a method' }}
          </SelectValue>
        </SelectTrigger>
        <SelectContent>
          <SelectItem
            v-for="method in paymentMethods"
            :key="method"
            :value="method"
          >
            <span class="capitalize">{{ method.replace('_', ' ') }}</span>
          </SelectItem>
        </SelectContent>
      </Select>
      <InputError :message="form.errors.payment_method" />
    </div>

    <!-- Status -->
    <div>
      <Label for="status">Status</Label>
      <Select v-model="form.status">
        <SelectTrigger>
          <SelectValue :value="form.status">
            {{ selectedStatusText ? selectedStatusText.charAt(0).toUpperCase() + selectedStatusText.slice(1) : 'Select a status' }}
          </SelectValue>
        </SelectTrigger>
        <SelectContent>
          <SelectItem
            v-for="statusItem in paymentStatuses"
            :key="statusItem"
            :value="statusItem"
          >
            <span class="capitalize">{{ statusItem }}</span>
          </SelectItem>
        </SelectContent>
      </Select>
      <InputError :message="form.errors.status" />
    </div>

    <!-- Transaction ID -->
    <div class="md:col-span-2">
      <Label for="transaction_id">Transaction ID</Label>
      <Input id="transaction_id" v-model="form.transaction_id" type="text" />
      <InputError :message="form.errors.transaction_id" />
    </div>

    <!-- Mobile Banking Fields -->
    <template v-if="['bkash', 'nagad', 'rocket'].includes(form.payment_method)">
      <div>
        <Label for="sender_number">Sender Number</Label>
        <Input id="sender_number" v-model="form.sender_number" type="text" />
        <InputError :message="form.errors.sender_number" />
      </div>
      <div>
        <Label for="receiver_number">Receiver Number</Label>
        <Input id="receiver_number" v-model="form.receiver_number" type="text" />
        <InputError :message="form.errors.receiver_number" />
      </div>
    </template>

    <!-- Bank Transfer Fields -->
    <template v-if="form.payment_method === 'bank_transfer'">
      <div>
        <Label for="bank_name">Bank Name</Label>
        <Input id="bank_name" v-model="form.bank_name" type="text" />
        <InputError :message="form.errors.bank_name" />
      </div>
      <div>
        <Label for="account_number">Account Number</Label>
        <Input id="account_number" v-model="form.account_number" type="text" />
        <InputError :message="form.errors.account_number" />
      </div>
      <div>
        <Label for="branch_name">Branch Name</Label>
        <Input id="branch_name" v-model="form.branch_name" type="text" />
        <InputError :message="form.errors.branch_name" />
      </div>
    </template>
  </div>
</template>
