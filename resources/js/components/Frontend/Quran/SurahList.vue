<template>
    <div class="h-full bg-white lg:rounded-xl lg:border lg:border-gray-200 lg:bg-transparent">
        <!-- Header -->
        <div class="sticky top-0 border-b border-gray-200 bg-white p-4 lg:rounded-t-xl">
            <div class="flex items-center justify-between">
                <h2 class="flex items-center text-lg font-semibold text-gray-900">
                    <BookOpenIcon class="mr-2 h-5 w-5 text-[#5f5fcd]" />
                    সূরা তালিকা
                </h2>
                <button @click="$emit('close')" class="rounded-lg p-2 hover:bg-gray-100 lg:hidden">
                    <XIcon class="h-5 w-5" />
                </button>
            </div>

            <!-- Search -->
            <div class="relative mt-4">
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="সূরা খুঁজুন..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 pl-10 focus:border-[#5f5fcd] focus:ring-2 focus:ring-[#5f5fcd]/20"
                />
                <SearchIcon class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 transform text-gray-400" />
            </div>
        </div>

        <!-- Surah List -->
        <div class="surah-list max-h-[calc(100vh-200px)] overflow-y-auto p-2 lg:max-h-96">
            <div class="space-y-1">
                <button
                    v-for="surah in filteredSurahs"
                    :key="surah.number"
                    @click="selectSurah(surah)"
                    :class="[
                        'w-full rounded-lg p-4 text-left transition-all duration-200 hover:bg-gray-50',
                        currentSurah?.number === surah.number ? 'border border-[#5f5fcd]/30 bg-[#5f5fcd]/10' : 'hover:shadow-sm',
                    ]"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <!-- Surah Number -->
                            <div
                                :class="[
                                    'flex h-8 w-8 items-center justify-center rounded-full text-sm font-semibold',
                                    currentSurah?.number === surah.number ? 'bg-[#5f5fcd] text-white' : 'bg-gray-100 text-gray-600',
                                ]"
                            >
                                {{ toBanglaNumber(surah.number) }}
                            </div>

                            <!-- Surah Names -->
                            <div class="min-w-0 flex-1">
                                <div class="mb-1 flex items-center space-x-2">
                                    <span class="truncate font-medium text-gray-900">{{ surah.banglaName }}</span>
                                    <span class="text-sm text-gray-500">•</span>
                                    <span class="truncate text-sm text-gray-500">{{ surah.englishName }}</span>
                                </div>
                                <div class="flex items-center space-x-2 text-xs text-gray-500">
                                    <span>{{ surah.revelationType === 'Meccan' ? 'মক্কী' : 'মাদানী' }}</span>
                                    <span>•</span>
                                    <span>{{ toBanglaNumber(surah.numberOfAyahs) }} আয়াত</span>
                                </div>
                            </div>
                        </div>

                        <!-- Arabic Name -->
                        <div class="text-right">
                            <div class="font-arabic text-lg text-[#2d5a27]">{{ surah.name }}</div>
                        </div>
                    </div>
                </button>
            </div>
        </div>

        <!-- Footer -->
        <div class="border-t border-gray-200 bg-gray-50 p-4 lg:rounded-b-xl">
            <div class="text-center text-sm text-gray-600">মোট {{ toBanglaNumber(props.surahs.length) }}টি সূরা</div>
        </div>
    </div>
</template>

<script setup>
import { BookOpenIcon, SearchIcon, XIcon } from 'lucide-vue-next';
import { computed, ref } from 'vue';

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

const emit = defineEmits(['selectSurah', 'close']);

const searchQuery = ref('');

const filteredSurahs = computed(() => {
    if (!searchQuery.value.trim()) return props.surahs;

    const query = searchQuery.value.toLowerCase().trim();
    return props.surahs.filter(
        (surah) =>
            surah.englishName.toLowerCase().includes(query) ||
            surah.banglaName?.toLowerCase().includes(query) ||
            surah.name.includes(query) ||
            surah.number.toString().includes(query),
    );
});

const toBanglaNumber = (num) => {
    const en = '0123456789';
    const bn = '০১২৩৪৫৬৭৮৯';
    return num
        .toString()
        .split('')
        .map((d) => (en.includes(d) ? bn[en.indexOf(d)] : d))
        .join('');
};

const selectSurah = (surah) => {
    emit('selectSurah', surah);
    emit('close');
};
</script>

<style scoped>
.font-arabic {
    font-family: 'Amiri', 'Scheherazade', 'Noto Naskh Arabic', serif;
}

/* Custom scrollbar */
.surah-list::-webkit-scrollbar {
    width: 6px;
}

.surah-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.surah-list::-webkit-scrollbar-thumb {
    background: #5f5fcd;
    border-radius: 3px;
}

.surah-list::-webkit-scrollbar-thumb:hover {
    background: #4a4aa6;
}
</style>
