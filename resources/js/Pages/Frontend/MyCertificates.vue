<template>
    <FrontendLayout title="My Certificates - Iqra Online Academy">
        <Head title="My Certificates" />

        <!-- Page Header -->
        <section class="bg-gradient-to-br from-[#2d5a27] via-[#356a2f] to-[#2d5a27] py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-white sm:text-5xl">My Certificates</h1>
                    <p class="mt-4 text-xl text-green-100">
                        Your achievements and certifications in Islamic education
                    </p>
                </div>
            </div>
        </section>

        <!-- Certificates Content -->
        <section class="bg-gray-50 py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div v-if="certificates.length === 0" class="py-12 text-center">
                    <div class="flex flex-col items-center justify-center space-y-4">
                        <AwardIcon class="h-24 w-24 text-gray-400" />
                        <h3 class="text-2xl font-medium text-gray-900">No certificates yet</h3>
                        <p class="text-gray-600 max-w-md">
                            Complete courses to earn certificates that recognize your achievements in Islamic education.
                        </p>
                        <PrimaryButton
                            @click="goToCourses"
                            variant="primary"
                            size="md"
                            :icon="BookOpenIcon"
                        >
                            Browse Courses
                        </PrimaryButton>
                    </div>
                </div>

                <div v-else class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="certificate in certificates"
                        :key="certificate.id"
                        class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-lg transition-all duration-300 hover:shadow-xl"
                    >
                        <!-- Certificate Header -->
                        <div class="bg-gradient-to-r from-[#d4a574] to-[#b8935e] px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-white/20">
                                    <AwardIcon class="h-6 w-6 text-white" />
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-white">Certificate of Achievement</h3>
                                    <p class="text-sm text-white/90">{{ certificate.certificate_number }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Certificate Body -->
                        <div class="p-6">
                            <h4 class="mb-2 text-xl font-bold text-gray-900">{{ certificate.course_title }}</h4>
                            
                            <div class="mb-4 space-y-2">
                                <div class="flex items-center text-sm text-gray-600">
                                    <CalendarIcon class="mr-2 h-4 w-4" />
                                    <span>Completed: {{ certificate.completion_date }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <CalendarIcon class="mr-2 h-4 w-4" />
                                    <span>Issued: {{ certificate.issue_date }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <ShieldCheckIcon class="mr-2 h-4 w-4" />
                                    <span>Verification: {{ certificate.verification_code }}</span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex space-x-3">
                                <PrimaryButton
                                    @click="downloadCertificate(certificate)"
                                    variant="primary"
                                    size="sm"
                                    :icon="DownloadIcon"
                                    class="flex-1 justify-center"
                                >
                                    Download
                                </PrimaryButton>
                                <PrimaryButton
                                    @click="verifyCertificate(certificate.verification_code)"
                                    variant="outline"
                                    size="sm"
                                    :icon="ExternalLinkIcon"
                                    class="flex-1 justify-center"
                                >
                                    Verify
                                </PrimaryButton>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Certificate Information -->
                <div v-if="certificates.length > 0" class="mt-16 rounded-2xl border border-gray-200 bg-white p-8 shadow-lg">
                    <h3 class="mb-4 text-2xl font-bold text-gray-900">About Your Certificates</h3>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <h4 class="mb-2 font-semibold text-gray-900">Certificate Verification</h4>
                            <p class="text-gray-600">
                                Each certificate comes with a unique verification code. Anyone can verify the authenticity 
                                of your certificate using this code on our verification page.
                            </p>
                        </div>
                        <div>
                            <h4 class="mb-2 font-semibold text-gray-900">Digital & Print Ready</h4>
                            <p class="text-gray-600">
                                Your certificates are available in high-quality PDF format, suitable for both digital 
                                sharing and professional printing.
                            </p>
                        </div>
                        <div>
                            <h4 class="mb-2 font-semibold text-gray-900">Lifetime Access</h4>
                            <p class="text-gray-600">
                                Once earned, your certificates remain accessible in your account permanently. You can 
                                download them anytime you need.
                            </p>
                        </div>
                        <div>
                            <h4 class="mb-2 font-semibold text-gray-900">Professional Recognition</h4>
                            <p class="text-gray-600">
                                Our certificates are recognized credentials that demonstrate your commitment to Islamic 
                                education and knowledge.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </FrontendLayout>
</template>

<script setup lang="ts">
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue';
import { useToast } from '@/composables/useToast';
import FrontendLayout from '@/layouts/FrontendLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import {
    AwardIcon,
    BookOpenIcon,
    CalendarIcon,
    DownloadIcon,
    ExternalLinkIcon,
    ShieldCheckIcon,
} from 'lucide-vue-next';

// Props
interface Props {
    certificates: Array<{
        id: number;
        certificate_number: string;
        course_title: string;
        completion_date: string;
        issue_date: string;
        verification_code: string;
        download_url: string;
    }>;
}

const props = defineProps<Props>();

// Composables
const { success, error } = useToast();

// Methods
const downloadCertificate = (certificate: any) => {
    if (certificate.download_url) {
        // Create a temporary link and trigger download
        const link = document.createElement('a');
        link.href = certificate.download_url;
        link.download = `Certificate_${certificate.certificate_number}.pdf`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        success('Certificate download started!');
    } else {
        error('Certificate download URL not available. Please contact support.');
    }
};

const verifyCertificate = (verificationCode: string) => {
    router.visit(route('certificates.verify'), {
        data: { verification_code: verificationCode },
        method: 'get'
    });
};

const goToCourses = () => {
    router.visit(route('frontend.courses.index'));
};
</script>