<template>
    <FrontendLayout title="কুরআন পাঠ - ইকরা অনলাইন একাডেমি">
        <Head title="কুরআন পাঠ - ইকরা অনলাইন একাডেমি" />

        <div class="mx-auto max-w-7xl px-3 py-3 sm:px-6 sm:py-8 lg:px-8">
            <!-- Mobile-First Compact Header -->
            <div class="py-2 text-center sm:py-4">
                <h1
                    class="mb-2 bg-gradient-to-r from-[#2d5a27] via-[#5f5fcd] to-[#2d5a27] bg-clip-text text-2xl font-bold text-transparent sm:mb-3 sm:text-4xl md:text-5xl"
                >
                    পবিত্র কুরআন শরীফ
                </h1>
                <p class="mx-auto mb-2 max-w-xl px-4 text-sm leading-relaxed text-gray-600 sm:px-0 sm:text-lg">
                    সুন্দর টাইপোগ্রাফি, মনোরম কণ্ঠস্বর এবং সহজ নেভিগেশন সহ কুরআন পড়ুন ও শুনুন।
                </p>
                <div class="flex items-center justify-center">
                    <span
                        class="rounded-full border border-[#5f5fcd]/20 bg-gradient-to-r from-[#5f5fcd]/10 to-[#2d5a27]/10 px-3 py-1 text-xs font-semibold text-[#5f5fcd] sm:text-sm"
                    >
                        ইকরা অনলাইন একাডেমি
                    </span>
                </div>
            </div>

            <!-- Premium Search Bar -->
            <div
                class="mb-4 rounded-xl border border-[#5f5fcd]/10 bg-gradient-to-r from-[#5f5fcd]/5 via-white to-[#2d5a27]/5 p-3 shadow-lg sm:mb-6 sm:rounded-2xl sm:p-4"
            >
                <div class="flex flex-col gap-3 sm:flex-row sm:gap-4">
                    <!-- Search Input -->
                    <div class="relative flex-1">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-[#5f5fcd]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                />
                            </svg>
                        </div>
                        <input
                            v-model="searchQuery"
                            @keyup.enter="performSearch"
                            @input="debouncedSearch"
                            type="text"
                            placeholder="কুরআনে খুঁজুন... (আরবি অথবা বাংলা)"
                            class="w-full rounded-lg border-2 border-[#5f5fcd]/20 bg-white py-3 pr-4 pl-10 font-medium text-gray-700 placeholder-gray-400 transition-all duration-300 hover:border-[#5f5fcd]/40 hover:shadow-sm focus:border-[#5f5fcd] focus:bg-white/95 focus:shadow-lg focus:ring-2 focus:ring-[#5f5fcd]/30"
                        />
                        <div v-if="searchLoading" class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <div class="relative">
                                <div class="h-5 w-5 animate-spin rounded-full border-2 border-[#5f5fcd]/20 border-t-[#5f5fcd]"></div>
                                <div class="absolute inset-0 h-5 w-5 animate-ping rounded-full bg-[#5f5fcd]/10"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Search Button -->
                    <button
                        @click="performSearch"
                        :disabled="searchLoading || !searchQuery.trim()"
                        class="flex min-w-[100px] items-center justify-center rounded-lg bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] px-4 py-3 font-semibold text-white transition-all duration-300 hover:scale-105 hover:from-[#4a4aa6] hover:to-[#1f3e1b] hover:shadow-lg active:scale-95 disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:scale-100 sm:px-6"
                    >
                        <svg v-if="!searchLoading" class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <div v-if="searchLoading" class="mr-2 h-5 w-5 animate-spin rounded-full border-b-2 border-white"></div>
                        <span>{{ searchLoading ? 'খুঁজছি...' : 'খুঁজুন' }}</span>
                    </button>
                </div>

                <!-- Advanced Search Filters -->
                <div v-if="searchQuery.trim()" class="mt-4 space-y-4 sm:mt-5">
                    <!-- Search Controls Card -->
                    <div class="rounded-xl border border-gray-200 bg-gradient-to-r from-gray-50 to-white p-4 shadow-sm">
                        <!-- Search Scope & Language -->
                        <div class="mb-3">
                            <p class="mb-2 text-xs font-semibold tracking-wide text-gray-500 uppercase">🎯 অনুসন্ধান পরিসর</p>
                            <div class="flex flex-wrap gap-2">
                                <button
                                    @click="searchIn = 'all'"
                                    :class="[
                                        'rounded-lg px-4 py-2 text-sm font-medium shadow-sm transition-all duration-200',
                                        searchIn === 'all'
                                            ? 'bg-[#5f5fcd] text-white shadow-md'
                                            : 'border border-[#5f5fcd]/20 bg-white text-[#5f5fcd] hover:bg-[#5f5fcd]/5',
                                    ]"
                                >
                                    📚 সব সূরা
                                </button>
                                <button
                                    @click="searchIn = 'current'"
                                    :class="[
                                        'rounded-lg px-4 py-2 text-sm font-medium shadow-sm transition-all duration-200',
                                        searchIn === 'current'
                                            ? 'bg-[#2d5a27] text-white shadow-md'
                                            : 'border border-[#2d5a27]/20 bg-white text-[#2d5a27] hover:bg-[#2d5a27]/5',
                                    ]"
                                >
                                    📖 বর্তমান {{ currentSurah ? 'সূরা' : currentJuz ? 'পারা' : selectedPage ? 'পৃষ্ঠা' : 'নির্বাচন' }}
                                </button>
                            </div>
                        </div>

                        <!-- Language & Auto-search Options -->
                        <div class="border-t border-gray-100 pt-3">
                            <p class="mb-2 text-xs font-semibold tracking-wide text-gray-500 uppercase">⚙️ অনুসন্ধান অপশন</p>
                            <div class="flex flex-wrap gap-2">
                                <button
                                    @click="searchLanguage = searchLanguage === 'arabic' ? 'bengali' : 'arabic'"
                                    :class="[
                                        'rounded-lg px-4 py-2 text-sm font-medium shadow-sm transition-all duration-200',
                                        'border border-[#d4a574]/20 bg-white text-[#d4a574] hover:bg-[#d4a574]/5',
                                    ]"
                                >
                                    🌐 {{ searchLanguage === 'arabic' ? 'আরবি' : 'বাংলা' }} অনুবাদ
                                </button>
                                <button
                                    @click="autoSearch = !autoSearch"
                                    :class="[
                                        'rounded-lg px-4 py-2 text-sm font-medium shadow-sm transition-all duration-200',
                                        autoSearch
                                            ? 'border border-green-200 bg-green-50 text-green-700 hover:bg-green-100'
                                            : 'border border-gray-200 bg-white text-gray-600 hover:bg-gray-50',
                                    ]"
                                >
                                    {{ autoSearch ? '⚡ অটো সার্চ চালু' : '⚡ অটো সার্চ বন্ধ' }}
                                </button>
                                <button
                                    v-if="searchResults.length > 0"
                                    @click="clearSearch"
                                    class="rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-medium text-red-600 shadow-sm transition-all duration-200 hover:scale-105 hover:bg-red-100 hover:shadow-md active:scale-95"
                                >
                                    🗑️ সাফ করুন
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Concept-Based Search Suggestions -->
                    <div
                        v-if="conceptSuggestions.length > 0"
                        class="rounded-xl border border-blue-100 bg-gradient-to-r from-blue-50/50 to-indigo-50/50 p-4 shadow-sm"
                    >
                        <div class="mb-3 flex items-center gap-2">
                            <div class="rounded-full bg-blue-100 p-1">
                                <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                                    />
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-blue-700">সংশ্লিষ্ট বিষয়সমূহ</p>
                        </div>
                        <div class="grid grid-cols-2 gap-2 sm:grid-cols-3">
                            <button
                                v-for="concept in conceptSuggestions.slice(0, 6)"
                                :key="concept.id"
                                @click="searchByConcept(concept)"
                                class="rounded-lg border border-blue-200/50 bg-white/60 px-3 py-2 text-left text-sm font-medium text-blue-800 transition-all duration-200 hover:bg-white hover:shadow-sm"
                            >
                                <span class="text-base">{{ concept.icon }}</span>
                                <span class="ml-2">{{ concept.name }}</span>
                            </button>
                        </div>
                    </div>

                    <!-- Search History -->
                    <div
                        v-if="searchHistory.length > 0"
                        class="rounded-xl border border-amber-100 bg-gradient-to-r from-amber-50/50 to-orange-50/50 p-4 shadow-sm"
                    >
                        <div class="mb-3 flex items-center gap-2">
                            <div class="rounded-full bg-amber-100 p-1">
                                <svg class="h-4 w-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-amber-700">সাম্প্রতিক অনুসন্ধান</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <button
                                v-for="term in searchHistory.slice(0, 5)"
                                :key="term"
                                @click="
                                    searchQuery = term;
                                    performSearch();
                                "
                                class="rounded-lg border border-amber-200/50 bg-white/60 px-3 py-1.5 text-sm text-amber-800 transition-all duration-200 hover:bg-white hover:shadow-sm"
                            >
                                {{ term }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Topic-Based Study Section -->
            <div
                class="mb-4 rounded-xl border border-[#d4a574]/10 bg-gradient-to-r from-[#d4a574]/5 via-white to-[#5f5fcd]/5 p-3 shadow-lg sm:mb-6 sm:rounded-2xl sm:p-4"
            >
                <div class="mb-3 flex items-center justify-between sm:mb-4">
                    <h3 class="flex items-center text-lg font-bold text-[#2d5a27] sm:text-xl">
                        <svg class="mr-2 h-5 w-5 sm:h-6 sm:w-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        বিষয়ভিত্তিক অধ্যয়ন
                    </h3>
                    <button
                        @click="showTopics = !showTopics"
                        class="flex items-center rounded-lg bg-[#d4a574]/15 px-3 py-1.5 font-semibold text-[#8B5A3C] transition-all duration-200 hover:bg-[#d4a574]/25 hover:text-[#6B4423]"
                    >
                        <span class="mr-1 text-sm font-medium">{{ showTopics ? 'লুকান' : 'দেখুন' }}</span>
                        <svg
                            class="h-4 w-4 transition-transform duration-200"
                            :class="{ 'rotate-180': showTopics }"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>

                <div v-if="showTopics" class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:grid-cols-3">
                    <div
                        v-for="topic in topicCategories"
                        :key="topic.id"
                        @click="selectTopic(topic)"
                        class="group relative cursor-pointer rounded-xl border p-3 transition-all duration-300 hover:-translate-y-1 hover:scale-105 hover:transform hover:shadow-lg active:scale-95 sm:p-4"
                        :class="{
                            'pointer-events-none opacity-50': topicLoading && selectedTopic?.id === topic.id,
                            'border-[#d4a574] bg-gradient-to-br from-[#d4a574]/20 to-[#5f5fcd]/20 shadow-lg': selectedTopic?.id === topic.id,
                            'border-[#d4a574]/20 bg-gradient-to-br from-white to-[#f8f9ff] hover:border-[#d4a574]/40 hover:shadow-md':
                                selectedTopic?.id !== topic.id,
                        }"
                    >
                        <div class="mb-2 flex items-center">
                            <div
                                class="mr-3 flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-[#d4a574] to-[#5f5fcd] sm:h-10 sm:w-10"
                            >
                                <div
                                    v-if="topicLoading && selectedTopic?.id === topic.id"
                                    class="h-4 w-4 animate-spin rounded-full border-b-2 border-white"
                                ></div>
                                <span v-else class="text-lg sm:text-xl">{{ topic.icon }}</span>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-bold text-[#2d5a27] sm:text-base">{{ topic.name }}</h4>
                                <p class="text-xs text-gray-600">
                                    <span v-if="topicLoading && selectedTopic?.id === topic.id">লোড হচ্ছে...</span>
                                    <span v-else>{{ topic.count }}টি আয়াত</span>
                                </p>
                            </div>
                        </div>
                        <p class="text-xs leading-relaxed text-gray-700 sm:text-sm">{{ topic.description }}</p>
                    </div>
                </div>
            </div>

            <!-- Memorization Helper Section -->
            <div
                class="mb-4 rounded-xl border border-[#5f5fcd]/10 bg-gradient-to-r from-[#5f5fcd]/5 via-white to-[#d4a574]/5 p-3 shadow-lg sm:mb-6 sm:rounded-2xl sm:p-4"
            >
                <div class="mb-3 flex items-center justify-between sm:mb-4">
                    <h3 class="flex items-center text-lg font-bold text-[#2d5a27] sm:text-xl">
                        <svg class="mr-2 h-5 w-5 sm:h-6 sm:w-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        হিফয সহায়ক
                    </h3>
                    <button
                        @click="showMemorizationHelper = !showMemorizationHelper"
                        class="flex items-center rounded-lg bg-[#5f5fcd]/15 px-3 py-1.5 font-semibold text-[#4A4A9E] transition-all duration-200 hover:bg-[#5f5fcd]/25 hover:text-[#383875]"
                    >
                        <span class="mr-1 text-sm font-medium">{{ showMemorizationHelper ? 'লুকান' : 'দেখুন' }}</span>
                        <svg
                            class="h-4 w-4 transition-transform duration-200"
                            :class="{ 'rotate-180': showMemorizationHelper }"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>

                <div v-if="showMemorizationHelper" class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:grid-cols-3">
                    <!-- Progressive Revelation Mode -->
                    <div
                        @click="toggleProgressiveMode"
                        class="group relative cursor-pointer overflow-hidden rounded-xl border p-3 transition-all duration-300 hover:scale-105 hover:transform sm:p-4"
                        :class="{
                            'border-[#5f5fcd] bg-gradient-to-br from-[#5f5fcd]/20 to-[#2d5a27]/20 shadow-xl ring-2 ring-[#5f5fcd]/30':
                                progressiveMode,
                            'border-[#5f5fcd]/20 bg-gradient-to-br from-white to-[#f8f9ff] hover:border-[#5f5fcd]/40 hover:shadow-md':
                                !progressiveMode,
                        }"
                    >
                        <!-- Active indicator pulse -->
                        <div v-if="progressiveMode" class="absolute top-2 right-2">
                            <div class="relative">
                                <div class="h-3 w-3 rounded-full bg-green-500"></div>
                                <div class="absolute inset-0 h-3 w-3 animate-ping rounded-full bg-green-400 opacity-75"></div>
                            </div>
                        </div>

                        <!-- Background pattern -->
                        <div class="absolute inset-0 opacity-5">
                            <div class="h-full w-full bg-gradient-to-br from-[#5f5fcd] to-transparent"></div>
                        </div>

                        <div class="relative z-10 mb-2 flex items-center">
                            <div
                                class="mr-3 flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] shadow-lg transition-transform duration-300 sm:h-10 sm:w-10"
                                :class="{ 'scale-110 shadow-xl': progressiveMode }"
                            >
                                <span class="text-lg transition-transform duration-300 sm:text-xl" :class="{ 'animate-pulse': progressiveMode }"
                                    >👁️</span
                                >
                            </div>
                            <div class="flex-1">
                                <h4 class="flex items-center text-sm font-bold text-[#2d5a27] sm:text-base">
                                    {{ progressiveMode ? 'প্রগ্রেসিভ মোড চালু' : 'প্রগ্রেসিভ মোড' }}
                                    <span v-if="progressiveMode" class="ml-2 rounded-full bg-green-100 px-2 py-0.5 text-xs text-green-800"
                                        >সক্রিয়</span
                                    >
                                </h4>
                            </div>
                        </div>
                        <p class="relative z-10 text-xs leading-relaxed text-gray-700 sm:text-sm">
                            {{ progressiveMode ? 'সকল আয়াত একসাথে দেখানো হবে' : 'হিফয অনুশীলনের জন্য একে একে আয়াত প্রদর্শন' }}
                        </p>

                        <!-- Shine effect on hover -->
                        <div class="absolute inset-0 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                            <div
                                class="h-full w-full translate-x-full -skew-x-12 transform bg-gradient-to-r from-transparent via-white/10 to-transparent transition-transform duration-700 group-hover:translate-x-[-200%]"
                            ></div>
                        </div>
                    </div>

                    <!-- Hide Translation Mode -->
                    <div
                        @click="toggleTranslationVisibility"
                        class="group relative cursor-pointer overflow-hidden rounded-xl border p-3 transition-all duration-300 hover:scale-105 hover:transform sm:p-4"
                        :class="{
                            'border-[#d4a574] bg-gradient-to-br from-[#d4a574]/20 to-[#5f5fcd]/20 shadow-xl ring-2 ring-[#d4a574]/30':
                                hideTranslation,
                            'border-[#d4a574]/20 bg-gradient-to-br from-white to-[#f8f9ff] hover:border-[#d4a574]/40 hover:shadow-md':
                                !hideTranslation,
                        }"
                    >
                        <!-- Active indicator pulse -->
                        <div v-if="hideTranslation" class="absolute top-2 right-2">
                            <div class="relative">
                                <div class="h-3 w-3 rounded-full bg-orange-500"></div>
                                <div class="absolute inset-0 h-3 w-3 animate-ping rounded-full bg-orange-400 opacity-75"></div>
                            </div>
                        </div>

                        <!-- Background pattern -->
                        <div class="absolute inset-0 opacity-5">
                            <div class="h-full w-full bg-gradient-to-br from-[#d4a574] to-transparent"></div>
                        </div>

                        <div class="relative z-10 mb-2 flex items-center">
                            <div
                                class="mr-3 flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-[#d4a574] to-[#5f5fcd] shadow-lg transition-transform duration-300 sm:h-10 sm:w-10"
                                :class="{ 'scale-110 shadow-xl': hideTranslation }"
                            >
                                <span class="text-lg transition-transform duration-300 sm:text-xl" :class="{ 'animate-pulse': hideTranslation }">{{
                                    hideTranslation ? '🙈' : '🔤'
                                }}</span>
                            </div>
                            <div class="flex-1">
                                <h4 class="flex items-center text-sm font-bold text-[#2d5a27] sm:text-base">
                                    {{ hideTranslation ? 'অনুবাদ লুকানো' : 'অনুবাদ লুকান' }}
                                    <span v-if="hideTranslation" class="ml-2 rounded-full bg-orange-100 px-2 py-0.5 text-xs text-orange-800"
                                        >সক্রিয়</span
                                    >
                                </h4>
                            </div>
                        </div>
                        <p class="relative z-10 text-xs leading-relaxed text-gray-700 sm:text-sm">
                            {{ hideTranslation ? 'অনুবাদ আবার দেখানো হবে' : 'হিফয অনুশীলনের জন্য অনুবাদ লুকিয়ে রাখুন' }}
                        </p>

                        <!-- Shine effect on hover -->
                        <div class="absolute inset-0 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                            <div
                                class="h-full w-full translate-x-full -skew-x-12 transform bg-gradient-to-r from-transparent via-white/10 to-transparent transition-transform duration-700 group-hover:translate-x-[-200%]"
                            ></div>
                        </div>
                    </div>

                    <!-- Repeat Mode -->
                    <div
                        @click="toggleRepeatMode"
                        class="group relative cursor-pointer overflow-hidden rounded-xl border p-3 transition-all duration-300 hover:scale-105 hover:transform sm:p-4"
                        :class="{
                            'border-[#2d5a27] bg-gradient-to-br from-[#2d5a27]/20 to-[#d4a574]/20 shadow-xl ring-2 ring-[#2d5a27]/30': repeatMode,
                            'border-[#2d5a27]/20 bg-gradient-to-br from-white to-[#f8f9ff] hover:border-[#2d5a27]/40 hover:shadow-md': !repeatMode,
                        }"
                    >
                        <!-- Active indicator pulse -->
                        <div v-if="repeatMode" class="absolute top-2 right-2">
                            <div class="relative">
                                <div class="h-3 w-3 rounded-full bg-blue-500"></div>
                                <div class="absolute inset-0 h-3 w-3 animate-ping rounded-full bg-blue-400 opacity-75"></div>
                            </div>
                        </div>

                        <!-- Background pattern -->
                        <div class="absolute inset-0 opacity-5">
                            <div class="h-full w-full bg-gradient-to-br from-[#2d5a27] to-transparent"></div>
                        </div>

                        <div class="relative z-10 mb-2 flex items-center">
                            <div
                                class="mr-3 flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-[#2d5a27] to-[#d4a574] shadow-lg transition-transform duration-300 sm:h-10 sm:w-10"
                                :class="{ 'scale-110 shadow-xl': repeatMode }"
                            >
                                <span class="text-lg transition-transform duration-300 sm:text-xl" :class="{ 'animate-spin': repeatMode }">🔄</span>
                            </div>
                            <div class="flex-1">
                                <h4 class="flex items-center text-sm font-bold text-[#2d5a27] sm:text-base">
                                    {{ repeatMode ? 'রিপিট মোড চালু' : 'রিপিট মোড' }}
                                    <span v-if="repeatMode" class="ml-2 rounded-full bg-blue-100 px-2 py-0.5 text-xs text-blue-800">সক্রিয়</span>
                                </h4>
                            </div>
                        </div>
                        <p class="relative z-10 text-xs leading-relaxed text-gray-700 sm:text-sm">
                            {{ repeatMode ? 'রিপিট মোড কার্যকর' : 'হিফয অনুশীলনের জন্য রিপিট মোড' }}
                        </p>

                        <!-- Shine effect on hover -->
                        <div class="absolute inset-0 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                            <div
                                class="h-full w-full translate-x-full -skew-x-12 transform bg-gradient-to-r from-transparent via-white/10 to-transparent transition-transform duration-700 group-hover:translate-x-[-200%]"
                            ></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile-First Compact Selector -->
            <div
                class="mb-4 rounded-xl border border-[#5f5fcd]/10 bg-gradient-to-r from-[#5f5fcd]/5 via-white to-[#2d5a27]/5 p-3 shadow-lg sm:mb-8 sm:rounded-2xl sm:p-6"
            >
                <!-- Mobile: 2 rows, Tablet: 3 items per row, Desktop: All in one row -->
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-4 lg:grid-cols-6">
                    <!-- Surah Selector -->
                    <div class="dropdown-container flex flex-col">
                        <label
                            class="islamic-pattern mb-1 flex items-center justify-center rounded-full px-2 py-1 text-xs font-semibold text-[#2d5a27] sm:mb-2"
                        >
                            <svg class="mr-1 h-2.5 w-2.5 sm:h-3 sm:w-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            সূরা
                        </label>
                        <div class="relative">
                            <select
                                v-model="currentSurahNumber"
                                @change="selectSurah($event)"
                                :disabled="surahLoading"
                                class="w-full rounded-lg border-2 border-[#5f5fcd]/20 bg-gradient-to-r from-white to-[#5f5fcd]/5 px-2 py-2 text-xs font-medium text-gray-700 shadow-sm transition-all duration-300 hover:border-[#5f5fcd]/40 hover:shadow-md focus:border-[#5f5fcd] focus:ring-2 focus:ring-[#5f5fcd]/30 disabled:cursor-not-allowed disabled:opacity-50 sm:px-3 sm:text-sm"
                            >
                                <option value="">সূরা নির্বাচন করুন</option>
                                <option v-for="surah in surahs" :key="surah.number" :value="surah.number">
                                    {{ surah.number }}. {{ surah.banglaName }}
                                </option>
                            </select>
                            <div v-if="surahLoading" class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                <div class="h-4 w-4 animate-spin rounded-full border-2 border-[#5f5fcd] border-t-transparent"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Juz/Para Selector -->
                    <div class="dropdown-container flex flex-col">
                        <label
                            class="islamic-pattern mb-1 flex items-center justify-center rounded-full px-2 py-1 text-xs font-semibold text-[#2d5a27] sm:mb-2"
                        >
                            <svg class="mr-1 h-2.5 w-2.5 sm:h-3 sm:w-3" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                />
                            </svg>
                            পারা
                        </label>
                        <div class="relative">
                            <select
                                v-model="selectedJuz"
                                @change="selectJuz($event)"
                                :disabled="juzLoading"
                                class="w-full rounded-lg border-2 border-[#2d5a27]/20 bg-gradient-to-r from-white to-[#2d5a27]/5 px-2 py-2 text-xs font-medium text-gray-700 shadow-sm transition-all duration-300 hover:border-[#2d5a27]/40 hover:shadow-md focus:border-[#2d5a27] focus:ring-2 focus:ring-[#2d5a27]/30 disabled:cursor-not-allowed disabled:opacity-50 sm:px-3 sm:text-sm"
                            >
                                <option value="">পারা নির্বাচন করুন</option>
                                <option v-for="juz in availableJuzs" :key="juz.number" :value="juz.number">
                                    {{ juz.name }}
                                </option>
                            </select>
                            <div v-if="juzLoading" class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                <div class="h-4 w-4 animate-spin rounded-full border-2 border-[#2d5a27] border-t-transparent"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Reciter Selector -->
                    <div class="dropdown-container flex flex-col">
                        <label
                            class="islamic-pattern mb-1 flex items-center justify-center rounded-full px-2 py-1 text-xs font-semibold text-[#2d5a27] sm:mb-2"
                        >
                            <svg class="mr-1 h-2.5 w-2.5 sm:h-3 sm:w-3" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                />
                            </svg>
                            কণ্ঠস্বর
                        </label>
                        <select
                            v-model="selectedReciter"
                            @change="updateReciter"
                            class="w-full rounded-lg border-2 border-[#d4a574]/20 bg-gradient-to-r from-white to-[#d4a574]/5 px-2 py-2 text-xs font-medium text-gray-700 shadow-sm transition-all duration-300 hover:border-[#d4a574]/40 hover:shadow-md focus:border-[#d4a574] focus:ring-2 focus:ring-[#d4a574]/30 sm:px-3 sm:text-sm"
                        >
                            <option v-for="reciter in availableReciters" :key="reciter.code" :value="reciter.code">
                                {{ reciter.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Translation Selector -->
                    <div class="dropdown-container flex flex-col">
                        <label
                            class="islamic-pattern mb-1 flex items-center justify-center rounded-full px-2 py-1 text-xs font-semibold text-[#2d5a27] sm:mb-2"
                        >
                            <svg class="mr-1 h-2.5 w-2.5 sm:h-3 sm:w-3" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H9.414l-2 2H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6.414l2-2H15a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6z"
                                />
                            </svg>
                            অনুবাদ
                        </label>
                        <select
                            v-model="selectedTranslation"
                            @change="updateTranslation"
                            class="w-full rounded-lg border-2 border-[#5f5fcd]/20 bg-gradient-to-r from-white to-[#5f5fcd]/5 px-2 py-2 text-xs font-medium text-gray-700 shadow-sm transition-all duration-300 hover:border-[#5f5fcd]/40 hover:shadow-md focus:border-[#5f5fcd] focus:ring-2 focus:ring-[#5f5fcd]/30 sm:px-3 sm:text-sm"
                        >
                            <option v-for="translation in availableTranslations" :key="translation.code" :value="translation.code">
                                {{ translation.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Arabic Text Style Selector -->
                    <div class="dropdown-container flex flex-col">
                        <label
                            class="islamic-pattern mb-1 flex items-center justify-center rounded-full px-2 py-1 text-xs font-semibold text-[#2d5a27] sm:mb-2"
                        >
                            <svg class="mr-1 h-2.5 w-2.5 sm:h-3 sm:w-3" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"
                                />
                            </svg>
                            ফন্ট
                        </label>
                        <select
                            v-model="selectedTextStyle"
                            @change="updateTextStyle"
                            class="w-full rounded-lg border-2 border-[#2d5a27]/20 bg-gradient-to-r from-white to-[#2d5a27]/5 px-2 py-2 text-xs font-medium text-gray-700 shadow-sm transition-all duration-300 hover:border-[#2d5a27]/40 hover:shadow-md focus:border-[#2d5a27] focus:ring-2 focus:ring-[#2d5a27]/30 sm:px-3 sm:text-sm"
                        >
                            <option v-for="textStyle in availableTextStyles" :key="textStyle.code" :value="textStyle.code">
                                {{ textStyle.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Page Selector -->
                    <div class="dropdown-container flex flex-col">
                        <label
                            class="islamic-pattern mb-1 flex items-center justify-center rounded-full px-2 py-1 text-xs font-semibold text-[#2d5a27] sm:mb-2"
                        >
                            <svg class="mr-1 h-2.5 w-2.5 sm:h-3 sm:w-3" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                />
                            </svg>
                            পৃষ্ঠা
                        </label>
                        <div class="relative">
                            <select
                                v-model="selectedPage"
                                @change="selectPage($event)"
                                :disabled="pageLoading"
                                class="w-full rounded-lg border-2 border-[#5f5fcd]/20 bg-gradient-to-r from-white to-[#5f5fcd]/5 px-2 py-2 text-xs font-medium text-gray-700 shadow-sm transition-all duration-300 hover:border-[#5f5fcd]/40 hover:shadow-md focus:border-[#5f5fcd] focus:ring-2 focus:ring-[#5f5fcd]/30 disabled:cursor-not-allowed disabled:opacity-50 sm:px-3 sm:text-sm"
                            >
                                <option value="">পৃষ্ঠা নির্বাচন করুন</option>
                                <option v-for="page in availablePages" :key="page.number" :value="page.number">পৃষ্ঠা {{ page.number }}</option>
                            </select>
                            <div v-if="pageLoading" class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                <div class="h-4 w-4 animate-spin rounded-full border-2 border-[#5f5fcd] border-t-transparent"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ultra-Compact Audio Controls -->
                <div class="mt-3 flex flex-wrap items-center justify-center gap-2 sm:mt-4">
                    <button
                        @click="toggleAudio"
                        :disabled="audioLoading"
                        :class="[
                            'flex items-center rounded-lg px-3 py-1.5 text-sm font-medium transition-all hover:shadow-md sm:px-4 sm:py-2',
                            isPlaying
                                ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white'
                                : 'bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] text-white',
                            audioLoading && 'cursor-wait opacity-75',
                        ]"
                    >
                        <div
                            v-if="audioLoading"
                            class="mr-1 h-3 w-3 animate-spin rounded-full border-2 border-white border-t-transparent sm:mr-2 sm:h-4 sm:w-4"
                        ></div>
                        <component v-else :is="isPlaying ? PauseIcon : PlayIcon" class="mr-1 h-3 w-3 sm:mr-2 sm:h-4 sm:w-4" />
                        <span>{{ audioLoading ? 'লোড হচ্ছে...' : isPlaying ? 'থামান' : 'তেলাওয়াত শুনুন' }}</span>
                    </button>
                </div>
            </div>

            <!-- Ultra-Compact Info Badges -->
            <div v-if="currentSurah || currentJuz || selectedPage" class="mb-3 flex flex-wrap justify-center gap-2 sm:mb-4">
                <!-- Surah Badge -->
                <div v-if="currentSurah" class="flex items-center rounded-full bg-gradient-to-r from-[#5f5fcd]/10 to-[#2d5a27]/10 px-3 py-1.5">
                    <span class="text-sm font-semibold text-[#2d5a27]"> সূরা {{ currentSurah.banglaName }} </span>
                    <span class="ml-2 text-xs text-gray-600">
                        {{ currentSurah.revelationType === 'Meccan' ? 'মক্কী' : 'মাদানী' }} • {{ currentSurah.numberOfAyahs }} আয়াত
                    </span>
                </div>

                <!-- Juz Badge -->
                <div v-if="currentJuz" class="flex items-center rounded-full bg-gradient-to-r from-[#2d5a27]/10 to-[#5f5fcd]/10 px-3 py-1.5">
                    <span class="text-sm font-semibold text-[#2d5a27]">
                        {{ currentJuz.name }}
                    </span>
                    <span class="ml-2 text-xs text-gray-600"> {{ currentJuz.arabicName }} • {{ ayahs.length }} আয়াত </span>
                </div>

                <!-- Page Badge -->
                <div v-if="selectedPage" class="flex items-center rounded-full bg-gradient-to-r from-[#5f5fcd]/10 to-[#d4a574]/10 px-3 py-1.5">
                    <span class="text-sm font-semibold text-[#2d5a27]"> পৃষ্ঠা {{ selectedPage }} </span>
                    <span class="ml-2 text-xs text-gray-600"> {{ ayahs.length }} আয়াত </span>
                </div>
            </div>

            <!-- Topic Loading State -->
            <div v-if="topicLoading" class="mb-4 text-center sm:mb-6">
                <div
                    class="inline-flex items-center rounded-full border border-[#d4a574]/20 bg-gradient-to-r from-[#d4a574]/10 to-[#5f5fcd]/10 px-4 py-2"
                >
                    <div class="mr-2 h-5 w-5 animate-spin rounded-full border-b-2 border-[#d4a574]"></div>
                    <span class="font-semibold text-[#2d5a27]"> {{ selectedTopic?.name || 'বিষয়' }} এর আয়াত খুঁজে আনা হচ্ছে... </span>
                </div>
            </div>

            <!-- Topic Results Header -->
            <div v-if="topicResults.length > 0 && !topicLoading" class="animate-fade-in animate-bounce-once mb-4 text-center sm:mb-6">
                <div
                    class="inline-flex items-center rounded-full border border-[#d4a574]/20 bg-gradient-to-r from-[#d4a574]/10 to-[#5f5fcd]/10 px-4 py-2 shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl"
                >
                    <span class="mr-2 text-lg">{{ selectedTopic.icon }}</span>
                    <span class="font-semibold text-[#2d5a27]"> {{ selectedTopic.name }} - {{ topicResults.length }}টি আয়াত পাওয়া গেছে </span>
                    <button
                        @click="clearTopic"
                        class="ml-3 rounded-full bg-[#d4a574]/10 px-2 py-1 text-sm text-[#d4a574] transition-all hover:bg-[#d4a574]/20"
                    >
                        ✕
                    </button>
                </div>
            </div>

            <!-- Search Results Header -->
            <div v-if="searchResults.length > 0 && !selectedTopic" class="mb-4 text-center sm:mb-6">
                <div
                    class="inline-flex items-center rounded-full border border-[#5f5fcd]/20 bg-gradient-to-r from-[#5f5fcd]/10 to-[#2d5a27]/10 px-4 py-2"
                >
                    <svg class="mr-2 h-5 w-5 text-[#5f5fcd]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span class="font-semibold text-[#2d5a27]"> "{{ searchQuery }}" এর জন্য {{ searchResults.length }}টি ফলাফল পাওয়া গেছে </span>
                </div>
            </div>

            <!-- Search Error Message -->
            <div v-if="searchError" class="mb-4 py-4 text-center">
                <div class="rounded-xl border border-red-200 bg-gradient-to-r from-red-50 to-red-100 p-4 sm:p-6">
                    <svg class="mx-auto mb-3 h-10 w-10 text-red-500 sm:h-12 sm:w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                        />
                    </svg>
                    <h3 class="mb-2 text-lg font-semibold text-red-700 sm:text-xl">সমস্যা ঘটেছে</h3>
                    <p class="text-sm text-red-600 sm:text-base">{{ searchError }}</p>
                </div>
            </div>

            <!-- No Search Results -->
            <div v-if="searchQuery.trim() && searchResults.length === 0 && !searchLoading && !searchError" class="py-8 text-center sm:py-12">
                <div class="rounded-xl border border-[#d4a574]/20 bg-gradient-to-r from-[#d4a574]/10 to-[#5f5fcd]/10 p-6 sm:p-8">
                    <svg class="mx-auto mb-4 h-12 w-12 text-[#d4a574] sm:h-16 sm:w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <h3 class="mb-2 text-lg font-semibold text-[#2d5a27] sm:text-xl">কোন ফলাফল পাওয়া যায়নি</h3>
                    <p class="text-sm text-gray-600 sm:text-base">
                        "{{ searchQuery }}" এর জন্য কোন আয়াত খুঁজে পাওয়া যায়নি। অন্য কিছু অনুসন্ধান করে দেখুন।
                    </p>
                </div>
            </div>

            <!-- Progressive Mode Controls -->
            <div v-if="progressiveMode && ayahs.length > 0" class="mb-4 flex justify-center sm:mb-6">
                <div
                    class="inline-flex items-center rounded-xl border border-[#5f5fcd]/20 bg-gradient-to-r from-[#5f5fcd]/10 to-[#2d5a27]/10 p-3 shadow-lg sm:p-4"
                >
                    <button
                        @click="prevAyah"
                        :disabled="currentAyahIndex === 0"
                        class="mr-4 flex items-center rounded-lg bg-[#5f5fcd]/20 px-3 py-2 text-[#5f5fcd] transition-all duration-200 hover:bg-[#5f5fcd]/30 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        আগের
                    </button>

                    <div class="mx-4 flex items-center">
                        <span class="font-bold text-[#2d5a27]"> {{ currentAyahIndex + 1 }} / {{ ayahs.length }} </span>
                        <span class="ml-2 text-sm text-gray-600">আয়াত</span>
                    </div>

                    <button
                        @click="nextAyah"
                        :disabled="currentAyahIndex === ayahs.length - 1"
                        class="ml-4 flex items-center rounded-lg bg-[#2d5a27]/20 px-3 py-2 text-[#2d5a27] transition-all duration-200 hover:bg-[#2d5a27]/30 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        পরের
                        <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Topic Loading Indicator -->
            <div v-if="topicLoading && selectedTopic" class="flex flex-col items-center justify-center py-12">
                <div class="relative">
                    <div class="flex h-16 w-16 animate-pulse items-center justify-center rounded-full bg-gradient-to-br from-[#d4a574] to-[#5f5fcd]">
                        <span class="text-2xl">{{ selectedTopic.icon }}</span>
                    </div>
                    <div class="absolute inset-0 animate-ping">
                        <div class="h-16 w-16 rounded-full border-2 border-[#d4a574] opacity-30"></div>
                    </div>
                </div>
                <p class="mt-4 text-lg font-medium text-[#2d5a27]">{{ selectedTopic.name }} এর আয়াত খুঁজে আনা হচ্ছে...</p>
                <p class="mt-2 text-sm text-gray-600">অনুগ্রহ করে অপেক্ষা করুন</p>
            </div>

            <!-- Juz Boundary Fixing Indicator -->
            <div v-if="juzBoundaryFixing && currentJuz" class="mb-4 text-center">
                <div
                    class="inline-flex items-center rounded-full border border-[#5f5fcd]/20 bg-gradient-to-r from-[#5f5fcd]/10 to-[#2d5a27]/10 px-4 py-2"
                >
                    <div class="mr-2 h-5 w-5 animate-spin rounded-full border-b-2 border-[#5f5fcd]"></div>
                    <span class="font-semibold text-[#2d5a27]">পারা {{ currentJuz.number }} এর সঠিক সীমানা নির্ধারণ করা হচ্ছে...</span>
                </div>
            </div>

            <!-- Premium Ayahs Display -->
            <div
                ref="resultsArea"
                v-if="
                    (ayahs.length > 0 || surahLoading || juzLoading || pageLoading || searchResults.length > 0 || topicResults.length > 0) &&
                    !topicLoading
                "
                class="relative space-y-4 sm:space-y-6"
            >
                <!-- Skeleton Loaders for Ayahs -->
                <template v-if="(surahLoading && currentSurah) || (juzLoading && currentJuz) || (pageLoading && selectedPage)">
                    <div
                        v-for="i in currentSurah ? Math.min(currentSurah.numberOfAyahs || 5, 10) : 10"
                        :key="`skeleton-${i}`"
                        class="group relative animate-pulse overflow-hidden rounded-2xl border border-[#5f5fcd]/10 bg-gradient-to-br from-white via-[#f8f9ff] to-[#f0f7f0] p-4 shadow-lg sm:rounded-3xl sm:p-6"
                    >
                        <!-- Header Skeleton -->
                        <div class="relative z-10 mb-4 flex items-center justify-between sm:mb-6">
                            <div class="flex items-center space-x-3 sm:space-x-4">
                                <!-- Ayah Number Skeleton -->
                                <div class="h-8 w-8 rounded-full bg-gray-200 sm:h-10 sm:w-10"></div>
                                <!-- Info Skeleton -->
                                <div class="space-y-2">
                                    <div class="h-4 w-24 rounded bg-gray-200"></div>
                                    <div class="h-3 w-16 rounded bg-gray-200"></div>
                                </div>
                            </div>
                            <!-- Copy Button Skeleton -->
                            <div class="h-9 w-20 rounded-xl bg-gray-200 sm:h-10 sm:w-24"></div>
                        </div>

                        <!-- Arabic Text Skeleton -->
                        <div
                            class="relative z-10 mb-4 rounded-2xl border border-[#5f5fcd]/20 bg-gradient-to-br from-[#5f5fcd]/8 via-white to-[#2d5a27]/8 p-5 sm:mb-6 sm:p-8"
                        >
                            <div class="space-y-3 text-center">
                                <div class="mx-auto h-6 w-3/4 rounded bg-gray-200 sm:h-8"></div>
                                <div class="mx-auto h-6 w-full rounded bg-gray-200 sm:h-8"></div>
                                <div class="mx-auto h-6 w-5/6 rounded bg-gray-200 sm:h-8"></div>
                            </div>
                        </div>

                        <!-- Translation Skeleton -->
                        <div class="text-center">
                            <div class="space-y-2">
                                <div class="mx-auto h-4 w-full rounded bg-gray-200"></div>
                                <div class="mx-auto h-4 w-5/6 rounded bg-gray-200"></div>
                                <div class="mx-auto h-4 w-4/5 rounded bg-gray-200"></div>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Actual Ayahs Display -->
                <template v-else-if="displayAyahs.length > 0">
                    <div
                        v-for="ayah in displayAyahs"
                        :key="ayah.numberInSurah"
                        class="group relative overflow-hidden rounded-2xl border border-[#5f5fcd]/10 bg-gradient-to-br from-white via-[#f8f9ff] to-[#f0f7f0] p-4 shadow-lg transition-all duration-500 hover:scale-[1.01] hover:transform hover:border-[#5f5fcd]/20 hover:shadow-xl sm:rounded-3xl sm:p-6"
                    >
                        <!-- Subtle Background Pattern -->
                        <div class="absolute inset-0 opacity-[0.02]">
                            <div
                                class="absolute top-0 right-0 h-32 w-32 translate-x-16 -translate-y-16 transform rounded-full bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] blur-3xl"
                            ></div>
                            <div
                                class="absolute bottom-0 left-0 h-24 w-24 -translate-x-12 translate-y-12 transform rounded-full bg-gradient-to-tr from-[#d4a574] to-[#5f5fcd] blur-2xl"
                            ></div>
                        </div>

                        <!-- Enhanced Header -->
                        <div class="relative z-10 mb-4 flex items-center justify-between sm:mb-6">
                            <div class="flex items-center space-x-3 sm:space-x-4">
                                <!-- Premium Ayah Number Badge -->
                                <div class="relative">
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-[#5f5fcd] via-[#4a4aa6] to-[#2d5a27] shadow-lg sm:h-10 sm:w-10"
                                    >
                                        <span class="text-sm font-bold text-white sm:text-base">{{ ayah.numberInSurah }}</span>
                                    </div>
                                    <div class="absolute -inset-1 rounded-full bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] opacity-20 blur"></div>
                                </div>

                                <!-- Ayah Info -->
                                <div class="space-y-1">
                                    <div class="flex items-center space-x-2 text-sm sm:text-base">
                                        <span
                                            v-if="currentJuz || selectedTopic"
                                            class="rounded-full bg-[#2d5a27]/10 px-2 py-1 text-xs font-semibold text-[#2d5a27] sm:text-sm"
                                        >
                                            {{ ayah.surah.englishName }}
                                        </span>
                                        <span
                                            v-if="selectedTopic"
                                            class="flex items-center rounded-full bg-[#d4a574]/10 px-2 py-1 text-xs font-semibold text-[#d4a574] sm:text-sm"
                                        >
                                            <span class="mr-1">{{ selectedTopic.icon }}</span>
                                            {{ selectedTopic.name }}
                                        </span>
                                    </div>
                                    <div v-if="currentJuz || selectedTopic" class="text-xs text-gray-500">
                                        {{ ayah.surah.revelationType === 'Meccan' ? 'মক্কী' : 'মাদানী' }}
                                    </div>
                                </div>
                            </div>

                            <!-- Enhanced Copy Button -->
                            <div class="flex items-center">
                                <button
                                    @click="copyAyah(ayah, $event)"
                                    class="group/copy flex items-center rounded-xl border border-[#5f5fcd]/20 bg-gradient-to-r from-[#5f5fcd]/10 via-[#5f5fcd]/5 to-[#2d5a27]/10 px-3 py-2 shadow-sm transition-all duration-300 hover:border-[#5f5fcd]/40 hover:from-[#5f5fcd]/20 hover:to-[#2d5a27]/20 hover:shadow-md sm:px-4 sm:py-2.5"
                                    title="আয়াত কপি করুন"
                                >
                                    <svg
                                        class="mr-2 h-4 w-4 text-[#5f5fcd] transition-transform group-hover/copy:scale-110 sm:h-5 sm:w-5"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path d="M8 2a1 1 0 000 2h2a1 1 0 100-2H8z" />
                                        <path
                                            d="M3 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v6h-4.586l1.293-1.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L10.414 13H15v3a2 2 0 01-2 2H5a2 2 0 01-2-2V5zM15 11.586l-3-3a1 1 0 00-1.414 1.414L11.586 11H15V11.586z"
                                        />
                                    </svg>
                                    <span class="text-sm font-semibold text-[#5f5fcd] group-hover/copy:text-[#4a4aa6] sm:text-base">কপি</span>
                                </button>
                            </div>
                        </div>

                        <!-- Premium Arabic Text -->
                        <div
                            class="relative z-10 mb-4 rounded-2xl border border-[#5f5fcd]/20 bg-gradient-to-br from-[#5f5fcd]/8 via-white to-[#2d5a27]/8 p-5 shadow-inner sm:mb-6 sm:p-8"
                            dir="rtl"
                        >
                            <div
                                class="font-arabic text-center text-xl leading-[1.8] font-medium text-[#2d5a27] selection:bg-[#5f5fcd]/20 selection:text-[#2d5a27] sm:text-2xl sm:leading-[1.9] md:text-3xl lg:text-4xl lg:leading-[2.0]"
                            >
                                {{ ayah.arabicText || ayah.text }}
                            </div>
                            <!-- Subtle shine effect -->
                            <div
                                class="absolute inset-0 rounded-2xl bg-gradient-to-r from-transparent via-white/10 to-transparent opacity-0 transition-opacity duration-700 group-hover:opacity-100"
                            ></div>
                        </div>

                        <!-- Enhanced Bengali Translation -->
                        <div
                            v-if="!hideTranslation && (ayah.translationText || ayahTranslations[ayah.numberInSurah])"
                            class="relative z-10 text-center"
                        >
                            <div
                                class="relative rounded-xl border border-[#d4a574]/10 bg-gradient-to-r from-[#d4a574]/5 via-[#f8f9ff] to-[#d4a574]/5 p-4 shadow-sm sm:p-6"
                            >
                                <div
                                    class="text-base leading-relaxed font-medium tracking-wide text-gray-700 selection:bg-[#d4a574]/20 selection:text-gray-800 sm:text-lg"
                                    style="line-height: 1.75"
                                >
                                    <!-- Show search match context for topic results -->
                                    <div
                                        v-if="
                                            selectedTopic && ayah.text && ayah.text !== (ayah.translationText || ayahTranslations[ayah.numberInSurah])
                                        "
                                        class="mb-3 rounded-lg border border-blue-200 bg-blue-50 p-2"
                                    >
                                        <div class="mb-1 text-xs font-semibold text-blue-700">ইংরেজি অনুবাদে পাওয়া গেছে:</div>
                                        <div class="text-sm text-blue-600 italic">{{ ayah.text }}</div>
                                    </div>

                                    <span class="text-lg text-[#d4a574] sm:text-xl">"</span>
                                    {{ ayah.translationText || ayahTranslations[ayah.numberInSurah] }}
                                    <span class="text-lg text-[#d4a574] sm:text-xl">"</span>
                                </div>
                                <!-- Quote decoration -->
                                <div class="absolute top-2 left-2 h-2 w-2 rounded-full bg-[#d4a574]/20"></div>
                                <div class="absolute right-2 bottom-2 h-2 w-2 rounded-full bg-[#d4a574]/20"></div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Error Message Display -->
            <div v-if="errorMessage && !surahLoading && !juzLoading && !pageLoading" class="mb-4 sm:mb-6">
                <div class="mx-auto max-w-2xl rounded-xl border border-red-200 bg-red-50 p-4 sm:p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                />
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-medium text-red-800 sm:text-base">
                                {{ errorType === 'audio' ? 'অডিও ত্রুটি' : 'লোডিং ত্রুটি' }}
                            </h3>
                            <p class="mt-1 text-sm text-red-700">{{ errorMessage }}</p>
                            <button
                                @click="clearError"
                                class="mt-3 rounded-lg bg-red-100 px-3 py-1.5 text-sm font-medium text-red-800 hover:bg-red-200"
                            >
                                আবার চেষ্টা করুন
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Audio Element -->
            <audio
                ref="audioElement"
                @play="isPlaying = true"
                @pause="isPlaying = false"
                @ended="handleAudioEnded"
                @error="handleAudioError"
                @canplay="preloadNextAudio"
                @loadstart="audioLoading = true"
                @canplaythrough="audioLoading = false"
                preload="metadata"
                style="display: none"
            ></audio>

            <!-- Pre-buffer Next Audio Element -->
            <audio ref="nextAudioElement" preload="auto" style="display: none"></audio>

            <!-- Back to Top Button -->
            <div v-if="displayAyahs.length > 3" class="fixed right-6 bottom-6 z-50">
                <button
                    @click="scrollToTop"
                    class="group rounded-full bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] p-3 text-white shadow-lg transition-all duration-300 hover:scale-110 hover:shadow-xl active:scale-95"
                    title="উপরে যান"
                >
                    <svg
                        class="h-5 w-5 transition-transform duration-200 group-hover:-translate-y-0.5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                </button>
            </div>
        </div>
    </FrontendLayout>
</template>

<script setup lang="ts">
import FrontendLayout from '@/layouts/FrontendLayout.vue';
import { Head } from '@inertiajs/vue3';
import { PauseIcon, PlayIcon } from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref } from 'vue';

const surahs = ref([]);
const currentSurahNumber = ref('');
const currentSurah = ref(null);
const currentJuz = ref(null);
const ayahs = ref([]);
const ayahTranslations = ref({});
const isPlaying = ref(false);
const audioLoading = ref(false);
const audioElement = ref(null);
const nextAudioElement = ref(null); // Pre-buffer next audio
const resultsArea = ref(null); // Reference to results display area
const surahLoading = ref(false);
const juzLoading = ref(false);
const pageLoading = ref(false);
const errorMessage = ref('');
const errorType = ref(''); // 'surah', 'juz', 'page', 'search', 'audio'
const selectedReciter = ref('afs');
const availableReciters = ref([
    { code: 'afs', name: 'আল-আফাসি', server: 'server8.mp3quran.net/afs', ayahServer: 'everyayah.com/data/Alafasy_128kbps' },
    { code: 'thubti', name: 'সাউদ আশ-শুরাইম', server: 'server6.mp3quran.net/thubti', ayahServer: 'everyayah.com/data/Saood_ash-Shuraym_128kbps' },
    { code: 'husary', name: 'মাহমুদ খলিল আল-হুসারি', server: 'server13.mp3quran.net/husr', ayahServer: 'everyayah.com/data/Husary_128kbps' },
]);

const selectedTranslation = ref('bn.bengali');
const availableTranslations = ref([
    { code: 'bn.bengali', name: 'মুহিউদ্দীন খান', author: 'Muhiuddin Khan' },
    { code: 'bn.hoque', name: 'জহুরুল হক', author: 'Jahorul Hoque' },
    { code: 'en.sahih', name: 'Sahih International (English)', author: 'Sahih International' },
    { code: 'en.pickthall', name: 'Pickthall (English)', author: 'Mohammed Marmaduke William Pickthall' },
    { code: 'en.yusufali', name: 'Yusuf Ali (English)', author: 'Abdullah Yusuf Ali' },
]);

const selectedTextStyle = ref('quran-uthmani');
const availableTextStyles = ref([
    { code: 'quran-uthmani', name: 'উসমানি (ঐতিহ্যবাহী)', description: 'Traditional Uthmani script' },
    { code: 'quran-simple', name: 'সরল (তাশকিল সহ)', description: 'Simple with Tashkeel' },
    { code: 'quran-simple-clean', name: 'সরল ক্লিন (তাশকিল ছাড়া)', description: 'Simple without Tashkeel' },
    { code: 'quran-tajweed', name: 'তাজউইদ (রঙিন)', description: 'Colored for Tajweed rules' },
]);

const selectedJuz = ref('');
// Juz boundaries with exact starting points (surah:ayah)
const juzBoundaries = {
    1: { startSurah: 1, startAyah: 1 },
    2: { startSurah: 2, startAyah: 142 },
    3: { startSurah: 2, startAyah: 253 },
    4: { startSurah: 3, startAyah: 93 },
    5: { startSurah: 4, startAyah: 24 },
    6: { startSurah: 4, startAyah: 148 },
    7: { startSurah: 5, startAyah: 82 },
    8: { startSurah: 6, startAyah: 111 },
    9: { startSurah: 7, startAyah: 88 },
    10: { startSurah: 8, startAyah: 41 },
    11: { startSurah: 9, startAyah: 93 },
    12: { startSurah: 11, startAyah: 6 },
    13: { startSurah: 12, startAyah: 53 },
    14: { startSurah: 15, startAyah: 1 },
    15: { startSurah: 17, startAyah: 1 },
    16: { startSurah: 18, startAyah: 75 },
    17: { startSurah: 21, startAyah: 1 },
    18: { startSurah: 23, startAyah: 1 },
    19: { startSurah: 25, startAyah: 21 },
    20: { startSurah: 27, startAyah: 56 },
    21: { startSurah: 29, startAyah: 46 },
    22: { startSurah: 33, startAyah: 31 },
    23: { startSurah: 36, startAyah: 28 },
    24: { startSurah: 39, startAyah: 32 },
    25: { startSurah: 41, startAyah: 47 },
    26: { startSurah: 46, startAyah: 1 },
    27: { startSurah: 51, startAyah: 31 },
    28: { startSurah: 58, startAyah: 1 },
    29: { startSurah: 67, startAyah: 1 },
    30: { startSurah: 78, startAyah: 1 },
};

const availableJuzs = ref([
    { number: 1, name: '১ম পারা', arabicName: 'الۤمۤ' },
    { number: 2, name: '২য় পারা', arabicName: 'سَيَقُولُ' },
    { number: 3, name: '৩য় পারা', arabicName: 'تِلۡكَ الرُّسُلُ' },
    { number: 4, name: '৪র্থ পারা', arabicName: 'لَن تَنَالُوا۟' },
    { number: 5, name: '৫ম পারা', arabicName: 'وَالۡمُحۡصَنٰتُ' },
    { number: 6, name: '৬ষ্ঠ পারা', arabicName: 'لَا يُحِبُّ اللَّهُ' },
    { number: 7, name: '৭ম পারা', arabicName: 'وَإِذَا سَمِعُوا۟' },
    { number: 8, name: '৮ম পারা', arabicName: 'وَلَوۡ أَنَّنَا' },
    { number: 9, name: '৯ম পারা', arabicName: 'قَالَ الۡمَلَؤُا۟' },
    { number: 10, name: '১০ম পারা', arabicName: 'وَاعۡلَمُوۤا۟' },
    { number: 11, name: '১১ম পারা', arabicName: 'يَعۡتَذِرُونَ' },
    { number: 12, name: '১২ম পারা', arabicName: 'وَمَا مِن دَآبَّةٍ' },
    { number: 13, name: '১৩ম পারা', arabicName: 'وَمَا أُبَرِّئُ' },
    { number: 14, name: '১৪ম পারা', arabicName: 'رُّبَمَا' },
    { number: 15, name: '১৫ম পারা', arabicName: 'سُبۡحَانَ الَّذِىۤ' },
    { number: 16, name: '১৬ম পারা', arabicName: 'قَالَ أَلَمۡ' },
    { number: 17, name: '১৭ম পারা', arabicName: 'اقۡتَرَبَ لِلنَّاسِ' },
    { number: 18, name: '১৮ম পারা', arabicName: 'قَدۡ أَفۡلَحَ' },
    { number: 19, name: '১৯ম পারা', arabicName: 'وَقَالَ الَّذِينَ' },
    { number: 20, name: '২০ম পারা', arabicName: 'أَمَّنۡ خَلَقَ' },
    { number: 21, name: '২১ম পারা', arabicName: 'اتۡلُ مَا أُوحِيَ' },
    { number: 22, name: '২২ম পারা', arabicName: 'وَمَن يَّقۡنُتۡ' },
    { number: 23, name: '২৩ম পারা', arabicName: 'وَمَآ أَنزَلۡنَا' },
    { number: 24, name: '২৪ম পারা', arabicName: 'فَمَنۡ أَظۡلَمُ' },
    { number: 25, name: '২৫ম পারা', arabicName: 'إِلَيۡهِ يُرَدُّ' },
    { number: 26, name: '২৬ম পারা', arabicName: 'حٰمٓ' },
    { number: 27, name: '২৭ম পারা', arabicName: 'قَالَ فَمَا خَطۡبُكُمۡ' },
    { number: 28, name: '২৮ম পারা', arabicName: 'قَدۡ سَمِعَ' },
    { number: 29, name: '২৯ম পারা', arabicName: 'تَبَارَكَ الَّذِى' },
    { number: 30, name: '৩০ম পারা', arabicName: 'عَمَّ يَتَسَآءَلُونَ' },
]);

const selectedPage = ref('');
const availablePages = ref([]);

const generatePageNumbers = () => {
    const pages = [];
    for (let i = 1; i <= 604; i++) {
        pages.push({ number: i });
    }
    return pages;
};

const juzBoundaryFixing = ref(false);
const searchQuery = ref('');
const searchResults = ref([]);
const searchLoading = ref(false);
const searchError = ref(null);
const searchTimeout = ref(null);
const autoSearch = ref(false);
const searchIn = ref('all');
const searchLanguage = ref('arabic');
const showTopics = ref(false);
const selectedTopic = ref(null);
const topicResults = ref([]);
const topicLoading = ref(false);

const CACHE_DURATION = 30 * 60 * 1000;
const LOCALSTORAGE_CACHE_KEY = 'iqra_quran_cache';
const MAX_CACHE_SIZE = 50; // Maximum number of cached items

// Get cache from localStorage
const getLocalCache = () => {
    try {
        const cached = localStorage.getItem(LOCALSTORAGE_CACHE_KEY);
        if (cached) {
            const data = JSON.parse(cached);
            // Clean expired entries
            const now = Date.now();
            const cleaned = {};
            Object.keys(data).forEach((key) => {
                if (data[key].expires > now) {
                    cleaned[key] = data[key];
                }
            });
            return cleaned;
        }
    } catch (e) {
        console.error('Error reading cache:', e);
    }
    return {};
};

// Set cache to localStorage
const setLocalCache = (key, value) => {
    try {
        const cache = getLocalCache();

        // Add new entry
        cache[key] = {
            data: value,
            expires: Date.now() + CACHE_DURATION,
        };

        // Limit cache size
        const keys = Object.keys(cache);
        if (keys.length > MAX_CACHE_SIZE) {
            // Remove oldest entries
            keys.sort((a, b) => cache[a].expires - cache[b].expires);
            for (let i = 0; i < keys.length - MAX_CACHE_SIZE; i++) {
                delete cache[keys[i]];
            }
        }

        localStorage.setItem(LOCALSTORAGE_CACHE_KEY, JSON.stringify(cache));
    } catch (e) {
        console.error('Error setting cache:', e);
    }
};

// Get cached data
const getCachedData = (key) => {
    const cache = getLocalCache();
    if (cache[key] && cache[key].expires > Date.now()) {
        return cache[key].data;
    }
    return null;
};

const conceptSuggestions = ref([]);
const searchHistory = ref([]);
const showMemorizationHelper = ref(false);
const progressiveMode = ref(false);
const hideTranslation = ref(false);
const repeatMode = ref(false);
const currentAyahIndex = ref(0);
const repeatCount = ref(0);
const collectionRepeatCount = ref(0); // For Para/Page level repeats

const topicCategories = ref([
    {
        id: 'salah',
        name: 'নামাজ ও ইবাদত',
        icon: '🤲',
        count: 67,
        description: 'নামাজ, দোয়া, যিকির এবং ইবাদত সম্পর্কিত আয়াত',
        searchTerms: ['صلاة', 'صل', 'صلوا', 'اعبد', 'عبادة', 'نسك', 'دعوة', 'استغفر', 'تسبيح'],
    },
    {
        id: 'zakat',
        name: 'যাকাত ও দানশীলতা',
        icon: '💰',
        count: 32,
        description: 'যাকাত, দান, সাদকা এবং দানশীলতা সম্পর্কিত আয়াত',
        searchTerms: ['زكاة', 'زكوا', 'صدقة', 'انفق', 'انفاق', 'اعطى', 'برر', 'خير'],
    },
    {
        id: 'hajj',
        name: 'হজ ও উমরা',
        icon: '🕋',
        count: 15,
        description: 'হজ, উমরা, কাবা এবং পবিত্র স্থান সম্পর্কিত আয়াত',
        searchTerms: ['حج', 'عمرة', 'كعبة', 'بيت', 'مكة', 'مسجد', 'حرام', 'منسك'],
    },
    {
        id: 'patience',
        name: 'ধৈর্য ও সবর',
        icon: '🌸',
        count: 90,
        description: 'ধৈর্য, সবর, কষ্ট এবং পরীক্ষা সম্পর্কিত আয়াত',
        searchTerms: ['صبر', 'اصبر', 'صابر', 'صابرين', 'بلاء', 'فتنة', 'امتحان', 'ابتلى'],
    },
    {
        id: 'prophets',
        name: 'নবী-রাসূলদের কাহিনী',
        icon: '👥',
        count: 156,
        description: 'হযরত আদম থেকে মুহাম্মদ (সা.) পর্যন্ত সকল নবীদের কাহিনী',
        searchTerms: ['نبي', 'رسول', 'آدم', 'نوح', 'إبراهيم', 'موسى', 'عيسى', 'محمد', 'يوسف', 'داود', 'سليمان'],
    },
    {
        id: 'paradise',
        name: 'জান্নাত ও জাহান্নাম',
        icon: '🌹',
        count: 77,
        description: 'জান্নাত, জাহান্নাম, পরকাল এবং পুরস্কার সম্পর্কিত আয়াত',
        searchTerms: ['جنة', 'جنات', 'نار', 'جهنم', 'عذاب', 'ثواب', 'اجر', 'رحمة'],
    },
    {
        id: 'family',
        name: 'পরিবার ও সমাজ',
        icon: '👪',
        count: 45,
        description: 'পারিবারিক সম্পর্ক, পিতা-মাতা, সন্তান এবং সমাজ সম্পর্কিত আয়াত',
        searchTerms: ['والدين', 'اب', 'ام', 'بنين', 'زوج', 'اهل', 'قرابة', 'رحم'],
    },
    {
        id: 'knowledge',
        name: 'জ্ঞান ও শিক্ষা',
        icon: '📚',
        count: 54,
        description: 'জ্ঞান, শিক্ষা, বুদ্ধিমত্তা এবং চিন্তাভাবনা সম্পর্কিত আয়াত',
        searchTerms: ['علم', 'عالم', 'علماء', 'حكمة', 'فقه', 'فكر', 'عقل', 'تدبر'],
    },
    {
        id: 'forgiveness',
        name: 'ক্ষমা ও তওবা',
        icon: '🕊️',
        count: 88,
        description: 'ক্ষমা, তওবা, অনুশোচনা এবং মাফ সম্পর্কিত আয়াত',
        searchTerms: ['غفر', 'غفور', 'مغفرة', 'توبة', 'تاب', 'استغفار', 'عفو', 'رحيم'],
    },
    {
        id: 'guidance',
        name: 'হিদায়াত ও পথপ্রদর্শন',
        icon: '🌟',
        count: 63,
        description: 'হিদায়াত, সত্য পথ, পথপ্রদর্শন এবং সঠিক দিক সম্পর্কিত আয়াত',
        searchTerms: ['هدى', 'هداية', 'صراط', 'طريق', 'سبيل', 'نور', 'بصيرة', 'رشد'],
    },
    {
        id: 'allah_names',
        name: 'আল্লাহর নাম ও গুণাবলী',
        icon: '✨',
        count: 99,
        description: 'আসমাউল হুসনা - আল্লাহর ৯৯টি নাম এবং গুণাবলী',
        searchTerms: ['الله', 'رحمن', 'رحيم', 'ملك', 'قدوس', 'سلام', 'عزيز', 'غفار', 'قهار', 'وهاب'],
    },
    {
        id: 'creation',
        name: 'সৃষ্টি ও প্রকৃতি',
        icon: '🌍',
        count: 41,
        description: 'সৃষ্টিজগত, প্রকৃতি, আকাশ-পৃথিবী এবং মহাবিশ্ব সম্পর্কিত আয়াত',
        searchTerms: ['خلق', 'خالق', 'سماء', 'ارض', 'شمس', 'قمر', 'نجم', 'بحر', 'جبال'],
    },
]);

// Computed property to determine which ayahs to display
const displayAyahs = computed(() => {
    let ayahsToShow = [];

    if (topicResults.value.length > 0) {
        ayahsToShow = topicResults.value;
    } else if (searchResults.value.length > 0) {
        ayahsToShow = searchResults.value;
    } else {
        ayahsToShow = ayahs.value;
    }

    // Apply progressive mode filter
    if (progressiveMode.value && ayahsToShow.length > 0) {
        // Show only the current ayah and all previous ones
        return ayahsToShow.slice(0, currentAyahIndex.value + 1);
    }

    return ayahsToShow;
});

const performSearch = async () => {
    if (!searchQuery.value.trim()) return;

    searchLoading.value = true;
    searchResults.value = [];
    searchError.value = null;

    try {
        const query = searchQuery.value.trim();
        const searchEdition = searchLanguage.value === 'arabic' ? selectedTextStyle.value : selectedTranslation.value;
        let searchScope = 'all';

        // Add to search history
        addToSearchHistory(query);

        // Generate concept suggestions
        generateConceptSuggestions(query);

        // Determine search scope
        if (searchIn.value === 'current') {
            if (currentSurah.value) {
                searchScope = currentSurah.value.number;
            } else if (currentJuz.value) {
                // For Juz, we'll search across all surahs but filter later
                searchScope = 'all';
            } else if (selectedPage.value) {
                // For Page, we'll search across all surahs but filter later
                searchScope = 'all';
            }
        }

        // API call to search
        const response = await fetch(`https://api.alquran.cloud/v1/search/${encodeURIComponent(query)}/${searchScope}/${searchEdition}`);
        const data = await response.json();

        if (data.data && data.data.matches) {
            let matches = data.data.matches;

            // Filter results for Juz or Page if needed
            if (searchIn.value === 'current') {
                if (currentJuz.value) {
                    // Filter by current Juz ayahs
                    const currentAyahNumbers = ayahs.value.map((ayah) => `${ayah.surah.number}:${ayah.numberInSurah}`);
                    matches = matches.filter((match) => currentAyahNumbers.includes(`${match.surah.number}:${match.numberInSurah}`));
                } else if (selectedPage.value) {
                    // Filter by current Page ayahs
                    const currentAyahNumbers = ayahs.value.map((ayah) => `${ayah.surah.number}:${ayah.numberInSurah}`);
                    matches = matches.filter((match) => currentAyahNumbers.includes(`${match.surah.number}:${match.numberInSurah}`));
                }
            }

            searchResults.value = matches;

            // If searching in Bengali, we need to also load Arabic text for each result
            if (searchLanguage.value === 'bengali') {
                await loadArabicTextForSearchResults(matches);
            }

            // If searching in Arabic, we need to also load Bengali translations
            if (searchLanguage.value === 'arabic') {
                await loadTranslationsForSearchResults(matches);
            }
        }
    } catch (error) {
        console.error('Search error:', error);
        searchError.value = 'অনুসন্ধানে সমস্যা হয়েছে। আবার চেষ্টা করুন।';
        searchResults.value = [];
    } finally {
        searchLoading.value = false;
        // Clear error after 5 seconds
        if (searchError.value) {
            setTimeout(() => {
                searchError.value = null;
            }, 5000);
        }
    }
};

const loadArabicTextForSearchResults = async (matches) => {
    try {
        // Group by surah for efficient API calls
        const surahGroups = {};
        matches.forEach((match) => {
            if (!surahGroups[match.surah.number]) {
                surahGroups[match.surah.number] = [];
            }
            surahGroups[match.surah.number].push(match);
        });

        // Load Arabic text for each surah
        for (const surahNumber of Object.keys(surahGroups)) {
            try {
                const response = await fetch(`https://api.alquran.cloud/v1/surah/${surahNumber}/${selectedTextStyle.value}`);
                const data = await response.json();

                if (data.data && data.data.ayahs) {
                    // Update the matches with Arabic text
                    surahGroups[surahNumber].forEach((match) => {
                        const arabicAyah = data.data.ayahs.find((ayah) => ayah.numberInSurah === match.numberInSurah);
                        if (arabicAyah) {
                            match.arabicText = arabicAyah.text;
                        }
                    });
                }
            } catch (error) {
                console.error(`Error loading Arabic text for surah ${surahNumber}:`, error);
            }
        }
    } catch (error) {
        console.error('Error loading Arabic text for search results:', error);
    }
};

const loadTranslationsForSearchResults = async (matches) => {
    try {
        // Group by surah for efficient API calls
        const surahGroups = {};
        matches.forEach((match) => {
            if (!surahGroups[match.surah.number]) {
                surahGroups[match.surah.number] = [];
            }
            surahGroups[match.surah.number].push(match);
        });

        // Load translations for each surah
        for (const surahNumber of Object.keys(surahGroups)) {
            try {
                const response = await fetch(`https://api.alquran.cloud/v1/surah/${surahNumber}/${selectedTranslation.value}`);
                const data = await response.json();

                if (data.data && data.data.ayahs) {
                    // Update the matches with translation
                    surahGroups[surahNumber].forEach((match) => {
                        const translatedAyah = data.data.ayahs.find((ayah) => ayah.numberInSurah === match.numberInSurah);
                        if (translatedAyah) {
                            match.translationText = translatedAyah.text;
                        }
                    });
                }
            } catch (error) {
                console.error(`Error loading translation for surah ${surahNumber}:`, error);
            }
        }
    } catch (error) {
        console.error('Error loading translations for search results:', error);
    }
};

const clearSearch = () => {
    searchQuery.value = '';
    searchResults.value = [];
    searchError.value = null;
    searchIn.value = 'all';
    searchLanguage.value = 'arabic';
    conceptSuggestions.value = [];
    if (searchTimeout.value) {
        clearTimeout(searchTimeout.value);
        searchTimeout.value = null;
    }
};

const debouncedSearch = () => {
    if (searchTimeout.value) {
        clearTimeout(searchTimeout.value);
    }

    searchTimeout.value = setTimeout(() => {
        if (autoSearch.value && searchQuery.value.trim().length >= 3) {
            performSearch();
        }
    }, 800);
};

const addToSearchHistory = (query) => {
    if (!searchHistory.value.includes(query)) {
        searchHistory.value.unshift(query);
        if (searchHistory.value.length > 10) {
            searchHistory.value = searchHistory.value.slice(0, 10);
        }
        localStorage.setItem('iqra_search_history', JSON.stringify(searchHistory.value));
    }
};

const generateConceptSuggestions = (query) => {
    const concepts = [];
    const lowerQuery = query.toLowerCase();

    // Map search terms to related concepts
    const conceptMap = {
        আল্লাহ: ['allah_names', 'guidance', 'forgiveness'],
        নামাজ: ['salah', 'guidance'],
        সালাত: ['salah', 'guidance'],
        দোয়া: ['salah', 'forgiveness'],
        রহমান: ['allah_names', 'forgiveness'],
        রহিম: ['allah_names', 'forgiveness'],
        ক্ষমা: ['forgiveness', 'guidance'],
        তওবা: ['forgiveness', 'guidance'],
        সবর: ['patience', 'guidance'],
        ধৈর্য: ['patience', 'guidance'],
        জান্নাত: ['paradise', 'guidance'],
        জাহান্নাম: ['paradise', 'guidance'],
        জ্ঞান: ['knowledge', 'guidance'],
        হিদায়াত: ['guidance', 'knowledge'],
        পথ: ['guidance', 'knowledge'],
        সৃষ্টি: ['creation', 'allah_names'],
        পিতা: ['family', 'guidance'],
        মা: ['family', 'guidance'],
        দান: ['zakat', 'guidance'],
        সাদকা: ['zakat', 'guidance'],
        হজ: ['hajj', 'guidance'],
        কাবা: ['hajj', 'guidance'],
        নবী: ['prophets', 'guidance'],
        রাসূল: ['prophets', 'guidance'],
    };

    // Find matching concepts
    for (const [term, relatedConcepts] of Object.entries(conceptMap)) {
        if (lowerQuery.includes(term) || term.includes(lowerQuery)) {
            relatedConcepts.forEach((conceptId) => {
                const concept = topicCategories.value.find((c) => c.id === conceptId);
                if (concept && !concepts.some((c) => c.id === conceptId)) {
                    concepts.push(concept);
                }
            });
        }
    }

    // If no specific matches, suggest popular concepts
    if (concepts.length === 0) {
        const popularConcepts = ['guidance', 'forgiveness', 'patience', 'allah_names', 'salah'];
        popularConcepts.forEach((conceptId) => {
            const concept = topicCategories.value.find((c) => c.id === conceptId);
            if (concept) {
                concepts.push(concept);
            }
        });
    }

    conceptSuggestions.value = concepts.slice(0, 6);
};

const searchByConcept = (concept) => {
    selectedTopic.value = concept;
    searchQuery.value = '';
    searchResults.value = [];
    conceptSuggestions.value = [];
    selectTopic(concept);
};

const loadSearchHistory = () => {
    const saved = localStorage.getItem('iqra_search_history');
    if (saved) {
        try {
            searchHistory.value = JSON.parse(saved);
        } catch (error) {
            console.error('Error loading search history:', error);
            searchHistory.value = [];
        }
    }
};

// Smooth scroll to results area
const scrollToResults = () => {
    if (resultsArea.value) {
        // Calculate offset to ensure header visibility
        const offset = 80; // 80px offset from top
        const elementPosition = resultsArea.value.offsetTop - offset;

        window.scrollTo({
            top: elementPosition,
            behavior: 'smooth',
        });
    }
};

// Scroll to top of page
const scrollToTop = () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth',
    });
};

const selectTopic = async (topic) => {
    // Clear any previous errors
    errorMessage.value = '';
    errorType.value = '';

    // If clicking the same topic, reset/clear it
    if (selectedTopic.value?.id === topic.id) {
        selectedTopic.value = null;
        topicResults.value = [];
        return;
    }

    selectedTopic.value = topic;

    // Scroll to results area with a small delay to ensure content is ready
    setTimeout(() => {
        scrollToResults();
    }, 100);

    // Check cache first
    const cacheKey = `topic_${topic.id}`;
    const cachedData = getCachedData(cacheKey);

    if (cachedData) {
        topicResults.value = cachedData;
        // Scroll again after content is loaded from cache
        setTimeout(() => {
            scrollToResults();
        }, 150);
        return; // Exit early with cached data
    }

    topicLoading.value = true;
    topicResults.value = [];

    // Clear other selections
    searchResults.value = [];
    searchQuery.value = '';

    // Map topic IDs to English search terms
    const topicSearchTerms = {
        salah: ['prayer', 'worship', 'prostrate', 'bow', 'pray'],
        zakat: ['charity', 'alms', 'spend', 'poor', 'needy'],
        hajj: ['pilgrimage', 'hajj', 'mecca', 'kaaba', 'sacred'],
        sawm: ['fasting', 'fast', 'ramadan'],
        prophets: ['prophet', 'messenger', 'abraham', 'moses', 'jesus', 'noah', 'muhammad'],
        tawhid: ['allah', 'god', 'one', 'believe', 'faith', 'worship'],
        akhirah: ['hereafter', 'paradise', 'hell', 'judgment', 'resurrection', 'day'],
        guidance: ['guidance', 'guide', 'path', 'straight', 'right'],
        family: ['wife', 'husband', 'marriage', 'parents', 'children'],
        knowledge: ['knowledge', 'know', 'wisdom', 'think', 'understand'],
        patience: ['patience', 'patient', 'grateful', 'trust', 'thankful'],
        creation: ['creation', 'create', 'earth', 'heaven', 'sun', 'moon', 'sky'],
    };

    const searchTerms = topicSearchTerms[topic.id] || [];

    try {
        // Search for multiple terms and combine results
        const allResults = [];
        const uniqueAyahs = new Set();

        // Parallel search for all terms
        const searchPromises = searchTerms.map(async (term) => {
            try {
                // Check cache for search results
                const searchCacheKey = `search_${term}_en`;
                let data = getCachedData(searchCacheKey);

                if (!data) {
                    // Search in English translation
                    const url = `https://api.alquran.cloud/v1/search/${encodeURIComponent(term)}/all/en`;
                    const response = await fetch(url);
                    data = await response.json();
                    setLocalCache(searchCacheKey, data);
                }

                return { term, data };
            } catch (error) {
                console.error(`Error searching for term "${term}":`, error);
                return { term, data: null };
            }
        });

        // Wait for all searches to complete
        const searchResults = await Promise.all(searchPromises);

        // Process results
        searchResults.forEach(({ term, data }) => {
            if (data && data.data && data.data.matches) {
                data.data.matches.forEach((match) => {
                    const ayahKey = `${match.surah.number}:${match.numberInSurah}`;
                    if (!uniqueAyahs.has(ayahKey)) {
                        uniqueAyahs.add(ayahKey);
                        allResults.push(match);
                    }
                });
            }
        });

        // Sort results by surah and ayah number
        allResults.sort((a, b) => {
            if (a.surah.number !== b.surah.number) {
                return a.surah.number - b.surah.number;
            }
            return a.numberInSurah - b.numberInSurah;
        });

        topicResults.value = allResults;

        // Load translations for the results
        await loadTranslationsForTopicResults(allResults);

        // Cache the results
        setLocalCache(cacheKey, topicResults.value);

        // Scroll to results after content is fully loaded
        setTimeout(() => {
            scrollToResults();
        }, 200);
    } catch (error) {
        console.error('Error loading topic results:', error);
        topicResults.value = [];
        errorMessage.value = `"${topic.name}" এর আয়াত লোড করতে সমস্যা হয়েছে। ইন্টারনেট সংযোগ পরীক্ষা করে আবার চেষ্টা করুন।`;
        errorType.value = 'topic';
    } finally {
        topicLoading.value = false;
    }
};

const loadTranslationsForTopicResults = async (results) => {
    try {
        // Group by surah for efficient API calls
        const surahGroups = {};
        results.forEach((result) => {
            if (!surahGroups[result.surah.number]) {
                surahGroups[result.surah.number] = [];
            }
            surahGroups[result.surah.number].push(result);
        });

        // Load Arabic text and translations for all surahs in parallel
        const surahPromises = Object.keys(surahGroups).map(async (surahNumber) => {
            try {
                // Parallel fetch for Arabic and Bengali
                const [arabicData, bengaliData] = await Promise.all([
                    // Arabic text
                    (async () => {
                        const arabicCacheKey = `surah_${surahNumber}_${selectedTextStyle.value}`;
                        let data = getCachedData(arabicCacheKey);
                        if (!data) {
                            const response = await fetch(`https://api.alquran.cloud/v1/surah/${surahNumber}/${selectedTextStyle.value}`);
                            data = await response.json();
                            setLocalCache(arabicCacheKey, data);
                        }
                        return data;
                    })(),
                    // Bengali translation
                    (async () => {
                        const bengaliCacheKey = `surah_${surahNumber}_${selectedTranslation.value}`;
                        let data = getCachedData(bengaliCacheKey);
                        if (!data) {
                            const response = await fetch(`https://api.alquran.cloud/v1/surah/${surahNumber}/${selectedTranslation.value}`);
                            data = await response.json();
                            setLocalCache(bengaliCacheKey, data);
                        }
                        return data;
                    })(),
                ]);

                if (arabicData.data && arabicData.data.ayahs && bengaliData.data && bengaliData.data.ayahs) {
                    // Update the results with Arabic text and translation
                    surahGroups[surahNumber].forEach((result) => {
                        const arabicAyah = arabicData.data.ayahs.find((ayah) => ayah.numberInSurah === result.numberInSurah);
                        const bengaliAyah = bengaliData.data.ayahs.find((ayah) => ayah.numberInSurah === result.numberInSurah);
                        if (arabicAyah) {
                            result.arabicText = arabicAyah.text; // Store Arabic text separately
                        }
                        if (bengaliAyah) {
                            result.translationText = bengaliAyah.text; // Store Bengali translation
                        }
                    });
                }
            } catch (error) {
                console.error(`Error loading translation for surah ${surahNumber}:`, error);
            }
        });

        // Wait for all surah data to load
        await Promise.all(surahPromises);
    } catch (error) {
        console.error('Error loading translations for topic results:', error);
    }
};

const clearTopic = () => {
    selectedTopic.value = null;
    topicResults.value = [];
    showTopics.value = false;
};

const toggleProgressiveMode = () => {
    progressiveMode.value = !progressiveMode.value;
    currentAyahIndex.value = 0;

    if (progressiveMode.value) {
        // Save preference
        localStorage.setItem('iqra_progressive_mode', 'true');
    } else {
        localStorage.removeItem('iqra_progressive_mode');
    }
};

const toggleTranslationVisibility = () => {
    hideTranslation.value = !hideTranslation.value;

    if (hideTranslation.value) {
        localStorage.setItem('iqra_hide_translation', 'true');
    } else {
        localStorage.removeItem('iqra_hide_translation');
    }
};

const toggleRepeatMode = () => {
    repeatMode.value = !repeatMode.value;
    repeatCount.value = 0;
    collectionRepeatCount.value = 0;

    if (repeatMode.value) {
        localStorage.setItem('iqra_repeat_mode', 'true');
    } else {
        localStorage.removeItem('iqra_repeat_mode');
    }
};

const nextAyah = () => {
    if (progressiveMode.value) {
        const maxIndex = (ayahs.value.length || displayAyahs.value.length) - 1;
        if (currentAyahIndex.value < maxIndex) {
            currentAyahIndex.value++;
        }
    }
};

const prevAyah = () => {
    if (progressiveMode.value && currentAyahIndex.value > 0) {
        currentAyahIndex.value--;
    }
};

const resetProgressive = () => {
    currentAyahIndex.value = 0;
    repeatCount.value = 0;
    collectionRepeatCount.value = 0;
};

const loadMemorizationSettings = () => {
    // Load progressive mode
    if (localStorage.getItem('iqra_progressive_mode') === 'true') {
        progressiveMode.value = true;
    }

    // Load hide translation
    if (localStorage.getItem('iqra_hide_translation') === 'true') {
        hideTranslation.value = true;
    }

    // Load repeat mode
    if (localStorage.getItem('iqra_repeat_mode') === 'true') {
        repeatMode.value = true;
    }
};

// Surah names in Bengali
const getSurahBanglaName = (englishName) => {
    const names = {
        'Al-Fatiha': 'ফাতিহা',
        'Al-Baqarah': 'বাক্বারা',
        "Ali 'Imran": 'আলে-ইমরান',
        'An-Nisa': 'নিসা',
        "Al-Ma'idah": 'মায়িদা',
        "Al-An'am": 'আনআম',
        "Al-A'raf": 'আরাফ',
        'Al-Anfal': 'আনফাল',
        'At-Tawbah': 'তাওবা',
        Yunus: 'ইউনুস',
        Hud: 'হুদ',
        Yusuf: 'ইউসুফ',
        "Ar-Ra'd": 'রাদ',
        Ibrahim: 'ইবরাহীম',
        'Al-Hijr': 'হিজর',
        'An-Nahl': 'নাহল',
        'Al-Isra': 'ইসরা',
        'Al-Kahf': 'কাহফ',
        Maryam: 'মারিয়াম',
        Taha: 'ত্বা-হা',
        'Al-Anbya': 'আম্বিয়া',
        'Al-Hajj': 'হজ্জ',
        "Al-Mu'minun": 'মুমিনূন',
        'An-Nur': 'নূর',
        'Al-Furqan': 'ফুরকান',
        "Ash-Shu'ara": 'শুআরা',
        'An-Naml': 'নামল',
        'Al-Qasas': 'কাসাস',
        "Al-'Ankabut": 'আনকাবুত',
        'Ar-Rum': 'রূম',
        Luqman: 'লুকমান',
        'As-Sajdah': 'সিজদা',
        'Al-Ahzab': 'আহযাব',
        Saba: 'সাবা',
        Fatir: 'ফাতির',
        'Ya-Sin': 'ইয়া-সীন',
        'As-Saffat': 'সাফফাত',
        Sad: 'সোয়াদ',
        'Az-Zumar': 'যুমার',
        Ghafir: 'গাফির',
        Fussilat: 'ফুসসিলাত',
        'Ash-Shuraa': 'শূরা',
        'Az-Zukhruf': 'যুখরুফ',
        'Ad-Dukhan': 'দুখান',
        'Al-Jathiyah': 'জাসিয়া',
        'Al-Ahqaf': 'আহকাফ',
        Muhammad: 'মুহাম্মাদ',
        'Al-Fath': 'ফাতহ',
        'Al-Hujurat': 'হুজুরাত',
        Qaf: 'ক্বাফ',
        'Adh-Dhariyat': 'যারিয়াত',
        'At-Tur': 'তূর',
        'An-Najm': 'নাজম',
        'Al-Qamar': 'কামার',
        'Ar-Rahman': 'রহমান',
        "Al-Waqi'ah": 'ওয়াকিয়া',
        'Al-Hadid': 'হাদীদ',
        'Al-Mujadila': 'মুজাদালা',
        'Al-Hashr': 'হাশর',
        'Al-Mumtahanah': 'মুমতাহিনা',
        'As-Saff': 'সাফ',
        "Al-Jumu'ah": 'জুমআ',
        'Al-Munafiqun': 'মুনাফিকূন',
        'At-Taghabun': 'তাগাবুন',
        'At-Talaq': 'তালাক',
        'At-Tahrim': 'তাহরীম',
        'Al-Mulk': 'মুলক',
        'Al-Qalam': 'কালাম',
        'Al-Haqqah': 'হাক্কা',
        "Al-Ma'arij": 'মাআরিজ',
        Nuh: 'নূহ',
        'Al-Jinn': 'জিন',
        'Al-Muzzammil': 'মুযযাম্মিল',
        'Al-Muddaththir': 'মুদ্দাস্সির',
        'Al-Qiyamah': 'কিয়ামা',
        'Al-Insan': 'ইনসান',
        'Al-Mursalat': 'মুরসালাত',
        'An-Naba': 'নাবা',
        "An-Nazi'at": 'নাযিআত',
        "'Abasa": 'আবাসা',
        'At-Takwir': 'তাকবীর',
        'Al-Infitar': 'ইনফিতার',
        'Al-Mutaffifin': 'মুতাফফিফীন',
        'Al-Inshiqaq': 'ইনশিকাক',
        'Al-Buruj': 'বুরূজ',
        'At-Tariq': 'তারিক',
        "Al-A'la": 'আলা',
        'Al-Ghashiyah': 'গাশিয়া',
        'Al-Fajr': 'ফজর',
        'Al-Balad': 'বালাদ',
        'Ash-Shams': 'শামস',
        'Al-Layl': 'লাইল',
        'Ad-Duhaa': 'দুহা',
        'Ash-Sharh': 'শারহ',
        'At-Tin': 'তীন',
        "Al-'Alaq": 'আলাক',
        'Al-Qadr': 'কদর',
        'Al-Bayyinah': 'বাইয়্যিনা',
        'Az-Zalzalah': 'যিলযাল',
        "Al-'Adiyat": 'আদিয়াত',
        "Al-Qari'ah": 'কারিয়া',
        'At-Takathur': 'তাকাসুর',
        "Al-'Asr": 'আসর',
        'Al-Humazah': 'হুমাযা',
        'Al-Fil': 'ফীল',
        Quraysh: 'কুরাইশ',
        "Al-Ma'un": 'মাউন',
        'Al-Kawthar': 'কাওসার',
        'Al-Kafirun': 'কাফিরূন',
        'An-Nasr': 'নাসর',
        'Al-Masad': 'মাসাদ',
        'Al-Ikhlas': 'ইখলাস',
        'Al-Falaq': 'ফালাক',
        'An-Nas': 'নাস',
    };
    return names[englishName] || englishName;
};

// Load surahs list
const loadSurahs = async () => {
    const response = await fetch('https://api.alquran.cloud/v1/surah');
    const data = await response.json();

    surahs.value = data.data.map((surah) => ({
        ...surah,
        banglaName: getSurahBanglaName(surah.englishName),
    }));
};

const selectSurah = async (event) => {
    // Prevent any default behavior
    if (event && event.preventDefault) {
        event.preventDefault();
    }

    const surah = surahs.value.find((s) => s.number === currentSurahNumber.value);
    if (!surah) return;

    // Update surah info immediately for instant feedback
    currentSurah.value = surah;
    currentJuz.value = null; // Clear juz when selecting surah
    selectedJuz.value = ''; // Clear juz dropdown
    selectedPage.value = ''; // Clear page when selecting surah

    // Stop any playing audio and reset states
    if (audioElement.value) {
        audioElement.value.pause();
        audioElement.value.src = '';
    }
    isPlaying.value = false;
    currentlyPlayingAyahIndex.value = -1;

    // Clear previous ayahs but show loading state
    ayahs.value = [];
    ayahTranslations.value = {};
    searchResults.value = []; // Clear search results
    topicResults.value = []; // Clear topic results

    // Clear any previous errors
    errorMessage.value = '';
    errorType.value = '';

    // Start loading indicator after setting currentSurah
    surahLoading.value = true;

    try {
        // Progressive loading: First load Arabic text
        const loadArabicText = async () => {
            try {
                // Check cache first
                const cacheKey = `surah_${currentSurah.value.number}_${selectedTextStyle.value}`;
                const cachedData = getCachedData(cacheKey);

                if (cachedData) {
                    // Use cached data
                    ayahs.value = cachedData.ayahs;
                    if (ayahs.value.length > 0) {
                        surahLoading.value = false;
                    }
                    return;
                }

                // If not cached, fetch from API
                const arabicResponse = await fetch(`https://api.alquran.cloud/v1/surah/${currentSurah.value.number}/${selectedTextStyle.value}`);
                const arabicJson = await arabicResponse.json();

                // Progressive update: Add ayahs as they come
                ayahs.value = arabicJson.data.ayahs;

                // Cache the successful response
                if (arabicJson.data) {
                    setLocalCache(cacheKey, arabicJson.data);
                }

                // If we have ayahs, we can hide the skeleton loader
                if (ayahs.value.length > 0) {
                    surahLoading.value = false;
                }
            } catch (error) {
                // Fallback to default text style
                try {
                    const fallbackResponse = await fetch(`https://api.alquran.cloud/v1/surah/${currentSurah.value.number}`);
                    const fallbackJson = await fallbackResponse.json();
                    ayahs.value = fallbackJson.data.ayahs;

                    if (ayahs.value.length > 0) {
                        surahLoading.value = false;
                    }
                } catch (fallbackError) {
                    ayahs.value = [];
                    surahLoading.value = false;
                    errorMessage.value = 'সূরা লোড করতে সমস্যা হয়েছে। ইন্টারনেট সংযোগ পরীক্ষা করে আবার চেষ্টা করুন।';
                    errorType.value = 'surah';
                }
            }
        };

        // Load translations separately (don't wait for it)
        const loadTranslations = async () => {
            try {
                // Check cache first
                const cacheKey = `surah_trans_${currentSurah.value.number}_${selectedTranslation.value}`;
                const cachedData = getCachedData(cacheKey);

                if (cachedData) {
                    // Use cached data
                    const translations = {};
                    cachedData.ayahs.forEach((ayah) => {
                        translations[ayah.numberInSurah] = ayah.text;
                    });
                    ayahTranslations.value = translations;
                    return;
                }

                const translationResponse = await fetch(
                    `https://api.alquran.cloud/v1/surah/${currentSurah.value.number}/${selectedTranslation.value}`,
                );
                const translationJson = await translationResponse.json();
                const translations = {};
                translationJson.data.ayahs.forEach((ayah) => {
                    translations[ayah.numberInSurah] = ayah.text;
                });
                ayahTranslations.value = translations;

                // Cache the successful response
                if (translationJson.data) {
                    setLocalCache(cacheKey, translationJson.data);
                }
            } catch (error) {
                // Fallback to default translation
                try {
                    const fallbackResponse = await fetch(`https://api.alquran.cloud/v1/surah/${currentSurah.value.number}/bn.bengali`);
                    const fallbackJson = await fallbackResponse.json();
                    const translations = {};
                    fallbackJson.data.ayahs.forEach((ayah) => {
                        translations[ayah.numberInSurah] = ayah.text;
                    });
                    ayahTranslations.value = translations;
                } catch (fallbackError) {
                    ayahTranslations.value = {};
                }
            }
        };

        // Start both but don't wait for translations
        await loadArabicText();
        loadTranslations(); // Fire and forget for progressive loading
    } catch (error) {
        console.error('Error loading surah:', error);
        surahLoading.value = false;
    }
};

const selectJuz = async (event) => {
    // Prevent any default behavior
    if (event && event.preventDefault) {
        event.preventDefault();
    }

    if (!selectedJuz.value || selectedJuz.value === '') {
        // "পারা নির্বাচন করুন" selected, just reset without navigation
        currentJuz.value = null;
        return;
    }

    const juz = availableJuzs.value.find((j) => j.number === selectedJuz.value);
    if (!juz) return;

    // Update juz info immediately for instant feedback
    currentJuz.value = juz;
    currentSurah.value = null; // Clear surah when selecting juz
    currentSurahNumber.value = ''; // Clear surah dropdown
    selectedPage.value = ''; // Clear page when selecting juz

    // Stop any playing audio and reset states
    if (audioElement.value) {
        audioElement.value.pause();
        audioElement.value.src = '';
    }
    isPlaying.value = false;
    currentlyPlayingAyahIndex.value = -1;

    // Clear previous ayahs but show loading state
    ayahs.value = [];
    ayahTranslations.value = {};
    searchResults.value = []; // Clear search results
    topicResults.value = []; // Clear topic results

    // Clear any previous errors
    errorMessage.value = '';
    errorType.value = '';

    // Start loading indicator after setting currentJuz
    juzLoading.value = true;

    try {
        // Progressive loading: First load Arabic text
        await loadJuzArabicText();

        // Load translations separately (don't wait for it)
        loadJuzTranslation(); // Fire and forget for progressive loading
    } catch (error) {
        console.error('Error loading juz:', error);
        juzLoading.value = false;
    }
};

const selectPage = async (event) => {
    // Prevent any default behavior
    if (event && event.preventDefault) {
        event.preventDefault();
    }

    if (!selectedPage.value || selectedPage.value === '') return;

    // Note: Page doesn't need to be cleared since it's the selected value
    currentSurah.value = null; // Clear surah when selecting page
    currentJuz.value = null; // Clear juz when selecting page
    selectedJuz.value = ''; // Clear juz dropdown
    currentSurahNumber.value = ''; // Clear surah dropdown

    // Stop any playing audio and reset states
    if (audioElement.value) {
        audioElement.value.pause();
        audioElement.value.src = '';
    }
    isPlaying.value = false;
    currentlyPlayingAyahIndex.value = -1;

    // Clear previous ayahs but show loading state
    ayahs.value = [];
    ayahTranslations.value = {};
    searchResults.value = []; // Clear search results
    topicResults.value = []; // Clear topic results

    // Clear any previous errors
    errorMessage.value = '';
    errorType.value = '';

    // Start loading indicator
    pageLoading.value = true;

    try {
        // Progressive loading: First load Arabic text
        await loadPageArabicText();

        // Load translations separately (don't wait for it)
        loadPageTranslation(); // Fire and forget for progressive loading
    } catch (error) {
        console.error('Error loading page:', error);
        pageLoading.value = false;
    }
};

const loadSurahArabicText = async () => {
    if (!currentSurah.value) return;

    try {
        const arabicResponse = await fetch(`https://api.alquran.cloud/v1/surah/${currentSurah.value.number}/${selectedTextStyle.value}`);
        const arabicData = await arabicResponse.json();
        ayahs.value = arabicData.data.ayahs;
    } catch (error) {
        console.error('Error loading Arabic text:', error);
        // Fallback to default text style
        try {
            const fallbackResponse = await fetch(`https://api.alquran.cloud/v1/surah/${currentSurah.value.number}`);
            const fallbackData = await fallbackResponse.json();
            ayahs.value = fallbackData.data.ayahs;
        } catch (fallbackError) {
            console.error('Error loading fallback Arabic text:', fallbackError);
        }
    }
};

const loadSurahTranslation = async () => {
    if (!currentSurah.value) return;

    try {
        const translationResponse = await fetch(`https://api.alquran.cloud/v1/surah/${currentSurah.value.number}/${selectedTranslation.value}`);
        const translationData = await translationResponse.json();

        const translations = {};
        translationData.data.ayahs.forEach((ayah) => {
            translations[ayah.numberInSurah] = ayah.text;
        });
        ayahTranslations.value = translations;
    } catch (error) {
        console.error('Error loading translation:', error);
        // Fallback to default translation
        try {
            const fallbackResponse = await fetch(`https://api.alquran.cloud/v1/surah/${currentSurah.value.number}/bn.bengali`);
            const fallbackData = await fallbackResponse.json();

            const translations = {};
            fallbackData.data.ayahs.forEach((ayah) => {
                translations[ayah.numberInSurah] = ayah.text;
            });
            ayahTranslations.value = translations;
        } catch (fallbackError) {
            console.error('Error loading fallback translation:', fallbackError);
        }
    }
};

const fixJuzBoundary = async (juzNumber, textStyle = 'quran-uthmani') => {
    const boundary = juzBoundaries[juzNumber];
    if (!boundary || ayahs.value.length === 0) return;

    const firstAyah = ayahs.value[0];

    // Check if the first ayah matches the expected boundary
    if (firstAyah.surah.number === boundary.startSurah && firstAyah.numberInSurah === boundary.startAyah) {
        return;
    }

    juzBoundaryFixing.value = true;

    try {
        // Check if we're missing ayahs at the beginning (same surah but later ayah)
        if (firstAyah.surah.number === boundary.startSurah && firstAyah.numberInSurah > boundary.startAyah) {
            const missingAyahs = [];

            // Fetch all missing ayahs
            for (let ayahNum = boundary.startAyah; ayahNum < firstAyah.numberInSurah; ayahNum++) {
                try {
                    const response = await fetch(`https://api.alquran.cloud/v1/ayah/${boundary.startSurah}:${ayahNum}/${textStyle}`);
                    const data = await response.json();

                    if (data.data) {
                        missingAyahs.push(data.data);
                    }
                } catch (error) {
                    // Silent fail for individual ayah fetch
                }
            }

            // Add missing ayahs at the beginning
            if (missingAyahs.length > 0) {
                ayahs.value = [...missingAyahs, ...ayahs.value];
            }
        }
        // Check if API started from wrong surah or earlier ayah
        else {
            try {
                const response = await fetch(`https://api.alquran.cloud/v1/ayah/${boundary.startSurah}:${boundary.startAyah}/${textStyle}`);
                const data = await response.json();

                if (data.data) {
                    // Check if this ayah already exists in our array
                    const existingIndex = ayahs.value.findIndex(
                        (a) => a.surah.number === data.data.surah.number && a.numberInSurah === data.data.numberInSurah,
                    );

                    if (existingIndex === -1) {
                        ayahs.value.unshift(data.data);
                    }
                }
            } catch (error) {
                // Silent fail for boundary correction
            }
        }
    } finally {
        juzBoundaryFixing.value = false;
    }
};

const loadJuzArabicText = async () => {
    if (!currentJuz.value) return;

    const juzNumber = currentJuz.value.number;

    try {
        const arabicResponse = await fetch(`https://api.alquran.cloud/v1/juz/${juzNumber}/${selectedTextStyle.value}`);
        const arabicData = await arabicResponse.json();

        if (arabicData.data && arabicData.data.ayahs && arabicData.data.ayahs.length > 0) {
            ayahs.value = arabicData.data.ayahs;

            // Hide skeleton loader once we have ayahs
            juzLoading.value = false;

            await fixJuzBoundary(juzNumber, selectedTextStyle.value);
        } else {
            throw new Error('No ayahs returned from API');
        }
    } catch (error) {
        // Fallback to default text style
        try {
            const fallbackResponse = await fetch(`https://api.alquran.cloud/v1/juz/${juzNumber}`);
            const fallbackData = await fallbackResponse.json();

            if (fallbackData.data && fallbackData.data.ayahs) {
                ayahs.value = fallbackData.data.ayahs;

                // Hide skeleton loader once we have ayahs
                juzLoading.value = false;

                await fixJuzBoundary(juzNumber, 'quran-uthmani');
            } else {
                ayahs.value = [];
                juzLoading.value = false;
            }
        } catch (fallbackError) {
            ayahs.value = [];
            juzLoading.value = false;
            errorMessage.value = 'পারা লোড করতে সমস্যা হয়েছে। ইন্টারনেট সংযোগ পরীক্ষা করে আবার চেষ্টা করুন।';
            errorType.value = 'juz';
        }
    }
};

const loadJuzTranslation = async () => {
    if (!currentJuz.value || !ayahs.value.length) return;

    try {
        // First, try to get the entire Juz translation
        const translationResponse = await fetch(`https://api.alquran.cloud/v1/juz/${currentJuz.value.number}/${selectedTranslation.value}`);
        const translationData = await translationResponse.json();

        const translations = {};

        // Create a map of translations from the API response
        const apiTranslations = {};
        translationData.data.ayahs.forEach((ayah) => {
            const key = `${ayah.surah.number}:${ayah.numberInSurah}`;
            apiTranslations[key] = ayah.text;
        });

        // Map translations to our displayed ayahs
        ayahs.value.forEach((ayah) => {
            const key = `${ayah.surah.number}:${ayah.numberInSurah}`;
            if (apiTranslations[key]) {
                translations[ayah.numberInSurah] = apiTranslations[key];
            }
        });

        ayahTranslations.value = translations;

        // Fetch any missing translations individually
        const missingAyahs = ayahs.value.filter((ayah) => !translations[ayah.numberInSurah]);
        if (missingAyahs.length > 0) {
            for (const ayah of missingAyahs) {
                try {
                    const response = await fetch(
                        `https://api.alquran.cloud/v1/ayah/${ayah.surah.number}:${ayah.numberInSurah}/${selectedTranslation.value}`,
                    );
                    const data = await response.json();

                    if (data.data) {
                        ayahTranslations.value[ayah.numberInSurah] = data.data.text;
                    }
                } catch (error) {
                    console.error(`Error loading translation for ayah ${ayah.surah.number}:${ayah.numberInSurah}:`, error);
                }
            }
        }
    } catch (error) {
        console.error('Error loading Juz translation:', error);
        // Fallback to default translation
        try {
            const fallbackResponse = await fetch(`https://api.alquran.cloud/v1/juz/${currentJuz.value.number}/bn.bengali`);
            const fallbackData = await fallbackResponse.json();

            const translations = {};

            // Create a map of translations from the API response
            const apiTranslations = {};
            fallbackData.data.ayahs.forEach((ayah) => {
                const key = `${ayah.surah.number}:${ayah.numberInSurah}`;
                apiTranslations[key] = ayah.text;
            });

            // Map translations to our displayed ayahs
            ayahs.value.forEach((ayah) => {
                const key = `${ayah.surah.number}:${ayah.numberInSurah}`;
                if (apiTranslations[key]) {
                    translations[ayah.numberInSurah] = apiTranslations[key];
                }
            });

            ayahTranslations.value = translations;
        } catch (fallbackError) {
            console.error('Error loading fallback Juz translation:', fallbackError);
        }
    }
};

const loadPageArabicText = async () => {
    if (!selectedPage.value || selectedPage.value === '') return;

    try {
        const arabicResponse = await fetch(`https://api.alquran.cloud/v1/page/${selectedPage.value}/${selectedTextStyle.value}`);
        const arabicData = await arabicResponse.json();
        ayahs.value = arabicData.data.ayahs;

        // Hide skeleton loader once we have ayahs
        if (ayahs.value.length > 0) {
            pageLoading.value = false;
        }
    } catch (error) {
        console.error('Error loading Page Arabic text:', error);
        // Fallback to default text style
        try {
            const fallbackResponse = await fetch(`https://api.alquran.cloud/v1/page/${selectedPage.value}`);
            const fallbackData = await fallbackResponse.json();
            ayahs.value = fallbackData.data.ayahs;

            // Hide skeleton loader once we have ayahs
            if (ayahs.value.length > 0) {
                pageLoading.value = false;
            }
        } catch (fallbackError) {
            console.error('Error loading fallback Page Arabic text:', fallbackError);
            ayahs.value = [];
            pageLoading.value = false;
            errorMessage.value = 'পৃষ্ঠা লোড করতে সমস্যা হয়েছে। ইন্টারনেট সংযোগ পরীক্ষা করে আবার চেষ্টা করুন।';
            errorType.value = 'page';
        }
    }
};

const loadPageTranslation = async () => {
    if (!selectedPage.value || selectedPage.value === '') return;

    try {
        const translationResponse = await fetch(`https://api.alquran.cloud/v1/page/${selectedPage.value}/${selectedTranslation.value}`);
        const translationData = await translationResponse.json();

        const translations = {};
        translationData.data.ayahs.forEach((ayah) => {
            translations[ayah.numberInSurah] = ayah.text;
        });
        ayahTranslations.value = translations;
    } catch (error) {
        console.error('Error loading Page translation:', error);
        // Fallback to default translation
        try {
            const fallbackResponse = await fetch(`https://api.alquran.cloud/v1/page/${selectedPage.value}/bn.bengali`);
            const fallbackData = await fallbackResponse.json();

            const translations = {};
            fallbackData.data.ayahs.forEach((ayah) => {
                translations[ayah.numberInSurah] = ayah.text;
            });
            ayahTranslations.value = translations;
        } catch (fallbackError) {
            console.error('Error loading fallback Page translation:', fallbackError);
        }
    }
};

// Track if audio has been initialized
const audioInitialized = ref(false);

// Toggle audio playback
const toggleAudio = async () => {
    // Check if we have either surah, juz, or page selected
    if (!currentSurah.value && !currentJuz.value && !selectedPage.value) return;

    if (isPlaying.value) {
        audioElement.value.pause();
        repeatCount.value = 0; // Reset repeat count when stopping
        collectionRepeatCount.value = 0; // Reset collection repeat count
        currentlyPlayingAyahIndex.value = -1; // Reset playing index
        return;
    }

    // Check if audio is paused and can be resumed
    if (audioElement.value.src && audioElement.value.paused && !audioElement.value.ended) {
        try {
            await audioElement.value.play();
            return;
        } catch (error) {
            // If resume fails, load fresh audio
        }
    }

    // Reset repeat counts when loading new audio
    repeatCount.value = 0;
    collectionRepeatCount.value = 0;

    // For Juz/Para mode, we'll play the first ayah of the juz
    // Since juz can start in the middle of a surah, we can't play full surah audio
    if (currentJuz.value) {
        if (ayahs.value.length > 0) {
            const firstAyah = ayahs.value[0];
            const surahNumber = firstAyah.surah.number;
            const ayahNumber = firstAyah.numberInSurah;

            // Create padded numbers for the audio file
            const surahPadded = surahNumber.toString().padStart(3, '0');
            const ayahPadded = ayahNumber.toString().padStart(3, '0');

            // Use the selected reciter's ayah server
            const currentReciter = availableReciters.value.find((r) => r.code === selectedReciter.value);
            if (!currentReciter) {
                console.error('Selected reciter not found:', selectedReciter.value);
                return;
            }

            // For juz, we'll use individual ayah audio from the selected reciter
            // This ensures we start from the correct ayah even if it's in the middle of a surah
            const audioUrl = `https://${currentReciter.ayahServer}/${surahPadded}${ayahPadded}.mp3`;

            audioElement.value.src = audioUrl;

            // Store current ayah info for continuous playback
            audioElement.value.dataset.currentAyahIndex = '0';
            audioElement.value.dataset.playMode = 'juz';
            currentlyPlayingAyahIndex.value = 0;
        } else {
            return; // No ayahs to play
        }
    } else if (selectedPage.value) {
        // For Page mode, we'll play the first ayah of the page
        // Similar to juz mode, since pages can start in the middle of a surah
        if (ayahs.value.length > 0) {
            const firstAyah = ayahs.value[0];
            const surahNumber = firstAyah.surah.number;
            const ayahNumber = firstAyah.numberInSurah;

            // Create padded numbers for the audio file
            const surahPadded = surahNumber.toString().padStart(3, '0');
            const ayahPadded = ayahNumber.toString().padStart(3, '0');

            // Use the selected reciter's ayah server
            const currentReciter = availableReciters.value.find((r) => r.code === selectedReciter.value);
            if (!currentReciter) {
                console.error('Selected reciter not found:', selectedReciter.value);
                return;
            }

            // For page, we'll use individual ayah audio from the selected reciter
            // This ensures we start from the correct ayah even if it's in the middle of a surah
            const audioUrl = `https://${currentReciter.ayahServer}/${surahPadded}${ayahPadded}.mp3`;

            audioElement.value.src = audioUrl;

            // Store current ayah info for continuous playback
            audioElement.value.dataset.currentAyahIndex = '0';
            audioElement.value.dataset.playMode = 'page';
            currentlyPlayingAyahIndex.value = 0;
        } else {
            return; // No ayahs to play
        }
    } else if (currentSurah.value) {
        // For surah mode, play the complete surah
        const surahNumber = currentSurah.value.number;

        // Create padded surah number
        const surahPadded = surahNumber < 10 ? '00' + surahNumber : surahNumber < 100 ? '0' + surahNumber : surahNumber.toString();

        // Use the selected reciter's server
        let currentReciter = availableReciters.value.find((r) => r.code === selectedReciter.value);
        if (!currentReciter) {
            // Fallback to default reciter if not found
            selectedReciter.value = 'afs';
            currentReciter = availableReciters.value.find((r) => r.code === 'afs');
        }

        const baseUrl = 'https://' + currentReciter.server + '/';
        const fileName = surahPadded + '.mp3';
        const audioUrl = baseUrl + fileName;

        audioElement.value.src = audioUrl;

        // Store play mode for surah
        audioElement.value.dataset.playMode = 'surah';
    }

    // If first time, try to initialize audio context
    if (!audioInitialized.value) {
        audioInitialized.value = true;
        try {
            // Try to play immediately
            await audioElement.value.play();
        } catch (error) {
            // If fails, wait a bit and try again
            setTimeout(async () => {
                try {
                    await audioElement.value.play();
                } catch (retryError) {
                    // Silent fail on retry
                }
            }, 200);
        }
    } else {
        // Audio already initialized, should work immediately
        try {
            await audioElement.value.play();
        } catch (error) {
            // Silent fail
        }
    }
};

// Stop audio completely
const stopAudio = () => {
    if (audioElement.value) {
        audioElement.value.pause();
        audioElement.value.currentTime = 0;
        audioElement.value.src = '';
        isPlaying.value = false;
    }
};

// Update reciter selection
const updateReciter = () => {
    // Save user preference
    localStorage.setItem('iqra_selected_reciter', selectedReciter.value);

    // Stop current audio when changing reciter
    if (isPlaying.value) {
        stopAudio();
    }
};

// Update translation selection
const updateTranslation = () => {
    // Save user preference
    localStorage.setItem('iqra_selected_translation', selectedTranslation.value);

    // Reload current content with new translation
    if (currentSurah.value) {
        loadSurahTranslation();
    } else if (currentJuz.value) {
        loadJuzTranslation();
    } else if (selectedPage.value) {
        loadPageTranslation();
    }
};

// Update text style selection
const updateTextStyle = () => {
    // Save user preference
    localStorage.setItem('iqra_selected_text_style', selectedTextStyle.value);

    // Reload current content with new text style
    if (currentSurah.value) {
        loadSurahArabicText();
    } else if (currentJuz.value) {
        loadJuzArabicText();
    } else if (selectedPage.value) {
        loadPageArabicText();
    }
};

// Handle audio errors
const handleAudioError = (event) => {
    isPlaying.value = false;
    audioLoading.value = false;
    errorMessage.value = 'অডিও চালাতে সমস্যা হয়েছে। দয়া করে অন্য একটি কারী নির্বাচন করুন অথবা পরে চেষ্টা করুন।';
    errorType.value = 'audio';
};

// Track currently playing ayah for juz mode
const currentlyPlayingAyahIndex = ref(-1);

// Handle audio ended - for repeat mode and continuous playback
const handleAudioEnded = async () => {
    const playMode = audioElement.value.dataset.playMode;
    const currentIndex = parseInt(audioElement.value.dataset.currentAyahIndex || '0');

    // For Surah mode: repeat individual audio 3 times
    if (playMode !== 'juz' && playMode !== 'page') {
        if (repeatMode.value && repeatCount.value < 2) {
            repeatCount.value++;

            setTimeout(async () => {
                if (audioElement.value) {
                    audioElement.value.currentTime = 0;
                    try {
                        await audioElement.value.play();
                    } catch (error) {
                        console.error('Error repeating audio:', error);
                        isPlaying.value = false;
                    }
                }
            }, 500);
            return;
        }

        // Reset and stop for surah mode
        if (repeatMode.value) {
            repeatCount.value = 0;
        }
        isPlaying.value = false;
        return;
    }

    // Reset repeat count for current ayah (used for individual ayah repeats in collection modes)
    repeatCount.value = 0;

    // Handle continuous playback for juz mode
    if (playMode === 'juz' && currentJuz.value) {
        const nextIndex = currentIndex + 1;

        if (nextIndex < ayahs.value.length) {
            // Play next ayah in the juz
            const nextAyah = ayahs.value[nextIndex];
            const surahNumber = nextAyah.surah.number;
            const ayahNumber = nextAyah.numberInSurah;

            const surahPadded = surahNumber.toString().padStart(3, '0');
            const ayahPadded = ayahNumber.toString().padStart(3, '0');

            // Use the selected reciter's ayah server
            const currentReciter = availableReciters.value.find((r) => r.code === selectedReciter.value);
            if (!currentReciter) {
                console.error('Selected reciter not found:', selectedReciter.value);
                isPlaying.value = false;
                return;
            }
            const audioUrl = `https://${currentReciter.ayahServer}/${surahPadded}${ayahPadded}.mp3`;

            // Update dataset for next iteration
            audioElement.value.dataset.currentAyahIndex = nextIndex.toString();
            currentlyPlayingAyahIndex.value = nextIndex;

            // Small delay before playing next ayah
            setTimeout(async () => {
                audioElement.value.src = audioUrl;
                try {
                    await audioElement.value.play();
                } catch (error) {
                    console.error('Error playing next ayah:', error);
                    isPlaying.value = false;
                    currentlyPlayingAyahIndex.value = -1;
                }
            }, 1000); // 1 second delay between ayahs
        } else {
            // Reached end of juz - handle collection-level repeat
            if (repeatMode.value && collectionRepeatCount.value < 2) {
                collectionRepeatCount.value++;

                // Restart from first ayah of the juz
                setTimeout(async () => {
                    audioElement.value.dataset.currentAyahIndex = '0';
                    currentlyPlayingAyahIndex.value = 0;

                    const firstAyah = ayahs.value[0];
                    const surahNumber = firstAyah.surah.number;
                    const ayahNumber = firstAyah.numberInSurah;

                    const surahPadded = surahNumber.toString().padStart(3, '0');
                    const ayahPadded = ayahNumber.toString().padStart(3, '0');

                    // Use the selected reciter's ayah server
                    const currentReciter = availableReciters.value.find((r) => r.code === selectedReciter.value);
                    if (!currentReciter) {
                        console.error('Selected reciter not found:', selectedReciter.value);
                        isPlaying.value = false;
                        currentlyPlayingAyahIndex.value = -1;
                        return;
                    }
                    const audioUrl = `https://${currentReciter.ayahServer}/${surahPadded}${ayahPadded}.mp3`;

                    audioElement.value.src = audioUrl;
                    try {
                        await audioElement.value.play();
                    } catch (error) {
                        console.error('Error repeating juz:', error);
                        isPlaying.value = false;
                        currentlyPlayingAyahIndex.value = -1;
                    }
                }, 1500); // 1.5 second delay before repeating collection
            } else {
                // Reset and stop
                collectionRepeatCount.value = 0;
                isPlaying.value = false;
                currentlyPlayingAyahIndex.value = -1;
            }
        }
    } else if (playMode === 'page' && selectedPage.value) {
        // Handle continuous playback for page mode
        const nextIndex = currentIndex + 1;

        if (nextIndex < ayahs.value.length) {
            // Play next ayah in the page
            const nextAyah = ayahs.value[nextIndex];
            const surahNumber = nextAyah.surah.number;
            const ayahNumber = nextAyah.numberInSurah;

            const surahPadded = surahNumber.toString().padStart(3, '0');
            const ayahPadded = ayahNumber.toString().padStart(3, '0');

            // Use the selected reciter's ayah server
            const currentReciter = availableReciters.value.find((r) => r.code === selectedReciter.value);
            if (!currentReciter) {
                console.error('Selected reciter not found:', selectedReciter.value);
                isPlaying.value = false;
                return;
            }
            const audioUrl = `https://${currentReciter.ayahServer}/${surahPadded}${ayahPadded}.mp3`;

            // Update dataset for next iteration
            audioElement.value.dataset.currentAyahIndex = nextIndex.toString();
            currentlyPlayingAyahIndex.value = nextIndex;

            // Small delay before playing next ayah
            setTimeout(async () => {
                audioElement.value.src = audioUrl;
                try {
                    await audioElement.value.play();
                } catch (error) {
                    console.error('Error playing next ayah:', error);
                    isPlaying.value = false;
                    currentlyPlayingAyahIndex.value = -1;
                }
            }, 1000); // 1 second delay between ayahs
        } else {
            // Reached end of page - handle collection-level repeat
            if (repeatMode.value && collectionRepeatCount.value < 2) {
                collectionRepeatCount.value++;

                // Restart from first ayah of the page
                setTimeout(async () => {
                    audioElement.value.dataset.currentAyahIndex = '0';
                    currentlyPlayingAyahIndex.value = 0;

                    const firstAyah = ayahs.value[0];
                    const surahNumber = firstAyah.surah.number;
                    const ayahNumber = firstAyah.numberInSurah;

                    const surahPadded = surahNumber.toString().padStart(3, '0');
                    const ayahPadded = ayahNumber.toString().padStart(3, '0');

                    // Use the selected reciter's ayah server
                    const currentReciter = availableReciters.value.find((r) => r.code === selectedReciter.value);
                    if (!currentReciter) {
                        console.error('Selected reciter not found:', selectedReciter.value);
                        isPlaying.value = false;
                        currentlyPlayingAyahIndex.value = -1;
                        return;
                    }
                    const audioUrl = `https://${currentReciter.ayahServer}/${surahPadded}${ayahPadded}.mp3`;

                    audioElement.value.src = audioUrl;
                    try {
                        await audioElement.value.play();
                    } catch (error) {
                        console.error('Error repeating page:', error);
                        isPlaying.value = false;
                        currentlyPlayingAyahIndex.value = -1;
                    }
                }, 1500); // 1.5 second delay before repeating collection
            } else {
                // Reset and stop
                collectionRepeatCount.value = 0;
                isPlaying.value = false;
                currentlyPlayingAyahIndex.value = -1;
            }
        }
    } else {
        // For surah mode or end of playback
        isPlaying.value = false;
        currentlyPlayingAyahIndex.value = -1;

        // If in progressive mode, move to next ayah
        if (progressiveMode.value && currentAyahIndex.value < displayAyahs.value.length - 1) {
            setTimeout(() => {
                nextAyah();
            }, 1000);
        }
    }
};

// Copy ayah to clipboard
const copyAyah = async (ayah, event) => {
    try {
        // Get the proper translation text for this ayah (handles both search results and regular ayahs)
        const translation = ayah.translationText || ayahTranslations.value[ayah.numberInSurah];
        const surahName = currentJuz.value ? ayah.surah.englishName : currentSurah.value?.banglaName || ayah.surah.englishName;

        // Get Arabic text (handles both search results and regular ayahs)
        const arabicText = ayah.arabicText || ayah.text;

        const copyText = `${arabicText}

"${translation || 'অনুবাদ উপলব্ধ নেই'}"

সূরা ${surahName}, আয়াত ${ayah.numberInSurah}

ইকরা অনলাইন একাডেমি থেকে`;

        await navigator.clipboard.writeText(copyText);

        // Show success feedback
        const button = event.target.closest('button');
        const originalHTML = button.innerHTML;
        button.innerHTML = `
      <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
      </svg>
    `;

        setTimeout(() => {
            button.innerHTML = originalHTML;
        }, 2000);
    } catch (error) {
        console.error('Failed to copy:', error);
        // Fallback: update button text to show error
        button.innerHTML = 'কপি করতে সমস্যা হয়েছে';
        button.className = button.className.replace('bg-[#5f5fcd]', 'bg-red-500');
        setTimeout(() => {
            button.innerHTML = originalHTML;
            button.className = button.className.replace('bg-red-500', 'bg-[#5f5fcd]');
        }, 2000);
    }
};

// Pre-load next ayah's audio
const preloadNextAudio = () => {
    if (!nextAudioElement.value || !selectedReciter.value) return;

    // Determine the next ayah to pre-load
    let nextAyah = null;

    if (currentSurah.value && ayahs.value.length > 0) {
        // For surah mode, get the next ayah
        const currentIndex = parseInt(audioElement.value?.dataset.currentAyahIndex || '0');
        if (currentIndex < ayahs.value.length - 1) {
            nextAyah = ayahs.value[currentIndex + 1];
        }
    } else if ((currentJuz.value || selectedPage.value) && currentlyPlayingAyahIndex.value >= 0) {
        // For juz/page mode
        if (currentlyPlayingAyahIndex.value < ayahs.value.length - 1) {
            nextAyah = ayahs.value[currentlyPlayingAyahIndex.value + 1];
        }
    }

    // Pre-load the audio if we have a next ayah
    if (nextAyah) {
        const currentReciter = availableReciters.value.find((r) => r.code === selectedReciter.value);
        if (currentReciter && currentReciter.ayahServer) {
            const surahPadded = String(nextAyah.surah.number).padStart(3, '0');
            const ayahPadded = String(nextAyah.numberInSurah).padStart(3, '0');
            const audioUrl = `https://${currentReciter.ayahServer}/${surahPadded}${ayahPadded}.mp3`;

            // Set the source but don't play
            nextAudioElement.value.src = audioUrl;
        }
    }
};

// Clear error message
const clearError = () => {
    errorMessage.value = '';
    errorType.value = '';

    // Retry based on error type
    if (errorType.value === 'surah' && currentSurah.value) {
        selectSurah();
    } else if (errorType.value === 'juz' && currentJuz.value) {
        selectJuz();
    } else if (errorType.value === 'page' && selectedPage.value) {
        selectPage();
    } else if (errorType.value === 'topic' && selectedTopic.value) {
        selectTopic(selectedTopic.value);
    }
};

// Keyboard shortcuts handler
const handleKeyboardShortcuts = (event) => {
    // Don't trigger shortcuts when typing in input fields
    if (event.target.tagName === 'INPUT' || event.target.tagName === 'TEXTAREA') {
        return;
    }

    switch (event.key) {
        case ' ': // Spacebar
            event.preventDefault();
            toggleAudio();
            break;

        case 'ArrowRight':
            event.preventDefault();
            if (progressiveMode.value) {
                nextAyah();
            } else if (currentlyPlayingAyahIndex.value >= 0 && currentlyPlayingAyahIndex.value < ayahs.value.length - 1) {
                // Skip to next ayah in playlist
                currentlyPlayingAyahIndex.value++;
                playCurrentAyah();
            }
            break;

        case 'ArrowLeft':
            event.preventDefault();
            if (progressiveMode.value) {
                prevAyah();
            } else if (currentlyPlayingAyahIndex.value > 0) {
                // Skip to previous ayah
                currentlyPlayingAyahIndex.value--;
                playCurrentAyah();
            }
            break;

        case 'Escape':
            event.preventDefault();
            // Stop audio
            if (audioElement.value) {
                audioElement.value.pause();
                audioElement.value.currentTime = 0;
                isPlaying.value = false;
                currentlyPlayingAyahIndex.value = -1;
            }
            break;
    }
};

// Play current ayah (helper for keyboard navigation)
const playCurrentAyah = async () => {
    if (!ayahs.value.length || currentlyPlayingAyahIndex.value < 0) return;

    const ayah = ayahs.value[currentlyPlayingAyahIndex.value];
    const currentReciter = availableReciters.value.find((r) => r.code === selectedReciter.value);

    if (currentReciter && currentReciter.ayahServer && ayah) {
        const surahPadded = String(ayah.surah.number).padStart(3, '0');
        const ayahPadded = String(ayah.numberInSurah).padStart(3, '0');
        const audioUrl = `https://${currentReciter.ayahServer}/${surahPadded}${ayahPadded}.mp3`;

        audioElement.value.src = audioUrl;
        audioElement.value.dataset.currentAyahIndex = currentlyPlayingAyahIndex.value;
        audioElement.value.dataset.playMode = currentJuz.value ? 'juz' : selectedPage.value ? 'page' : 'surah';

        try {
            await audioElement.value.play();
        } catch (error) {
            console.error('Error playing audio:', error);
        }
    }
};

// Initialize
onMounted(async () => {
    // Add keyboard event listener
    window.addEventListener('keydown', handleKeyboardShortcuts);
    // Load user's preferred reciter
    const savedReciter = localStorage.getItem('iqra_selected_reciter');
    if (savedReciter && availableReciters.value.find((r) => r.code === savedReciter)) {
        selectedReciter.value = savedReciter;
    }

    // Load user's preferred translation
    const savedTranslation = localStorage.getItem('iqra_selected_translation');
    if (savedTranslation && availableTranslations.value.find((t) => t.code === savedTranslation)) {
        selectedTranslation.value = savedTranslation;
    }

    // Load user's preferred text style
    const savedTextStyle = localStorage.getItem('iqra_selected_text_style');
    if (savedTextStyle && availableTextStyles.value.find((s) => s.code === savedTextStyle)) {
        selectedTextStyle.value = savedTextStyle;
    }

    // Generate available pages
    availablePages.value = generatePageNumbers();

    // Load search history
    loadSearchHistory();

    // Load memorization settings
    loadMemorizationSettings();

    await loadSurahs();
    await selectSurah();
});

// Cleanup
onUnmounted(() => {
    // Remove keyboard event listener
    window.removeEventListener('keydown', handleKeyboardShortcuts);
});
</script>

<style scoped>
/* Premium Arabic Font Stack */
.font-arabic {
    font-family: 'Amiri Quran', 'Amiri', 'Scheherazade New', 'Noto Naskh Arabic', 'Traditional Arabic', 'Al Qalam Quran Majeed', serif;
    font-feature-settings:
        'liga' on,
        'calt' on,
        'kern' on;
    font-synthesis: none;
    text-rendering: optimizeLegibility;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    letter-spacing: 0.02em;
}

/* Premium Typography */
body {
    font-family: 'Inter', 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
    line-height: 1.6;
}

h1,
h2,
h3 {
    font-family: 'Inter', 'Segoe UI', sans-serif;
    font-weight: 700;
    letter-spacing: -0.025em;
}

/* Eye-friendly Color Palette */
:root {
    --primary-green: #2d5a27;
    --primary-purple: #5f5fcd;
    --accent-gold: #d4a574;
    --text-primary: #1a202c;
    --text-secondary: #4a5568;
    --text-muted: #718096;
    --background-soft: #f8fafc;
    --background-cream: #fefdfb;
}

/* Premium Dropdown Styling */
select {
    appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235f5fcd' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 16px;
    padding-right: 40px;
    font-family: 'Inter', sans-serif;
    font-weight: 500;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

select:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(95, 95, 205, 0.1);
}

select:focus {
    transform: translateY(0);
    box-shadow: 0 8px 25px rgba(95, 95, 205, 0.15);
    outline: none;
}

/* Islamic Pattern for Labels */
.islamic-pattern {
    background: linear-gradient(45deg, transparent 30%, rgba(95, 95, 205, 0.08) 30%, rgba(95, 95, 205, 0.08) 70%, transparent 70%);
    backdrop-filter: blur(20px);
}

/* Smooth Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes breathe {
    0%,
    100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.02);
    }
}

.dropdown-container {
    animation: fadeInUp 0.3s ease-out;
}

/* Premium Card Hover Effects */
.group:hover .font-arabic {
    animation: breathe 3s ease-in-out infinite;
}

/* Reading Mode Enhancements */
.reading-focus {
    background: linear-gradient(135deg, #fefdfb 0%, #f8fafc 100%);
    min-height: 100vh;
}

/* Smooth Scrolling */
html {
    scroll-behavior: smooth;
}

/* Selection Colors */
::selection {
    background: rgba(95, 95, 205, 0.2);
    color: var(--primary-green);
}

::-moz-selection {
    background: rgba(95, 95, 205, 0.2);
    color: var(--primary-green);
}

/* Focus Visible for Accessibility */
*:focus-visible {
    outline: 2px solid var(--primary-purple);
    outline-offset: 2px;
}

/* Mobile-First Typography */
.font-arabic {
    font-size: 1.25rem;
    line-height: 1.6;
}

@media (min-width: 640px) {
    .font-arabic {
        font-size: 1.5rem;
        line-height: 1.7;
    }
}

@media (min-width: 768px) {
    .font-arabic {
        font-size: 1.875rem;
        line-height: 1.75;
    }
}

@media (min-width: 1024px) {
    .font-arabic {
        font-size: 2.25rem;
        line-height: 1.8;
    }
}

@media (min-width: 1280px) {
    .font-arabic {
        font-size: 2.5rem;
        line-height: 1.8;
    }
}

/* Print Styles */
@media print {
    .group {
        break-inside: avoid;
        page-break-inside: avoid;
    }

    .font-arabic {
        color: black !important;
        font-size: 16pt;
        line-height: 1.8;
    }
}

/* Dark Mode Preparation */
@media (prefers-color-scheme: dark) {
    :root {
        --text-primary: #f7fafc;
        --text-secondary: #e2e8f0;
        --text-muted: #a0aec0;
        --background-soft: #1a202c;
        --background-cream: #2d3748;
    }
}

/* High Contrast Mode */
@media (prefers-contrast: high) {
    .font-arabic {
        color: #000 !important;
        font-weight: 600;
    }

    .group {
        border: 2px solid #000;
    }
}

/* Bounce Once Animation for Results */
@keyframes bounce-once {
    0%,
    100% {
        transform: translateY(0);
    }
    25% {
        transform: translateY(-10px);
    }
    50% {
        transform: translateY(-5px);
    }
    75% {
        transform: translateY(-2px);
    }
}

.animate-bounce-once {
    animation: bounce-once 0.6s ease-out;
}

/* Reduced Motion */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }

    .animate-bounce-once {
        animation: none;
    }
}
</style>
