<template>
    <FrontendLayout>
        <Head :title="`${status} - ${title} - ইকরা অনলাইন একাডেমি`" />

        <!-- Error Section -->
        <section class="relative flex min-h-screen items-center justify-center overflow-hidden bg-gradient-to-br from-gray-50 via-white to-gray-50">
            <!-- Islamic Background Pattern -->
            <div class="pattern-grid absolute inset-0 opacity-5"></div>

            <!-- Floating Decorative Elements -->
            <div class="animate-float absolute top-20 left-20 h-32 w-32 rounded-full bg-gradient-to-br from-[#5f5fcd]/10 to-transparent"></div>
            <div
                class="animate-float-delayed absolute right-20 bottom-20 h-40 w-40 rounded-full bg-gradient-to-tl from-[#d4a574]/10 to-transparent"
            ></div>
            <div class="animate-float absolute top-1/2 left-1/4 h-24 w-24 rounded-full bg-gradient-to-br from-[#2d5a27]/10 to-transparent"></div>

            <div class="relative mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
                <!-- Error Number with Islamic Design -->
                <div class="mb-8">
                    <div class="relative inline-block">
                        <h1
                            class="text-gradient-islamic animate-gradient-shift bg-gradient-to-r from-[#5f5fcd] via-[#2d5a27] to-[#d4a574] bg-clip-text text-8xl font-bold text-transparent lg:text-9xl"
                        >
                            {{ status }}
                        </h1>
                        <!-- Islamic Decorative Elements around error number -->
                        <div
                            class="absolute -top-4 -left-4 h-8 w-8 rotate-45 transform rounded-lg bg-gradient-to-br from-[#5f5fcd]/30 to-[#2d5a27]/30"
                        ></div>
                        <div
                            class="absolute -top-4 -right-4 h-8 w-8 -rotate-45 transform rounded-lg bg-gradient-to-br from-[#d4a574]/30 to-[#5f5fcd]/30"
                        ></div>
                        <div
                            class="absolute -bottom-4 -left-4 h-8 w-8 -rotate-45 transform rounded-lg bg-gradient-to-br from-[#2d5a27]/30 to-[#d4a574]/30"
                        ></div>
                        <div
                            class="absolute -right-4 -bottom-4 h-8 w-8 rotate-45 transform rounded-lg bg-gradient-to-br from-[#5f5fcd]/30 to-[#2d5a27]/30"
                        ></div>
                    </div>
                </div>

                <!-- Error Message -->
                <div class="mb-8">
                    <div class="mb-6 inline-flex items-center rounded-full border border-red-200 bg-red-50 px-6 py-3">
                        <AlertTriangleIcon class="mr-2 h-5 w-5 text-red-500" />
                        <span class="font-medium text-red-700">{{ errorTitle }}</span>
                    </div>

                    <h2 class="mb-4 text-3xl leading-tight font-bold text-gray-900 lg:text-4xl">
                        {{ errorMessage }}
                    </h2>

                    <p class="mx-auto mb-8 max-w-2xl text-lg leading-relaxed text-gray-600">
                        {{ errorDescription }}
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="mb-12 flex flex-col items-center justify-center gap-4 sm:flex-row">
                    <PrimaryButton
                        :href="route('frontend.home')"
                        tag="a"
                        size="lg"
                        variant="primary"
                        :icon="HomeIcon"
                        class="min-w-[200px] transition-transform duration-300 hover:scale-105"
                    >
                        হোমপেজে ফিরে যান
                    </PrimaryButton>

                    <PrimaryButton
                        @click="reloadPage"
                        size="lg"
                        variant="outline"
                        :icon="RefreshCwIcon"
                        class="min-w-[200px] transition-transform duration-300 hover:scale-105"
                    >
                        পৃষ্ঠা রিফ্রেশ করুন
                    </PrimaryButton>
                </div>

                <!-- Quick Links -->
                <div class="shadow-islamic rounded-2xl border border-gray-200/50 bg-white/60 p-8 backdrop-blur-sm">
                    <h3 class="mb-6 text-xl font-semibold text-gray-900">দ্রুত লিংক</h3>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <Link
                            :href="route('frontend.courses.index')"
                            class="group rounded-xl border border-[#5f5fcd]/10 bg-gradient-to-br from-[#5f5fcd]/5 to-[#2d5a27]/5 p-4 transition-all duration-300 hover:scale-105 hover:border-[#5f5fcd]/20 hover:from-[#5f5fcd]/10 hover:to-[#2d5a27]/10"
                        >
                            <div class="flex items-center space-x-3">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] transition-transform duration-300 group-hover:scale-110"
                                >
                                    <BookOpenIcon class="h-5 w-5 text-white" />
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 transition-colors duration-200 group-hover:text-[#5f5fcd]">
                                        কোর্স ব্রাউজ করুন
                                    </p>
                                    <p class="text-sm text-gray-600">সমস্ত কোর্স দেখুন</p>
                                </div>
                            </div>
                        </Link>

                        <Link
                            :href="route('frontend.about')"
                            class="group rounded-xl border border-[#d4a574]/10 bg-gradient-to-br from-[#d4a574]/5 to-[#5f5fcd]/5 p-4 transition-all duration-300 hover:scale-105 hover:border-[#d4a574]/20 hover:from-[#d4a574]/10 hover:to-[#5f5fcd]/10"
                        >
                            <div class="flex items-center space-x-3">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-[#d4a574] to-[#5f5fcd] transition-transform duration-300 group-hover:scale-110"
                                >
                                    <InfoIcon class="h-5 w-5 text-white" />
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 transition-colors duration-200 group-hover:text-[#d4a574]">
                                        আমাদের সম্পর্কে
                                    </p>
                                    <p class="text-sm text-gray-600">আমাদের মিশন ও লক্ষ্য</p>
                                </div>
                            </div>
                        </Link>

                        <Link
                            :href="route('frontend.contact')"
                            class="group rounded-xl border border-[#2d5a27]/10 bg-gradient-to-br from-[#2d5a27]/5 to-[#d4a574]/5 p-4 transition-all duration-300 hover:scale-105 hover:border-[#2d5a27]/20 hover:from-[#2d5a27]/10 hover:to-[#d4a574]/10"
                        >
                            <div class="flex items-center space-x-3">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-[#2d5a27] to-[#d4a574] transition-transform duration-300 group-hover:scale-110"
                                >
                                    <MessageCircleIcon class="h-5 w-5 text-white" />
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 transition-colors duration-200 group-hover:text-[#2d5a27]">যোগাযোগ করুন</p>
                                    <p class="text-sm text-gray-600">সাহায্য ও সহায়তা</p>
                                </div>
                            </div>
                        </Link>

                        <Link
                            :href="route('login')"
                            class="group rounded-xl border border-[#5f5fcd]/10 bg-gradient-to-br from-[#5f5fcd]/5 to-[#d4a574]/5 p-4 transition-all duration-300 hover:scale-105 hover:border-[#5f5fcd]/20 hover:from-[#5f5fcd]/10 hover:to-[#d4a574]/10"
                        >
                            <div class="flex items-center space-x-3">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-[#5f5fcd] to-[#d4a574] transition-transform duration-300 group-hover:scale-110"
                                >
                                    <UserIcon class="h-5 w-5 text-white" />
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 transition-colors duration-200 group-hover:text-[#5f5fcd]">লগইন করুন</p>
                                    <p class="text-sm text-gray-600">আপনার অ্যাকাউন্টে প্রবেশ</p>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>

                <!-- Help Section -->
                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-500">
                        সমস্যা হচ্ছে?
                        <Link
                            :href="route('frontend.contact')"
                            class="font-medium text-[#5f5fcd] transition-colors duration-200 hover:text-[#4a4ab8]"
                        >
                            আমাদের সাথে যোগাযোগ করুন
                        </Link>
                    </p>
                </div>
            </div>
        </section>
    </FrontendLayout>
</template>

<script setup lang="ts">
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue';
import FrontendLayout from '@/layouts/FrontendLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { AlertTriangleIcon, BookOpenIcon, HomeIcon, InfoIcon, MessageCircleIcon, RefreshCwIcon, UserIcon } from 'lucide-vue-next';
import { computed } from 'vue';

// Props
interface Props {
    status: number;
    title?: string;
    message?: string;
    description?: string;
}

const props = withDefaults(defineProps<Props>(), {
    title: 'সার্ভার ত্রুটি',
    message: 'কিছু ভুল হয়েছে',
    description: 'দুঃখিত, সার্ভারে একটি সমস্যা হয়েছে। দয়া করে কিছুক্ষণ পরে আবার চেষ্টা করুন।',
});

// Computed properties
const errorTitle = computed(() => {
    const titles: Record<number, string> = {
        500: 'সার্ভার ত্রুটি',
        503: 'সার্ভিস অপ্রাপ্য',
        419: 'সেশন মেয়াদ শেষ',
        429: 'অতিরিক্ত অনুরোধ',
    };
    return titles[props.status] || props.title;
});

const errorMessage = computed(() => {
    const messages: Record<number, string> = {
        500: 'সার্ভারে একটি অভ্যন্তরীণ ত্রুটি হয়েছে',
        503: 'সার্ভিস সাময়িকভাবে অপ্রাপ্য',
        419: 'আপনার সেশন মেয়াদ শেষ হয়ে গেছে',
        429: 'অতিরিক্ত অনুরোধ পাঠানো হয়েছে',
    };
    return messages[props.status] || props.message;
});

const errorDescription = computed(() => {
    const descriptions: Record<number, string> = {
        500: 'সার্ভারে একটি সমস্যা হয়েছে। আমাদের টেকনিক্যাল টিম সমস্যাটি সমাধানের চেষ্টা করছে।',
        503: 'সার্ভিস রক্ষণাবেক্ষণের জন্য সাময়িকভাবে বন্ধ রয়েছে। দয়া করে কিছুক্ষণ পরে আবার চেষ্টা করুন।',
        419: 'আপনার লগইন সেশন মেয়াদ শেষ হয়ে গেছে। দয়া করে আবার লগইন করুন।',
        429: 'অতিরিক্ত অনুরোধ পাঠানো হয়েছে। দয়া করে কিছুক্ষণ অপেক্ষা করে আবার চেষ্টা করুন।',
    };
    return descriptions[props.status] || props.description;
});

// Methods
const reloadPage = () => {
    window.location.reload();
};
</script>

<style scoped>
/* Enhanced float animations */
@keyframes float {
    0%,
    100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-20px);
    }
}

@keyframes float-delayed {
    0%,
    100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-15px);
    }
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

.animate-float-delayed {
    animation: float-delayed 8s ease-in-out infinite;
}

/* Enhanced gradient text animation */
@keyframes gradientShift {
    0%,
    100% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
}

.text-gradient-islamic {
    background-size: 200% 200%;
    animation: gradientShift 4s ease-in-out infinite;
}

/* Enhanced hover effects */
.group:hover {
    transform: translateY(-2px);
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
    background: #5f5fcd;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #4a4ab8;
}
</style>
