<template>
  <div class="min-h-screen bg-gray-50">
    <div class="max-w-3xl mx-auto py-12 px-4">
      <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Change Password</h1>
        
        <form @submit.prevent="updatePassword" class="space-y-6">
          <div>
            <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
            <input
              id="current_password"
              v-model="form.current_password"
              type="password"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              required
            />
            <div v-if="form.errors.current_password" class="mt-2 text-sm text-red-600">
              {{ form.errors.current_password }}
            </div>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
            <input
              id="password"
              v-model="form.password"
              type="password"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              required
            />
            <div v-if="form.errors.password" class="mt-2 text-sm text-red-600">
              {{ form.errors.password }}
            </div>
          </div>

          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
            <input
              id="password_confirmation"
              v-model="form.password_confirmation"
              type="password"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              required
            />
            <div v-if="form.errors.password_confirmation" class="mt-2 text-sm text-red-600">
              {{ form.errors.password_confirmation }}
            </div>
          </div>

          <div>
            <button
              type="submit"
              class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
              :disabled="form.processing"
            >
              Update Password
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'

const form = reactive({
  current_password: '',
  password: '',
  password_confirmation: '',
  processing: false,
  errors: {},
})

const updatePassword = () => {
  form.processing = true
  form.errors = {}

  router.put(route('settings.password.update'), {
    current_password: form.current_password,
    password: form.password,
    password_confirmation: form.password_confirmation,
  }, {
    onSuccess: () => {
      form.processing = false
      form.current_password = ''
      form.password = ''
      form.password_confirmation = ''
    },
    onError: (errors) => {
      form.errors = errors
      form.processing = false
    }
  })
}
</script>