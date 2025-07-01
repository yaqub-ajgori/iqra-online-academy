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
              <h1 class="text-2xl font-bold text-gray-900">Student Details</h1>
              <p class="text-sm text-gray-600">View and manage student information</p>
            </div>
          </div>
        </div>
        <div class="flex items-center space-x-3">
          <Link 
            :href="route('admin.students.edit', student.id)"
            :class="buttonVariants({ variant: 'outline' })"
          >
            <Icon name="Edit" class="h-4 w-4 mr-2" />
            Edit Student
          </Link>
        </div>
      </div>
    </div>

    <div class="space-y-6">
      <!-- Student Profile Card -->
      <Card>
        <CardHeader>
          <CardTitle class="flex items-center space-x-3">
            <Avatar class="h-12 w-12">
              <AvatarImage 
                v-if="student.profile_picture" 
                :src="`/storage/${student.profile_picture}`" 
                :alt="student.full_name" 
              />
              <AvatarFallback>{{ getInitials(student.full_name) }}</AvatarFallback>
            </Avatar>
            <div>
              <div class="text-xl font-semibold">{{ student.full_name }}</div>
              <div class="text-sm text-gray-500">{{ student.user?.email }}</div>
            </div>
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Personal Information -->
            <div>
              <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
              <dl class="space-y-3">
                <div v-if="student.date_of_birth">
                  <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                  <dd class="text-sm text-gray-900">{{ formatDate(student.date_of_birth) }}</dd>
                </div>
                <div v-if="student.gender">
                  <dt class="text-sm font-medium text-gray-500">Gender</dt>
                  <dd class="text-sm text-gray-900 capitalize">{{ student.gender }}</dd>
                </div>
                <div v-if="student.occupation">
                  <dt class="text-sm font-medium text-gray-500">Occupation</dt>
                  <dd class="text-sm text-gray-900">{{ student.occupation }}</dd>
                </div>
                <div v-if="student.education_level">
                  <dt class="text-sm font-medium text-gray-500">Education Level</dt>
                  <dd class="text-sm text-gray-900">{{ student.education_level }}</dd>
                </div>
                <div v-if="student.bio">
                  <dt class="text-sm font-medium text-gray-500">Bio</dt>
                  <dd class="text-sm text-gray-900">{{ student.bio }}</dd>
                </div>
              </dl>
            </div>

            <!-- Contact & Location -->
            <div>
              <h3 class="text-lg font-medium text-gray-900 mb-4">Contact & Location</h3>
              <dl class="space-y-3">
                <div v-if="student.phone">
                  <dt class="text-sm font-medium text-gray-500">Phone</dt>
                  <dd class="text-sm text-gray-900">{{ student.phone }}</dd>
                </div>
                <div v-if="student.address">
                  <dt class="text-sm font-medium text-gray-500">Address</dt>
                  <dd class="text-sm text-gray-900">{{ student.address }}</dd>
                </div>
                <div v-if="student.city || student.district">
                  <dt class="text-sm font-medium text-gray-500">City/District</dt>
                  <dd class="text-sm text-gray-900">{{ [student.city, student.district].filter(Boolean).join(', ') }}</dd>
                </div>
                <div v-if="student.country">
                  <dt class="text-sm font-medium text-gray-500">Country</dt>
                  <dd class="text-sm text-gray-900">{{ student.country }}</dd>
                </div>
                <div v-if="student.postal_code">
                  <dt class="text-sm font-medium text-gray-500">Postal Code</dt>
                  <dd class="text-sm text-gray-900">{{ student.postal_code }}</dd>
                </div>
              </dl>
            </div>
          </div>

          <!-- Status Badges -->
          <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="flex items-center space-x-4">
              <span 
                :class="[
                  'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium',
                  student.is_active 
                    ? 'bg-green-100 text-green-800' 
                    : 'bg-red-100 text-red-800'
                ]"
              >
                {{ student.is_active ? 'Active' : 'Inactive' }}
              </span>
              <span 
                v-if="student.is_verified" 
                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800"
              >
                Verified
              </span>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <Card>
          <CardContent class="p-6">
            <div class="flex items-center">
              <div class="p-2 bg-blue-100 rounded-lg">
                <Icon name="BookOpen" class="h-6 w-6 text-blue-600" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Enrollments</p>
                <p class="text-2xl font-bold text-gray-900">{{ stats.total_enrollments }}</p>
              </div>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardContent class="p-6">
            <div class="flex items-center">
              <div class="p-2 bg-green-100 rounded-lg">
                <Icon name="CheckCircle" class="h-6 w-6 text-green-600" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Completed Courses</p>
                <p class="text-2xl font-bold text-gray-900">{{ stats.completed_courses }}</p>
              </div>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardContent class="p-6">
            <div class="flex items-center">
              <div class="p-2 bg-purple-100 rounded-lg">
                <Icon name="CreditCard" class="h-6 w-6 text-purple-600" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Payments</p>
                <p class="text-2xl font-bold text-gray-900">{{ stats.total_payments }}</p>
              </div>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardContent class="p-6">
            <div class="flex items-center">
              <div class="p-2 bg-orange-100 rounded-lg">
                <Icon name="TrendingUp" class="h-6 w-6 text-orange-600" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Avg Progress</p>
                <p class="text-2xl font-bold text-gray-900">{{ stats.average_progress }}%</p>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>

      <!-- Course Enrollments -->
      <Card v-if="student.enrollments && student.enrollments.length > 0">
        <CardHeader>
          <CardTitle>Course Enrollments</CardTitle>
        </CardHeader>
        <CardContent class="p-0">
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Course</TableHead>
                <TableHead>Category</TableHead>
                <TableHead text-align="center">Progress</TableHead>
                <TableHead text-align="center">Status</TableHead>
                <TableHead text-align="center">Enrolled</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow v-for="enrollment in student.enrollments" :key="enrollment.id">
                <TableCell>
                  <div class="text-sm font-medium text-gray-900">{{ enrollment.course?.title }}</div>
                </TableCell>
                <TableCell>
                  <div class="text-sm text-gray-500">{{ enrollment.course?.category?.name }}</div>
                </TableCell>
                <TableCell text-align="center">
                  <div class="flex items-center justify-center">
                    <div class="w-16 bg-gray-200 rounded-full h-2">
                      <div class="bg-blue-600 h-2 rounded-full" :style="`width: ${enrollment.progress_percentage}%`"></div>
                    </div>
                    <span class="ml-2 text-sm text-gray-600">{{ enrollment.progress_percentage }}%</span>
                  </div>
                </TableCell>
                <TableCell text-align="center">
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                        :class="enrollment.is_completed 
                          ? 'bg-green-100 text-green-800' 
                          : 'bg-yellow-100 text-yellow-800'">
                    {{ enrollment.is_completed ? 'Completed' : 'In Progress' }}
                  </span>
                </TableCell>
                <TableCell text-align="center">
                  <span class="text-sm text-gray-500">{{ formatDate(enrollment.enrolled_at) }}</span>
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </CardContent>
      </Card>

      <!-- Recent Payments -->
      <Card v-if="student.payments && student.payments.length > 0">
        <CardHeader>
          <CardTitle>Recent Payments</CardTitle>
        </CardHeader>
        <CardContent class="p-0">
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Amount</TableHead>
                <TableHead>Method</TableHead>
                <TableHead text-align="center">Status</TableHead>
                <TableHead text-align="center">Date</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow v-for="payment in student.payments" :key="payment.id">
                <TableCell>
                  <div class="text-sm font-medium text-gray-900">${{ formatCurrency(payment.amount) }}</div>
                </TableCell>
                <TableCell>
                  <div class="text-sm text-gray-500 capitalize">{{ payment.payment_method }}</div>
                </TableCell>
                <TableCell text-align="center">
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                        :class="getPaymentStatusClass(payment.status)">
                    {{ payment.status }}
                  </span>
                </TableCell>
                <TableCell text-align="center">
                  <span class="text-sm text-gray-500">{{ formatDate(payment.created_at) }}</span>
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </CardContent>
      </Card>
    </div>
  </AdminLayout>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'
import Icon from '@/components/Icon.vue'
import { buttonVariants } from '@/lib/utils'

interface Course {
  id: number
  title: string
  category?: {
    id: number
    name: string
  }
}

interface Enrollment {
  id: number
  progress_percentage: number
  is_completed: boolean
  enrolled_at: string
  course?: Course
}

interface Payment {
  id: number
  amount: number
  payment_method: string
  status: string
  created_at: string
}

interface Student {
  id: number
  user_id: number
  full_name: string
  phone?: string
  date_of_birth?: string
  gender?: string
  address?: string
  city?: string
  district?: string
  country?: string
  postal_code?: string
  profile_picture?: string
  bio?: string
  occupation?: string
  education_level?: string
  is_active: boolean
  is_verified: boolean
  user?: {
    id: number
    name: string
    email: string
  }
  enrollments?: Enrollment[]
  payments?: Payment[]
}

interface Stats {
  total_enrollments: number
  completed_courses: number
  total_payments: number
  average_progress: number
}

defineProps<{
  student: Student
  stats: Stats
}>()

const getInitials = (name: string): string => {
  return name
    .split(' ')
    .map(word => word.charAt(0))
    .join('')
    .toUpperCase()
    .substring(0, 2)
}

const formatDate = (date: string): string => {
  return new Date(date).toLocaleDateString()
}

const formatCurrency = (amount: number): string => {
  return amount.toFixed(2)
}

const getPaymentStatusClass = (status: string): string => {
  switch (status.toLowerCase()) {
    case 'completed':
    case 'paid':
      return 'bg-green-100 text-green-800'
    case 'pending':
      return 'bg-yellow-100 text-yellow-800'
    case 'failed':
    case 'cancelled':
      return 'bg-red-100 text-red-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}
</script> 