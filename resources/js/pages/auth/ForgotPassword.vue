<template>
    <FrontendLayout title="পাসওয়ার্ড রিসেট - ইকরা অনলাইন একাডেমি">
        <Head title="পাসওয়ার্ড রিসেট" />

        <!-- Forgot Password Section -->
        <section class="py-20 min-h-screen flex items-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <!-- Left Side - Information -->
                    <div class="space-y-8 hidden lg:block">
                        <div>
                            <div class="inline-flex items-center px-4 py-2 rounded-full bg-orange-600/10 border border-orange-600/20 mb-6">
                                <KeyIcon class="w-4 h-4 text-orange-600 mr-2" />
                                <span class="text-orange-600 text-sm font-medium">পাসওয়ার্ড রিসেট</span>
                            </div>
                            
                            <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                                পাসওয়ার্ড রিসেট
                            </h1>
                            
                            <p class="text-xl text-gray-600 leading-relaxed mb-8">
                                আপনার ইমেইল দিন, আমরা একটি পাসওয়ার্ড রিসেট লিঙ্ক পাঠাবো।
                            </p>
                        </div>
                    </div>

                    <!-- Right Side - Forgot Password Form -->
                    <div>
                        <div class="bg-white/95 backdrop-blur-sm p-8 rounded-2xl shadow-islamic-lg border border-gray-100/50 relative overflow-hidden">
                            <!-- Background pattern -->
                            <div class="absolute inset-0 opacity-5">
                                <svg class="w-full h-full" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
                                    <defs>
                                        <pattern id="forgotPattern" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                                            <path d="M20 0 L40 20 L20 40 L0 20 Z" fill="none" stroke="#5f5fcd" stroke-width="1"/>
                                            <circle cx="20" cy="20" r="8" fill="none" stroke="#2d5a27" stroke-width="1"/>
                                        </pattern>
                                    </defs>
                                    <rect width="400" height="400" fill="url(#forgotPattern)" />
                                </svg>
                            </div>
                            
                            <div class="relative z-10">
                                <div class="text-center mb-8">
                                    <div class="w-16 h-16 bg-gradient-to-r from-[#5f5fcd]/20 via-[#2d5a27]/20 to-[#d4a574]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <KeyIcon class="w-8 h-8 text-[#5f5fcd]" />
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
                                        <Label for="email" class="text-gray-700 font-medium">ইমেইল ঠিকানা</Label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors duration-200 group-focus-within:text-[#5f5fcd]">
                                                <MailIcon class="h-5 w-5 text-gray-400" />
                                            </div>
                                            <Input
                                                id="email"
                                                type="email"
                                                v-model="form.email"
                                                class="pl-10 border-gray-200 focus:border-[#5f5fcd] focus:ring-[#5f5fcd]/20 transition-all duration-200 rounded-xl"
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
                                        class="group w-full bg-gradient-to-r from-[#5f5fcd] via-[#2d5a27] to-[#d4a574] text-white font-semibold py-3 px-4 rounded-xl hover:shadow-islamic-lg transition-all duration-300 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none relative overflow-hidden"
                                    >
                                        <!-- Animated background overlay -->
                                        <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/10 to-white/0 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                                        <span class="relative z-10">{{ form.processing ? 'পাঠানো হচ্ছে...' : 'পাসওয়ার্ড রিসেট লিংক পাঠান' }}</span>
                                    </Button>
                                </form>

                                <!-- Back to Login -->
                                <div class="mt-8 text-center">
                                    <span class="text-gray-600">পাসওয়ার্ড মনে আছে? </span>
                                    <TextLink 
                                        :href="route('login')" 
                                        class="inline-flex items-center text-[#5f5fcd] hover:text-[#4a4aa6] font-medium"
                                    >
                                        <ArrowLeftIcon class="w-4 h-4 mr-2" />
                                        লগইন পেজে ফিরে যান
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
import { Head } from '@inertiajs/vue3'
import { MailIcon, KeyIcon, ArrowLeftIcon, ArrowRightIcon, ClockIcon, ShieldIcon, RefreshCwIcon } from 'lucide-vue-next'

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