<template>
  <FrontendLayout title="Student Dashboard - Iqra Online Academy">
    <Head title="Student Dashboard" />

    <!-- Dashboard Content -->
    <section class="py-16 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
          
          <!-- Sidebar Navigation -->
          <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sticky top-8">
              <h3 class="text-xl font-bold text-gray-900 mb-6">Dashboard Menu</h3>
              
              <nav class="space-y-2">
                <button 
                  v-for="item in navigationItems" 
                  :key="item.id"
                  @click="activeSection = item.id"
                  :class="[
                    'w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-left transition-colors duration-200',
                    activeSection === item.id 
                      ? 'bg-[#5f5fcd] text-white' 
                      : 'text-gray-700 hover:bg-gray-100'
                  ]"
                >
                  <component :is="item.icon" class="w-5 h-5" />
                  <span>{{ item.name }}</span>
                </button>
              </nav>

              <!-- Islamic Quote -->
              <div class="mt-8 p-4 bg-[#2d5a27]/5 rounded-lg border-l-4 border-[#2d5a27]">
                <p class="text-sm text-gray-600 italic mb-2">
                  "Whoever seeks knowledge, Allah will make the path to Paradise easy for them."
                </p>
                <p class="text-xs text-gray-500">- Hadith Sharif</p>
              </div>
            </div>
          </div>

          <!-- Main Content -->
          <div class="lg:col-span-3">
            
            <!-- Enrolled Courses Section -->
            <div v-if="activeSection === 'courses'" class="space-y-8">
              <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-900">My Courses</h2>
                <PrimaryButton
                  @click="goToCourses"
                  variant="outline"
                  size="md"
                  :icon="PlusIcon"
                >
                  Find New Courses
                </PrimaryButton>
              </div>

              <div v-if="enrollments.length === 0" class="text-center py-12">
                <div class="flex flex-col items-center justify-center space-y-4">
                  <BookOpenIcon class="h-16 w-16 text-gray-400" />
                  <h3 class="text-lg font-medium text-gray-900">No courses enrolled yet</h3>
                  <p class="text-gray-600">Start your learning journey by enrolling in a course.</p>
                  <PrimaryButton
                    @click="goToCourses"
                    variant="primary"
                    size="md"
                    :icon="PlusIcon"
                  >
                    Browse Courses
                  </PrimaryButton>
                </div>
              </div>

              <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div 
                  v-for="enrollment in enrollments" 
                  :key="enrollment.id"
                  class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300"
                >
                  <!-- Status Badge -->
                  <div class="relative">
                    <div class="w-full h-48">
                      <img 
                        v-if="isValidImage(enrollment.course.thumbnail_image)" 
                        :src="enrollment.course.thumbnail_image" 
                        :alt="enrollment.course.title"
                        class="w-full h-full object-cover"
                      />
                      <CoursePlaceholder 
                        v-else 
                        text="Course Image"
                        class="w-full h-full"
                      />
                    </div>
                    
                    <!-- Payment Status Badge -->
                    <div class="absolute top-4 right-4">
                      <span 
                        v-if="enrollment.payment_status === 'pending'"
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"
                      >
                        Payment Pending
                      </span>
                      <span 
                        v-else-if="enrollment.payment_status === 'completed'"
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"
                      >
                        Verified
                      </span>
                      <span 
                        v-else-if="enrollment.payment_status === 'failed'"
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800"
                      >
                        Payment Failed
                      </span>
                    </div>
                  </div>
                  
                  <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ enrollment.course.title }}</h3>
                    <p class="text-gray-600 mb-2">{{ enrollment.course.instructor }}</p>
                    <p v-if="enrollment.course.category" class="text-sm text-gray-500 mb-4">
                      {{ enrollment.course.category }}
                    </p>
                    
                    <!-- Payment Status Message -->
                    <div 
                      v-if="enrollment.payment_status === 'pending'"
                      class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg"
                    >
                      <p class="text-sm text-yellow-800">
                        <span class="font-medium">Payment Verification Pending:</span>
                        Your payment is being verified by our admin team. You'll be able to access the course once verified.
                      </p>
                    </div>
                    
                    <div 
                      v-else-if="enrollment.payment_status === 'failed'"
                      class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg"
                    >
                      <p class="text-sm text-red-800">
                        <span class="font-medium">Payment Failed:</span>
                        Please contact support to resolve payment issues.
                      </p>
                    </div>
                    
                    <!-- Progress Bar (only show for verified enrollments) -->
                    <div v-if="enrollment.payment_status === 'completed'" class="mb-4">
                      <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>Progress</span>
                        <span>{{ enrollment.progress_percentage }}%</span>
                      </div>
                      <div class="w-full bg-gray-200 rounded-full h-2">
                        <div 
                          class="bg-[#2d5a27] h-2 rounded-full transition-all duration-300"
                          :style="{ width: enrollment.progress_percentage + '%' }"
                        ></div>
                      </div>
                    </div>

                    <div class="flex space-x-3">
                      <PrimaryButton
                        v-if="enrollment.payment_status === 'completed'"
                        @click="goToLearning(enrollment.course.slug)"
                        variant="primary"
                        size="sm"
                        :icon="PlayIcon"
                        class="flex-1 justify-center"
                      >
                        {{ enrollment.progress_percentage === 0 ? 'Start Learning' : 'Continue' }}
                      </PrimaryButton>
                      
                      <PrimaryButton
                        v-else
                        @click="handlePendingCourseAccess(enrollment)"
                        variant="secondary"
                        size="sm"
                        :icon="LockIcon"
                        class="flex-1 justify-center"
                        disabled
                      >
                        {{ enrollment.payment_status === 'pending' ? 'Awaiting Verification' : 'Access Restricted' }}
                      </PrimaryButton>
                      
                      <PrimaryButton
                        @click="goToCourseDetails(enrollment.course.slug)"
                        variant="outline"
                        size="sm"
                        :icon="BookOpenIcon"
                        class="flex-1 justify-center"
                      >
                        Details
                      </PrimaryButton>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Profile Section -->
            <div v-if="activeSection === 'profile'" class="space-y-8">
              <h2 class="text-2xl font-bold text-gray-900">Profile Settings</h2>
              
              <!-- Profile Information -->
              <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Personal Information</h3>
                <form @submit.prevent="updateProfile" class="space-y-6">
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                      <input
                        v-model="profileForm.full_name"
                        type="text"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent"
                        :class="{ 'border-red-500': profileForm.errors.full_name }"
                      />
                      <div v-if="profileForm.errors.full_name" class="mt-1 text-sm text-red-600">
                        {{ profileForm.errors.full_name }}
                      </div>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                      <input
                        :value="student.email"
                        type="email"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                        disabled
                      />
                      <p class="mt-1 text-xs text-gray-500">Email cannot be changed</p>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                      <input
                        v-model="profileForm.phone"
                        type="tel"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent"
                        :class="{ 'border-red-500': profileForm.errors.phone }"
                      />
                      <div v-if="profileForm.errors.phone" class="mt-1 text-sm text-red-600">
                        {{ profileForm.errors.phone }}
                      </div>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                      <input
                        v-model="profileForm.date_of_birth"
                        type="date"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent"
                        :class="{ 'border-red-500': profileForm.errors.date_of_birth }"
                      />
                      <div v-if="profileForm.errors.date_of_birth" class="mt-1 text-sm text-red-600">
                        {{ profileForm.errors.date_of_birth }}
                      </div>
                    </div>
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <textarea
                      v-model="profileForm.address"
                      rows="3"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent"
                      :class="{ 'border-red-500': profileForm.errors.address }"
                    ></textarea>
                    <div v-if="profileForm.errors.address" class="mt-1 text-sm text-red-600">
                      {{ profileForm.errors.address }}
                    </div>
                  </div>

                  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                      <input
                        v-model="profileForm.city"
                        type="text"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent"
                        :class="{ 'border-red-500': profileForm.errors.city }"
                      />
                      <div v-if="profileForm.errors.city" class="mt-1 text-sm text-red-600">
                        {{ profileForm.errors.city }}
                      </div>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">District</label>
                      <input
                        v-model="profileForm.district"
                        type="text"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent"
                        :class="{ 'border-red-500': profileForm.errors.district }"
                      />
                      <div v-if="profileForm.errors.district" class="mt-1 text-sm text-red-600">
                        {{ profileForm.errors.district }}
                      </div>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                      <input
                        v-model="profileForm.country"
                        type="text"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent"
                        :class="{ 'border-red-500': profileForm.errors.country }"
                      />
                      <div v-if="profileForm.errors.country" class="mt-1 text-sm text-red-600">
                        {{ profileForm.errors.country }}
                      </div>
                    </div>
                  </div>

                  <div class="flex space-x-4 pt-6 border-t border-gray-200">
                    <PrimaryButton
                      type="submit"
                      variant="primary"
                      size="md"
                      :icon="SaveIcon"
                      :disabled="profileForm.processing"
                    >
                      {{ profileForm.processing ? 'Updating...' : 'Update Profile' }}
                    </PrimaryButton>
                  </div>
                </form>
              </div>

              <!-- Change Password Section -->
              <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Change Password</h3>
                <form @submit.prevent="changePassword" class="space-y-6">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                    <input
                      v-model="passwordForm.current_password"
                      type="password"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent"
                      :class="{ 'border-red-500': passwordForm.errors.current_password }"
                      required
                    />
                    <div v-if="passwordForm.errors.current_password" class="mt-1 text-sm text-red-600">
                      {{ passwordForm.errors.current_password }}
                    </div>
                  </div>

                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                      <input
                        v-model="passwordForm.password"
                        type="password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent"
                        :class="{ 'border-red-500': passwordForm.errors.password }"
                        required
                      />
                      <div v-if="passwordForm.errors.password" class="mt-1 text-sm text-red-600">
                        {{ passwordForm.errors.password }}
                      </div>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                      <input
                        v-model="passwordForm.password_confirmation"
                        type="password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent"
                        :class="{ 'border-red-500': passwordForm.errors.password_confirmation }"
                        required
                      />
                      <div v-if="passwordForm.errors.password_confirmation" class="mt-1 text-sm text-red-600">
                        {{ passwordForm.errors.password_confirmation }}
                      </div>
                    </div>
                  </div>

                  <div class="flex space-x-4 pt-6 border-t border-gray-200">
                    <PrimaryButton
                      type="submit"
                      variant="primary"
                      size="md"
                      :icon="LockIcon"
                      :disabled="passwordForm.processing"
                    >
                      {{ passwordForm.processing ? 'Updating...' : 'Change Password' }}
                    </PrimaryButton>
                  </div>
                </form>
              </div>
            </div>

            <!-- Certificates Section -->
            <div v-if="activeSection === 'certificates'" class="space-y-8">
              <h2 class="text-2xl font-bold text-gray-900">Certificates</h2>
              
              <div v-if="certificates.length === 0" class="text-center py-12">
                <div class="flex flex-col items-center justify-center space-y-4">
                  <AwardIcon class="h-16 w-16 text-gray-400" />
                  <h3 class="text-lg font-medium text-gray-900">No certificates yet</h3>
                  <p class="text-gray-600">Complete courses to earn certificates.</p>
                </div>
              </div>
              
              <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div 
                  v-for="certificate in certificates" 
                  :key="certificate.id"
                  class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6"
                >
                  <div class="flex items-center space-x-4 mb-4">
                    <div class="w-16 h-16 bg-[#d4a574]/10 rounded-full flex items-center justify-center">
                      <AwardIcon class="w-8 h-8 text-[#d4a574]" />
                    </div>
                    <div>
                      <h3 class="text-lg font-semibold text-gray-900">{{ certificate.course }}</h3>
                      <p class="text-gray-600">{{ certificate.date }}</p>
                    </div>
                  </div>
                  
                  <PrimaryButton
                    @click="downloadCertificate(certificate)"
                    variant="outline"
                    size="sm"
                    :icon="DownloadIcon"
                    class="w-full justify-center"
                  >
                    Download Certificate
                  </PrimaryButton>
                </div>
              </div>
            </div>

            <!-- Progress Section -->
            <div v-if="activeSection === 'progress'" class="space-y-8">
              <h2 class="text-2xl font-bold text-gray-900">Progress Report</h2>
              
              <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                <!-- Recent Activity -->
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
                <div v-if="recentActivities.length === 0" class="text-center py-8">
                  <p class="text-gray-500">No recent activity to show.</p>
                </div>
                <div v-else class="space-y-4">
                  <div v-for="activity in recentActivities" :key="activity.id" class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-[#5f5fcd]/10 rounded-full flex items-center justify-center">
                      <CheckIcon class="w-5 h-5 text-[#5f5fcd]" />
                    </div>
                    <div class="flex-1">
                      <p class="font-medium text-gray-900">{{ activity.title }}</p>
                      <p class="text-sm text-gray-600">{{ activity.course }}</p>
                    </div>
                    <div class="text-sm text-gray-500">{{ activity.date }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </FrontendLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { router, Head, useForm } from '@inertiajs/vue3'
import FrontendLayout from '@/layouts/FrontendLayout.vue'
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue'
import CoursePlaceholder from '@/components/Frontend/CoursePlaceholder.vue'
import Icon from '@/components/Icon.vue'
import { useToast } from '@/composables/useToast'
import {
  BookOpenIcon,
  PlayIcon,
  PlusIcon,
  SaveIcon,
  SettingsIcon,
  AwardIcon,
  DownloadIcon,
  CheckIcon,
  TrendingUpIcon,
  LockIcon
} from 'lucide-vue-next'

// Props
interface Props {
  student?: {
    id: number
    full_name: string
    email: string
    phone?: string
    profile_picture?: string
    date_of_birth?: string
    address?: string
    city?: string
    district?: string
    country?: string
    is_active: boolean
    is_verified: boolean
  }
  enrollments?: Array<{
    id: number
    course: {
      id: number
      title: string
      slug: string
      thumbnail_image?: string
      category?: string
      instructor?: string
    }
    enrolled_at: string
    enrollment_type: string
    payment_status: string
    progress_percentage: number
    lessons_completed: number
    is_completed: boolean
    is_active: boolean
  }>
  recentActivities?: Array<{
    id: number
    title: string
    course: string
    date: string
    type: string
  }>
  certificates?: Array<{
    id: number
    course: string
    date: string
    course_slug: string
  }>
}

const props = defineProps<Props>()

// Toast composable
const { success, error } = useToast()

// Active section
const activeSection = ref('courses')

// Navigation items
const navigationItems = [
  { id: 'courses', name: 'My Courses', icon: BookOpenIcon },
  { id: 'profile', name: 'Profile', icon: SettingsIcon },
  { id: 'progress', name: 'Progress', icon: TrendingUpIcon },
  { id: 'certificates', name: 'Certificates', icon: AwardIcon }
]

// Default values for optional props
const student = computed(() => props.student || {
  id: 0,
  full_name: 'Student',
  email: 'student@example.com',
  phone: '',
  profile_picture: '',
  date_of_birth: '',
  address: '',
  city: '',
  district: '',
  country: '',
  is_active: false,
  is_verified: false,
})

const enrollments = computed(() => props.enrollments || [])
const recentActivities = computed(() => props.recentActivities || [])
const certificates = computed(() => props.certificates || [])

// Profile form
const profileForm = useForm({
  full_name: student.value.full_name,
  phone: student.value.phone || '',
  date_of_birth: student.value.date_of_birth || '',
  address: student.value.address || '',
  city: student.value.city || '',
  district: student.value.district || '',
  country: student.value.country || '',
})

// Password form
const passwordForm = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
})

// Methods
const goToLearning = (courseSlug: string) => {
  router.visit(route('frontend.learning.show', courseSlug))
}

const goToCourses = () => {
  router.visit(route('frontend.courses.index'))
}

const goToCourseDetails = (courseSlug: string) => {
  router.visit(`/courses/${courseSlug}`)
}

const updateProfile = () => {
  profileForm.patch(route('frontend.student.profile.update'), {
    preserveScroll: true,
    onSuccess: () => {
      success('Profile updated successfully.')
    },
    onError: () => {
      error('Failed to update profile. Please check the form and try again.')
    }
  })
}

const changePassword = () => {
  passwordForm.patch(route('frontend.student.password.update'), {
    preserveScroll: true,
    onSuccess: () => {
      success('Password changed successfully.')
      passwordForm.reset()
    },
    onError: () => {
      error('Failed to change password. Please check your current password and try again.')
    }
  })
}

const downloadCertificate = (certificate: any) => {
  // Implement certificate download functionality
}

const handlePendingCourseAccess = (enrollment: any) => {
  if (enrollment.payment_status === 'pending') {
    error('আপনার পেমেন্ট যাচাই হওয়ার পর এই কোর্সটি অ্যাক্সেস করতে পারবেন। অনুগ্রহ করে অপেক্ষা করুন।')
  } else if (enrollment.payment_status === 'failed') {
    error('পেমেন্ট সমস্যা সমাধানের জন্য অনুগ্রহ করে সাপোর্ট টিমের সাথে যোগাযোগ করুন।')
  } else {
    error('এই মুহূর্তে কোর্সটি অ্যাক্সেস করা যাচ্ছে না। পরে আবার চেষ্টা করুন।')
  }
}

// Utility function to check for a valid image
const isValidImage = (src: string | undefined | null): boolean => {
  return !!src && typeof src === 'string' && src.trim() !== ''
}
</script> 