<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="flex min-h-svh flex-col items-center justify-center gap-6 bg-background p-6 md:p-10">
        <div class="w-full max-w-sm">
            <div class="flex flex-col gap-8">
                <div class="flex flex-col items-center gap-4">
                    <Link :href="route('frontend.home')" class="flex flex-col items-center gap-2 font-medium">
                        <div class="relative mb-1">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] shadow-lg"
                            >
                                <span class="text-xl font-bold text-white">ই</span>
                            </div>
                            <div class="absolute -top-1 -right-1 h-3 w-3 animate-pulse rounded-full bg-[#d4a574]"></div>
                        </div>
                        <span class="text-gradient-islamic text-lg font-bold">ইকরা</span>
                    </Link>
                    <div class="space-y-2 text-center">
                        <h1 class="text-xl font-medium">{{ title }}</h1>
                        <p class="text-center text-sm text-muted-foreground">{{ description }}</p>
                    </div>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>
