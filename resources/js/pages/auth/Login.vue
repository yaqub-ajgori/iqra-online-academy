<template>
    <FrontendLayout title="ছাত্র লগইন - ইকরা অনলাইন একাডেমি">
        <Head title="ছাত্র লগইন" />

        <!-- Student Login Section -->
        <section class="py-20 min-h-screen flex items-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <!-- Left Side - Information -->
                    <div class="space-y-8">
                        <div>
                            <div class="inline-flex items-center px-4 py-2 rounded-full bg-green-600/10 border border-green-600/20 mb-6">
                                <User class="w-4 h-4 text-green-600 mr-2" />
                                <span class="text-green-600 text-sm font-medium">ছাত্র প্যানেল</span>
                            </div>
                            
                            <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                                <span class="text-gradient-islamic bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] bg-clip-text text-transparent">ইকরা একাডেমিতে</span> স্বাগতম
                            </h1>
                            
                            <p class="text-xl text-gray-600 leading-relaxed mb-8">
                                আপনার ইসলামিক শিক্ষার যাত্রা শুরু করুন। কুরআন, হাদিস, ফিকহ, এবং আরো অনেক বিষয়ে বিশেষজ্ঞ শিক্ষকদের কাছ থেকে শিখুন।
                            </p>
                        </div>

                        <!-- Features List -->
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <BookOpen class="w-4 h-4 text-green-600" />
                                </div>
                                <span class="text-gray-700">২৪/৭ অনলাইন কোর্স অ্যাক্সেস</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <Users class="w-4 h-4 text-green-600" />
                                </div>
                                <span class="text-gray-700">অভিজ্ঞ ইসলামিক স্কলারদের পাঠদান</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <Award class="w-4 h-4 text-green-600" />
                                </div>
                                <span class="text-gray-700">সার্টিফিকেট এবং কোর্স সমাপনী প্রমাণপত্র</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <Tablet class="w-4 h-4 text-green-600" />
                                </div>
                                <span class="text-gray-700">মোবাইল এবং ডেস্কটপে সহজ ব্যবহার</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Student Login Form -->
                    <div>
                        <div class="bg-white p-8 rounded-2xl shadow-islamic border border-gray-100">
                            <div class="text-center mb-8">
                                <div class="w-16 h-16 bg-gradient-to-r from-[#2d5a27]/20 to-[#5f5fcd]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <User class="w-8 h-8 text-green-600" />
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">আপনার অ্যাকাউন্টে প্রবেশ করুন</h2>
                                <p class="text-gray-600">ইমেইল এবং পাসওয়ার্ড দিয়ে লগইন করুন</p>
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
                                            <Mail class="h-5 w-5 text-gray-400" />
                                        </div>
                                        <Input
                                            id="email"
                                            type="email"
                                            v-model="form.email"
                                            class="pl-10"
                                            placeholder="আপনার ইমেইল ঠিকানা"
                                            required
                                            autofocus
                                            autocomplete="username"
                                        />
                                    </div>
                                    <InputError :message="form.errors.email" />
                                </div>

                                <!-- Password -->
                                <div class="space-y-2">
                                    <Label for="password">পাসওয়ার্ড</Label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <Lock class="h-5 w-5 text-gray-400" />
                                        </div>
                                        <Input
                                            id="password"
                                            type="password"
                                            v-model="form.password"
                                            class="pl-10"
                                            placeholder="আপনার পাসওয়ার্ড"
                                            required
                                            autocomplete="current-password"
                                        />
                                    </div>
                                    <InputError :message="form.errors.password" />
                                </div>

                                <!-- Remember Me & Forgot Password -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <Checkbox 
                                            id="remember" 
                                            v-model="form.remember"
                                        />
                                        <Label for="remember" class="text-sm text-gray-600">
                                            আমাকে মনে রাখুন
                                        </Label>
                                    </div>
                                    <TextLink 
                                        v-if="canResetPassword"
                                        :href="route('password.request')" 
                                        class="text-sm text-[#5f5fcd] hover:text-[#4a4aa6]"
                                    >
                                        পাসওয়ার্ড ভুলে গেছেন?
                                    </TextLink>
                                </div>

                                <!-- Login Button -->
                                <Button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="w-full bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] text-white font-medium py-3 px-4 rounded-lg hover:shadow-islamic-lg transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                                >
                                    <LoaderCircle v-if="form.processing" class="w-4 h-4 mr-2 animate-spin" />
                                    {{ form.processing ? 'লগইন হচ্ছে...' : 'লগইন করুন' }}
                                </Button>
                            </form>

                            <!-- Register Link -->
                            <div class="mt-8 text-center">
                                <p class="text-gray-600 mb-4">এখনো অ্যাকাউন্ট নেই?</p>
                                <TextLink 
                                    :href="route('register')" 
                                    class="inline-flex items-center text-[#5f5fcd] hover:text-[#4a4aa6] font-medium"
                                >
                                    এখনই রেজিস্ট্রেশন করুন
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
import { LoaderCircle, Mail, Lock, User, ArrowLeft, ArrowRight, BookOpen, Users, Tablet, Award } from 'lucide-vue-next'

defineProps({
  canResetPassword: Boolean,
  status: String,
})

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
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