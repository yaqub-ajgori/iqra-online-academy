<template>
    <FrontendLayout title="নতুন ছাত্র রেজিস্ট্রেশন - ইকরা অনলাইন একাডেমি">
        <Head title="নতুন ছাত্র রেজিস্ট্রেশন" />

        <!-- Student Registration Section -->
        <section class="flex min-h-screen items-start pt-6 sm:items-center sm:py-20">
            <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 items-center gap-16 lg:grid-cols-2">
                    <!-- Left Side - Information -->
                    <div class="hidden space-y-8 lg:block">
                        <div>
                            <div class="mb-6 inline-flex items-center rounded-full border border-blue-600/20 bg-blue-600/10 px-4 py-2">
                                <UserPlus class="mr-2 h-4 w-4 text-blue-600" />
                                <span class="text-sm font-medium text-blue-600">নতুন ছাত্র রেজিস্ট্রেশন</span>
                            </div>
                            <h1 class="mb-6 text-4xl font-bold text-gray-900 lg:text-5xl">নতুন ছাত্র রেজিস্ট্রেশন</h1>
                            <p class="mb-8 text-xl leading-relaxed text-gray-600">
                                নতুন অ্যাকাউন্ট তৈরি করতে আপনার নাম, ইমেইল ও পাসওয়ার্ড দিন। রেজিস্ট্রেশনের পর ইমেইল যাচাই করুন।
                            </p>
                        </div>
                    </div>

                    <!-- Right Side - Registration Form -->
                    <div>
                        <div
                            class="shadow-islamic-lg relative overflow-hidden rounded-2xl border border-gray-100/50 bg-white/95 p-8 backdrop-blur-sm"
                        >
                            <!-- Background pattern -->
                            <div class="absolute inset-0 opacity-5">
                                <svg class="h-full w-full" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
                                    <defs>
                                        <pattern id="registerPattern" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                                            <path d="M20 0 L40 20 L20 40 L0 20 Z" fill="none" stroke="#5f5fcd" stroke-width="1" />
                                            <circle cx="20" cy="20" r="8" fill="none" stroke="#2d5a27" stroke-width="1" />
                                        </pattern>
                                    </defs>
                                    <rect width="400" height="400" fill="url(#registerPattern)" />
                                </svg>
                            </div>

                            <div class="relative z-10">
                                <div class="mb-8 text-center">
                                    <div
                                        class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-r from-[#5f5fcd]/20 via-[#2d5a27]/20 to-[#d4a574]/20"
                                    >
                                        <UserPlus class="h-8 w-8 text-[#5f5fcd]" />
                                    </div>
                                    <h2 class="mb-2 text-2xl font-bold text-gray-900">নতুন অ্যাকাউন্ট তৈরি করুন</h2>
                                    <p class="text-gray-600">আপনার তথ্য দিয়ে রেজিস্ট্রেশন সম্পন্ন করুন</p>
                                </div>

                                <form @submit.prevent="submit" class="space-y-6">
                                    <!-- Name -->
                                    <div class="space-y-2">
                                        <Label for="name" class="font-medium text-gray-700">পূর্ণ নাম</Label>
                                        <div class="group relative">
                                            <div
                                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 transition-colors duration-200 group-focus-within:text-[#5f5fcd]"
                                            >
                                                <User class="h-5 w-5 text-gray-400" />
                                            </div>
                                            <Input
                                                id="name"
                                                type="text"
                                                v-model="form.name"
                                                class="rounded-xl border-gray-200 pl-10 transition-all duration-200 focus:border-[#5f5fcd] focus:ring-[#5f5fcd]/20"
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
                                        <Label for="email" class="font-medium text-gray-700">ইমেইল ঠিকানা</Label>
                                        <div class="group relative">
                                            <div
                                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 transition-colors duration-200 group-focus-within:text-[#5f5fcd]"
                                            >
                                                <Mail class="h-5 w-5 text-gray-400" />
                                            </div>
                                            <Input
                                                id="email"
                                                type="email"
                                                v-model="form.email"
                                                class="rounded-xl border-gray-200 pl-10 transition-all duration-200 focus:border-[#5f5fcd] focus:ring-[#5f5fcd]/20"
                                                placeholder="আপনার ইমেইল ঠিকানা"
                                                required
                                                autocomplete="username"
                                            />
                                        </div>
                                        <InputError :message="form.errors.email" />
                                    </div>

                                    <!-- Password -->
                                    <div class="space-y-2">
                                        <Label for="password" class="font-medium text-gray-700">পাসওয়ার্ড</Label>
                                        <div class="group relative">
                                            <div
                                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 transition-colors duration-200 group-focus-within:text-[#5f5fcd]"
                                            >
                                                <Lock class="h-5 w-5 text-gray-400" />
                                            </div>
                                            <Input
                                                id="password"
                                                type="password"
                                                v-model="form.password"
                                                class="rounded-xl border-gray-200 pl-10 transition-all duration-200 focus:border-[#5f5fcd] focus:ring-[#5f5fcd]/20"
                                                placeholder="কমপক্ষে ৮ অক্ষরের পাসওয়ার্ড"
                                                required
                                                autocomplete="new-password"
                                            />
                                        </div>
                                        <InputError :message="form.errors.password" />
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="space-y-2">
                                        <Label for="password_confirmation" class="font-medium text-gray-700">পাসওয়ার্ড নিশ্চিত করুন</Label>
                                        <div class="group relative">
                                            <div
                                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 transition-colors duration-200 group-focus-within:text-[#5f5fcd]"
                                            >
                                                <Lock class="h-5 w-5 text-gray-400" />
                                            </div>
                                            <Input
                                                id="password_confirmation"
                                                type="password"
                                                v-model="form.password_confirmation"
                                                class="rounded-xl border-gray-200 pl-10 transition-all duration-200 focus:border-[#5f5fcd] focus:ring-[#5f5fcd]/20"
                                                placeholder="পাসওয়ার্ড পুনরায় লিখুন"
                                                required
                                                autocomplete="new-password"
                                            />
                                        </div>
                                        <InputError :message="form.errors.password_confirmation" />
                                    </div>

                                    <!-- Terms and Conditions -->
                                    <div class="flex flex-row flex-wrap items-start gap-2">
                                        <Checkbox id="terms" v-model="form.terms" class="mt-1 flex-shrink-0" required />
                                        <Label for="terms" class="w-0 min-w-0 flex-1 text-sm leading-relaxed break-words text-gray-600">
                                            আমি ইকরা অনলাইন একাডেমির
                                            <br class="block sm:hidden" />
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
                                        class="group hover:shadow-islamic-lg relative w-full transform overflow-hidden rounded-xl bg-gradient-to-r from-[#5f5fcd] via-[#2d5a27] to-[#d4a574] px-4 py-3 font-semibold text-white transition-all duration-300 hover:scale-105 disabled:transform-none disabled:cursor-not-allowed disabled:opacity-50"
                                    >
                                        <!-- Animated background overlay -->
                                        <div
                                            class="absolute inset-0 -translate-x-full -skew-x-12 transform bg-gradient-to-r from-white/0 via-white/10 to-white/0 transition-transform duration-700 group-hover:translate-x-full"
                                        ></div>
                                        <span class="relative z-10">{{ form.processing ? 'রেজিস্ট্রেশন হচ্ছে...' : 'রেজিস্ট্রেশন করুন' }}</span>
                                    </Button>
                                </form>

                                <!-- Login Link -->
                                <div class="mt-8 text-center">
                                    <span class="text-gray-600">ইতিমধ্যে অ্যাকাউন্ট আছে? </span>
                                    <TextLink :href="route('login')" class="inline-flex items-center font-medium text-[#5f5fcd] hover:text-[#4a4aa6]">
                                        লগইন করুন
                                        <ArrowRight class="ml-2 h-4 w-4" />
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
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useToast } from '@/composables/useToast';
import FrontendLayout from '@/layouts/FrontendLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ArrowRight, Lock, Mail, User, UserPlus } from 'lucide-vue-next';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const { error: showError, success: showSuccess } = useToast();

const submit = () => {
    form.post(route('register'), {
        onSuccess: () => {
            form.reset();
            showSuccess('Registration successful. Please check your email for verification.');
        },
        onError: () => {
            // Show error and reset only password fields
            if (form.errors.email && form.errors.email.includes('seconds')) {
                showError('Too many registration attempts. Please try again in a minute.');
            } else if (form.errors.email || form.errors.password) {
                showError('Registration failed. Please check your input.');
            } else {
                showError('An unexpected error occurred. Please try again.');
            }
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<style scoped>
/* Text gradient animation */
.text-gradient-islamic {
    background-size: 200% 200%;
    animation: gradientShift 4s ease-in-out infinite;
}

@keyframes gradientShift {
    0%,
    100% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
}
</style>
