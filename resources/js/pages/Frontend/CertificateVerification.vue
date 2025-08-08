<template>
    <FrontendLayout title="সার্টিফিকেট যাচাই - ইকরা অনলাইন একাডেমি">
        <div class="min-h-screen bg-gradient-to-br from-[#5f5fcd]/5 via-white to-[#2d5a27]/5">
            <div class="container mx-auto px-4 py-12">
                <div class="mx-auto max-w-2xl">
                    <!-- Header -->
                    <div class="mb-8 text-center">
                        <div
                            class="shadow-islamic mb-4 inline-flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-[#5f5fcd]/10 to-[#2d5a27]/10"
                        >
                            <svg class="h-8 w-8 text-[#5f5fcd]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                ></path>
                            </svg>
                        </div>
                        <h1 class="mb-2 text-3xl font-bold text-gray-900">সার্টিফিকেট যাচাই</h1>
                        <p class="text-gray-600">আপনার সার্টিফিকেটের সত্যতা যাচাই করুন</p>
                    </div>

                    <!-- Verification Form -->
                    <div class="shadow-islamic mb-8 rounded-2xl border border-gray-100 bg-white p-8">
                        <form @submit.prevent="verifyCode" class="space-y-6">
                            <div>
                                <label for="verification_code" class="mb-2 block text-sm font-medium text-gray-700"> ভেরিফিকেশন কোড </label>
                                <input
                                    id="verification_code"
                                    v-model="form.verification_code"
                                    type="text"
                                    maxlength="12"
                                    placeholder="12-digit verification code"
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-center text-lg tracking-widest uppercase transition-all duration-200 focus:border-[#5f5fcd] focus:ring-2 focus:ring-[#5f5fcd]"
                                    :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': form.errors.verification_code }"
                                    required
                                />
                                <div v-if="form.errors.verification_code" class="mt-2 text-sm text-red-600">
                                    {{ form.errors.verification_code }}
                                </div>
                                <p class="mt-2 text-sm text-gray-500">ভেরিফিকেশন কোড সার্টিফিকেটের নিচের দিকে পাবেন</p>
                            </div>

                            <button
                                type="submit"
                                :disabled="form.processing || !form.verification_code"
                                class="hover:shadow-islamic-lg w-full rounded-xl bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] px-6 py-3 font-medium text-white transition-all duration-200 disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                <span v-if="form.processing">যাচাই করা হচ্ছে...</span>
                                <span v-else>যাচাই করুন</span>
                            </button>
                        </form>
                    </div>

                    <!-- Verification Result -->
                    <div v-if="certificate" class="shadow-islamic rounded-2xl border border-gray-100 bg-white p-8">
                        <div class="mb-6 text-center">
                            <div
                                class="shadow-islamic mb-4 inline-flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-[#2d5a27]/10 to-[#2d5a27]/20"
                            >
                                <svg class="h-8 w-8 text-[#2d5a27]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h2 class="mb-2 text-2xl font-bold text-[#2d5a27]">বৈধ সার্টিফিকেট</h2>
                            <p class="text-gray-600">এই সার্টিফিকেটটি সত্য এবং বৈধ</p>
                        </div>

                        <!-- Certificate Details -->
                        <div class="border-t pt-6">
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <h3 class="mb-1 text-sm font-medium text-gray-500">সার্টিফিকেট নম্বর</h3>
                                    <p class="text-lg font-semibold text-gray-800">{{ certificate.certificate_number }}</p>
                                </div>

                                <div>
                                    <h3 class="mb-1 text-sm font-medium text-gray-500">শিক্ষার্থীর নাম</h3>
                                    <p class="text-lg font-semibold text-gray-800">{{ certificate.student_name }}</p>
                                </div>

                                <div>
                                    <h3 class="mb-1 text-sm font-medium text-gray-500">কোর্সের নাম</h3>
                                    <p class="text-lg font-semibold text-gray-800">{{ certificate.course_title }}</p>
                                </div>

                                <div>
                                    <h3 class="mb-1 text-sm font-medium text-gray-500">সম্পন্নের তারিখ</h3>
                                    <p class="text-lg font-semibold text-gray-800">{{ certificate.completion_date }}</p>
                                </div>

                                <div>
                                    <h3 class="mb-1 text-sm font-medium text-gray-500">ইস্যুর তারিখ</h3>
                                    <p class="text-lg font-semibold text-gray-800">{{ certificate.issue_date }}</p>
                                </div>

                                <div v-if="certificate.final_score">
                                    <h3 class="mb-1 text-sm font-medium text-gray-500">স্কোর</h3>
                                    <p class="text-lg font-semibold text-gray-800">{{ certificate.final_score }}%</p>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-6 border-t pt-6">
                            <div class="flex justify-center">
                                <button
                                    @click="reset"
                                    class="inline-flex items-center rounded-xl bg-gradient-to-r from-gray-600 to-gray-700 px-6 py-3 font-medium text-white transition-all duration-200 hover:shadow-lg"
                                >
                                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                        ></path>
                                    </svg>
                                    নতুন যাচাই
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="mt-8 rounded-2xl border border-[#5f5fcd]/10 bg-gradient-to-r from-[#5f5fcd]/5 to-[#d4a574]/5 p-6">
                        <h3 class="mb-3 text-lg font-semibold text-[#5f5fcd]">কীভাবে যাচাই করবেন?</h3>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start">
                                <span class="mt-2 mr-3 inline-block h-2 w-2 flex-shrink-0 rounded-full bg-[#d4a574]"></span>
                                আপনার সার্টিফিকেটের নিচের দিকে 12-সংখ্যার ভেরিফিকেশন কোড খুঁজুন
                            </li>
                            <li class="flex items-start">
                                <span class="mt-2 mr-3 inline-block h-2 w-2 flex-shrink-0 rounded-full bg-[#d4a574]"></span>
                                উপরের ফর্মে কোডটি লিখুন
                            </li>
                            <li class="flex items-start">
                                <span class="mt-2 mr-3 inline-block h-2 w-2 flex-shrink-0 rounded-full bg-[#d4a574]"></span>
                                "যাচাই করুন" বাটনে ক্লিক করুন
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </FrontendLayout>
</template>

<script setup>
import FrontendLayout from '@/layouts/FrontendLayout.vue';
import { router } from '@inertiajs/vue3';
import { reactive } from 'vue';

const props = defineProps({
    certificate: Object,
    errors: Object,
});

const form = reactive({
    verification_code: '',
    processing: false,
    errors: props.errors || {},
});

const verifyCode = () => {
    form.processing = true;
    form.errors = {};

    router.post(
        route('certificates.check'),
        {
            verification_code: form.verification_code,
        },
        {
            onSuccess: () => {
                form.processing = false;
            },
            onError: (errors) => {
                form.errors = errors;
                form.processing = false;
            },
        },
    );
};

const reset = () => {
    form.verification_code = '';
    form.errors = {};
    router.visit(route('certificates.verify'));
};
</script>
