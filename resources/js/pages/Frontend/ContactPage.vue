<template>
    <FrontendLayout title="যোগাযোগ - ইকরা অনলাইন একাডেমি">
        <Head title="যোগাযোগ - ইকরা অনলাইন একাডেমি" />

        <!-- Main Content -->
        <div>
            <!-- Page Header -->
            <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-20">
                <!-- Background Pattern -->
                <div class="pattern-dots absolute inset-0 opacity-10"></div>

                <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <div class="mb-6 inline-flex items-center rounded-full border border-white/20 bg-white/10 px-4 py-2 backdrop-blur-sm">
                            <MailIcon class="mr-2 h-4 w-4 text-white" />
                            <span class="text-sm font-medium text-white">যোগাযোগ করুন</span>
                        </div>

                        <h1 class="mb-6 text-4xl font-bold text-white lg:text-6xl">
                            {{ contactData?.title || 'আমাদের সাথে যোগাযোগ করুন' }}
                        </h1>

                        <p class="mx-auto max-w-3xl text-xl leading-relaxed text-gray-300">
                            {{
                                contactData?.subtitle ||
                                'আমরা সবসময় আপনাদের প্রশ্ন ও পরামর্শের জন্য প্রস্তুত। যেকোনো সহায়তার জন্য আমাদের সাথে যোগাযোগ করতে দ্বিধা করবেন না।'
                            }}
                        </p>
                    </div>
                </div>
            </section>

            <!-- Contact Section -->
            <section class="py-20">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 gap-16 lg:grid-cols-2">
                        <!-- Contact Form -->
                        <div>
                            <div class="shadow-islamic rounded-2xl bg-white p-8">
                                <h2 class="mb-6 text-2xl font-bold text-gray-900">{{ contactData?.form_title || 'আমাদের বার্তা পাঠান' }}</h2>

                                <form @submit.prevent="submitForm" class="space-y-6">
                                    <!-- Name -->
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-gray-700">আপনার নাম *</label>
                                        <input
                                            v-model="form.name"
                                            type="text"
                                            required
                                            :disabled="isSubmitting"
                                            class="w-full rounded-lg border border-gray-200 px-4 py-3 focus:border-transparent focus:ring-2 focus:ring-[#5f5fcd] disabled:cursor-not-allowed disabled:bg-gray-100"
                                            placeholder="আপনার পূর্ণ নাম লিখুন"
                                            :class="{ 'border-red-500': errors.name }"
                                        />
                                        <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-gray-700">ইমেইল ঠিকানা *</label>
                                        <input
                                            v-model="form.email"
                                            type="email"
                                            required
                                            :disabled="isSubmitting"
                                            class="w-full rounded-lg border border-gray-200 px-4 py-3 focus:border-transparent focus:ring-2 focus:ring-[#5f5fcd] disabled:cursor-not-allowed disabled:bg-gray-100"
                                            placeholder="your.email@example.com"
                                            :class="{ 'border-red-500': errors.email }"
                                        />
                                        <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
                                    </div>

                                    <!-- Phone -->
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-gray-700">ফোন নম্বর</label>
                                        <input
                                            v-model="form.phone"
                                            type="tel"
                                            :disabled="isSubmitting"
                                            class="w-full rounded-lg border border-gray-200 px-4 py-3 focus:border-transparent focus:ring-2 focus:ring-[#5f5fcd] disabled:cursor-not-allowed disabled:bg-gray-100"
                                            placeholder="01750-469027"
                                            :class="{ 'border-red-500': errors.phone }"
                                        />
                                        <p v-if="errors.phone" class="mt-1 text-sm text-red-600">{{ errors.phone }}</p>
                                    </div>

                                    <!-- Subject -->
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-gray-700">বিষয় *</label>
                                        <select
                                            v-model="form.subject"
                                            required
                                            :disabled="isSubmitting"
                                            class="w-full rounded-lg border border-gray-200 px-4 py-3 focus:border-transparent focus:ring-2 focus:ring-[#5f5fcd] disabled:cursor-not-allowed disabled:bg-gray-100"
                                            :class="{ 'border-red-500': errors.subject }"
                                        >
                                            <option value="">বিষয় নির্বাচন করুন</option>
                                            <option v-for="subject in contactData?.subjects" :key="subject.value" :value="subject.value">
                                                {{ subject.label }}
                                            </option>
                                        </select>
                                        <p v-if="errors.subject" class="mt-1 text-sm text-red-600">{{ errors.subject }}</p>
                                    </div>

                                    <!-- Message -->
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-gray-700">আপনার বার্তা *</label>
                                        <textarea
                                            v-model="form.message"
                                            required
                                            rows="5"
                                            :disabled="isSubmitting"
                                            class="w-full rounded-lg border border-gray-200 px-4 py-3 focus:border-transparent focus:ring-2 focus:ring-[#5f5fcd] disabled:cursor-not-allowed disabled:bg-gray-100"
                                            placeholder="আপনার প্রশ্ন বা মন্তব্য বিস্তারিত লিখুন..."
                                            :class="{ 'border-red-500': errors.message }"
                                        ></textarea>
                                        <p v-if="errors.message" class="mt-1 text-sm text-red-600">{{ errors.message }}</p>
                                    </div>

                                    <!-- Submit Button -->
                                    <PrimaryButton type="submit" size="lg" variant="primary" full-width :loading="isSubmitting" :icon="SendIcon">
                                        {{ isSubmitting ? 'পাঠানো হচ্ছে...' : 'বার্তা পাঠান' }}
                                    </PrimaryButton>
                                </form>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="space-y-8">
                            <!-- Quick Contact -->
                            <div class="rounded-2xl bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] p-8 text-white">
                                <h3 class="mb-6 text-2xl font-bold">দ্রুত যোগাযোগ</h3>
                                <div class="space-y-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20">
                                            <PhoneIcon class="h-5 w-5" />
                                        </div>
                                        <div>
                                            <p class="font-medium">মোবাইল নম্বর</p>
                                            <p class="text-white/80">01750-469027</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Support Options -->
                            <div class="rounded-2xl bg-gray-50 p-8">
                                <h3 class="mb-6 text-xl font-bold text-gray-900">{{ contactData?.support_title || 'সহায়তার মাধ্যম' }}</h3>

                                <div class="space-y-4">
                                    <div
                                        v-for="support in contactData?.support_options"
                                        :key="support.type"
                                        class="flex items-center justify-between rounded-lg bg-white p-4 shadow-sm"
                                    >
                                        <div class="flex items-center space-x-3">
                                            <component :is="getSupportIcon(support.type)" class="h-5 w-5 text-[#5f5fcd]" />
                                            <span class="font-medium text-gray-900">{{ support.label }}</span>
                                        </div>
                                        <span v-if="support.status" :class="getStatusClass(support.status)" class="rounded-full px-2 py-1 text-sm">
                                            {{ support.status }}
                                        </span>
                                        <ChevronRightIcon v-else class="h-4 w-4 text-gray-400" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FAQ Section -->
            <section class="bg-gray-50 py-20">
                <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                    <div class="mb-12 text-center">
                        <h2 class="mb-4 text-3xl font-bold text-gray-900">{{ contactData?.faq_title || 'প্রায়শই জিজ্ঞাসিত প্রশ্ন' }}</h2>
                        <p class="text-lg text-gray-600">{{ contactData?.faq_subtitle || 'আপনার প্রশ্নের উত্তর এখানে পেতে পারেন' }}</p>
                    </div>

                    <!-- Loading State -->
                    <div v-if="dataLoading" class="py-12 text-center">
                        <div class="inline-flex items-center space-x-2 text-gray-500">
                            <div class="h-6 w-6 animate-spin rounded-full border-b-2 border-[#5f5fcd]"></div>
                            <span>লোড হচ্ছে...</span>
                        </div>
                    </div>

                    <!-- FAQ Content -->
                    <div v-else-if="contactData?.faqs && contactData.faqs.length > 0" class="space-y-4">
                        <div v-for="(faq, index) in contactData.faqs" :key="faq.id" class="rounded-lg border border-gray-200 bg-white shadow-sm">
                            <button @click="toggleFaq(index)" class="flex w-full items-center justify-between px-6 py-4 text-left">
                                <span class="font-medium text-gray-900">{{ faq.question }}</span>
                                <ChevronDownIcon :class="{ 'rotate-180': openFaq === index }" class="h-5 w-5 text-gray-500 transition-transform" />
                            </button>
                            <Transition name="faq-fade-slide">
                                <div v-show="openFaq === index" class="px-6 pb-4">
                                    <p class="text-gray-600">{{ faq.answer }}</p>
                                </div>
                            </Transition>
                        </div>
                    </div>

                    <!-- No FAQ Content -->
                    <div v-else class="py-12 text-center text-gray-500">
                        <p>এখনো কোনো প্রশ্নোত্তর যোগ করা হয়নি।</p>
                    </div>
                </div>
            </section>
        </div>
    </FrontendLayout>
</template>

<script setup lang="ts">
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue';
import { useToast } from '@/composables/useToast';
import FrontendLayout from '@/layouts/FrontendLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import {
    ChevronDownIcon,
    ChevronRightIcon,
    ClockIcon,
    HelpCircleIcon,
    MailIcon,
    MapPinIcon,
    MessageCircleIcon,
    PhoneIcon,
    SendIcon,
} from 'lucide-vue-next';
import { onMounted, ref } from 'vue';

// Interfaces
interface ContactData {
    title: string;
    subtitle: string;
    form_title: string;
    quick_contact_title: string;
    support_title: string;
    faq_title: string;
    faq_subtitle: string;
    subjects: Array<{
        value: string;
        label: string;
    }>;
    contact_info: Array<{
        type: string;
        label: string;
        value: string;
    }>;
    support_options: Array<{
        type: string;
        label: string;
        status?: string;
    }>;
    faqs: Array<{
        id: number;
        question: string;
        answer: string;
    }>;
}

// State
const contactData = ref<ContactData | null>(null);
const dataLoading = ref(true);

// Form state
const form = ref({
    name: '',
    email: '',
    phone: '',
    subject: '',
    message: '',
});

const errors = ref<Record<string, string>>({});
const isSubmitting = ref(false);

// FAQ state
const openFaq = ref<number | null>(null);

// Toast system
const toast = useToast();

// Methods
const loadContactData = async () => {
    dataLoading.value = true;
    try {
        await router.get(
            route('frontend.contact'),
            {},
            {
                preserveState: true,
                preserveScroll: true,
                only: ['contactData'],
            },
        );

        // Get updated data from page props
        const page = usePage();
        contactData.value = page.props.contactData as ContactData;
    } catch (err) {
        console.error('Error loading contact data:', err);
    } finally {
        dataLoading.value = false;
    }
};

const validateForm = () => {
    errors.value = {};

    if (!form.value.name.trim()) {
        errors.value.name = 'নাম প্রয়োজন';
    }

    if (!form.value.email.trim()) {
        errors.value.email = 'ইমেইল প্রয়োজন';
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.value.email)) {
        errors.value.email = 'সঠিক ইমেইল ঠিকানা দিন';
    }

    if (form.value.phone && !/^(\+880|880|0)?1[3-9]\d{8}$/.test(form.value.phone.replace(/\s/g, ''))) {
        errors.value.phone = 'সঠিক বাংলাদেশী ফোন নম্বর দিন';
    }

    if (!form.value.subject) {
        errors.value.subject = 'বিষয় নির্বাচন করুন';
    }

    if (!form.value.message.trim()) {
        errors.value.message = 'বার্তা প্রয়োজন';
    } else if (form.value.message.trim().length < 10) {
        errors.value.message = 'বার্তা কমপক্ষে ১০ অক্ষরের হতে হবে';
    }

    return Object.keys(errors.value).length === 0;
};

const sanitizeInput = (input: string): string => {
    if (!input) return '';
    // Remove HTML tags and dangerous characters
    return input
        .replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '')
        .replace(/<[^>]*>/g, '')
        .replace(/javascript:/gi, '')
        .replace(/on\w+=/gi, '')
        .trim();
};

const submitForm = async () => {
    if (!validateForm()) {
        toast.warning('অনুগ্রহ করে সব তথ্য সঠিকভাবে পূরণ করুন।');
        return;
    }

    isSubmitting.value = true;
    errors.value = {}; // Reset errors

    // Sanitize form data before submission
    const sanitizedForm = {
        name: sanitizeInput(form.value.name),
        email: sanitizeInput(form.value.email),
        phone: sanitizeInput(form.value.phone),
        subject: sanitizeInput(form.value.subject),
        message: sanitizeInput(form.value.message),
    };

    try {
        await router.post(route('frontend.contact.submit'), sanitizedForm, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                // Reset form
                form.value = {
                    name: '',
                    email: '',
                    phone: '',
                    subject: '',
                    message: '',
                };

                toast.success({
                    title: 'বার্তা সফলভাবে পাঠানো হয়েছে!',
                    message: 'আমরা শীঘ্রই আপনার সাথে যোগাযোগ করবো।',
                    duration: 5000,
                });
            },
            onError: (errorData: any) => {
                console.error('Contact form submission error:', errorData);

                // Handle specific field errors
                if (errorData.name) {
                    errors.value.name = errorData.name;
                    toast.error({ title: 'নাম ত্রুটি', message: errorData.name });
                } else if (errorData.email) {
                    errors.value.email = errorData.email;
                    toast.error({ title: 'ইমেইল ত্রুটি', message: errorData.email });
                } else if (errorData.phone) {
                    errors.value.phone = errorData.phone;
                    toast.error({ title: 'ফোন ত্রুটি', message: errorData.phone });
                } else if (errorData.subject) {
                    errors.value.subject = errorData.subject;
                    toast.error({ title: 'বিষয় ত্রুটি', message: errorData.subject });
                } else if (errorData.message) {
                    errors.value.message = errorData.message;
                    toast.error({ title: 'বার্তা ত্রুটি', message: errorData.message });
                } else if (errorData.error) {
                    toast.error({ title: 'ত্রুটি', message: errorData.error });
                } else {
                    // Copy all errors to reactive errors object
                    errors.value = { ...errorData };
                    toast.error({
                        title: 'ফর্মে ত্রুটি রয়েছে',
                        message: 'অনুগ্রহ করে সব তথ্য সঠিকভাবে পূরণ করুন।',
                        duration: 7000,
                    });
                }
            },
            onFinish: () => {
                isSubmitting.value = false;
            },
        });
    } catch (error: any) {
        console.error('Unexpected contact form error:', error);

        // Handle network or unexpected errors
        toast.error({
            title: 'বার্তা পাঠানো ব্যর্থ!',
            message: 'দুঃখিত, একটি সমস্যা হয়েছে। ইন্টারনেট সংযোগ যাচাই করে পুনরায় চেষ্টা করুন।',
            duration: 8000,
        });
        isSubmitting.value = false;
    }
};

const toggleFaq = (index: number) => {
    openFaq.value = openFaq.value === index ? null : index;
};

const getContactIcon = (type: string) => {
    const icons: Record<string, any> = {
        phone: PhoneIcon,
        email: MailIcon,
        address: MapPinIcon,
        clock: ClockIcon,
    };
    return icons[type] || MailIcon;
};

const getSupportIcon = (type: string) => {
    const icons: Record<string, any> = {
        chat: MessageCircleIcon,
        phone: PhoneIcon,
        help: HelpCircleIcon,
    };
    return icons[type] || HelpCircleIcon;
};

const getStatusClass = (status: string) => {
    const classes: Record<string, string> = {
        অনলাইন: 'bg-green-100 text-green-800',
        '২৪/৭': 'bg-blue-100 text-blue-800',
        অফলাইন: 'bg-gray-100 text-gray-800',
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
};

// Initialize on mount
onMounted(() => {
    loadContactData();
});
</script>

<style scoped>
/* Custom styles for the contact page */
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

/* Smooth transitions for FAQ */
.transition-transform {
    transition: transform 0.2s ease-in-out;
}

.rotate-180 {
    transform: rotate(180deg);
}

/* FAQ animation */
.faq-fade-slide-enter-active,
.faq-fade-slide-leave-active {
    transition:
        opacity 0.35s cubic-bezier(0.4, 0, 0.2, 1),
        transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    will-change: opacity, transform;
}
.faq-fade-slide-enter-from,
.faq-fade-slide-leave-to {
    opacity: 0;
    transform: translateY(16px);
}
.faq-fade-slide-enter-to,
.faq-fade-slide-leave-from {
    opacity: 1;
    transform: translateY(0);
}
</style>
