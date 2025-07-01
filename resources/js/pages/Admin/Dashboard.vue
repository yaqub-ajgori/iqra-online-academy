<template>
  <AdminLayout>
    <!-- Page Header -->
    <div class="mb-8">
      <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
      <p class="text-sm text-gray-600">Overview of your academy's performance and recent activity</p>
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
          <CardTitle class="flex items-center text-gray-900 text-lg font-semibold gap-2">
            <Icon name="user-check" class="h-5 w-5 text-blue-600" />
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
                <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-400 rounded-full flex items-center justify-center">
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
              class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors"
            >
              View all enrollments →
            </Link>
          </div>
        </CardContent>
      </Card>

      <!-- Recent Payments -->
      <Card class="border border-gray-200 shadow-sm">
        <CardHeader class="border-b border-gray-100 bg-gray-50">
          <CardTitle class="flex items-center text-gray-900 text-lg font-semibold gap-2">
            <Icon name="credit-card" class="h-5 w-5 text-green-600" />
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
                <div class="w-10 h-10 bg-gradient-to-br from-green-600 to-green-400 rounded-full flex items-center justify-center">
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
              class="text-sm text-green-600 hover:text-green-800 font-medium transition-colors"
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
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import StatsCard from '@/components/Admin/StatsCard.vue'
import Icon from '@/components/Icon.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Link } from '@inertiajs/vue3'
import { formatNumber, formatDate } from '@/lib/utils'

const props = defineProps({
  stats: Object,
  recentEnrollments: Array,
  recentPayments: Array
})

const getPaymentStatusClass = (status) => {
  switch (status) {
    case 'completed':
    case 'paid':
      return 'bg-green-100 text-green-800';
    case 'pending':
      return 'bg-yellow-100 text-yellow-800';
    case 'failed':
    case 'cancelled':
      return 'bg-red-100 text-red-800';
    default:
      return 'bg-gray-100 text-gray-800';
  }
}
</script> 