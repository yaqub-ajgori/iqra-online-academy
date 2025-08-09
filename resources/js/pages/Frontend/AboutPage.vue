<template>
    <FrontendLayout title="আমাদের সম্পর্কে - ইকরা অনলাইন একাডেমি">
        <Head title="আমাদের সম্পর্কে - ইকরা অনলাইন একাডেমি" />

        <!-- Main Content -->
        <div>
            <!-- Page Header -->
            <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-20">
                <!-- Background Pattern -->
                <div class="pattern-dots absolute inset-0 opacity-10"></div>

                <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <div class="mb-6 inline-flex items-center rounded-full border border-white/20 bg-white/10 px-4 py-2 backdrop-blur-sm">
                            <HeartIcon class="mr-2 h-4 w-4 text-white" />
                            <span class="text-sm font-medium text-white">আমাদের পরিচয়</span>
                        </div>

                        <h1 class="mb-6 text-4xl font-bold text-white lg:text-6xl">
                            ইকরা অনলাইন
                            <span class="text-gradient-islamic bg-gradient-to-r from-[#d4a574] to-[#5f5fcd] bg-clip-text text-transparent"
                                >একাডেমি</span
                            >
                        </h1>

                        <p class="mx-auto max-w-3xl text-xl leading-relaxed text-gray-300">
                            {{
                                aboutData?.tagline ||
                                'ইসলামিক শিক্ষায় আধুনিক প্রযুক্তির সমন্বয়ে গড়ে উঠেছে ইকরা অনলাইন একাডেমি। আমরা বিশ্বাস করি জ্ঞানার্জনই প্রকৃত ইবাদত।'
                            }}
                        </p>
                    </div>
                </div>
            </section>

            <!-- Mission Section -->
            <section class="py-20">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 items-center gap-16 lg:grid-cols-2">
                        <!-- Content -->
                        <div>
                            <div class="mb-6 inline-flex items-center rounded-full border border-[#5f5fcd]/20 bg-[#5f5fcd]/10 px-4 py-2">
                                <BookOpenIcon class="mr-2 h-4 w-4 text-[#5f5fcd]" />
                                <span class="text-sm font-medium text-[#5f5fcd]">আমাদের লক্ষ্য</span>
                            </div>

                            <h2 class="mb-6 text-3xl font-bold text-gray-900 lg:text-4xl">
                                {{ aboutData?.mission_title || 'ইসলামিক জ্ঞানে আলোকিত হোক প্রতিটি মুসলমান' }}
                            </h2>

                            <p class="mb-6 text-lg leading-relaxed text-gray-600">
                                {{
                                    aboutData?.mission_description ||
                                    'আমাদের মূল লক্ষ্য হলো সহজ ও আধুনিক পদ্ধতিতে ইসলামিক শিক্ষা প্রদান করা। কুরআন-হাদিসের আলোকে একটি আদর্শ ইসলামিক সমাজ গঠনে অবদান রাখা।'
                                }}
                            </p>

                            <div class="space-y-4">
                                <div v-for="feature in aboutData?.features" :key="feature.id" class="flex items-start space-x-3">
                                    <div class="mt-1 flex h-6 w-6 items-center justify-center rounded-full bg-[#2d5a27]">
                                        <CheckIcon class="h-3 w-3 text-white" />
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ feature.title }}</h4>
                                        <p class="text-gray-600">{{ feature.description }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Image -->
                        <div class="relative">
                            <div v-if="aboutData?.mission_image">
                                <img
                                    :src="aboutData.mission_image"
                                    :alt="aboutData?.mission_image_alt || 'ইসলামিক শিক্ষা'"
                                    class="shadow-islamic-lg h-auto w-full rounded-2xl"
                                    @error="handleImageError"
                                />
                            </div>
                            <div
                                v-else
                                class="shadow-islamic-lg flex h-[400px] w-full items-center justify-center rounded-2xl bg-gradient-to-br from-[#5f5fcd]/10 to-[#2d5a27]/10"
                            >
                                <div class="text-center">
                                    <div
                                        class="mx-auto mb-4 flex h-32 w-32 items-center justify-center rounded-2xl bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27]"
                                    >
                                        <span class="text-5xl font-bold text-white">ই</span>
                                    </div>
                                    <p class="text-lg text-gray-500">ইকরা অনলাইন একাডেমি</p>
                                </div>
                            </div>

                            <div class="absolute -bottom-6 -left-6 h-32 w-32 rounded-2xl bg-gradient-to-br from-[#5f5fcd]/20 to-[#2d5a27]/20"></div>
                            <div class="absolute -top-6 -right-6 h-24 w-24 rounded-full bg-gradient-to-br from-[#d4a574]/30 to-transparent"></div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Values Section -->
            <section class="bg-gray-50 py-20">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="mb-16 text-center">
                        <div class="mb-6 inline-flex items-center rounded-full border border-gray-200 bg-white px-4 py-2">
                            <StarIcon class="mr-2 h-4 w-4 text-[#5f5fcd]" />
                            <span class="text-sm font-medium text-[#5f5fcd]">আমাদের মূল্যবোধ</span>
                        </div>

                        <h2 class="mb-6 text-3xl font-bold text-gray-900 lg:text-4xl">
                            {{ aboutData?.values_title || 'যে নীতিমালার উপর ভিত্তি করে আমরা কাজ করি' }}
                        </h2>

                        <p class="mx-auto max-w-3xl text-lg text-gray-600">
                            {{
                                aboutData?.values_description ||
                                'ইসলামিক মূল্যবোধ ও আধুনিক শিক্ষা পদ্ধতির সমন্বয়ে আমরা গড়ে তুলেছি একটি অনন্য শিক্ষা প্রতিষ্ঠান।'
                            }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                        <div
                            v-for="value in aboutData?.values"
                            :key="value.id"
                            class="shadow-islamic hover:shadow-islamic-lg group rounded-2xl bg-white p-8 transition-all duration-300"
                        >
                            <div
                                class="mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] transition-transform duration-300 group-hover:scale-110"
                            >
                                <component :is="getValueIcon(value.icon)" class="h-8 w-8 text-white" />
                            </div>
                            <h3 class="mb-4 text-xl font-semibold text-gray-900">{{ value.title }}</h3>
                            <p class="leading-relaxed text-gray-600">{{ value.description }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Team Section -->
            <section class="py-20">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="mb-16 text-center">
                        <div class="mb-6 inline-flex items-center rounded-full border border-[#5f5fcd]/20 bg-[#5f5fcd]/10 px-4 py-2">
                            <UsersIcon class="mr-2 h-4 w-4 text-[#5f5fcd]" />
                            <span class="text-sm font-medium text-[#5f5fcd]">আমাদের টিম</span>
                        </div>

                        <h2 class="mb-6 text-3xl font-bold text-gray-900 lg:text-4xl">
                            {{ aboutData?.team_title || 'অভিজ্ঞ শিক্ষকমণ্ডলী ও বিশেষজ্ঞগণ' }}
                        </h2>

                        <p class="mx-auto max-w-3xl text-lg text-gray-600">
                            {{ aboutData?.team_description || 'আমাদের টিমে রয়েছেন দেশের সেরা আলেম, ইসলামিক স্কলার এবং প্রযুক্তিবিদরা।' }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                        <div
                            v-for="member in aboutData?.team_members"
                            :key="member.id"
                            class="shadow-islamic hover:shadow-islamic-lg group rounded-2xl bg-white p-6 text-center transition-all duration-300"
                        >
                            <div class="relative">
                                <img
                                    v-if="member.avatar && !member.avatarError"
                                    :src="member.avatar"
                                    :alt="member.name"
                                    class="mx-auto mb-4 h-24 w-24 rounded-full border-4 border-[#d4a574] object-cover transition-transform duration-300 group-hover:scale-105"
                                    @error="handleAvatarError(member)"
                                />
                                <div
                                    v-else
                                    class="mx-auto mb-4 flex h-24 w-24 items-center justify-center rounded-full border-4 border-[#d4a574] bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] transition-transform duration-300 group-hover:scale-105"
                                >
                                    <span class="text-3xl font-bold text-white">{{ getInitials(member.name) }}</span>
                                </div>
                            </div>
                            <h3 class="mb-2 text-xl font-semibold text-gray-900">{{ member.name }}</h3>
                            <p class="mb-2 font-medium text-[#5f5fcd]">{{ member.position }}</p>
                            <p class="text-sm text-gray-600">{{ member.experience }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Contact CTA -->
            <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-20">
                <!-- Background Pattern -->
                <div class="pattern-grid absolute inset-0 opacity-10"></div>

                <div class="relative mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
                    <h2 class="mb-6 text-3xl font-bold text-white lg:text-5xl">
                        {{ aboutData?.cta_title || 'আমাদের সাথে যুক্ত হন' }}
                    </h2>

                    <p class="mx-auto mb-8 max-w-2xl text-xl leading-relaxed text-gray-300">
                        {{
                            aboutData?.cta_description ||
                            'ইসলামিক শিক্ষার এই মহান যাত্রায় আমাদের সাথে যুক্ত হন। আপনার জ্ঞানের পিপাসা মিটান আমাদের সাথে।'
                        }}
                    </p>

                    <div class="flex flex-col justify-center gap-4 sm:flex-row">
                        <PrimaryButton :href="route('frontend.courses.index')" tag="a" size="xl" variant="primary" :icon="BookOpenIcon">
                            কোর্স দেখুন
                        </PrimaryButton>
                        <PrimaryButton
                            :href="route('frontend.contact')"
                            tag="a"
                            size="xl"
                            variant="outline"
                            class="border-white/20 text-white hover:border-white/40 hover:bg-white/10"
                            :icon="MailIcon"
                        >
                            যোগাযোগ করুন
                        </PrimaryButton>
                    </div>
                </div>
            </section>
        </div>
    </FrontendLayout>
</template>

<script setup lang="ts">
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue';
import FrontendLayout from '@/layouts/FrontendLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { AwardIcon, BookOpenIcon, CheckIcon, GlobeIcon, HeartIcon, MailIcon, ShieldIcon, StarIcon, UsersIcon } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';

// Interfaces
interface AboutData {
    tagline: string;
    mission_title: string;
    mission_description: string;
    mission_image: string;
    mission_image_alt: string;
    values_title: string;
    values_description: string;
    team_title: string;
    team_description: string;
    cta_title: string;
    cta_description: string;
    features: Array<{
        id: number;
        title: string;
        description: string;
    }>;
    values: Array<{
        id: number;
        title: string;
        description: string;
        icon: string;
    }>;
    team_members: Array<{
        id: number;
        name: string;
        position: string;
        experience: string;
        avatar: string;
        avatarError?: boolean;
    }>;
}

interface Stat {
    id: number;
    label: string;
    value: string;
}

// State
const page = usePage();
const aboutData = ref(page.props.aboutData as AboutData);
const stats = ref((page.props.stats as Stat[]) || []);

const getValueIcon = (iconName: string) => {
    const icons: Record<string, any> = {
        'book-open': BookOpenIcon,
        users: UsersIcon,
        award: AwardIcon,
        heart: HeartIcon,
        globe: GlobeIcon,
        shield: ShieldIcon,
        star: StarIcon,
        check: CheckIcon,
    };
    return icons[iconName] || BookOpenIcon;
};

const getInitials = (name: string) => {
    // Get first letter of first and last name
    const parts = name.split(' ');
    if (parts.length >= 2) {
        return parts[0][0] + parts[parts.length - 1][0];
    }
    return parts[0][0] || 'ই';
};

const handleImageError = (event: Event) => {
    const target = event.target as HTMLImageElement;
    // Hide the image and let the CSS fallback show
    target.style.display = 'none';
};

const handleAvatarError = (member: any) => {
    // Mark this member's avatar as having an error so the fallback shows
    member.avatarError = true;
};

// Initialize on mount
onMounted(() => {
    loadAboutData();
});
</script>

<style scoped>
/* Custom styles for the about page */
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

/* Hover animations for cards */
.group:hover .group-hover\:scale-105 {
    transform: scale(1.05);
}

.group:hover .group-hover\:scale-110 {
    transform: scale(1.1);
}
</style>
