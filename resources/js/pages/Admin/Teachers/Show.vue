<template>
  <AdminLayout>
    <!-- Page Header -->
    <div class="mb-8">
      <div class="flex items-center justify-between">
        <div>
          <div class="flex items-center space-x-3">
            <Link 
              :href="route('admin.teachers.index')"
              :class="buttonVariants({ variant: 'ghost', size: 'icon-sm' })"
              title="Back to Teachers"
            >
              <Icon name="ArrowLeft" class="h-4 w-4" />
            </Link>
            <div>
              <h1 class="text-2xl font-bold text-gray-900">Teacher Details</h1>
              <p class="text-sm text-gray-600">View and manage teacher information</p>
            </div>
          </div>
        </div>
        <div class="flex items-center space-x-3">
          <Link 
            :href="route('admin.teachers.edit', teacher.id)"
            :class="buttonVariants({ variant: 'outline' })"
          >
            <Icon name="Edit" class="h-4 w-4 mr-2" />
            Edit Teacher
          </Link>
        </div>
      </div>
    </div>

    <div class="space-y-6">
      <!-- Teacher Profile Card -->
      <Card>
        <CardHeader>
          <CardTitle class="flex items-center space-x-3">
            <Avatar class="h-12 w-12">
              <AvatarImage 
                v-if="teacher.profile_picture" 
                :src="`/storage/${teacher.profile_picture}`" 
                :alt="teacher.full_name" 
              />
              <AvatarFallback>{{ getInitials(teacher.full_name) }}</AvatarFallback>
            </Avatar>
            <div>
              <div class="text-xl font-semibold">{{ teacher.full_name }}</div>
              <div class="text-sm text-gray-500">{{ teacher.speciality || 'No speciality specified' }}</div>
            </div>
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Contact Information -->
            <div>
              <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h3>
              <dl class="space-y-3">
                <div v-if="teacher.phone">
                  <dt class="text-sm font-medium text-gray-500">Phone</dt>
                  <dd class="text-sm text-gray-900">{{ teacher.phone }}</dd>
                </div>
                <div v-if="!teacher.phone">
                  <dt class="text-sm font-medium text-gray-500">Phone</dt>
                  <dd class="text-sm text-gray-400">Not provided</dd>
                </div>
              </dl>
            </div>

            <!-- Professional Information -->
            <div>
              <h3 class="text-lg font-medium text-gray-900 mb-4">Professional Information</h3>
              <dl class="space-y-3">
                <div v-if="teacher.speciality">
                  <dt class="text-sm font-medium text-gray-500">Speciality</dt>
                  <dd class="text-sm text-gray-900">{{ teacher.speciality }}</dd>
                </div>
                <div v-if="teacher.experience">
                  <dt class="text-sm font-medium text-gray-500">Experience</dt>
                  <dd class="text-sm text-gray-900">{{ teacher.experience }}</dd>
                </div>
                <div v-if="!teacher.speciality && !teacher.experience">
                  <dt class="text-sm font-medium text-gray-500">Professional Info</dt>
                  <dd class="text-sm text-gray-400">No professional information provided</dd>
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
                  teacher.is_active 
                    ? 'bg-green-100 text-green-800' 
                    : 'bg-red-100 text-red-800'
                ]"
              >
                {{ teacher.is_active ? 'Active' : 'Inactive' }}
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
                <p class="text-sm font-medium text-gray-600">Total Courses</p>
                <p class="text-2xl font-bold text-gray-900">{{ stats.total_courses }}</p>
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
                <p class="text-sm font-medium text-gray-600">Active Courses</p>
                <p class="text-2xl font-bold text-gray-900">{{ stats.active_courses }}</p>
              </div>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardContent class="p-6">
            <div class="flex items-center">
              <div class="p-2 bg-purple-100 rounded-lg">
                <Icon name="Users" class="h-6 w-6 text-purple-600" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Students Taught</p>
                <p class="text-2xl font-bold text-gray-900">{{ stats.total_students }}</p>
              </div>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardContent class="p-6">
            <div class="flex items-center">
              <div class="p-2 bg-orange-100 rounded-lg">
                <Icon name="Star" class="h-6 w-6 text-orange-600" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Avg Rating</p>
                <p class="text-2xl font-bold text-gray-900">{{ stats.average_rating || 'N/A' }}</p>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'
import Icon from '@/components/Icon.vue'
import { buttonVariants } from '@/lib/utils'

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
  total_courses: number
  active_courses: number
  total_students: number
  average_rating: number
}

defineProps<{
  teacher: Teacher
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
</script> 