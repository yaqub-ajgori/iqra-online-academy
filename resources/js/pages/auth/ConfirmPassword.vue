<template>
    <FrontendLayout title="পাসওয়ার্ড নিশ্চিত করুন - ইকরা অনলাইন একাডেমি">
        <Head title="পাসওয়ার্ড নিশ্চিত করুন" />

        <!-- Password Confirmation Section -->
        <section class="flex min-h-screen items-start pt-6 sm:items-center sm:py-20">
            <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 items-center gap-16 lg:grid-cols-2">
                    <!-- Left Side - Information -->
                    <div class="hidden space-y-8 lg:block">
                        <div>
                            <div class="mb-6 inline-flex items-center rounded-full border border-red-600/20 bg-red-600/10 px-4 py-2">
                                <Shield class="mr-2 h-4 w-4 text-red-600" />
                                <span class="text-sm font-medium text-red-600">নিরাপত্তা যাচাই</span>
                            </div>

                            <h1 class="mb-6 text-4xl font-bold text-gray-900 lg:text-5xl">পাসওয়ার্ড নিশ্চিত করুন</h1>

                            <p class="mb-8 text-xl leading-relaxed text-gray-600">
                                এটি আপনার অ্যাপ্লিকেশনের একটি নিরাপত্তা এলাকা। অনুগ্রহ করে আগে আপনার পাসওয়ার্ড নিশ্চিত করুন।
                            </p>
                        </div>
                    </div>

                    <!-- Right Side - Password Confirmation Form -->
                    <div>
                        <div
                            class="shadow-islamic-lg relative overflow-hidden rounded-2xl border border-gray-100/50 bg-white/95 p-8 backdrop-blur-sm"
                        >
                            <!-- Background pattern -->
                            <div class="absolute inset-0 opacity-5">
                                <svg class="h-full w-full" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
                                    <defs>
                                        <pattern id="confirmPattern" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                                            <path d="M20 0 L40 20 L20 40 L0 20 Z" fill="none" stroke="#5f5fcd" stroke-width="1" />
                                            <circle cx="20" cy="20" r="8" fill="none" stroke="#2d5a27" stroke-width="1" />
                                        </pattern>
                                    </defs>
                                    <rect width="400" height="400" fill="url(#confirmPattern)" />
                                </svg>
                            </div>

                            <div class="relative z-10">
                                <div class="mb-8 text-center">
                                    <div
                                        class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-r from-[#5f5fcd]/20 via-[#2d5a27]/20 to-[#d4a574]/20"
                                    >
                                        <Shield class="h-8 w-8 text-[#5f5fcd]" />
                                    </div>
                                    <h2 class="mb-2 text-2xl font-bold text-gray-900">পাসওয়ার্ড নিশ্চিত করুন</h2>
                                    <p class="text-sm text-gray-600">
                                        আপনার বর্তমান পাসওয়ার্ড দিয়ে নিশ্চিত করুন
                                    </p>
                                </div>

                                <form @submit.prevent="submit">
                                    <div class="space-y-6">
                                        <!-- Password Field -->
                                        <div>
                                            <label
                                                for="password"
                                                class="block text-sm font-medium text-gray-700 mb-2"
                                            >
                                                পাসওয়ার্ড
                                            </label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 flex items-center pl-4">
                                                    <Lock class="h-5 w-5 text-gray-400" />
                                                </div>
                                                <input
                                                    id="password"
                                                    v-model="form.password"
                                                    type="password"
                                                    class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent bg-white/80 backdrop-blur-sm transition-all duration-200"
                                                    placeholder="আপনার বর্তমান পাসওয়ার্ড লিখুন"
                                                    required
                                                    autofocus
                                                />
                                            </div>
                                            <InputError class="mt-2" :message="form.errors.password" />
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="space-y-4">
                                            <Button
                                                type="submit"
                                                :disabled="form.processing"
                                                class="w-full justify-center relative overflow-hidden bg-gradient-to-r from-[#5f5fcd] via-[#2d5a27] to-[#d4a574] text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-[1.02] py-3 px-6 rounded-lg font-medium"
                                            >
                                                <div class="absolute inset-0 bg-white/10 opacity-0 hover:opacity-100 transition-opacity duration-300"></div>
                                                <Shield class="mr-2 h-4 w-4" />
                                                <span class="relative z-10">{{ form.processing ? 'প্রক্রিয়াধীন...' : 'নিশ্চিত করুন' }}</span>
                                            </Button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </FrontendLayout>
</template>

<script setup>
import FrontendLayout from '@/layouts/FrontendLayout.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Head, useForm } from '@inertiajs/vue3';
import { Lock, Shield } from 'lucide-vue-next';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => {
            form.reset();
        },
    });
};
</script>