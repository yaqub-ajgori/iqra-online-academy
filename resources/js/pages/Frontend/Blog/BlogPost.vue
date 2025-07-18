<template>
    <FrontendLayout :title="post.title">
        <Head>
            <title>{{ post.meta_title || post.title }} - ইকরা অনলাইন একাডেমি</title>
            <meta name="description" :content="post.meta_description || post.excerpt || stripHtml(post.content).substring(0, 160)" />
            <meta name="keywords" :content="post.meta_keywords || post.tags?.map((t) => t.name).join(', ')" />
            <meta property="og:title" :content="post.title" />
            <meta property="og:description" :content="post.excerpt || stripHtml(post.content).substring(0, 160)" />
            <meta property="og:type" content="article" />
            <meta property="og:image" :content="post.featured_image_url" />
            <meta property="article:published_time" :content="post.published_at" />
            <meta property="article:author" :content="post.author?.name" />
            <meta property="article:section" :content="post.category?.name" />
            <meta property="article:tag" v-for="tag in post.tags" :key="tag.id" :content="tag.name" />
        </Head>

        <!-- Page Header -->
        <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-16">
            <!-- Background Pattern -->
            <div class="pattern-dots absolute inset-0 opacity-10"></div>

            <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Breadcrumb -->
                <nav class="mb-8 flex items-center space-x-2 text-sm">
                    <Link :href="route('frontend.home')" class="text-gray-300 hover:text-white">হোম</Link>
                    <ChevronRightIcon class="h-4 w-4 text-gray-400" />
                    <Link :href="route('frontend.blog.index')" class="text-gray-300 hover:text-white">ব্লগ</Link>
                    <ChevronRightIcon class="h-4 w-4 text-gray-400" />
                    <span class="font-medium text-white">{{ post.category?.name || 'পোস্ট' }}</span>
                </nav>

                <div class="text-center">
                    <div
                        v-if="post.category"
                        class="mb-6 inline-flex items-center rounded-full border border-white/20 bg-white/10 px-4 py-2 backdrop-blur-sm"
                    >
                        <BookOpenIcon class="mr-2 h-4 w-4 text-white" />
                        <span class="text-sm font-medium text-white">{{ post.category.name }}</span>
                    </div>

                    <h1 class="mb-6 text-3xl leading-tight font-bold text-white lg:text-5xl">
                        {{ post.title }}
                    </h1>

                    <div class="flex flex-wrap items-center justify-center gap-6 text-sm text-gray-300">
                        <div class="flex items-center">
                            <CalendarIcon class="mr-2 h-4 w-4" />
                            {{ formatDate(post.published_at) }}
                        </div>
                        <div class="flex items-center">
                            <ClockIcon class="mr-2 h-4 w-4" />
                            {{ post.reading_time }} মিনিট পড়ার সময়
                        </div>
                        <div class="flex items-center">
                            <EyeIcon class="mr-2 h-4 w-4" />
                            {{ post.views_count }} বার দেখা হয়েছে
                        </div>
                        <div v-if="post.author" class="flex items-center">
                            <UserIcon class="mr-2 h-4 w-4" />
                            {{ post.author.name }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <article class="py-12 lg:py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-12 lg:grid-cols-4">
                    <!-- Article Content -->
                    <div class="lg:col-span-3">
                        <!-- Social Share -->
                        <div class="mb-8 flex items-center space-x-4">
                            <span class="text-sm font-medium text-neutral-700">শেয়ার করুন:</span>
                            <div class="flex space-x-2">
                                <button @click="shareOnFacebook" class="rounded-lg bg-blue-600 p-2 text-white transition-colors hover:bg-blue-700">
                                    <Facebook class="h-4 w-4" />
                                </button>
                                <button @click="shareOnTwitter" class="rounded-lg bg-sky-500 p-2 text-white transition-colors hover:bg-sky-600">
                                    <Twitter class="h-4 w-4" />
                                </button>
                                <button @click="shareOnWhatsApp" class="rounded-lg bg-green-600 p-2 text-white transition-colors hover:bg-green-700">
                                    <MessageCircle class="h-4 w-4" />
                                </button>
                                <button @click="copyLink" class="rounded-lg bg-neutral-600 p-2 text-white transition-colors hover:bg-neutral-700">
                                    <LinkIcon class="h-4 w-4" />
                                </button>
                            </div>
                        </div>

                        <!-- Featured Image -->
                        <div v-if="post.featured_image_url" class="mb-8">
                            <img :src="post.featured_image_url" :alt="post.title" class="h-64 w-full rounded-2xl object-cover shadow-lg lg:h-96" />
                        </div>

                        <!-- Article Content -->
                        <div class="prose prose-lg mb-8 max-w-none">
                            <div v-html="post.content" class="blog-content"></div>
                        </div>

                        <!-- Tags -->
                        <div v-if="post.tags && post.tags.length > 0" class="mb-8">
                            <h3 class="mb-4 text-lg font-semibold text-primary">ট্যাগসমূহ:</h3>
                            <div class="flex flex-wrap gap-2">
                                <Link
                                    v-for="tag in post.tags"
                                    :key="tag.id"
                                    :href="route('frontend.blog.tag', { slug: tag.slug })"
                                    class="inline-flex items-center rounded-lg bg-neutral-100 px-3 py-1 text-sm font-medium text-neutral-700 transition-colors hover:bg-[#5f5fcd] hover:text-white"
                                >
                                    #{{ tag.name }}
                                </Link>
                            </div>
                        </div>

                        <!-- Reactions -->
                        <div class="mb-8 rounded-2xl bg-neutral-50">
                            <h3 class="mb-4 text-lg font-semibold text-primary">এই পোস্টটি কেমন লাগল?</h3>

                            <div class="flex space-x-4">
                                <button
                                    @click="toggleReaction('like')"
                                    :disabled="reactionLoading"
                                    class="reaction-btn-like group flex transform cursor-pointer items-center rounded-lg border px-4 py-2 transition-all duration-300 hover:scale-105 active:scale-95"
                                    :class="[
                                        reactionCounts.like_active
                                            ? 'border-red-200 bg-red-50 text-red-700 shadow-md'
                                            : 'border-neutral-200 bg-white text-neutral-700 hover:border-red-200 hover:bg-red-50 hover:shadow-sm',
                                        reactionLoading ? 'opacity-50' : '',
                                    ]"
                                >
                                    <HeartIcon
                                        class="heart-icon mr-2 h-5 w-5 transition-all duration-300"
                                        :class="{
                                            'fill-current text-red-500': reactionCounts.like_active,
                                            'group-hover:scale-110 group-hover:text-red-500': !reactionCounts.like_active,
                                        }"
                                    />
                                    <span class="font-medium">পছন্দ</span>
                                    <span
                                        ref="likeCountRef"
                                        class="ml-1 rounded-full px-2 py-0.5 text-xs font-semibold transition-all duration-300"
                                        :class="reactionCounts.like_active ? 'bg-red-100 text-red-700' : 'bg-neutral-100 text-neutral-600'"
                                    >
                                        {{ reactionCounts.likes_count }}
                                    </span>
                                </button>
                                <button
                                    @click="toggleReaction('helpful')"
                                    :disabled="reactionLoading"
                                    class="reaction-btn-helpful group flex transform cursor-pointer items-center rounded-lg border px-4 py-2 transition-all duration-300 hover:scale-105 active:scale-95"
                                    :class="[
                                        reactionCounts.helpful_active
                                            ? 'border-green-200 bg-green-50 text-green-700 shadow-md'
                                            : 'border-neutral-200 bg-white text-neutral-700 hover:border-green-200 hover:bg-green-50 hover:shadow-sm',
                                        reactionLoading ? 'opacity-50' : '',
                                    ]"
                                >
                                    <ThumbsUpIcon
                                        class="thumbs-icon mr-2 h-5 w-5 transition-all duration-300"
                                        :class="{
                                            'fill-current text-green-500': reactionCounts.helpful_active,
                                            'group-hover:scale-110 group-hover:text-green-500': !reactionCounts.helpful_active,
                                        }"
                                    />
                                    <span class="font-medium">সহায়ক</span>
                                    <span
                                        ref="helpfulCountRef"
                                        class="ml-1 rounded-full px-2 py-0.5 text-xs font-semibold transition-all duration-300"
                                        :class="reactionCounts.helpful_active ? 'bg-green-100 text-green-700' : 'bg-neutral-100 text-neutral-600'"
                                    >
                                        {{ reactionCounts.helpful_count }}
                                    </span>
                                </button>
                            </div>
                        </div>

                        <!-- Comments Section -->
                        <BlogComments :post-id="post.id" :comments="comments" @comment-added="handleCommentAdded" />
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="sticky top-8 space-y-8">
                            <!-- Table of Contents -->
                            <div v-if="tableOfContents.length > 0" class="shadow-islamic rounded-2xl border border-neutral-100 bg-white p-6">
                                <h3 class="mb-4 flex items-center text-lg font-semibold text-primary">
                                    <ListIcon class="mr-2 h-5 w-5 text-[#5f5fcd]" />
                                    সূচিপত্র
                                </h3>
                                <nav>
                                    <ul class="space-y-2">
                                        <li v-for="item in tableOfContents" :key="item.id">
                                            <a
                                                :href="`#${item.id}`"
                                                class="block py-1 text-sm text-secondary transition-colors hover:text-[#5f5fcd]"
                                                :class="{ 'pl-4': item.level > 2 }"
                                            >
                                                {{ item.text }}
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>

                            <!-- Author Info -->
                            <div v-if="post.author" class="shadow-islamic rounded-2xl border border-neutral-100 bg-white p-6">
                                <h3 class="mb-3 text-base font-bold text-primary">লেখক সম্পর্কে</h3>
                                <div class="flex items-start space-x-4">
                                    <img
                                        v-if="post.author.avatar"
                                        :src="post.author.avatar"
                                        :alt="post.author.name"
                                        class="h-14 w-14 rounded-full border border-gray-200 object-cover"
                                    />
                                    <div
                                        v-else
                                        class="flex h-14 w-14 items-center justify-center rounded-full border border-gray-200 bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27]"
                                    >
                                        <span class="text-lg font-bold text-white">
                                            {{ getInitials(post.author.name) }}
                                        </span>
                                    </div>
                                    <div class="flex flex-col justify-center">
                                        <h4 class="text-base leading-tight font-bold text-neutral-800">{{ post.author.name }}</h4>
                                        <p class="mt-0.5 text-xs text-gray-500">ইসলামি স্কলার ও লেখক</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Related Posts -->
                            <div v-if="relatedPosts.length > 0" class="shadow-islamic rounded-2xl border border-neutral-100 bg-white p-6">
                                <h3 class="mb-4 flex items-center text-lg font-semibold text-primary">
                                    <BookOpenIcon class="mr-2 h-5 w-5 text-[#5f5fcd]" />
                                    সম্পর্কিত পোস্ট
                                </h3>
                                <div class="space-y-4">
                                    <article v-for="relatedPost in relatedPosts" :key="relatedPost.id" class="group">
                                        <Link :href="route('frontend.blog.show', { slug: relatedPost.slug })">
                                            <h4 class="line-clamp-2 text-sm font-medium text-primary transition-colors group-hover:text-[#5f5fcd]">
                                                {{ relatedPost.title }}
                                            </h4>
                                            <p class="mt-1 text-xs text-muted">
                                                {{ formatDate(relatedPost.published_at) }}
                                            </p>
                                        </Link>
                                    </article>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </FrontendLayout>
</template>

<script setup lang="ts">
import BlogComments from '@/components/Frontend/Blog/BlogComments.vue';
import FrontendLayout from '@/layouts/FrontendLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { useIntervalFn } from '@vueuse/core';
import {
    BookOpen as BookOpenIcon,
    Calendar as CalendarIcon,
    ChevronRight as ChevronRightIcon,
    Clock as ClockIcon,
    Eye as EyeIcon,
    Facebook,
    Heart as HeartIcon,
    Link as LinkIcon,
    List as ListIcon,
    MessageCircle,
    ThumbsUp as ThumbsUpIcon,
    Twitter,
    User as UserIcon,
} from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref } from 'vue';

interface BlogPost {
    id: number;
    slug: string;
    title: string;
    excerpt?: string;
    content: string;
    featured_image_url?: string;
    published_at: string;
    reading_time?: number;
    views_count?: number;
    likes_count?: number;
    helpful_count?: number;
    meta_title?: string;
    meta_description?: string;
    meta_keywords?: string;
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
    post: BlogPost;
    comments: any[];
    relatedPosts: BlogPost[];
    userReactions: {
        like: boolean;
        helpful: boolean;
    };
}

const props = defineProps<Props>();

const page = usePage();
const isAuthenticated = computed(() => !!page.props.auth?.user);

// Reactive reaction counts
const reactionCounts = ref({
    likes_count: props.post.likes_count || 0,
    helpful_count: props.post.helpful_count || 0,
    like_active: props.userReactions.like,
    helpful_active: props.userReactions.helpful,
});

const reactionLoading = ref(false);
const likeCountRef = ref<HTMLElement>();
const helpfulCountRef = ref<HTMLElement>();

const tableOfContents = ref<Array<{ id: string; text: string; level: number }>>([]);

const generateTableOfContents = () => {
    const headings = document.querySelectorAll('.blog-content h2, .blog-content h3, .blog-content h4');
    tableOfContents.value = Array.from(headings).map((heading, index) => {
        const id = `heading-${index}`;
        heading.id = id;
        return {
            id,
            text: heading.textContent || '',
            level: parseInt(heading.tagName.charAt(1)),
        };
    });
};

const formatDate = (date: string) => {
    const d = new Date(date);
    const options: Intl.DateTimeFormatOptions = {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    };
    return new Intl.DateTimeFormat('bn-BD', options).format(d);
};

const formatNumber = (num: number) => {
    if (num >= 1000000) {
        return (num / 1000000).toFixed(1) + 'M';
    }
    if (num >= 1000) {
        return (num / 1000).toFixed(1) + 'k';
    }
    return num.toString();
};

const getInitials = (name: string): string => {
    return name
        .split(' ')
        .map((word) => word.charAt(0))
        .join('')
        .toUpperCase()
        .slice(0, 2);
};

const stripHtml = (html: string): string => {
    // Create a new DOMParser to safely parse HTML without executing scripts
    const parser = new DOMParser();
    const doc = parser.parseFromString(html, 'text/html');
    return doc.body.textContent || doc.body.innerText || '';
};

const shareOnFacebook = () => {
    const url = encodeURIComponent(window.location.href);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
};

const shareOnTwitter = () => {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent(props.post.title);
    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank');
};

const shareOnWhatsApp = () => {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent(props.post.title);
    window.open(`https://wa.me/?text=${text}%20${url}`, '_blank');
};

const copyLink = async () => {
    try {
        await navigator.clipboard.writeText(window.location.href);
        // Show success message
    } catch (err) {
        console.error('Failed to copy link:', err);
    }
};

const toggleReaction = (type: 'like' | 'helpful') => {
    if (reactionLoading.value) return;

    reactionLoading.value = true;

    // Optimistically update UI immediately using Vue reactivity
    const wasActive = type === 'like' ? reactionCounts.value.like_active : reactionCounts.value.helpful_active;
    const currentCount = type === 'like' ? reactionCounts.value.likes_count : reactionCounts.value.helpful_count;

    if (type === 'like') {
        reactionCounts.value.like_active = !wasActive;
        reactionCounts.value.likes_count = wasActive ? currentCount - 1 : currentCount + 1;
        // Trigger count animation
        if (likeCountRef.value) {
            likeCountRef.value.classList.add('count-change');
            setTimeout(() => likeCountRef.value?.classList.remove('count-change'), 400);
        }
    } else {
        reactionCounts.value.helpful_active = !wasActive;
        reactionCounts.value.helpful_count = wasActive ? currentCount - 1 : currentCount + 1;
        // Trigger count animation
        if (helpfulCountRef.value) {
            helpfulCountRef.value.classList.add('count-change');
            setTimeout(() => helpfulCountRef.value?.classList.remove('count-change'), 400);
        }
    }

    // Send request to backend using Inertia
    router.post(
        route('frontend.blog.posts.react', { post: props.post.id }),
        { type: type },
        {
            preserveScroll: true,
            preserveState: true,
            only: [],
            onError: (errors) => {
                // Revert optimistic update on error
                if (type === 'like') {
                    reactionCounts.value.like_active = wasActive;
                    reactionCounts.value.likes_count = currentCount;
                } else {
                    reactionCounts.value.helpful_active = wasActive;
                    reactionCounts.value.helpful_count = currentCount;
                }
                console.error('Error toggling reaction:', errors);
            },
            onFinish: () => {
                reactionLoading.value = false;
            },
        },
    );
};

const handleCommentAdded = (comment: any) => {
    console.log('New comment added:', comment);
};

onMounted(() => {
    generateTableOfContents();
});

// Poll for live reaction updates every 2 minutes
const { pause: pauseReactionPolling, resume: resumeReactionPolling } = useIntervalFn(
    () => {
        // Only poll if the page is visible to avoid unnecessary requests
        if (!document.hidden) {
            router.reload({
                only: ['post', 'userReactions'],
                onSuccess: (page) => {
                    // Update reaction counts with fresh data
                    const freshPost = page.props.post as any;
                    if (freshPost) {
                        reactionCounts.value = {
                            likes_count: freshPost.likes_count || 0,
                            helpful_count: freshPost.helpful_count || 0,
                            like_active: page.props.userReactions?.like || false,
                            helpful_active: page.props.userReactions?.helpful || false,
                        };
                    }
                },
            });
        }
    },
    120000,
    { immediate: false },
); // Poll every 2 minutes

// Start polling when component mounts
onMounted(() => {
    resumeReactionPolling();
});

// Pause polling when page is hidden, resume when visible
const handleVisibilityChange = () => {
    if (document.hidden) {
        pauseReactionPolling();
    } else {
        resumeReactionPolling();
    }
};

onMounted(() => {
    document.addEventListener('visibilitychange', handleVisibilityChange);
});

// Cleanup on unmount
onUnmounted(() => {
    pauseReactionPolling();
    document.removeEventListener('visibilitychange', handleVisibilityChange);
});
</script>

<style scoped>
.prose {
    color: var(--color-iqra-neutral-700);
    line-height: 1.6;
}

.prose h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--color-iqra-text-primary);
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.prose h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--color-iqra-text-primary);
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
}

.prose h4 {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--color-iqra-text-primary);
    margin-top: 1rem;
    margin-bottom: 0.5rem;
}

.prose p {
    margin-bottom: 1rem;
}

.prose ul,
.prose ol {
    margin-bottom: 1rem;
    padding-left: 1.5rem;
}

.prose li {
    margin-bottom: 0.5rem;
}

.prose blockquote {
    border-left: 4px solid #5f5fcd;
    padding-left: 1rem;
    font-style: italic;
    color: var(--color-iqra-text-secondary);
    margin: 1.5rem 0;
}

.prose code {
    background-color: var(--color-iqra-neutral-100);
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.875rem;
}

.prose pre {
    background-color: var(--color-iqra-neutral-900);
    color: var(--color-iqra-neutral-100);
    padding: 1rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin: 1.5rem 0;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Custom reaction button animations */
@keyframes heartBeat {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.15);
    }
    100% {
        transform: scale(1);
    }
}

@keyframes thumbsUp {
    0% {
        transform: scale(1) rotate(0deg);
    }
    50% {
        transform: scale(1.1) rotate(-5deg);
    }
    100% {
        transform: scale(1) rotate(0deg);
    }
}

.reaction-btn-like:active .heart-icon {
    animation: heartBeat 0.3s ease-in-out;
}

.reaction-btn-helpful:active .thumbs-icon {
    animation: thumbsUp 0.3s ease-in-out;
}

/* Number count animation */
@keyframes numberPop {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.2);
    }
    100% {
        transform: scale(1);
    }
}

.count-change {
    animation: numberPop 0.4s ease-in-out;
}
</style>
