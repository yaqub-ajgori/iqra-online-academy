<template>
    <FrontendLayout title="ব্লগ - ইকরা অনলাইন একাডেমি">
        <Head>
            <title>ব্লগ - ইকরা অনলাইন একাডেমি</title>
            <meta name="description" content="ইসলামিক শিক্ষা, কুরআন, হাদিস এবং ইসলামিক জীবনযাত্রা সম্পর্কে সর্বশেষ নিবন্ধ ও তথ্য পড়ুন।" />
            <meta name="keywords" content="ইসলামিক ব্লগ, কুরআন, হাদিস, ইসলামিক শিক্ষা, ইসলামিক জীবনযাত্রা" />
            <meta property="og:title" content="ব্লগ - ইকরা অনলাইন একাডেমি" />
            <meta property="og:description" content="ইসলামিক শিক্ষা, কুরআন, হাদিস এবং ইসলামিক জীবনযাত্রা সম্পর্কে সর্বশেষ নিবন্ধ ও তথ্য পড়ুন।" />
            <meta property="og:type" content="website" />
        </Head>

        <!-- Page Header -->
        <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-20">
            <!-- Background Pattern -->
            <div class="pattern-dots absolute inset-0 opacity-10"></div>

            <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <div class="mb-6 inline-flex items-center rounded-full border border-white/20 bg-white/10 px-4 py-2 backdrop-blur-sm">
                        <BookOpenIcon class="mr-2 h-4 w-4 text-white" />
                        <span class="text-sm font-medium text-white">ইসলামিক ব্লগ</span>
                    </div>

                    <h1 class="mb-6 text-4xl font-bold text-white lg:text-6xl">
                        জ্ঞান ও
                        <span class="text-gradient-islamic bg-gradient-to-r from-[#d4a574] to-[#5f5fcd] bg-clip-text text-transparent"
                            >আধ্যাত্মিকতার</span
                        >
                        আলোকবর্তিকা
                    </h1>

                    <p class="mx-auto max-w-3xl text-xl leading-relaxed text-gray-300">
                        কুরআন, হাদিস, ইসলামিক জীবনযাত্রা এবং ইতিহাস সম্পর্কে গভীর আলোচনা ও দিকনির্দেশনা
                    </p>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <section class="bg-neutral-50 py-12 lg:py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-8 lg:grid-cols-4">
                    <!-- Main Content Area -->
                    <div class="lg:col-span-3">
                        <!-- Featured Posts -->
                        <div v-if="featuredPosts.length > 0" class="mb-12">
                            <h2 class="mb-6 flex items-center text-2xl font-bold text-primary">
                                <StarIcon class="mr-2 h-6 w-6 text-[#d4a574]" />
                                ফিচার্ড পোস্ট
                            </h2>
                            <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2">
                                <BlogCard v-for="post in featuredPosts.slice(0, 2)" :key="post.id" :post="post" />
                            </div>
                        </div>

                        <!-- Filter Tabs -->
                        <div class="mb-8">
                            <div class="border-b border-neutral-200">
                                <nav class="flex space-x-8">
                                    <button
                                        v-for="filter in filters"
                                        :key="filter.key"
                                        @click="activeFilter = filter.key"
                                        class="border-b-2 px-1 py-4 text-sm font-medium transition-colors"
                                        :class="
                                            activeFilter === filter.key
                                                ? 'border-[#5f5fcd] text-[#5f5fcd]'
                                                : 'border-transparent text-muted hover:border-neutral-300 hover:text-neutral-700'
                                        "
                                    >
                                        {{ filter.label }}
                                        <span class="ml-2 rounded-full bg-neutral-100 px-2 py-1 text-xs text-secondary">
                                            {{ filter.count }}
                                        </span>
                                    </button>
                                </nav>
                            </div>
                        </div>

                        <!-- Posts Grid -->
                        <BlogGrid :posts="filteredPosts" :loading="loading" />

                        <!-- Infinite Scroll Trigger -->
                        <div v-if="props.hasMorePosts" class="mt-12">
                            <!-- Loading indicator -->
                            <div v-if="loadingMore" class="py-8 text-center">
                                <div class="inline-flex items-center space-x-2 text-gray-600">
                                    <Loader2Icon class="h-6 w-6 animate-spin" />
                                    <span>আরও পোস্ট লোড হচ্ছে...</span>
                                </div>
                            </div>
                            <!-- Invisible trigger element -->
                            <div ref="loadMoreTrigger" class="h-20 w-full"></div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        <WhenVisible>
                            <BlogSidebar
                                :categories="categories"
                                :popular-tags="popularTags"
                                :recent-posts="recentPosts"
                                :active-category="activeCategory"
                            />
                        </WhenVisible>
                    </div>
                </div>
            </div>
        </section>
    </FrontendLayout>
</template>

<script setup lang="ts">
import BlogCard from '@/components/Frontend/Blog/BlogCard.vue';
import BlogGrid from '@/components/Frontend/Blog/BlogGrid.vue';
import BlogSidebar from '@/components/Frontend/Blog/BlogSidebar.vue';
import WhenVisible from '@/components/Frontend/WhenVisible.vue';
import FrontendLayout from '@/layouts/FrontendLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { useIntersectionObserver } from '@vueuse/core';
import { BookOpen as BookOpenIcon, Loader2 as Loader2Icon, Star as StarIcon } from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref } from 'vue';

interface BlogPost {
    id: number;
    slug: string;
    title: string;
    excerpt?: string;
    content: string;
    featured_image?: string;
    featured_image_url?: string;
    published_at: string;
    reading_time?: number;
    views_count?: number;
    likes_count?: number;
    comments_count?: number;
    user_liked?: boolean;
    is_featured?: boolean;
    is_sticky?: boolean;
    category?: {
        id: number;
        name: string;
        slug: string;
        color?: string;
    };
    author?: {
        id: number;
        name: string;
        avatar?: string;
    };
    tags?: Array<{
        id: number;
        name: string;
        slug: string;
    }>;
}

interface Props {
    posts: BlogPost[];
    featuredPosts: BlogPost[];
    categories: Array<{
        id: number;
        name: string;
        slug: string;
        color?: string;
        posts_count: number;
    }>;
    popularTags: Array<{
        id: number;
        name: string;
        slug: string;
        posts_count: number;
    }>;
    recentPosts: Array<{
        id: number;
        slug: string;
        title: string;
        featured_image_url?: string;
        published_at: string;
    }>;
    hasMorePosts?: boolean;
    activeCategory?: string;
}

const props = defineProps<Props>();

const activeFilter = ref('all');
const loading = ref(false);
const loadingMore = ref(false);
const allPosts = ref([...props.posts]);
const loadMoreTrigger = ref<HTMLElement>();
const page = usePage();

// Setup intersection observer for infinite scroll
const { stop } = useIntersectionObserver(
    loadMoreTrigger,
    ([{ isIntersecting }]) => {
        if (isIntersecting && props.hasMorePosts && !loadingMore.value) {
            loadMorePosts();
        }
    },
    {
        threshold: 0.1,
        rootMargin: '100px',
    },
);

const filters = [
    { key: 'all', label: 'সব পোস্ট', count: allPosts.value.length },
    { key: 'recent', label: 'সাম্প্রতিক', count: allPosts.value.filter((p) => isRecent(p.published_at)).length },
    { key: 'popular', label: 'জনপ্রিয়', count: allPosts.value.filter((p) => (p.views_count || 0) > 100).length },
    { key: 'featured', label: 'ফিচার্ড', count: allPosts.value.filter((p) => p.is_featured).length },
];

const filteredPosts = computed(() => {
    switch (activeFilter.value) {
        case 'recent':
            return allPosts.value.filter((post) => isRecent(post.published_at));
        case 'popular':
            return allPosts.value.filter((post) => (post.views_count || 0) > 100);
        case 'featured':
            return allPosts.value.filter((post) => post.is_featured);
        default:
            return allPosts.value;
    }
});

const loadMorePosts = async () => {
    if (!props.hasMorePosts || loadingMore.value) return;

    loadingMore.value = true;

    try {
        const currentUrl = new URL(window.location.href);
        const nextPage = (parseInt(currentUrl.searchParams.get('page') || '1') + 1).toString();

        currentUrl.searchParams.set('page', nextPage);
        currentUrl.searchParams.set('merge', 'true');

        await router.get(
            currentUrl.toString(),
            {},
            {
                preserveState: true,
                preserveScroll: true,
                onSuccess: (page) => {
                    const newPosts = page.props.posts || [];
                    allPosts.value = [...allPosts.value, ...newPosts];
                },
            },
        );
    } catch (error) {
        console.error('Error loading more posts:', error);
    } finally {
        loadingMore.value = false;
    }
};

// Cleanup intersection observer on unmount
onUnmounted(() => {
    stop();
});

function isRecent(publishedAt: string): boolean {
    const publishDate = new Date(publishedAt);
    const now = new Date();
    const diffTime = Math.abs(now.getTime() - publishDate.getTime());
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    return diffDays <= 7; // Posts from last 7 days
}

onMounted(() => {
    // Any initialization logic
});
</script>

<style scoped>
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
</style>
