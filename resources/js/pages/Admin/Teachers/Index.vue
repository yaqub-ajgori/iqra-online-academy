<template>
  <AdminLayout>
    <!-- Page Header -->
    <div class="mb-8">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Teachers</h1>
          <p class="text-sm text-gray-600">Manage teacher profiles and information</p>
        </div>
        <div class="flex items-center space-x-3">
          <div class="text-sm text-gray-500">
            {{ teachers.length }} {{ teachers.length === 1 ? 'teacher' : 'teachers' }}
          </div>
          <Link 
            :href="route('admin.teachers.create')"
            :class="buttonVariants({ variant: 'primary' })"
          >
            <Icon name="Plus" class="h-4 w-4 mr-2" />
            New Teacher
          </Link>
        </div>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
      <Card>
        <CardContent class="p-6">
          <div class="flex items-center">
            <div class="p-2 bg-blue-100 rounded-lg">
              <Icon name="Users" class="h-6 w-6 text-blue-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Total Teachers</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.total_teachers }}</p>
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
              <p class="text-sm font-medium text-gray-600">Active Teachers</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.active_teachers }}</p>
            </div>
          </div>
        </CardContent>
      </Card>

      <Card>
        <CardContent class="p-6">
          <div class="flex items-center">
            <div class="p-2 bg-red-100 rounded-lg">
              <Icon name="UserX" class="h-6 w-6 text-red-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Inactive Teachers</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.inactive_teachers }}</p>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>

    <!-- Teachers Table -->
    <Card>
      <CardHeader>
        <CardTitle>Teachers List</CardTitle>
      </CardHeader>
      <CardContent class="p-0">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Teacher</TableHead>
              <TableHead>Contact</TableHead>
              <TableHead>Speciality</TableHead>
              <TableHead text-align="center">Experience</TableHead>
              <TableHead text-align="center">Status</TableHead>
              <TableHead text-align="right">Actions</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow
              v-for="teacher in teachers"
              :key="teacher.id"
              class="hover:bg-gray-50"
            >
              <!-- Teacher Info -->
              <TableCell>
                <div class="flex items-center space-x-3">
                  <Avatar class="h-10 w-10">
                    <AvatarImage 
                      v-if="teacher.profile_picture" 
                      :src="`/storage/${teacher.profile_picture}`" 
                      :alt="teacher.full_name" 
                    />
                    <AvatarFallback>{{ getInitials(teacher.full_name) }}</AvatarFallback>
                  </Avatar>
                  <div class="min-w-0 flex-1">
                    <div class="text-sm font-medium text-gray-900">{{ teacher.full_name }}</div>
                  </div>
                </div>
              </TableCell>

              <!-- Contact -->
              <TableCell>
                <div class="text-sm">
                  <div v-if="teacher.phone" class="text-gray-900">{{ teacher.phone }}</div>
                  <div v-if="!teacher.phone" class="text-gray-400">No phone</div>
                </div>
              </TableCell>

              <!-- Speciality -->
              <TableCell>
                <div class="text-sm text-gray-900">
                  {{ teacher.speciality || 'Not specified' }}
                </div>
              </TableCell>

              <!-- Experience -->
              <TableCell text-align="center">
                <div class="text-sm text-gray-900">
                  {{ teacher.experience || 'Not specified' }}
                </div>
              </TableCell>

              <!-- Status -->
              <TableCell text-align="center">
                <span 
                  :class="[
                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                    teacher.is_active 
                      ? 'bg-green-100 text-green-800' 
                      : 'bg-red-100 text-red-800'
                  ]"
                >
                  {{ teacher.is_active ? 'Active' : 'Inactive' }}
                </span>
              </TableCell>

              <!-- Actions -->
              <TableCell text-align="right">
                <div class="flex items-center justify-end space-x-2">
                  <Link 
                    :href="route('admin.teachers.show', teacher.id)"
                    :class="buttonVariants({ variant: 'ghost', size: 'icon-sm' })"
                    title="View Teacher"
                  >
                    <Icon name="Eye" class="h-4 w-4" />
                  </Link>
                  
                  <Link 
                    :href="route('admin.teachers.edit', teacher.id)"
                    :class="buttonVariants({ variant: 'ghost', size: 'icon-sm' })"
                    title="Edit Teacher"
                  >
                    <Icon name="Edit" class="h-4 w-4" />
                  </Link>
                  
                  <Button
                    @click="deleteTeacher(teacher)"
                    variant="ghost"
                    size="icon-sm"
                    class="text-red-600 hover:text-red-700"
                    title="Delete Teacher"
                  >
                    <Icon name="Trash2" class="h-4 w-4" />
                  </Button>
                </div>
              </TableCell>
            </TableRow>

            <!-- Empty State -->
            <TableRow v-if="teachers.length === 0">
              <TableCell :colspan="6" class="text-center py-12">
                <div class="flex flex-col items-center justify-center space-y-3">
                  <Icon name="Users" class="h-12 w-12 text-gray-400" />
                  <div class="text-gray-500">No teachers found</div>
                  <Link 
                    :href="route('admin.teachers.create')"
                    :class="buttonVariants({ variant: 'primary' })"
                  >
                    Create First Teacher
                  </Link>
                </div>
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </CardContent>
    </Card>
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

interface Teacher {
  id: number
  full_name: string
  speciality?: string
  experience?: string
  profile_picture?: string
  phone?: string
  is_active: boolean
  created_at: string
  updated_at: string
}

interface Stats {
  total_teachers: number
  active_teachers: number
  inactive_teachers: number
}

defineProps<{
  teachers: Teacher[]
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

const deleteTeacher = (teacher: Teacher) => {
  if (confirm(`Are you sure you want to delete ${teacher.full_name}? This action cannot be undone.`)) {
    router.delete(route('admin.teachers.destroy', teacher.id), {
      onSuccess: () => {
        success('Teacher deleted successfully')
      },
      onError: () => {
        error('Failed to delete teacher')
      }
    })
  }
}
</script>
