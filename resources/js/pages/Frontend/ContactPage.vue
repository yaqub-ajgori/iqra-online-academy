<template>
  <FrontendLayout title="যোগাযোগ - ইকরা অনলাইন একাডেমি">
    <Head title="যোগাযোগ - ইকরা অনলাইন একাডেমি" />

    <!-- Loading State -->
    <div v-if="loading" class="min-h-screen flex items-center justify-center">
      <div class="text-center">
        <ProgressIndicator type="spinner" :size="48" show-label label="তথ্য লোড হচ্ছে..." />
        <p class="text-gray-500 mt-4">যোগাযোগের তথ্য প্রস্তুত হচ্ছে...</p>
      </div>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="min-h-screen flex items-center justify-center">
      <div class="text-center max-w-md mx-auto px-4">
        <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
          <AlertTriangleIcon class="w-12 h-12 text-red-500" />
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-4">তথ্য লোড করতে সমস্যা হয়েছে</h3>
        <p class="text-gray-600 mb-6">{{ error }}</p>
        <PrimaryButton @click="loadContactData" variant="outline">
          আবার চেষ্টা করুন
        </PrimaryButton>
      </div>
    </div>

    <!-- Main Content -->
    <div v-else>
      <!-- Page Header -->
      <section class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-20 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 pattern-dots opacity-10"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
          <div class="text-center">
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 mb-6">
              <MailIcon class="w-4 h-4 text-white mr-2" />
              <span class="text-white text-sm font-medium">যোগাযোগ করুন</span>
            </div>
            
            <h1 class="text-4xl lg:text-6xl font-bold text-white mb-6">
              {{ contactData?.title || 'আমাদের সাথে যোগাযোগ করুন' }}
            </h1>
            
            <p class="text-xl text-gray-300 max-w-3xl mx-auto leading-relaxed">
              {{ contactData?.subtitle || 'আমরা সবসময় আপনাদের প্রশ্ন ও পরামর্শের জন্য প্রস্তুত। যেকোনো সহায়তার জন্য আমাদের সাথে যোগাযোগ করতে দ্বিধা করবেন না।' }}
            </p>
          </div>
        </div>
      </section>

      <!-- Contact Section -->
      <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
            <!-- Contact Form -->
            <div>
              <div class="bg-white p-8 rounded-2xl shadow-islamic">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ contactData?.form_title || 'আমাদের বার্তা পাঠান' }}</h2>
                
                <form @submit.prevent="submitForm" class="space-y-6">
                  <!-- Name -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">আপনার নাম *</label>
                    <input 
                      v-model="form.name"
                      type="text" 
                      required
                      :disabled="isSubmitting"
                      class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent disabled:bg-gray-100 disabled:cursor-not-allowed"
                      placeholder="আপনার পূর্ণ নাম লিখুন"
                      :class="{ 'border-red-500': errors.name }"
                    />
                    <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
                  </div>

                  <!-- Email -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ইমেইল ঠিকানা *</label>
                    <input 
                      v-model="form.email"
                      type="email" 
                      required
                      :disabled="isSubmitting"
                      class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent disabled:bg-gray-100 disabled:cursor-not-allowed"
                      placeholder="your.email@example.com"
                      :class="{ 'border-red-500': errors.email }"
                    />
                    <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
                  </div>

                  <!-- Phone -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ফোন নম্বর</label>
                    <input 
                      v-model="form.phone"
                      type="tel" 
                      :disabled="isSubmitting"
                      class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent disabled:bg-gray-100 disabled:cursor-not-allowed"
                      placeholder="+৮৮০ ১২৩৪ ৫৬৭৮৯০"
                      :class="{ 'border-red-500': errors.phone }"
                    />
                    <p v-if="errors.phone" class="mt-1 text-sm text-red-600">{{ errors.phone }}</p>
                  </div>

                  <!-- Subject -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">বিষয় *</label>
                    <select 
                      v-model="form.subject"
                      required
                      :disabled="isSubmitting"
                      class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent disabled:bg-gray-100 disabled:cursor-not-allowed"
                      :class="{ 'border-red-500': errors.subject }"
                    >
                      <option value="">বিষয় নির্বাচন করুন</option>
                      <option v-for="subject in contactData?.subjects" :key="subject.value" :value="subject.value">
                        {{ subject.label }}
                      </option>
                    </select>
                    <p v-if="errors.subject" class="mt-1 text-sm text-red-600">{{ errors.subject }}</p>
                  </div>

                  <!-- Message -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">আপনার বার্তা *</label>
                    <textarea 
                      v-model="form.message"
                      required
                      rows="5"
                      :disabled="isSubmitting"
                      class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent disabled:bg-gray-100 disabled:cursor-not-allowed"
                      placeholder="আপনার প্রশ্ন বা মন্তব্য বিস্তারিত লিখুন..."
                      :class="{ 'border-red-500': errors.message }"
                    ></textarea>
                    <p v-if="errors.message" class="mt-1 text-sm text-red-600">{{ errors.message }}</p>
                  </div>

                  <!-- Submit Button -->
                  <PrimaryButton 
                    type="submit"
                    size="lg"
                    variant="primary"
                    full-width
                    :loading="isSubmitting"
                    :icon="SendIcon"
                  >
                    {{ isSubmitting ? 'পাঠানো হচ্ছে...' : 'বার্তা পাঠান' }}
                  </PrimaryButton>
                </form>
              </div>
            </div>

            <!-- Contact Information -->
            <div class="space-y-8">
              <!-- Quick Contact -->
              <div class="bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] p-8 rounded-2xl text-white">
                <h3 class="text-2xl font-bold mb-6">{{ contactData?.quick_contact_title || 'দ্রুত যোগাযোগ' }}</h3>
                
                <div class="space-y-4">
                  <div v-for="contact in contactData?.contact_info" :key="contact.type" class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                      <component :is="getContactIcon(contact.type)" class="w-5 h-5" />
                    </div>
                    <div>
                      <p class="font-medium">{{ contact.label }}</p>
                      <p class="text-white/80">{{ contact.value }}</p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Support Options -->
              <div class="bg-gray-50 p-8 rounded-2xl">
                <h3 class="text-xl font-bold text-gray-900 mb-6">{{ contactData?.support_title || 'সহায়তার মাধ্যম' }}</h3>
                
                <div class="space-y-4">
                  <div v-for="support in contactData?.support_options" :key="support.type" class="flex items-center justify-between p-4 bg-white rounded-lg shadow-sm">
                    <div class="flex items-center space-x-3">
                      <component :is="getSupportIcon(support.type)" class="w-5 h-5 text-[#5f5fcd]" />
                      <span class="font-medium text-gray-900">{{ support.label }}</span>
                    </div>
                    <span v-if="support.status" :class="getStatusClass(support.status)" class="text-sm px-2 py-1 rounded-full">
                      {{ support.status }}
                    </span>
                    <ChevronRightIcon v-else class="w-4 h-4 text-gray-400" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- FAQ Section -->
      <section v-if="contactData?.faqs && contactData.faqs.length > 0" class="py-20 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ contactData?.faq_title || 'প্রায়শই জিজ্ঞাসিত প্রশ্ন' }}</h2>
            <p class="text-lg text-gray-600">{{ contactData?.faq_subtitle || 'আপনার প্রশ্নের উত্তর এখানে পেতে পারেন' }}</p>
          </div>

          <div class="space-y-4">
            <div v-for="(faq, index) in contactData.faqs" :key="faq.id" class="bg-white rounded-lg shadow-sm border border-gray-200">
              <button @click="toggleFaq(index)" class="w-full px-6 py-4 text-left flex items-center justify-between">
                <span class="font-medium text-gray-900">{{ faq.question }}</span>
                <ChevronDownIcon :class="{'rotate-180': openFaq === index}" class="w-5 h-5 text-gray-500 transition-transform" />
              </button>
              <div v-show="openFaq === index" class="px-6 pb-4">
                <p class="text-gray-600">{{ faq.answer }}</p>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </FrontendLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import FrontendLayout from '@/layouts/FrontendLayout.vue'
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue'
import ProgressIndicator from '@/components/Frontend/ProgressIndicator.vue'
import {
  MailIcon,
  PhoneIcon,
  MapPinIcon,
  ClockIcon,
  SendIcon,
  MessageCircleIcon,
  HelpCircleIcon,
  ChevronRightIcon,
  ChevronDownIcon,
  AlertTriangleIcon
} from 'lucide-vue-next'
import { useToast } from '@/composables/useToast'

// Interfaces
interface ContactData {
  title: string
  subtitle: string
  form_title: string
  quick_contact_title: string
  support_title: string
  faq_title: string
  faq_subtitle: string
  subjects: Array<{
    value: string
    label: string
  }>
  contact_info: Array<{
    type: string
    label: string
    value: string
  }>
  support_options: Array<{
    type: string
    label: string
    status?: string
  }>
  faqs: Array<{
    id: number
    question: string
    answer: string
  }>
}

// State
const loading = ref(true)
const error = ref('')
const contactData = ref<ContactData | null>(null)

// Form state
const form = ref({
  name: '',
  email: '',
  phone: '',
  subject: '',
  message: ''
})

const errors = ref<Record<string, string>>({})
const isSubmitting = ref(false)

// FAQ state
const openFaq = ref<number | null>(null)

// Toast system
const toast = useToast()

// Methods
const loadContactData = async () => {
  loading.value = true
  error.value = ''
  
  try {
    await router.get(route('frontend.contact'), {}, {
      preserveState: true,
      preserveScroll: true,
      only: ['contactData']
    })
    
    // Get updated data from page props
    const page = usePage()
    contactData.value = page.props.contactData as ContactData
  } catch (err) {
    error.value = 'তথ্য লোড করতে সমস্যা হয়েছে। দয়া করে আবার চেষ্টা করুন।'
    console.error('Error loading contact data:', err)
  } finally {
    loading.value = false
  }
}

const validateForm = () => {
  errors.value = {}
  
  if (!form.value.name.trim()) {
    errors.value.name = 'নাম প্রয়োজন'
  }
  
  if (!form.value.email.trim()) {
    errors.value.email = 'ইমেইল প্রয়োজন'
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.value.email)) {
    errors.value.email = 'সঠিক ইমেইল ঠিকানা দিন'
  }
  
  if (form.value.phone && !/^(\+880|880|0)?1[3-9]\d{8}$/.test(form.value.phone.replace(/\s/g, ''))) {
    errors.value.phone = 'সঠিক বাংলাদেশী ফোন নম্বর দিন'
  }
  
  if (!form.value.subject) {
    errors.value.subject = 'বিষয় নির্বাচন করুন'
  }
  
  if (!form.value.message.trim()) {
    errors.value.message = 'বার্তা প্রয়োজন'
  } else if (form.value.message.trim().length < 10) {
    errors.value.message = 'বার্তা কমপক্ষে ১০ অক্ষরের হতে হবে'
  }
  
  return Object.keys(errors.value).length === 0
}

const submitForm = async () => {
  if (!validateForm()) {
    toast.warning('অনুগ্রহ করে সব তথ্য সঠিকভাবে পূরণ করুন।')
    return
  }

  isSubmitting.value = true
  
  try {
    await router.post(route('frontend.contact.submit'), form.value, {
      preserveState: true,
      preserveScroll: true,
    })
    
    // Reset form
    form.value = {
      name: '',
      email: '',
      phone: '',
      subject: '',
      message: ''
    }
    
    toast.success({
      title: 'বার্তা সফলভাবে পাঠানো হয়েছে!',
      message: 'আমরা শীঘ্রই আপনার সাথে যোগাযোগ করবো।',
      duration: 5000
    })
  } catch (error: any) {
    // Handle validation errors
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors
      toast.error({
        title: 'ফর্মে ত্রুটি রয়েছে',
        message: 'অনুগ্রহ করে সব তথ্য সঠিকভাবে পূরণ করুন।',
        duration: 7000
      })
    } else {
      toast.error({
        title: 'বার্তা পাঠানো ব্যর্থ!',
        message: 'দুঃখিত, একটি সমস্যা হয়েছে। পুনরায় চেষ্টা করুন।',
        duration: 7000
      })
    }
  } finally {
    isSubmitting.value = false
  }
}

const toggleFaq = (index: number) => {
  openFaq.value = openFaq.value === index ? null : index
}

const getContactIcon = (type: string) => {
  const icons: Record<string, any> = {
    'phone': PhoneIcon,
    'email': MailIcon,
    'address': MapPinIcon,
    'clock': ClockIcon
  }
  return icons[type] || MailIcon
}

const getSupportIcon = (type: string) => {
  const icons: Record<string, any> = {
    'chat': MessageCircleIcon,
    'phone': PhoneIcon,
    'help': HelpCircleIcon
  }
  return icons[type] || HelpCircleIcon
}

const getStatusClass = (status: string) => {
  const classes: Record<string, string> = {
    'অনলাইন': 'bg-green-100 text-green-800',
    '২৪/৭': 'bg-blue-100 text-blue-800',
    'অফলাইন': 'bg-gray-100 text-gray-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

// Initialize on mount
onMounted(() => {
  loadContactData()
})
</script>

<style scoped>
/* Custom styles for the contact page */
.text-gradient-islamic {
  background-size: 200% 200%;
  animation: gradientShift 4s ease-in-out infinite;
}

@keyframes gradientShift {
  0%, 100% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
}

/* Smooth transitions for FAQ */
.transition-transform {
  transition: transform 0.2s ease-in-out;
}

.rotate-180 {
  transform: rotate(180deg);
}
</style> 
