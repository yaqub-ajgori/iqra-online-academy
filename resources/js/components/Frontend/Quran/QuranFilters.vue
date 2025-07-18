<template>
    <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
        <h3 class="mb-4 flex items-center font-semibold text-gray-900">
            <FilterIcon class="mr-2 h-5 w-5 text-[#5f5fcd]" />
            ফিল্টার অপশন
        </h3>

        <div class="space-y-4">
            <!-- Quick Surah Selection -->
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">দ্রুত সূরা নির্বাচন</label>
                <div class="grid grid-cols-2 gap-2">
                    <button
                        v-for="popularSurah in popularSurahs"
                        :key="popularSurah.number"
                        @click="$emit('selectSurah', popularSurah)"
                        :class="[
                            'rounded-lg p-2 text-left text-sm transition-colors',
                            currentSurah?.number === popularSurah.number ? 'bg-[#5f5fcd] text-white' : 'bg-gray-50 text-gray-700 hover:bg-gray-100',
                        ]"
                    >
                        {{ popularSurah.banglaName }}
                    </button>
                </div>
            </div>

            <!-- Revelation Type Filter -->
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">নাযিলের স্থান</label>
                <div class="flex space-x-2">
                    <button
                        v-for="type in revelationTypes"
                        :key="type.value"
                        @click="toggleRevelationType(type.value)"
                        :class="[
                            'rounded-lg px-3 py-2 text-sm transition-colors',
                            selectedRevelationTypes.includes(type.value) ? 'bg-[#5f5fcd] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                        ]"
                    >
                        {{ type.label }}
                    </button>
                </div>
            </div>

            <!-- Surah Length Filter -->
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">সূরার দৈর্ঘ্য (আয়াত সংখ্যা)</label>
                <div class="space-y-2">
                    <div class="flex space-x-2">
                        <button
                            v-for="length in surahLengths"
                            :key="length.value"
                            @click="toggleSurahLength(length.value)"
                            :class="[
                                'rounded-lg px-3 py-2 text-sm transition-colors',
                                selectedSurahLengths.includes(length.value)
                                    ? 'bg-[#5f5fcd] text-white'
                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                            ]"
                        >
                            {{ length.label }}
                        </button>
                    </div>

                    <!-- Custom Range -->
                    <div class="flex items-center space-x-2 text-sm">
                        <span class="text-gray-600">কাস্টম:</span>
                        <input
                            v-model.number="customRange.min"
                            type="number"
                            min="1"
                            max="286"
                            placeholder="সর্বনিম্ন"
                            class="w-20 rounded border border-gray-300 px-2 py-1 text-center"
                            @input="applyCustomRange"
                        />
                        <span class="text-gray-400">-</span>
                        <input
                            v-model.number="customRange.max"
                            type="number"
                            min="1"
                            max="286"
                            placeholder="সর্বোচ্চ"
                            class="w-20 rounded border border-gray-300 px-2 py-1 text-center"
                            @input="applyCustomRange"
                        />
                    </div>
                </div>
            </div>

            <!-- Advanced Search -->
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">উন্নত অনুসন্ধান</label>
                <div class="space-y-2">
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="সূরার নাম, নম্বর বা কীওয়ার্ড..."
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-[#5f5fcd] focus:ring-2 focus:ring-[#5f5fcd]/20"
                        @input="$emit('search', searchQuery)"
                    />

                    <!-- Search by Juz/Para -->
                    <select
                        v-model="selectedJuz"
                        @change="$emit('filterByJuz', selectedJuz)"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-[#5f5fcd] focus:ring-2 focus:ring-[#5f5fcd]/20"
                    >
                        <option value="">পারা দিয়ে ফিল্টার করুন</option>
                        <option v-for="i in 30" :key="i" :value="i">পারা {{ toBanglaNumber(i) }}</option>
                    </select>
                </div>
            </div>

            <!-- Reading Preferences -->
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">পড়ার পছন্দ</label>
                <div class="space-y-2">
                    <!-- Bookmark favorite surahs -->
                    <button
                        @click="toggleBookmarkMode"
                        :class="[
                            'flex w-full items-center justify-center rounded-lg px-3 py-2 text-sm transition-colors',
                            bookmarkMode ? 'border border-yellow-300 bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                        ]"
                    >
                        <BookmarkIcon :class="['mr-2 h-4 w-4', bookmarkMode ? 'fill-current' : '']" />
                        {{ bookmarkMode ? 'বুকমার্কড সূরা দেখাচ্ছে' : 'বুকমার্কড সূরা দেখান' }}
                    </button>

                    <!-- Recently read -->
                    <button
                        @click="toggleRecentMode"
                        :class="[
                            'flex w-full items-center justify-center rounded-lg px-3 py-2 text-sm transition-colors',
                            recentMode ? 'border border-blue-300 bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                        ]"
                    >
                        <ClockIcon class="mr-2 h-4 w-4" />
                        {{ recentMode ? 'সাম্প্রতিক পঠিত দেখাচ্ছে' : 'সাম্প্রতিক পঠিত দেখান' }}
                    </button>
                </div>
            </div>

            <!-- Clear Filters -->
            <div class="border-t border-gray-200 pt-2">
                <button
                    @click="clearAllFilters"
                    class="flex w-full items-center justify-center rounded-lg px-3 py-2 text-sm text-gray-600 transition-colors hover:bg-gray-50 hover:text-gray-800"
                >
                    <RotateCcwIcon class="mr-2 h-4 w-4" />
                    সব ফিল্টার মুছুন
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { BookmarkIcon, ClockIcon, FilterIcon, RotateCcwIcon } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

const props = defineProps({
    surahs: {
        type: Array,
        default: () => [],
    },
    currentSurah: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits([
    'selectSurah',
    'search',
    'filterByJuz',
    'filterByRevelationType',
    'filterByLength',
    'filterByBookmarks',
    'filterByRecent',
    'clearFilters',
]);

// Filter states
const searchQuery = ref('');
const selectedRevelationTypes = ref([]);
const selectedSurahLengths = ref([]);
const selectedJuz = ref('');
const customRange = ref({ min: null, max: null });
const bookmarkMode = ref(false);
const recentMode = ref(false);

// Popular surahs for quick access
const popularSurahs = computed(() => {
    if (!props.surahs.length) return [];

    const popularNumbers = [1, 2, 18, 36, 55, 67, 112, 113, 114]; // Al-Fatiha, Al-Baqarah, Al-Kahf, Ya-Sin, Ar-Rahman, Al-Mulk, Al-Ikhlas, Al-Falaq, An-Nas
    return props.surahs.filter((surah) => popularNumbers.includes(surah.number));
});

// Filter options
const revelationTypes = [
    { value: 'Meccan', label: 'মক্কী' },
    { value: 'Medinan', label: 'মাদানী' },
];

const surahLengths = [
    { value: 'short', label: 'ছোট (১-১০ আয়াত)' },
    { value: 'medium', label: 'মাঝারি (১১-৫০ আয়াত)' },
    { value: 'long', label: 'লম্বা (৫১+ আয়াত)' },
];

// Utility functions
const toBanglaNumber = (num) => {
    const en = '0123456789';
    const bn = '০১২৩৪৫৬৭৮৯';
    return num
        .toString()
        .split('')
        .map((d) => (en.includes(d) ? bn[en.indexOf(d)] : d))
        .join('');
};

// Filter methods
const toggleRevelationType = (type) => {
    const index = selectedRevelationTypes.value.indexOf(type);
    if (index > -1) {
        selectedRevelationTypes.value.splice(index, 1);
    } else {
        selectedRevelationTypes.value.push(type);
    }
    emit('filterByRevelationType', selectedRevelationTypes.value);
};

const toggleSurahLength = (length) => {
    const index = selectedSurahLengths.value.indexOf(length);
    if (index > -1) {
        selectedSurahLengths.value.splice(index, 1);
    } else {
        selectedSurahLengths.value.push(length);
    }

    // Convert length categories to actual ranges
    const ranges = [];
    selectedSurahLengths.value.forEach((len) => {
        switch (len) {
            case 'short':
                ranges.push({ min: 1, max: 10 });
                break;
            case 'medium':
                ranges.push({ min: 11, max: 50 });
                break;
            case 'long':
                ranges.push({ min: 51, max: 286 });
                break;
        }
    });

    emit('filterByLength', ranges);
};

const applyCustomRange = () => {
    if (customRange.value.min && customRange.value.max) {
        const ranges = [
            {
                min: Math.min(customRange.value.min, customRange.value.max),
                max: Math.max(customRange.value.min, customRange.value.max),
            },
        ];
        selectedSurahLengths.value = []; // Clear predefined selections
        emit('filterByLength', ranges);
    }
};

const toggleBookmarkMode = () => {
    bookmarkMode.value = !bookmarkMode.value;
    recentMode.value = false; // Can't have both modes active
    emit('filterByBookmarks', bookmarkMode.value);
};

const toggleRecentMode = () => {
    recentMode.value = !recentMode.value;
    bookmarkMode.value = false; // Can't have both modes active
    emit('filterByRecent', recentMode.value);
};

const clearAllFilters = () => {
    searchQuery.value = '';
    selectedRevelationTypes.value = [];
    selectedSurahLengths.value = [];
    selectedJuz.value = '';
    customRange.value = { min: null, max: null };
    bookmarkMode.value = false;
    recentMode.value = false;

    emit('clearFilters');
};

// Watch for search query changes with debounce
let searchTimeout;
watch(searchQuery, (newQuery) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        emit('search', newQuery);
    }, 300);
});
</script>
