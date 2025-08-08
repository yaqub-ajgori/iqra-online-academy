<template>
    <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
        <!-- Now Playing Info -->
        <div v-if="currentTrack" class="mb-4 flex items-center space-x-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27]">
                <component :is="isPlaying ? PauseIcon : PlayIcon" class="h-6 w-6 text-white" />
            </div>
            <div class="min-w-0 flex-1">
                <div class="truncate font-semibold text-gray-900">
                    {{ currentTrack.title }}
                </div>
                <div class="truncate text-sm text-gray-600">
                    {{ currentTrack.subtitle }}
                </div>
            </div>
        </div>

        <!-- Audio Controls -->
        <div class="mb-3 flex items-center justify-center space-x-4">
            <button
                @click="togglePlayPause"
                :disabled="!audioUrl || audioLoading"
                class="flex h-12 w-12 items-center justify-center rounded-full bg-[#5f5fcd] text-white transition-colors hover:bg-[#4a4aa6] disabled:bg-gray-300"
            >
                <div v-if="audioLoading" class="h-5 w-5 animate-spin rounded-full border-b-2 border-white"></div>
                <component v-else :is="isPlaying ? PauseIcon : PlayIcon" class="h-5 w-5" />
            </button>

            <button @click="stop" :disabled="!audioUrl" class="rounded-lg p-2 transition-colors hover:bg-gray-100 disabled:opacity-50" title="থামান">
                <Square class="h-4 w-4 text-gray-600" />
            </button>
        </div>

        <!-- Progress Bar -->
        <div v-if="duration > 0" class="mb-2">
            <div class="h-2 w-full cursor-pointer rounded-full bg-gray-200" @click="seekTo" ref="progressBar">
                <div
                    class="h-2 rounded-full bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] transition-all duration-300"
                    :style="{ width: `${progress}%` }"
                ></div>
            </div>
        </div>

        <!-- Time Display -->
        <div v-if="duration > 0" class="flex justify-between text-xs text-gray-500">
            <span>{{ formatTime(currentTime) }}</span>
            <span>{{ formatTime(duration) }}</span>
        </div>

        <!-- Error Message -->
        <div v-if="errorMessage" class="mt-3 rounded-lg border border-red-200 bg-red-50 p-3">
            <div class="flex items-center">
                <AlertCircleIcon class="mr-2 h-4 w-4 text-red-500" />
                <span class="text-sm text-red-700">{{ errorMessage }}</span>
                <button @click="retry" class="ml-auto text-sm text-red-600 underline hover:text-red-800">আবার চেষ্টা করুন</button>
            </div>
        </div>

        <!-- Hidden Audio Element -->
        <audio
            ref="audioElement"
            @loadstart="handleLoadStart"
            @canplay="handleCanPlay"
            @play="handlePlay"
            @pause="handlePause"
            @ended="handleEnded"
            @timeupdate="handleTimeUpdate"
            @error="handleError"
            @loadedmetadata="handleLoadedMetadata"
            preload="metadata"
            crossorigin="anonymous"
            class="hidden"
        ></audio>
    </div>
</template>

<script setup>
import { AlertCircleIcon, PauseIcon, PlayIcon, Square } from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';

const props = defineProps({
    audioUrl: {
        type: String,
        default: '',
    },
    audioUrls: {
        type: Array,
        default: () => [],
    },
    currentTrack: {
        type: Object,
        default: null,
    },
    autoPlay: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['play', 'pause', 'stop', 'ended', 'error', 'timeupdate']);

// Audio element and state
const audioElement = ref(null);
const isPlaying = ref(false);
const audioLoading = ref(false);
const currentTime = ref(0);
const duration = ref(0);
const errorMessage = ref('');
const progressBar = ref(null);
const currentUrlIndex = ref(0);
const availableUrls = ref([]);

// Computed
const progress = computed(() => {
    if (!duration.value) return 0;
    return (currentTime.value / duration.value) * 100;
});

// Methods
const toBanglaNumber = (num) => {
    const en = '0123456789';
    const bn = '০১২৩৪৫৬৭৮৯';
    return num
        .toString()
        .split('')
        .map((d) => (en.includes(d) ? bn[en.indexOf(d)] : d))
        .join('');
};

const formatTime = (seconds) => {
    if (!seconds || isNaN(seconds)) return '০:০০';
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${toBanglaNumber(mins)}:${toBanglaNumber(secs.toString().padStart(2, '0'))}`;
};

const tryNextAudioSource = async () => {
    if (currentUrlIndex.value < availableUrls.value.length - 1) {
        currentUrlIndex.value++;
        const nextUrl = availableUrls.value[currentUrlIndex.value];
        // Trying fallback audio source

        audioElement.value.src = nextUrl;
        audioElement.value.load();

        try {
            await audioElement.value.play();
            return true;
        } catch (error) {
            // Fallback source failed
            return await tryNextAudioSource();
        }
    }
    return false;
};

const loadAudioWithFallback = async (url) => {
    if (!audioElement.value) return false;

    // Setup available URLs
    availableUrls.value = props.audioUrls.length > 0 ? [...props.audioUrls] : [url];
    currentUrlIndex.value = 0;

    // If the primary URL is not in the array, add it as first option
    if (url && !availableUrls.value.includes(url)) {
        availableUrls.value.unshift(url);
    }

    // Available audio sources loaded

    const firstUrl = availableUrls.value[0];
    if (!firstUrl) return false;

    audioElement.value.src = firstUrl;
    audioElement.value.load();

    try {
        await audioElement.value.play();
        return true;
    } catch (error) {
        // Primary audio source failed
        return await tryNextAudioSource();
    }
};

const togglePlayPause = async () => {
    if (!audioElement.value) return;

    try {
        errorMessage.value = '';

        if (isPlaying.value) {
            audioElement.value.pause();
        } else {
            audioLoading.value = true;

            // Check if we need to load a new audio source
            const currentUrl = props.audioUrl || availableUrls.value[0];
            if (!audioElement.value.src || audioElement.value.src === window.location.href || !availableUrls.value.includes(audioElement.value.src)) {
                const loaded = await loadAudioWithFallback(currentUrl);
                if (!loaded) {
                    throw new Error('সব অডিও সোর্স ব্যর্থ হয়েছে');
                }
            } else {
                // Just play current loaded audio
                await audioElement.value.play();
            }
        }
    } catch (error) {
        // Audio play error

        if (error.name === 'NotAllowedError') {
            errorMessage.value = 'ব্রাউজার অডিও চালানোর অনুমতি দেয়নি। পেজ রিফ্রেশ করে আবার চেষ্টা করুন।';
        } else if (error.name === 'NotSupportedError') {
            errorMessage.value = 'এই অডিও ফরম্যাট সাপোর্ট করা হয় না';
        } else {
            errorMessage.value = error.message || 'অডিও চালাতে সমস্যা হয়েছে';
        }

        audioLoading.value = false;
        emit('error', error);
    }
};

const stop = () => {
    if (!audioElement.value) return;

    audioElement.value.pause();
    audioElement.value.currentTime = 0;
    currentTime.value = 0;
    isPlaying.value = false;
    emit('stop');
};

const seekTo = (event) => {
    if (!audioElement.value || !progressBar.value || !duration.value) return;

    const rect = progressBar.value.getBoundingClientRect();
    const clickX = event.clientX - rect.left;
    const percentage = clickX / rect.width;
    const newTime = percentage * duration.value;

    audioElement.value.currentTime = newTime;
    currentTime.value = newTime;
};

const retry = () => {
    errorMessage.value = '';
    if (props.audioUrl) {
        audioElement.value.src = props.audioUrl;
        audioElement.value.load();
    }
};

// Audio event handlers
const handleLoadStart = () => {
    audioLoading.value = true;
};

const handleCanPlay = () => {
    audioLoading.value = false;

    // Auto-play if requested
    if (props.autoPlay && !isPlaying.value) {
        togglePlayPause();
    }
};

const handlePlay = () => {
    isPlaying.value = true;
    audioLoading.value = false;
    emit('play');
};

const handlePause = () => {
    isPlaying.value = false;
    emit('pause');
};

const handleEnded = () => {
    isPlaying.value = false;
    currentTime.value = 0;
    emit('ended');
};

const handleTimeUpdate = () => {
    if (!audioElement.value) return;

    currentTime.value = audioElement.value.currentTime;
    emit('timeupdate', {
        currentTime: currentTime.value,
        duration: duration.value,
        progress: progress.value,
    });
};

const handleError = async (event) => {
    // Audio error

    // Try next audio source if available
    if (currentUrlIndex.value < availableUrls.value.length - 1) {
        // Trying next audio source due to error
        const success = await tryNextAudioSource();
        if (success) {
            return; // Successfully loaded fallback
        }
    }

    audioLoading.value = false;
    isPlaying.value = false;
    errorMessage.value = 'সব অডিও সোর্স লোড করতে ব্যর্থ হয়েছে';
    emit('error', event);
};

const handleLoadedMetadata = () => {
    if (audioElement.value) {
        duration.value = audioElement.value.duration || 0;
    }
};

// Watch for URL changes
watch(
    () => props.audioUrl,
    (newUrl, oldUrl) => {
        if (newUrl && newUrl !== oldUrl && audioElement.value) {
            // Audio URL changed

            // Stop current playback
            audioElement.value.pause();
            currentTime.value = 0;
            isPlaying.value = false;
            errorMessage.value = '';

            // Load new audio
            audioElement.value.src = newUrl;
            audioElement.value.load();
        }
    },
    { immediate: true },
);

// Lifecycle
onMounted(() => {
    if (audioElement.value && props.audioUrl) {
        audioElement.value.src = props.audioUrl;
        audioElement.value.load();
    }
});

onUnmounted(() => {
    if (audioElement.value) {
        audioElement.value.pause();
        audioElement.value.src = '';
    }
});

// Expose methods for parent component
defineExpose({
    play: () => togglePlayPause(),
    pause: () => audioElement.value?.pause(),
    stop,
    isPlaying: () => isPlaying.value,
});
</script>
