<template>
    <FrontendLayout title="Student Dashboard - Iqra Online Academy">
        <Head title="Student Dashboard" />

        <!-- Dashboard Content -->
        <section class="bg-gray-50 py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-8 lg:grid-cols-4">
                    <!-- Sidebar Navigation -->
                    <div class="lg:col-span-1">
                        <div class="sticky top-8 rounded-2xl border border-gray-100 bg-white p-6 shadow-lg">
                            <h3 class="mb-6 text-xl font-bold text-gray-900">Dashboard Menu</h3>

                            <nav class="space-y-2">
                                <button
                                    v-for="item in navigationItems"
                                    :key="item.id"
                                    @click="activeSection = item.id"
                                    :class="[
                                        'flex w-full items-center space-x-3 rounded-lg px-4 py-3 text-left transition-colors duration-200',
                                        activeSection === item.id ? 'bg-[#5f5fcd] text-white' : 'text-gray-700 hover:bg-gray-100',
                                    ]"
                                >
                                    <component :is="item.icon" class="h-5 w-5" />
                                    <span>{{ item.name }}</span>
                                </button>
                            </nav>

                            <!-- Islamic Quote -->
                            <div class="mt-8 rounded-lg border-l-4 border-[#2d5a27] bg-[#2d5a27]/5 p-4">
                                <p class="mb-2 text-sm text-gray-600 italic">
                                    "Whoever seeks knowledge, Allah will make the path to Paradise easy for them."
                                </p>
                                <p class="text-xs text-gray-500">- Hadith Sharif</p>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="lg:col-span-3">
                        <!-- Enrolled Courses Section -->
                        <div v-if="activeSection === 'courses'" class="space-y-8">
                            <div class="flex items-center justify-between">
                                <h2 class="text-2xl font-bold text-gray-900">My Courses</h2>
                                <PrimaryButton @click="goToCourses" variant="outline" size="md" :icon="PlusIcon"> Find New Courses </PrimaryButton>
                            </div>

                            <div v-if="enrollments.length === 0" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center space-y-4">
                                    <BookOpenIcon class="h-16 w-16 text-gray-400" />
                                    <h3 class="text-lg font-medium text-gray-900">No courses enrolled yet</h3>
                                    <p class="text-gray-600">Start your learning journey by enrolling in a course.</p>
                                    <PrimaryButton @click="goToCourses" variant="primary" size="md" :icon="PlusIcon"> Browse Courses </PrimaryButton>
                                </div>
                            </div>

                            <div v-else class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div
                                    v-for="enrollment in enrollments"
                                    :key="enrollment.id"
                                    class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-lg transition-shadow duration-300 hover:shadow-xl"
                                >
                                    <!-- Status Badge -->
                                    <div class="relative">
                                        <div class="h-48 w-full">
                                            <img
                                                v-if="isValidImage(enrollment.course.thumbnail_image)"
                                                :src="enrollment.course.thumbnail_image"
                                                :alt="enrollment.course.title"
                                                class="h-full w-full object-cover"
                                            />
                                            <CoursePlaceholder v-else text="Course Image" class="h-full w-full" />
                                        </div>

                                        <!-- Payment Status Badge -->
                                        <div class="absolute top-4 right-4">
                                            <span
                                                v-if="enrollment.payment_status === 'pending'"
                                                class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-800"
                                            >
                                                Payment Pending
                                            </span>
                                            <span
                                                v-else-if="enrollment.payment_status === 'completed'"
                                                class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800"
                                            >
                                                Verified
                                            </span>
                                            <span
                                                v-else-if="enrollment.payment_status === 'failed'"
                                                class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-800"
                                            >
                                                Payment Failed
                                            </span>
                                        </div>
                                    </div>

                                    <div class="p-6">
                                        <h3 class="mb-2 text-xl font-semibold text-gray-900">{{ enrollment.course.title }}</h3>
                                        <p class="mb-2 text-gray-600">{{ enrollment.course.instructor }}</p>
                                        <p v-if="enrollment.course.category" class="mb-4 text-sm text-gray-500">
                                            {{ enrollment.course.category }}
                                        </p>

                                        <!-- Payment Status Message -->
                                        <div
                                            v-if="enrollment.payment_status === 'pending'"
                                            class="mb-4 rounded-lg border border-yellow-200 bg-yellow-50 p-3"
                                        >
                                            <p class="text-sm text-yellow-800">
                                                <span class="font-medium">Payment Verification Pending:</span>
                                                Your payment is being verified by our admin team. You'll be able to access the course once verified.
                                            </p>
                                        </div>

                                        <div
                                            v-else-if="enrollment.payment_status === 'failed'"
                                            class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3"
                                        >
                                            <p class="text-sm text-red-800">
                                                <span class="font-medium">Payment Failed:</span>
                                                Please contact support to resolve payment issues.
                                            </p>
                                        </div>

                                        <!-- Progress Bar (only show for verified enrollments) -->
                                        <div v-if="enrollment.payment_status === 'completed'" class="mb-4">
                                            <div class="mb-2 flex justify-between text-sm text-gray-600">
                                                <span>Progress</span>
                                                <span>{{ enrollment.progress_percentage }}%</span>
                                            </div>
                                            <div class="h-2 w-full rounded-full bg-gray-200">
                                                <div
                                                    class="h-2 rounded-full bg-[#2d5a27] transition-all duration-300"
                                                    :style="{ width: enrollment.progress_percentage + '%' }"
                                                ></div>
                                            </div>
                                        </div>

                                        <div class="flex space-x-3">
                                            <PrimaryButton
                                                v-if="enrollment.payment_status === 'completed'"
                                                @click="goToLearning(enrollment.course.slug)"
                                                variant="primary"
                                                size="sm"
                                                :icon="PlayIcon"
                                                class="flex-1 justify-center"
                                            >
                                                {{ enrollment.progress_percentage === 0 ? 'Start Learning' : 'Continue' }}
                                            </PrimaryButton>

                                            <PrimaryButton
                                                v-else
                                                @click="handlePendingCourseAccess(enrollment)"
                                                variant="secondary"
                                                size="sm"
                                                :icon="LockIcon"
                                                class="flex-1 justify-center"
                                                disabled
                                            >
                                                {{ enrollment.payment_status === 'pending' ? 'Awaiting Verification' : 'Access Restricted' }}
                                            </PrimaryButton>

                                            <PrimaryButton
                                                @click="goToCourseDetails(enrollment.course.slug)"
                                                variant="outline"
                                                size="sm"
                                                :icon="BookOpenIcon"
                                                class="flex-1 justify-center"
                                            >
                                                Details
                                            </PrimaryButton>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Section -->
                        <div v-if="activeSection === 'profile'" class="space-y-8">
                            <h2 class="text-2xl font-bold text-gray-900">Profile Settings</h2>

                            <!-- Profile Information -->
                            <div class="rounded-2xl border border-gray-100 bg-white p-8 shadow-lg">
                                <h3 class="mb-6 text-lg font-semibold text-gray-900">Personal Information</h3>
                                <form @submit.prevent="updateProfile" class="space-y-6">
                                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-gray-700">Full Name</label>
                                            <input
                                                v-model="profileForm.full_name"
                                                type="text"
                                                class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-transparent focus:ring-2 focus:ring-[#5f5fcd]"
                                                :class="{ 'border-red-500': profileForm.errors.full_name }"
                                            />
                                            <div v-if="profileForm.errors.full_name" class="mt-1 text-sm text-red-600">
                                                {{ profileForm.errors.full_name }}
                                            </div>
                                        </div>

                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-gray-700">Email</label>
                                            <input
                                                :value="student.email"
                                                type="email"
                                                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-600"
                                                disabled
                                            />
                                            <p class="mt-1 text-xs text-gray-500">Email cannot be changed</p>
                                        </div>

                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-gray-700">Phone Number</label>
                                            <input
                                                v-model="profileForm.phone"
                                                type="tel"
                                                class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-transparent focus:ring-2 focus:ring-[#5f5fcd]"
                                                :class="{ 'border-red-500': profileForm.errors.phone }"
                                            />
                                            <div v-if="profileForm.errors.phone" class="mt-1 text-sm text-red-600">
                                                {{ profileForm.errors.phone }}
                                            </div>
                                        </div>

                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-gray-700">Date of Birth</label>
                                            <input
                                                v-model="profileForm.date_of_birth"
                                                type="date"
                                                class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-transparent focus:ring-2 focus:ring-[#5f5fcd]"
                                                :class="{ 'border-red-500': profileForm.errors.date_of_birth }"
                                            />
                                            <div v-if="profileForm.errors.date_of_birth" class="mt-1 text-sm text-red-600">
                                                {{ profileForm.errors.date_of_birth }}
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-gray-700">Address</label>
                                        <textarea
                                            v-model="profileForm.address"
                                            rows="3"
                                            class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-transparent focus:ring-2 focus:ring-[#5f5fcd]"
                                            :class="{ 'border-red-500': profileForm.errors.address }"
                                        ></textarea>
                                        <div v-if="profileForm.errors.address" class="mt-1 text-sm text-red-600">
                                            {{ profileForm.errors.address }}
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-gray-700">City</label>
                                            <input
                                                v-model="profileForm.city"
                                                type="text"
                                                class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-transparent focus:ring-2 focus:ring-[#5f5fcd]"
                                                :class="{ 'border-red-500': profileForm.errors.city }"
                                            />
                                            <div v-if="profileForm.errors.city" class="mt-1 text-sm text-red-600">
                                                {{ profileForm.errors.city }}
                                            </div>
                                        </div>

                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-gray-700">District</label>
                                            <input
                                                v-model="profileForm.district"
                                                type="text"
                                                class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-transparent focus:ring-2 focus:ring-[#5f5fcd]"
                                                :class="{ 'border-red-500': profileForm.errors.district }"
                                            />
                                            <div v-if="profileForm.errors.district" class="mt-1 text-sm text-red-600">
                                                {{ profileForm.errors.district }}
                                            </div>
                                        </div>

                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-gray-700">Country</label>
                                            <input
                                                v-model="profileForm.country"
                                                type="text"
                                                class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-transparent focus:ring-2 focus:ring-[#5f5fcd]"
                                                :class="{ 'border-red-500': profileForm.errors.country }"
                                            />
                                            <div v-if="profileForm.errors.country" class="mt-1 text-sm text-red-600">
                                                {{ profileForm.errors.country }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex space-x-4 border-t border-gray-200 pt-6">
                                        <PrimaryButton type="submit" variant="primary" size="md" :icon="SaveIcon" :disabled="profileForm.processing">
                                            {{ profileForm.processing ? 'Updating...' : 'Update Profile' }}
                                        </PrimaryButton>
                                    </div>
                                </form>
                            </div>

                            <!-- Change Password Section -->
                            <div class="rounded-2xl border border-gray-100 bg-white p-8 shadow-lg">
                                <h3 class="mb-6 text-lg font-semibold text-gray-900">Change Password</h3>
                                <form @submit.prevent="changePassword" class="space-y-6">
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-gray-700">Current Password</label>
                                        <input
                                            v-model="passwordForm.current_password"
                                            type="password"
                                            class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-transparent focus:ring-2 focus:ring-[#5f5fcd]"
                                            :class="{ 'border-red-500': passwordForm.errors.current_password }"
                                            required
                                        />
                                        <div v-if="passwordForm.errors.current_password" class="mt-1 text-sm text-red-600">
                                            {{ passwordForm.errors.current_password }}
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-gray-700">New Password</label>
                                            <input
                                                v-model="passwordForm.password"
                                                type="password"
                                                class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-transparent focus:ring-2 focus:ring-[#5f5fcd]"
                                                :class="{ 'border-red-500': passwordForm.errors.password }"
                                                required
                                            />
                                            <div v-if="passwordForm.errors.password" class="mt-1 text-sm text-red-600">
                                                {{ passwordForm.errors.password }}
                                            </div>
                                        </div>

                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-gray-700">Confirm New Password</label>
                                            <input
                                                v-model="passwordForm.password_confirmation"
                                                type="password"
                                                class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-transparent focus:ring-2 focus:ring-[#5f5fcd]"
                                                :class="{ 'border-red-500': passwordForm.errors.password_confirmation }"
                                                required
                                            />
                                            <div v-if="passwordForm.errors.password_confirmation" class="mt-1 text-sm text-red-600">
                                                {{ passwordForm.errors.password_confirmation }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex space-x-4 border-t border-gray-200 pt-6">
                                        <PrimaryButton type="submit" variant="primary" size="md" :icon="LockIcon" :disabled="passwordForm.processing">
                                            {{ passwordForm.processing ? 'Updating...' : 'Change Password' }}
                                        </PrimaryButton>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Certificates Section -->
                        <div v-if="activeSection === 'certificates'" class="space-y-8">
                            <h2 class="text-2xl font-bold text-gray-900">Certificates</h2>

                            <div v-if="certificates.length === 0" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center space-y-4">
                                    <AwardIcon class="h-16 w-16 text-gray-400" />
                                    <h3 class="text-lg font-medium text-gray-900">No certificates yet</h3>
                                    <p class="text-gray-600">Complete courses to earn certificates.</p>
                                </div>
                            </div>

                            <div v-else class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div
                                    v-for="certificate in certificates"
                                    :key="certificate.id"
                                    class="rounded-2xl border border-gray-100 bg-white p-6 shadow-lg"
                                >
                                    <div class="mb-4 flex items-center space-x-4">
                                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-[#d4a574]/10">
                                            <AwardIcon class="h-8 w-8 text-[#d4a574]" />
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ certificate.course }}</h3>
                                            <p class="text-gray-600">{{ certificate.date }}</p>
                                        </div>
                                    </div>

                                    <PrimaryButton
                                        @click="downloadCertificate(certificate)"
                                        variant="outline"
                                        size="sm"
                                        :icon="DownloadIcon"
                                        class="w-full justify-center"
                                    >
                                        Download Certificate
                                    </PrimaryButton>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Section -->
                        <div v-if="activeSection === 'progress'" class="space-y-8">
                            <h2 class="text-2xl font-bold text-gray-900">Progress Report</h2>

                            <div class="rounded-2xl border border-gray-100 bg-white p-8 shadow-lg">
                                <!-- Recent Activity -->
                                <h3 class="mb-4 text-lg font-semibold text-gray-900">Recent Activity</h3>
                                <div v-if="recentActivities.length === 0" class="py-8 text-center">
                                    <p class="text-gray-500">No recent activity to show.</p>
                                </div>
                                <div v-else class="space-y-4">
                                    <div
                                        v-for="activity in recentActivities"
                                        :key="activity.id"
                                        class="flex items-center space-x-4 rounded-lg bg-gray-50 p-4"
                                    >
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#5f5fcd]/10">
                                            <CheckIcon class="h-5 w-5 text-[#5f5fcd]" />
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900">{{ activity.title }}</p>
                                            <p class="text-sm text-gray-600">{{ activity.course }}</p>
                                        </div>
                                        <div class="text-sm text-gray-500">{{ activity.date }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </FrontendLayout>
</template>

<script setup lang="ts">
import CoursePlaceholder from '@/components/Frontend/CoursePlaceholder.vue';
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue';
import { useToast } from '@/composables/useToast';
import FrontendLayout from '@/layouts/FrontendLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { useIntervalFn } from '@vueuse/core';
import {
    AwardIcon,
    BookOpenIcon,
    CheckIcon,
    DownloadIcon,
    LockIcon,
    PlayIcon,
    PlusIcon,
    SaveIcon,
    SettingsIcon,
    TrendingUpIcon,
} from 'lucide-vue-next';
import { computed, onUnmounted, ref, watch } from 'vue';

// Props
interface Props {
    student?: {
        id: number;
        full_name: string;
        email: string;
        phone?: string;
        profile_picture?: string;
        date_of_birth?: string;
        address?: string;
        city?: string;
        district?: string;
        country?: string;
        is_active: boolean;
        is_verified: boolean;
    };
    enrollments?: Array<{
        id: number;
        course: {
            id: number;
            title: string;
            slug: string;
            thumbnail_image?: string;
            category?: string;
            instructor?: string;
        };
        enrolled_at: string;
        enrollment_type: string;
        payment_status: string;
        progress_percentage: number;
        lessons_completed: number;
        is_completed: boolean;
        is_active: boolean;
    }>;
    recentActivities?: Array<{
        id: number;
        title: string;
        course: string;
        date: string;
        type: string;
    }>;
    certificates?: Array<{
        id: number;
        course: string;
        date: string;
        course_slug: string;
    }>;
    stats?: {
        total_enrollments: number;
        active_enrollments: number;
        completed_courses: number;
        total_lessons_completed: number;
        total_study_hours: number;
        average_progress: number;
    };
}

const props = defineProps<Props>();

// Get page for deferred props
const page = usePage();

// Toast composable
const { success, error } = useToast();

// Active section
const activeSection = ref('courses');

// Reactive stats that will be updated via deferred props
const stats = ref({
    total_enrollments: 0,
    active_enrollments: 0,
    completed_courses: 0,
    total_lessons_completed: 0,
    total_study_hours: 0,
    average_progress: 0,
});

// Reactive activities and certificates
const recentActivities = ref<any[]>([]);
const certificates = ref<any[]>([]);

// Watch for deferred props updates
watch(
    () => page.props.stats,
    (newStats) => {
        if (newStats) {
            stats.value = newStats as any;
        }
    },
);

watch(
    () => page.props.recentActivities,
    (newActivities) => {
        if (newActivities) {
            recentActivities.value = newActivities as any[];
        }
    },
);

watch(
    () => page.props.certificates,
    (newCertificates) => {
        if (newCertificates) {
            certificates.value = newCertificates as any[];
        }
    },
);

// Navigation items
const navigationItems = [
    { id: 'courses', name: 'My Courses', icon: BookOpenIcon },
    { id: 'profile', name: 'Profile', icon: SettingsIcon },
    { id: 'progress', name: 'Progress', icon: TrendingUpIcon },
    { id: 'certificates', name: 'Certificates', icon: AwardIcon },
];

// Default values for optional props
const student = computed(
    () =>
        props.student || {
            id: 0,
            full_name: 'Student',
            email: 'student@example.com',
            phone: '',
            profile_picture: '',
            date_of_birth: '',
            address: '',
            city: '',
            district: '',
            country: '',
            is_active: false,
            is_verified: false,
        },
);

const enrollments = computed(() => props.enrollments || []);

// Profile form
const profileForm = useForm({
    full_name: student.value.full_name,
    phone: student.value.phone || '',
    date_of_birth: student.value.date_of_birth || '',
    address: student.value.address || '',
    city: student.value.city || '',
    district: student.value.district || '',
    country: student.value.country || '',
});

// Password form
const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

// Methods
const goToLearning = (courseSlug: string) => {
    router.visit(route('frontend.learning.show', courseSlug));
};

const goToCourses = () => {
    router.visit(route('frontend.courses.index'));
};

const goToCourseDetails = (courseSlug: string) => {
    router.visit(`/courses/${courseSlug}`);
};

const updateProfile = () => {
    profileForm.patch(route('frontend.student.profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            success('Profile updated successfully.');
        },
        onError: () => {
            error('Failed to update profile. Please check the form and try again.');
        },
    });
};

const changePassword = () => {
    passwordForm.patch(route('frontend.student.password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            success('Password changed successfully.');
            passwordForm.reset();
        },
        onError: () => {
            error('Failed to change password. Please check your current password and try again.');
        },
    });
};

const downloadCertificate = (certificate: any) => {
    // Implement certificate download functionality
};

const handlePendingCourseAccess = (enrollment: any) => {
    if (enrollment.payment_status === 'pending') {
        error('আপনার পেমেন্ট যাচাই হওয়ার পর এই কোর্সটি অ্যাক্সেস করতে পারবেন। অনুগ্রহ করে অপেক্ষা করুন।');
    } else if (enrollment.payment_status === 'failed') {
        error('পেমেন্ট সমস্যা সমাধানের জন্য অনুগ্রহ করে সাপোর্ট টিমের সাথে যোগাযোগ করুন।');
    } else {
        error('এই মুহূর্তে কোর্সটি অ্যাক্সেস করা যাচ্ছে না। পরে আবার চেষ্টা করুন।');
    }
};

// Utility function to check for a valid image
const isValidImage = (src: string | undefined | null): boolean => {
    return !!src && typeof src === 'string' && src.trim() !== '';
};

// Polling for payment status updates
const hasPendingPayments = computed(() => {
    return enrollments.value.some((enrollment) => enrollment.payment_status === 'pending' || enrollment.payment_status === 'processing');
});

// Poll payment status every 30 seconds if there are pending payments
const { pause: pausePolling, resume: resumePolling } = useIntervalFn(
    () => {
        if (hasPendingPayments.value) {
            router.reload({ only: ['enrollments'] });
        }
    },
    30000,
    { immediate: false },
);

// Start/stop polling based on pending payments
watch(
    hasPendingPayments,
    (hasPending) => {
        if (hasPending) {
            resumePolling();
        } else {
            pausePolling();
        }
    },
    { immediate: true },
);

// Cleanup polling on unmount
onUnmounted(() => {
    pausePolling();
});
</script>
