<template>
  <div class="space-y-6">
    <!-- Basic Information -->
    <Card>
      <CardHeader>
        <CardTitle class="flex items-center text-gray-900">
          <Icon name="User" class="w-5 h-5 mr-2 text-blue-600" />
          Basic Information
        </CardTitle>
        <CardDescription>
          Teacher's primary information
        </CardDescription>
      </CardHeader>
      <CardContent class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Full Name -->
          <div>
            <Label for="full_name">Full Name *</Label>
            <Input
              id="full_name"
              v-model="form.full_name"
              type="text"
              placeholder="Enter teacher's full name"
              required
            />
            <InputError :message="form.errors.full_name" />
          </div>

          <!-- Phone -->
          <div>
            <Label for="phone">Phone Number</Label>
            <Input
              id="phone"
              v-model="form.phone"
              type="tel"
              placeholder="Enter phone number"
            />
            <InputError :message="form.errors.phone" />
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Speciality -->
          <div>
            <Label for="speciality">Speciality</Label>
            <Input
              id="speciality"
              v-model="form.speciality"
              type="text"
              placeholder="e.g., Islamic Studies, Quran Recitation"
            />
            <InputError :message="form.errors.speciality" />
          </div>

          <!-- Experience -->
          <div>
            <Label for="experience">Experience</Label>
            <Input
              id="experience"
              v-model="form.experience"
              type="text"
              placeholder="e.g., 5-10 years, 10+ years"
            />
            <InputError :message="form.errors.experience" />
          </div>
        </div>

        <!-- Profile Picture -->
        <div>
          <Label for="profile_picture">Profile Picture</Label>
          <div class="flex items-center space-x-4">
            <div class="relative">
              <div v-if="currentProfilePicture && !newProfilePicture" class="w-20 h-20 rounded-full overflow-hidden">
                <img 
                  :src="`/storage/${currentProfilePicture}`" 
                  :alt="form.full_name"
                  class="w-full h-full object-cover"
                >
              </div>
              <div 
                v-else 
                class="w-20 h-20 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-lg"
              >
                {{ getInitials(form.full_name) }}
              </div>
            </div>
            <div class="flex-1">
              <input
                id="profile_picture"
                type="file"
                accept="image/jpeg,image/png,image/jpg"
                @change="handleFileUpload"
                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
              />
              <p class="text-xs text-gray-500 mt-1">
                Maximum file size: 2MB. Supported formats: JPG, PNG
                <span v-if="mode === 'edit'">Leave empty to keep current picture.</span>
              </p>
              <InputError :message="form.errors.profile_picture" />
            </div>
          </div>
        </div>

        <!-- Active Status -->
        <div>
          <div class="flex items-center space-x-2">
            <Checkbox
              id="is_active"
              v-model:checked="form.is_active"
            />
            <Label for="is_active">Active Teacher</Label>
          </div>
          <p class="text-xs text-gray-500 mt-1">
            Active teachers can be assigned to courses and are visible to students.
          </p>
          <InputError :message="form.errors.is_active" />
        </div>
      </CardContent>
    </Card>
  </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Checkbox } from '@/components/ui/checkbox'
import InputError from '@/components/InputError.vue'
import Icon from '@/components/Icon.vue'

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

interface Props {
  form: any
  mode: 'create' | 'edit'
  teacher?: Teacher
}

const props = defineProps<Props>()

const newProfilePicture = ref<File | null>(null)

const currentProfilePicture = computed(() => {
  return props.teacher?.profile_picture || null
})

const getInitials = (name: string): string => {
  return name
    .split(' ')
    .map(word => word.charAt(0))
    .join('')
    .toUpperCase()
    .substring(0, 2)
}

const handleFileUpload = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (target.files && target.files[0]) {
    newProfilePicture.value = target.files[0]
    props.form.profile_picture = target.files[0]
  }
}
</script> 