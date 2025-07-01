<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
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
                            <div class="w-12 h-12 bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] rounded-lg flex items-center justify-center shadow-lg">
                              <span class="text-white font-bold text-xl">ই</span>
                            </div>
                            <div class="absolute -top-1 -right-1 w-3 h-3 bg-[#d4a574] rounded-full animate-pulse"></div>
                        </div>
                        <span class="text-lg font-bold text-gradient-islamic">ইকরা</span>
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
