<template>
  <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
    <!-- Desktop Header Layout -->
    <div class="hidden lg:block">
      <div class="flex h-16 items-center">
        <!-- Left section - Logo aligned with sidebar content -->
        <div class="w-64 flex-shrink-0 px-6 border-r border-gray-200">
          <Link href="/admin" class="flex items-center space-x-3">
            <div class="w-9 h-9 bg-gradient-to-br from-[#2d5a27] to-[#5f5fcd] rounded-lg flex items-center justify-center">
              <span class="text-white font-bold text-lg">ই</span>
            </div>
            <span class="text-xl font-bold text-gray-900">
              ইকরা অ্যাডমিন
            </span>
          </Link>
        </div>

        <!-- Right section - Search and user menu -->
        <div class="flex-1 flex items-center justify-end px-6 space-x-4">
          <!-- Search -->
          <div class="hidden md:block">
            <div class="relative">
              <Icon 
                name="search" 
                class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
              />
              <input
                type="text"
                placeholder="Search..."
                class="w-64 rounded-lg border border-gray-200 bg-gray-50 pl-10 pr-4 py-2.5 text-sm text-gray-900 placeholder-gray-500 focus:bg-white focus:border-[#5f5fcd] focus:ring-2 focus:ring-[#5f5fcd]/20 transition-all"
              />
            </div>
          </div>

          <!-- User menu -->
          <div class="relative" ref="userDropdownRef">
            <button 
              @click.stop="userDropdownOpen = !userDropdownOpen"
              class="flex items-center space-x-3 p-2 hover:bg-gray-100 rounded-lg transition-colors"
            >
              <div class="w-8 h-8 bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] rounded-full flex items-center justify-center">
                <span class="text-white text-sm font-semibold">{{ userInitials }}</span>
              </div>
              <span class="hidden xl:block text-sm font-medium text-gray-700">{{ user?.name }}</span>
              <Icon name="chevronDown" class="h-4 w-4 text-gray-500 transition-transform" :class="{ 'rotate-180': userDropdownOpen }" />
            </button>
            
            <!-- Dropdown Menu -->
            <Transition
              enter-active-class="transition ease-out duration-100"
              enter-from-class="transform opacity-0 scale-95"
              enter-to-class="transform opacity-100 scale-100"
              leave-active-class="transition ease-in duration-75"
              leave-from-class="transform opacity-100 scale-100"
              leave-to-class="transform opacity-0 scale-95"
            >
              <div 
                v-show="userDropdownOpen" 
                class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50"
                @click.stop
              >
                <!-- User Type Indicator -->
                <div class="px-4 py-2 text-xs text-gray-500 bg-gray-50 border-b border-gray-100">
                  🔧 অ্যাডমিন অ্যাকাউন্ট
                </div>
                
                <!-- User Email -->
                <div class="px-4 py-2 text-sm text-gray-600 border-b border-gray-100">
                  {{ user?.email }}
                </div>
                
                <Link 
                  :href="route('admin.settings')" 
                  class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#5f5fcd] transition-colors"
                  @click="userDropdownOpen = false"
                >
                  <Icon name="settings" class="w-4 h-4 mr-3" />
                  Settings
                </Link>
                
                <div class="border-t border-gray-200 my-2"></div>
                
                <button 
                  @click="logout"
                  class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors text-left"
                >
                  <Icon name="logOut" class="w-4 h-4 mr-3" />
                  Logout
                </button>
              </div>
            </Transition>
          </div>
        </div>
      </div>
    </div>

    <!-- Mobile Header Layout -->
    <div class="lg:hidden">
      <div class="px-4 sm:px-6">
        <div class="flex h-16 items-center justify-between">
          <!-- Mobile menu button -->
          <div class="flex items-center">
            <button
              @click="toggleSidebar"
              type="button"
              class="inline-flex items-center justify-center rounded-md p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-600 transition-colors mr-3"
            >
              <Icon name="menu" class="h-6 w-6" />
            </button>

            <!-- Mobile Logo -->
            <Link href="/admin" class="flex items-center space-x-2">
              <div class="w-8 h-8 bg-gradient-to-br from-[#2d5a27] to-[#5f5fcd] rounded-lg flex items-center justify-center">
                <span class="text-white font-bold text-sm">ই</span>
              </div>
              <span class="text-lg font-bold text-gray-900">
                ইকরা অ্যাডমিন
              </span>
            </Link>
          </div>

          <!-- Mobile right side -->
          <div class="flex items-center">
            <!-- Mobile user menu -->
            <div class="relative" ref="mobileDropdownRef">
              <button 
                @click.stop="mobileDropdownOpen = !mobileDropdownOpen"
                class="flex items-center space-x-2 p-2 hover:bg-gray-100 rounded-lg transition-colors"
              >
                <div class="w-8 h-8 bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] rounded-full flex items-center justify-center">
                  <span class="text-white text-sm font-semibold">{{ userInitials }}</span>
                </div>
                <Icon name="chevronDown" class="h-4 w-4 text-gray-500 transition-transform" :class="{ 'rotate-180': mobileDropdownOpen }" />
              </button>
              
              <!-- Mobile Dropdown Menu -->
              <Transition
                enter-active-class="transition ease-out duration-100"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
              >
                <div 
                  v-show="mobileDropdownOpen" 
                  class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50"
                  @click.stop
                >
                  <!-- User Type Indicator -->
                  <div class="px-4 py-2 text-xs text-gray-500 bg-gray-50 border-b border-gray-100">
                    🔧 অ্যাডমিন অ্যাকাউন্ট
                  </div>
                  
                  <!-- User Email -->
                  <div class="px-4 py-2 text-sm text-gray-600 border-b border-gray-100">
                    {{ user?.email }}
                  </div>
                  
                  <Link 
                    :href="route('admin.settings')" 
                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#5f5fcd] transition-colors"
                    @click="mobileDropdownOpen = false"
                  >
                    <Icon name="settings" class="w-4 h-4 mr-3" />
                    Settings
                  </Link>
                  
                  <div class="border-t border-gray-200 my-2"></div>
                  
                  <button 
                    @click="logout"
                    class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors text-left"
                  >
                    <Icon name="logOut" class="w-4 h-4 mr-3" />
                    Logout
                  </button>
                </div>
              </Transition>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { usePage, Link, router } from '@inertiajs/vue3'
import { getInitials } from '@/lib/utils'
import Icon from '@/components/Icon.vue'

const page = usePage()
const user = computed(() => page.props.auth?.user)
const userInitials = computed(() => getInitials(user.value?.name || ''))

// Dropdown states and refs
const userDropdownOpen = ref(false)
const mobileDropdownOpen = ref(false)
const userDropdownRef = ref(null)
const mobileDropdownRef = ref(null)

const toggleSidebar = () => {
  // Toggle sidebar visibility on mobile
  document.body.classList.toggle('sidebar-open')
}

const logout = () => {
  userDropdownOpen.value = false
  mobileDropdownOpen.value = false
  router.post(route('admin.logout'))
}

// Close dropdowns when clicking outside
const handleClickOutside = (event) => {
  // Check if click is outside user dropdown
  if (userDropdownRef.value && !userDropdownRef.value.contains(event.target)) {
    userDropdownOpen.value = false
  }
  
  // Check if click is outside mobile dropdown
  if (mobileDropdownRef.value && !mobileDropdownRef.value.contains(event.target)) {
    mobileDropdownOpen.value = false
  }
}

// Close dropdowns on escape key
const handleEscKey = (event) => {
  if (event.key === 'Escape') {
    userDropdownOpen.value = false
    mobileDropdownOpen.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
  document.addEventListener('keydown', handleEscKey)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
  document.removeEventListener('keydown', handleEscKey)
})
</script> 