<template>
    <FrontendLayout title="অ্যাডমিন পাসওয়ার্ড রিসেট - ইকরা অনলাইন একাডেমি">
        <Head title="অ্যাডমিন পাসওয়ার্ড রিসেট" />

        <!-- Admin Reset Password Section -->
        <section class="py-20 min-h-screen flex items-center">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <!-- Left Side - Information -->
                    <div class="space-y-8">
                        <div>
                            <div class="inline-flex items-center px-4 py-2 rounded-full bg-green-600/10 border border-green-600/20 mb-6">
                                <KeyIcon class="w-4 h-4 text-green-600 mr-2" />
                                <span class="text-green-600 text-sm font-medium">নতুন পাসওয়ার্ড সেট করুন</span>
                            </div>
                            
                            <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                                <span class="text-gradient-islamic bg-gradient-to-r from-green-600 to-red-600 bg-clip-text text-transparent">নিরাপদ</span> অ্যাডমিন অ্যাকাউন্ট
                            </h1>
                            
                            <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                                আপনার অ্যাডমিন অ্যাকাউন্টের জন্য একটি শক্তিশালী এবং নিরাপদ পাসওয়ার্ড তৈরি করুন। 
                                এই পাসওয়ার্ড দিয়ে আপনি ইকরা অনলাইন একাডেমির সম্পূর্ণ নিয়ন্ত্রণ পাবেন।
                            </p>
                        </div>

                        <!-- Security Guidelines -->
                        <div class="space-y-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">নিরাপদ পাসওয়ার্ডের নির্দেশনা:</h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-start space-x-4">
                                    <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center font-semibold text-sm">
                                        ✓
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">কমপক্ষে ৮ অক্ষর ব্যবহার করুন</h4>
                                        <p class="text-gray-600 text-sm">বড় হাতের অক্ষর, ছোট হাতের অক্ষর ও সংখ্যা মিশ্রিত করুন</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-4">
                                    <div class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center font-semibold text-sm">
                                        !
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">বিশেষ চিহ্ন যোগ করুন</h4>
                                        <p class="text-gray-600 text-sm">@, #, $, % ইত্যাদি চিহ্ন ব্যবহার করে আরও নিরাপদ করুন</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-4">
                                    <div class="w-8 h-8 bg-[#5f5fcd] text-white rounded-full flex items-center justify-center font-semibold text-sm">
                                        🔐
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">অনন্য পাসওয়ার্ড ব্যবহার করুন</h4>
                                        <p class="text-gray-600 text-sm">অন্য কোন অ্যাকাউন্টে ব্যবহৃত পাসওয়ার্ড ব্যবহার করবেন না</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Security Warning -->
                        <div class="bg-gradient-to-r from-amber-50 to-red-50 border border-amber-200 p-6 rounded-2xl">
                            <h3 class="font-semibold text-amber-800 mb-2 flex items-center">
                                <ShieldAlertIcon class="w-5 h-5 mr-2" />
                                গুরুত্বপূর্ণ নিরাপত্তা সতর্কতা
                            </h3>
                            <p class="text-amber-700 text-sm mb-3">
                                এই পাসওয়ার্ড অত্যন্ত গোপনীয় রাখুন। কখনও অন্য কারও সাথে শেয়ার করবেন না এবং নিরাপদ স্থানে সংরক্ষণ করুন।
                            </p>
                            <div class="text-xs text-amber-600">
                                আপনার অ্যাকাউন্টের সকল কার্যক্রম লগ করা হয় এবং নিরাপত্তার জন্য পর্যবেক্ষণ করা হয়।
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Reset Form -->
                    <div>
                        <div class="bg-white p-8 rounded-2xl shadow-islamic border border-gray-100">
                            <div class="text-center mb-8">
                                <div class="w-16 h-16 bg-gradient-to-r from-green-600/20 to-red-600/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <LockIcon class="w-8 h-8 text-green-600" />
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">নতুন অ্যাডমিন পাসওয়ার্ড</h2>
                                <p class="text-gray-600">একটি শক্তিশালী পাসওয়ার্ড তৈরি করুন</p>
                            </div>

                            <form @submit.prevent="submit" class="space-y-6">
                                <!-- Email Field (Read Only) -->
                                <div>
                                    <Label for="email" class="text-gray-700 font-medium">অ্যাডমিন ইমেইল</Label>
                                    <div class="relative mt-2">
                                        <MailIcon class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                                        <Input 
                                            id="email" 
                                            type="email" 
                                            name="email" 
                                            autocomplete="email" 
                                            v-model="form.email" 
                                            readonly
                                            class="pl-10 py-3 bg-gray-50 border-gray-200 rounded-lg cursor-not-allowed"
                                        />
                                    </div>
                                    <InputError :message="form.errors.email" />
                                </div>

                                <!-- Password Field -->
                                <div>
                                    <Label for="password" class="text-gray-700 font-medium">নতুন পাসওয়ার্ড</Label>
                                    <div class="relative mt-2">
                                        <LockIcon class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                                        <Input 
                                            id="password" 
                                            type="password" 
                                            name="password" 
                                            autocomplete="new-password" 
                                            v-model="form.password" 
                                            autofocus
                                            placeholder="কমপক্ষে ৮ অক্ষরের শক্তিশালী পাসওয়ার্ড"
                                            class="pl-10 py-3 border-gray-200 focus:ring-2 focus:ring-green-600 focus:border-transparent rounded-lg"
                                        />
                                    </div>
                                    <InputError :message="form.errors.password" />
                                </div>

                                <!-- Confirm Password Field -->
                                <div>
                                    <Label for="password_confirmation" class="text-gray-700 font-medium">পাসওয়ার্ড নিশ্চিত করুন</Label>
                                    <div class="relative mt-2">
                                        <KeyIcon class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                                        <Input 
                                            id="password_confirmation" 
                                            type="password" 
                                            name="password_confirmation" 
                                            autocomplete="new-password" 
                                            v-model="form.password_confirmation" 
                                            placeholder="উপরের পাসওয়ার্ডটি আবার লিখুন"
                                            class="pl-10 py-3 border-gray-200 focus:ring-2 focus:ring-green-600 focus:border-transparent rounded-lg"
                                        />
                                    </div>
                                    <InputError :message="form.errors.password_confirmation" />
                                </div>

                                <!-- Submit Button -->
                                <Button 
                                    type="submit"
                                    class="w-full bg-gradient-to-r from-green-600 to-red-600 hover:from-green-700 hover:to-red-700 text-white py-3 rounded-lg font-medium shadow-islamic hover:shadow-islamic-lg transition-all duration-200 transform hover:scale-[1.02]" 
                                    :disabled="form.processing"
                                >
                                    <LoaderCircle v-if="form.processing" class="h-5 w-5 animate-spin mr-2" />
                                    <LockIcon v-else class="h-5 w-5 mr-2" />
                                    অ্যাডমিন পাসওয়ার্ড আপডেট করুন
                                </Button>
                            </form>

                            <!-- Success Message -->
                            <div class="mt-6 text-center">
                                <p class="text-xs text-gray-500">
                                    পাসওয়ার্ড পরিবর্তনের পর আপনাকে আবার লগইন করতে হবে
                                </p>
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
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Head } from '@inertiajs/vue3'
import { LoaderCircle, MailIcon, LockIcon, KeyIcon, ShieldAlertIcon } from 'lucide-vue-next'

const props = defineProps({
  token: String,
  email: String,
})

const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
})

const submit = () => {
  form.post(route('admin.password.update'), {
    onFinish: () => {
      form.reset('password', 'password_confirmation')
    },
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

/* Hover animations */
.transform:hover {
  transform: scale(1.02);
}
</style> 