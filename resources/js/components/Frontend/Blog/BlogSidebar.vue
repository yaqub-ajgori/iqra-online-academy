<template>
    <aside class="space-y-8">
        <!-- Categories -->
        <div class="shadow-islamic rounded-2xl border border-neutral-100 bg-white p-6">
            <h3 class="mb-4 flex items-center text-lg font-semibold text-primary">
                <TagIcon class="mr-2 h-5 w-5 text-[#5f5fcd]" />
                বিভাগসমূহ
            </h3>
            <ul class="space-y-2">
                <li>
                    <button
                        @click="clearFilters"
                        class="group flex w-full cursor-pointer items-center justify-between rounded-lg p-3 text-left transition-colors hover:bg-neutral-50"
                        :class="{ 'bg-[#5f5fcd]/10 text-[#5f5fcd]': !activeCategory && !activeTag }"
                    >
                        <div class="flex items-center">
                            <div class="mr-3 h-3 w-3 rounded-full bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27]"></div>
                            <span class="font-medium">সব পোস্ট</span>
                        </div>
                    </button>
                </li>
                <li v-for="category in categories" :key="category.id">
                    <button
                        @click="filterByCategory(category.slug)"
                        class="group flex w-full cursor-pointer items-center justify-between rounded-lg p-3 text-left transition-colors hover:bg-neutral-50"
                        :class="{ 'bg-[#5f5fcd]/10 text-[#5f5fcd]': category.slug === activeCategory }"
                    >
                        <div class="flex items-center">
                            <div class="mr-3 h-3 w-3 rounded-full" :style="{ backgroundColor: category.color || '#5f5fcd' }"></div>
                            <span class="font-medium">{{ category.name }}</span>
                        </div>
                        <span class="text-sm text-muted group-hover:text-neutral-700">
                            {{ category.posts_count }}
                        </span>
                    </button>
                </li>
            </ul>
        </div>

        <!-- Popular Tags -->
        <div class="shadow-islamic rounded-2xl border border-neutral-100 bg-white p-6">
            <h3 class="mb-4 flex items-center text-lg font-semibold text-primary">
                <HashtagIcon class="mr-2 h-5 w-5 text-[#5f5fcd]" />
                জনপ্রিয় ট্যাগ
            </h3>
            <div class="flex flex-wrap gap-2">
                <button
                    v-for="tag in popularTags"
                    :key="tag.id"
                    @click="filterByTag(tag.slug)"
                    class="inline-flex cursor-pointer items-center rounded-lg bg-neutral-100 px-3 py-2 text-sm font-medium text-neutral-700 transition-colors hover:bg-[#5f5fcd] hover:text-white"
                    :class="{ 'bg-[#5f5fcd] text-white': tag.slug === activeTag }"
                >
                    #{{ tag.name }}
                    <span class="ml-1 text-xs opacity-75">({{ tag.posts_count }})</span>
                </button>
            </div>
        </div>

        <!-- Recent Posts -->
        <div class="shadow-islamic rounded-2xl border border-neutral-100 bg-white p-6">
            <h3 class="mb-4 flex items-center text-lg font-semibold text-primary">
                <ClockIcon class="mr-2 h-5 w-5 text-[#5f5fcd]" />
                সাম্প্রতিক পোস্ট
                <span v-if="activeCategory || activeTag" class="ml-2 rounded-full bg-[#5f5fcd]/10 px-2 py-1 text-xs font-normal text-[#5f5fcd]">
                    ফিল্টার করা
                </span>
            </h3>
            <div class="space-y-4">
                <article v-for="post in recentPosts" :key="post.id" class="group cursor-pointer">
                    <Link :href="route('frontend.blog.show', { slug: post.slug })" class="block">
                        <div class="flex space-x-3">
                            <img
                                v-if="post.featured_image_url"
                                :src="post.featured_image_url"
                                :alt="post.title"
                                class="h-16 w-16 flex-shrink-0 rounded-lg object-cover"
                            />
                            <div
                                v-else
                                class="flex h-16 w-16 flex-shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-[#5f5fcd]/10 to-[#2d5a27]/10"
                            >
                                <NewspaperIcon class="h-6 w-6 text-[#5f5fcd]/30" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="line-clamp-2 text-sm font-medium text-primary transition-colors group-hover:text-[#5f5fcd]">
                                    {{ post.title }}
                                </h4>
                                <p class="mt-1 flex items-center text-xs text-muted">
                                    <CalendarIcon class="mr-1 h-3 w-3" />
                                    {{ formatDate(post.published_at) }}
                                </p>
                            </div>
                        </div>
                    </Link>
                </article>
            </div>
        </div>
    </aside>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { Calendar as CalendarIcon, Clock as ClockIcon, Hash as HashtagIcon, Newspaper as NewspaperIcon, Tag as TagIcon } from 'lucide-vue-next';

interface Category {
    id: number;
    name: string;
    slug: string;
    color?: string;
    posts_count: number;
}

interface Tag {
    id: number;
    name: string;
    slug: string;
    posts_count: number;
}

interface RecentPost {
    id: number;
    slug: string;
    title: string;
    featured_image_url?: string;
    published_at: string;
}

interface Props {
    categories: Category[];
    popularTags: Tag[];
    recentPosts: RecentPost[];
    activeCategory?: string;
    activeTag?: string;
}

defineProps<Props>();

const filterByCategory = (categorySlug: string) => {
    const url = new URL(window.location.href);
    url.pathname = '/blog';
    url.searchParams.delete('page');
    url.searchParams.delete('tag'); // Clear tag filter when filtering by category

    if (categorySlug) {
        url.searchParams.set('category', categorySlug);
    } else {
        url.searchParams.delete('category');
    }

    router.get(
        url.toString(),
        {},
        {
            preserveState: false,
            preserveScroll: false,
        },
    );
};

const filterByTag = (tagSlug: string) => {
    const url = new URL(window.location.href);
    url.pathname = '/blog';
    url.searchParams.delete('page');
    url.searchParams.delete('category'); // Clear category filter when filtering by tag

    if (tagSlug) {
        url.searchParams.set('tag', tagSlug);
    } else {
        url.searchParams.delete('tag');
    }

    router.get(
        url.toString(),
        {},
        {
            preserveState: false,
            preserveScroll: false,
        },
    );
};

const clearFilters = () => {
    const url = new URL(window.location.href);
    url.pathname = '/blog';
    url.searchParams.delete('page');
    url.searchParams.delete('category');
    url.searchParams.delete('tag');

    router.get(
        url.toString(),
        {},
        {
            preserveState: false,
            preserveScroll: false,
        },
    );
};

const formatDate = (date: string) => {
    const d = new Date(date);
    const options: Intl.DateTimeFormatOptions = {
        month: 'short',
        day: 'numeric',
    };
    return new Intl.DateTimeFormat('bn-BD', options).format(d);
};
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
