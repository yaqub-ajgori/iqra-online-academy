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
          Student's primary information
        </CardDescription>
      </CardHeader>
      <CardContent class="space-y-6">
        <!-- User Selection (Create mode only) -->
        <div v-if="mode === 'create'">
          <Label for="user_id">Select User Account *</Label>
          <Select v-model="form.user_id">
            <SelectTrigger>
              <SelectValue :value="form.user_id">
                {{ selectedUserText || 'Select a user...' }}
              </SelectValue>
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="">Select a user...</SelectItem>
              <SelectItem
                v-for="user in availableUsers"
                :key="user.id"
                :value="user.id.toString()"
              >
                {{ user.name }} ({{ user.email }})
              </SelectItem>
            </SelectContent>
          </Select>
          <InputError :message="form.errors.user_id" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Full Name -->
          <div class="md:col-span-2">
            <Label for="full_name">Full Name *</Label>
            <Input
              id="full_name"
              v-model="form.full_name"
              type="text"
              placeholder="Enter student's full name"
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

          <!-- Date of Birth -->
          <div>
            <Label for="date_of_birth">Date of Birth</Label>
            <Input
              id="date_of_birth"
              v-model="form.date_of_birth"
              type="date"
            />
            <InputError :message="form.errors.date_of_birth" />
          </div>

          <!-- Gender -->
          <div>
            <Label for="gender">Gender</Label>
            <Select v-model="form.gender">
              <SelectTrigger>
                <SelectValue :value="form.gender">
                  {{ genderText || 'Select gender...' }}
                </SelectValue>
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="">Select gender...</SelectItem>
                <SelectItem value="male">Male</SelectItem>
                <SelectItem value="female">Female</SelectItem>
                <SelectItem value="other">Other</SelectItem>
              </SelectContent>
            </Select>
            <InputError :message="form.errors.gender" />
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
        </div>
      </CardContent>
    </Card>

    <!-- Address Information -->
    <Card>
      <CardHeader>
        <CardTitle class="flex items-center text-gray-900">
          <Icon name="MapPin" class="w-5 h-5 mr-2 text-green-600" />
          Address Information
        </CardTitle>
        <CardDescription>
          Student's location and contact details
        </CardDescription>
      </CardHeader>
      <CardContent class="space-y-6">
        <!-- Address -->
        <div>
          <Label for="address">Address</Label>
          <textarea
            id="address"
            v-model="form.address"
            rows="3"
            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
            placeholder="Enter full address"
          ></textarea>
          <InputError :message="form.errors.address" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- City -->
          <div>
            <Label for="city">City</Label>
            <Input
              id="city"
              v-model="form.city"
              type="text"
              placeholder="Enter city"
            />
            <InputError :message="form.errors.city" />
          </div>

          <!-- District -->
          <div>
            <Label for="district">District</Label>
            <Input
              id="district"
              v-model="form.district"
              type="text"
              placeholder="Enter district"
            />
            <InputError :message="form.errors.district" />
          </div>

          <!-- Country -->
          <div>
            <Label for="country">Country</Label>
            <Input
              id="country"
              v-model="form.country"
              type="text"
              placeholder="Enter country"
            />
            <InputError :message="form.errors.country" />
          </div>

          <!-- Postal Code -->
          <div>
            <Label for="postal_code">Postal Code</Label>
            <Input
              id="postal_code"
              v-model="form.postal_code"
              type="text"
              placeholder="Enter postal code"
            />
            <InputError :message="form.errors.postal_code" />
          </div>
        </div>
      </CardContent>
    </Card>

    <!-- Additional Information -->
    <Card>
      <CardHeader>
        <CardTitle class="flex items-center text-gray-900">
          <Icon name="Info" class="w-5 h-5 mr-2 text-purple-600" />
          Additional Information
        </CardTitle>
        <CardDescription>
          Student's background and preferences
        </CardDescription>
      </CardHeader>
      <CardContent class="space-y-6">
        <!-- Bio -->
        <div>
          <Label for="bio">Biography</Label>
          <textarea
            id="bio"
            v-model="form.bio"
            rows="4"
            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
            placeholder="Enter biography or description"
          ></textarea>
          <InputError :message="form.errors.bio" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Occupation -->
          <div>
            <Label for="occupation">Occupation</Label>
            <Input
              id="occupation"
              v-model="form.occupation"
              type="text"
              placeholder="Enter occupation"
            />
            <InputError :message="form.errors.occupation" />
          </div>

          <!-- Education Level -->
          <div>
            <Label for="education_level">Education Level</Label>
            <Input
              id="education_level"
              v-model="form.education_level"
              type="text"
              placeholder="Enter education level"
            />
            <InputError :message="form.errors.education_level" />
          </div>
        </div>
      </CardContent>
    </Card>

    <!-- Status & Permissions -->
    <Card>
      <CardHeader>
        <CardTitle class="flex items-center text-gray-900">
          <Icon name="Settings" class="w-5 h-5 mr-2 text-orange-600" />
          Status & Permissions
        </CardTitle>
        <CardDescription>
          Configure student's status and permissions
        </CardDescription>
      </CardHeader>
      <CardContent class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Verification Status -->
          <div class="flex items-center space-x-3">
            <Checkbox
              id="is_verified"
              v-model:checked="form.is_verified"
            />
            <div>
              <Label for="is_verified" class="text-sm font-medium text-gray-700">
                Verified Student
              </Label>
              <p class="text-xs text-gray-500">
                {{ mode === 'create' ? 'Mark as verified during creation' : 'Student has been verified' }}
              </p>
            </div>
          </div>

          <!-- Active Status -->
          <div class="flex items-center space-x-3">
            <Checkbox
              id="is_active"
              v-model:checked="form.is_active"
            />
            <div>
              <Label for="is_active" class="text-sm font-medium text-gray-700">
                Active Status
              </Label>
              <p class="text-xs text-gray-500">Student can access the platform</p>
            </div>
          </div>
        </div>
      </CardContent>
    </Card>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Checkbox } from '@/components/ui/checkbox'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import InputError from '@/components/InputError.vue'
import Icon from '@/components/Icon.vue'
import { getInitials } from '@/lib/utils'

// Types
interface User {
  id: number
  name: string
  email: string
}

interface Props {
  form: any
  mode: 'create' | 'edit'
  availableUsers?: User[]
  currentProfilePicture?: string
}

const props = withDefaults(defineProps<Props>(), {
  availableUsers: () => [],
  currentProfilePicture: '',
})

// Reactive state
const newProfilePicture = ref(false)

// Computed properties
const selectedUserText = computed(() => {
  if (!props.form.user_id) return ''
  const user = props.availableUsers.find(u => u.id.toString() === props.form.user_id)
  return user ? `${user.name} (${user.email})` : ''
})

const genderText = computed(() => {
  const genderMap: Record<string, string> = {
    male: 'Male',
    female: 'Female',
    other: 'Other'
  }
  return props.form.gender ? genderMap[props.form.gender] : ''
})

// Methods
const handleFileUpload = (event: Event): void => {
  const target = event.target as HTMLInputElement
  const file = target.files?.[0]
  if (file) {
    props.form.profile_picture = file
    newProfilePicture.value = true
  }
}
</script> 