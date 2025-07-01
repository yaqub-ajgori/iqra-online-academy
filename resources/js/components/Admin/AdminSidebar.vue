<template>
  <!-- Desktop Sidebar -->
  <div class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col lg:pt-16">
    <div class="flex flex-grow flex-col overflow-y-auto bg-white border-r border-gray-200 shadow-sm">
      <div class="flex flex-col flex-grow">
        <!-- Navigation -->
        <nav class="flex-1 px-3 py-6 space-y-4">
          <!-- Dashboard -->
          <div class="space-y-1">
            <SidebarLink 
              :href="route('admin.dashboard')" 
              :active="$page.component === 'Admin/Dashboard'"
              icon="layoutDashboard"
            >
              Dashboard
            </SidebarLink>
          </div>

          <!-- Course Management -->
          <SidebarSection title="Course Management">
            <SidebarLink 
              :href="route('admin.courses.index')" 
              :active="$page.component === 'Admin/Courses/Index' || $page.component === 'Admin/Courses/Show' || $page.component === 'Admin/Courses/Builder'"
              icon="list"
            >
              All Courses
            </SidebarLink>
            <SidebarLink 
              :href="route('admin.courses.create')" 
              :active="$page.component === 'Admin/Courses/Create'"
              icon="plus"
            >
              Create Course
            </SidebarLink>
            <SidebarLink 
              :href="route('admin.categories.index')" 
              :active="$page.component.startsWith('Admin/Categories')"
              icon="folder"
            >
              Course Categories
            </SidebarLink>
          </SidebarSection>

          <!-- User Management -->
          <SidebarSection title="User Management">
            <SidebarLink 
              :href="route('admin.students.index')" 
              :active="$page.component.startsWith('Admin/Students')"
              icon="users"
            >
              Students
            </SidebarLink>
            <SidebarLink 
              :href="route('admin.teachers.index')" 
              :active="$page.component.startsWith('Admin/Teachers')"
              icon="graduationCap"
            >
              Teachers
            </SidebarLink>
          </SidebarSection>

          <!-- Enrollment & Payments -->
          <SidebarSection title="Enrollment & Payments">
            <SidebarLink 
              :href="route('admin.enrollments.index')" 
              :active="$page.component.startsWith('Admin/Enrollments')"
              icon="userCheck"
            >
              Enrollments
            </SidebarLink>
            <SidebarLink 
              :href="route('admin.payments.index')" 
              :active="$page.component.startsWith('Admin/Payments')"
              icon="creditCard"
            >
              Payments
            </SidebarLink>
          </SidebarSection>

          <!-- Settings -->
          <SidebarSection title="System">
            <SidebarLink 
              :href="route('admin.settings')" 
              :active="$page.component === 'Admin/Settings'"
              icon="settings"
            >
              Settings
            </SidebarLink>
          </SidebarSection>
        </nav>

        <!-- Sidebar Footer -->
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
          <div class="text-xs text-gray-500 text-center">
            <div class="font-semibold">Iqra Online Academy</div>
            <div class="mt-1">Admin Panel v1.0</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Mobile Sidebar -->
  <div class="lg:hidden">
    <!-- Overlay -->
    <div 
      class="fixed inset-0 z-40 bg-gray-900 bg-opacity-50 sidebar-overlay hidden transition-opacity duration-300"
      @click="closeSidebar"
    ></div>

    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white transform -translate-x-full transition-transform duration-300 ease-in-out sidebar-mobile shadow-2xl">
      <div class="flex flex-col h-full">
        <!-- Mobile Header -->
        <div class="flex items-center justify-between px-6 py-6 border-b border-gray-200 bg-gray-50">
          <Link href="/admin" class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-[#2d5a27] to-[#5f5fcd] rounded-xl flex items-center justify-center shadow-md">
              <span class="text-white font-bold text-lg">ই</span>
            </div>
            <span class="text-lg font-bold text-gray-900">
              ইকরা অ্যাডমিন
            </span>
          </Link>
          <button 
            @click="closeSidebar" 
            class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-200 rounded-lg transition-colors duration-200"
          >
            <Icon name="x" class="h-5 w-5" />
          </button>
        </div>
        
        <!-- Mobile Navigation -->
        <nav class="flex-1 px-3 py-4 space-y-4 overflow-y-auto">
          <!-- Dashboard -->
          <div class="space-y-1">
            <SidebarLink 
              :href="route('admin.dashboard')" 
              :active="$page.component === 'Admin/Dashboard'"
              icon="layoutDashboard"
              @click="closeSidebar"
            >
              Dashboard
            </SidebarLink>
          </div>

          <!-- Course Management -->
          <SidebarSection title="Course Management">
            <SidebarLink 
              :href="route('admin.courses.index')" 
              :active="$page.component === 'Admin/Courses/Index' || $page.component === 'Admin/Courses/Show' || $page.component === 'Admin/Courses/Builder'"
              icon="list"
              @click="closeSidebar"
            >
              All Courses
            </SidebarLink>
            <SidebarLink 
              :href="route('admin.courses.create')" 
              :active="$page.component === 'Admin/Courses/Create'"
              icon="plus"
              @click="closeSidebar"
            >
              Create Course
            </SidebarLink>
            <SidebarLink 
              :href="route('admin.categories.index')" 
              :active="$page.component.startsWith('Admin/Categories')"
              icon="folder"
              @click="closeSidebar"
            >
              Course Categories
            </SidebarLink>
          </SidebarSection>

          <!-- User Management -->
          <SidebarSection title="User Management">
            <SidebarLink 
              :href="route('admin.students.index')" 
              :active="$page.component.startsWith('Admin/Students')"
              icon="users"
              @click="closeSidebar"
            >
              Students
            </SidebarLink>
            <SidebarLink 
              :href="route('admin.teachers.index')" 
              :active="$page.component.startsWith('Admin/Teachers')"
              icon="graduationCap"
              @click="closeSidebar"
            >
              Teachers
            </SidebarLink>
          </SidebarSection>

          <!-- Enrollment & Payments -->
          <SidebarSection title="Enrollment & Payments">
            <SidebarLink 
              :href="route('admin.enrollments.index')" 
              :active="$page.component.startsWith('Admin/Enrollments')"
              icon="userCheck"
              @click="closeSidebar"
            >
              Enrollments
            </SidebarLink>
            <SidebarLink 
              :href="route('admin.payments.index')" 
              :active="$page.component.startsWith('Admin/Payments')"
              icon="creditCard"
              @click="closeSidebar"
            >
              Payments
            </SidebarLink>
          </SidebarSection>

          <!-- Settings -->
          <SidebarSection title="System">
            <SidebarLink 
              :href="route('admin.settings')" 
              :active="$page.component === 'Admin/Settings'"
              icon="settings"
              @click="closeSidebar"
            >
              Settings
            </SidebarLink>
          </SidebarSection>
        </nav>

        <!-- Mobile Footer -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
          <div class="text-xs text-gray-500 text-center">
            <div class="font-semibold">Iqra Online Academy</div>
            <div class="mt-1">Admin Panel v1.0</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import Icon from '@/components/Icon.vue'
import SidebarLink from './SidebarLink.vue'
import SidebarSection from './SidebarSection.vue'

// Mobile sidebar functionality
const closeSidebar = () => {
  const overlay = document.querySelector('.sidebar-overlay')
  const sidebar = document.querySelector('.sidebar-mobile')
  
  if (overlay && sidebar) {
    overlay.classList.add('hidden')
    sidebar.classList.add('-translate-x-full')
  }
}

// Listen for global sidebar events
onMounted(() => {
  // Handle mobile sidebar toggle
  window.addEventListener('toggle-sidebar', () => {
    const overlay = document.querySelector('.sidebar-overlay')
    const sidebar = document.querySelector('.sidebar-mobile')
    
    if (overlay && sidebar) {
      overlay.classList.toggle('hidden')
      sidebar.classList.toggle('-translate-x-full')
    }
  })
})
</script>

<style>
/* Mobile sidebar styles */
.sidebar-open .sidebar-overlay {
  display: block;
}

.sidebar-open .sidebar-mobile {
  transform: translateX(0);
}
</style> 