<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 pattern-dots">
    <!-- Navigation Header -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-200 shadow-islamic">
      <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <!-- Logo -->
          <div class="flex items-center">
            <Link :href="route('frontend.home')" class="flex items-center space-x-3">
              <div class="relative">
                <div class="w-10 h-10 bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] rounded-lg flex items-center justify-center shadow-islamic">
                  <span class="text-white font-bold text-lg">ই</span>
                </div>
                <div class="absolute -top-1 -right-1 w-3 h-3 bg-[#d4a574] rounded-full animate-pulse"></div>
              </div>
              <div>
                <h1 class="text-xl font-bold text-gradient-islamic">ইকরা</h1>
              </div>
            </Link>
          </div>

          <!-- Desktop Navigation -->
          <div class="hidden md:flex items-center space-x-8">
            <Link 
              :href="route('frontend.home')" 
              class="text-gray-700 hover:text-[#5f5fcd] px-3 py-2 rounded-md text-sm font-medium transition-colors"
              :class="{ 'text-[#5f5fcd] bg-gray-100': $page.component === 'Frontend/HomePage' }"
            >
              হোম
            </Link>
            <Link 
              :href="route('frontend.courses.index')" 
              class="text-gray-700 hover:text-[#5f5fcd] px-3 py-2 rounded-md text-sm font-medium transition-colors"
              :class="{ 'text-[#5f5fcd] bg-gray-100': $page.component === 'Frontend/CoursesPage' }"
            >
              কোর্সসমূহ
            </Link>
            <Link 
              :href="route('frontend.about')" 
              class="text-gray-700 hover:text-[#5f5fcd] px-3 py-2 rounded-md text-sm font-medium transition-colors"
            >
              আমাদের সম্পর্কে
            </Link>
            <Link 
              :href="route('frontend.contact')" 
              class="text-gray-700 hover:text-[#5f5fcd] px-3 py-2 rounded-md text-sm font-medium transition-colors"
            >
              যোগাযোগ
            </Link>
          </div>

          <!-- Auth Buttons -->
          <div class="hidden md:flex items-center space-x-4">
            <template v-if="$page.props.auth.user">
              <!-- User is logged in -->
              <div class="relative">
                <button 
                  @click="userDropdownOpen = !userDropdownOpen"
                  class="flex items-center space-x-2 text-gray-700 hover:text-[#5f5fcd] transition-colors"
                >
                  <div class="w-8 h-8 bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] rounded-full flex items-center justify-center">
                    <span class="text-white text-sm font-semibold">{{ $page.props.auth.user.name.charAt(0) }}</span>
                  </div>
                  <span class="text-sm font-medium">{{ $page.props.auth.user.name }}</span>
                  <ChevronDownIcon class="w-4 h-4 transition-transform" :class="{ 'rotate-180': userDropdownOpen }" />
                </button>
                
                <!-- Dropdown Menu -->
                <div 
                  v-show="userDropdownOpen" 
                  class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-islamic-lg border border-gray-200 py-2 z-50"
                >
                  <!-- User Type Indicator -->
                  <div class="px-4 py-2 text-xs text-gray-500 bg-gray-50 border-b border-gray-100">
                    {{ isAdmin() ? '🔧 অ্যাডমিন অ্যাকাউন্ট' : '👨‍🎓 ছাত্র অ্যাকাউন্ট' }}
                  </div>
                  
                  <Link 
                    :href="getDashboardRoute()" 
                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#5f5fcd] transition-colors"
                  >
                    <UserIcon class="w-4 h-4 mr-3" />
                    {{ isAdmin() ? 'অ্যাডমিন ড্যাশবোর্ড' : 'ছাত্র ড্যাশবোর্ড' }}
                  </Link>
                  <div class="border-t border-gray-200 my-2"></div>
                  <button 
                    @click="logout"
                    class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors"
                  >
                    <LogOutIcon class="w-4 h-4 mr-3" />
                    লগআউট
                  </button>
                </div>
              </div>
            </template>
            <template v-else>
              <!-- User is not logged in -->
              <Link 
                :href="route('login')" 
                class="text-gray-700 hover:text-[#5f5fcd] px-4 py-2 text-sm font-medium transition-colors"
              >
                লগইন
              </Link>
              <Link 
                :href="route('register')" 
                class="bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] text-white px-6 py-2 rounded-lg text-sm font-medium hover:shadow-islamic-lg transition-all duration-200 transform hover:scale-105"
              >
                রেজিস্ট্রেশন
              </Link>
            </template>
          </div>

          <!-- Mobile menu button -->
          <div class="md:hidden">
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-500 hover:text-gray-700 p-2">
              <MenuIcon v-if="!mobileMenuOpen" class="w-6 h-6" />
              <XIcon v-else class="w-6 h-6" />
            </button>
          </div>
        </div>

        <!-- Mobile Navigation -->
        <div v-show="mobileMenuOpen" class="md:hidden border-t border-gray-200 py-4">
          <div class="space-y-2">
            <Link 
              :href="route('frontend.home')" 
              class="block px-3 py-2 text-gray-700 hover:text-[#5f5fcd] hover:bg-gray-50 rounded-md transition-colors"
            >
              হোম
            </Link>
            <Link 
              :href="route('frontend.courses.index')" 
              class="block px-3 py-2 text-gray-700 hover:text-[#5f5fcd] hover:bg-gray-50 rounded-md transition-colors"
            >
              কোর্সসমূহ
            </Link>
            <Link 
              :href="route('frontend.about')" 
              class="block px-3 py-2 text-gray-700 hover:text-[#5f5fcd] hover:bg-gray-50 rounded-md transition-colors"
            >
              আমাদের সম্পর্কে
            </Link>
            <Link 
              :href="route('frontend.contact')" 
              class="block px-3 py-2 text-gray-700 hover:text-[#5f5fcd] hover:bg-gray-50 rounded-md transition-colors"
            >
              যোগাযোগ
            </Link>
            
            <div class="border-t border-gray-200 pt-4 mt-4">
              <template v-if="$page.props.auth.user">
                <div class="px-3 py-2">
                  <p class="text-sm text-gray-500">স্বাগতম, {{ $page.props.auth.user.name }}</p>
                  <p class="text-xs text-gray-400 mt-1">{{ isAdmin() ? '🔧 অ্যাডমিন অ্যাকাউন্ট' : '👨‍🎓 ছাত্র অ্যাকাউন্ট' }}</p>
                </div>
                <Link 
                  :href="getDashboardRoute()" 
                  class="block px-3 py-2 text-gray-700 hover:text-[#5f5fcd] hover:bg-gray-50 rounded-md transition-colors"
                >
                  {{ isAdmin() ? 'অ্যাডমিন ড্যাশবোর্ড' : 'ছাত্র ড্যাশবোর্ড' }}
                </Link>
                <button 
                  @click="logout"
                  class="block w-full text-left px-3 py-2 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-md transition-colors"
                >
                  লগআউট
                </button>
              </template>
              <template v-else>
                <Link 
                  :href="route('login')" 
                  class="block px-3 py-2 text-gray-700 hover:text-[#5f5fcd] hover:bg-gray-50 rounded-md transition-colors"
                >
                  লগইন
                </Link>
                <Link 
                  :href="route('register')" 
                  class="block mx-3 my-2 bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] text-white px-4 py-2 rounded-lg text-sm font-medium text-center"
                >
                  রেজিস্ট্রেশন
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

    <!-- Islamic Divider -->
    <div class="divider-islamic max-w-4xl mx-auto my-16"></div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
          <!-- Brand Section -->
          <div class="md:col-span-2">
            <div class="flex items-center space-x-3 mb-4">
              <div class="w-12 h-12 bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] rounded-lg flex items-center justify-center">
                <span class="text-white font-bold text-xl">ই</span>
              </div>
              <div>
                <h2 class="text-2xl font-bold text-gradient-islamic">ইকরা অনলাইন একাডেমি</h2>
                <p class="text-gray-400 text-sm">Islamic Learning Made Simple</p>
              </div>
            </div>
            <p class="text-gray-300 mb-4 leading-relaxed">
              ইসলামিক শিক্ষায় আধুনিক প্রযুক্তির সমন্বয়ে গড়ে উঠেছে ইকরা অনলাইন একাডেমি। 
              কুরআন, হাদিস ও ইসলামিক জ্ঞানচর্চায় আমরা আপনার সাথে রয়েছি।
            </p>
            <div class="flex space-x-4">
              <a href="#" class="text-gray-400 hover:text-[#d4a574] transition-colors">
                <FacebookIcon class="w-5 h-5" />
              </a>
              <a href="#" class="text-gray-400 hover:text-[#d4a574] transition-colors">
                <YoutubeIcon class="w-5 h-5" />
              </a>
              <a href="#" class="text-gray-400 hover:text-[#d4a574] transition-colors">
                <TwitterIcon class="w-5 h-5" />
              </a>
            </div>
          </div>

          <!-- Quick Links -->
          <div>
            <h3 class="text-lg font-semibold mb-4 text-[#d4a574]">দ্রুত লিঙ্ক</h3>
            <ul class="space-y-2">
              <li><Link :href="route('frontend.courses.index')" class="text-gray-300 hover:text-white transition-colors">কোর্সসমূহ</Link></li>
              <li><a href="#" class="text-gray-300 hover:text-white transition-colors">ইন্সট্রাক্টর</a></li>
              <li><a href="#" class="text-gray-300 hover:text-white transition-colors">সার্টিফিকেট</a></li>
              <li><a href="#" class="text-gray-300 hover:text-white transition-colors">ব্লগ</a></li>
            </ul>
          </div>

          <!-- Contact Info -->
          <div>
            <h3 class="text-lg font-semibold mb-4 text-[#d4a574]">যোগাযোগ</h3>
            <ul class="space-y-3 text-gray-300">
              <li class="flex items-center space-x-2">
                <PhoneIcon class="w-4 h-4 text-[#d4a574]" />
                <span>+৮৮০ ১২৩৪ ৫৬৭৮৯০</span>
              </li>
              <li class="flex items-center space-x-2">
                <MailIcon class="w-4 h-4 text-[#d4a574]" />
                <span>info@iqra-academy.com</span>
              </li>
              <li class="flex items-start space-x-2">
                <MapPinIcon class="w-4 h-4 text-[#d4a574] mt-1" />
                <span class="text-sm">ঢাকা, বাংলাদেশ</span>
              </li>
            </ul>
          </div>
        </div>

        <!-- Bottom Border -->
        <div class="border-t border-gray-800 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
          <p class="text-gray-400 text-sm">
            © ২০২৫ ইকরা অনলাইন একাডেমি। সর্বস্বত্ব সংরক্ষিত।
          </p>
          <div class="flex space-x-6 mt-4 md:mt-0 text-sm">
            <a href="#" class="text-gray-400 hover:text-white transition-colors">প্রাইভেসি পলিসি</a>
            <a href="#" class="text-gray-400 hover:text-white transition-colors">ব্যবহারের শর্তাবলী</a>
            <a href="#" class="text-gray-400 hover:text-white transition-colors">রিফান্ড পলিসি</a>
          </div>
        </div>
      </div>
    </footer>

    <!-- Toast Container -->
    <ToastContainer 
      :toasts="toasts" 
      :onRemove="removeToast" 
    />
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
  UserIcon,
  LogOutIcon
} from 'lucide-vue-next'
import { ToastContainer, useToast } from '@/components/ui/toast'

// Define props for receiving data
defineProps<{
  title?: string
}>()

// Mobile menu state
const mobileMenuOpen = ref(false)

// User dropdown state
const userDropdownOpen = ref(false)

// Toast system
const { toasts, removeToast } = useToast()

// Page composable for accessing auth data
const page = usePage()

// Check if user is admin (based on current route or user data)
const isAdmin = () => {
  // Check if we're on an admin route
  const currentUrl = window.location.pathname
  if (currentUrl.startsWith('/admin')) {
    return true
  }
  
  // Check if user has admin role (using any type to avoid TypeScript issues)
  const user = page.props.auth.user as any
  return user?.roles?.some((role: any) => role.role_type === 'admin' && role.is_active) || false
}

// Get appropriate dashboard route based on user type
const getDashboardRoute = () => {
  if (isAdmin()) {
    return route('admin.dashboard')
  }
  return route('frontend.student.dashboard')
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
</style> 
