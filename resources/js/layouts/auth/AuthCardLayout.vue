<script setup lang="ts">
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="flex min-h-svh flex-col items-center justify-center gap-6 bg-muted p-6 md:p-10">
        <div class="flex w-full max-w-md flex-col gap-6">
            <Link :href="route('frontend.home')" class="flex items-center space-x-3 self-center font-medium">
                <div class="relative">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] shadow-lg">
                        <span class="text-lg font-bold text-white">ই</span>
                    </div>
                    <div class="absolute -top-1 -right-1 h-3 w-3 animate-pulse rounded-full bg-[#d4a574]"></div>
                </div>
                <div>
                    <h1 class="text-gradient-islamic text-xl font-bold">ইকরা</h1>
                </div>
            </Link>

            <div class="flex flex-col gap-6">
                <Card class="rounded-xl">
                    <CardHeader class="px-10 pt-8 pb-0 text-center">
                        <CardTitle class="text-xl">{{ title }}</CardTitle>
                        <CardDescription>
                            {{ description }}
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="px-10 py-8">
                        <slot />
                    </CardContent>
                </Card>
            </div>
        </div>
    </div>
</template>
