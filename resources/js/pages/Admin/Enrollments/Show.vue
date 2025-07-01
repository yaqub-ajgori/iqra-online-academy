<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import Icon from '@/components/Icon.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { formatCurrency, formatDate, getStatusColor, capitalizeWords } from '@/lib/utils'

const props = defineProps<{
  enrollment: App.Data.EnrollmentData
}>()
</script>

<template>
  <AdminLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Enrollment Details</h1>
          <p class="text-gray-600 mt-1">View detailed information about this enrollment</p>
        </div>
        <div class="flex space-x-3">
          <Button
            variant="outline"
            @click="$inertia.visit(route('admin.enrollments.edit', enrollment.id))"
          >
            <Icon name="Pencil" class="h-4 w-4 mr-2" />
            Edit Enrollment
          </Button>
          <Link
            :href="route('admin.enrollments.index')"
            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all h-9 px-4 py-2 border bg-background shadow-xs hover:bg-accent hover:text-accent-foreground"
          >
            <Icon name="ArrowLeft" class="h-4 w-4 mr-2" />
            Back
          </Link>
        </div>
      </div>

      <!-- Content -->
      <div class="space-y-8">
        <!-- Enrollment Overview -->
        <Card>
          <CardHeader>
            <CardTitle class="flex items-center justify-between">
              <span class="flex items-center">
                <Icon name="Users" class="w-5 h-5 mr-2 text-[#d4a574]" />
                Enrollment Overview
              </span>
              <div class="flex space-x-2">
                <span
                  :class="[
                    'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium',
                    enrollment.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                  ]"
                >
                  {{ enrollment.is_active ? 'Active' : 'Inactive' }}
                </span>
                <span
                  v-if="enrollment.is_completed"
                  class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800"
                >
                  Completed
                </span>
              </div>
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
              <div class="text-center">
                <div class="text-3xl font-bold text-gray-900">
                  {{ formatCurrency(enrollment.amount_paid, enrollment.currency) }}
                </div>
                <div class="text-sm text-gray-500">Amount Paid</div>
              </div>
              <div class="text-center">
                <div class="text-lg font-semibold text-gray-900">
                  {{ capitalizeWords(enrollment.enrollment_type) }}
                </div>
                <div class="text-sm text-gray-500">Enrollment Type</div>
              </div>
              <div class="text-center">
                <div class="text-lg font-semibold text-gray-900">
                  {{ enrollment.progress_percentage }}%
                </div>
                <div class="text-sm text-gray-500">Progress</div>
              </div>
              <div class="text-center">
                <div class="text-lg font-semibold text-gray-900">
                  {{ formatDate(enrollment.enrolled_at) }}
                </div>
                <div class="text-sm text-gray-500">Enrolled Date</div>
              </div>
            </div>
          </CardContent>
        </Card>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Student Information -->
          <Card>
            <CardHeader>
              <CardTitle class="flex items-center">
                <Icon name="User" class="h-5 w-5 mr-2 text-[#d4a574]" />
                Student Information
              </CardTitle>
            </CardHeader>
            <CardContent>
              <div class="space-y-4">
                <div>
                  <div class="text-sm font-medium text-gray-500">Full Name</div>
                  <div class="text-lg font-semibold text-gray-900">
                    {{ enrollment.student.full_name }}
                  </div>
                </div>
                <div>
                  <div class="text-sm font-medium text-gray-500">Email</div>
                  <div class="text-lg text-gray-900">
                    {{ enrollment.student.user?.email }}
                  </div>
                </div>
                <div v-if="enrollment.student.phone">
                  <div class="text-sm font-medium text-gray-500">Phone</div>
                  <div class="text-lg text-gray-900">
                    {{ enrollment.student.phone }}
                  </div>
                </div>
              </div>
            </CardContent>
          </Card>

          <!-- Course Information -->
          <Card>
            <CardHeader>
              <CardTitle class="flex items-center">
                <Icon name="BookOpen" class="h-5 w-5 mr-2 text-[#d4a574]" />
                Course Information
              </CardTitle>
            </CardHeader>
            <CardContent>
              <div class="space-y-4">
                <div>
                  <div class="text-sm font-medium text-gray-500">Course Title</div>
                  <div class="text-lg font-semibold text-gray-900">
                    {{ enrollment.course.title }}
                  </div>
                </div>
                <div>
                  <div class="text-sm font-medium text-gray-500">Category</div>
                  <div class="text-lg text-gray-900">
                    {{ enrollment.course.category?.name || 'N/A' }}
                  </div>
                </div>
                <div>
                  <div class="text-sm font-medium text-gray-500">Price</div>
                  <div class="text-lg text-gray-900">
                    {{ formatCurrency(enrollment.course.price, enrollment.course.currency) }}
                  </div>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>

        <!-- Payment Information -->
        <Card>
          <CardHeader>
            <CardTitle class="flex items-center">
              <Icon name="CreditCard" class="h-5 w-5 mr-2 text-[#d4a574]" />
              Payment Information
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <div class="text-sm font-medium text-gray-500">Payment Status</div>
                <div class="mt-1">
                  <span
                    :class="[
                      'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium',
                      getStatusColor(enrollment.payment_status)
                    ]"
                  >
                    {{ capitalizeWords(enrollment.payment_status) }}
                  </span>
                </div>
              </div>

              <div v-if="enrollment.payment">
                <div class="text-sm font-medium text-gray-500">Transaction ID</div>
                <div class="text-lg font-mono text-gray-900">
                  {{ enrollment.payment.transaction_id || 'N/A' }}
                </div>
              </div>

              <div>
                <div class="text-sm font-medium text-gray-500">Amount Paid</div>
                <div class="text-lg font-semibold text-gray-900">
                  {{ formatCurrency(enrollment.amount_paid, enrollment.currency) }}
                </div>
              </div>

              <div>
                <div class="text-sm font-medium text-gray-500">Enrollment Type</div>
                <div class="text-lg text-gray-900">
                  {{ capitalizeWords(enrollment.enrollment_type) }}
                </div>
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Progress Information -->
        <Card>
          <CardHeader>
            <CardTitle class="flex items-center">
              <Icon name="BarChart3" class="h-5 w-5 mr-2 text-[#d4a574]" />
              Progress Information
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div class="space-y-6">
              <!-- Progress Bar -->
              <div>
                <div class="flex justify-between items-center mb-2">
                  <span class="text-sm font-medium text-gray-700">Course Progress</span>
                  <span class="text-sm font-medium text-gray-900">{{ enrollment.progress_percentage }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                  <div 
                    class="bg-blue-600 h-3 rounded-full transition-all duration-300"
                    :style="{ width: `${enrollment.progress_percentage}%` }"
                  ></div>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <div class="text-sm font-medium text-gray-500">Lessons Completed</div>
                  <div class="text-lg font-semibold text-gray-900">
                    {{ enrollment.lessons_completed }} lessons
                  </div>
                </div>

                <div>
                  <div class="text-sm font-medium text-gray-500">Completion Status</div>
                  <div class="text-lg text-gray-900">
                    <span
                      :class="[
                        'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium',
                        enrollment.is_completed ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'
                      ]"
                    >
                      {{ enrollment.is_completed ? 'Completed' : 'In Progress' }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  </AdminLayout>
</template> 