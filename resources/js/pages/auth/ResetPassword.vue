<template>
    <FrontendLayout title="নতুন পাসওয়ার্ড সেট করুন - ইকরা অনলাইন একাডেমি">
        <Head title="নতুন পাসওয়ার্ড সেট করুন" />

        <!-- Reset Password Section -->
        <section class="py-20 min-h-screen flex items-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <!-- Left Side - Information -->
                    <div class="space-y-8">
                        <div>
                            <div class="inline-flex items-center px-4 py-2 rounded-full bg-purple-600/10 border border-purple-600/20 mb-6">
                                <RefreshCw class="w-4 h-4 text-purple-600 mr-2" />
                                <span class="text-purple-600 text-sm font-medium">নতুন পাসওয়ার্ড</span>
                            </div>
                            
                            <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                                <span class="text-gradient-islamic bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] bg-clip-text text-transparent">নতুন পাসওয়ার্ড</span> সেট করুন
                            </h1>
                            
                            <p class="text-xl text-gray-600 leading-relaxed mb-8">
                                আপনার অ্যাকাউন্টের জন্য একটি শক্তিশালী এবং নিরাপদ পাসওয়ার্ড তৈরি করুন।
                            </p>
                        </div>

                        <!-- Password Requirements -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900">পাসওয়ার্ডের প্রয়োজনীয়তা:</h3>
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <Check class="w-4 h-4 text-purple-600" />
                                </div>
                                <span class="text-gray-700">কমপক্ষে ৮ অক্ষর হতে হবে</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <Check class="w-4 h-4 text-purple-600" />
                                </div>
                                <span class="text-gray-700">বড় এবং ছোট হাতের অক্ষর থাকতে হবে</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <Check class="w-4 h-4 text-purple-600" />
                                </div>
                                <span class="text-gray-700">অন্তত একটি সংখ্যা থাকতে হবে</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <Check class="w-4 h-4 text-purple-600" />
                                </div>
                                <span class="text-gray-700">বিশেষ চিহ্ন (@, #, $, % ইত্যাদি) থাকতে হবে</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Reset Password Form -->
                    <div>
                        <div class="bg-white p-8 rounded-2xl shadow-islamic border border-gray-100">
                            <div class="text-center mb-8">
                                <div class="w-16 h-16 bg-gradient-to-r from-[#2d5a27]/20 to-[#5f5fcd]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <RefreshCw class="w-8 h-8 text-purple-600" />
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">নতুন পাসওয়ার্ড তৈরি করুন</h2>
                                <p class="text-gray-600">আপনার অ্যাকাউন্টের জন্য একটি নিরাপদ পাসওয়ার্ড সেট করুন</p>
                            </div>

                            <form @submit.prevent="submit" class="space-y-6">
                                <!-- Email (readonly) -->
                                <div class="space-y-2">
                                    <Label for="email">ইমেইল ঠিকানা</Label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <Mail class="h-5 w-5 text-gray-400" />
                                        </div>
                                        <Input
                                            id="email"
                                            type="email"
                                            v-model="form.email"
                                            class="pl-10 bg-gray-50"
                                            readonly
                                            autocomplete="username"
                                        />
                                    </div>
                                    <InputError :message="form.errors.email" />
                                </div>

                                <!-- Password -->
                                <div class="space-y-2">
                                    <Label for="password">নতুন পাসওয়ার্ড</Label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <Lock class="h-5 w-5 text-gray-400" />
                                        </div>
                                        <Input
                                            id="password"
                                            type="password"
                                            v-model="form.password"
                                            class="pl-10"
                                            placeholder="নতুন পাসওয়ার্ড লিখুন"
                                            required
                                            autofocus
                                            autocomplete="new-password"
                                        />
                                    </div>
                                    <InputError :message="form.errors.password" />
                                </div>

                                <!-- Confirm Password -->
                                <div class="space-y-2">
                                    <Label for="password_confirmation">পাসওয়ার্ড নিশ্চিত করুন</Label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <Lock class="h-5 w-5 text-gray-400" />
                                        </div>
                                        <Input
                                            id="password_confirmation"
                                            type="password"
                                            v-model="form.password_confirmation"
                                            class="pl-10"
                                            placeholder="পাসওয়ার্ড পুনরায় লিখুন"
                                            required
                                            autocomplete="new-password"
                                        />
                                    </div>
                                    <InputError :message="form.errors.password_confirmation" />
                                </div>

                                <!-- Submit Button -->
                                <Button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="w-full bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] text-white font-medium py-3 px-4 rounded-lg hover:shadow-islamic-lg transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                                >
                                    <LoaderCircle v-if="form.processing" class="w-4 h-4 mr-2 animate-spin" />
                                    {{ form.processing ? 'আপডেট হচ্ছে...' : 'পাসওয়ার্ড আপডেট করুন' }}
                                </Button>
                            </form>

                            <!-- Back to Login -->
                            <div class="mt-8 text-center">
                                <p class="text-gray-600 mb-4">পাসওয়ার্ড সেট করার পর</p>
                                <TextLink 
                                    :href="route('login')" 
                                    class="inline-flex items-center text-[#5f5fcd] hover:text-[#4a4aa6] font-medium"
                                >
                                    লগইন পেজে যান
                                    <ArrowRight class="w-4 h-4 ml-2" />
                                </TextLink>
                            </div>

                            <!-- Back to Website -->
                            <div class="mt-8 text-center border-t pt-6">
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
import { Head } from '@inertiajs/vue3'
import { LoaderCircle, Mail, Lock, RefreshCw, ArrowLeft, ArrowRight, Check } from 'lucide-vue-next'

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
  form.post(route('password.store'), {
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