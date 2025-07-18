<template>
    <FrontendLayout title="নতুন পাসওয়ার্ড সেট করুন - ইকরা অনলাইন একাডেমি">
        <Head title="নতুন পাসওয়ার্ড সেট করুন" />

        <!-- Reset Password Section -->
        <section class="flex min-h-screen items-start pt-6 sm:items-center sm:py-20">
            <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 items-center gap-16 lg:grid-cols-2">
                    <!-- Left Side - Information -->
                    <div class="space-y-8">
                        <div>
                            <div class="mb-6 inline-flex items-center rounded-full border border-purple-600/20 bg-purple-600/10 px-4 py-2">
                                <RefreshCw class="mr-2 h-4 w-4 text-purple-600" />
                                <span class="text-sm font-medium text-purple-600">নতুন পাসওয়ার্ড</span>
                            </div>

                            <h1 class="mb-6 text-4xl font-bold text-gray-900 lg:text-5xl">
                                <span class="text-gradient-islamic bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] bg-clip-text text-transparent"
                                    >নতুন পাসওয়ার্ড</span
                                >
                                সেট করুন
                            </h1>

                            <p class="mb-8 text-xl leading-relaxed text-gray-600">
                                আপনার অ্যাকাউন্টের জন্য একটি শক্তিশালী এবং নিরাপদ পাসওয়ার্ড তৈরি করুন।
                            </p>
                        </div>

                        <!-- Password Requirements -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900">পাসওয়ার্ডের প্রয়োজনীয়তা:</h3>
                            <div class="flex items-center space-x-3">
                                <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-purple-100">
                                    <Check class="h-4 w-4 text-purple-600" />
                                </div>
                                <span class="text-gray-700">কমপক্ষে ৮ অক্ষর হতে হবে</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-purple-100">
                                    <Check class="h-4 w-4 text-purple-600" />
                                </div>
                                <span class="text-gray-700">বড় এবং ছোট হাতের অক্ষর থাকতে হবে</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-purple-100">
                                    <Check class="h-4 w-4 text-purple-600" />
                                </div>
                                <span class="text-gray-700">অন্তত একটি সংখ্যা থাকতে হবে</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-purple-100">
                                    <Check class="h-4 w-4 text-purple-600" />
                                </div>
                                <span class="text-gray-700">বিশেষ চিহ্ন (@, #, $, % ইত্যাদি) থাকতে হবে</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Reset Password Form -->
                    <div>
                        <div class="shadow-islamic rounded-2xl border border-gray-100 bg-white p-8">
                            <div class="mb-8 text-center">
                                <div
                                    class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-r from-[#2d5a27]/20 to-[#5f5fcd]/20"
                                >
                                    <RefreshCw class="h-8 w-8 text-purple-600" />
                                </div>
                                <h2 class="mb-2 text-2xl font-bold text-gray-900">নতুন পাসওয়ার্ড তৈরি করুন</h2>
                                <p class="text-gray-600">আপনার অ্যাকাউন্টের জন্য একটি নিরাপদ পাসওয়ার্ড সেট করুন</p>
                            </div>

                            <form @submit.prevent="submit" class="space-y-6">
                                <!-- Email (readonly) -->
                                <div class="space-y-2">
                                    <Label for="email">ইমেইল ঠিকানা</Label>
                                    <div class="relative">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <Mail class="h-5 w-5 text-gray-400" />
                                        </div>
                                        <Input
                                            id="email"
                                            type="email"
                                            v-model="form.email"
                                            class="bg-gray-50 pl-10"
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
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
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
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
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
                                    class="hover:shadow-islamic-lg w-full transform rounded-lg bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] px-4 py-3 font-medium text-white transition-all duration-200 hover:scale-105 disabled:transform-none disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    {{ form.processing ? 'আপডেট হচ্ছে...' : 'পাসওয়ার্ড আপডেট করুন' }}
                                </Button>
                            </form>

                            <!-- Back to Login -->
                            <div class="mt-8 text-center">
                                <p class="mb-4 text-gray-600">পাসওয়ার্ড সেট করার পর</p>
                                <TextLink :href="route('login')" class="inline-flex items-center font-medium text-[#5f5fcd] hover:text-[#4a4aa6]">
                                    লগইন পেজে যান
                                    <ArrowRight class="ml-2 h-4 w-4" />
                                </TextLink>
                            </div>

                            <!-- Back to Website -->
                            <div class="mt-8 border-t pt-6 text-center">
                                <TextLink href="/" class="inline-flex items-center font-medium text-[#5f5fcd] hover:text-[#4a4aa6]">
                                    <ArrowLeft class="mr-2 h-4 w-4" />
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
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import FrontendLayout from '@/layouts/FrontendLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ArrowLeft, ArrowRight, Check, Lock, Mail, RefreshCw } from 'lucide-vue-next';

const props = defineProps({
    token: String,
    email: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
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
