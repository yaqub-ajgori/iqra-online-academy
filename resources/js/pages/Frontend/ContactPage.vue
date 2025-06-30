<template>
  <FrontendLayout title="যোগাযোগ - ইকরা অনলাইন একাডেমি">
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
            আমাদের সাথে <span class="text-gradient-islamic bg-gradient-to-r from-[#d4a574] to-[#5f5fcd] bg-clip-text text-transparent">যোগাযোগ</span> করুন
          </h1>
          
          <p class="text-xl text-gray-300 max-w-3xl mx-auto leading-relaxed">
            আমরা সবসময় আপনাদের প্রশ্ন ও পরামর্শের জন্য প্রস্তুত। 
            যেকোনো সহায়তার জন্য আমাদের সাথে যোগাযোগ করতে দ্বিধা করবেন না।
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
              <h2 class="text-2xl font-bold text-gray-900 mb-6">আমাদের বার্তা পাঠান</h2>
              
              <form @submit.prevent="submitForm" class="space-y-6">
                <!-- Name -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">আপনার নাম *</label>
                  <input 
                    v-model="form.name"
                    type="text" 
                    required
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent"
                    placeholder="আপনার পূর্ণ নাম লিখুন"
                  />
                </div>

                <!-- Email -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">ইমেইল ঠিকানা *</label>
                  <input 
                    v-model="form.email"
                    type="email" 
                    required
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent"
                    placeholder="your.email@example.com"
                  />
                </div>

                <!-- Phone -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">ফোন নম্বর</label>
                  <input 
                    v-model="form.phone"
                    type="tel" 
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent"
                    placeholder="+৮৮০ ১২৩৪ ৫৬৭৮৯০"
                  />
                </div>

                <!-- Subject -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">বিষয় *</label>
                  <select 
                    v-model="form.subject"
                    required
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent"
                  >
                    <option value="">বিষয় নির্বাচন করুন</option>
                    <option value="course_inquiry">কোর্স সম্পর্কে জানতে চাই</option>
                    <option value="technical_support">প্রযুক্তিগত সহায়তা</option>
                    <option value="payment_issue">পেমেন্ট সমস্যা</option>
                    <option value="course_content">কোর্স কনটেন্ট সম্পর্কে</option>
                    <option value="certificate">সার্টিফিকেট সম্পর্কে</option>
                    <option value="feedback">মতামত ও পরামর্শ</option>
                    <option value="other">অন্যান্য</option>
                  </select>
                </div>

                <!-- Message -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">আপনার বার্তা *</label>
                  <textarea 
                    v-model="form.message"
                    required
                    rows="5"
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent"
                    placeholder="আপনার প্রশ্ন বা মন্তব্য বিস্তারিত লিখুন..."
                  ></textarea>
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
                  বার্তা পাঠান
                </PrimaryButton>
              </form>
            </div>
          </div>

          <!-- Contact Information -->
          <div class="space-y-8">
            <!-- Quick Contact -->
            <div class="bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] p-8 rounded-2xl text-white">
              <h3 class="text-2xl font-bold mb-6">দ্রুত যোগাযোগ</h3>
              
              <div class="space-y-4">
                <div class="flex items-center space-x-3">
                  <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <PhoneIcon class="w-5 h-5" />
                  </div>
                  <div>
                    <p class="font-medium">ফোন</p>
                    <p class="text-white/80">+৮৮০ ১২৩৪ ৫৬৭৮৯০</p>
                  </div>
                </div>

                <div class="flex items-center space-x-3">
                  <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <MailIcon class="w-5 h-5" />
                  </div>
                  <div>
                    <p class="font-medium">ইমেইল</p>
                    <p class="text-white/80">info@iqra-academy.com</p>
                  </div>
                </div>

                <div class="flex items-start space-x-3">
                  <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <MapPinIcon class="w-5 h-5" />
                  </div>
                  <div>
                    <p class="font-medium">ঠিকানা</p>
                    <p class="text-white/80">১২৩৪ ইসলামিক সেন্টার<br/>ঢাকা, বাংলাদেশ</p>
                  </div>
                </div>

                <div class="flex items-center space-x-3">
                  <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <ClockIcon class="w-5 h-5" />
                  </div>
                  <div>
                    <p class="font-medium">অফিস সময়</p>
                    <p class="text-white/80">রবি-বৃহস্পতি: ৯:০০ - ১৮:০০<br/>শুক্রবার: ১৪:০০ - ১৮:০০</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Support Options -->
            <div class="bg-gray-50 p-8 rounded-2xl">
              <h3 class="text-xl font-bold text-gray-900 mb-6">সহায়তার মাধ্যম</h3>
              
              <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow-sm">
                  <div class="flex items-center space-x-3">
                    <MessageCircleIcon class="w-5 h-5 text-[#5f5fcd]" />
                    <span class="font-medium text-gray-900">লাইভ চ্যাট</span>
                  </div>
                  <span class="text-sm bg-green-100 text-green-800 px-2 py-1 rounded-full">অনলাইন</span>
                </div>

                <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow-sm">
                  <div class="flex items-center space-x-3">
                    <PhoneIcon class="w-5 h-5 text-[#5f5fcd]" />
                    <span class="font-medium text-gray-900">ফোন সাপোর্ট</span>
                  </div>
                  <span class="text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded-full">২৪/৭</span>
                </div>

                <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow-sm">
                  <div class="flex items-center space-x-3">
                    <HelpCircleIcon class="w-5 h-5 text-[#5f5fcd]" />
                    <span class="font-medium text-gray-900">FAQ</span>
                  </div>
                  <ChevronRightIcon class="w-4 h-4 text-gray-400" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-gray-50">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-bold text-gray-900 mb-4">প্রায়শই জিজ্ঞাসিত প্রশ্ন</h2>
          <p class="text-lg text-gray-600">আপনার প্রশ্নের উত্তর এখানে পেতে পারেন</p>
        </div>

        <div class="space-y-4">
          <!-- FAQ Item 1 -->
          <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <button @click="toggleFaq(0)" class="w-full px-6 py-4 text-left flex items-center justify-between">
              <span class="font-medium text-gray-900">কোর্সে ভর্তি হতে কি কোনো যোগ্যতার প্রয়োজন?</span>
              <ChevronDownIcon :class="{'rotate-180': openFaq === 0}" class="w-5 h-5 text-gray-500 transition-transform" />
            </button>
            <div v-show="openFaq === 0" class="px-6 pb-4">
              <p class="text-gray-600">না, আমাদের বেশিরভাগ কোর্সে ভর্তির জন্য কোনো বিশেষ যোগ্যতার প্রয়োজন নেই। শুধু ইসলামিক জ্ঞান অর্জনের আগ্রহ থাকলেই যথেষ্ট।</p>
            </div>
          </div>

          <!-- FAQ Item 2 -->
          <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <button @click="toggleFaq(1)" class="w-full px-6 py-4 text-left flex items-center justify-between">
              <span class="font-medium text-gray-900">কোর্স সম্পন্ন করার পর কি সার্টিফিকেট পাবো?</span>
              <ChevronDownIcon :class="{'rotate-180': openFaq === 1}" class="w-5 h-5 text-gray-500 transition-transform" />
            </button>
            <div v-show="openFaq === 1" class="px-6 pb-4">
              <p class="text-gray-600">হ্যাঁ, প্রতিটি কোর্স সফলভাবে সম্পন্ন করলে আপনি একটি ডিজিটাল সার্টিফিকেট পাবেন যা আন্তর্জাতিকভাবে স্বীকৃত।</p>
            </div>
          </div>

          <!-- FAQ Item 3 -->
          <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <button @click="toggleFaq(2)" class="w-full px-6 py-4 text-left flex items-center justify-between">
              <span class="font-medium text-gray-900">পেমেন্ট কিভাবে করবো?</span>
              <ChevronDownIcon :class="{'rotate-180': openFaq === 2}" class="w-5 h-5 text-gray-500 transition-transform" />
            </button>
            <div v-show="openFaq === 2" class="px-6 pb-4">
              <p class="text-gray-600">আমরা বিকাশ, নগদ, রকেট, ব্যাংক ট্রান্সফার এবং কার্ড পেমেন্ট গ্রহণ করি। সবগুলো পেমেন্ট সম্পূর্ণ নিরাপদ ও এনক্রিপ্টেড।</p>
            </div>
          </div>

          <!-- FAQ Item 4 -->
          <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <button @click="toggleFaq(3)" class="w-full px-6 py-4 text-left flex items-center justify-between">
              <span class="font-medium text-gray-900">কোর্স কি লাইভ নাকি রেকর্ডেড?</span>
              <ChevronDownIcon :class="{'rotate-180': openFaq === 3}" class="w-5 h-5 text-gray-500 transition-transform" />
            </button>
            <div v-show="openFaq === 3" class="px-6 pb-4">
              <p class="text-gray-600">আমাদের কোর্সগুলো মূলত রেকর্ডেড ভিডিও। তবে নির্দিষ্ট সময়ে লাইভ Q&A সেশন এবং ডিসকাশন ক্লাসের ব্যবস্থা রয়েছে।</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </FrontendLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import FrontendLayout from '@/layouts/FrontendLayout.vue'
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue'
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
  FacebookIcon,
  YoutubeIcon,
  TwitterIcon,
  InstagramIcon
} from 'lucide-vue-next'

// Form state
const form = ref({
  name: '',
  email: '',
  phone: '',
  subject: '',
  message: ''
})

const isSubmitting = ref(false)

// FAQ state
const openFaq = ref<number | null>(null)

// Methods
const submitForm = async () => {
  isSubmitting.value = true
  
  try {
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    // Reset form
    form.value = {
      name: '',
      email: '',
      phone: '',
      subject: '',
      message: ''
    }
    
    alert('আপনার বার্তা সফলভাবে পাঠানো হয়েছে। আমরা শীঘ্রই আপনার সাথে যোগাযোগ করবো।')
  } catch (error) {
    alert('দুঃখিত, একটি সমস্যা হয়েছে। পুনরায় চেষ্টা করুন।')
  } finally {
    isSubmitting.value = false
  }
}

const toggleFaq = (index: number) => {
  openFaq.value = openFaq.value === index ? null : index
}
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
