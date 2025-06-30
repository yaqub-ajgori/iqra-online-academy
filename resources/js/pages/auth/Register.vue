<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import FrontendLayout from '@/layouts/FrontendLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle, UserIcon, MailIcon, LockIcon, UserPlusIcon, BookOpenIcon } from 'lucide-vue-next';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <FrontendLayout title="নিবন্ধন - ইকরা অনলাইন একাডেমি">
        <Head title="নিবন্ধন" />

        <!-- Register Section -->
        <section class="py-20 min-h-screen flex items-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <!-- Left Side - Information -->
                    <div class="space-y-8">
                        <div>
                            <div class="inline-flex items-center px-4 py-2 rounded-full bg-[#2d5a27]/10 border border-[#2d5a27]/20 mb-6">
                                <UserPlusIcon class="w-4 h-4 text-[#2d5a27] mr-2" />
                                <span class="text-[#2d5a27] text-sm font-medium">নতুন সদস্য</span>
                            </div>
                            
                            <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                                ইসলামিক <span class="text-gradient-islamic bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] bg-clip-text text-transparent">জ্ঞানের যাত্রা</span> শুরু করুন
                            </h1>
                            
                            <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                                ইকরা অনলাইন একাডেমিতে যোগ দিন এবং কুরআন, হাদিস ও ইসলামিক শিক্ষার বিশাল ভাণ্ডার থেকে জ্ঞান অর্জন করুন। 
                                আপনার ইসলামিক শিক্ষার যাত্রা আজই শুরু করুন।
                            </p>
                        </div>

                        <!-- Benefits -->
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-[#5f5fcd] rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs">✓</span>
                                </div>
                                <span class="text-gray-700">সম্পূর্ণ বিনামূল্যে নিবন্ধন</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-[#5f5fcd] rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs">✓</span>
                                </div>
                                <span class="text-gray-700">১০০+ ইসলামিক কোর্সে অ্যাক্সেস</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-[#5f5fcd] rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs">✓</span>
                                </div>
                                <span class="text-gray-700">অভিজ্ঞ উস্তাদদের সরাসরি গাইডেন্স</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-[#5f5fcd] rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs">✓</span>
                                </div>
                                <span class="text-gray-700">কোর্স সম্পন্নের পর সার্টিফিকেট</span>
                            </div>
                        </div>

                        <!-- Course Statistics -->
                        <div class="bg-gradient-to-r from-[#5f5fcd]/10 to-[#2d5a27]/10 p-6 rounded-2xl">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">আমাদের শিক্ষার্থীরা</h3>
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div>
                                    <div class="text-2xl font-bold text-[#5f5fcd]">৫০০০+</div>
                                    <div class="text-sm text-gray-600">শিক্ষার্থী</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-[#2d5a27]">১০০+</div>
                                    <div class="text-sm text-gray-600">কোর্স</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-[#d4a574]">৯৫%</div>
                                    <div class="text-sm text-gray-600">সন্তুষ্টি</div>
                                </div>
                            </div>
                        </div>

                        <!-- Quranic Verse -->
                        <div class="relative">
                            <div class="absolute -inset-4 bg-gradient-to-r from-[#2d5a27]/10 to-[#5f5fcd]/10 rounded-2xl transform -rotate-1"></div>
                            <div class="relative bg-white p-6 rounded-2xl shadow-islamic">
                                <p class="text-[#2d5a27] font-medium italic text-center">
                                    "যে ব্যক্তি জ্ঞান অন্বেষণে বের হয়, সে আল্লাহর পথে থাকে।"
                                </p>
                                <p class="text-gray-500 text-sm text-center mt-2">- হাদিস শরীফ</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Registration Form -->
                    <div>
                        <div class="bg-white p-8 rounded-2xl shadow-islamic border border-gray-100">
                            <div class="text-center mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">নতুন অ্যাকাউন্ট তৈরি করুন</h2>
                                <p class="text-gray-600">নিচের তথ্য দিয়ে আপনার অ্যাকাউন্ট তৈরি করুন</p>
                            </div>

                            <form @submit.prevent="submit" class="space-y-6">
                                <!-- Name Field -->
                                <div>
                                    <Label for="name" class="text-gray-700 font-medium">পূর্ণ নাম</Label>
                                    <div class="relative mt-2">
                                        <UserIcon class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                                        <Input 
                                            id="name" 
                                            type="text" 
                                            required 
                                            autofocus 
                                            :tabindex="1" 
                                            autocomplete="name" 
                                            v-model="form.name" 
                                            placeholder="আপনার পূর্ণ নাম লিখুন"
                                            class="pl-10 py-3 border-gray-200 focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent rounded-lg"
                                        />
                                    </div>
                    <InputError :message="form.errors.name" />
                </div>

                                <!-- Email Field -->
                                <div>
                                    <Label for="email" class="text-gray-700 font-medium">ইমেইল ঠিকানা</Label>
                                    <div class="relative mt-2">
                                        <MailIcon class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                                        <Input 
                                            id="email" 
                                            type="email" 
                                            required 
                                            :tabindex="2" 
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
                                    <Label for="password" class="text-gray-700 font-medium">পাসওয়ার্ড</Label>
                                    <div class="relative mt-2">
                                        <LockIcon class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="3"
                        autocomplete="new-password"
                        v-model="form.password"
                                            placeholder="একটি শক্তিশালী পাসওয়ার্ড তৈরি করুন"
                                            class="pl-10 py-3 border-gray-200 focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent rounded-lg"
                    />
                                    </div>
                    <InputError :message="form.errors.password" />
                </div>

                                <!-- Confirm Password Field -->
                                <div>
                                    <Label for="password_confirmation" class="text-gray-700 font-medium">পাসওয়ার্ড নিশ্চিত করুন</Label>
                                    <div class="relative mt-2">
                                        <LockIcon class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                    <Input
                        id="password_confirmation"
                        type="password"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        v-model="form.password_confirmation"
                                            placeholder="পাসওয়ার্ড পুনরায় লিখুন"
                                            class="pl-10 py-3 border-gray-200 focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent rounded-lg"
                    />
                                    </div>
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                                <!-- Submit Button -->
                                <Button 
                                    type="submit" 
                                    class="w-full bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] hover:from-[#1f3e1b] hover:to-[#4a4aa6] text-white py-3 rounded-lg font-medium shadow-islamic hover:shadow-islamic-lg transition-all duration-200 transform hover:scale-[1.02]" 
                                    :tabindex="5" 
                                    :disabled="form.processing"
                                >
                                    <LoaderCircle v-if="form.processing" class="h-5 w-5 animate-spin mr-2" />
                                    <UserPlusIcon v-else class="h-5 w-5 mr-2" />
                                    অ্যাকাউন্ট তৈরি করুন
                </Button>
                            </form>

                            <!-- Login Link -->
                            <div class="mt-8 text-center">
                                <p class="text-gray-600">
                                    ইতিমধ্যে অ্যাকাউন্ট আছে?
                                    <TextLink :href="route('login')" :tabindex="6" class="text-[#5f5fcd] hover:text-[#4a4aa6] font-medium ml-1">
                                        লগইন করুন
                                    </TextLink>
                                </p>
            </div>

                            <!-- Terms -->
                            <div class="mt-6 text-center">
                                <p class="text-sm text-gray-500">
                                    নিবন্ধনের মাধ্যমে আপনি আমাদের 
                                    <a href="#" class="text-[#5f5fcd] hover:underline">ব্যবহারের শর্তাবলী</a> ও 
                                    <a href="#" class="text-[#5f5fcd] hover:underline">গোপনীয়তা নীতি</a> মেনে নিচ্ছেন।
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
