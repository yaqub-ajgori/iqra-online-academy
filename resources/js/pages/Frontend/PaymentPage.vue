<template>
    <FrontendLayout title="পেমেন্ট - ইকরা অনলাইন একাডেমি">
        <Head :title="`পেমেন্ট - ${course?.title || 'কোর্স'}`" />

        <!-- Main Payment Interface -->
        <div>
            <!-- Payment Header -->
            <section class="border-b border-gray-100 bg-gradient-to-br from-[#5f5fcd]/5 via-white to-[#2d5a27]/3 py-8">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <h1 class="mb-4 text-3xl font-bold text-gray-900">কোর্স এনরোলমেন্ট</h1>
                        <p class="text-lg text-gray-600">আপনার শিক্ষার যাত্রা শুরু করুন</p>
                    </div>
                </div>
            </section>

            <!-- Payment Content -->
            <section class="bg-gray-50 py-12">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 gap-12 lg:grid-cols-3">
                        <!-- Payment Form -->
                        <div class="space-y-8 lg:col-span-2">
                            <!-- Payment Methods -->
                            <div class="rounded-2xl border border-gray-100 bg-white p-8 shadow-lg">
                                <h2 class="mb-6 flex items-center text-2xl font-bold text-gray-900">
                                    <CreditCardIcon class="mr-3 h-6 w-6 text-[#2d5a27]" />
                                    পেমেন্ট পদ্ধতি
                                </h2>

                                <div class="space-y-4">
                                    <!-- Mobile Banking -->
                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                        <label
                                            v-for="method in mobileBankingMethods"
                                            :key="method.id"
                                            :class="[
                                                'relative flex cursor-pointer items-center rounded-xl border-2 p-4 transition-all duration-200',
                                                selectedPaymentMethod === method.id
                                                    ? 'border-[#5f5fcd] bg-[#5f5fcd]/5'
                                                    : 'border-gray-200 hover:border-[#5f5fcd]/30',
                                            ]"
                                        >
                                            <input
                                                v-model="selectedPaymentMethod"
                                                :value="method.id"
                                                type="radio"
                                                :disabled="form.processing"
                                                class="sr-only"
                                            />
                                            <div class="flex flex-1 items-center">
                                                <!-- Custom Payment Method Icons -->
                                                <div
                                                    class="mr-4 flex h-12 w-12 items-center justify-center rounded-lg text-lg font-bold text-white"
                                                    :style="{ backgroundColor: method.color }"
                                                >
                                                    <template v-if="method.id === 'bkash'">
                                                        <!-- bKash Icon (Mobile/Smartphone) -->
                                                        <svg
                                                            class="h-7 w-7 text-white"
                                                            viewBox="0 0 24 24"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            stroke-width="2.5"
                                                        >
                                                            <rect x="5" y="2" width="14" height="20" rx="2" ry="2" />
                                                            <line x1="12" y1="18" x2="12.01" y2="18" />
                                                        </svg>
                                                    </template>
                                                    <template v-else-if="method.id === 'nagad'">
                                                        <!-- Nagad Icon (Banknote/Money) -->
                                                        <svg
                                                            class="h-7 w-7 text-white"
                                                            viewBox="0 0 24 24"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            stroke-width="2.5"
                                                        >
                                                            <rect x="2" y="6" width="20" height="12" rx="2" />
                                                            <circle cx="12" cy="12" r="2" />
                                                            <path d="m6 12-2-2 2-2" />
                                                            <path d="m18 12 2-2-2-2" />
                                                        </svg>
                                                    </template>
                                                    <template v-else-if="method.id === 'rocket'">
                                                        <!-- Rocket Icon (Zap/Lightning) -->
                                                        <svg
                                                            class="h-7 w-7 text-white"
                                                            viewBox="0 0 24 24"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            stroke-width="2.5"
                                                        >
                                                            <polygon points="13,2 3,14 12,14 11,22 21,10 12,10 13,2" />
                                                        </svg>
                                                    </template>
                                                </div>
                                                <div>
                                                    <div class="font-semibold text-gray-900">{{ method.name }}</div>
                                                    <div class="text-sm text-gray-600">{{ method.description }}</div>
                                                </div>
                                            </div>
                                            <CheckCircleIcon v-if="selectedPaymentMethod === method.id" class="h-6 w-6 text-[#5f5fcd]" />
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Instructions -->
                            <transition name="fade-slide">
                                <div v-if="selectedPaymentMethod" class="rounded-2xl border border-gray-100 bg-white p-8 shadow-lg">
                                    <h2 class="mb-6 text-2xl font-bold text-gray-900">পেমেন্ট নির্দেশনা</h2>
                                    <div class="prose prose-slate max-w-none">
                                        <div v-if="selectedPaymentMethod === 'bkash'">
                                            <p>অনুগ্রহ করে নিচের নম্বরে সেন্ড মানি করুন:</p>
                                            <p class="inline-block rounded-lg bg-gray-100 p-3 font-mono text-lg font-bold text-gray-800">
                                                01915878662 (পার্সোনাল)
                                            </p>
                                            <ol>
                                                <li>আপনার বিকাশ অ্যাপে যান এবং "সেন্ড মানি" অপশনটি নির্বাচন করুন।</li>
                                                <li>
                                                    অ্যামাউন্ট হিসেবে <strong>৳{{ formatPrice(finalPrice) }}</strong> দিন।
                                                </li>
                                                <li>রেফারেন্স হিসেবে আপনার নাম এবং কোর্সের নাম লিখুন।</li>
                                                <li>আপনার বিকাশ পিন দিয়ে পেমেন্ট সম্পন্ন করুন।</li>
                                                <li>পেমেন্ট সম্পন্ন হলে ট্রানজেকশন আইডিটি সংরক্ষণ করুন।</li>
                                            </ol>
                                        </div>
                                        <div v-else-if="selectedPaymentMethod === 'nagad'">
                                            <p>অনুগ্রহ করে নিচের নম্বরে সেন্ড মানি করুন:</p>
                                            <p class="inline-block rounded-lg bg-gray-100 p-3 font-mono text-lg font-bold text-gray-800">
                                                01750-469027 (পার্সোনাল)
                                            </p>
                                            <ol>
                                                <li>আপনার নগদ অ্যাপে যান এবং "সেন্ড মানি" অপশনটি নির্বাচন করুন।</li>
                                                <li>
                                                    অ্যামাউন্ট হিসেবে <strong>৳{{ formatPrice(finalPrice) }}</strong> দিন।
                                                </li>
                                                <li>আপনার নগদ পিন দিয়ে পেমেন্ট সম্পন্ন করুন।</li>
                                                <li>পেমেন্ট সম্পন্ন হলে ট্রানজেকশন আইডিটি সংরক্ষণ করুন।</li>
                                            </ol>
                                        </div>
                                        <div v-else-if="selectedPaymentMethod === 'rocket'">
                                            <p>অনুগ্রহ করে নিচের নম্বরে সেন্ড মানি করুন:</p>
                                            <p class="inline-block rounded-lg bg-gray-100 p-3 font-mono text-lg font-bold text-gray-800">
                                                019158786625 (পার্সোনাল)
                                            </p>
                                            <ol>
                                                <li>আপনার রকেট অ্যাপে যান এবং "সেন্ড মানি" অপশনটি নির্বাচন করুন।</li>
                                                <li>
                                                    অ্যামাউন্ট হিসেবে <strong>৳{{ formatPrice(finalPrice) }}</strong> দিন।
                                                </li>
                                                <li>আপনার রকেট পিন দিয়ে পেমেন্ট সম্পন্ন করুন।</li>
                                                <li>পেমেন্ট সম্পন্ন হলে ট্রানজেকশন আইডিটি সংরক্ষণ করুন।</li>
                                            </ol>
                                        </div>
                                    </div>

                                    <!-- Transaction ID Input -->
                                    <div class="mt-8">
                                        <label for="transaction_id" class="mb-2 block text-lg font-semibold text-gray-800">ট্রানজেকশন আইডি</label>
                                        <input
                                            type="text"
                                            id="transaction_id"
                                            v-model="form.transactionId"
                                            placeholder="পেমেন্টের পর প্রাপ্ত ট্রানজেকশন আইডি লিখুন"
                                            required
                                            :disabled="form.processing"
                                            class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 transition duration-200 focus:border-[#5f5fcd] focus:ring-[#5f5fcd]"
                                        />
                                        <p class="mt-2 text-sm text-gray-500">
                                            বিকাশ, নগদ বা রকেট থেকে পেমেন্ট করার পর মেসেজে যে ট্রানজেকশন আইডিটি পেয়েছেন, সেটি এখানে সাবমিট করুন।
                                        </p>
                                    </div>
                                </div>
                            </transition>

                            <!-- Terms and Conditions -->
                            <div class="rounded-2xl border border-gray-100 bg-white p-8 shadow-lg">
                                <h2 class="mb-6 text-2xl font-bold text-gray-900">শর্তাবলী</h2>
                                <div class="space-y-4 text-sm text-gray-600">
                                    <label class="flex items-start space-x-3 font-semibold text-gray-800">
                                        <input
                                            v-model="allAgreed"
                                            type="checkbox"
                                            class="mt-1 h-4 w-4 rounded border-gray-300 text-[#5f5fcd] focus:ring-[#5f5fcd] disabled:opacity-50"
                                        />
                                        <span>সবগুলো নির্বাচন করুন</span>
                                    </label>

                                    <hr class="my-4" />

                                    <label class="flex items-start space-x-3">
                                        <input
                                            v-model="form.agreeTerms"
                                            type="checkbox"
                                            required
                                            :disabled="form.processing"
                                            class="mt-1 h-4 w-4 rounded border-gray-300 text-[#5f5fcd] focus:ring-[#5f5fcd] disabled:opacity-50"
                                        />
                                        <span
                                            >আমি <Link href="#" class="text-[#5f5fcd] hover:underline">শর্তাবলী ও নীতিমালা</Link> পড়েছি এবং সম্মত
                                            আছি</span
                                        >
                                    </label>
                                    <label class="flex items-start space-x-3">
                                        <input
                                            v-model="form.agreeRefund"
                                            type="checkbox"
                                            required
                                            :disabled="form.processing"
                                            class="mt-1 h-4 w-4 rounded border-gray-300 text-[#5f5fcd] focus:ring-[#5f5fcd] disabled:opacity-50"
                                        />
                                        <span>আমি <Link href="#" class="text-[#5f5fcd] hover:underline">রিফান্ড নীতি</Link> সম্পর্কে অবগত আছি</span>
                                    </label>
                                    <label class="flex items-start space-x-3">
                                        <input
                                            v-model="form.agreeNewsletter"
                                            type="checkbox"
                                            :disabled="form.processing"
                                            class="mt-1 h-4 w-4 rounded border-gray-300 text-[#5f5fcd] focus:ring-[#5f5fcd] disabled:opacity-50"
                                        />
                                        <span>নতুন কোর্স ও আপডেটের জন্য ইমেইল নোটিফিকেশন পেতে চাই</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="lg:col-span-1">
                            <div class="sticky top-8 space-y-6">
                                <!-- Course Summary -->
                                <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-lg">
                                    <div class="border-b border-gray-100 px-5 py-4">
                                        <h3 class="text-lg font-bold text-gray-900">অর্ডার সামারি</h3>
                                    </div>

                                    <div class="p-5">
                                        <!-- Course Info -->
                                        <div class="mb-5 border-b border-gray-100 pb-4">
                                            <h4 class="mb-1 font-semibold text-gray-900">{{ course?.title }}</h4>
                                            <p class="mb-2 text-sm text-gray-600">{{ course?.category?.name }}</p>
                                            <div class="flex items-center text-sm text-gray-500">
                                                <UsersIcon class="mr-1 h-4 w-4" />
                                                <span>{{ course?.students_count || 0 }}+ শিক্ষার্থী</span>
                                            </div>
                                        </div>

                                        <!-- Price Breakdown -->
                                        <div class="space-y-2 border-t border-gray-100 pt-4">
                                            <div class="flex justify-between text-gray-600">
                                                <span>কোর্স ফি</span>
                                                <span>
                                                    <template v-if="props.course.is_free">ফ্রি</template>
                                                    <template v-else-if="showDiscount">
                                                        <span class="font-bold text-[#e2136e]"
                                                            >৳{{ formatPrice(props.course.discount_price as number) }}</span
                                                        >
                                                        <span class="ml-2 text-gray-400 line-through"
                                                            >৳{{ formatPrice(props.course.price as number) }}</span
                                                        >
                                                        <span
                                                            v-if="discountExpiresIn"
                                                            class="ml-2 rounded bg-yellow-100 px-2 py-0.5 text-xs font-semibold text-yellow-800"
                                                            >{{ discountExpiresIn }}</span
                                                        >
                                                    </template>
                                                    <template v-else>৳{{ formatPrice(props.course.price as number) }}</template>
                                                </span>
                                            </div>
                                            <div class="flex justify-between border-t border-gray-100 pt-2 text-lg font-bold text-gray-900">
                                                <span>সর্বমোট</span>
                                                <span>
                                                    <template v-if="props.course.is_free">ফ্রি</template>
                                                    <template v-else>৳{{ formatPrice(finalPrice) }}</template>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Payment Button -->
                                        <PrimaryButton
                                            @click="handleSubmit"
                                            :disabled="!canSubmit"
                                            :loading="form.processing"
                                            size="lg"
                                            variant="primary"
                                            :icon="CreditCardIcon"
                                            class="mt-5 w-full justify-center"
                                        >
                                            {{ form.processing ? 'প্রক্রিয়াধীন...' : 'পেমেন্ট করুন' }}
                                        </PrimaryButton>
                                    </div>
                                </div>

                                <!-- Security Info -->
                                <div class="rounded-2xl bg-[#2d5a27]/5 p-6">
                                    <div class="mb-4 flex items-center space-x-3">
                                        <ShieldCheckIcon class="h-6 w-6 text-[#2d5a27]" />
                                        <h4 class="font-semibold text-gray-900">নিরাপত্তা গ্যারান্টি</h4>
                                    </div>
                                    <ul class="space-y-2 text-sm text-gray-600">
                                        <li class="flex items-center space-x-2">
                                            <CheckIcon class="h-4 w-4 text-[#2d5a27]" />
                                            <span>১০০% নিরাপদ পেমেন্ট</span>
                                        </li>
                                        <li class="flex items-center space-x-2">
                                            <CheckIcon class="h-4 w-4 text-[#2d5a27]" />
                                            <span>লাইফটাইম অ্যাক্সেস</span>
                                        </li>
                                        <li class="flex items-center space-x-2">
                                            <CheckIcon class="h-4 w-4 text-[#2d5a27]" />
                                            <span>২৪/৭ কাস্টমার সাপোর্ট</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
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
import { Head, Link, useForm } from '@inertiajs/vue3';
import { CheckCircleIcon, CheckIcon, CreditCardIcon, ShieldCheckIcon, UsersIcon } from 'lucide-vue-next';
import { computed, ref } from 'vue';
// @ts-ignore
import dayjs from 'dayjs';

// Course interface
interface Course {
    id: number;
    title: string;
    slug: string;
    description: string;
    image: string;
    price: number;
    students_count: number;
    level?: string;
    duration?: string;
    rating?: number;
    category?: {
        id: number;
        name: string;
    };
    instructor?: {
        name: string;
        avatar: string;
    };
    discount_price?: number;
    discount_expires_at?: string;
    is_free?: boolean;
}

// Props
const props = defineProps({
    course: {
        type: Object,
        required: true,
    },
    user: {
        type: Object,
        default: () => ({}),
    },
});

const toast = useToast();

const mobileBankingMethods = [
    { id: 'bkash', name: 'bKash', description: 'bKash Payment', color: '#e2136e' },
    { id: 'nagad', name: 'Nagad', description: 'Nagad Payment', color: '#f58220' },
    { id: 'rocket', name: 'Rocket', description: 'DBBL Mobile Banking', color: '#8a2be2' },
];

const selectedPaymentMethod = ref<string | null>(null);

// Form state using Inertia's useForm
const form = useForm({
    studentId: props.user?.id || null,
    transactionId: '',
    agreeTerms: false,
    agreeRefund: false,
    agreeNewsletter: false,
    paymentMethod: null as string | null,
    course_id: props.course.id,
    amount: props.course?.price || 0,
});

const allAgreed = computed({
    get: () => form.agreeTerms && form.agreeRefund && form.agreeNewsletter,
    set: (value) => {
        form.agreeTerms = value;
        form.agreeRefund = value;
        form.agreeNewsletter = value;
    },
});

const showDiscount = computed(() => {
    return props.course.discount_price && props.course.discount_expires_at && dayjs(props.course.discount_expires_at).isAfter(dayjs());
});

const discountExpiresIn = computed(() => {
    if (!props.course.discount_expires_at) return '';
    const expires = dayjs(props.course.discount_expires_at);
    const now = dayjs();
    if (expires.isBefore(now)) return '';
    const days = expires.diff(now, 'day');
    if (days > 0) return `ছাড় চলবে আর ${days} দিন`;
    const hours = expires.diff(now, 'hour');
    if (hours > 0) return `ছাড় চলবে আজ পর্যন্ত`;
    return '';
});

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('bn-BD').format(price);
};

const finalPrice = computed(() => {
    if (props.course.is_free) return 0;
    if (showDiscount.value) return props.course.discount_price;
    return props.course.price;
});

const canSubmit = computed(() => {
    return form.agreeTerms && form.agreeRefund && selectedPaymentMethod.value !== null && form.transactionId.trim() !== '' && !form.processing;
});

const sanitizeInput = (input: string): string => {
    if (!input) return '';
    return input
        .replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '')
        .replace(/<[^>]*>/g, '')
        .replace(/javascript:/gi, '')
        .replace(/on\w+=/gi, '')
        .trim();
};

const validatePaymentForm = (): boolean => {
    if (!selectedPaymentMethod.value) {
        toast.error({ title: 'ত্রুটি', message: 'পেমেন্ট পদ্ধতি নির্বাচন করুন।' });
        return false;
    }
    if (!form.transactionId || form.transactionId.trim().length < 5) {
        toast.error({ title: 'ত্রুটি', message: 'ট্রানজেকশন আইডি কমপক্ষে ৫ অক্ষরের হতে হবে।' });
        return false;
    }
    if (!form.agreeTerms) {
        toast.error({ title: 'ত্রুটি', message: 'শর্তাবলী সম্মত হতে হবে।' });
        return false;
    }
    if (!form.agreeRefund) {
        toast.error({ title: 'ত্রুটি', message: 'রিফান্ড নীতি সম্মত হতে হবে।' });
        return false;
    }
    return true;
};

const handleSubmit = () => {
    if (!validatePaymentForm()) return;

    // Update paymentMethod in form just before submitting
    form.paymentMethod = selectedPaymentMethod.value;

    // Sanitize transaction ID
    form.transactionId = sanitizeInput(form.transactionId);

    // Submit the form to the backend
    form.post(route('frontend.payment.process', props.course.slug), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (page) => {
            const flash = page.props.flash as Record<string, any>;
            let successMsg = `অভিনন্দন! আপনার পেমেন্ট জমা দেওয়া হয়েছে। যাচাইয়ের পর আপনার এনরোলমেন্ট সক্রিয় করা হবে।`;
            if (flash && flash.success) {
                successMsg = flash.success;
            }

            toast.success({
                title: 'সফল হয়েছে',
                message: successMsg,
                duration: 8000,
            });

            form.reset();
            selectedPaymentMethod.value = null;
        },
        onError: (errors) => {
            console.error('Payment form submission error:', errors);

            // Handle specific field errors
            if (errors.transactionId) {
                toast.error({ title: 'ট্রানজেকশন আইডি ত্রুটি', message: errors.transactionId });
            } else if (errors.paymentMethod) {
                toast.error({ title: 'পেমেন্ট পদ্ধতি ত্রুটি', message: errors.paymentMethod });
            } else if (errors.course_id) {
                toast.error({ title: 'কোর্স ত্রুটি', message: errors.course_id });
            } else if (errors.amount) {
                toast.error({ title: 'অ্যামাউন্ট ত্রুটি', message: errors.amount });
            } else if (errors.error) {
                toast.error({
                    title: 'ত্রুটি',
                    message: errors.error,
                    duration: 7000,
                });
            } else {
                toast.error({
                    title: 'ত্রুটি',
                    message: 'পেমেন্ট প্রক্রিয়ায় সমস্যা হয়েছে। ইন্টারনেট সংযোগ যাচাই করে আবার চেষ্টা করুন।',
                    duration: 7000,
                });
            }
        },
        onFinish: () => {
            // Additional cleanup if needed
        },
    });
};
</script>

<style scoped>
.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
}

.fade-slide-enter-from,
.fade-slide-leave-to {
    opacity: 0;
    transform: translateY(-15px);
}
</style>
