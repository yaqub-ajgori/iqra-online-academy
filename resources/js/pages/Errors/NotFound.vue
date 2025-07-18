<template>
    <FrontendLayout>
        <Head title="পৃষ্ঠা পাওয়া যায়নি - ইকরা অনলাইন একাডেমি" />

        <!-- 404 Error Section -->
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
                            ৪০৪
                        </h1>
                        <!-- Islamic Decorative Elements around 404 -->
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
                        <span class="font-medium text-red-700">পৃষ্ঠা পাওয়া যায়নি</span>
                    </div>

                    <h2 class="mb-4 text-3xl leading-tight font-bold text-gray-900 lg:text-4xl">
                        দুঃখিত, আপনি যে পৃষ্ঠাটি খুঁজছেন
                        <span class="text-gradient-islamic bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] bg-clip-text text-transparent">
                            পাওয়া যায়নি
                        </span>
                    </h2>

                    <p class="mx-auto mb-8 max-w-2xl text-lg leading-relaxed text-gray-600">
                        আপনি যে পৃষ্ঠাটি খুঁজছেন তা মুছে ফেলা হয়েছে, নাম পরিবর্তন করা হয়েছে, অথবা সাময়িকভাবে উপলব্ধ নেই। দয়া করে নিচের বিকল্পগুলি
                        ব্যবহার করুন।
                    </p>
                </div>

                <!-- Search Box -->
                <div class="mx-auto mb-8 max-w-md">
                    <div class="relative">
                        <input
                            v-model="searchQuery"
                            @keyup.enter="handleSearch"
                            type="text"
                            placeholder="কোর্স বা বিষয় অনুসন্ধান করুন..."
                            class="w-full rounded-xl border border-gray-300 bg-white/80 px-4 py-3 pr-4 pl-12 backdrop-blur-sm transition-all duration-200 focus:border-[#5f5fcd] focus:ring-2 focus:ring-[#5f5fcd]"
                        />
                        <SearchIcon class="absolute top-1/2 left-4 h-5 w-5 -translate-y-1/2 transform text-gray-400" />
                        <button
                            @click="handleSearch"
                            class="absolute top-1/2 right-2 -translate-y-1/2 transform rounded-lg bg-[#5f5fcd] p-2 text-white transition-colors duration-200 hover:bg-[#4a4ab8]"
                        >
                            <ArrowRightIcon class="h-4 w-4" />
                        </button>
                    </div>
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
                        :href="route('frontend.courses.index')"
                        tag="a"
                        size="lg"
                        variant="outline"
                        :icon="BookOpenIcon"
                        class="min-w-[200px] transition-transform duration-300 hover:scale-105"
                    >
                        কোর্স ব্রাউজ করুন
                    </PrimaryButton>
                </div>
            </div>
        </section>
    </FrontendLayout>
</template>

<script setup lang="ts">
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue';
import FrontendLayout from '@/layouts/FrontendLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { AlertTriangleIcon, ArrowRightIcon, BookOpenIcon, HomeIcon, SearchIcon } from 'lucide-vue-next';
import { ref } from 'vue';

// State
const searchQuery = ref('');

// Methods
const handleSearch = () => {
    if (searchQuery.value.trim()) {
        // Redirect to courses page with search query
        router.visit(route('frontend.courses.index'), {
            data: { search: searchQuery.value.trim() },
        });
    }
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

/* Enhanced focus states for accessibility */
input:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(95, 95, 205, 0.1);
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
