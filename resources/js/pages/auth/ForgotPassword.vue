<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import FrontendLayout from '@/layouts/FrontendLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle, MailIcon, KeyIcon, ArrowLeftIcon } from 'lucide-vue-next';

defineProps<{
    status?: string;
}>();

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <FrontendLayout title="পাসওয়ার্ড রিসেট - ইকরা অনলাইন একাডেমি">
        <Head title="পাসওয়ার্ড ভুলে গেছেন" />

        <!-- Forgot Password Section -->
        <section class="py-20 min-h-screen flex items-center">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <!-- Left Side - Information -->
                    <div class="space-y-8">
                        <div>
                            <div class="inline-flex items-center px-4 py-2 rounded-full bg-[#d4a574]/10 border border-[#d4a574]/20 mb-6">
                                <KeyIcon class="w-4 h-4 text-[#d4a574] mr-2" />
                                <span class="text-[#d4a574] text-sm font-medium">পাসওয়ার্ড রিসেট</span>
                            </div>
                            
                            <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                                <span class="text-gradient-islamic bg-gradient-to-r from-[#d4a574] to-[#5f5fcd] bg-clip-text text-transparent">পাসওয়ার্ড</span> পুনরুদ্ধার করুন
                            </h1>
                            
                            <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                                চিন্তা করবেন না! আপনার ইমেইল ঠিকানা দিন এবং আমরা আপনাকে পাসওয়ার্ড রিসেট করার জন্য একটি লিঙ্ক পাঠাবো। 
                                কয়েক মিনিটের মধ্যেই আপনি আবার আপনার অ্যাকাউন্টে প্রবেশ করতে পারবেন।
                            </p>
        </div>

                        <!-- Steps -->
        <div class="space-y-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">পাসওয়ার্ড রিসেট প্রক্রিয়া:</h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-start space-x-4">
                                    <div class="w-8 h-8 bg-[#5f5fcd] text-white rounded-full flex items-center justify-center font-semibold text-sm">
                                        ১
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">ইমেইল দিন</h4>
                                        <p class="text-gray-600 text-sm">আপনার নিবন্ধিত ইমেইল ঠিকানা লিখুন</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-4">
                                    <div class="w-8 h-8 bg-[#2d5a27] text-white rounded-full flex items-center justify-center font-semibold text-sm">
                                        ২
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">ইমেইল চেক করুন</h4>
                                        <p class="text-gray-600 text-sm">আপনার ইনবক্সে রিসেট লিঙ্ক পাবেন</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-4">
                                    <div class="w-8 h-8 bg-[#d4a574] text-white rounded-full flex items-center justify-center font-semibold text-sm">
                                        ৩
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">নতুন পাসওয়ার্ড সেট করুন</h4>
                                        <p class="text-gray-600 text-sm">লিঙ্কে ক্লিক করে নতুন পাসওয়ার্ড দিন</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Support Info -->
                        <div class="bg-gradient-to-r from-[#5f5fcd]/10 to-[#2d5a27]/10 p-6 rounded-2xl">
                            <h3 class="font-semibold text-gray-900 mb-2">সাহায্য প্রয়োজন?</h3>
                            <p class="text-gray-600 text-sm mb-3">
                                যদি ইমেইল না পান বা অন্য কোনো সমস্যা হয়, আমাদের সাথে যোগাযোগ করুন।
                            </p>
                            <div class="flex items-center space-x-4 text-sm">
                                <a href="mailto:support@iqraacademy.com" class="text-[#5f5fcd] hover:text-[#4a4aa6] font-medium">
                                    support@iqraacademy.com
                                </a>
                                <span class="text-gray-400">|</span>
                                <a href="tel:+8801700000000" class="text-[#2d5a27] hover:text-[#1f3e1b] font-medium">
                                    +880 170 000 0000
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Reset Form -->
                    <div>
                        <div class="bg-white p-8 rounded-2xl shadow-islamic border border-gray-100">
                            <div class="text-center mb-8">
                                <div class="w-16 h-16 bg-gradient-to-r from-[#d4a574]/20 to-[#5f5fcd]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <KeyIcon class="w-8 h-8 text-[#d4a574]" />
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">পাসওয়ার্ড রিসেট করুন</h2>
                                <p class="text-gray-600">আপনার ইমেইল ঠিকানা দিন রিসেট লিঙ্ক পেতে</p>
                            </div>

                            <div v-if="status" class="mb-6 text-center text-sm font-medium text-green-600 bg-green-50 p-4 rounded-lg border border-green-200">
                                <div class="flex items-center justify-center space-x-2">
                                    <span class="text-green-500">✓</span>
                                    <span>{{ status }}</span>
                                </div>
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
                                            name="email" 
                                            autocomplete="email" 
                                            v-model="form.email" 
                                            autofocus 
                                            placeholder="আপনার ইমেইল ঠিকানা লিখুন"
                                            class="pl-10 py-3 border-gray-200 focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent rounded-lg"
                                        />
                                    </div>
                    <InputError :message="form.errors.email" />
                </div>

                                <!-- Submit Button -->
                                <Button 
                                    type="submit"
                                    class="w-full bg-gradient-to-r from-[#d4a574] to-[#5f5fcd] hover:from-[#c09660] hover:to-[#4a4aa6] text-white py-3 rounded-lg font-medium shadow-islamic hover:shadow-islamic-lg transition-all duration-200 transform hover:scale-[1.02]" 
                                    :disabled="form.processing"
                                >
                                    <LoaderCircle v-if="form.processing" class="h-5 w-5 animate-spin mr-2" />
                                    <MailIcon v-else class="h-5 w-5 mr-2" />
                                    রিসেট লিঙ্ক পাঠান
                    </Button>
                            </form>

                            <!-- Back to Login -->
                            <div class="mt-8 text-center">
                                <p class="text-gray-600 mb-4">অথবা</p>
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
        </section>
    </FrontendLayout>
</template>

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
