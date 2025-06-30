<template>
  <FrontendLayout title="পেমেন্ট - ইকরা অনলাইন একাডেমি">
    <Head :title="`পেমেন্ট - ${course.title}`" />

    <!-- Payment Header -->
    <section class="py-8 bg-gradient-to-br from-[#5f5fcd]/5 via-white to-[#2d5a27]/3 border-b border-gray-100">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <h1 class="text-3xl font-bold text-gray-900 mb-4">কোর্স এনরোলমেন্ট</h1>
          <p class="text-lg text-gray-600">আপনার শিক্ষার যাত্রা শুরু করুন</p>
        </div>
      </div>
    </section>

    <!-- Payment Content -->
    <section class="py-12 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
          
          <!-- Payment Form -->
          <div class="lg:col-span-2 space-y-8">
            
            <!-- Student Information -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
              <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <UserIcon class="w-6 h-6 text-[#5f5fcd] mr-3" />
                শিক্ষার্থীর তথ্য
              </h2>
              
              <form @submit.prevent="handleSubmit" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      পূর্ণ নাম *
                    </label>
                    <input
                      v-model="form.name"
                      type="text"
                      required
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent"
                      placeholder="আপনার পূর্ণ নাম লিখুন"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      ইমেইল ঠিকানা *
                    </label>
                    <input
                      v-model="form.email"
                      type="email"
                      required
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent"
                      placeholder="আপনার ইমেইল ঠিকানা"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      ফোন নম্বর *
                    </label>
                    <input
                      v-model="form.phone"
                      type="tel"
                      required
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent"
                      placeholder="০১৭xxxxxxxx"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      বয়স
                    </label>
                    <input
                      v-model="form.age"
                      type="number"
                      min="10"
                      max="100"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent"
                      placeholder="আপনার বয়স"
                    />
                  </div>
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    ঠিকানা
                  </label>
                  <textarea
                    v-model="form.address"
                    rows="3"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent"
                    placeholder="আপনার সম্পূর্ণ ঠিকানা"
                  ></textarea>
                </div>
              </form>
            </div>

            <!-- Payment Methods -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
              <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <CreditCardIcon class="w-6 h-6 text-[#2d5a27] mr-3" />
                পেমেন্ট পদ্ধতি
              </h2>
              
              <div class="space-y-4">
                <!-- Mobile Banking -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <label 
                    v-for="method in mobileBankingMethods" 
                    :key="method.id"
                    :class="[
                      'relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                      selectedPaymentMethod === method.id 
                        ? 'border-[#5f5fcd] bg-[#5f5fcd]/5' 
                        : 'border-gray-200 hover:border-[#5f5fcd]/30'
                    ]"
                  >
                    <input
                      v-model="selectedPaymentMethod"
                      :value="method.id"
                      type="radio"
                      class="sr-only"
                    />
                    <div class="flex items-center flex-1">
                      <!-- Custom Payment Method Icons -->
                      <div 
                        class="w-12 h-12 rounded-lg flex items-center justify-center text-white font-bold text-lg mr-4"
                        :style="{ backgroundColor: method.color }"
                      >
                                                                                                   <template v-if="method.id === 'bkash'">
                             <!-- bKash Icon (Mobile/Smartphone) -->
                             <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                               <rect x="5" y="2" width="14" height="20" rx="2" ry="2"/>
                               <line x1="12" y1="18" x2="12.01" y2="18"/>
                             </svg>
                         </template>
                         <template v-else-if="method.id === 'nagad'">
                             <!-- Nagad Icon (Banknote/Money) -->
                             <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                               <rect x="2" y="6" width="20" height="12" rx="2"/>
                               <circle cx="12" cy="12" r="2"/>
                               <path d="m6 12-2-2 2-2"/>
                               <path d="m18 12 2-2-2-2"/>
                             </svg>
                         </template>
                         <template v-else-if="method.id === 'rocket'">
                             <!-- Rocket Icon (Zap/Lightning) -->
                             <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                               <polygon points="13,2 3,14 12,14 11,22 21,10 12,10 13,2"/>
                             </svg>
                         </template>
                      </div>
                      <div>
                        <div class="font-semibold text-gray-900">{{ method.name }}</div>
                        <div class="text-sm text-gray-600">{{ method.description }}</div>
                      </div>
                    </div>
                    <CheckCircleIcon 
                      v-if="selectedPaymentMethod === method.id"
                      class="w-6 h-6 text-[#5f5fcd]" 
                    />
                  </label>
                </div>

                <!-- Bank Transfer -->
                <label 
                  :class="[
                    'relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                    selectedPaymentMethod === 'bank_transfer' 
                      ? 'border-[#2d5a27] bg-[#2d5a27]/5' 
                      : 'border-gray-200 hover:border-[#2d5a27]/30'
                  ]"
                >
                  <input
                    v-model="selectedPaymentMethod"
                    value="bank_transfer"
                    type="radio"
                    class="sr-only"
                  />
                  <div class="flex items-center flex-1">
                    <BuildingIcon class="w-12 h-12 text-[#2d5a27] mr-4" />
                    <div>
                      <div class="font-semibold text-gray-900">ব্যাংক ট্রান্সফার</div>
                      <div class="text-sm text-gray-600">সরাসরি ব্যাংক অ্যাকাউন্টে পেমেন্ট</div>
                    </div>
                  </div>
                  <CheckCircleIcon 
                    v-if="selectedPaymentMethod === 'bank_transfer'"
                    class="w-6 h-6 text-[#2d5a27]" 
                  />
                </label>
              </div>

              <!-- Payment Instructions -->
              <div v-if="selectedPaymentMethod" class="mt-6 p-4 bg-gray-50 rounded-xl">
                <h4 class="font-semibold text-gray-900 mb-3">পেমেন্ট নির্দেশনা:</h4>
                <div v-if="selectedPaymentMethod === 'bkash'" class="space-y-2 text-sm text-gray-600">
                  <p>• বিকাশে 01712-345678 নম্বরে "Send Money" করুন</p>
                  <p>• Reference হিসেবে আপনার ফোন নম্বর লিখুন</p>
                  <p>• Transaction ID সংরক্ষণ করুন</p>
                </div>
                <div v-else-if="selectedPaymentMethod === 'nagad'" class="space-y-2 text-sm text-gray-600">
                  <p>• নগদে 01812-345678 নম্বরে "Send Money" করুন</p>
                  <p>• Reference হিসেবে আপনার ফোন নম্বর লিখুন</p>
                  <p>• Transaction ID সংরক্ষণ করুন</p>
                </div>
                <div v-else-if="selectedPaymentMethod === 'rocket'" class="space-y-2 text-sm text-gray-600">
                  <p>• রকেটে 01912-345678 নম্বরে "Send Money" করুন</p>
                  <p>• Reference হিসেবে আপনার ফোন নম্বর লিখুন</p>
                  <p>• Transaction ID সংরক্ষণ করুন</p>
                </div>
                <div v-else-if="selectedPaymentMethod === 'bank_transfer'" class="space-y-2 text-sm text-gray-600">
                  <p>• ব্যাংক: ইসলামী ব্যাংক বাংলাদেশ লিমিটেড</p>
                  <p>• অ্যাকাউন্ট নাম: ইকরা অনলাইন একাডেমি</p>
                  <p>• অ্যাকাউন্ট নম্বর: 20123456789012</p>
                  <p>• ব্রাঞ্চ: ধানমন্ডি, ঢাকা</p>
                </div>
              </div>

              <!-- Transaction ID -->
              <div v-if="selectedPaymentMethod && selectedPaymentMethod !== 'bank_transfer'" class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Transaction ID *
                </label>
                <input
                  v-model="form.transactionId"
                  type="text"
                  required
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent"
                  placeholder="পেমেন্টের Transaction ID দিন"
                />
              </div>
            </div>

            <!-- Terms and Conditions -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
              <h2 class="text-2xl font-bold text-gray-900 mb-6">শর্তাবলী</h2>
              <div class="space-y-4 text-sm text-gray-600">
                <label class="flex items-start space-x-3">
                  <input
                    v-model="form.agreeTerms"
                    type="checkbox"
                    required
                    class="mt-1 w-4 h-4 text-[#5f5fcd] border-gray-300 rounded focus:ring-[#5f5fcd]"
                  />
                  <span>আমি <Link href="#" class="text-[#5f5fcd] hover:underline">শর্তাবলী ও নীতিমালা</Link> পড়েছি এবং সম্মত আছি</span>
                </label>
                <label class="flex items-start space-x-3">
                  <input
                    v-model="form.agreeRefund"
                    type="checkbox"
                    required
                    class="mt-1 w-4 h-4 text-[#5f5fcd] border-gray-300 rounded focus:ring-[#5f5fcd]"
                  />
                  <span>আমি <Link href="#" class="text-[#5f5fcd] hover:underline">রিফান্ড নীতি</Link> সম্পর্কে অবগত আছি</span>
                </label>
                <label class="flex items-start space-x-3">
                  <input
                    v-model="form.agreeNewsletter"
                    type="checkbox"
                    class="mt-1 w-4 h-4 text-[#5f5fcd] border-gray-300 rounded focus:ring-[#5f5fcd]"
                  />
                  <span>নতুন কোর্স ও আপডেটের জন্য ইমেইল নোটিফিকেশন পেতে চাই</span>
                </label>
              </div>
            </div>
          </div>

          <!-- Order Summary -->
          <div class="lg:col-span-1">
            <div class="sticky top-8 space-y-6">
              
              <!-- Course Summary -->
              <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                  <h3 class="text-lg font-bold text-gray-900">অর্ডার সামারি</h3>
                </div>
                
                <div class="p-5">
                  <!-- Course Info -->
                  <div class="mb-5 pb-4 border-b border-gray-100">
                    <h4 class="font-semibold text-gray-900 mb-1">{{ course.title }}</h4>
                    <p class="text-sm text-gray-600 mb-2">{{ course.category }}</p>
                    <div class="flex items-center text-sm text-gray-500">
                      <UsersIcon class="w-4 h-4 mr-1" />
                      <span>{{ course.students_count }}+ শিক্ষার্থী</span>
                    </div>
                  </div>

                  <!-- Coupon Code -->
                  <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      কুপন কোড (ঐচ্ছিক)
                    </label>
                    <div class="flex space-x-2">
                      <input
                        v-model="couponCode"
                        type="text"
                        :disabled="couponApplied"
                        class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent disabled:bg-gray-100 disabled:cursor-not-allowed"
                        placeholder="কুপন কোড লিখুন (যেমন: IQRA10)"
                      />
                      <PrimaryButton
                        v-if="!couponApplied"
                        @click="applyCoupon"
                        variant="outline"
                        size="md"
                        :disabled="!couponCode"
                      >
                        প্রয়োগ করুন
                      </PrimaryButton>
                      <PrimaryButton
                        v-else
                        @click="removeCoupon"
                        variant="destructive"
                        size="md"
                        :icon="XIcon"
                      >
                        রিমুভ করুন
                      </PrimaryButton>
                    </div>
                    
                    <!-- Testing Hints -->
                    <div class="mt-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                      <p class="text-xs text-blue-700 font-medium mb-1">পরীক্ষার জন্য কুপন কোড:</p>
                      <div class="text-xs text-blue-600 space-y-1">
                        <div><strong>IQRA10</strong> - ১০% ছাড় (বৈধ)</div>
                        <div><strong>STUDENT50</strong> - ৫০% ছাড় (বৈধ)</div>
                        <div><strong>OLDCOUPON</strong> - মেয়াদোত্তীর্ণ (পরীক্ষার জন্য)</div>
                        <div><strong>EXPIRED2024</strong> - মেয়াদোত্তীর্ণ (পরীক্ষার জন্য)</div>
                        <div><strong>INVALID123</strong> - অবৈধ কোড (পরীক্ষার জন্য)</div>
                      </div>
                      

                    </div>
                  </div>

                  <!-- Price Breakdown -->
                  <div class="space-y-2 border-t border-gray-100 pt-4">
                    <div class="flex justify-between text-gray-600">
                      <span>কোর্স ফি</span>
                      <span>৳{{ course.price.toLocaleString() }}</span>
                    </div>
                    <div v-if="discount > 0" class="flex justify-between text-green-600">
                      <span>ছাড় ({{ appliedCoupon }})</span>
                      <span>-৳{{ discount.toLocaleString() }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg text-gray-900 border-t border-gray-100 pt-2">
                      <span>সর্বমোট</span>
                      <span>৳{{ finalPrice.toLocaleString() }}</span>
                    </div>
                  </div>

                  <!-- Payment Button -->
                  <PrimaryButton
                    @click="handleSubmit"
                    :disabled="!canSubmit"
                    :loading="isSubmitting"
                    size="lg"
                    variant="primary"
                    :icon="CreditCardIcon"
                    class="w-full justify-center mt-5"
                  >
                    {{ isSubmitting ? 'প্রক্রিয়াধীন...' : `৳${finalPrice.toLocaleString()} পেমেন্ট করুন` }}
                  </PrimaryButton>
                </div>
              </div>

              <!-- Security Info -->
              <div class="bg-[#2d5a27]/5 rounded-2xl p-6">
                <div class="flex items-center space-x-3 mb-4">
                  <ShieldCheckIcon class="w-6 h-6 text-[#2d5a27]" />
                  <h4 class="font-semibold text-gray-900">নিরাপত্তা গ্যারান্টি</h4>
                </div>
                <ul class="space-y-2 text-sm text-gray-600">
                  <li class="flex items-center space-x-2">
                    <CheckIcon class="w-4 h-4 text-[#2d5a27]" />
                    <span>১০০% নিরাপদ পেমেন্ট</span>
                  </li>
                  <li class="flex items-center space-x-2">
                    <CheckIcon class="w-4 h-4 text-[#2d5a27]" />
                    <span>৭ দিনের মানি ব্যাক গ্যারান্টি</span>
                  </li>
                  <li class="flex items-center space-x-2">
                    <CheckIcon class="w-4 h-4 text-[#2d5a27]" />
                    <span>লাইফটাইম অ্যাক্সেস</span>
                  </li>
                  <li class="flex items-center space-x-2">
                    <CheckIcon class="w-4 h-4 text-[#2d5a27]" />
                    <span>২৪/৭ কাস্টমার সাপোর্ট</span>
                  </li>
                </ul>
              </div>

              <!-- Need Help -->
              <div class="bg-[#5f5fcd]/5 rounded-2xl p-6 text-center">
                <h4 class="font-semibold text-gray-900 mb-2">সাহায্য দরকার?</h4>
                <p class="text-sm text-gray-600 mb-4">আমাদের সাপোর্ট টিম সর্বদা আপনার সেবায় নিয়োজিত</p>
                <PrimaryButton
                  variant="outline"
                  size="sm"
                  :icon="PhoneIcon"
                  class="w-full justify-center"
                >
                  +৮৮০১৭১২-৩৪৫৬৭৮
                </PrimaryButton>
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
import { Link, Head, router } from '@inertiajs/vue3'
import FrontendLayout from '@/layouts/FrontendLayout.vue'
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue'
import {
  UserIcon,
  CreditCardIcon,
  CheckCircleIcon,
  BuildingIcon,
  UsersIcon,
  ShieldCheckIcon,
  CheckIcon,
  PhoneIcon,
  XIcon
} from 'lucide-vue-next'
import { useToast } from '@/composables/useToast'

// Course interface
interface Course {
  id: number
  title: string
  slug: string
  description: string
  image: string
  category: string
  price: number
  students_count: number
  level?: string
  duration?: string
  rating?: number
  instructor?: {
    name: string
    avatar: string
  }
}

// Props from Laravel controller
const props = defineProps<{
  course: Course
  title?: string
}>()

// Use course data from props
const course = ref(props.course)

// Form state
const form = ref({
  name: '',
  email: '',
  phone: '',
  age: '',
  address: '',
  transactionId: '',
  agreeTerms: false,
  agreeRefund: false,
  agreeNewsletter: false
})

const selectedPaymentMethod = ref('')
const isSubmitting = ref(false)
const discount = ref(0)

// Coupon state
const couponCode = ref('')
const couponApplied = ref(false)
const appliedCoupon = ref('')

// Toast system
const toast = useToast()

// Payment methods
const mobileBankingMethods = [
  {
    id: 'bkash',
    name: 'বিকাশ',
    description: 'Send Money দিয়ে পেমেন্ট',
    color: '#e2136e',
    bgColor: '#fce7f3'
  },
  {
    id: 'nagad',
    name: 'নগদ',
    description: 'Send Money দিয়ে পেমেন্ট',
    color: '#ec4a0a',
    bgColor: '#fff7ed'
  },
  {
    id: 'rocket',
    name: 'রকেট',
    description: 'Send Money দিয়ে পেমেন্ট',
    color: '#8b5cf6',
    bgColor: '#f3e8ff'
  }
]

// Computed properties
const finalPrice = computed(() => course.value.price - discount.value)

const canSubmit = computed(() => {
  // Basic validation for UI testing - just need name, email, payment method and terms
  return form.value.name && 
         form.value.email && 
         selectedPaymentMethod.value &&
         form.value.agreeTerms && 
         form.value.agreeRefund &&
         !isSubmitting.value
})

// Methods
const applyCoupon = () => {
  if (!couponCode.value) {
    toast.warning('অনুগ্রহ করে একটি কুপন কোড প্রবেশ করান।')
    return
  }

  // Mock coupon validation with expiry dates (in real app, this would be an API call)
  const coupons = {
    'IQRA10': { 
      discount: 10, 
      type: 'percentage', 
      expiry: '2025-12-31',
      active: true 
    },
    'RAMADAN': { 
      discount: 200, 
      type: 'fixed', 
      expiry: '2024-05-30', 
      active: true 
    },
    'STUDENT50': { 
      discount: 50, 
      type: 'percentage', 
      expiry: '2025-06-30', 
      active: true 
    },
    'OLDCOUPON': { 
      discount: 20, 
      type: 'percentage', 
      expiry: '2023-12-31', 
      active: false 
    },
    'EXPIRED2024': { 
      discount: 30, 
      type: 'percentage', 
      expiry: '2024-01-31', 
      active: false 
    }
  }

  const inputCode = couponCode.value.toUpperCase()
  const coupon = coupons[inputCode as keyof typeof coupons]
  
  if (!coupon) {
    // Invalid/non-existent coupon code
    couponApplied.value = false
    discount.value = 0
    appliedCoupon.value = ''
    
    toast.error({
      title: 'অবৈধ কুপন কোড',
      message: 'এই কুপন কোডটি বিদ্যমান নেই। সঠিক কোড দিন।',
      duration: 6000
    })
    return
  }

  if (!coupon.active || new Date(coupon.expiry) < new Date()) {
    // Expired coupon
    couponApplied.value = false
    discount.value = 0
    appliedCoupon.value = ''
    
    toast.error({
      title: 'মেয়াদোত্তীর্ণ কুপন কোড',
      message: `এই কুপনটির মেয়াদ ${coupon.expiry} তারিখে শেষ হয়ে গেছে।`,
      duration: 7000
    })
    return
  }

  // Valid coupon - apply discount
  if (coupon.type === 'percentage') {
    discount.value = Math.round((course.value.price * coupon.discount) / 100)
  } else {
    discount.value = coupon.discount
  }
  
  couponApplied.value = true
  appliedCoupon.value = inputCode
  
  toast.success({
    title: 'কুপন সফলভাবে প্রয়োগ হয়েছে!',
    message: `৳${discount.value} ছাড় পেয়েছেন। কুপন: ${appliedCoupon.value}`,
    duration: 5000
  })
}

const removeCoupon = () => {
  // Reset coupon state
  couponApplied.value = false
  discount.value = 0
  appliedCoupon.value = ''
  couponCode.value = ''
  
  // Show success toast for coupon removal
  toast.success({
    title: 'কুপন সরানো হয়েছে',
    message: 'কুপন কোড সফলভাবে রিমুভ করা হয়েছে।',
    duration: 3000
  })
}

const handleSubmit = async () => {
  if (!canSubmit.value) {
    toast.warning('অনুগ্রহ করে সব তথ্য সঠিকভাবে পূরণ করুন।')
    return
  }

  isSubmitting.value = true

  try {
    // Show processing toast
    const processingToast = toast.info({
      message: 'পেমেন্ট প্রক্রিয়াধীন... অনুগ্রহ করে অপেক্ষা করুন।',
      persistent: true
    })

    // Simulate payment processing for UI testing
    await new Promise(resolve => setTimeout(resolve, 2000))

    // Remove processing toast
    toast.dismiss(processingToast)

    // Show success toast
    toast.success({
      title: 'পেমেন্ট সফল!',
      message: `${course.value.title} কোর্সে সফলভাবে নিবন্ধিত হয়েছেন।`,
      duration: 5000
    })

    // Wait a bit then redirect
    setTimeout(() => {
      router.visit(route('frontend.student.dashboard'))
    }, 1500)
    
  } catch (error) {
    toast.error({
      title: 'পেমেন্ট ব্যর্থ!',
      message: 'পেমেন্ট প্রক্রিয়ায় সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।',
      duration: 7000
    })
  } finally {
    isSubmitting.value = false
  }
}
</script>