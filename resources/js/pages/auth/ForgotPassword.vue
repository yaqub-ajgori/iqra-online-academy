<template>
    <FrontendLayout title="পাসওয়ার্ড রিসেট - ইকরা অনলাইন একাডেমি">
        <Head title="পাসওয়ার্ড রিসেট" />

        <!-- Forgot Password Section -->
        <section class="flex min-h-screen items-start pt-6 sm:items-center sm:py-20">
            <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 items-center gap-16 lg:grid-cols-2">
                    <!-- Left Side - Information -->
                    <div class="hidden space-y-8 lg:block">
                        <div>
                            <div class="mb-6 inline-flex items-center rounded-full border border-orange-600/20 bg-orange-600/10 px-4 py-2">
                                <KeyIcon class="mr-2 h-4 w-4 text-orange-600" />
                                <span class="text-sm font-medium text-orange-600">পাসওয়ার্ড রিসেট</span>
                            </div>

                            <h1 class="mb-6 text-4xl font-bold text-gray-900 lg:text-5xl">পাসওয়ার্ড রিসেট</h1>

                            <p class="mb-8 text-xl leading-relaxed text-gray-600">আপনার ইমেইল দিন, আমরা একটি পাসওয়ার্ড রিসেট লিঙ্ক পাঠাবো।</p>
                        </div>
                    </div>

                    <!-- Right Side - Forgot Password Form -->
                    <div>
                        <div
                            class="shadow-islamic-lg relative overflow-hidden rounded-2xl border border-gray-100/50 bg-white/95 p-8 backdrop-blur-sm"
                        >
                            <!-- Background pattern -->
                            <div class="absolute inset-0 opacity-5">
                                <svg class="h-full w-full" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
                                    <defs>
                                        <pattern id="forgotPattern" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                                            <path d="M20 0 L40 20 L20 40 L0 20 Z" fill="none" stroke="#5f5fcd" stroke-width="1" />
                                            <circle cx="20" cy="20" r="8" fill="none" stroke="#2d5a27" stroke-width="1" />
                                        </pattern>
                                    </defs>
                                    <rect width="400" height="400" fill="url(#forgotPattern)" />
                                </svg>
                            </div>

                            <div class="relative z-10">
                                <div class="mb-8 text-center">
                                    <div
                                        class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-r from-[#5f5fcd]/20 via-[#2d5a27]/20 to-[#d4a574]/20"
                                    >
                                        <KeyIcon class="h-8 w-8 text-[#5f5fcd]" />
                                    </div>
                                    <h2 class="mb-2 text-2xl font-bold text-gray-900">পাসওয়ার্ড ভুলে গেছেন?</h2>
                                    <p class="text-gray-600">আপনার ইমেইল ঠিকানা দিন পাসওয়ার্ড রিসেট করতে</p>
                                </div>

                                <div v-if="status" class="mb-6 rounded-lg bg-green-50 p-3 text-center text-sm font-medium text-green-600">
                                    {{ status }}
                                </div>

                                <form @submit.prevent="submit" class="space-y-6">
                                    <!-- Email -->
                                    <div class="space-y-2">
                                        <Label for="email" class="font-medium text-gray-700">ইমেইল ঠিকানা</Label>
                                        <div class="group relative">
                                            <div
                                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 transition-colors duration-200 group-focus-within:text-[#5f5fcd]"
                                            >
                                                <MailIcon class="h-5 w-5 text-gray-400" />
                                            </div>
                                            <Input
                                                id="email"
                                                type="email"
                                                v-model="form.email"
                                                class="rounded-xl border-gray-200 pl-10 transition-all duration-200 focus:border-[#5f5fcd] focus:ring-[#5f5fcd]/20"
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
                                        class="group hover:shadow-islamic-lg relative w-full transform overflow-hidden rounded-xl bg-gradient-to-r from-[#5f5fcd] via-[#2d5a27] to-[#d4a574] px-4 py-3 font-semibold text-white transition-all duration-300 hover:scale-105 disabled:transform-none disabled:cursor-not-allowed disabled:opacity-50"
                                    >
                                        <!-- Animated background overlay -->
                                        <div
                                            class="absolute inset-0 -translate-x-full -skew-x-12 transform bg-gradient-to-r from-white/0 via-white/10 to-white/0 transition-transform duration-700 group-hover:translate-x-full"
                                        ></div>
                                        <span class="relative z-10">{{ form.processing ? 'পাঠানো হচ্ছে...' : 'পাসওয়ার্ড রিসেট লিংক পাঠান' }}</span>
                                    </Button>
                                </form>

                                <!-- Back to Login -->
                                <div class="mt-8 text-center">
                                    <span class="text-gray-600">পাসওয়ার্ড মনে আছে? </span>
                                    <TextLink :href="route('login')" class="inline-flex items-center font-medium text-[#5f5fcd] hover:text-[#4a4aa6]">
                                        <ArrowLeftIcon class="mr-2 h-4 w-4" />
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
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import FrontendLayout from '@/layouts/FrontendLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ArrowLeftIcon, KeyIcon, MailIcon } from 'lucide-vue-next';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
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
