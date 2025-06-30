<template>
  <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
    <!-- Desktop Header Layout -->
    <div class="hidden lg:block">
      <div class="flex h-16 items-center">
        <!-- Left section - Logo aligned with sidebar content -->
        <div class="w-64 flex-shrink-0 px-6 border-r border-gray-200">
          <Link href="/admin" class="flex items-center space-x-3">
            <div class="w-9 h-9 bg-gradient-to-br from-red-600 to-[#5f5fcd] rounded-lg flex items-center justify-center">
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
          <DropdownMenu>
            <DropdownMenuTrigger asChild>
              <Button variant="ghost" size="sm" class="flex items-center space-x-3 p-2 hover:bg-gray-100 rounded-lg">
                <Avatar class="h-8 w-8">
                  <AvatarImage :src="user?.avatar || ''" />
                  <AvatarFallback class="bg-gradient-to-br from-[#5f5fcd] to-red-600 text-white">{{ userInitials }}</AvatarFallback>
                </Avatar>
                <span class="hidden xl:block text-sm font-medium text-gray-700">{{ user?.name }}</span>
                <Icon name="chevron-down" class="h-4 w-4 text-gray-500" />
              </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end" class="w-56">
              <DropdownMenuLabel class="text-gray-600">{{ user?.email }}</DropdownMenuLabel>
              <DropdownMenuSeparator />
              <DropdownMenuItem asChild class="text-gray-700 hover:bg-gray-50 cursor-pointer">
                <Link :href="route('admin.settings')">
                  <Icon name="settings" class="mr-3 h-4 w-4" />
                  Settings
                </Link>
              </DropdownMenuItem>
              <DropdownMenuSeparator />
              <DropdownMenuItem @click="logout" class="text-red-600 hover:bg-red-50 cursor-pointer">
                <Icon name="log-out" class="mr-3 h-4 w-4" />
                Logout
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>
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
              <div class="w-8 h-8 bg-gradient-to-br from-red-600 to-[#5f5fcd] rounded-lg flex items-center justify-center">
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
            <DropdownMenu>
              <DropdownMenuTrigger asChild>
                <Button variant="ghost" size="sm" class="flex items-center space-x-2 p-2 hover:bg-gray-100 rounded-lg">
                  <Avatar class="h-8 w-8">
                    <AvatarImage :src="user?.avatar || ''" />
                    <AvatarFallback class="bg-gradient-to-br from-[#5f5fcd] to-red-600 text-white">{{ userInitials }}</AvatarFallback>
                  </Avatar>
                  <Icon name="chevron-down" class="h-4 w-4 text-gray-500" />
                </Button>
              </DropdownMenuTrigger>
              <DropdownMenuContent align="end" class="w-56">
                <DropdownMenuLabel class="text-gray-600">{{ user?.email }}</DropdownMenuLabel>
                <DropdownMenuSeparator />
                <DropdownMenuItem asChild class="text-gray-700 hover:bg-gray-50 cursor-pointer">
                  <Link :href="route('admin.settings')">
                    <Icon name="settings" class="mr-3 h-4 w-4" />
                    Settings
                  </Link>
                </DropdownMenuItem>
                <DropdownMenuSeparator />
                <DropdownMenuItem @click="logout" class="text-red-600 hover:bg-red-50 cursor-pointer">
                  <Icon name="log-out" class="mr-3 h-4 w-4" />
                  Logout
                </DropdownMenuItem>
              </DropdownMenuContent>
            </DropdownMenu>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { computed } from 'vue'
import { usePage, Link, router } from '@inertiajs/vue3'
import { useInitials } from '@/composables/useInitials'
import Icon from '@/components/Icon.vue'
import { Button } from '@/components/ui/button'
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'

const page = usePage()
const user = computed(() => page.props.auth?.user)
const userInitials = computed(() => useInitials(user.value?.name || ''))

const toggleSidebar = () => {
  // Toggle sidebar visibility on mobile
  document.body.classList.toggle('sidebar-open')
}

const logout = () => {
  router.post(route('admin.logout'))
}
</script> 