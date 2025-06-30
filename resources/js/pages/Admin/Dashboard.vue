<template>
  <AdminLayout page-title="">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-red-600 to-[#5f5fcd] rounded-xl p-6 mb-8">
      <div class="flex items-center justify-between text-white">
        <div>
          <h2 class="text-2xl font-bold mb-2">Welcome back, Admin!</h2>
          <p class="text-red-100">Here's what's happening with your Islamic academy today.</p>
        </div>
        <div class="hidden md:block">
          <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
            <Icon name="layout-dashboard" class="h-8 w-8 text-white" />
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-10">
      <StatsCard
        title="Total Users"
        :value="stats.total_users"
        icon="users"
        color="blue"
      />
      <StatsCard
        title="Total Courses"
        :value="stats.total_courses"
        icon="book-open"
        color="green"
      />
      <StatsCard
        title="Active Enrollments"
        :value="stats.active_enrollments"
        icon="user-check"
        color="purple"
      />
      <StatsCard
        title="Total Revenue"
        :value="`৳${formatNumber(stats.total_revenue)}`"
        icon="credit-card"
        color="orange"
      />
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- Recent Enrollments -->
      <Card class="border border-gray-200 shadow-sm">
        <CardHeader class="border-b border-gray-100 bg-gray-50">
          <CardTitle class="flex items-center text-gray-900">
            <Icon name="user-check" class="mr-3 h-5 w-5 text-[#5f5fcd]" />
            Recent Enrollments
          </CardTitle>
        </CardHeader>
        <CardContent class="p-6">
          <div class="space-y-4">
            <div 
              v-for="enrollment in recentEnrollments.slice(0, 5)" 
              :key="enrollment.id"
              class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors"
            >
              <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-[#5f5fcd] to-blue-600 rounded-full flex items-center justify-center">
                  <span class="text-white text-sm font-semibold">
                    {{ enrollment.student?.user?.name?.charAt(0) || 'U' }}
                  </span>
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-900">{{ enrollment.student?.user?.name }}</p>
                  <p class="text-xs text-gray-500">{{ enrollment.course?.title }}</p>
                </div>
              </div>
              <span class="text-xs text-gray-500 bg-white px-2 py-1 rounded">
                {{ formatDate(enrollment.enrolled_at) }}
              </span>
            </div>
          </div>
          <div class="mt-6 text-center">
            <Link 
              :href="route('admin.enrollments.index')"
              class="text-sm text-[#5f5fcd] hover:text-red-600 font-medium transition-colors"
            >
              View all enrollments →
            </Link>
          </div>
        </CardContent>
      </Card>

      <!-- Recent Payments -->
      <Card class="border border-gray-200 shadow-sm">
        <CardHeader class="border-b border-gray-100 bg-gray-50">
          <CardTitle class="flex items-center text-gray-900">
            <Icon name="credit-card" class="mr-3 h-5 w-5 text-[#2d5a27]" />
            Recent Payments
          </CardTitle>
        </CardHeader>
        <CardContent class="p-6">
          <div class="space-y-4">
            <div 
              v-for="payment in recentPayments.slice(0, 5)" 
              :key="payment.id"
              class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors"
            >
              <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-[#2d5a27] to-green-600 rounded-full flex items-center justify-center">
                  <Icon name="credit-card" class="h-5 w-5 text-white" />
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-900">৳{{ payment.amount }}</p>
                  <p class="text-xs text-gray-500">{{ payment.student?.user?.name }}</p>
                </div>
              </div>
              <div class="text-right">
                <span class="text-xs text-gray-500 bg-white px-2 py-1 rounded block mb-1">
                  {{ formatDate(payment.created_at) }}
                </span>
                <span class="text-xs px-2 py-1 rounded" :class="getPaymentStatusClass(payment.status)">
                  {{ payment.status }}
                </span>
              </div>
            </div>
          </div>
          <div class="mt-6 text-center">
            <Link 
              :href="route('admin.payments.index')"
              class="text-sm text-[#5f5fcd] hover:text-red-600 font-medium transition-colors"
            >
              View all payments →
            </Link>
          </div>
        </CardContent>
      </Card>
    </div>
  </AdminLayout>
</template>

<script setup>
import { defineProps } from 'vue'
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import StatsCard from '@/components/Admin/StatsCard.vue'
import Icon from '@/components/Icon.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  stats: Object,
  recentEnrollments: Array,
  recentPayments: Array
})

const formatNumber = (number) => {
  return new Intl.NumberFormat('bn-BD').format(number)
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('bn-BD')
}

const getPaymentStatusClass = (status) => {
  switch (status) {
    case 'completed':
      return 'bg-green-100 text-green-700'
    case 'pending':
      return 'bg-yellow-100 text-yellow-700'
    case 'failed':
      return 'bg-red-100 text-red-700'
    default:
      return 'bg-gray-100 text-gray-700'
  }
}
</script> 