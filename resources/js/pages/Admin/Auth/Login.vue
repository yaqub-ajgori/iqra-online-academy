<template>
    <FrontendLayout title="অ্যাডমিন লগইন - ইকরা অনলাইন একাডেমি">
        <Head title="অ্যাডমিন লগইন" />

        <!-- Admin Login Section -->
        <section class="py-20 min-h-screen flex items-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <!-- Left Side - Information -->
                    <div class="space-y-8">
                        <div>
                            <div class="inline-flex items-center px-4 py-2 rounded-full bg-red-600/10 border border-red-600/20 mb-6">
                                <ShieldCheckIcon class="w-4 h-4 text-red-600 mr-2" />
                                <span class="text-red-600 text-sm font-medium">অ্যাডমিন প্যানেল</span>
                            </div>
                            
                            <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                                <span class="text-gradient-islamic bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] bg-clip-text text-transparent">ইকরা একাডেমি</span> পরিচালনা করুন
                            </h1>
                            
                            <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                                অ্যাডমিন প্যানেলে প্রবেশ করে ইকরা অনলাইন একাডেমির সকল কার্যক্রম পরিচালনা করুন। 
                                শিক্ষার্থী, কোর্স ও পেমেন্ট ব্যবস্থাপনা থেকে শুরু করে সকল বিষয়ে নিয়ন্ত্রণ রাখুন।
                            </p>
                        </div>

                        <!-- Admin Features -->
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-red-600 rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs">✓</span>
                                </div>
                                <span class="text-gray-700">কোর্স ও শিক্ষক ব্যবস্থাপনা</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-red-600 rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs">✓</span>
                                </div>
                                <span class="text-gray-700">পেমেন্ট ও এনরোলমেন্ট নিয়ন্ত্রণ</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-red-600 rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs">✓</span>
                                </div>
                                <span class="text-gray-700">রিয়েল-টাইম ড্যাশবোর্ড ও রিপোর্ট</span>
                            </div>
                        </div>

                        <!-- Islamic Pattern Decoration -->
                        <div class="relative">
                            <div class="absolute -inset-4 bg-gradient-to-r from-[#2d5a27]/10 to-[#5f5fcd]/10 rounded-2xl transform rotate-1"></div>
                            <div class="relative bg-white p-6 rounded-2xl shadow-islamic">
                                <p class="text-red-600 font-medium italic text-center">
                                    "আর যে ব্যক্তি জ্ঞান অন্বেষণের পথে বের হয়, আল্লাহ তার জন্য জান্নাতের পথ সহজ করে দেন।"
                                </p>
                                <p class="text-gray-500 text-sm text-center mt-2">- হাদিস শরীফ</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Admin Login Form -->
                    <div>
                        <div class="bg-white p-8 rounded-2xl shadow-islamic border border-gray-100">
                            <div class="text-center mb-8">
                                <div class="w-16 h-16 bg-gradient-to-r from-[#2d5a27]/20 to-[#5f5fcd]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <ShieldCheckIcon class="w-8 h-8 text-red-600" />
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">অ্যাডমিন প্যানেলে প্রবেশ করুন</h2>
                                <p class="text-gray-600">আপনার অ্যাডমিন অ্যাকাউন্টের তথ্য দিয়ে লগইন করুন</p>
                            </div>

                            <div v-if="status" class="mb-6 text-center text-sm font-medium text-green-600 bg-green-50 p-3 rounded-lg">
                                {{ status }}
                            </div>

                            <form @submit.prevent="submit" class="space-y-6">
                                <!-- Email Field -->
                                <div>
                                    <Label for="email" class="text-gray-700 font-medium">ইমেইল ঠিকানা</Label>
                                    <div class="relative mt-2">
                                        <MailIcon class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                                        <Input
                                            id="email"
                                            type="email"
                                            required
                                            autofocus
                                            autocomplete="email"
                                            v-model="form.email"
                                            placeholder="admin@iqra-academy.com"
                                            class="pl-10 py-3 border-gray-200 focus:ring-2 focus:ring-red-600 focus:border-transparent rounded-lg"
                                        />
                                    </div>
                                    <InputError :message="form.errors.email" />
                                </div>

                                <!-- Password Field -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <Label for="password" class="text-gray-700 font-medium">পাসওয়ার্ড</Label>
                                        <TextLink :href="route('admin.password.request')" class="text-sm text-red-600 hover:text-red-700">
                                            পাসওয়ার্ড ভুলে গেছেন?
                                        </TextLink>
                                    </div>
                                    <div class="relative">
                                        <LockIcon class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                                        <Input
                                            id="password"
                                            type="password"
                                            required
                                            autocomplete="current-password"
                                            v-model="form.password"
                                            placeholder="আপনার অ্যাডমিন পাসওয়ার্ড"
                                            class="pl-10 py-3 border-gray-200 focus:ring-2 focus:ring-red-600 focus:border-transparent rounded-lg"
                                        />
                                    </div>
                                    <InputError :message="form.errors.password" />
                                </div>

                                <!-- Remember Me -->
                                <div class="flex items-center justify-between">
                                    <Label for="remember" class="flex items-center space-x-3 cursor-pointer">
                                        <Checkbox id="remember" v-model="form.remember" class="data-[state=checked]:bg-red-600 border-red-600" />
                                        <span class="text-gray-700">আমাকে মনে রাখুন</span>
                                    </Label>
                                </div>

                                <!-- Submit Button -->
                                <Button 
                                    type="submit" 
                                    class="w-full bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] hover:from-[#254521] hover:to-[#4a4aa6] text-white py-3 rounded-lg font-medium shadow-islamic hover:shadow-islamic-lg transition-all duration-200 transform hover:scale-[1.02]" 
                                    :disabled="form.processing"
                                >
                                    <LoaderCircle v-if="form.processing" class="h-5 w-5 animate-spin mr-2" />
                                    <ShieldCheckIcon v-else class="h-5 w-5 mr-2" />
                                    অ্যাডমিন প্যানেলে প্রবেশ করুন
                                </Button>
                            </form>

                            <!-- Back to Website -->
                            <div class="mt-8 text-center">
                                <p class="text-gray-600 mb-4">অথবা</p>
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
import { Checkbox } from '@/components/ui/checkbox'
import { Head } from '@inertiajs/vue3'
import { LoaderCircle, MailIcon, LockIcon, ShieldCheckIcon, ArrowLeftIcon } from 'lucide-vue-next'

defineProps({
  status: String,
})

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

const submit = () => {
  form.post(route('admin.login.submit'), {
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