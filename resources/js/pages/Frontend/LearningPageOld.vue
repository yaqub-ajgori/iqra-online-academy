<template>
    <div class="flex h-screen flex-col overflow-hidden bg-gray-50">
        <Head :title="`${currentLesson?.title || 'Loading...'} - ${course?.title || 'Course'}`" />

        <!-- Main Learning Interface -->
        <div class="flex h-screen flex-col overflow-hidden">
            <!-- Header Bar -->
            <header class="z-10 flex items-center justify-between border-b border-gray-200 bg-white px-6 py-3 shadow-sm">
                <div class="flex items-center space-x-4">
                    <!-- Back to Dashboard -->
                    <PrimaryButton
                        @click="goBackToDashboard"
                        variant="ghost"
                        size="sm"
                        :icon="ArrowLeftIcon"
                        class="hover:shadow-islamic-lg inline-flex transform items-center justify-center rounded-lg border border-[#5f5fcd] border-transparent bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] px-3 py-1.5 text-sm font-medium text-[#5f5fcd] text-white transition-all duration-200 ease-in-out hover:scale-105 hover:bg-[#5f5fcd] hover:from-[#1f3e1b] hover:to-[#4a4aa6] hover:text-white focus:ring-2 focus:ring-[#5f5fcd] focus:ring-offset-2 focus:outline-none active:scale-95 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        ড্যাশবোর্ডে ফিরে যান
                    </PrimaryButton>
                    <div class="h-6 w-px bg-gray-200"></div>
                    <!-- Course Title -->
                    <div>
                        <h1 class="hidden text-lg font-bold text-gray-900 sm:block sm:text-xl">{{ course?.title }}</h1>
                        <p class="text-xs text-gray-500 sm:text-sm">{{ currentLesson?.title }}</p>
                    </div>
                </div>
                <!-- Progress -->
                <div class="flex items-center space-x-4">
                    <div class="text-sm text-gray-500">{{ completedLessons }}/{{ totalLessons }} পাঠ সম্পন্ন</div>
                    <div class="h-2 w-32 rounded-full bg-gray-200">
                        <div class="h-2 rounded-full bg-[#5f5fcd] transition-all duration-300" :style="{ width: progressPercentage + '%' }"></div>
                    </div>
                    <span class="text-sm text-gray-700">{{ Math.round(progressPercentage) }}%</span>
                </div>
            </header>

            <!-- Main Content Area -->
            <div class="flex flex-1 overflow-hidden bg-gray-50">
                <!-- Collapsible Sidebar -->
                <transition name="sidebar-slide" mode="out-in">
                    <aside
                        :key="sidebarCollapsed ? 'collapsed' : 'expanded'"
                        :class="[
                            'z-20 flex flex-col border-r border-gray-200 bg-white shadow-lg transition-all duration-500',
                            sidebarCollapsed ? 'w-14 min-w-[56px]' : 'w-80 min-w-[320px]',
                        ]"
                    >
                        <!-- Sidebar Header -->
                        <div class="flex items-center justify-between border-b border-gray-100 p-4">
                            <h2 v-if="!sidebarCollapsed" class="text-lg font-bold tracking-tight text-[#5f5fcd]">কোর্স কনটেন্ট</h2>
                            <button
                                @click="toggleSidebar"
                                class="rounded-lg p-2 text-[#5f5fcd] transition-colors hover:bg-[#5f5fcd] hover:text-white"
                                :aria-label="sidebarCollapsed ? 'Expand sidebar' : 'Collapse sidebar'"
                            >
                                <PanelLeftIcon v-if="!sidebarCollapsed" class="h-5 w-5" />
                                <PanelRightIcon v-else class="h-5 w-5" />
                            </button>
                        </div>

                        <!-- Course Modules -->
                        <div v-if="!sidebarCollapsed" class="flex-1 overflow-y-auto py-2 pr-1">
                            <div
                                v-for="module in course?.modules"
                                :key="module.id"
                                class="mb-2 overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm"
                            >
                                <!-- Module Header -->
                                <button
                                    @click="toggleModule(module.id)"
                                    class="group flex w-full items-center justify-between bg-white px-4 py-3 text-left transition-colors hover:bg-[#f5f6fd]"
                                >
                                    <div>
                                        <h3 class="text-base font-medium text-gray-900 group-hover:text-[#5f5fcd]">{{ module.title }}</h3>
                                        <p class="text-xs text-gray-500">{{ module.lessons?.length || 0 }} পাঠ • {{ module.duration }}</p>
                                    </div>
                                    <ChevronDownIcon
                                        :class="[
                                            'h-5 w-5 text-gray-400 transition-transform duration-300',
                                            expandedModules.includes(module.id) ? 'rotate-180 text-[#5f5fcd]' : '',
                                        ]"
                                    />
                                </button>
                                <!-- Module Lessons -->
                                <transition name="module-scale-rotate">
                                    <div v-show="expandedModules.includes(module.id)" class="overflow-hidden bg-[#f5f6fd]">
                                        <button
                                            v-for="lesson in module.lessons"
                                            :key="lesson.id"
                                            @click="!isLessonLocked(lesson) && selectLesson(lesson)"
                                            :disabled="isLessonLocked(lesson)"
                                            :class="[
                                                'flex w-full items-center space-x-3 border-l-4 px-6 py-3 text-left transition-colors',
                                                isLessonLocked(lesson)
                                                    ? 'cursor-not-allowed border-gray-200 bg-gray-100 text-gray-400 opacity-60'
                                                    : currentLesson?.id === lesson.id
                                                      ? 'border-[#5f5fcd] bg-[#e7eafd] font-semibold text-[#5f5fcd] hover:bg-[#e7eafd]'
                                                      : lesson.completed
                                                        ? 'border-[#2d5a27] bg-white text-[#2d5a27] hover:bg-[#e7eafd]'
                                                        : 'border-transparent bg-white text-gray-700 hover:bg-[#e7eafd]',
                                            ]"
                                            :title="isLessonLocked(lesson) ? 'Complete previous lessons to unlock.' : lesson.title"
                                        >
                                            <div class="flex-shrink-0">
                                                <div
                                                    v-if="lesson.completed"
                                                    class="flex h-6 w-6 items-center justify-center rounded-full bg-[#2d5a27]"
                                                >
                                                    <CheckIcon class="h-4 w-4 text-white" />
                                                </div>
                                                <div
                                                    v-else-if="currentLesson?.id === lesson.id"
                                                    class="flex h-6 w-6 items-center justify-center rounded-full bg-[#5f5fcd]"
                                                >
                                                    <PlayIcon class="h-3 w-3 text-white" />
                                                </div>
                                                <div
                                                    v-else-if="isLessonLocked(lesson)"
                                                    class="flex h-6 w-6 items-center justify-center rounded-full border-2 border-gray-300"
                                                >
                                                    <svg
                                                        class="h-3 w-3 text-gray-400"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M16 12V8a4 4 0 10-8 0v4M5 12h14a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2z"
                                                        />
                                                    </svg>
                                                </div>
                                                <div v-else class="h-6 w-6 rounded-full border-2 border-gray-300"></div>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="truncate">{{ lesson.title }}</p>
                                                <div class="flex items-center space-x-2 text-xs text-gray-400">
                                                    <component :is="getLessonTypeIcon(lesson.type)" class="h-4 w-4" />
                                                    <span>{{ lesson.duration }}</span>
                                                </div>
                                            </div>
                                        </button>
                                    </div>
                                </transition>
                            </div>
                        </div>

                        <!-- Collapsed Sidebar Lessons -->
                        <div v-else class="flex flex-1 flex-col items-center gap-2 overflow-y-auto py-2">
                            <div v-for="lesson in allLessons" :key="lesson.id" class="">
                                <button
                                    @click="selectLesson(lesson)"
                                    :class="[
                                        'flex h-10 w-10 items-center justify-center rounded-full transition-colors',
                                        currentLesson?.id === lesson.id
                                            ? 'bg-[#5f5fcd] text-white shadow-lg'
                                            : lesson.completed
                                              ? 'bg-[#2d5a27] text-white'
                                              : 'bg-gray-200 text-gray-400 hover:bg-gray-300',
                                    ]"
                                    :title="lesson.title"
                                >
                                    <CheckIcon v-if="lesson.completed" class="h-4 w-4" />
                                    <PlayIcon v-else-if="currentLesson?.id === lesson.id" class="h-3 w-3" />
                                    <span v-else class="text-xs">{{ lesson.order.toString() }}</span>
                                </button>
                            </div>
                        </div>
                    </aside>
                </transition>

                <!-- Main Learning Content -->
                <main
                    class="flex flex-1 flex-col overflow-hidden rounded-tl-3xl bg-white shadow-xl transition-all duration-500"
                    :class="sidebarCollapsed ? 'rounded-none' : 'rounded-tl-3xl'"
                >
                    <!-- Video/Content Area: Add bottom padding for controls bar -->
                    <div class="flex flex-1 items-center justify-center pb-[80px] transition-all duration-500" :class="'px-0 sm:px-0'">
                        <!-- Loading Content -->
                        <div v-if="contentLoading" class="text-center text-lg font-medium text-gray-400">Loading...</div>
                        <!-- Responsive Video Player -->
                        <div v-else-if="currentLesson?.type === 'video' && currentLesson.video_url" class="h-full w-full">
                            <div class="relative w-full" style="padding-top: 56.25%">
                                <iframe
                                    :src="getEmbedUrl(currentLesson.video_url)"
                                    class="absolute top-0 left-0 h-full w-full rounded-xl border border-gray-200 shadow-lg"
                                    frameborder="0"
                                    sandbox="allow-scripts allow-same-origin allow-presentation allow-fullscreen"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; fullscreen"
                                    allowfullscreen
                                    @load="handleVideoLoad"
                                    title="Lesson Video"
                                    loading="lazy"
                                ></iframe>
                            </div>
                            <div class="mt-6 text-center">
                                <h2 class="mb-2 text-base leading-tight font-bold text-gray-900 sm:text-2xl">{{ currentLesson.title }}</h2>
                                <p class="text-base text-gray-500">ভিডিও পাঠ</p>
                            </div>
                        </div>
                        <!-- Text Content -->
                        <div v-else-if="currentLesson?.type === 'text'" class="h-full w-full overflow-y-auto bg-white p-4 sm:p-8">
                            <h1 class="mb-6 text-xl leading-tight font-extrabold text-gray-900 sm:text-3xl">{{ currentLesson.title }}</h1>
                            <div class="prose prose-lg max-w-none leading-relaxed text-gray-800" v-html="sanitizeHtml(currentLesson.content)"></div>
                        </div>
                        <!-- Quiz Content -->
                        <div v-else-if="currentLesson?.type === 'quiz'" class="h-full w-full overflow-y-auto bg-white p-4 sm:p-8">
                            <!-- Quiz Not Started -->
                            <div v-if="!quizStarted" class="space-y-4">
                                <h1 class="mb-6 text-lg leading-tight font-bold text-gray-900 sm:text-2xl">{{ currentLesson.title }}</h1>
                                
                                <div v-if="currentLesson?.quiz" class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ currentLesson.quiz.title }}</h3>
                                    <p v-if="currentLesson.quiz.description" class="text-gray-600 mb-4">{{ currentLesson.quiz.description }}</p>
                                    
                                    <div class="flex flex-wrap gap-4 text-sm text-gray-500 mb-4">
                                        <span class="flex items-center">
                                            <HelpCircleIcon class="h-4 w-4 mr-1" />
                                            {{ currentLesson.quiz.total_questions }} প্রশ্ন
                                        </span>
                                        <span v-if="currentLesson.quiz.time_limit_minutes" class="flex items-center">
                                            <ClockIcon class="h-4 w-4 mr-1" />
                                            {{ currentLesson.quiz.time_limit_minutes }} মিনিট
                                        </span>
                                        <span class="flex items-center">
                                            <CheckCircleIcon class="h-4 w-4 mr-1" />
                                            পাশ: {{ currentLesson.quiz.passing_score }}%
                                        </span>
                                    </div>
                                    
                                    <button 
                                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] text-white font-medium rounded-lg hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-[#5f5fcd] focus:ring-offset-2 transform hover:scale-105 transition-all"
                                        @click="startQuiz(currentLesson.quiz)">
                                        <PlayIcon class="w-4 h-4 mr-2" />
                                        কুইজ শুরু করুন
                                    </button>
                                </div>
                            </div>

                            <!-- Quiz Player -->
                            <div v-else-if="quizStarted && !quizCompleted" class="space-y-6">
                                <!-- Quiz Header -->
                                <div class="rounded-xl bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] p-6 text-white shadow-lg">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h2 class="text-xl font-bold mb-2">{{ currentQuiz.title }}</h2>
                                            <div class="flex items-center space-x-4 text-sm opacity-90">
                                                <span class="flex items-center">
                                                    <HelpCircleIcon class="h-4 w-4 mr-1" />
                                                    প্রশ্ন {{ currentQuestionIndex + 1 }} / {{ currentQuiz.questions.length }}
                                                </span>
                                                <span v-if="currentQuiz.time_limit_minutes" class="flex items-center">
                                                    <ClockIcon class="h-4 w-4 mr-1" />
                                                    {{ currentQuiz.time_limit_minutes }} মিনিট
                                                </span>
                                            </div>
                                        </div>
                                        <!-- Progress Circle -->
                                        <div class="flex items-center justify-center h-16 w-16 rounded-full bg-white bg-opacity-20 text-lg font-bold">
                                            {{ Math.round(((currentQuestionIndex + 1) / currentQuiz.questions.length) * 100) }}%
                                        </div>
                                    </div>
                                    
                                    <!-- Progress Bar -->
                                    <div class="mt-4">
                                        <div class="h-2 bg-white bg-opacity-20 rounded-full">
                                            <div 
                                                class="h-2 bg-white rounded-full transition-all duration-300" 
                                                :style="{ width: ((currentQuestionIndex + 1) / currentQuiz.questions.length) * 100 + '%' }">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Question Card -->
                                <div v-if="currentQuestion" class="bg-white rounded-xl border border-gray-200 shadow-lg overflow-hidden">
                                    <!-- Question Header -->
                                    <div class="bg-[#f5f6fd] px-6 py-4 border-b border-gray-200">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <h3 class="text-lg font-semibold text-gray-900 leading-relaxed">
                                                    {{ currentQuestionIndex + 1 }}. {{ currentQuestion.question }}
                                                </h3>
                                            </div>
                                            
                                            <!-- Compact Navigation Buttons -->
                                            <div class="flex items-center space-x-2 ml-4">
                                                <!-- Previous Button -->
                                                <button
                                                    @click="previousQuestion"
                                                    :disabled="currentQuestionIndex === 0"
                                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md transition-all duration-200"
                                                    :class="currentQuestionIndex === 0 
                                                        ? 'bg-gray-100 text-gray-400 cursor-not-allowed border border-gray-200' 
                                                        : 'bg-white border border-[#5f5fcd] text-[#5f5fcd] hover:bg-[#5f5fcd] hover:text-white'">
                                                    <ChevronLeftIcon class="h-3 w-3 mr-1" />
                                                    পূর্ববর্তী
                                                </button>
                                                
                                                <!-- Progress Indicator -->
                                                <div class="text-xs font-medium text-[#5f5fcd]">
                                                    {{ currentQuestionIndex + 1 }}/{{ currentQuiz.questions.length }}
                                                </div>
                                                
                                                <!-- Next/Submit Button -->
                                                <button
                                                    v-if="currentQuestionIndex < currentQuiz.questions.length - 1"
                                                    @click="nextQuestion"
                                                    class="inline-flex items-center px-3 py-1.5 bg-[#5f5fcd] text-white text-xs font-medium rounded-md hover:bg-[#4a4a9f] transition-all duration-200">
                                                    পরবর্তী
                                                    <ChevronRightIcon class="h-3 w-3 ml-1" />
                                                </button>
                                                <button
                                                    v-else
                                                    @click="submitQuiz"
                                                    class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded-md hover:bg-green-700 transition-all duration-200">
                                                    <CheckIcon class="h-3 w-3 mr-1" />
                                                    জমা দিন
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Answer Options -->
                                    <div class="p-6">
                                        <!-- Multiple Choice -->
                                        <div v-if="currentQuestion.type === 'multiple_choice'" class="space-y-3">
                                            <label 
                                                v-for="(option, index) in currentQuestion.options"
                                                :key="index"
                                                class="flex items-start p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:shadow-md group"
                                                :class="quizAnswers[currentQuestion.id] == index 
                                                    ? 'border-[#5f5fcd] bg-[#e7eafd] shadow-md' 
                                                    : 'border-gray-200 hover:border-[#5f5fcd] hover:bg-[#f8f9ff]'">
                                                <div class="flex items-center h-5">
                                                    <input
                                                        type="radio"
                                                        :value="index"
                                                        v-model="quizAnswers[currentQuestion.id]"
                                                        class="h-4 w-4 text-[#5f5fcd] border-gray-300 focus:ring-[#5f5fcd]"
                                                    />
                                                </div>
                                                <div class="ml-3 text-sm">
                                                    <span class="font-medium text-gray-700 group-hover:text-[#5f5fcd]">
                                                        {{ String.fromCharCode(65 + index) }}.
                                                    </span>
                                                    <span class="ml-2 text-gray-900">{{ option }}</span>
                                                </div>
                                            </label>
                                        </div>

                                        <!-- True/False -->
                                        <div v-else-if="currentQuestion.type === 'true_false'" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <label class="flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:shadow-md group"
                                                :class="quizAnswers[currentQuestion.id] === 'সত্য' 
                                                    ? 'border-green-500 bg-green-50 shadow-md' 
                                                    : 'border-gray-200 hover:border-green-400 hover:bg-green-50'">
                                                <input type="radio" value="সত্য" v-model="quizAnswers[currentQuestion.id]" 
                                                    class="h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500" />
                                                <div class="ml-3">
                                                    <div class="text-2xl">✓</div>
                                                    <div class="text-sm font-medium text-gray-700">সত্য</div>
                                                </div>
                                            </label>
                                            <label class="flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:shadow-md group"
                                                :class="quizAnswers[currentQuestion.id] === 'মিথ্যা' 
                                                    ? 'border-red-500 bg-red-50 shadow-md' 
                                                    : 'border-gray-200 hover:border-red-400 hover:bg-red-50'">
                                                <input type="radio" value="মিথ্যা" v-model="quizAnswers[currentQuestion.id]" 
                                                    class="h-4 w-4 text-red-600 border-gray-300 focus:ring-red-500" />
                                                <div class="ml-3">
                                                    <div class="text-2xl">✗</div>
                                                    <div class="text-sm font-medium text-gray-700">মিথ্যা</div>
                                                </div>
                                            </label>
                                        </div>

                                        <!-- Short Answer -->
                                        <div v-else-if="currentQuestion.type === 'short_answer'">
                                            <input
                                                v-model="quizAnswers[currentQuestion.id]"
                                                type="text"
                                                class="w-full p-4 border-2 border-gray-200 rounded-xl focus:border-[#5f5fcd] focus:ring-2 focus:ring-[#5f5fcd] focus:ring-opacity-20 transition-colors"
                                                placeholder="আপনার উত্তর লিখুন..."
                                            />
                                        </div>

                                        <!-- Essay -->
                                        <div v-else-if="currentQuestion.type === 'essay'">
                                            <textarea
                                                v-model="quizAnswers[currentQuestion.id]"
                                                rows="6"
                                                class="w-full p-4 border-2 border-gray-200 rounded-xl focus:border-[#5f5fcd] focus:ring-2 focus:ring-[#5f5fcd] focus:ring-opacity-20 transition-colors resize-vertical"
                                                placeholder="আপনার বিস্তারিত উত্তর লিখুন..."
                                            ></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quiz Results -->
                            <div v-else-if="quizCompleted && quizResults" class="space-y-6">
                                <!-- Results Header -->
                                <div class="text-center">
                                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full mb-4"
                                        :class="quizResults.is_passed ? 'bg-green-100' : 'bg-red-100'">
                                        <CheckCircleIcon v-if="quizResults.is_passed" class="w-10 h-10 text-green-600" />
                                        <AlertTriangleIcon v-else class="w-10 h-10 text-red-600" />
                                    </div>
                                    <h2 class="text-2xl font-bold text-gray-900 mb-2">কুইজ সম্পন্ন!</h2>
                                </div>

                                <!-- Score Card -->
                                <div class="bg-white rounded-xl border border-gray-200 shadow-lg overflow-hidden">
                                    <div class="text-center py-8 px-6"
                                        :class="quizResults.is_passed ? 'bg-gradient-to-br from-green-50 to-green-100' : 'bg-gradient-to-br from-red-50 to-red-100'">
                                        <div class="text-5xl font-bold mb-3"
                                            :class="quizResults.is_passed ? 'text-green-600' : 'text-red-600'">
                                            {{ Math.round(quizResults.score) }}%
                                        </div>
                                        <p class="text-lg text-gray-700 mb-4">
                                            {{ quizResults.correct_answers }} / {{ quizResults.total_questions }} সঠিক উত্তর
                                        </p>
                                        
                                        <!-- Status Badge -->
                                        <div class="inline-flex items-center px-6 py-3 rounded-full text-lg font-semibold"
                                            :class="quizResults.is_passed 
                                                ? 'bg-green-600 text-white' 
                                                : 'bg-red-600 text-white'">
                                            <CheckIcon v-if="quizResults.is_passed" class="w-5 h-5 mr-2" />
                                            <AlertTriangleIcon v-else class="w-5 h-5 mr-2" />
                                            <span v-if="quizResults.is_passed">
                                                উত্তীর্ণ ({{ quizResults.correct_answers }}/{{ quizResults.total_questions }} সঠিক)
                                            </span>
                                            <span v-else>
                                                {{ quizResults.total_questions - quizResults.correct_answers }} ভুল উত্তর | অনুত্তীর্ণ
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Performance Breakdown -->
                                    <div class="px-6 py-4 bg-gray-50 border-t">
                                        <div class="grid grid-cols-3 gap-4 text-center">
                                            <div>
                                                <div class="text-2xl font-bold text-green-600">{{ quizResults.correct_answers }}</div>
                                                <div class="text-sm text-gray-600">সঠিক</div>
                                            </div>
                                            <div>
                                                <div class="text-2xl font-bold text-red-600">{{ quizResults.total_questions - quizResults.correct_answers }}</div>
                                                <div class="text-sm text-gray-600">ভুল</div>
                                            </div>
                                            <div>
                                                <div class="text-2xl font-bold text-[#5f5fcd]">{{ quizResults.total_questions }}</div>
                                                <div class="text-sm text-gray-600">মোট</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <button @click="resetQuiz" 
                                        class="flex-1 inline-flex items-center justify-center px-6 py-3 border-2 border-gray-200 rounded-xl font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#5f5fcd] focus:ring-offset-2 transition-all">
                                        <RotateCcwIcon class="w-4 h-4 mr-2" />
                                        পুনরায় চেষ্টা করুন
                                    </button>
                                    <button @click="goToNextLesson" 
                                        v-if="hasNextLesson"
                                        class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] text-white font-medium rounded-xl hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-[#5f5fcd] focus:ring-offset-2 transform hover:scale-105 transition-all">
                                        পরবর্তী পাঠ
                                        <ChevronRightIcon class="w-4 h-4 ml-2" />
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Fallback: Course Quizzes (Legacy) -->
                            <div v-else-if="course?.quizzes && course.quizzes.length > 0" class="space-y-4">
                                <div v-for="quiz in course.quizzes" :key="quiz.id" 
                                     class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ quiz.title }}</h3>
                                            <p v-if="quiz.description" class="text-gray-600 mb-4">{{ quiz.description }}</p>
                                            
                                            <div class="flex flex-wrap gap-4 text-sm text-gray-500 mb-4">
                                                <span class="flex items-center">
                                                    <HelpCircleIcon class="h-4 w-4 mr-1" />
                                                    {{ quiz.total_questions }} প্রশ্ন
                                                </span>
                                                <span v-if="quiz.time_limit_minutes" class="flex items-center">
                                                    <ClockIcon class="h-4 w-4 mr-1" />
                                                    {{ quiz.time_limit_minutes }} মিনিট
                                                </span>
                                                <span class="flex items-center">
                                                    <CheckCircleIcon class="h-4 w-4 mr-1" />
                                                    পাশ: {{ quiz.passing_score }}%
                                                </span>
                                            </div>
                                            
                                            <!-- Quiz Status -->
                                            <div v-if="quiz.attempts && quiz.attempts.length > 0" class="mb-4">
                                                <div class="text-sm text-gray-600 mb-2">আপনার সর্বোচ্চ স্কোর:</div>
                                                <div class="flex items-center space-x-2">
                                                    <div class="text-lg font-bold" 
                                                         :class="getBestScore(quiz.attempts) >= quiz.passing_score ? 'text-green-600' : 'text-red-600'">
                                                        {{ getBestScore(quiz.attempts) }}%
                                                    </div>
                                                    <div v-if="getBestScore(quiz.attempts) >= quiz.passing_score" 
                                                         class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                                        পাশ
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="ml-4">
                                            <PrimaryButton
                                                @click="startQuiz(quiz.id)"
                                                variant="primary"
                                                size="sm"
                                                class="border-[#5f5fcd] bg-[#5f5fcd] text-white hover:bg-[#4a4ab8]"
                                            >
                                                {{ quiz.attempts && quiz.attempts.length > 0 ? 'পুনরায় চেষ্টা' : 'কুইজ শুরু করুন' }}
                                            </PrimaryButton>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- No Quizzes Available -->
                            <div v-else class="rounded-lg bg-[#f5f6fd] p-6 text-center">
                                <HelpCircleIcon class="h-12 w-12 text-gray-400 mx-auto mb-4" />
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">কোনো কুইজ নেই</h3>
                                <p class="text-gray-600">এই কোর্সের জন্য এখনো কোনো কুইজ যোগ করা হয়নি।</p>
                            </div>
                        </div>
                        <!-- PDF Content -->
                        <div
                            v-else-if="currentLesson?.type === 'pdf' && currentLesson.primary_file_url"
                            class="h-full w-full rounded-none border-none shadow-none"
                            style="height: 70vh"
                        >
                            <iframe :src="currentLesson.primary_file_url" class="h-full w-full rounded-xl" frameborder="0" title="Lesson PDF"></iframe>
                        </div>
                        <!-- Mixed Content -->
                        <div v-else-if="currentLesson?.type === 'mixed' || !currentLesson?.type" class="h-full w-full overflow-y-auto bg-white">
                            <!-- Video Section (if available) -->
                            <div v-if="currentLesson?.video_url" class="relative mb-6">
                                <div class="aspect-video w-full bg-black">
                                    <iframe
                                        :src="getEmbedUrl(currentLesson?.video_url || '')"
                                        class="h-full w-full"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen
                                        title="Lesson Video"
                                    ></iframe>
                                </div>
                            </div>
                            
                            <!-- Content Section -->
                            <div class="px-4 sm:px-8">
                                <h1 class="mb-6 text-xl leading-tight font-extrabold text-gray-900 sm:text-3xl">
                                    {{ currentLesson?.title || 'Untitled Lesson' }}
                                </h1>
                                
                                <!-- Text Content -->
                                <div v-if="currentLesson?.content" class="prose prose-lg max-w-none leading-relaxed text-gray-800 mb-8" v-html="sanitizeHtml(currentLesson?.content || '')"></div>
                                
                                <!-- Primary File Download -->
                                <div v-if="currentLesson?.primary_file_url" class="mb-6">
                                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                        <h3 class="mb-3 font-semibold text-gray-900">প্রধান ফাইল</h3>
                                        <a :href="currentLesson?.primary_file_url" download class="inline-flex items-center space-x-2 text-[#5f5fcd] hover:text-[#4a4ab8]">
                                            <DownloadIcon class="h-4 w-4" />
                                            <span>{{ getFileTypeDisplay(currentLesson?.primary_file_type) }} ডাউনলোড করুন</span>
                                        </a>
                                    </div>
                                </div>
                                
                                <!-- Additional Attachments -->
                                <div v-if="currentLesson?.attachments && currentLesson?.attachments.length > 0" class="mb-6">
                                    <h3 class="mb-3 font-semibold text-gray-900">অতিরিক্ত সংযুক্তি</h3>
                                    <div class="space-y-2">
                                        <div v-for="attachment in currentLesson?.attachments" :key="attachment.name" class="rounded border border-gray-200 bg-gray-50 p-3">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ attachment.name }}</p>
                                                    <p v-if="attachment.formatted_size" class="text-sm text-gray-500">{{ attachment.formatted_size }}</p>
                                                </div>
                                                <a :href="attachment.url" download class="text-[#5f5fcd] hover:text-[#4a4ab8]">
                                                    <DownloadIcon class="h-4 w-4" />
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- External Resources -->
                                <div v-if="currentLesson?.resources && currentLesson?.resources.length > 0" class="mb-6">
                                    <h3 class="mb-3 font-semibold text-gray-900">বাহ্যিক রিসোর্স</h3>
                                    <div class="space-y-2">
                                        <a v-for="resource in currentLesson?.resources" :key="resource.url" :href="resource.url" target="_blank" 
                                           class="block rounded border border-gray-200 bg-gray-50 p-3 hover:bg-gray-100">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ resource.title }}</p>
                                                    <p class="text-sm text-gray-500">{{ resource.type }}</p>
                                                </div>
                                                <ExternalLinkIcon class="h-4 w-4 text-[#5f5fcd]" />
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Audio Content -->
                        <div
                            v-else-if="currentLesson?.type === 'audio' && currentLesson?.audio_url"
                            class="h-full w-full overflow-y-auto bg-white p-4 sm:p-8"
                        >
                            <h1 class="mb-6 text-lg leading-tight font-bold text-gray-900 sm:text-2xl">{{ currentLesson?.title }}</h1>
                            <audio controls class="mb-4 w-full">
                                <source :src="currentLesson?.audio_url" type="audio/mpeg" />
                                আপনার ব্রাউজার অডিও সাপোর্ট করে না।
                            </audio>
                            <div class="prose prose-base text-gray-700" v-html="sanitizeHtml(currentLesson?.content || '')"></div>
                        </div>
                    </div>
                    <!-- Lesson Controls: Always fixed at bottom -->
                    <div
                        class="sticky right-0 bottom-0 left-0 z-30 flex w-full flex-col items-center justify-between border-t border-gray-200 bg-[#f5f6fd] px-4 py-3 shadow-lg transition-all duration-500 sm:flex-row sm:px-6 sm:py-4"
                        style="min-height: 64px"
                    >
                        <div class="mb-2 flex items-center space-x-2 sm:mb-0 sm:space-x-4">
                            <!-- Previous Lesson -->
                            <PrimaryButton
                                @click="goToPreviousLesson"
                                :disabled="!hasPreviousLesson"
                                variant="ghost"
                                size="sm"
                                :icon="ChevronLeftIcon"
                                class="hover:shadow-islamic-lg inline-flex transform items-center justify-center rounded-lg border border-[#5f5fcd] border-transparent bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] px-3 py-1.5 text-sm font-medium text-[#5f5fcd] text-white transition-all duration-200 ease-in-out hover:scale-105 hover:bg-[#5f5fcd] hover:from-[#1f3e1b] hover:to-[#4a4aa6] hover:text-white focus:ring-2 focus:ring-[#5f5fcd] focus:ring-offset-2 focus:outline-none active:scale-95 disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                আগের পাঠ
                            </PrimaryButton>
                            <!-- Mark as Complete -->
                            <PrimaryButton
                                v-if="!currentLesson?.completed"
                                @click="markLessonComplete"
                                :loading="markingComplete"
                                variant="secondary"
                                size="sm"
                                :icon="CheckCircleIcon"
                                class="hover:shadow-islamic-lg inline-flex transform items-center justify-center rounded-lg border border-[#5f5fcd] border-transparent bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] px-3 py-1.5 text-sm font-medium text-[#5f5fcd] text-white transition-all duration-200 ease-in-out hover:scale-105 hover:bg-[#5f5fcd] hover:from-[#1f3e1b] hover:to-[#4a4aa6] hover:text-white focus:ring-2 focus:ring-[#5f5fcd] focus:ring-offset-2 focus:outline-none active:scale-95 disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                সম্পূর্ণ হিসেবে চিহ্নিত করুন
                            </PrimaryButton>
                        </div>
                        <div class="flex items-center space-x-4">
                            <!-- Lesson Info -->
                            <div class="text-sm text-gray-500">পাঠ {{ currentLessonIndex + 1 }} / {{ totalLessons }}</div>
                            <!-- Next Lesson -->
                            <PrimaryButton
                                @click="goToNextLesson"
                                :disabled="!hasNextLesson"
                                variant="primary"
                                size="sm"
                                :icon="ChevronRightIcon"
                                icon-position="right"
                                class="border-[#5f5fcd] bg-[#5f5fcd] text-white hover:bg-[#4a4ab8] disabled:opacity-50"
                            >
                                পরবর্তী পাঠ
                            </PrimaryButton>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { useIntervalFn } from '@vueuse/core';
import {
    ArrowLeftIcon,
    CheckCircleIcon,
    CheckIcon,
    ChevronDownIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
    ClockIcon,
    DownloadIcon,
    AlertTriangleIcon,
    ExternalLinkIcon,
    FileIcon,
    FileTextIcon,
    HeadphonesIcon,
    HelpCircleIcon,
    PanelLeftIcon,
    PanelRightIcon,
    PlayIcon,
    RotateCcwIcon,
    VideoIcon,
} from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';

// Type definitions
interface Lesson {
    id: number;
    order: number;
    title: string;
    duration: string;
    type: 'video' | 'text' | 'quiz' | 'pdf' | 'audio' | 'mixed';
    video_url?: string;
    content?: string;
    primary_file_url?: string;
    primary_file_type?: string;
    attachments?: Array<{
        name: string;
        url: string;
        formatted_size?: string;
    }>;
    resources?: Array<{
        title: string;
        url: string;
        type: string;
    }>;
    pdf_url?: string; // Keep for backward compatibility
    audio_url?: string;
    completed: boolean;
    module_id: number;
}

interface Module {
    id: number;
    title: string;
    duration: string;
    lessons: Lesson[];
}

interface Quiz {
    id: number;
    title: string;
    description?: string;
    type: string;
    time_limit_minutes?: number;
    passing_score: number;
    total_questions: number;
    attempts?: QuizAttempt[];
}

interface QuizAttempt {
    id: number;
    score: number;
    is_passed: boolean;
    completed_at: string;
}

interface Course {
    id: number;
    title: string;
    slug: string;
    modules: Module[];
    total_lessons: number;
    completed_lessons: number;
    quizzes?: Quiz[];
}

// Get data from props
const page = usePage();
const courseSlug = computed(() => (page.props.course_slug as string) || '');
const course = ref<Course | null>(null);
const contentLoading = ref(false);
const markingComplete = ref(false);

// State
const sidebarCollapsed = ref(false);
const expandedModules = ref<number[]>([]);
const currentLesson = ref<Lesson | null>(null);
const completedLessons = ref(0);

// Quiz state
const quizStarted = ref(false);
const quizCompleted = ref(false);
const currentQuiz = ref<any>(null);
const currentQuestionIndex = ref(0);
const quizAnswers = ref<Record<number, any>>({});
const quizResults = ref<any>(null);
const quizStartTime = ref<Date | null>(null);

// Computed properties
const allLessons = computed(() => {
    if (!course.value?.modules) return [];
    return course.value.modules.flatMap((module) => module.lessons);
});

const totalLessons = computed(() => allLessons.value.length);

const progressPercentage = computed(() => {
    return totalLessons.value > 0 ? (completedLessons.value / totalLessons.value) * 100 : 0;
});

const currentLessonIndex = computed(() => {
    if (!currentLesson.value) return -1;
    return allLessons.value.findIndex((lesson) => lesson.id === currentLesson.value!.id);
});

const hasPreviousLesson = computed(() => currentLessonIndex.value > 0);

const hasNextLesson = computed(() => currentLessonIndex.value < totalLessons.value - 1);

// Helper: Get a flat list of all lessons in order
const flatLessons = computed(() => {
    if (!course.value?.modules) return [];
    return course.value.modules.flatMap((module) => module.lessons);
});

// Helper: Get the index of a lesson in the flat list
function getLessonIndex(lessonId: number) {
    return flatLessons.value.findIndex((l) => l.id === lessonId);
}

// Helper: Is a lesson locked?
function isLessonLocked(lesson: Lesson) {
    const idx = getLessonIndex(lesson.id);
    if (idx === 0) return false; // First lesson always unlocked
    // All previous lessons must be completed
    return !flatLessons.value.slice(0, idx).every((l) => l.completed);
}

// Methods
const loadCourseData = async () => {
    try {
        await router.get(
            route('frontend.learning.show', courseSlug.value),
            {},
            {
                preserveState: true,
                preserveScroll: true,
                only: ['course', 'enrollment'],
            },
        );

        // Get updated data from page props
        const pageData = usePage();
        course.value = pageData.props.course as Course;
        completedLessons.value = (pageData.props.enrollment as any)?.completed_lessons || 0;

        // Set first lesson as current if none selected
        if (!currentLesson.value && allLessons.value.length > 0) {
            currentLesson.value = allLessons.value[0];
            // Expand the module containing this lesson
            const module = course.value?.modules.find((m) => m.lessons.some((l) => l.id === currentLesson.value!.id));
            if (module) {
                expandedModules.value = [module.id];
            }
        }
    } catch (err) {
        // Handle course loading error silently
    }
};

const toggleSidebar = () => {
    sidebarCollapsed.value = !sidebarCollapsed.value;
};

const toggleModule = (moduleId: number) => {
    if (expandedModules.value.includes(moduleId)) {
        expandedModules.value = []; // Close all if clicking the open one
    } else {
        expandedModules.value = [moduleId]; // Only one open at a time
    }
};

const selectLesson = async (lesson: Lesson) => {
    contentLoading.value = true;
    currentLesson.value = lesson;

    // Expand the module containing this lesson
    const module = course.value?.modules.find((m) => m.lessons.some((l) => l.id === lesson.id));
    if (module && !expandedModules.value.includes(module.id)) {
        expandedModules.value.push(module.id);
    }

    // Simulate content loading
    setTimeout(() => {
        contentLoading.value = false;
    }, 500);
};

const goToPreviousLesson = () => {
    if (hasPreviousLesson.value) {
        const previousLesson = allLessons.value[currentLessonIndex.value - 1];
        selectLesson(previousLesson);
    }
};

const goToNextLesson = () => {
    if (hasNextLesson.value) {
        const nextLesson = allLessons.value[currentLessonIndex.value + 1];
        selectLesson(nextLesson);
    }
};

const markLessonComplete = async () => {
    if (!currentLesson.value) return;

    markingComplete.value = true;

    try {
        await router.post(
            route('frontend.learning.complete-lesson'),
            {
                lesson_id: currentLesson.value.id,
                course_id: course.value?.id,
            },
            {
                preserveState: true,
                preserveScroll: true,
                only: ['enrollment'],
            },
        );

        currentLesson.value.completed = true;
        completedLessons.value++;

        // Auto-advance to next lesson after a delay
        if (hasNextLesson.value) {
            setTimeout(() => {
                goToNextLesson();
            }, 1000);
        }
    } catch (err) {
        // Handle lesson completion error silently
    } finally {
        markingComplete.value = false;
    }
};

const goBackToDashboard = () => {
    router.visit(route('frontend.student.dashboard'));
};

const startQuiz = async (quiz: any) => {
    try {
        // Handle both quiz object and quiz ID
        let quizData;
        
        if (typeof quiz === 'object' && quiz) {
            // Quiz object passed directly (from current lesson)
            quizData = quiz;
        } else {
            // Quiz ID passed - find it in course quizzes
            const quizId = quiz;
            if (course.value?.quizzes) {
                quizData = course.value.quizzes.find(q => q.id === quizId);
            }
        }
        
        if (!quizData) {
            alert('কুইজ পাওয়া যায়নি। দয়া করে পুনরায় চেষ্টা করুন।');
            return;
        }
        
        if (!quizData.questions || quizData.questions.length === 0) {
            alert('এই কুইজে কোনো প্রশ্ন নেই।');
            return;
        }
        
        
        // Set up quiz state
        currentQuiz.value = quizData;
        quizStarted.value = true;
        quizCompleted.value = false;
        currentQuestionIndex.value = 0;
        quizAnswers.value = {};
        quizStartTime.value = new Date();
        
    } catch (error) {
        alert('কুইজ শুরু করতে সমস্যা হয়েছে। দয়া করে পুনরায় চেষ্টা করুন।');
    }
};

const currentQuestion = computed(() => {
    if (!currentQuiz.value?.questions) return null;
    return currentQuiz.value.questions[currentQuestionIndex.value];
});

const nextQuestion = () => {
    if (currentQuestionIndex.value < currentQuiz.value.questions.length - 1) {
        currentQuestionIndex.value++;
    }
};

const previousQuestion = () => {
    if (currentQuestionIndex.value > 0) {
        currentQuestionIndex.value--;
    }
};

const submitQuiz = () => {
    console.log('Submit button clicked!');
    
    if (!currentQuiz.value) {
        alert('কুইজ লোড হয়নি। দয়া করে পুনরায় চেষ্টা করুন।');
        return;
    }
    
    console.log('Submitting quiz with data:', {
        quiz_id: currentQuiz.value.id,
        answers: quizAnswers.value,
        started_at: quizStartTime.value?.toISOString(),
    });
    
    router.post(route('quiz.submit', currentQuiz.value.id), {
        answers: quizAnswers.value,
        started_at: quizStartTime.value?.toISOString(),
    });
};

const resetQuiz = () => {
    quizStarted.value = false;
    quizCompleted.value = false;
    currentQuiz.value = null;
    currentQuestionIndex.value = 0;
    quizAnswers.value = {};
    quizResults.value = null;
    quizStartTime.value = null;
};

const getLessonTypeIcon = (type: string) => {
    switch (type) {
        case 'video':
            return VideoIcon;
        case 'text':
            return FileTextIcon;
        case 'quiz':
            return HelpCircleIcon;
        case 'pdf':
            return FileIcon;
        case 'audio':
            return HeadphonesIcon;
        default:
            return FileTextIcon;
    }
};

// Add a utility to convert YouTube/Vimeo URLs to embed URLs
function getEmbedUrl(url: string): string {
    if (!url) return '';
    // YouTube
    const ytMatch = url.match(/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/);
    if (ytMatch) {
        return `https://www.youtube.com/embed/${ytMatch[1]}?rel=0&showinfo=0&autoplay=0`;
    }
    // Vimeo
    const vimeoMatch = url.match(/vimeo\.com\/(\d+)/);
    if (vimeoMatch) {
        return `https://player.vimeo.com/video/${vimeoMatch[1]}`;
    }
    // Default: return as-is
    return url;
}

const sanitizeHtml = (html: string): string => {
    if (!html) return '';
    // Create a new DOMParser to safely parse HTML without executing scripts
    const parser = new DOMParser();
    const doc = parser.parseFromString(html, 'text/html');
    return doc.body.innerHTML || '';
};

// Quiz helper methods
const getBestScore = (attempts: QuizAttempt[]): number => {
    if (!attempts || attempts.length === 0) return 0;
    return Math.max(...attempts.map(attempt => attempt.score));
};


// File type display helper
const getFileTypeDisplay = (fileType: string | null): string => {
    if (!fileType) return 'ফাইল';
    
    switch (fileType.toLowerCase()) {
        case 'pdf':
            return 'পিডিএফ';
        case 'doc':
        case 'docx':
            return 'ওয়ার্ড ডকুমেন্ট';
        case 'ppt':
        case 'pptx':
            return 'প্রেজেন্টেশন';
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
            return 'ছবি';
        case 'mp3':
        case 'wav':
            return 'অডিও';
        case 'mp4':
        case 'avi':
            return 'ভিডিও';
        default:
            return 'ফাইল';
    }
};

// Initialize on mount
onMounted(() => {
    loadCourseData();
    
    // Check for quiz results in flash messages
    if (page.props.flash?.quiz_results) {
        quizResults.value = page.props.flash.quiz_results;
        quizCompleted.value = true;
        quizStarted.value = false;
    }
});

// Watch for course slug changes
watch(courseSlug, () => {
    if (courseSlug.value) {
        loadCourseData();
    }
});


// Track if user is actively learning (watching videos, reading content)
const isActiveLearning = ref(false);
const lastActivity = ref(Date.now());

// Set active learning state when user interacts with content
const updateActivity = () => {
    isActiveLearning.value = true;
    lastActivity.value = Date.now();
};

// Stop active learning after 5 minutes of inactivity
const checkInactivity = () => {
    if (Date.now() - lastActivity.value > 300000) {
        // 5 minutes
        isActiveLearning.value = false;
    }
};

// Poll lesson progress every 60 seconds during active learning
const { pause: pauseProgressPolling, resume: resumeProgressPolling } = useIntervalFn(
    () => {
        if (isActiveLearning.value && courseSlug.value) {
            router.reload({ only: ['enrollment'] });
        }
        checkInactivity();
    },
    60000,
    { immediate: false },
);

// Start polling when user becomes active
watch(isActiveLearning, (active) => {
    if (active) {
        resumeProgressPolling();
    } else {
        pauseProgressPolling();
    }
});

// Add event listeners to track user activity
onMounted(() => {
    const events = ['click', 'scroll', 'keydown', 'mousemove'];
    events.forEach((event) => {
        document.addEventListener(event, updateActivity);
    });
});

// Cleanup on unmount
onUnmounted(() => {
    pauseProgressPolling();
    const events = ['click', 'scroll', 'keydown', 'mousemove'];
    events.forEach((event) => {
        document.removeEventListener(event, updateActivity);
    });
});
</script>

<style scoped>
/* Custom scrollbar for sidebar */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #374151;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #6b7280;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}

/* Ensure video responsiveness */
iframe {
    min-height: 400px;
}

@media (max-width: 768px) {
    iframe {
        min-height: 250px;
    }
}

/* Prose styles for text content */
.prose {
    color: #6b7280;
}

.prose h1,
.prose h2,
.prose h3,
.prose h4 {
    color: #111827;
    font-weight: 600;
    margin-bottom: 1rem;
    margin-top: 2rem;
}

.prose p {
    margin-bottom: 1rem;
    line-height: 1.625;
}

.prose ul,
.prose ol {
    margin-bottom: 1.5rem;
}

.prose li {
    margin-bottom: 0.5rem;
}

/* Video aspect ratio utility */
.responsive-video {
    position: relative;
    width: 100%;
    padding-top: 56.25%; /* 16:9 Aspect Ratio */
}
.responsive-video iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border-radius: 0.75rem;
    box-shadow: 0 4px 24px 0 rgba(0, 0, 0, 0.08);
    background: #000;
}

/* Prose overrides for lesson content */
.prose-lg {
    font-size: 1.15rem;
    line-height: 1.8;
}
.prose h1,
.prose h2,
.prose h3 {
    color: #5f5fcd;
    font-weight: 700;
}
.prose strong {
    color: #222;
}

/* Accordion animation */
.module-scale-rotate-enter-active,
.module-scale-rotate-leave-active {
    transition:
        opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1),
        transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    will-change: opacity, transform;
    transform-origin: top center;
}
.module-scale-rotate-enter-from,
.module-scale-rotate-leave-to {
    opacity: 0;
    transform: scale(0.95) rotateX(-10deg);
}
.module-scale-rotate-enter-to,
.module-scale-rotate-leave-from {
    opacity: 1;
    transform: scale(1) rotateX(0deg);
}
</style>
