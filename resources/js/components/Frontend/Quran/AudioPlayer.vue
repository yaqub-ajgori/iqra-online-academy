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
        <div class="mb-3 flex items-center justify-between">
            <button
                @click="togglePlayPause"
                :disabled="!currentTrack || audioLoading"
                class="flex h-12 w-12 items-center justify-center rounded-full bg-[#5f5fcd] text-white transition-colors hover:bg-[#4a4aa6] disabled:bg-gray-300"
            >
                <div v-if="audioLoading" class="h-5 w-5 animate-spin rounded-full border-b-2 border-white"></div>
                <component v-else :is="isPlaying ? PauseIcon : PlayIcon" class="h-5 w-5" />
            </button>

            <div class="flex items-center space-x-2">
                <button
                    @click="stop"
                    :disabled="!currentTrack"
                    class="rounded-lg p-2 transition-colors hover:bg-gray-100 disabled:opacity-50"
                    title="থামান"
                >
                    <StopIcon class="h-4 w-4 text-gray-600" />
                </button>

                <button
                    @click="toggleMute"
                    class="rounded-lg p-2 transition-colors hover:bg-gray-100"
                    :title="isMuted ? 'শব্দ চালু করুন' : 'শব্দ বন্ধ করুন'"
                >
                    <component :is="isMuted ? VolumeXIcon : Volume2Icon" class="h-4 w-4 text-gray-600" />
                </button>

                <!-- Volume Slider -->
                <div class="flex w-20 items-center space-x-2">
                    <input
                        type="range"
                        v-model="volume"
                        @input="updateVolume"
                        min="0"
                        max="1"
                        step="0.1"
                        class="slider h-1 w-full cursor-pointer appearance-none rounded-lg bg-gray-200"
                    />
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="mb-2">
            <div class="h-2 w-full cursor-pointer rounded-full bg-gray-200" @click="seekTo" ref="progressBar">
                <div
                    class="h-2 rounded-full bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] transition-all duration-300"
                    :style="{ width: `${progress}%` }"
                ></div>
            </div>
        </div>

        <!-- Time Display -->
        <div class="flex justify-between text-xs text-gray-500">
            <span>{{ formatTime(currentTime) }}</span>
            <span>{{ formatTime(duration) }}</span>
        </div>

        <!-- Error Message -->
        <div v-if="errorMessage" class="mt-3 rounded-lg border border-red-200 bg-red-50 p-3">
            <div class="flex items-center">
                <AlertCircleIcon class="mr-2 h-4 w-4 text-red-500" />
                <span class="text-sm text-red-700">{{ errorMessage }}</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { AlertCircleIcon, PauseIcon, PlayIcon, Square as StopIcon, Volume2Icon, VolumeXIcon } from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';

const props = defineProps({
    audioUrl: {
        type: String,
        default: '',
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
const volume = ref(0.8);
const isMuted = ref(false);
const errorMessage = ref('');
const progressBar = ref(null);

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

const togglePlayPause = async () => {
    if (!audioElement.value) return;

    try {
        errorMessage.value = '';

        if (isPlaying.value) {
            audioElement.value.pause();
            emit('pause');
        } else {
            audioLoading.value = true;

            // Check if audio source is loaded
            if (!audioElement.value.src || audioElement.value.src === window.location.href) {
                throw new Error('No audio source loaded');
            }

            // Attempting to play audio

            // Test if URL is accessible
            try {
                const response = await fetch(audioElement.value.src, { method: 'HEAD' });
                if (!response.ok) {
                    throw new Error(`Audio file not accessible: ${response.status}`);
                }
            } catch (fetchError) {
                // Could not verify audio URL - might be CORS issue
                // Continue anyway, might be CORS issue
            }

            const playPromise = audioElement.value.play();

            if (playPromise !== undefined) {
                await playPromise;
                emit('play');
            }
        }
    } catch (error) {
        // Audio play/pause error

        if (error.name === 'NotAllowedError') {
            errorMessage.value = 'ব্রাউজার অডিও চালানোর অনুমতি দেয়নি';
        } else if (error.name === 'NotSupportedError') {
            errorMessage.value = 'এই অডিও ফরম্যাট সাপোর্ট করা হয় না';
        } else {
            errorMessage.value = 'অডিও চালাতে সমস্যা হয়েছে';
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

const toggleMute = () => {
    if (!audioElement.value) return;

    isMuted.value = !isMuted.value;
    audioElement.value.muted = isMuted.value;
};

const updateVolume = () => {
    if (!audioElement.value) return;

    audioElement.value.volume = volume.value;
    if (volume.value === 0) {
        isMuted.value = true;
        audioElement.value.muted = true;
    } else {
        isMuted.value = false;
        audioElement.value.muted = false;
    }
};

const seekTo = (event) => {
    if (!audioElement.value || !progressBar.value) return;

    const rect = progressBar.value.getBoundingClientRect();
    const clickX = event.clientX - rect.left;
    const percentage = clickX / rect.width;
    const newTime = percentage * duration.value;

    audioElement.value.currentTime = newTime;
    currentTime.value = newTime;
};

const loadAudio = async (url) => {
    if (!audioElement.value || !url) return;

    try {
        audioLoading.value = true;
        errorMessage.value = '';

        // Loading audio URL

        // Stop current playback
        audioElement.value.pause();
        audioElement.value.currentTime = 0;

        // Load new audio
        audioElement.value.src = url;
        audioElement.value.load();

        // Wait for audio to be ready
        await new Promise((resolve, reject) => {
            const handleCanPlay = () => {
                audioElement.value.removeEventListener('canplay', handleCanPlay);
                audioElement.value.removeEventListener('error', handleError);
                resolve();
            };

            const handleError = (error) => {
                audioElement.value.removeEventListener('canplay', handleCanPlay);
                audioElement.value.removeEventListener('error', handleError);
                reject(error);
            };

            audioElement.value.addEventListener('canplay', handleCanPlay);
            audioElement.value.addEventListener('error', handleError);
        });

        if (props.autoPlay) {
            await audioElement.value.play();
        }
    } catch (error) {
        // Audio loading error
        errorMessage.value = 'অডিও লোড করতে সমস্যা হয়েছে';
        audioLoading.value = false;
        emit('error', error);
    }
};

// Audio event handlers
const handleLoadStart = () => {
    audioLoading.value = true;
};

const handleCanPlay = () => {
    audioLoading.value = false;
};

const handlePlay = () => {
    isPlaying.value = true;
    audioLoading.value = false;
};

const handlePause = () => {
    isPlaying.value = false;
};

const handleEnded = () => {
    isPlaying.value = false;
    currentTime.value = 0;
    emit('ended');
};

const handleTimeUpdate = () => {
    if (!audioElement.value) return;

    currentTime.value = audioElement.value.currentTime;
    duration.value = audioElement.value.duration || 0;
    emit('timeupdate', {
        currentTime: currentTime.value,
        duration: duration.value,
        progress: progress.value,
    });
};

const handleError = () => {
    audioLoading.value = false;
    isPlaying.value = false;
    errorMessage.value = 'অডিও চালাতে ত্রুটি হয়েছে';
    emit('error', new Error('Audio playback failed'));
};

// Watch for URL changes
watch(
    () => props.audioUrl,
    (newUrl) => {
        if (newUrl) {
            loadAudio(newUrl);
        }
    },
    { immediate: true },
);

// Lifecycle
onMounted(() => {
    // Create audio element
    audioElement.value = new Audio();

    // Set initial volume
    audioElement.value.volume = volume.value;

    // Add event listeners
    audioElement.value.addEventListener('loadstart', handleLoadStart);
    audioElement.value.addEventListener('canplay', handleCanPlay);
    audioElement.value.addEventListener('play', handlePlay);
    audioElement.value.addEventListener('pause', handlePause);
    audioElement.value.addEventListener('ended', handleEnded);
    audioElement.value.addEventListener('timeupdate', handleTimeUpdate);
    audioElement.value.addEventListener('error', handleError);
    audioElement.value.addEventListener('loadedmetadata', () => {
        duration.value = audioElement.value.duration || 0;
    });
});

onUnmounted(() => {
    if (audioElement.value) {
        audioElement.value.pause();
        audioElement.value.src = '';
        audioElement.value = null;
    }
});

// Expose methods for parent component
defineExpose({
    play: () => togglePlayPause(),
    pause: () => audioElement.value?.pause(),
    stop,
    isPlaying: () => isPlaying.value,
    setVolume: (vol) => {
        volume.value = vol;
        updateVolume();
    },
});
</script>

<style scoped>
/* Custom slider styles */
.slider::-webkit-slider-thumb {
    appearance: none;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #5f5fcd;
    cursor: pointer;
}

.slider::-moz-range-thumb {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #5f5fcd;
    cursor: pointer;
    border: none;
}
</style>
