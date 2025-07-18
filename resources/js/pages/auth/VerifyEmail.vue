<template>
    <FrontendLayout title="ইমেইল যাচাইকরণ - ইকরা অনলাইন একাডেমি">
        <Head title="ইমেইল যাচাইকরণ" />

        <section class="flex min-h-screen items-center py-8 sm:py-20">
            <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 items-center gap-16 lg:grid-cols-2">
                    <!-- Left Side - Information -->
                    <div class="hidden space-y-8 lg:block">
                        <div>
                            <div class="mb-6 inline-flex items-center rounded-full border border-blue-600/20 bg-blue-600/10 px-4 py-2">
                                <MailCheck class="mr-2 h-4 w-4 text-blue-600" />
                                <span class="text-sm font-medium text-blue-600">ইমেইল যাচাইকরণ</span>
                            </div>
                            <h1 class="mb-6 text-4xl font-bold text-gray-900 lg:text-5xl">ইমেইল যাচাইকরণ</h1>
                            <p class="mb-8 text-xl leading-relaxed text-gray-600">
                                আপনার অ্যাকাউন্ট সক্রিয় করতে ইমেইলে পাঠানো লিঙ্কটি ক্লিক করুন। ইমেইল না পেলে স্প্যাম ফোল্ডার চেক করুন।
                            </p>
                        </div>
                    </div>

                    <!-- Right Side - Email Verification Card -->
                    <div>
                        <div
                            class="shadow-islamic-lg relative overflow-hidden rounded-2xl border border-gray-100/50 bg-white/95 p-8 backdrop-blur-sm"
                        >
                            <!-- Background pattern -->
                            <div class="absolute inset-0 opacity-5">
                                <svg class="h-full w-full" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
                                    <defs>
                                        <pattern id="verifyPattern" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                                            <path d="M20 0 L40 20 L20 40 L0 20 Z" fill="none" stroke="#5f5fcd" stroke-width="1" />
                                            <circle cx="20" cy="20" r="8" fill="none" stroke="#2d5a27" stroke-width="1" />
                                        </pattern>
                                    </defs>
                                    <rect width="400" height="400" fill="url(#verifyPattern)" />
                                </svg>
                            </div>

                            <div class="relative z-10">
                                <div class="mb-8 text-center">
                                    <div
                                        class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-r from-[#5f5fcd]/20 via-[#2d5a27]/20 to-[#d4a574]/20"
                                    >
                                        <MailCheck class="h-8 w-8 text-[#5f5fcd]" />
                                    </div>
                                    <h2 class="mb-2 text-2xl font-bold text-gray-900">ইমেইল যাচাই করুন</h2>
                                    <p class="text-gray-600">আপনার ইমেইল ঠিকানায় যাচাইকরণ লিঙ্ক পাঠানো হয়েছে।</p>
                                </div>

                                <div
                                    v-if="verificationLinkSent"
                                    class="mb-6 rounded-lg bg-green-50 p-3 text-center text-sm font-medium text-green-600"
                                >
                                    আপনার ইমেইল ঠিকানায় একটি নতুন যাচাইকরণ লিঙ্ক পাঠানো হয়েছে।
                                </div>

                                <form @submit.prevent="submit">
                                    <Button
                                        type="submit"
                                        :class="{ 'opacity-25': isButtonDisabled }"
                                        :disabled="isButtonDisabled"
                                        class="group hover:shadow-islamic-lg w-full rounded-xl bg-gradient-to-r from-[#5f5fcd] via-[#2d5a27] to-[#d4a574] px-4 py-3 font-semibold text-white transition-all duration-300"
                                    >
                                        যাচাইকরণ ইমেইল পুনরায় পাঠান
                                    </Button>
                                </form>

                                <div v-if="cooldown > 0" class="mt-6 text-center text-sm font-medium text-red-600">
                                    পরে আবার চেষ্টা করুন ({{ cooldown }} সেকেন্ড অপেক্ষা করুন)
                                </div>

                                <div class="mt-6 text-center">
                                    <TextLink
                                        :href="route('logout')"
                                        method="post"
                                        as="button"
                                        class="inline-flex items-center font-medium text-gray-600 hover:text-red-500"
                                    >
                                        <LogOut class="mr-2 h-4 w-4" />
                                        লগ আউট
                                    </TextLink>
                                </div>

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
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { useToast } from '@/composables/useToast';
import FrontendLayout from '@/layouts/FrontendLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ArrowRight, LogOut, MailCheck } from 'lucide-vue-next';
import { computed, onMounted, ref, watch } from 'vue';

const props = defineProps({
    status: String,
    error: String,
});

const form = useForm({});
const { error: showError, success: showSuccess } = useToast();

const cooldown = ref(0);
const COOLDOWN_KEY = 'verify_email_cooldown';

// Restore cooldown from localStorage on mount
onMounted(() => {
    const saved = localStorage.getItem(COOLDOWN_KEY);
    if (saved) {
        const remaining = parseInt(saved, 10) - Math.floor(Date.now() / 1000);
        if (remaining > 0) {
            cooldown.value = remaining;
        } else {
            localStorage.removeItem(COOLDOWN_KEY);
        }
    }
    if (props.error) {
        showError(props.error);
    }
});

// Watch cooldown and update localStorage
watch(cooldown, (val) => {
    if (val > 0) {
        localStorage.setItem(COOLDOWN_KEY, (Math.floor(Date.now() / 1000) + val).toString());
    } else {
        localStorage.removeItem(COOLDOWN_KEY);
    }
});

// Decrement cooldown every second
let interval = null;
watch(cooldown, (val) => {
    if (val > 0 && !interval) {
        interval = setInterval(() => {
            if (cooldown.value > 0) {
                cooldown.value--;
            }
            if (cooldown.value <= 0 && interval) {
                clearInterval(interval);
                interval = null;
            }
        }, 1000);
    } else if (val <= 0 && interval) {
        clearInterval(interval);
        interval = null;
    }
});

watch(
    () => props.error,
    (val) => {
        if (val) {
            showError(val);
        }
    },
);

const submit = () => {
    form.post(route('verification.send'), {
        onError: () => {
            // Check for rate limit error (HTTP 429)
            if (form.errors && form.errors.email && form.errors.email.includes('seconds')) {
                cooldown.value = 60; // 60 seconds cooldown
                showError('অনুগ্রহ করে কিছুক্ষণ অপেক্ষা করুন এবং পরে আবার চেষ্টা করুন।');
            } else {
                showError('কিছু ভুল হয়েছে। দয়া করে পরে আবার চেষ্টা করুন।');
            }
        },
    });
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
const isButtonDisabled = computed(() => form.processing || cooldown.value > 0);
</script>

<style scoped>
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
