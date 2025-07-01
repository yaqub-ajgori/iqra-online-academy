<template>
  <AdminLayout>
    <!-- Page Header -->
    <div class="mb-8">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Students</h1>
          <p class="text-sm text-gray-600">Manage student accounts and profiles</p>
        </div>
        <div class="flex items-center space-x-3">
          <div class="text-sm text-gray-500">
            {{ students.length }} {{ students.length === 1 ? 'student' : 'students' }}
          </div>
          <Link 
            :href="route('admin.students.create')"
            :class="buttonVariants({ variant: 'primary' })"
          >
            <Icon name="Plus" class="h-4 w-4 mr-2" />
            New Student
          </Link>
        </div>
      </div>
    </div>

    <!-- Students Table -->
    <Card>
      <CardHeader>
        <CardTitle>Student List</CardTitle>
      </CardHeader>
      <CardContent class="p-0">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Student</TableHead>
              <TableHead>Contact</TableHead>
              <TableHead>Location</TableHead>
              <TableHead text-align="center">Enrollments</TableHead>
              <TableHead text-align="center">Status</TableHead>
              <TableHead text-align="right">Actions</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow
              v-for="student in students"
              :key="student.id"
              class="hover:bg-gray-50"
            >
              <!-- Student Info -->
              <TableCell>
                <div class="flex items-center space-x-3">
                  <Avatar class="h-10 w-10">
                    <AvatarImage 
                      v-if="student.profile_picture" 
                      :src="`/storage/${student.profile_picture}`" 
                      :alt="student.full_name" 
                    />
                    <AvatarFallback>{{ getInitials(student.full_name) }}</AvatarFallback>
                  </Avatar>
                  <div class="min-w-0 flex-1">
                    <div class="text-sm font-medium text-gray-900">{{ student.full_name }}</div>
                    <div class="text-sm text-gray-500">{{ student.user?.email }}</div>
                  </div>
                </div>
              </TableCell>

              <!-- Contact -->
              <TableCell>
                <div class="text-sm">
                  <div v-if="student.phone" class="text-gray-900">{{ student.phone }}</div>
                  <div v-if="student.date_of_birth" class="text-gray-500">
                    Born {{ formatDate(student.date_of_birth) }}
                  </div>
                  <div v-if="!student.phone && !student.date_of_birth" class="text-gray-400">
                    No contact info
                  </div>
                </div>
              </TableCell>

              <!-- Location -->
              <TableCell>
                <div class="text-sm text-gray-900">
                  <div v-if="student.city || student.district">
                    {{ [student.city, student.district].filter(Boolean).join(', ') }}
                  </div>
                  <div v-if="student.country" class="text-gray-500">{{ student.country }}</div>
                  <div v-if="!student.city && !student.district && !student.country" class="text-gray-400">
                    No location
                  </div>
                </div>
              </TableCell>

              <!-- Enrollments -->
              <TableCell text-align="center">
                <div class="flex flex-col items-center space-y-1">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                    {{ student.enrollments_count }} {{ student.enrollments_count === 1 ? 'course' : 'courses' }}
                  </span>
                  <span v-if="student.payments_count > 0" class="text-xs text-gray-500">
                    {{ student.payments_count }} {{ student.payments_count === 1 ? 'payment' : 'payments' }}
                  </span>
                </div>
              </TableCell>

              <!-- Status -->
              <TableCell text-align="center">
                <div class="flex flex-col items-center space-y-1">
                  <span 
                    :class="[
                      'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                      student.is_active 
                        ? 'bg-green-100 text-green-800' 
                        : 'bg-red-100 text-red-800'
                    ]"
                  >
                    {{ student.is_active ? 'Active' : 'Inactive' }}
                  </span>
                  <span 
                    v-if="student.is_verified" 
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                  >
                    Verified
                  </span>
                </div>
              </TableCell>

              <!-- Actions -->
              <TableCell text-align="right">
                <div class="flex items-center justify-end space-x-2">
                  <Link 
                    :href="route('admin.students.show', student.id)"
                    :class="buttonVariants({ variant: 'ghost', size: 'icon-sm' })"
                    title="View Student"
                  >
                    <Icon name="Eye" class="h-4 w-4" />
                  </Link>
                  
                  <Link 
                    :href="route('admin.students.edit', student.id)"
                    :class="buttonVariants({ variant: 'ghost', size: 'icon-sm' })"
                    title="Edit Student"
                  >
                    <Icon name="Edit" class="h-4 w-4" />
                  </Link>
                  
                  <Button
                    @click="deleteStudent(student)"
                    variant="ghost"
                    size="icon-sm"
                    class="text-red-600 hover:text-red-700"
                    :disabled="student.enrollments_count > 0"
                    :title="student.enrollments_count > 0 ? 'Cannot delete student with active enrollments' : 'Delete Student'"
                  >
                    <Icon name="Trash2" class="h-4 w-4" />
                  </Button>
                </div>
              </TableCell>
            </TableRow>

            <!-- Empty State -->
            <TableRow v-if="students.length === 0">
              <TableCell :colspan="6" class="text-center py-12">
                <div class="flex flex-col items-center justify-center space-y-3">
                  <Icon name="Users" class="h-12 w-12 text-gray-400" />
                  <div class="text-gray-500">No students found</div>
                  <Link 
                    :href="route('admin.students.create')"
                    :class="buttonVariants({ variant: 'primary' })"
                  >
                    Create First Student
                  </Link>
                </div>
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </CardContent>
    </Card>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
      <Card>
        <CardContent class="p-6">
          <div class="flex items-center">
            <div class="p-2 bg-blue-100 rounded-lg">
              <Icon name="Users" class="h-6 w-6 text-blue-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Total Students</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.total_students }}</p>
            </div>
          </div>
        </CardContent>
      </Card>

      <Card>
        <CardContent class="p-6">
          <div class="flex items-center">
            <div class="p-2 bg-green-100 rounded-lg">
              <Icon name="UserCheck" class="h-6 w-6 text-green-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Active Students</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.active_students }}</p>
            </div>
          </div>
        </CardContent>
      </Card>

      <Card>
        <CardContent class="p-6">
          <div class="flex items-center">
            <div class="p-2 bg-purple-100 rounded-lg">
              <Icon name="Shield" class="h-6 w-6 text-purple-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Verified Students</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.verified_students }}</p>
            </div>
          </div>
        </CardContent>
      </Card>

      <Card>
        <CardContent class="p-6">
          <div class="flex items-center">
            <div class="p-2 bg-orange-100 rounded-lg">
              <Icon name="BookOpen" class="h-6 w-6 text-orange-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Enrolled Students</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.enrolled_students }}</p>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>
  </AdminLayout>
</template>

<script setup lang="ts">
import { router, Link } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'
import Icon from '@/components/Icon.vue'
import { useToast } from '@/composables/useToast'
import { buttonVariants } from '@/lib/utils'

// Toast composable
const { success, error } = useToast()

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
  enrollments_count: number
  payments_count: number
}

interface Stats {
  total_students: number
  active_students: number
  verified_students: number
  enrolled_students: number
}

defineProps<{
  students: Student[]
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

const deleteStudent = (student: Student) => {
  if (student.enrollments_count > 0) {
    error('Cannot delete student with active enrollments')
    return
  }

  if (confirm(`Are you sure you want to delete ${student.full_name}? This action cannot be undone.`)) {
    router.delete(route('admin.students.destroy', student.id), {
      onSuccess: () => {
        success('Student deleted successfully')
      },
      onError: (errors) => {
        if (errors.error) {
          error(errors.error)
        } else {
          error('Failed to delete student')
        }
      }
    })
  }
}
</script> 