<template>
    <div class="relative min-h-screen bg-gray-50">
        <!-- Islamic Geometric Background Pattern -->
        <div class="pointer-events-none fixed inset-0 z-0 opacity-5">
            <svg class="h-full w-full" viewBox="0 0 1200 1200" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="islamicPatternGlobal" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse">
                        <path d="M50 0 L100 50 L50 100 L0 50 Z" fill="none" stroke="#5f5fcd" stroke-width="1" opacity="0.3" />
                        <circle cx="50" cy="50" r="20" fill="none" stroke="#2d5a27" stroke-width="1" opacity="0.2" />
                        <path d="M25 25 L75 75 M75 25 L25 75" stroke="#d4a574" stroke-width="1" opacity="0.2" />
                    </pattern>
                </defs>
                <rect width="1200" height="1200" fill="url(#islamicPatternGlobal)" />
            </svg>
        </div>

        <!-- Content Container with proper z-index -->
        <div class="relative z-10">
            <!-- Enhanced Navigation Header -->
            <header class="shadow-islamic sticky top-0 z-50 border-b border-gray-200 bg-white/95 backdrop-blur-sm">
                <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-20 items-center justify-between">
                        <!-- Enhanced Logo -->
                        <div class="flex items-center">
                            <Link :href="route('frontend.home')" class="group flex items-center">
                                <AppLogoIcon class="h-10 w-10" />
                            </Link>
                        </div>

                        <!-- Enhanced Desktop Navigation -->
                        <div class="hidden items-center space-x-1 lg:flex">
                            <Link
                                :href="route('frontend.home')"
                                class="group relative rounded-lg px-4 py-2 text-sm font-medium text-gray-700 transition-all duration-200 hover:bg-gray-50 hover:text-[#5f5fcd]"
                                :class="{ 'bg-gray-100 text-[#5f5fcd] shadow-sm': $page.component === 'Frontend/HomePage' }"
                            >
                                হোম
                                <div
                                    class="absolute bottom-0 left-1/2 h-0.5 w-0 -translate-x-1/2 transform bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] transition-all duration-200 group-hover:w-3/4"
                                ></div>
                            </Link>
                            <Link
                                :href="route('frontend.courses.index')"
                                class="group relative rounded-lg px-4 py-2 text-sm font-medium text-gray-700 transition-all duration-200 hover:bg-gray-50 hover:text-[#5f5fcd]"
                                :class="{ 'bg-gray-100 text-[#5f5fcd] shadow-sm': $page.component === 'Frontend/CoursesPage' }"
                            >
                                কোর্সসমূহ
                                <div
                                    class="absolute bottom-0 left-1/2 h-0.5 w-0 -translate-x-1/2 transform bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] transition-all duration-200 group-hover:w-3/4"
                                ></div>
                            </Link>
                            <Link
                                :href="route('frontend.quran')"
                                class="group relative rounded-lg px-4 py-2 text-sm font-medium text-gray-700 transition-all duration-200 hover:bg-gray-50 hover:text-[#5f5fcd]"
                                :class="{ 'bg-gray-100 text-[#5f5fcd] shadow-sm': $page.component === 'Frontend/QuranReader' }"
                            >
                                আল-কুরআন
                                <div
                                    class="absolute bottom-0 left-1/2 h-0.5 w-0 -translate-x-1/2 transform bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] transition-all duration-200 group-hover:w-3/4"
                                ></div>
                            </Link>
                            <Link
                                :href="route('frontend.blog.index')"
                                class="group relative rounded-lg px-4 py-2 text-sm font-medium text-gray-700 transition-all duration-200 hover:bg-gray-50 hover:text-[#5f5fcd]"
                                :class="{ 'bg-gray-100 text-[#5f5fcd] shadow-sm': $page.component?.startsWith('Frontend/Blog/') }"
                            >
                                ম্যাগাজিন
                                <div
                                    class="absolute bottom-0 left-1/2 h-0.5 w-0 -translate-x-1/2 transform bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] transition-all duration-200 group-hover:w-3/4"
                                ></div>
                            </Link>
                            <Link
                                :href="route('frontend.about')"
                                class="group relative rounded-lg px-4 py-2 text-sm font-medium text-gray-700 transition-all duration-200 hover:bg-gray-50 hover:text-[#5f5fcd]"
                            >
                                আমাদের সম্পর্কে
                                <div
                                    class="absolute bottom-0 left-1/2 h-0.5 w-0 -translate-x-1/2 transform bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] transition-all duration-200 group-hover:w-3/4"
                                ></div>
                            </Link>
                            <Link
                                :href="route('frontend.contact')"
                                class="group relative rounded-lg px-4 py-2 text-sm font-medium text-gray-700 transition-all duration-200 hover:bg-gray-50 hover:text-[#5f5fcd]"
                            >
                                যোগাযোগ
                                <div
                                    class="absolute bottom-0 left-1/2 h-0.5 w-0 -translate-x-1/2 transform bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] transition-all duration-200 group-hover:w-3/4"
                                ></div>
                            </Link>
                        </div>

                        <!-- Course Search Form (Desktop) -->
                        <form
                            class="relative mx-4 hidden w-72 items-center lg:flex"
                            @submit.prevent="onSearchSubmit"
                            role="search"
                            aria-label="কোর্স অনুসন্ধান"
                        >
                            <label for="course-search" class="sr-only">কোর্স অনুসন্ধান করুন</label>
                            <input
                                id="course-search"
                                v-model="searchQuery"
                                type="text"
                                placeholder="কোর্স অনুসন্ধান করুন..."
                                class="w-full rounded-xl border border-gray-200 bg-white py-2 pr-10 pl-4 text-sm text-gray-700 placeholder-gray-400 shadow-sm transition-all duration-200 focus:border-[#5f5fcd] focus:ring-2 focus:ring-[#5f5fcd]/20"
                                autocomplete="off"
                                :disabled="searching"
                            />
                            <button
                                type="submit"
                                :disabled="searching"
                                class="absolute top-1/2 right-2 -translate-y-1/2 text-[#5f5fcd] hover:text-[#2d5a27] focus:outline-none"
                            >
                                <svg
                                    v-if="!searching"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"
                                    />
                                </svg>
                                <svg
                                    v-else
                                    class="h-5 w-5 animate-spin text-[#5f5fcd]"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                            </button>
                        </form>

                        <!-- Student Auth Section -->
                        <div class="hidden items-center space-x-4 md:flex">
                            <template v-if="$page.props.auth && $page.props.auth.user && isStudent">
                                <!-- Student Dropdown -->
                                <div class="relative">
                                    <button
                                        @click="userDropdownOpen = !userDropdownOpen"
                                        class="flex items-center space-x-2 rounded-lg p-2 text-gray-700 transition-all duration-200 hover:bg-gray-50 hover:text-[#5f5fcd] focus:ring-2 focus:ring-[#5f5fcd]/20 focus:outline-none"
                                    >
                                        <div class="relative">
                                            <div
                                                class="student-avatar flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] shadow-sm"
                                            >
                                                <span class="text-sm font-semibold text-white">{{ $page.props.auth.user.name.charAt(0) }}</span>
                                            </div>
                                            <div
                                                class="absolute -right-0.5 -bottom-0.5 h-2.5 w-2.5 rounded-full border-2 border-white bg-green-400"
                                            ></div>
                                        </div>
                                        <ChevronDownIcon
                                            class="h-4 w-4 transition-transform duration-200"
                                            :class="{ 'rotate-180': userDropdownOpen }"
                                        />
                                    </button>

                                    <!-- Student Dropdown Menu -->
                                    <div
                                        v-show="userDropdownOpen"
                                        class="ring-opacity-5 absolute right-0 z-50 mt-2 w-52 origin-top-right rounded-lg border border-gray-200 bg-white py-1 shadow-lg ring-1 ring-black transition-all duration-200"
                                        :class="userDropdownOpen ? 'scale-100 opacity-100' : 'scale-95 opacity-0'"
                                    >
                                        <Link
                                            :href="route('frontend.student.dashboard')"
                                            class="flex items-center px-4 py-3 text-sm text-gray-700 transition-colors hover:bg-gray-50 hover:text-[#5f5fcd]"
                                        >
                                            <UserIcon class="mr-3 h-4 w-4" />
                                            ড্যাশবোর্ড
                                        </Link>
                                        <Link
                                            :href="route('frontend.student.dashboard')"
                                            class="flex items-center px-4 py-3 text-sm text-gray-700 transition-colors hover:bg-gray-50 hover:text-[#5f5fcd]"
                                        >
                                            <BookOpenIcon class="mr-3 h-4 w-4" />
                                            আমার কোর্স
                                        </Link>
                                        <div class="my-1 border-t border-gray-200"></div>
                                        <button
                                            @click="logout"
                                            class="flex w-full items-center px-4 py-3 text-sm text-red-600 transition-colors hover:bg-red-50"
                                        >
                                            <LogOutIcon class="mr-3 h-4 w-4" />
                                            লগআউট
                                        </button>
                                    </div>
                                </div>
                            </template>
                            <template v-else>
                                <!-- Enhanced Auth Buttons -->
                                <div class="flex items-center space-x-3">
                                    <Link
                                        :href="route('login')"
                                        class="group hover:shadow-islamic-sm flex items-center space-x-2 rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-700 transition-all duration-300 hover:border-[#5f5fcd]/30 hover:bg-gradient-to-r hover:from-[#5f5fcd]/10 hover:to-[#2d5a27]/10 hover:text-[#5f5fcd]"
                                    >
                                        <LogInIcon class="h-4 w-4 transition-transform duration-300 group-hover:scale-110" />
                                        <span>লগইন</span>
                                    </Link>
                                    <Link
                                        :href="route('register')"
                                        class="group hover:shadow-islamic-lg relative flex transform items-center space-x-2 overflow-hidden rounded-xl bg-gradient-to-r from-[#5f5fcd] via-[#2d5a27] to-[#d4a574] px-6 py-2.5 text-sm font-semibold text-white transition-all duration-300 hover:scale-105"
                                    >
                                        <!-- Animated background overlay -->
                                        <div
                                            class="absolute inset-0 -translate-x-full -skew-x-12 transform bg-gradient-to-r from-white/0 via-white/10 to-white/0 transition-transform duration-700 group-hover:translate-x-full"
                                        ></div>
                                        <UserPlusIcon class="relative z-10 h-4 w-4 transition-transform duration-300 group-hover:scale-110" />
                                        <span class="relative z-10">নিবন্ধন</span>
                                    </Link>
                                </div>
                            </template>
                        </div>

                        <!-- Enhanced Mobile menu button -->
                        <div class="md:hidden">
                            <button
                                @click="mobileMenuOpen = !mobileMenuOpen"
                                class="rounded-lg p-2 text-gray-500 transition-colors hover:bg-gray-50 hover:text-gray-700"
                            >
                                <MenuIcon v-if="!mobileMenuOpen" class="h-6 w-6" />
                                <XIcon v-else class="h-6 w-6" />
                            </button>
                        </div>
                    </div>

                    <!-- Enhanced Mobile Navigation -->
                    <div v-show="mobileMenuOpen" class="border-t border-gray-200 bg-white/95 backdrop-blur-sm md:hidden">
                        <div class="space-y-1">
                            <Link
                                :href="route('frontend.home')"
                                class="flex items-center rounded-lg px-4 py-3 text-gray-700 transition-colors hover:bg-gray-50 hover:text-[#5f5fcd]"
                                :class="{ 'bg-gray-100 text-[#5f5fcd]': $page.component === 'Frontend/HomePage' }"
                            >
                                <HomeIcon class="mr-3 h-5 w-5" />
                                হোম
                            </Link>
                            <Link
                                :href="route('frontend.courses.index')"
                                class="flex items-center rounded-lg px-4 py-3 text-gray-700 transition-colors hover:bg-gray-50 hover:text-[#5f5fcd]"
                                :class="{ 'bg-gray-100 text-[#5f5fcd]': $page.component === 'Frontend/CoursesPage' }"
                            >
                                <BookOpenIcon class="mr-3 h-5 w-5" />
                                কোর্সসমূহ
                            </Link>
                            <Link
                                :href="route('frontend.quran')"
                                class="flex items-center rounded-lg px-4 py-3 text-gray-700 transition-colors hover:bg-gray-50 hover:text-[#5f5fcd]"
                                :class="{ 'bg-gray-100 text-[#5f5fcd]': $page.component === 'Frontend/QuranReader' }"
                            >
                                <BookIcon class="mr-3 h-5 w-5" />
                                আল-কুরআন
                            </Link>
                            <Link
                                :href="route('frontend.blog.index')"
                                class="flex items-center rounded-lg px-4 py-3 text-gray-700 transition-colors hover:bg-gray-50 hover:text-[#5f5fcd]"
                                :class="{ 'bg-gray-100 text-[#5f5fcd]': $page.component?.startsWith('Frontend/Blog/') }"
                            >
                                <MessageCircleIcon class="mr-3 h-5 w-5" />
                                ম্যাগাজিন
                            </Link>
                            <Link
                                :href="route('frontend.about')"
                                class="flex items-center rounded-lg px-4 py-3 text-gray-700 transition-colors hover:bg-gray-50 hover:text-[#5f5fcd]"
                            >
                                <InfoIcon class="mr-3 h-5 w-5" />
                                আমাদের সম্পর্কে
                            </Link>
                            <Link
                                :href="route('frontend.contact')"
                                class="flex items-center rounded-lg px-4 py-3 text-gray-700 transition-colors hover:bg-gray-50 hover:text-[#5f5fcd]"
                            >
                                <MessageCircleIcon class="mr-3 h-5 w-5" />
                                যোগাযোগ
                            </Link>

                            <div class="mt-4 border-t border-gray-200 pt-4">
                                <template v-if="$page.props.auth && $page.props.auth.user && isStudent">
                                    <Link
                                        :href="route('frontend.student.dashboard')"
                                        class="flex items-center rounded-lg px-4 py-3 text-gray-700 transition-colors hover:bg-gray-50 hover:text-[#5f5fcd]"
                                    >
                                        <UserIcon class="mr-3 h-5 w-5" />
                                        ড্যাশবোর্ড
                                    </Link>
                                    <Link
                                        :href="route('frontend.student.dashboard')"
                                        class="flex items-center rounded-lg px-4 py-3 text-gray-700 transition-colors hover:bg-gray-50 hover:text-[#5f5fcd]"
                                    >
                                        <BookOpenIcon class="mr-3 h-5 w-5" />
                                        আমার কোর্স
                                    </Link>
                                    <button
                                        @click="logout"
                                        class="flex w-full items-center rounded-lg px-4 py-3 text-red-600 transition-colors hover:bg-red-50 hover:text-red-700"
                                    >
                                        <LogOutIcon class="mr-3 h-5 w-5" />
                                        লগআউট
                                    </button>
                                </template>
                                <template v-else>
                                    <Link
                                        :href="route('login')"
                                        class="flex items-center rounded-xl border border-transparent px-4 py-3 text-gray-700 transition-all duration-300 hover:border-[#5f5fcd]/30 hover:bg-gradient-to-r hover:from-[#5f5fcd]/10 hover:to-[#2d5a27]/10 hover:text-[#5f5fcd]"
                                    >
                                        <LogInIcon class="mr-3 h-5 w-5 transition-transform duration-300 group-hover:scale-110" />
                                        <span class="font-medium">লগইন</span>
                                    </Link>
                                    <Link
                                        :href="route('register')"
                                        class="group relative mx-4 my-2 flex transform items-center overflow-hidden rounded-xl bg-gradient-to-r from-[#5f5fcd] via-[#2d5a27] to-[#d4a574] px-4 py-3 text-sm font-semibold text-white transition-all duration-300 hover:scale-105"
                                    >
                                        <!-- Animated background overlay -->
                                        <div
                                            class="absolute inset-0 -translate-x-full -skew-x-12 transform bg-gradient-to-r from-white/0 via-white/10 to-white/0 transition-transform duration-700 group-hover:translate-x-full"
                                        ></div>
                                        <UserPlusIcon class="relative z-10 mr-3 h-5 w-5 transition-transform duration-300 group-hover:scale-110" />
                                        <span class="relative z-10">নিবন্ধন</span>
                                    </Link>
                                </template>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>

            <!-- Main Content -->
            <main class="flex-1">
                <slot />
            </main>

            <!-- Enhanced Islamic Divider -->
            <div class="divider-islamic relative mx-auto my-16 max-w-4xl">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-white shadow-lg">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27]">
                            <span class="text-sm font-bold text-white">ই</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Footer -->
            <footer class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white">
                <!-- Footer Background Pattern -->
                <div class="absolute inset-0 opacity-5">
                    <svg class="h-full w-full" viewBox="0 0 1200 400" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="footerPattern" x="0" y="0" width="60" height="60" patternUnits="userSpaceOnUse">
                                <path d="M30 0 L60 30 L30 60 L0 30 Z" fill="none" stroke="#d4a574" stroke-width="1" />
                                <circle cx="30" cy="30" r="15" fill="none" stroke="#5f5fcd" stroke-width="1" />
                            </pattern>
                        </defs>
                        <rect width="1200" height="400" fill="url(#footerPattern)" />
                    </svg>
                </div>

                <div class="relative z-10 mx-auto max-w-7xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8 lg:py-16">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 sm:gap-8 lg:grid-cols-8 lg:gap-12">
                        <!-- Enhanced Brand Section -->
                        <div class="sm:col-span-2 lg:col-span-2">
                            <div class="mb-4 sm:mb-6">
                                <AppLogoIcon class="h-10 w-10" />
                            </div>
                            <p class="mb-4 text-sm leading-relaxed text-gray-300 sm:mb-6 sm:text-base lg:text-lg">
                                ইসলামিক শিক্ষায় আধুনিক প্রযুক্তির সমন্বয়ে গড়ে উঠেছে ইকরা অনলাইন একাডেমি। কুরআন, হাদিস ও ইসলামিক জ্ঞানচর্চায় আমরা
                                আপনার সাথে রয়েছি।
                            </p>

                            <!-- Enhanced Social Links -->
                            <div class="flex space-x-3 sm:space-x-4">
                                <a
                                    href="https://www.facebook.com/IqraOA"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="hover:shadow-islamic-lg flex h-8 w-8 transform items-center justify-center rounded-lg bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] text-white transition-all duration-200 hover:scale-110 sm:h-10 sm:w-10"
                                >
                                    <FacebookIcon class="h-4 w-4 sm:h-5 sm:w-5" />
                                </a>
                                <a
                                    href="#"
                                    class="hover:shadow-islamic-lg flex h-8 w-8 transform items-center justify-center rounded-lg bg-gradient-to-br from-[#d4a574] to-[#b8945f] text-white transition-all duration-200 hover:scale-110 sm:h-10 sm:w-10"
                                >
                                    <YoutubeIcon class="h-4 w-4 sm:h-5 sm:w-5" />
                                </a>
                                <a
                                    href="#"
                                    class="hover:shadow-islamic-lg flex h-8 w-8 transform items-center justify-center rounded-lg bg-gradient-to-br from-[#2d5a27] to-[#1f3e1b] text-white transition-all duration-200 hover:scale-110 sm:h-10 sm:w-10"
                                >
                                    <TwitterIcon class="h-4 w-4 sm:h-5 sm:w-5" />
                                </a>
                                <a
                                    href="#"
                                    class="hover:shadow-islamic-lg flex h-8 w-8 transform items-center justify-center rounded-lg bg-gradient-to-br from-[#5f5fcd] to-[#4a4aa6] text-white transition-all duration-200 hover:scale-110 sm:h-10 sm:w-10"
                                >
                                    <InstagramIcon class="h-4 w-4 sm:h-5 sm:w-5" />
                                </a>
                            </div>
                        </div>

                        <!-- Quick Links -->
                        <div class="lg:col-span-2">
                            <h3 class="mb-3 flex items-center text-lg font-semibold text-[#d4a574] sm:mb-4 sm:text-xl lg:mb-6">
                                <LinkIcon class="mr-2 h-4 w-4 sm:h-5 sm:w-5" />
                                দ্রুত লিঙ্ক
                            </h3>
                            <ul class="space-y-3">
                                <li>
                                    <Link
                                        :href="route('frontend.courses.index')"
                                        class="group flex items-center text-gray-300 transition-colors hover:text-white"
                                    >
                                        <ArrowRightIcon class="mr-2 h-3 w-3 transition-transform group-hover:translate-x-1" />
                                        কোর্সসমূহ
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        :href="route('frontend.about')"
                                        class="group flex items-center text-gray-300 transition-colors hover:text-white"
                                    >
                                        <ArrowRightIcon class="mr-2 h-3 w-3 transition-transform group-hover:translate-x-1" />
                                        আমাদের সম্পর্কে
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        :href="route('frontend.blog.index')"
                                        class="group flex items-center text-gray-300 transition-colors hover:text-white"
                                    >
                                        <ArrowRightIcon class="mr-2 h-3 w-3 transition-transform group-hover:translate-x-1" />
                                        ম্যাগাজিন
                                    </Link>
                                </li>
                            </ul>
                        </div>

                        <!-- Student Resources -->
                        <div class="lg:col-span-2">
                            <h3 class="mb-3 flex items-center text-lg font-semibold text-[#d4a574] sm:mb-4 sm:text-xl lg:mb-6">
                                <BookOpenIcon class="mr-2 h-4 w-4 sm:h-5 sm:w-5" />
                                শিক্ষার্থী রিসোর্স
                            </h3>
                            <ul class="space-y-3">
                                <li>
                                    <Link :href="route('register')" class="group flex items-center text-gray-300 transition-colors hover:text-white">
                                        <ArrowRightIcon class="mr-2 h-3 w-3 transition-transform group-hover:translate-x-1" />
                                        নিবন্ধন
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        :href="route('frontend.student.dashboard')"
                                        class="group flex items-center text-gray-300 transition-colors hover:text-white"
                                    >
                                        <ArrowRightIcon class="mr-2 h-3 w-3 transition-transform group-hover:translate-x-1" />
                                        ড্যাশবোর্ড
                                    </Link>
                                </li>
                                <li>
                                    <a href="#donation" class="group flex items-center text-gray-300 transition-colors hover:text-white">
                                        <ArrowRightIcon class="mr-2 h-3 w-3 transition-transform group-hover:translate-x-1" />
                                        ডোনেশন
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Enhanced Contact Info -->
                        <div class="sm:col-span-2 lg:col-span-2">
                            <h3 class="mb-3 flex items-center text-lg font-semibold text-[#d4a574] sm:mb-4 sm:text-xl lg:mb-6">
                                <MessageCircleIcon class="mr-2 h-4 w-4 sm:h-5 sm:w-5" />
                                যোগাযোগ
                            </h3>
                            <ul class="space-y-3 text-gray-300 sm:space-y-4">
                                <li class="group flex items-start space-x-2 sm:space-x-3">
                                    <div
                                        class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] transition-transform group-hover:scale-110 sm:h-8 sm:w-8"
                                    >
                                        <PhoneIcon class="h-3 w-3 text-white sm:h-4 sm:w-4" />
                                    </div>
                                    <div>
                                        <span class="block text-sm font-medium sm:text-base">01750-469027</span>
                                        <span class="text-xs text-gray-400 sm:text-sm">সোম-শুক্র, সকাল ৯টা-সন্ধ্যা ৬টা</span>
                                    </div>
                                </li>
                                <li class="group flex items-start space-x-2 sm:space-x-3">
                                    <div
                                        class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-[#d4a574] to-[#b8945f] transition-transform group-hover:scale-110 sm:h-8 sm:w-8"
                                    >
                                        <MailIcon class="h-3 w-3 text-white sm:h-4 sm:w-4" />
                                    </div>
                                    <div>
                                        <span class="block text-sm font-medium sm:text-base">iqraoa313@gmail.com</span>
                                        <span class="text-xs text-gray-400 sm:text-sm">২৪ ঘণ্টার মধ্যে উত্তর</span>
                                    </div>
                                </li>
                                <li class="group flex items-start space-x-2 sm:space-x-3">
                                    <div
                                        class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-[#2d5a27] to-[#1f3e1b] transition-transform group-hover:scale-110 sm:h-8 sm:w-8"
                                    >
                                        <MapPinIcon class="h-3 w-3 text-white sm:h-4 sm:w-4" />
                                    </div>
                                    <div>
                                        <span class="block text-sm font-medium sm:text-base">হাউজিং এস্টেট, চট্টগ্রাম, বাংলাদেশ</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Enhanced Bottom Border -->
                    <div class="mt-6 border-t border-gray-800 pt-4 sm:mt-8 sm:pt-6 lg:mt-12 lg:pt-8">
                        <div class="flex flex-col items-center justify-between space-y-3 sm:space-y-4 lg:flex-row lg:space-y-0">
                            <div
                                class="flex flex-col items-center space-y-2 text-center sm:flex-row sm:space-y-0 sm:space-x-3 sm:text-left lg:space-x-4"
                            >
                                <p class="text-xs text-gray-400 sm:text-sm">© ২০২৫ ইকরা অনলাইন একাডেমি। সর্বস্বত্ব সংরক্ষিত।</p>
                                <div class="hidden h-1 w-1 rounded-full bg-gray-600 sm:block"></div>
                                <p class="text-xs text-gray-400 sm:text-sm">
                                    Developed by
                                    <a
                                        href="https://www.pixelweblab.com/"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="font-medium text-[#5f5fcd] transition-colors duration-200 hover:text-[#2d5a27]"
                                    >
                                        PixelWeblab
                                    </a>
                                </p>
                            </div>
                            <div class="flex flex-wrap justify-center gap-3 text-xs sm:gap-4 sm:text-sm lg:justify-end lg:gap-6">
                                <Link :href="route('certificates.verify')" class="text-gray-400 transition-colors hover:text-white">সার্টিফিকেট যাচাই</Link>
                                <a href="#" class="text-gray-400 transition-colors hover:text-white">প্রাইভেসি পলিসি</a>
                                <a href="#" class="text-gray-400 transition-colors hover:text-white">ব্যবহারের শর্তাবলী</a>
                                <a href="#" class="text-gray-400 transition-colors hover:text-white">রিফান্ড পলিসি</a>
                                <a href="#" class="text-gray-400 transition-colors hover:text-white">কুকি পলিসি</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>

            <!-- Toast Container -->
            <ToastContainer :toasts="toasts" :onRemove="removeToast" />

            <!-- Notification Toast -->
            <NotificationToast ref="notificationToast" />

            <!-- Error Boundary -->
            <ErrorBoundary>
                <!-- Content will be wrapped by error boundary -->
            </ErrorBoundary>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import ErrorBoundary from '@/components/Frontend/ErrorBoundary.vue';
import NotificationToast from '@/components/Frontend/NotificationToast.vue';
import { ToastContainer, useToast } from '@/components/ui/toast';
import { Link, router, usePage } from '@inertiajs/vue3';
import {
    ArrowRightIcon,
    BookIcon,
    BookOpenIcon,
    ChevronDownIcon,
    FacebookIcon,
    HomeIcon,
    InfoIcon,
    InstagramIcon,
    LinkIcon,
    LogInIcon,
    LogOutIcon,
    MailIcon,
    MapPinIcon,
    MenuIcon,
    MessageCircleIcon,
    PhoneIcon,
    TwitterIcon,
    UserIcon,
    UserPlusIcon,
    XIcon,
    YoutubeIcon,
} from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref } from 'vue';

// Define props for receiving data
defineProps<{
    title?: string;
}>();

// Mobile menu state
const mobileMenuOpen = ref(false);

// User dropdown state
const userDropdownOpen = ref(false);

// Notification toast ref
const notificationToast = ref();

// Toast system
const { toasts, removeToast } = useToast();

// Page composable for accessing auth data
const page = usePage();

// Search query state
const searchQuery = ref('');
const searching = ref(false);

// Check if user is a student
const isStudent = computed(() => {
    const user = page.props.auth?.user;
    const roles = Array.isArray((user as any)?.roles) ? (user as any).roles.map((r: any) => r.role_type) : [];
    return roles.includes('student');
});

// Logout function
const logout = () => {
    router.post(route('logout'));
};

// Close dropdown when clicking outside
const handleClickOutside = (event: Event) => {
    const target = event.target as Element;
    if (!target.closest('.relative')) {
        userDropdownOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});

function onSearchSubmit() {
    const query = searchQuery.value.trim();
    if (!query) return;
    // Prevent redundant navigation
    const currentUrl = window.location.pathname + window.location.search;
    const targetUrl = route('frontend.courses.index', { search: query });
    if (currentUrl === targetUrl) return;
    searching.value = true;
    router.get(
        route('frontend.courses.index'),
        { search: query },
        {
            onFinish: () => {
                searching.value = false;
                // Optionally clear the search field after navigation
                // searchQuery.value = ''
            },
        },
    );
}

function scrollToDonation() {
    // Check if we are on the Home page
    if (page.component === 'Frontend/HomePage') {
        const el = document.getElementById('donation');
        if (el) {
            el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            el.classList.add('donation-highlight');
            setTimeout(() => el.classList.remove('donation-highlight'), 1200);
        }
    } else {
        // Navigate to Home page with scroll query
        router.visit(route('frontend.home', { scroll: 'donation' }));
    }
}
</script>

<style scoped>
/* Additional Islamic-inspired animations */
@keyframes float {
    0%,
    100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-10px);
    }
}

.float-animation {
    animation: float 3s ease-in-out infinite;
}

/* Ensure Islamic pattern is visible across all sections */
:deep(.bg-white) {
    background-color: rgba(255, 255, 255, 0.95) !important;
}

:deep(.bg-gray-50) {
    background-color: rgba(249, 250, 251, 0.9) !important;
}

:deep(.bg-gray-100) {
    background-color: rgba(243, 244, 246, 0.9) !important;
}

/* Custom scrollbar for Islamic theme */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #5f5fcd 0%, #2d5a27 100%);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #4a4aa6 0%, #1f3e1b 100%);
}

/* Enhanced Islamic divider */
.divider-islamic {
    height: 2px;
    background: linear-gradient(90deg, transparent 0%, #5f5fcd 25%, #d4a574 50%, #2d5a27 75%, transparent 100%);
    position: relative;
}

.divider-islamic::before {
    content: '';
    position: absolute;
    top: -1px;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(
        90deg,
        transparent 0%,
        rgba(95, 95, 205, 0.3) 25%,
        rgba(212, 165, 116, 0.3) 50%,
        rgba(45, 90, 39, 0.3) 75%,
        transparent 100%
    );
    border-radius: 2px;
}

.donation-highlight {
    animation: donationFlash 1.2s;
}
@keyframes donationFlash {
    0% {
        box-shadow: 0 0 0 0 #5f5fcd66;
    }
    50% {
        box-shadow: 0 0 24px 8px #5f5fcd99;
    }
    100% {
        box-shadow: 0 0 0 0 #5f5fcd00;
    }
}

/* Student dropdown animations */
.student-dropdown-enter-active,
.student-dropdown-leave-active {
    transition: all 0.2s ease;
}

.student-dropdown-enter-from,
.student-dropdown-leave-to {
    transform: scale(0.95);
    opacity: 0;
}

.student-dropdown-enter-to,
.student-dropdown-leave-from {
    transform: scale(1);
    opacity: 1;
}

/* Avatar hover effects */
.student-avatar:hover {
    transform: scale(1.05);
}

.student-avatar {
    transition: transform 0.2s ease;
}

@media (max-width: 640px) {
    header nav > div.flex {
        height: 3.5rem !important; /* Override h-20 for mobile */
        min-height: 3.5rem !important;
        max-height: 3.5rem !important;
    }
}
</style>

<InstallPWAButton />
<PWAUpdatePrompt />
