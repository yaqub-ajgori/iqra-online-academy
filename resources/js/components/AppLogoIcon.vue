<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80" :class="className" v-bind="$attrs" role="img" aria-label="Arabic ٱقۡرَأۡ logo">
        <defs>
            <linearGradient id="iqraLogoGradient" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0%" stop-color="#5f5fcd" />
                <stop offset="100%" stop-color="#2d5a27" />
            </linearGradient>
        </defs>
        <!-- Icon: Arabic word ٱقۡرَأۡ (Iqra) in bold, elegant script -->
        <circle cx="40" cy="40" r="36" fill="url(#iqraLogoGradient)" />
        <text
            x="40"
            y="50"
            text-anchor="middle"
            dominant-baseline="middle"
            font-family="'Amiri', 'Scheherazade', 'Noto Naskh Arabic', serif"
            font-size="32"
            font-weight="bold"
            fill="#fff"
            direction="rtl"
        >
            ٱقۡرَأۡ
        </text>
    </svg>
</template>
