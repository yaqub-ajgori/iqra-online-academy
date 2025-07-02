<template>
    <FrontendLayout title="নতুন ছাত্র রেজিস্ট্রেশন - ইকরা অনলাইন একাডেমি">
        <Head title="নতুন ছাত্র রেজিস্ট্রেশন" />

        <!-- Student Registration Section -->
        <section class="py-20 min-h-screen flex items-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <!-- Left Side - Information -->
                    <div class="space-y-8">
                        <div>
                            <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-600/10 border border-blue-600/20 mb-6">
                                <UserPlus class="w-4 h-4 text-blue-600 mr-2" />
                                <span class="text-blue-600 text-sm font-medium">নতুন ছাত্র রেজিস্ট্রেশন</span>
                            </div>
                            
                            <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                                <span class="text-gradient-islamic bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] bg-clip-text text-transparent">ইকরা একাডেমিতে</span> যোগ দিন
                            </h1>
                            
                            <p class="text-xl text-gray-600 leading-relaxed mb-8">
                                আজই শুরু করুন আপনার ইসলামিক শিক্ষার যাত্রা। সম্পূর্ণ বিনামূল্যে রেজিস্ট্রেশন করে অ্যাক্সেস পান হাজারো ইসলামিক কোর্সে।
                            </p>
                        </div>

                        <!-- Benefits List -->
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <BookOpen class="w-4 h-4 text-blue-600" />
                                </div>
                                <span class="text-gray-700">১০০+ ইসলামিক কোর্সে বিনামূল্যে অ্যাক্সেস</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <Clock class="w-4 h-4 text-blue-600" />
                                </div>
                                <span class="text-gray-700">নিজের সুবিধামতো সময়ে পড়াশোনা</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <Users class="w-4 h-4 text-blue-600" />
                                </div>
                                <span class="text-gray-700">অন্যান্য ছাত্রদের সাথে আলোচনার সুযোগ</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <Award class="w-4 h-4 text-blue-600" />
                                </div>
                                <span class="text-gray-700">কোর্স সমাপনে সার্টিফিকেট</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Registration Form -->
                    <div>
                        <div class="bg-white/95 backdrop-blur-sm p-8 rounded-2xl shadow-islamic-lg border border-gray-100/50 relative overflow-hidden">
                            <!-- Background pattern -->
                            <div class="absolute inset-0 opacity-5">
                                <svg class="w-full h-full" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
                                    <defs>
                                        <pattern id="registerPattern" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                                            <path d="M20 0 L40 20 L20 40 L0 20 Z" fill="none" stroke="#5f5fcd" stroke-width="1"/>
                                            <circle cx="20" cy="20" r="8" fill="none" stroke="#2d5a27" stroke-width="1"/>
                                        </pattern>
                                    </defs>
                                    <rect width="400" height="400" fill="url(#registerPattern)" />
                                </svg>
                            </div>
                            
                            <div class="relative z-10">
                                <div class="text-center mb-8">
                                    <div class="w-16 h-16 bg-gradient-to-r from-[#5f5fcd]/20 via-[#2d5a27]/20 to-[#d4a574]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <UserPlus class="w-8 h-8 text-[#5f5fcd]" />
                                    </div>
                                    <h2 class="text-2xl font-bold text-gray-900 mb-2">নতুন অ্যাকাউন্ট তৈরি করুন</h2>
                                    <p class="text-gray-600">আপনার তথ্য দিয়ে রেজিস্ট্রেশন সম্পন্ন করুন</p>
                                </div>

                                <form @submit.prevent="submit" class="space-y-6">
                                    <!-- Name -->
                                    <div class="space-y-2">
                                        <Label for="name" class="text-gray-700 font-medium">পূর্ণ নাম</Label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors duration-200 group-focus-within:text-[#5f5fcd]">
                                                <User class="h-5 w-5 text-gray-400" />
                                            </div>
                                            <Input
                                                id="name"
                                                type="text"
                                                v-model="form.name"
                                                class="pl-10 border-gray-200 focus:border-[#5f5fcd] focus:ring-[#5f5fcd]/20 transition-all duration-200 rounded-xl"
                                                placeholder="আপনার পূর্ণ নাম"
                                                required
                                                autofocus
                                                autocomplete="name"
                                            />
                                        </div>
                                        <InputError :message="form.errors.name" />
                                    </div>

                                    <!-- Email -->
                                    <div class="space-y-2">
                                        <Label for="email" class="text-gray-700 font-medium">ইমেইল ঠিকানা</Label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors duration-200 group-focus-within:text-[#5f5fcd]">
                                                <Mail class="h-5 w-5 text-gray-400" />
                                            </div>
                                            <Input
                                                id="email"
                                                type="email"
                                                v-model="form.email"
                                                class="pl-10 border-gray-200 focus:border-[#5f5fcd] focus:ring-[#5f5fcd]/20 transition-all duration-200 rounded-xl"
                                                placeholder="আপনার ইমেইল ঠিকানা"
                                                required
                                                autocomplete="username"
                                            />
                                        </div>
                                        <InputError :message="form.errors.email" />
                                    </div>

                                    <!-- Password -->
                                    <div class="space-y-2">
                                        <Label for="password" class="text-gray-700 font-medium">পাসওয়ার্ড</Label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors duration-200 group-focus-within:text-[#5f5fcd]">
                                                <Lock class="h-5 w-5 text-gray-400" />
                                            </div>
                                            <Input
                                                id="password"
                                                type="password"
                                                v-model="form.password"
                                                class="pl-10 border-gray-200 focus:border-[#5f5fcd] focus:ring-[#5f5fcd]/20 transition-all duration-200 rounded-xl"
                                                placeholder="কমপক্ষে ৮ অক্ষরের পাসওয়ার্ড"
                                                required
                                                autocomplete="new-password"
                                            />
                                        </div>
                                        <InputError :message="form.errors.password" />
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="space-y-2">
                                        <Label for="password_confirmation" class="text-gray-700 font-medium">পাসওয়ার্ড নিশ্চিত করুন</Label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors duration-200 group-focus-within:text-[#5f5fcd]">
                                                <Lock class="h-5 w-5 text-gray-400" />
                                            </div>
                                            <Input
                                                id="password_confirmation"
                                                type="password"
                                                v-model="form.password_confirmation"
                                                class="pl-10 border-gray-200 focus:border-[#5f5fcd] focus:ring-[#5f5fcd]/20 transition-all duration-200 rounded-xl"
                                                placeholder="পাসওয়ার্ড পুনরায় লিখুন"
                                                required
                                                autocomplete="new-password"
                                            />
                                        </div>
                                        <InputError :message="form.errors.password_confirmation" />
                                    </div>

                                    <!-- Terms and Conditions -->
                                    <div class="flex items-start space-x-2">
                                        <Checkbox 
                                            id="terms" 
                                            v-model="form.terms"
                                            class="mt-1"
                                            required
                                        />
                                        <Label for="terms" class="text-sm text-gray-600 leading-relaxed">
                                            আমি ইকরা অনলাইন একাডেমির 
                                            <TextLink href="#" class="text-[#5f5fcd] hover:text-[#4a4aa6]">শর্তাবলী</TextLink>
                                            এবং 
                                            <TextLink href="#" class="text-[#5f5fcd] hover:text-[#4a4aa6]">গোপনীয়তা নীতি</TextLink>
                                            তে সম্মত আছি।
                                        </Label>
                                    </div>

                                    <!-- Register Button -->
                                    <Button
                                        type="submit"
                                        :disabled="form.processing"
                                        class="group w-full bg-gradient-to-r from-[#5f5fcd] via-[#2d5a27] to-[#d4a574] text-white font-semibold py-3 px-4 rounded-xl hover:shadow-islamic-lg transition-all duration-300 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none relative overflow-hidden"
                                    >
                                        <!-- Animated background overlay -->
                                        <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/10 to-white/0 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                                        <span class="relative z-10">{{ form.processing ? 'রেজিস্ট্রেশন হচ্ছে...' : 'রেজিস্ট্রেশন করুন' }}</span>
                                    </Button>
                                </form>

                                <!-- Login Link -->
                                <div class="mt-8 text-center">
                                    <p class="text-gray-600 mb-4">ইতিমধ্যে অ্যাকাউন্ট আছে?</p>
                                    <TextLink 
                                        :href="route('login')" 
                                        class="inline-flex items-center text-[#5f5fcd] hover:text-[#4a4aa6] font-medium"
                                    >
                                        লগইন করুন
                                        <ArrowRight class="w-4 h-4 ml-2" />
                                    </TextLink>
                                </div>

                                <!-- Back to Website -->
                                <div class="mt-8 text-center">
                                    <p class="text-gray-600 mb-4">অথবা</p>
                                    <TextLink 
                                        href="/" 
                                        class="inline-flex items-center text-[#5f5fcd] hover:text-[#4a4aa6] font-medium"
                                    >
                                        <ArrowLeft class="w-4 h-4 mr-2" />
                                        মূল ওয়েবসাইটে ফিরে যান
                                    </TextLink>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </FrontendLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import FrontendLayout from '@/layouts/FrontendLayout.vue'
import InputError from '@/components/InputError.vue'
import TextLink from '@/components/TextLink.vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Checkbox } from '@/components/ui/checkbox'
import { Head } from '@inertiajs/vue3'
import { Mail, Lock, User, UserPlus, ArrowLeft, ArrowRight, BookOpen, Clock, Users, Award } from 'lucide-vue-next'
import { useToast } from '@/composables/useToast'

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms: false,
})

const { error: showError } = useToast()

const submit = () => {
  form.post(route('register'), {
    onError: (errors) => {
      // Rate limit error (from backend or HTTP 429)
      if (form.errors.email && form.errors.email.includes('seconds')) {
        showError('Too many registration attempts. Please try again in a minute.')
      } else if (form.errors.email || form.errors.password) {
        showError('Registration failed. Please check your input.')
      } else {
        showError('An unexpected error occurred. Please try again.')
      }
      form.reset('password', 'password_confirmation')
    },
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>

<style scoped>
/* Text gradient animation */
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
</style> 