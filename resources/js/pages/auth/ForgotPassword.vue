<template>
    <FrontendLayout title="পাসওয়ার্ড রিসেট - ইকরা অনলাইন একাডেমি">
        <Head title="পাসওয়ার্ড রিসেট" />

        <!-- Forgot Password Section -->
        <section class="py-20 min-h-screen flex items-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <!-- Left Side - Information -->
                    <div class="space-y-8">
                        <div>
                            <div class="inline-flex items-center px-4 py-2 rounded-full bg-orange-600/10 border border-orange-600/20 mb-6">
                                <KeyIcon class="w-4 h-4 text-orange-600 mr-2" />
                                <span class="text-orange-600 text-sm font-medium">পাসওয়ার্ড রিসেট</span>
                            </div>
                            
                            <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                                <span class="text-gradient-islamic bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] bg-clip-text text-transparent">পাসওয়ার্ড</span> রিসেট করুন
                            </h1>
                            
                            <p class="text-xl text-gray-600 leading-relaxed mb-8">
                                চিন্তা করবেন না! আপনার ইমেইল ঠিকানা দিন, আমরা আপনাকে পাসওয়ার্ড রিসেট করার লিংক পাঠিয়ে দিব।
                            </p>
                        </div>

                        <!-- Help Information -->
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <MailIcon class="w-4 h-4 text-orange-600" />
                                </div>
                                <span class="text-gray-700">ইমেইলে পাসওয়ার্ড রিসেট লিংক পাবেন</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <ClockIcon class="w-4 h-4 text-orange-600" />
                                </div>
                                <span class="text-gray-700">লিংক ২৪ ঘন্টার জন্য বৈধ থাকবে</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <ShieldIcon class="w-4 h-4 text-orange-600" />
                                </div>
                                <span class="text-gray-700">নিরাপদ এবং সুরক্ষিত প্রক্রিয়া</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <RefreshCwIcon class="w-4 h-4 text-orange-600" />
                                </div>
                                <span class="text-gray-700">নতুন পাসওয়ার্ড সেট করুন</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Forgot Password Form -->
                    <div>
                        <div class="bg-white p-8 rounded-2xl shadow-islamic border border-gray-100">
                            <div class="text-center mb-8">
                                <div class="w-16 h-16 bg-gradient-to-r from-[#2d5a27]/20 to-[#5f5fcd]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <KeyIcon class="w-8 h-8 text-orange-600" />
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">পাসওয়ার্ড ভুলে গেছেন?</h2>
                                <p class="text-gray-600">আপনার ইমেইল ঠিকানা দিন পাসওয়ার্ড রিসেট করতে</p>
                            </div>

                            <div v-if="status" class="mb-6 text-center text-sm font-medium text-green-600 bg-green-50 p-3 rounded-lg">
                                {{ status }}
                            </div>

                            <form @submit.prevent="submit" class="space-y-6">
                                <!-- Email -->
                                <div class="space-y-2">
                                    <Label for="email">ইমেইল ঠিকানা</Label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <MailIcon class="h-5 w-5 text-gray-400" />
                                        </div>
                                        <Input
                                            id="email"
                                            type="email"
                                            v-model="form.email"
                                            class="pl-10"
                                            placeholder="আপনার নিবন্ধিত ইমেইল ঠিকানা"
                                            required
                                            autofocus
                                            autocomplete="username"
                                        />
                                    </div>
                                    <InputError :message="form.errors.email" />
                                </div>

                                <!-- Submit Button -->
                                <Button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="w-full bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] text-white font-medium py-3 px-4 rounded-lg hover:shadow-islamic-lg transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                                >
                                    <LoaderCircle v-if="form.processing" class="w-4 h-4 mr-2 animate-spin" />
                                    {{ form.processing ? 'পাঠানো হচ্ছে...' : 'পাসওয়ার্ড রিসেট লিংক পাঠান' }}
                                </Button>
                            </form>

                            <!-- Back to Login -->
                            <div class="mt-8 text-center">
                                <p class="text-gray-600 mb-4">পাসওয়ার্ড মনে আছে?</p>
                                <TextLink 
                                    :href="route('login')" 
                                    class="inline-flex items-center text-[#5f5fcd] hover:text-[#4a4aa6] font-medium"
                                >
                                    <ArrowLeftIcon class="w-4 h-4 mr-2" />
                                    লগইন পেজে ফিরে যান
                                </TextLink>
                            </div>

                            <!-- Register Link -->
                            <div class="mt-6 text-center">
                                <p class="text-gray-600 mb-4">অথবা</p>
                                <TextLink 
                                    :href="route('register')" 
                                    class="inline-flex items-center text-[#5f5fcd] hover:text-[#4a4aa6] font-medium"
                                >
                                    নতুন অ্যাকাউন্ট তৈরি করুন
                                    <ArrowRightIcon class="w-4 h-4 ml-2" />
                                </TextLink>
                            </div>

                            <!-- Back to Website -->
                            <div class="mt-8 text-center border-t pt-6">
                                <TextLink 
                                    href="/" 
                                    class="inline-flex items-center text-[#5f5fcd] hover:text-[#4a4aa6] font-medium"
                                >
                                    <ArrowLeftIcon class="w-4 h-4 mr-2" />
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
import { LoaderCircle, MailIcon, KeyIcon, ArrowLeftIcon, ArrowRightIcon, ClockIcon, ShieldIcon, RefreshCwIcon } from 'lucide-vue-next'

defineProps({
  status: String,
})

const form = useForm({
  email: '',
})

const submit = () => {
  form.post(route('password.email'))
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