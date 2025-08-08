<template>
    <FrontendLayout title="My Certificates">
        <div class="min-h-screen bg-gradient-to-br from-blue-50 to-green-50">
            <div class="container mx-auto px-4 py-8">
                <!-- Header -->
                <div class="mb-12 text-center">
                    <div class="mb-6 inline-flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-r from-blue-600 to-green-600">
                        <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                            ></path>
                        </svg>
                    </div>
                    <h1 class="mb-4 text-4xl font-bold text-gray-800">আমার সার্টিফিকেট</h1>
                    <p class="text-lg text-gray-600">আপনার অর্জিত সকল সার্টিফিকেট এখানে দেখুন</p>
                </div>

                <!-- Certificates -->
                <div v-if="certificates.length > 0" class="grid gap-6">
                    <div
                        v-for="certificate in certificates"
                        :key="certificate.id"
                        class="overflow-hidden rounded-xl bg-white shadow-lg transition-shadow duration-300 hover:shadow-xl"
                    >
                        <div class="relative">
                            <!-- Certificate Header -->
                            <div class="bg-gradient-to-r from-blue-600 to-green-600 px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h2 class="mb-1 text-xl font-bold text-white">{{ certificate.course_title }}</h2>
                                        <p class="text-blue-100">সার্টিফিকেট নং: {{ certificate.certificate_number }}</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="bg-opacity-20 rounded-lg bg-white px-3 py-1">
                                            <div class="font-semibold text-white">{{ certificate.final_score }}%</div>
                                            <div class="text-sm text-blue-100">স্কোর</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Certificate Body -->
                            <div class="p-6">
                                <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-gray-800">{{ certificate.completion_date }}</div>
                                        <div class="text-sm text-gray-500">সম্পন্নের তারিখ</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-gray-800">{{ certificate.issue_date }}</div>
                                        <div class="text-sm text-gray-500">ইস্যুর তারিখ</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="font-mono text-2xl font-bold text-blue-600">{{ certificate.verification_code }}</div>
                                        <div class="text-sm text-gray-500">ভেরিফিকেশন কোড</div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex flex-col gap-3 sm:flex-row">
                                    <button
                                        @click="downloadCertificate(certificate)"
                                        class="flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-white transition-colors hover:bg-blue-700"
                                    >
                                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                            ></path>
                                        </svg>
                                        ডাউনলোড করুন
                                    </button>

                                    <button
                                        @click="viewCertificate(certificate)"
                                        class="flex items-center justify-center rounded-lg bg-green-600 px-4 py-2 text-white transition-colors hover:bg-green-700"
                                    >
                                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            ></path>
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                            ></path>
                                        </svg>
                                        দেখুন
                                    </button>

                                    <button
                                        @click="shareCertificate(certificate)"
                                        class="flex items-center justify-center rounded-lg bg-purple-600 px-4 py-2 text-white transition-colors hover:bg-purple-700"
                                    >
                                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"
                                            ></path>
                                        </svg>
                                        শেয়ার করুন
                                    </button>

                                    <button
                                        @click="verifyCertificate(certificate)"
                                        class="flex items-center justify-center rounded-lg bg-yellow-600 px-4 py-2 text-white transition-colors hover:bg-yellow-700"
                                    >
                                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                            ></path>
                                        </svg>
                                        যাচাই করুন
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="py-12 text-center">
                    <div class="mb-6 inline-flex h-24 w-24 items-center justify-center rounded-full bg-gray-100">
                        <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                            ></path>
                        </svg>
                    </div>
                    <h2 class="mb-4 text-2xl font-bold text-gray-600">এখনো কোনো সার্টিফিকেট নেই</h2>
                    <p class="mx-auto mb-8 max-w-md text-gray-500">কোর্স সম্পন্ন করুন এবং কুইজে পাস করুন সার্টিফিকেট পাওয়ার জন্য</p>
                    <button @click="goToCourses" class="rounded-lg bg-blue-600 px-6 py-3 text-white transition-colors hover:bg-blue-700">
                        কোর্স দেখুন
                    </button>
                </div>

                <!-- Share Modal -->
                <div v-if="showShareModal" class="bg-opacity-50 fixed inset-0 z-50 flex items-center justify-center bg-black">
                    <div class="mx-4 w-full max-w-md rounded-lg bg-white p-6">
                        <h3 class="mb-4 text-xl font-bold">সার্টিফিকেট শেয়ার করুন</h3>
                        <p class="mb-4 text-gray-600">আপনার সার্টিফিকেটের লিংক কপি করুন:</p>
                        <div class="mb-4 flex items-center rounded-lg border">
                            <input ref="shareLink" :value="shareUrl" readonly class="flex-1 border-0 bg-gray-50 px-3 py-2 text-sm focus:ring-0" />
                            <button @click="copyShareLink" class="px-4 py-2 text-blue-600 transition-colors hover:bg-blue-50">কপি</button>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button
                                @click="showShareModal = false"
                                class="rounded-lg border border-gray-300 px-4 py-2 text-gray-600 hover:bg-gray-50"
                            >
                                বন্ধ করুন
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </FrontendLayout>
</template>

<script setup>
import FrontendLayout from '@/layouts/FrontendLayout.vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    certificates: Array,
});

const showShareModal = ref(false);
const shareUrl = ref('');
const shareLink = ref(null);

const downloadCertificate = (certificate) => {
    window.open(certificate.download_url, '_blank');
};

const viewCertificate = (certificate) => {
    window.open(route('certificates.view', certificate.verification_code), '_blank');
};

const shareCertificate = (certificate) => {
    shareUrl.value = route('certificates.view', certificate.verification_code);
    showShareModal.value = true;
};

const verifyCertificate = (certificate) => {
    router.visit(route('certificates.verify') + '?code=' + certificate.verification_code);
};

const goToCourses = () => {
    router.visit(route('frontend.courses.index'));
};

const copyShareLink = async () => {
    try {
        await navigator.clipboard.writeText(shareUrl.value);
        // You could add a toast notification here
        alert('লিংক কপি হয়েছে!');
    } catch (err) {
        // Fallback for older browsers
        shareLink.value?.select();
        document.execCommand('copy');
        alert('লিংক কপি হয়েছে!');
    }
};
</script>
