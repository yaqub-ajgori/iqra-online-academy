<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import FrontendLayout from '@/layouts/FrontendLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle, MailIcon, LockIcon, LogInIcon } from 'lucide-vue-next';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <FrontendLayout title="লগইন - ইকরা অনলাইন একাডেমি">
        <Head title="লগইন" />

        <!-- Login Section -->
        <section class="py-20 min-h-screen flex items-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <!-- Left Side - Information -->
                    <div class="space-y-8">
                        <div>
                            <div class="inline-flex items-center px-4 py-2 rounded-full bg-[#5f5fcd]/10 border border-[#5f5fcd]/20 mb-6">
                                <LogInIcon class="w-4 h-4 text-[#5f5fcd] mr-2" />
                                <span class="text-[#5f5fcd] text-sm font-medium">স্বাগতম</span>
                            </div>
                            
                            <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                                আপনার <span class="text-gradient-islamic bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] bg-clip-text text-transparent">ইসলামিক জ্ঞানের</span> যাত্রা অব্যাহত রাখুন
                            </h1>
                            
                            <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                                আপনার অ্যাকাউন্টে লগইন করে ইসলামিক শিক্ষার অগ্রগতি চালিয়ে যান। 
                                কুরআন, হাদিস ও ফিকহের জ্ঞানে নিজেকে সমৃদ্ধ করুন।
                            </p>
                        </div>

                        <!-- Features -->
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-[#2d5a27] rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs">✓</span>
                                </div>
                                <span class="text-gray-700">অভিজ্ঞ উস্তাদদের তত্ত্বাবধানে কোর্স</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-[#2d5a27] rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs">✓</span>
                                </div>
                                <span class="text-gray-700">যেকোনো সময় যেকোনো জায়গা থেকে শিখুন</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-[#2d5a27] rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs">✓</span>
                                </div>
                                <span class="text-gray-700">আন্তর্জাতিক মানের সার্টিফিকেট</span>
                            </div>
                        </div>

                        <!-- Islamic Pattern Decoration -->
                        <div class="relative">
                            <div class="absolute -inset-4 bg-gradient-to-r from-[#5f5fcd]/10 to-[#2d5a27]/10 rounded-2xl transform rotate-1"></div>
                            <div class="relative bg-white p-6 rounded-2xl shadow-islamic">
                                <p class="text-[#5f5fcd] font-medium italic text-center">
                                    "পড়ো তোমার প্রভুর নামে যিনি সৃষ্টি করেছেন।"
                                </p>
                                <p class="text-gray-500 text-sm text-center mt-2">- সূরা আলাক, আয়াত ১</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Login Form -->
                    <div>
                        <div class="bg-white p-8 rounded-2xl shadow-islamic border border-gray-100">
                            <div class="text-center mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">আপনার অ্যাকাউন্টে লগইন করুন</h2>
                                <p class="text-gray-600">আপনার ইমেইল ও পাসওয়ার্ড দিয়ে প্রবেশ করুন</p>
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
                        :tabindex="1"
                        autocomplete="email"
                        v-model="form.email"
                                            placeholder="আপনার ইমেইল ঠিকানা লিখুন"
                                            class="pl-10 py-3 border-gray-200 focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent rounded-lg"
                    />
                                    </div>
                    <InputError :message="form.errors.email" />
                </div>

                                <!-- Password Field -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <Label for="password" class="text-gray-700 font-medium">পাসওয়ার্ড</Label>
                                        <TextLink v-if="canResetPassword" :href="route('password.request')" class="text-sm text-[#5f5fcd] hover:text-[#4a4aa6]" :tabindex="5">
                                            পাসওয়ার্ড ভুলে গেছেন?
                        </TextLink>
                    </div>
                                    <div class="relative">
                                        <LockIcon class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="2"
                        autocomplete="current-password"
                        v-model="form.password"
                                            placeholder="আপনার পাসওয়ার্ড"
                                            class="pl-10 py-3 border-gray-200 focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent rounded-lg"
                    />
                                    </div>
                    <InputError :message="form.errors.password" />
                </div>

                                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                                    <Label for="remember" class="flex items-center space-x-3 cursor-pointer">
                                        <Checkbox id="remember" v-model="form.remember" :tabindex="3" class="data-[state=checked]:bg-[#5f5fcd] border-[#5f5fcd]" />
                                        <span class="text-gray-700">আমাকে মনে রাখুন</span>
                    </Label>
                </div>

                                <!-- Submit Button -->
                                <Button 
                                    type="submit" 
                                    class="w-full bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] hover:from-[#4a4aa6] hover:to-[#1f3e1b] text-white py-3 rounded-lg font-medium shadow-islamic hover:shadow-islamic-lg transition-all duration-200 transform hover:scale-[1.02]" 
                                    :tabindex="4" 
                                    :disabled="form.processing"
                                >
                                    <LoaderCircle v-if="form.processing" class="h-5 w-5 animate-spin mr-2" />
                                    <LogInIcon v-else class="h-5 w-5 mr-2" />
                                    লগইন করুন
                </Button>
                            </form>

                            <!-- Register Link -->
                            <div class="mt-8 text-center">
                                <p class="text-gray-600">
                                    এখনো অ্যাকাউন্ট নেই?
                                    <TextLink :href="route('register')" :tabindex="6" class="text-[#5f5fcd] hover:text-[#4a4aa6] font-medium ml-1">
                                        নতুন অ্যাকাউন্ট তৈরি করুন
                                    </TextLink>
                                </p>
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
