<template>
    <div
        class="flex h-full w-full items-center justify-center rounded-lg border-2 border-dashed border-[#5f5fcd]/30 bg-gradient-to-br from-[#5f5fcd]/10 to-[#2d5a27]/10"
        :class="containerClass"
    >
        <div class="p-4 text-center">
            <!-- Islamic-themed SVG Icon -->
            <svg class="mx-auto mb-3 h-12 w-12 text-[#5f5fcd]/60" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                <path d="M12 2v20" />
                <path d="M2 7l10 5 10-5" />
                <path d="M2 12l10 5 10-5" />
                <path d="M2 17l10 5 10-5" />
            </svg>

            <!-- Course Icon -->
            <div class="mx-auto mb-2 flex h-8 w-8 items-center justify-center rounded-full bg-[#5f5fcd]/20">
                <svg class="h-4 w-4 text-[#5f5fcd]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                    />
                </svg>
            </div>

            <!-- Text -->
            <p class="text-xs font-medium text-[#5f5fcd]/70">
                {{ text || 'Course Image' }}
            </p>
        </div>
    </div>
</template>

<script setup lang="ts">
interface Props {
    text?: string;
    containerClass?: string;
}

defineProps<Props>();
</script>
