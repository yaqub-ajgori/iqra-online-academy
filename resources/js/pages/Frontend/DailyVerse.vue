<template>
    <FrontendLayout title="দৈনিক আয়াত - ইকরা অনলাইন একাডেমি">
        <Head title="দৈনিক আয়াত - ইকরা অনলাইন একাডেমি">
            <meta name="description" content="কুরআনের দৈনিক আয়াত পড়ুন, শুনুন এবং অনুপ্রাণিত হোন। বিভিন্ন বিষয়ভিত্তিক আয়াত এবং তেলাওয়াত সহ।" />
            <meta name="keywords" content="দৈনিক আয়াত, কুরআন, তেলাওয়াত, ইসলামিক, অনুপ্রেরণা" />
        </Head>

        <!-- Hero Section -->
        <section class="relative overflow-hidden bg-gradient-to-br from-[#5f5fcd]/5 via-white to-[#2d5a27]/5 pt-8 pb-12">
            <!-- Background Pattern -->
            <div class="pointer-events-none absolute inset-0 opacity-5">
                <svg class="h-full w-full" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="heroPattern" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                            <path d="M20 0 L40 20 L20 40 L0 20 Z" fill="none" stroke="#5f5fcd" stroke-width="1" />
                            <circle cx="20" cy="20" r="8" fill="none" stroke="#2d5a27" stroke-width="1" />
                        </pattern>
                    </defs>
                    <rect width="200" height="200" fill="url(#heroPattern)" />
                </svg>
            </div>

            <div class="relative mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
                <div class="mb-6 flex items-center justify-center">
                    <BookOpenIcon class="mr-3 h-8 w-8 text-[#5f5fcd]" />
                    <h1 class="text-3xl font-bold text-gray-900 sm:text-4xl lg:text-5xl">
                        <span class="text-gradient-islamic">দৈনিক আয়াত</span>
                    </h1>
                </div>

                <p class="mx-auto mb-8 max-w-3xl text-lg leading-relaxed text-gray-600 sm:text-xl">
                    কুরআনের পবিত্র আয়াতসমূহ পড়ুন, শুনুন এবং অনুপ্রাণিত হোন। প্রতিদিন নতুন আয়াত আবিষ্কার করুন এবং আপনার আধ্যাত্মিক যাত্রায় এগিয়ে
                    চলুন।
                </p>

                <!-- Quick Stats -->
                <div class="flex flex-wrap justify-center gap-6 text-sm sm:gap-8">
                    <div class="flex items-center text-[#5f5fcd]">
                        <div class="mr-2 h-2 w-2 animate-pulse rounded-full bg-[#5f5fcd]"></div>
                        <span>৩টি বিভাগ</span>
                    </div>
                    <div class="flex items-center text-[#2d5a27]">
                        <div class="mr-2 h-2 w-2 animate-pulse rounded-full bg-[#2d5a27]"></div>
                        <span>১৫+ আয়াত</span>
                    </div>
                    <div class="flex items-center text-[#d4a574]">
                        <div class="mr-2 h-2 w-2 animate-pulse rounded-full bg-[#d4a574]"></div>
                        <span>তেলাওয়াত সহ</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <section class="bg-gray-50/50 py-12 sm:py-16">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <!-- Daily Verse Widget -->
                <div class="mb-12">
                    <DailyVerseWidget />
                </div>

                <!-- Features Section -->
                <div class="mb-12 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="rounded-xl border border-gray-100 bg-white p-6 text-center shadow-sm">
                        <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-[#5f5fcd]/10">
                            <VolumeIcon class="h-6 w-6 text-[#5f5fcd]" />
                        </div>
                        <h3 class="mb-2 font-semibold text-gray-900">তেলাওয়াত শুনুন</h3>
                        <p class="text-sm text-gray-600">বিশুদ্ধ উচ্চারণে কুরআনের তেলাওয়াত শুনুন</p>
                    </div>

                    <div class="rounded-xl border border-gray-100 bg-white p-6 text-center shadow-sm">
                        <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-[#2d5a27]/10">
                            <RefreshCwIcon class="h-6 w-6 text-[#2d5a27]" />
                        </div>
                        <h3 class="mb-2 font-semibold text-gray-900">নতুন আয়াত</h3>
                        <p class="text-sm text-gray-600">প্রতিদিন নতুন আয়াত আবিষ্কার করুন</p>
                    </div>

                    <div class="rounded-xl border border-gray-100 bg-white p-6 text-center shadow-sm">
                        <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-[#d4a574]/10">
                            <BookmarkIcon class="h-6 w-6 text-[#d4a574]" />
                        </div>
                        <h3 class="mb-2 font-semibold text-gray-900">সেভ করুন</h3>
                        <p class="text-sm text-gray-600">পছন্দের আয়াত সেভ করে রাখুন</p>
                    </div>
                </div>

                <!-- Categories Info -->
                <div class="rounded-xl border border-gray-100 bg-white p-8 shadow-sm">
                    <h2 class="mb-6 text-center text-2xl font-bold text-gray-900">আয়াতের বিভাগসমূহ</h2>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div class="p-4 text-center">
                            <div
                                class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-[#5f5fcd]/10 to-[#5f5fcd]/20"
                            >
                                <SunIcon class="h-8 w-8 text-[#5f5fcd]" />
                            </div>
                            <h3 class="mb-2 font-semibold text-gray-900">দৈনিক আয়াত</h3>
                            <p class="text-sm text-gray-600">প্রতিদিনের জন্য গুরুত্বপূর্ণ আয়াতসমূহ যা আপনার দিন শুরু করতে সাহায্য করবে।</p>
                        </div>

                        <div class="p-4 text-center">
                            <div
                                class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-[#2d5a27]/10 to-[#2d5a27]/20"
                            >
                                <HeartIcon class="h-8 w-8 text-[#2d5a27]" />
                            </div>
                            <h3 class="mb-2 font-semibold text-gray-900">অনুপ্রেরণামূলক</h3>
                            <p class="text-sm text-gray-600">জীবনের কঠিন সময়ে অনুপ্রেরণা ও সাহস পাওয়ার জন্য বিশেষ আয়াতসমূহ।</p>
                        </div>

                        <div class="p-4 text-center">
                            <div
                                class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-[#d4a574]/10 to-[#d4a574]/20"
                            >
                                <CompassIcon class="h-8 w-8 text-[#d4a574]" />
                            </div>
                            <h3 class="mb-2 font-semibold text-gray-900">পথনির্দেশনা</h3>
                            <p class="text-sm text-gray-600">জীবনের সঠিক পথ খুঁজে পেতে এবং সিদ্ধান্ত নিতে সাহায্যকারী আয়াতসমূহ।</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </FrontendLayout>
</template>

<script setup>
import DailyVerseWidget from '@/components/Frontend/DailyVerseWidgetEnhanced.vue';
import FrontendLayout from '@/layouts/FrontendLayout.vue';
import { Head } from '@inertiajs/vue3';
import { BookmarkIcon, BookOpenIcon, CompassIcon, HeartIcon, RefreshCwIcon, SunIcon, VolumeIcon } from 'lucide-vue-next';
</script>

<style scoped>
.text-gradient-islamic {
    background: linear-gradient(135deg, #5f5fcd 0%, #2d5a27 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

@keyframes float {
    0%,
    100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-10px);
    }
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}
</style>
