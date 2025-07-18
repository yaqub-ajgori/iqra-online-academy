<template>
    <article
        class="group shadow-islamic hover:shadow-islamic-lg relative transform overflow-hidden rounded-2xl border border-neutral-100 bg-white transition-all duration-500 hover:-translate-y-2 hover:border-[#5f5fcd]/20"
    >
        <!-- Featured Image -->
        <div class="relative h-48 overflow-hidden">
            <img
                v-if="post.featured_image"
                :src="post.featured_image_url || post.featured_image"
                :alt="post.title"
                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                loading="lazy"
            />
            <div v-else class="flex h-full w-full items-center justify-center bg-gradient-to-br from-[#5f5fcd]/10 to-[#2d5a27]/10">
                <NewspaperIcon class="h-16 w-16 text-[#5f5fcd]/30" />
            </div>

            <!-- Category Badge -->
            <div class="absolute top-3 left-3">
                <span
                    class="rounded-full px-3 py-1 text-xs font-semibold text-white shadow-md"
                    :style="{ backgroundColor: post.category?.color || '#5f5fcd' }"
                >
                    {{ post.category?.name }}
                </span>
            </div>

            <!-- Featured/Sticky Badges -->
            <div class="absolute top-3 right-3 flex flex-col space-y-2">
                <span
                    v-if="post.is_featured"
                    class="rounded-full border border-yellow-300 bg-gradient-to-r from-yellow-400 to-amber-500 px-3 py-1 text-xs font-semibold text-white shadow-lg"
                >
                    ফিচার্ড
                </span>
                <span
                    v-if="post.is_sticky"
                    class="rounded-full border border-blue-400 bg-gradient-to-r from-blue-500 to-blue-600 px-3 py-1 text-xs font-semibold text-white shadow-lg"
                >
                    পিন করা
                </span>
            </div>

            <!-- Image Overlay -->
            <div
                class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"
            ></div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Meta Information -->
            <div class="mb-3 flex items-center justify-between text-sm text-muted">
                <div class="flex items-center space-x-4">
                    <span class="flex items-center">
                        <CalendarIcon class="mr-1 h-4 w-4" />
                        {{ formatDate(post.published_at) }}
                    </span>
                    <span class="flex items-center">
                        <ClockIcon class="mr-1 h-4 w-4" />
                        {{ post.reading_time || 5 }} মিনিট
                    </span>
                </div>
                <span class="flex items-center">
                    <EyeIcon class="mr-1 h-4 w-4" />
                    {{ formatNumber(post.views_count || 0) }}
                </span>
            </div>

            <!-- Title -->
            <h3 class="mb-3 line-clamp-2 text-lg leading-tight font-bold text-primary transition-colors duration-200 group-hover:text-[#5f5fcd]">
                <Link :href="postUrl" class="focus:outline-none">
                    {{ post.title }}
                </Link>
            </h3>

            <!-- Excerpt -->
            <p class="mb-4 line-clamp-3 text-sm leading-relaxed text-secondary">
                {{ post.excerpt || stripHtml(post.content).substring(0, 150) + '...' }}
            </p>

            <!-- Author Info -->
            <div v-if="post.author" class="mb-4 flex items-center">
                <img v-if="post.author.avatar" :src="post.author.avatar" :alt="post.author.name" class="mr-3 h-8 w-8 rounded-full" />
                <div v-else class="mr-3 flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27]">
                    <span class="text-xs font-semibold text-white">
                        {{ getInitials(post.author.name) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm font-medium text-neutral-700">{{ post.author.name }}</p>
                    <p class="text-xs text-muted">লেখক</p>
                </div>
            </div>

            <!-- Tags -->
            <div v-if="post.tags && post.tags.length > 0" class="mb-4 flex flex-wrap gap-2">
                <span
                    v-for="tag in post.tags.slice(0, 3)"
                    :key="tag.id"
                    class="inline-flex items-center rounded-md bg-neutral-100 px-2 py-1 text-xs font-medium text-neutral-600 transition-colors hover:bg-neutral-200"
                >
                    #{{ tag.name }}
                </span>
                <span v-if="post.tags.length > 3" class="text-xs text-muted"> +{{ post.tags.length - 3 }} আরও </span>
            </div>

            <!-- Actions -->
            <div class="mt-4 flex items-center justify-center border-t border-neutral-200 pt-4">
                <Link :href="postUrl" class="flex items-center text-sm font-medium text-[#5f5fcd] transition-colors hover:text-[#4a4aa6]">
                    পড়ুন
                    <ArrowRightIcon class="ml-1 h-4 w-4" />
                </Link>
            </div>
        </div>

        <!-- Decorative Corner -->
        <div class="absolute top-0 right-0 h-20 w-20 overflow-hidden">
            <div
                class="absolute top-3 right-3 h-10 w-10 rotate-45 transform rounded-lg bg-gradient-to-br from-[#d4a574]/30 to-[#5f5fcd]/20 transition-transform duration-300 group-hover:scale-110"
            ></div>
        </div>
    </article>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    ArrowRight as ArrowRightIcon,
    Calendar as CalendarIcon,
    Clock as ClockIcon,
    Eye as EyeIcon,
    Newspaper as NewspaperIcon,
} from 'lucide-vue-next';
import { computed } from 'vue';

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
    post: BlogPost;
}

const props = defineProps<Props>();

const postUrl = computed(() => route('frontend.blog.show', { slug: props.post.slug }));

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
    const tmp = document.createElement('div');
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || '';
};
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
