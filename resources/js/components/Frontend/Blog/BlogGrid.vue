<template>
    <div class="blog-grid">
        <div v-if="loading" class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
            <BlogCardSkeleton v-for="i in 6" :key="i" />
        </div>

        <div v-else-if="posts.length > 0" class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
            <BlogCard v-for="post in posts" :key="post.id" :post="post" />
        </div>

        <div v-else class="py-12 text-center">
            <NewspaperIcon class="mx-auto mb-4 h-16 w-16 text-neutral-300" />
            <p class="text-lg text-muted">কোন পোস্ট পাওয়া যায়নি</p>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Newspaper as NewspaperIcon } from 'lucide-vue-next';
import BlogCard from './BlogCard.vue';
import BlogCardSkeleton from './BlogCardSkeleton.vue';

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
    loading?: boolean;
}

defineProps<Props>();
</script>
