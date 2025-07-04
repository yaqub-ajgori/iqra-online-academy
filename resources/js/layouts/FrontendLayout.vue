<template>
  <div class="min-h-screen bg-gray-50 relative">
    <!-- Islamic Geometric Background Pattern -->
    <div class="fixed inset-0 z-0 opacity-5 pointer-events-none">
      <svg class="w-full h-full" viewBox="0 0 1200 1200" xmlns="http://www.w3.org/2000/svg">
        <defs>
          <pattern id="islamicPatternGlobal" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse">
            <path d="M50 0 L100 50 L50 100 L0 50 Z" fill="none" stroke="#5f5fcd" stroke-width="1" opacity="0.3"/>
            <circle cx="50" cy="50" r="20" fill="none" stroke="#2d5a27" stroke-width="1" opacity="0.2"/>
            <path d="M25 25 L75 75 M75 25 L25 75" stroke="#d4a574" stroke-width="1" opacity="0.2"/>
          </pattern>
        </defs>
        <rect width="1200" height="1200" fill="url(#islamicPatternGlobal)" />
      </svg>
    </div>
    
    <!-- Content Container with proper z-index -->
    <div class="relative z-10">
      <!-- Enhanced Navigation Header -->
      <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-200 shadow-islamic">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between items-center h-20">
            <!-- Enhanced Logo -->
            <div class="flex items-center">
              <Link :href="route('frontend.home')" class="flex items-center space-x-3 group">
                <div class="relative">
                  <div class="w-12 h-12 bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] rounded-xl flex items-center justify-center shadow-islamic group-hover:shadow-islamic-lg transition-all duration-300 transform group-hover:scale-105">
                    <span class="text-white font-bold text-xl">ই</span>
                  </div>
                  <div class="absolute -top-1 -right-1 w-4 h-4 bg-[#d4a574] rounded-full animate-pulse shadow-lg"></div>
                  <div class="absolute -bottom-1 -left-1 w-2 h-2 bg-[#2d5a27] rounded-full animate-ping"></div>
                </div>
                <div>
                  <h1 class="text-2xl font-bold text-gradient-islamic group-hover:scale-105 transition-transform duration-300">ইকরা</h1>
                  <p class="text-xs text-gray-500 -mt-1">অনলাইন একাডেমি</p>
                </div>
              </Link>
            </div>

            <!-- Enhanced Desktop Navigation -->
            <div class="hidden lg:flex items-center space-x-1">
              <Link 
                :href="route('frontend.home')" 
                class="text-gray-700 hover:text-[#5f5fcd] px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:bg-gray-50 relative group"
                :class="{ 'text-[#5f5fcd] bg-gray-100 shadow-sm': $page.component === 'Frontend/HomePage' }"
              >
                হোম
                <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-0 h-0.5 bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] transition-all duration-200 group-hover:w-3/4"></div>
              </Link>
              <Link 
                :href="route('frontend.courses.index')" 
                class="text-gray-700 hover:text-[#5f5fcd] px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:bg-gray-50 relative group"
                :class="{ 'text-[#5f5fcd] bg-gray-100 shadow-sm': $page.component === 'Frontend/CoursesPage' }"
              >
                কোর্সসমূহ
                <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-0 h-0.5 bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] transition-all duration-200 group-hover:w-3/4"></div>
              </Link>
              <Link 
                :href="route('frontend.about')" 
                class="text-gray-700 hover:text-[#5f5fcd] px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:bg-gray-50 relative group"
              >
                আমাদের সম্পর্কে
                <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-0 h-0.5 bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] transition-all duration-200 group-hover:w-3/4"></div>
              </Link>
              <Link 
                :href="route('frontend.contact')" 
                class="text-gray-700 hover:text-[#5f5fcd] px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:bg-gray-50 relative group"
              >
                যোগাযোগ
                <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-0 h-0.5 bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] transition-all duration-200 group-hover:w-3/4"></div>
              </Link>
              <a 
                href="#donation"
                class="text-gray-700 hover:text-[#5f5fcd] px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:bg-gray-50 relative group"
                @click.prevent="scrollToDonation"
              >
                ডোনেশন
                <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-0 h-0.5 bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] transition-all duration-200 group-hover:w-3/4"></div>
              </a>
            </div>

            <!-- Course Search Form (Desktop) -->
            <form
              class="hidden lg:flex items-center mx-4 w-72 relative"
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
                class="w-full py-2 pl-4 pr-10 rounded-xl border border-gray-200 focus:border-[#5f5fcd] focus:ring-2 focus:ring-[#5f5fcd]/20 bg-white shadow-sm text-sm text-gray-700 placeholder-gray-400 transition-all duration-200"
                autocomplete="off"
                :disabled="searching"
              />
              <button type="submit" :disabled="searching" class="absolute right-2 top-1/2 -translate-y-1/2 text-[#5f5fcd] hover:text-[#2d5a27] focus:outline-none">
                <svg v-if="!searching" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" /></svg>
                <svg v-else class="animate-spin h-5 w-5 text-[#5f5fcd]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
              </button>
            </form>

            <!-- Enhanced Auth Buttons -->
            <div class="hidden md:flex items-center space-x-4">
              <template v-if="$page.props.auth && $page.props.auth.user">
                <!-- Enhanced User Dropdown -->
                <div class="relative">
                  <button 
                    @click="userDropdownOpen = !userDropdownOpen"
                    class="flex items-center space-x-3 text-gray-700 hover:text-[#5f5fcd] transition-all duration-200 p-2 rounded-lg hover:bg-gray-50"
                  >
                    <div class="relative">
                      <div class="w-10 h-10 bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] rounded-full flex items-center justify-center shadow-sm">
                        <span class="text-white text-sm font-semibold">{{ $page.props.auth.user.name.charAt(0) }}</span>
                      </div>
                      <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-400 rounded-full border-2 border-white"></div>
                    </div>
                    <div class="text-left">
                      <span class="text-sm font-medium block">{{ $page.props.auth.user.name }}</span>
                      <span class="text-xs text-gray-500">{{ isAdmin() ? 'Admin' : 'Student' }}</span>
                    </div>
                    <ChevronDownIcon class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': userDropdownOpen }" />
                  </button>
                  
                  <!-- Enhanced Dropdown Menu -->
                  <div 
                    v-show="userDropdownOpen" 
                    class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-islamic-lg border border-gray-200 py-2 z-50"
                  >
                    <!-- User Info Header -->
                    <div class="px-4 py-3 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200 rounded-t-xl">
                      <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] rounded-full flex items-center justify-center">
                          <span class="text-white text-xs font-semibold">{{ $page.props.auth.user.name.charAt(0) }}</span>
                        </div>
                        <div>
                          <p class="text-sm font-medium text-gray-900">{{ $page.props.auth.user.name }}</p>
                          <p class="text-xs text-gray-500">{{ isAdmin() ? '🔧 Admin Account' : '👨‍🎓 Student Account' }}</p>
                        </div>
                      </div>
                    </div>
                    
                    <div class="py-2">
                      <Link 
                        :href="getDashboardRoute()" 
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#5f5fcd] transition-colors"
                      >
                        <UserIcon class="w-4 h-4 mr-3" />
                        {{ isAdmin() ? 'Admin Dashboard' : 'Student Dashboard' }}
                      </Link>
                      <Link 
                        :href="route('frontend.student.dashboard')" 
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#5f5fcd] transition-colors"
                      >
                        <BookOpenIcon class="w-4 h-4 mr-3" />
                        My Courses
                      </Link>
                      <Link 
                        :href="route('frontend.contact')" 
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#5f5fcd] transition-colors"
                      >
                        <MessageCircleIcon class="w-4 h-4 mr-3" />
                        Support
                      </Link>
                    </div>
                    
                    <div class="border-t border-gray-200 my-2"></div>
                    <button 
                      @click="logout"
                      class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors"
                    >
                      <LogOutIcon class="w-4 h-4 mr-3" />
                      Logout
                    </button>
                  </div>
                </div>
              </template>
              <template v-else>
                <!-- Enhanced Auth Buttons -->
                <div class="flex items-center space-x-3">
                  <Link 
                    :href="route('login')" 
                    class="group flex items-center space-x-2 text-gray-700 hover:text-[#5f5fcd] px-4 py-2.5 text-sm font-medium transition-all duration-300 hover:bg-gradient-to-r hover:from-[#5f5fcd]/10 hover:to-[#2d5a27]/10 rounded-xl border border-gray-200 hover:border-[#5f5fcd]/30 hover:shadow-islamic-sm"
                  >
                    <LogInIcon class="w-4 h-4 transition-transform duration-300 group-hover:scale-110" />
                    <span>লগইন</span>
                  </Link>
                  <Link 
                    :href="route('register')" 
                    class="group flex items-center space-x-2 bg-gradient-to-r from-[#5f5fcd] via-[#2d5a27] to-[#d4a574] text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:shadow-islamic-lg transition-all duration-300 transform hover:scale-105 relative overflow-hidden"
                  >
                    <!-- Animated background overlay -->
                    <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/10 to-white/0 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                    <UserPlusIcon class="w-4 h-4 relative z-10 transition-transform duration-300 group-hover:scale-110" />
                    <span class="relative z-10">নিবন্ধন</span>
                  </Link>
                </div>
              </template>
            </div>

            <!-- Enhanced Mobile menu button -->
            <div class="md:hidden">
              <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-500 hover:text-gray-700 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                <MenuIcon v-if="!mobileMenuOpen" class="w-6 h-6" />
                <XIcon v-else class="w-6 h-6" />
              </button>
            </div>
          </div>

          <!-- Enhanced Mobile Navigation -->
          <div v-show="mobileMenuOpen" class="md:hidden border-t border-gray-200 py-6 bg-white/95 backdrop-blur-sm">
            
            <div class="space-y-1">
              <Link 
                :href="route('frontend.home')" 
                class="flex items-center px-4 py-3 text-gray-700 hover:text-[#5f5fcd] hover:bg-gray-50 rounded-lg transition-colors"
                :class="{ 'text-[#5f5fcd] bg-gray-100': $page.component === 'Frontend/HomePage' }"
              >
                <HomeIcon class="w-5 h-5 mr-3" />
                হোম
              </Link>
              <Link 
                :href="route('frontend.courses.index')" 
                class="flex items-center px-4 py-3 text-gray-700 hover:text-[#5f5fcd] hover:bg-gray-50 rounded-lg transition-colors"
                :class="{ 'text-[#5f5fcd] bg-gray-100': $page.component === 'Frontend/CoursesPage' }"
              >
                <BookOpenIcon class="w-5 h-5 mr-3" />
                কোর্সসমূহ
              </Link>
              <Link 
                :href="route('frontend.about')" 
                class="flex items-center px-4 py-3 text-gray-700 hover:text-[#5f5fcd] hover:bg-gray-50 rounded-lg transition-colors"
              >
                <InfoIcon class="w-5 h-5 mr-3" />
                আমাদের সম্পর্কে
              </Link>
              <Link 
                :href="route('frontend.contact')" 
                class="flex items-center px-4 py-3 text-gray-700 hover:text-[#5f5fcd] hover:bg-gray-50 rounded-lg transition-colors"
              >
                <MessageCircleIcon class="w-5 h-5 mr-3" />
                যোগাযোগ
              </Link>
              <a 
                href="#donation"
                class="flex items-center px-4 py-3 text-gray-700 hover:text-[#5f5fcd] hover:bg-gray-50 rounded-lg transition-colors"
                @click.prevent="scrollToDonation"
              >
                <HeartIcon class="w-5 h-5 mr-3" />
                ডোনেশন
              </a>
              
              <div class="border-t border-gray-200 pt-4 mt-4">
                <template v-if="$page.props.auth && $page.props.auth.user">
                  <div class="px-4 py-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg mb-3">
                    <div class="flex items-center space-x-3">
                      <div class="w-8 h-8 bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] rounded-full flex items-center justify-center">
                        <span class="text-white text-xs font-semibold">{{ $page.props.auth.user.name.charAt(0) }}</span>
                      </div>
                      <div>
                        <p class="text-sm font-medium text-gray-900">{{ $page.props.auth.user.name }}</p>
                        <p class="text-xs text-gray-500">{{ isAdmin() ? '🔧 Admin Account' : '👨‍🎓 Student Account' }}</p>
                      </div>
                    </div>
                  </div>
                  <Link 
                    :href="getDashboardRoute()" 
                    class="flex items-center px-4 py-3 text-gray-700 hover:text-[#5f5fcd] hover:bg-gray-50 rounded-lg transition-colors"
                  >
                    <UserIcon class="w-5 h-5 mr-3" />
                    {{ isAdmin() ? 'Admin Dashboard' : 'Student Dashboard' }}
                  </Link>
                  <Link 
                    :href="route('frontend.student.dashboard')" 
                    class="flex items-center px-4 py-3 text-gray-700 hover:text-[#5f5fcd] hover:bg-gray-50 rounded-lg transition-colors"
                  >
                    <BookOpenIcon class="w-5 h-5 mr-3" />
                    My Courses
                  </Link>
                  <button 
                    @click="logout"
                    class="flex items-center w-full px-4 py-3 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors"
                  >
                    <LogOutIcon class="w-5 h-5 mr-3" />
                    Logout
                  </button>
                </template>
                <template v-else>
                  <Link 
                    :href="route('login')" 
                    class="flex items-center px-4 py-3 text-gray-700 hover:text-[#5f5fcd] hover:bg-gradient-to-r hover:from-[#5f5fcd]/10 hover:to-[#2d5a27]/10 rounded-xl transition-all duration-300 border border-transparent hover:border-[#5f5fcd]/30"
                  >
                    <LogInIcon class="w-5 h-5 mr-3 transition-transform duration-300 group-hover:scale-110" />
                    <span class="font-medium">লগইন</span>
                  </Link>
                  <Link 
                    :href="route('register')" 
                    class="group flex items-center mx-4 my-2 bg-gradient-to-r from-[#5f5fcd] via-[#2d5a27] to-[#d4a574] text-white px-4 py-3 rounded-xl text-sm font-semibold relative overflow-hidden transition-all duration-300 transform hover:scale-105"
                  >
                    <!-- Animated background overlay -->
                    <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/10 to-white/0 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                    <UserPlusIcon class="w-5 h-5 mr-3 relative z-10 transition-transform duration-300 group-hover:scale-110" />
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
      <div class="divider-islamic max-w-4xl mx-auto my-16 relative">
        <div class="absolute inset-0 flex items-center justify-center">
          <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-lg">
            <div class="w-8 h-8 bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] rounded-full flex items-center justify-center">
              <span class="text-white text-sm font-bold">ই</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Enhanced Footer -->
      <footer class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white relative overflow-hidden">
        <!-- Footer Background Pattern -->
        <div class="absolute inset-0 opacity-5">
          <svg class="w-full h-full" viewBox="0 0 1200 400" xmlns="http://www.w3.org/2000/svg">
            <defs>
              <pattern id="footerPattern" x="0" y="0" width="60" height="60" patternUnits="userSpaceOnUse">
                <path d="M30 0 L60 30 L30 60 L0 30 Z" fill="none" stroke="#d4a574" stroke-width="1"/>
                <circle cx="30" cy="30" r="15" fill="none" stroke="#5f5fcd" stroke-width="1"/>
              </pattern>
            </defs>
            <rect width="1200" height="400" fill="url(#footerPattern)" />
          </svg>
        </div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-16">
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-8 gap-6 sm:gap-8 lg:gap-12">
            <!-- Enhanced Brand Section -->
            <div class="sm:col-span-2 lg:col-span-2">
              <div class="flex items-center space-x-3 sm:space-x-4 mb-4 sm:mb-6">
                <div class="relative">
                  <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] rounded-xl flex items-center justify-center shadow-islamic-lg">
                    <span class="text-white font-bold text-lg sm:text-2xl">ই</span>
                  </div>
                  <div class="absolute -top-1 -right-1 sm:-top-2 sm:-right-2 w-4 h-4 sm:w-6 sm:h-6 bg-[#d4a574] rounded-full animate-pulse shadow-lg"></div>
                </div>
                <div>
                  <h2 class="text-base sm:text-lg font-semibold text-gray-200">ইকরা অনলাইন একাডেমি</h2>
                </div>
              </div>
              <p class="text-gray-300 mb-4 sm:mb-6 leading-relaxed text-sm sm:text-base lg:text-lg">
                ইসলামিক শিক্ষায় আধুনিক প্রযুক্তির সমন্বয়ে গড়ে উঠেছে ইকরা অনলাইন একাডেমি। 
                কুরআন, হাদিস ও ইসলামিক জ্ঞানচর্চায় আমরা আপনার সাথে রয়েছি।
              </p>
              
              <!-- Enhanced Social Links -->
              <div class="flex space-x-3 sm:space-x-4">
                <a href="https://www.facebook.com/IqraOA" target="_blank" rel="noopener noreferrer" class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] rounded-lg flex items-center justify-center text-white hover:shadow-islamic-lg transition-all duration-200 transform hover:scale-110">
                  <FacebookIcon class="w-4 h-4 sm:w-5 sm:h-5" />
                </a>
                <a href="#" class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-[#d4a574] to-[#b8945f] rounded-lg flex items-center justify-center text-white hover:shadow-islamic-lg transition-all duration-200 transform hover:scale-110">
                  <YoutubeIcon class="w-4 h-4 sm:w-5 sm:h-5" />
                </a>
                <a href="#" class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-[#2d5a27] to-[#1f3e1b] rounded-lg flex items-center justify-center text-white hover:shadow-islamic-lg transition-all duration-200 transform hover:scale-110">
                  <TwitterIcon class="w-4 h-4 sm:w-5 sm:h-5" />
                </a>
                <a href="#" class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-[#5f5fcd] to-[#4a4aa6] rounded-lg flex items-center justify-center text-white hover:shadow-islamic-lg transition-all duration-200 transform hover:scale-110">
                  <InstagramIcon class="w-4 h-4 sm:w-5 sm:h-5" />
                </a>
              </div>
            </div>

            <!-- Quick Links -->
            <div class="lg:col-span-2">
              <h3 class="text-lg sm:text-xl font-semibold mb-3 sm:mb-4 lg:mb-6 text-[#d4a574] flex items-center">
                <LinkIcon class="w-4 h-4 sm:w-5 sm:h-5 mr-2" />
                দ্রুত লিঙ্ক
              </h3>
              <ul class="space-y-3">
                <li>
                  <Link :href="route('frontend.courses.index')" class="text-gray-300 hover:text-white transition-colors flex items-center group">
                    <ArrowRightIcon class="w-3 h-3 mr-2 group-hover:translate-x-1 transition-transform" />
                    কোর্সসমূহ
                  </Link>
                </li>
                <li>
                  <Link :href="route('frontend.about')" class="text-gray-300 hover:text-white transition-colors flex items-center group">
                    <ArrowRightIcon class="w-3 h-3 mr-2 group-hover:translate-x-1 transition-transform" />
                    আমাদের সম্পর্কে
                  </Link>
                </li>
              </ul>
            </div>

            <!-- Student Resources -->
            <div class="lg:col-span-2">
              <h3 class="text-lg sm:text-xl font-semibold mb-3 sm:mb-4 lg:mb-6 text-[#d4a574] flex items-center">
                <BookOpenIcon class="w-4 h-4 sm:w-5 sm:h-5 mr-2" />
                শিক্ষার্থী রিসোর্স
              </h3>
              <ul class="space-y-3">
                <li>
                  <Link :href="route('register')" class="text-gray-300 hover:text-white transition-colors flex items-center group">
                    <ArrowRightIcon class="w-3 h-3 mr-2 group-hover:translate-x-1 transition-transform" />
                    নিবন্ধন
                  </Link>
                </li>
                <li>
                  <Link :href="route('frontend.student.dashboard')" class="text-gray-300 hover:text-white transition-colors flex items-center group">
                    <ArrowRightIcon class="w-3 h-3 mr-2 group-hover:translate-x-1 transition-transform" />
                    ড্যাশবোর্ড
                  </Link>
                </li>
              </ul>
            </div>

            <!-- Enhanced Contact Info -->
            <div class="sm:col-span-2 lg:col-span-2">
              <h3 class="text-lg sm:text-xl font-semibold mb-3 sm:mb-4 lg:mb-6 text-[#d4a574] flex items-center">
                <MessageCircleIcon class="w-4 h-4 sm:w-5 sm:h-5 mr-2" />
                যোগাযোগ
              </h3>
              <ul class="space-y-3 sm:space-y-4 text-gray-300">
                <li class="flex items-start space-x-2 sm:space-x-3 group">
                  <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                    <PhoneIcon class="w-3 h-3 sm:w-4 sm:h-4 text-white" />
                  </div>
                  <div>
                    <span class="block font-medium text-sm sm:text-base">01750-469027</span>
                    <span class="text-xs sm:text-sm text-gray-400">সোম-শুক্র, সকাল ৯টা-সন্ধ্যা ৬টা</span>
                  </div>
                </li>
                <li class="flex items-start space-x-2 sm:space-x-3 group">
                  <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-[#d4a574] to-[#b8945f] rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                    <MailIcon class="w-3 h-3 sm:w-4 sm:h-4 text-white" />
                  </div>
                  <div>
                    <span class="block font-medium text-sm sm:text-base">iqraoa313@gmail.com</span>
                    <span class="text-xs sm:text-sm text-gray-400">২৪ ঘণ্টার মধ্যে উত্তর</span>
                  </div>
                </li>
                <li class="flex items-start space-x-2 sm:space-x-3 group">
                  <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-[#2d5a27] to-[#1f3e1b] rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                    <MapPinIcon class="w-3 h-3 sm:w-4 sm:h-4 text-white" />
                  </div>
                  <div>
                    <span class="block font-medium text-sm sm:text-base">হাউজিং এস্টেট, চট্টগ্রাম, বাংলাদেশ</span>
                  </div>
                </li>
              </ul>
            </div>
          </div>

          <!-- Enhanced Bottom Border -->
          <div class="border-t border-gray-800 mt-6 sm:mt-8 lg:mt-12 pt-4 sm:pt-6 lg:pt-8">
            <div class="flex flex-col lg:flex-row justify-between items-center space-y-3 sm:space-y-4 lg:space-y-0">
              <div class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-3 lg:space-x-4 text-center sm:text-left">
                <p class="text-gray-400 text-xs sm:text-sm">
                  © ২০২৫ ইকরা অনলাইন একাডেমি। সর্বস্বত্ব সংরক্ষিত।
                </p>
                <div class="hidden sm:block w-1 h-1 bg-gray-600 rounded-full"></div>
                <p class="text-gray-400 text-xs sm:text-sm">
          Developed by 
          <a 
            href="https://www.pixelweblab.com/" 
            target="_blank" 
            rel="noopener noreferrer"
            class="text-[#5f5fcd] hover:text-[#2d5a27] transition-colors duration-200 font-medium"
          >
            PixelWeblab
          </a>
        </p>
              </div>
              <div class="flex flex-wrap justify-center lg:justify-end gap-3 sm:gap-4 lg:gap-6 text-xs sm:text-sm">
                <a href="#" class="text-gray-400 hover:text-white transition-colors">প্রাইভেসি পলিসি</a>
                <a href="#" class="text-gray-400 hover:text-white transition-colors">ব্যবহারের শর্তাবলী</a>
                <a href="#" class="text-gray-400 hover:text-white transition-colors">রিফান্ড পলিসি</a>
                <a href="#" class="text-gray-400 hover:text-white transition-colors">কুকি পলিসি</a>
              </div>
            </div>
          </div>
        </div>
      </footer>

      <!-- Toast Container -->
      <ToastContainer 
        :toasts="toasts" 
        :onRemove="removeToast" 
      />

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
import { ref, onMounted, onUnmounted } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import {
  MenuIcon,
  XIcon,
  ChevronDownIcon,
  PhoneIcon,
  MailIcon,
  MapPinIcon,
  FacebookIcon,
  YoutubeIcon,
  TwitterIcon,
  InstagramIcon,
  UserIcon,
  LogOutIcon,
  ArrowRightIcon,
  BookOpenIcon,
  MessageCircleIcon,
  HomeIcon,
  InfoIcon,
  LogInIcon,
  UserPlusIcon,
  SendIcon,
  LinkIcon,
  HelpCircleIcon,
  HeartIcon
} from 'lucide-vue-next'
import { ToastContainer, useToast } from '@/components/ui/toast'
import NotificationToast from '@/components/Frontend/NotificationToast.vue'
import ErrorBoundary from '@/components/Frontend/ErrorBoundary.vue'

// Define props for receiving data
defineProps<{
  title?: string
}>()

// Mobile menu state
const mobileMenuOpen = ref(false)

// User dropdown state
const userDropdownOpen = ref(false)

// Notification toast ref
const notificationToast = ref()

// Toast system
const { toasts, removeToast } = useToast()

// Page composable for accessing auth data
const page = usePage()

// Search query state
const searchQuery = ref('')
const searching = ref(false)

// Check if user is admin (based on current route or user data)
const isAdmin = () => {
  // Check if we're on an admin route
  const currentUrl = window.location.pathname
  if (currentUrl.startsWith('/admin')) {
    return true
  }
  
  // Check if user has admin role (using any type to avoid TypeScript issues)
  const user = page.props.auth.user as any
  if (!user || !user.roles) {
    return false
  }
  
  // Check if user has an active admin role
  const hasAdminRole = user.roles.some((role: any) => role.role_type === 'admin' && role.is_active)
  
  return hasAdminRole
}

// Get appropriate dashboard route based on user type
const getDashboardRoute = () => {
  if (isAdmin()) {
    // Assuming the Filament admin panel is at '/admin'
    return '/admin';
  }
  return route('frontend.student.dashboard');
}

// Get appropriate logout route based on user type
const getLogoutRoute = () => {
  if (isAdmin()) {
    return route('admin.logout')
  }
  return route('logout')
}

// Logout function
const logout = () => {
  router.post(getLogoutRoute())
}

// Close dropdown when clicking outside
const handleClickOutside = (event: Event) => {
  const target = event.target as Element
  if (!target.closest('.relative')) {
    userDropdownOpen.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})

function onSearchSubmit() {
  const query = searchQuery.value.trim()
  if (!query) return
  // Prevent redundant navigation
  const currentUrl = window.location.pathname + window.location.search
  const targetUrl = route('frontend.courses.index', { search: query })
  if (currentUrl === targetUrl) return
  searching.value = true
  router.get(route('frontend.courses.index'), { search: query }, {
    onFinish: () => {
      searching.value = false
      // Optionally clear the search field after navigation
      // searchQuery.value = ''
    }
  })
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
    router.visit(route('frontend.home', { scroll: 'donation' }))
  }
}
</script>

<style scoped>
/* Additional Islamic-inspired animations */
@keyframes float {
  0%, 100% {
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
  background: linear-gradient(90deg, transparent 0%, rgba(95, 95, 205, 0.3) 25%, rgba(212, 165, 116, 0.3) 50%, rgba(45, 90, 39, 0.3) 75%, transparent 100%);
  border-radius: 2px;
}

.donation-highlight {
  animation: donationFlash 1.2s;
}
@keyframes donationFlash {
  0%   { box-shadow: 0 0 0 0 #5f5fcd66; }
  50%  { box-shadow: 0 0 24px 8px #5f5fcd99; }
  100% { box-shadow: 0 0 0 0 #5f5fcd00; }
}
</style> 
