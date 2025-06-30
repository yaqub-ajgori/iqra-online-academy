<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Admin Navigation -->
    <AdminHeader />
    
    <!-- Admin Sidebar -->
    <AdminSidebar />
    
    <!-- Main Content -->
    <div class="lg:pl-64">
      <main class="py-8">
        <div class="mx-auto max-w-7xl px-6 sm:px-8 lg:px-10">
          <!-- Page Header -->
          <div class="mb-10" v-if="pageTitle || breadcrumbs">
            <div class="flex items-center justify-between">
              <div class="space-y-3">
                <h1 class="text-3xl font-bold text-gray-900" v-if="pageTitle">
                  {{ pageTitle }}
                </h1>
                <!-- Breadcrumbs -->
                <nav v-if="breadcrumbs">
                  <ol class="flex items-center space-x-3 text-sm text-gray-500">
                    <li v-for="(crumb, index) in breadcrumbs" :key="index" class="flex items-center">
                      <Link 
                        v-if="crumb.href && index < breadcrumbs.length - 1"
                        :href="crumb.href"
                        class="hover:text-gray-700 transition-colors"
                      >
                        {{ crumb.label }}
                      </Link>
                      <span v-else class="font-medium text-gray-700">
                        {{ crumb.label }}
                      </span>
                      <Icon 
                        v-if="index < breadcrumbs.length - 1"
                        name="chevron-right" 
                        class="ml-3 h-4 w-4"
                      />
                    </li>
                  </ol>
                </nav>
              </div>
              
              <!-- Action buttons slot -->
              <div v-if="$slots.actions" class="flex items-center space-x-4">
                <slot name="actions" />
              </div>
            </div>
          </div>
          
          <!-- Page Content -->
          <div class="space-y-8">
            <slot />
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import AdminHeader from '@/components/Admin/AdminHeader.vue'
import AdminSidebar from '@/components/Admin/AdminSidebar.vue'
import Icon from '@/components/Icon.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  pageTitle: String,
  breadcrumbs: Array
})

const page = usePage()
const user = computed(() => page.props.auth?.user)
</script> 