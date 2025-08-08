<template>
    <div class="shadow-islamic relative overflow-hidden rounded-xl border border-gray-100 bg-white p-6">
        <!-- Islamic Pattern Background -->
        <div class="pointer-events-none absolute inset-0 opacity-5">
            <svg class="h-full w-full" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="versePattern" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M20 0 L40 20 L20 40 L0 20 Z" fill="none" stroke="#5f5fcd" stroke-width="1" />
                        <circle cx="20" cy="20" r="8" fill="none" stroke="#2d5a27" stroke-width="1" />
                    </pattern>
                </defs>
                <rect width="200" height="200" fill="url(#versePattern)" />
            </svg>
        </div>

        <div class="relative z-10">
            <!-- Header with Category Selector -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="mb-3 flex items-center sm:mb-0">
                    <BookOpenIcon class="mr-2 h-5 w-5 text-[#5f5fcd]" />
                    <h3 class="text-lg font-semibold text-gray-900">কুরআনের আয়াত</h3>
                </div>

                <!-- Category Selector -->
                <div class="relative">
                    <button
                        @click="showCategorySelector = !showCategorySelector"
                        class="flex items-center rounded-lg border border-[#5f5fcd]/20 bg-gradient-to-r from-[#5f5fcd]/10 to-[#2d5a27]/10 px-4 py-2 text-sm font-medium text-gray-700 transition-all duration-200 hover:bg-[#5f5fcd]/5"
                    >
                        <span>{{ getCategoryDisplayName(currentCategory) }}</span>
                        <ChevronDownIcon class="ml-2 h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': showCategorySelector }" />
                    </button>

                    <!-- Dropdown -->
                    <div
                        v-if="showCategorySelector"
                        class="absolute top-full left-0 z-10 mt-2 w-48 rounded-lg border border-gray-200 bg-white shadow-lg"
                    >
                        <div class="py-2">
                            <button
                                v-for="categoryKey in availableCategories"
                                :key="categoryKey"
                                @click="changeCategory(categoryKey)"
                                :class="[
                                    'w-full px-4 py-2 text-left text-sm transition-colors hover:bg-gray-50',
                                    currentCategory === categoryKey ? 'bg-[#5f5fcd]/10 font-medium text-[#5f5fcd]' : 'text-gray-700',
                                ]"
                            >
                                {{ getCategoryDisplayName(categoryKey) }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Controls -->
            <div v-if="hasMultipleVerses" class="mb-6 flex items-center justify-between">
                <button
                    @click="prevVerse"
                    class="flex items-center rounded-lg px-3 py-2 text-sm text-gray-600 transition-all duration-200 hover:bg-[#5f5fcd]/5 hover:text-[#5f5fcd]"
                >
                    <ChevronLeftIcon class="mr-1 h-4 w-4" />
                    আগের আয়াত
                </button>

                <!-- Verse Counter -->
                <div class="rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-500">{{ currentVerseIndex + 1 }} / {{ currentVerses.length }}</div>

                <button
                    @click="nextVerse"
                    class="flex items-center rounded-lg px-3 py-2 text-sm text-gray-600 transition-all duration-200 hover:bg-[#5f5fcd]/5 hover:text-[#5f5fcd]"
                >
                    পরের আয়াত
                    <ChevronRightIcon class="ml-1 h-4 w-4" />
                </button>
            </div>

            <!-- Loading State -->
            <div v-if="loading" class="animate-pulse space-y-4 py-8 text-center">
                <div class="mx-auto h-8 w-3/4 rounded bg-gray-200"></div>
                <div class="mx-auto h-6 w-full rounded bg-gray-200"></div>
                <div class="mx-auto h-4 w-2/3 rounded bg-gray-200"></div>
            </div>

            <!-- Verse Content -->
            <div v-else-if="verse" class="space-y-6">
                <!-- Arabic Text -->
                <div class="rounded-xl border border-[#5f5fcd]/10 bg-gradient-to-r from-[#5f5fcd]/5 to-[#2d5a27]/5 p-6 text-center">
                    <div class="font-arabic mb-4 text-2xl leading-loose text-[#5f5fcd] sm:text-3xl" dir="rtl">
                        {{ verse.text }}
                    </div>
                </div>

                <!-- Bengali Translation -->
                <div class="px-4 text-center">
                    <div class="text-base leading-relaxed text-gray-700 sm:text-lg">"{{ verse.translation }}"</div>
                </div>

                <!-- Verse Reference and Theme -->
                <div class="space-y-3 text-center">
                    <div class="text-sm font-medium text-gray-500">সূরা {{ verse.surahBangla || verse.surah }}, আয়াত {{ verse.ayah }}</div>
                    <div v-if="verse.theme" class="inline-block rounded-full bg-[#d4a574]/20 px-4 py-2 text-sm font-medium text-[#d4a574]">
                        বিষয়: {{ verse.theme }}
                    </div>
                </div>

                <!-- Enhanced Audio Controls -->
                <div class="space-y-4">
                    <!-- Main Audio Button -->
                    <div class="flex justify-center">
                        <button
                            @click="toggleAudio"
                            :disabled="audioLoading || !verse.audioUrl"
                            :class="[
                                'group relative flex transform items-center space-x-3 rounded-xl px-8 py-4 text-lg font-semibold shadow-lg transition-all duration-300 hover:scale-105 disabled:transform-none disabled:cursor-not-allowed disabled:opacity-50',
                                isPlaying
                                    ? 'bg-gradient-to-r from-red-500 to-red-600 text-white'
                                    : 'bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] text-white hover:shadow-xl',
                            ]"
                        >
                            <!-- Animated background overlay -->
                            <div
                                class="absolute inset-0 -translate-x-full -skew-x-12 transform bg-gradient-to-r from-white/0 via-white/10 to-white/0 transition-transform duration-700 group-hover:translate-x-full"
                            ></div>

                            <component :is="isPlaying ? PauseIcon : PlayIcon" class="relative z-10 h-6 w-6" />
                            <span class="relative z-10">
                                {{ audioLoading ? 'লোড হচ্ছে...' : isPlaying ? 'থামান' : 'তেলাওয়াত শুনুন' }}
                            </span>
                        </button>
                    </div>

                    <!-- Audio Controls Row -->
                    <div v-if="verse.audioUrl && !audioError" class="flex flex-wrap items-center justify-center gap-4">
                        <!-- Speed Control -->
                        <div class="flex items-center space-x-2 rounded-lg bg-gray-50 px-4 py-2">
                            <span class="text-sm text-gray-600">গতি:</span>
                            <select
                                v-model="playbackSpeed"
                                @change="changeSpeed"
                                class="rounded-lg border border-gray-300 bg-white px-3 py-1 text-sm focus:border-[#5f5fcd] focus:ring-2 focus:ring-[#5f5fcd]/20"
                            >
                                <option value="0.5">০.৫x</option>
                                <option value="0.75">০.৭৫x</option>
                                <option value="1">১x (স্বাভাবিক)</option>
                                <option value="1.25">১.২৫x</option>
                            </select>
                        </div>

                        <!-- Audio Progress (if playing) -->
                        <div v-if="isPlaying" class="flex items-center space-x-2 text-sm text-gray-500">
                            <div class="h-2 w-2 animate-pulse rounded-full bg-green-500"></div>
                            <span>চলছে...</span>
                        </div>
                    </div>

                    <!-- Audio Error Message -->
                    <div v-if="audioError" class="rounded-lg bg-red-50 py-3 text-center text-sm text-red-500">
                        অডিও চালাতে সমস্যা হয়েছে। ইন্টারনেট সংযোগ চেক করুন।
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap justify-center gap-3 border-t border-gray-100 pt-4">
                    <button
                        @click="shareVerse"
                        class="flex items-center space-x-2 rounded-lg border border-[#5f5fcd]/30 bg-white px-4 py-2 text-[#5f5fcd] transition-all duration-200 hover:bg-[#5f5fcd]/5"
                    >
                        <ShareIcon class="h-4 w-4" />
                        <span class="text-sm font-medium">শেয়ার</span>
                    </button>

                    <button
                        @click="bookmarkVerse"
                        class="flex items-center space-x-2 rounded-lg border border-[#d4a574]/30 bg-white px-4 py-2 text-[#d4a574] transition-all duration-200 hover:bg-[#d4a574]/5"
                    >
                        <BookmarkIcon class="h-4 w-4" />
                        <span class="text-sm font-medium">সেভ</span>
                    </button>

                    <button
                        @click="getDailyVerse(currentCategory, currentVerseIndex)"
                        class="flex items-center space-x-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-600 transition-all duration-200 hover:bg-gray-50"
                    >
                        <RefreshCwIcon class="h-4 w-4" />
                        <span class="text-sm font-medium">রিলোড</span>
                    </button>
                </div>

                <!-- Hidden Audio Element -->
                <audio
                    ref="audioElement"
                    @loadstart="audioLoading = true"
                    @canplay="audioLoading = false"
                    @ended="isPlaying = false"
                    @error="handleAudioError"
                    @pause="isPlaying = false"
                    @play="isPlaying = true"
                    preload="metadata"
                    class="hidden"
                ></audio>
            </div>

            <!-- Error State -->
            <div v-else class="py-8 text-center">
                <BookOpenIcon class="mx-auto mb-4 h-12 w-12 text-gray-400" />
                <div class="mb-4 text-lg text-gray-500">আয়াত লোড করতে সমস্যা হয়েছে</div>
                <button @click="getDailyVerse" class="rounded-lg bg-[#5f5fcd] px-6 py-3 text-white transition-colors hover:bg-[#4a4aa6]">
                    পুনরায় চেষ্টা করুন
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import {
    BookmarkIcon,
    BookOpenIcon,
    ChevronDownIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
    PauseIcon,
    PlayIcon,
    RefreshCwIcon,
    ShareIcon,
} from 'lucide-vue-next';
import { computed, onMounted, ref } from 'vue';

// Categorized verses for better user experience
const verseCategories = {
    daily: {
        name: 'দৈনিক আয়াত',
        verses: [
            { surah: 1, ayah: 1, theme: 'শুরু' }, // Bismillah
            { surah: 2, ayah: 255, theme: 'আয়াতুল কুরসি' }, // Ayat al-Kursi
            { surah: 112, ayah: 1, theme: 'তাওহীদ' }, // Al-Ikhlas
            { surah: 113, ayah: 1, theme: 'সুরক্ষা' }, // Al-Falaq
            { surah: 114, ayah: 1, theme: 'সুরক্ষা' }, // An-Nas
        ],
    },
    motivational: {
        name: 'অনুপ্রেরণামূলক',
        verses: [
            { surah: 94, ayah: 5, theme: 'আশা' }, // Inna ma'al usri yusra
            { surah: 2, ayah: 286, theme: 'দোয়া' }, // La yukallifu Allah
            { surah: 65, ayah: 3, theme: 'ভরসা' }, // Wa man yatawakkal
            { surah: 17, ayah: 82, theme: 'নিরাময়' }, // Quran is healing
            { surah: 13, ayah: 28, theme: 'প্রশান্তি' }, // Hearts find peace
        ],
    },
    guidance: {
        name: 'পথনির্দেশনা',
        verses: [
            { surah: 2, ayah: 2, theme: 'হেদায়েত' }, // This is the Book
            { surah: 3, ayah: 18, theme: 'ইনসাফ' }, // Shahida Allah
            { surah: 49, ayah: 13, theme: 'ঐক্য' }, // O mankind
            { surah: 25, ayah: 63, theme: 'বিনয়' }, // Servants of Rahman
            { surah: 24, ayah: 35, theme: 'নূর' }, // Light verse
        ],
    },
};

const verse = ref(null);
const loading = ref(true);
const audioElement = ref(null);
const isPlaying = ref(false);
const audioLoading = ref(false);
const audioError = ref(false);
const playbackSpeed = ref(1);

const currentCategory = ref('daily');
const currentVerseIndex = ref(0);
const showCategorySelector = ref(false);

const availableCategories = computed(() => Object.keys(verseCategories));
const currentVerses = computed(() => verseCategories[currentCategory.value]?.verses || []);
const hasMultipleVerses = computed(() => currentVerses.value.length > 1);

// Get verse based on category and index
const getDailyVerse = async (categoryKey = null, verseIndex = null) => {
    loading.value = true;
    audioError.value = false;

    try {
        // Determine category and verse index
        const category = categoryKey || currentCategory.value;
        let index = verseIndex;

        if (index === null) {
            if (category === 'daily') {
                // Use current date for daily rotation
                const today = new Date();
                const dayOfYear = Math.floor((today - new Date(today.getFullYear(), 0, 0)) / (1000 * 60 * 60 * 24));
                index = dayOfYear % verseCategories[category].verses.length;
            } else {
                index = currentVerseIndex.value;
            }
        }

        const selectedVerse = verseCategories[category].verses[index];
        currentCategory.value = category;
        currentVerseIndex.value = index;

        // Create verse identifier
        const verseId = `${selectedVerse.surah}:${selectedVerse.ayah}`;

        // Fetch Arabic text
        const arabicResponse = await fetch(`https://api.alquran.cloud/v1/ayah/${verseId}`);
        const arabicData = await arabicResponse.json();

        if (!arabicData.data) {
            throw new Error('No Arabic data received');
        }

        // Fetch Bengali translation
        const bengaliResponse = await fetch(`https://api.alquran.cloud/v1/ayah/${verseId}/bn.bengali`);
        const bengaliData = await bengaliResponse.json();

        // Use direct reliable audio URLs
        const surahNum = selectedVerse.surah.toString().padStart(3, '0');
        const ayahNum = selectedVerse.ayah.toString().padStart(3, '0');
        const audioUrl = `https://everyayah.com/data/Alafasy_128kbps/${surahNum}${ayahNum}.mp3`;

        verse.value = {
            text: arabicData.data.text,
            translation: bengaliData.data?.text || 'অনুবাদ লোড করা যায়নি',
            surah: arabicData.data.surah.englishName,
            surahBangla: getSurahBanglaName(arabicData.data.surah.englishName),
            ayah: arabicData.data.numberInSurah,
            audioUrl: audioUrl,
            theme: selectedVerse.theme,
            category: verseCategories[category].name,
        };

        // Set audio source if available
        if (audioElement.value && verse.value.audioUrl) {
            audioElement.value.src = verse.value.audioUrl;
        }

        loading.value = false;
    } catch (error) {
        // Error fetching verse

        // Fallback verse (Bismillah)
        verse.value = {
            text: 'بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ',
            translation: 'পরম করুণাময় ও দয়ালু আল্লাহর নামে',
            surah: 'Al-Fatiha',
            surahBangla: 'ফাতিহা',
            ayah: 1,
            audioUrl: 'https://everyayah.com/data/Alafasy_128kbps/001001.mp3',
            theme: 'শুরু',
            category: 'দৈনিক আয়াত',
        };
        loading.value = false;
    }
};

// Get Bangla names for popular surahs
const getSurahBanglaName = (englishName) => {
    const surahNames = {
        'Al-Fatiha': 'ফাতিহা',
        'Al-Baqarah': 'বাক্বারা',
        "Ali 'Imran": 'আলে-ইমরান',
        'Al-Ikhlas': 'ইখলাস',
        'Al-Falaq': 'ফালাক্ব',
        'An-Nas': 'নাস',
        'At-Tawbah': 'তাওবা',
        'Al-Isra': 'ইসরা',
        'An-Nur': 'নূর',
        'Ar-Rahman': 'রহমান',
        'Al-Mulk': 'মুলক',
        'Al-Inshirah': 'ইনশিরাহ',
    };
    return surahNames[englishName] || englishName;
};

const toggleAudio = async () => {
    if (!audioElement.value || !verse.value.audioUrl) {
        return;
    }

    if (isPlaying.value) {
        audioElement.value.pause();
        isPlaying.value = false;
        return;
    }

    try {
        audioLoading.value = true;
        audioError.value = false;

        // Ensure audio source is set
        if (audioElement.value.src !== verse.value.audioUrl) {
            audioElement.value.src = verse.value.audioUrl;
            audioElement.value.load();
        }

        // Simple play attempt
        const playPromise = audioElement.value.play();

        if (playPromise !== undefined) {
            await playPromise;
            isPlaying.value = true;
        }

        audioLoading.value = false;
    } catch (error) {
        // Audio play failed
        audioLoading.value = false;
        audioError.value = true;
    }
};

const changeSpeed = () => {
    if (audioElement.value) {
        audioElement.value.playbackRate = playbackSpeed.value;
    }
};

const handleAudioError = (event) => {
    // Audio error
    audioError.value = true;
    audioLoading.value = false;
    isPlaying.value = false;
};

const shareVerse = () => {
    if (!verse.value) return;

    const shareText = `${verse.value.text}

"${verse.value.translation}"

- সূরা ${verse.value.surahBangla || verse.value.surah}, আয়াত ${verse.value.ayah}
${verse.value.theme ? `- বিষয়: ${verse.value.theme}` : ''}

ইকরা অনলাইন একাডেমি থেকে`;

    if (navigator.share) {
        navigator
            .share({
                title: `কুরআনের আয়াত - ${verse.value.theme || 'আজকের আয়াত'}`,
                text: shareText,
                url: window.location.href,
            })
            .catch(() => {});
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard
            .writeText(shareText)
            .then(() => {
                alert('আয়াতটি কপি করা হয়েছে!');
            })
            .catch(() => {
                // Final fallback: show text in alert
                alert(shareText);
            });
    }
};

const bookmarkVerse = () => {
    if (!verse.value) return;

    // Save to localStorage
    const bookmarks = JSON.parse(localStorage.getItem('iqra_bookmarked_verses') || '[]');
    const verseKey = `${verse.value.surah}:${verse.value.ayah}`;

    if (!bookmarks.find((b) => b.key === verseKey)) {
        bookmarks.push({
            key: verseKey,
            text: verse.value.text,
            translation: verse.value.translation,
            surah: verse.value.surah,
            surahBangla: verse.value.surahBangla,
            ayah: verse.value.ayah,
            theme: verse.value.theme,
            savedAt: new Date().toISOString(),
        });

        localStorage.setItem('iqra_bookmarked_verses', JSON.stringify(bookmarks));
        alert('আয়াতটি সেভ করা হয়েছে!');
    } else {
        alert('এই আয়াতটি ইতিমধ্যে সেভ করা আছে!');
    }
};

// Navigation functions
const changeCategory = (categoryKey) => {
    currentCategory.value = categoryKey;
    currentVerseIndex.value = 0;
    showCategorySelector.value = false;
    getDailyVerse(categoryKey, 0);
};

const nextVerse = () => {
    const nextIndex = (currentVerseIndex.value + 1) % currentVerses.value.length;
    currentVerseIndex.value = nextIndex;
    getDailyVerse(currentCategory.value, nextIndex);
};

const prevVerse = () => {
    const prevIndex = currentVerseIndex.value === 0 ? currentVerses.value.length - 1 : currentVerseIndex.value - 1;
    currentVerseIndex.value = prevIndex;
    getDailyVerse(currentCategory.value, prevIndex);
};

const getCategoryDisplayName = (key) => {
    return verseCategories[key]?.name || key;
};

onMounted(() => {
    getDailyVerse();
});
</script>

<style scoped>
.font-arabic {
    font-family: 'Amiri', 'Scheherazade', 'Noto Naskh Arabic', serif;
    line-height: 2;
}

/* Ensure proper RTL display for Arabic */
[dir='rtl'] {
    text-align: center;
}

/* Smooth transitions */
.transition-all {
    transition: all 0.3s ease;
}

/* Dropdown animation */
.dropdown-enter-active,
.dropdown-leave-active {
    transition: all 0.3s ease;
}

.dropdown-enter-from,
.dropdown-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>
