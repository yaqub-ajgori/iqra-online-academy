<template>
  <AdminLayout page-title="">
    <div class="max-w-4xl mx-auto space-y-8">
      <!-- Page Header -->
      <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Admin Settings</h1>
        <p class="text-sm text-gray-600">Manage your account profile and password settings.</p>
      </div>

      <!-- Profile Information -->
      <Card class="border border-gray-200 shadow-sm">
        <CardHeader class="border-b border-gray-100 bg-gray-50">
          <CardTitle class="flex items-center text-gray-900">
            <Icon name="user" class="mr-3 h-5 w-5 text-[#5f5fcd]" />
            Profile Information
          </CardTitle>
          <CardDescription>
            Update your account's profile information and email address.
          </CardDescription>
        </CardHeader>
        <CardContent class="p-6">
          <form @submit.prevent="updateProfile" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Name -->
              <div>
                <Label for="name" class="text-sm font-medium text-gray-700 mb-2 block">Name</Label>
                <Input
                  id="name"
                  v-model="profileForm.name"
                  type="text"
                  required
                  class="w-full"
                  :class="{ 'border-red-500': profileForm.errors.name }"
                />
                <InputError :message="profileForm.errors.name" class="mt-1" />
              </div>

              <!-- Email -->
              <div>
                <Label for="email" class="text-sm font-medium text-gray-700 mb-2 block">Email</Label>
                <Input
                  id="email"
                  v-model="profileForm.email"
                  type="email"
                  required
                  class="w-full"
                  :class="{ 'border-red-500': profileForm.errors.email }"
                />
                <InputError :message="profileForm.errors.email" class="mt-1" />
              </div>
            </div>

            <div class="flex items-center justify-end pt-4 border-t border-gray-200">
              <Button
                type="submit"
                :disabled="profileForm.processing"
                variant="primary"
              >
                {{ profileForm.processing ? 'Saving...' : 'Save Changes' }}
              </Button>
            </div>
          </form>
        </CardContent>
      </Card>

      <!-- Change Password -->
      <Card class="border border-gray-200 shadow-sm">
        <CardHeader class="border-b border-gray-100 bg-gray-50">
          <CardTitle class="flex items-center text-gray-900">
            <Icon name="lock" class="mr-3 h-5 w-5 text-[#2d5a27]" />
            Change Password
          </CardTitle>
          <CardDescription>
            Ensure your account is using a long, random password to stay secure.
          </CardDescription>
        </CardHeader>
        <CardContent class="p-6">
          <form @submit.prevent="updatePassword" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Current Password -->
              <div class="md:col-span-2">
                <Label for="current_password" class="text-sm font-medium text-gray-700 mb-2 block">Current Password</Label>
                <Input
                  id="current_password"
                  v-model="passwordForm.current_password"
                  type="password"
                  class="w-full"
                  :class="{ 'border-red-500': passwordForm.errors.current_password }"
                />
                <InputError :message="passwordForm.errors.current_password" class="mt-1" />
              </div>

              <!-- New Password -->
              <div>
                <Label for="password" class="text-sm font-medium text-gray-700 mb-2 block">New Password</Label>
                <Input
                  id="password"
                  v-model="passwordForm.password"
                  type="password"
                  class="w-full"
                  :class="{ 'border-red-500': passwordForm.errors.password }"
                />
                <InputError :message="passwordForm.errors.password" class="mt-1" />
              </div>

              <!-- Confirm Password -->
              <div>
                <Label for="password_confirmation" class="text-sm font-medium text-gray-700 mb-2 block">Confirm Password</Label>
                <Input
                  id="password_confirmation"
                  v-model="passwordForm.password_confirmation"
                  type="password"
                  class="w-full"
                  :class="{ 'border-red-500': passwordForm.errors.password_confirmation }"
                />
                <InputError :message="passwordForm.errors.password_confirmation" class="mt-1" />
              </div>
            </div>

            <div class="flex items-center justify-end pt-4 border-t border-gray-200">
              <Button
                type="submit"
                :disabled="passwordForm.processing"
                variant="primary"
              >
                {{ passwordForm.processing ? 'Updating...' : 'Update Password' }}
              </Button>
            </div>
          </form>
        </CardContent>
      </Card>
    </div>
  </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import InputError from '@/components/InputError.vue'
import Icon from '@/components/Icon.vue'
import { useToast } from '@/composables/useToast'

const { success, error } = useToast()

const props = defineProps({
  user: Object,
})

// Profile form
const profileForm = useForm({
  name: props.user.name,
  email: props.user.email,
})

// Password form
const passwordForm = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
})

const updateProfile = () => {
  profileForm.patch(route('admin.settings.update'), {
    preserveScroll: true,
    onSuccess: () => {
      success('Profile updated successfully.')
    },
    onError: () => {
      error('Failed to update profile.')
    },
  })
}

const updatePassword = () => {
  passwordForm.patch(route('admin.settings.update'), {
    preserveScroll: true,
    onSuccess: () => {
      passwordForm.reset()
      success('Password updated successfully.')
    },
    onError: () => {
      error('Failed to update password.')
    },
  })
}
</script> 