<template>
    <FrontendLayout title="দান করুন - ইকরা অনলাইন একাডেমি">
        <Head title="দান করুন - ইকরা অনলাইন একাডেমি" />
        <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-20">
            <div class="pattern-grid absolute inset-0 opacity-10"></div>
            <div
                class="animate-float absolute top-0 left-0 h-32 w-32 -translate-x-16 -translate-y-16 transform rounded-full bg-gradient-to-br from-[#5f5fcd]/20 to-transparent"
            ></div>
            <div
                class="animate-float-delayed absolute right-0 bottom-0 h-40 w-40 translate-x-20 translate-y-20 transform rounded-full bg-gradient-to-tl from-[#d4a574]/20 to-transparent"
            ></div>
            <div class="relative mx-auto max-w-2xl px-4 text-center sm:px-6 lg:px-8">
                <div class="mb-10">
                    <div
                        class="mb-6 inline-flex items-center rounded-full border border-white/20 bg-white/10 px-6 py-3 backdrop-blur-sm transition-transform duration-300 hover:scale-105"
                    >
                        <HeartIcon class="mr-2 h-5 w-5 text-[#d4a574]" />
                        <span class="text-sm font-medium text-white">দান করুন, সওয়াবের অংশীদার হোন</span>
                    </div>
                    <h2 class="mb-6 text-3xl leading-tight font-bold text-white lg:text-5xl">
                        ইসলামী শিক্ষার প্রসারে
                        <span
                            class="text-gradient-islamic animate-gradient-shift bg-gradient-to-r from-[#d4a574] to-[#5f5fcd] bg-clip-text text-transparent"
                            >আপনার দান</span
                        >
                        গুরুত্বপূর্ণ
                    </h2>
                    <p class="mx-auto mb-8 max-w-2xl text-lg leading-relaxed text-gray-300 lg:text-xl">
                        ইকরা অনলাইন একাডেমির মাধ্যমে হাজারো শিক্ষার্থী ইসলামী শিক্ষা গ্রহণ করছে। আপনার দান সরাসরি শিক্ষার্থীদের বৃত্তি, পাঠ্যপুস্তক ও
                        প্রযুক্তি সহায়তায় ব্যবহৃত হবে।
                    </p>
                </div>
                <!-- Placeholder for donation form/button -->
                <div class="flex flex-col items-center gap-6">
                    <PrimaryButton size="xl" variant="primary" :icon="HeartIcon" class="min-w-[200px]"> দান করুন </PrimaryButton>
                    <div class="mt-4 text-sm text-gray-400">
                        <span>আল্লাহ্‌ আপনার দান কবুল করুন এবং দ্বীনি শিক্ষার প্রসারে আপনাকে অংশীদার করুন।</span>
                    </div>
                </div>
            </div>
        </section>
    </FrontendLayout>
</template>

<script setup lang="ts">
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue';
import FrontendLayout from '@/layouts/FrontendLayout.vue';
import { Head } from '@inertiajs/vue3';
import { HeartIcon } from 'lucide-vue-next';
</script>
