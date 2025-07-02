<template>
  <AdminLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Enrollment Management</h1>
          <p class="text-gray-600 mt-1">Manage all course enrollments</p>
        </div>
        <Link 
          :href="route('admin.enrollments.create')"
          :class="buttonVariants({ variant: 'primary' })"
        >
          <Icon name="Plus" class="h-4 w-4" />
          New Enrollment
        </Link>
      </div>

      <!-- Filter Section -->
      <Card>
        <CardHeader>
          <CardTitle class="flex items-center gap-2">
            <Icon name="Filter" class="h-5 w-5" />
            Filters
            <span v-if="hasActiveFilters" class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
              {{ activeFiltersCount }} active
            </span>
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
              <Label for="search" class="flex items-center gap-2">
                Search
                <span v-if="filters.search" class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                  Active
                </span>
              </Label>
              <Input
                id="search"
                v-model="filters.search"
                placeholder="Search by student or course..."
                @input="debouncedSearch"
              />
            </div>

            <!-- Payment Status Filter -->
            <div>
              <Label for="payment_status" class="flex items-center gap-2">
                Payment Status
                <span v-if="filters.payment_status && filters.payment_status !== 'all'" class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                  Active
                </span>
              </Label>
              <select 
                v-model="filters.payment_status" 
                @change="applyFilters"
                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
              >
                <option value="all">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
                <option value="failed">Failed</option>
                <option value="refunded">Refunded</option>
              </select>
            </div>

            <!-- Enrollment Type Filter -->
            <div>
              <Label for="enrollment_type" class="flex items-center gap-2">
                Enrollment Type
                <span v-if="filters.enrollment_type && filters.enrollment_type !== 'all'" class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                  Active
                </span>
              </Label>
              <select 
                v-model="filters.enrollment_type" 
                @change="applyFilters"
                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
              >
                <option value="all">All Types</option>
                <option value="paid">Paid</option>
                <option value="free">Free</option>
                <option value="scholarship">Scholarship</option>
                <option value="trial">Trial</option>
              </select>
            </div>

            <!-- Filter Actions -->
            <div class="flex items-end gap-2">
              <Button 
                @click="clearFilters" 
                variant="outline" 
                size="sm"
                :disabled="!hasActiveFilters"
                :class="hasActiveFilters ? 'border-red-200 text-red-700 hover:bg-red-50' : ''"
              >
                <Icon name="X" class="h-4 w-4 mr-2" />
                {{ hasActiveFilters ? 'Clear Filters' : 'No Filters' }}
              </Button>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <Card>
          <CardContent class="flex items-center p-6">
            <div class="flex items-center space-x-3">
              <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                <Icon name="Users" class="h-5 w-5 text-blue-600" />
              </div>
              <div>
                <div class="text-2xl font-bold text-gray-900">{{ enrollments.total }}</div>
                <div class="text-sm text-gray-500">Total Enrollments</div>
              </div>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardContent class="flex items-center p-6">
            <div class="flex items-center space-x-3">
              <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                <Icon name="CheckCircle" class="h-5 w-5 text-green-600" />
              </div>
              <div>
                <div class="text-2xl font-bold text-gray-900">{{ activeEnrollments }}</div>
                <div class="text-sm text-gray-500">Active</div>
              </div>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardContent class="flex items-center p-6">
            <div class="flex items-center space-x-3">
              <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                <Icon name="Award" class="h-5 w-5 text-purple-600" />
              </div>
              <div>
                <div class="text-2xl font-bold text-gray-900">{{ completedEnrollments }}</div>
                <div class="text-sm text-gray-500">Completed</div>
              </div>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardContent class="flex items-center p-6">
            <div class="flex items-center space-x-3">
              <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                <Icon name="CreditCard" class="h-5 w-5 text-emerald-600" />
              </div>
              <div>
                <div class="text-2xl font-bold text-gray-900">{{ formatCurrency(totalRevenue, 'BDT') }}</div>
                <div class="text-sm text-gray-500">Total Revenue</div>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>

      <!-- Enrollments Table -->
      <Card>
        <CardHeader>
          <CardTitle class="flex items-center justify-between">
            <span class="flex items-center gap-2">
              Enrollments List
            </span>
            <div class="text-sm text-gray-500">
              {{ enrollments.from }}-{{ enrollments.to }} of {{ enrollments.total }}
            </div>
          </CardTitle>
        </CardHeader>
        <CardContent class="p-0">
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Student & Course</TableHead>
                <TableHead>Type & Payment</TableHead>
                <TableHead text-align="center">Progress</TableHead>
                <TableHead text-align="center">Status</TableHead>
                <TableHead text-align="center">Enrolled Date</TableHead>
                <TableHead text-align="right">Actions</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow
                v-for="enrollment in enrollments.data"
                :key="enrollment.id"
                class="hover:bg-gray-50"
              >
                <!-- Student & Course -->
                <TableCell>
                  <div class="space-y-2">
                    <div class="flex items-center space-x-3">
                      <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                        <Icon name="User" class="h-4 w-4 text-blue-600" />
                      </div>
                      <div>
                        <div class="text-sm font-medium text-gray-900">
                          {{ enrollment.student.full_name }}
                        </div>
                        <div class="text-sm text-gray-500">
                          {{ enrollment.student.user?.email }}
                        </div>
                      </div>
                    </div>
                    <div class="text-sm text-gray-600 ml-11">
                      <Icon name="BookOpen" class="h-4 w-4 inline mr-1" />
                      {{ truncateText(enrollment.course.title, 40) }}
                    </div>
                  </div>
                </TableCell>

                <!-- Type & Payment -->
                <TableCell>
                  <div class="space-y-1">
                    <span 
                      :class="[
                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                        getEnrollmentTypeColor(enrollment.enrollment_type)
                      ]"
                    >
                      {{ capitalizeWords(enrollment.enrollment_type) }}
                    </span>
                    <div class="text-sm text-gray-600">
                      {{ formatCurrency(enrollment.amount_paid, enrollment.currency) }}
                    </div>
                    <span 
                      :class="[
                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                        getStatusColor(enrollment.payment_status)
                      ]"
                    >
                      {{ capitalizeWords(enrollment.payment_status) }}
                    </span>
                  </div>
                </TableCell>

                <!-- Progress -->
                <TableCell text-align="center">
                  <div class="space-y-2">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                      <div 
                        class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                        :style="{ width: `${enrollment.progress_percentage}%` }"
                      ></div>
                    </div>
                    <div class="text-xs text-gray-600">
                      {{ enrollment.progress_percentage }}% ({{ enrollment.lessons_completed }} lessons)
                    </div>
                  </div>
                </TableCell>

                <!-- Status -->
                <TableCell text-align="center">
                  <div class="flex flex-col space-y-1 items-center">
                    <span 
                      :class="[
                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                        enrollment.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                      ]"
                    >
                      {{ enrollment.is_active ? 'Active' : 'Inactive' }}
                    </span>
                    <span 
                      v-if="enrollment.is_completed"
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800"
                    >
                      Completed
                    </span>
                  </div>
                </TableCell>

                <!-- Enrolled Date -->
                <TableCell text-align="center">
                  <div class="text-sm text-gray-900">
                    {{ formatDate(enrollment.enrolled_at) }}
                  </div>
                </TableCell>

                <!-- Actions -->
                <TableCell text-align="right">
                  <div class="flex items-center justify-end space-x-1">
                    <Button
                      variant="ghost"
                      size="icon-sm"
                      @click="$inertia.visit(route('admin.enrollments.show', enrollment.id))"
                      title="View Details"
                    >
                      <Icon name="Eye" class="h-4 w-4" />
                    </Button>
                    
                    <Button
                      variant="ghost"
                      size="icon-sm"
                      @click="$inertia.visit(route('admin.enrollments.edit', enrollment.id))"
                      title="Edit Enrollment"
                    >
                      <Icon name="Pencil" class="h-4 w-4" />
                    </Button>
                    
                    <Button
                      @click="toggleStatus(enrollment)"
                      variant="ghost"
                      size="icon-sm"
                      :class="enrollment.is_active ? 'text-red-600 hover:text-red-700' : 'text-green-600 hover:text-green-700'"
                      :title="enrollment.is_active ? 'Deactivate' : 'Activate'"
                    >
                      <Icon :name="enrollment.is_active ? 'UserX' : 'UserCheck'" class="h-4 w-4" />
                    </Button>
                    
                    <Button
                      @click="deleteEnrollment(enrollment)"
                      variant="ghost"
                      size="icon-sm"
                      class="text-red-600 hover:text-red-700"
                      title="Delete Enrollment"
                    >
                      <Icon name="Trash2" class="h-4 w-4" />
                    </Button>
                  </div>
                </TableCell>
              </TableRow>

              <!-- Empty State -->
              <TableRow v-if="enrollments.data.length === 0">
                <TableCell :colspan="6" class="text-center py-12">
                  <div class="flex flex-col items-center justify-center space-y-3">
                    <Icon name="Users" class="h-12 w-12 text-gray-400" />
                    <div class="text-gray-500">No enrollments found</div>
                    <Link 
                      :href="route('admin.enrollments.create')"
                      :class="buttonVariants({ variant: 'primary' })"
                    >
                      Create First Enrollment
                    </Link>
                  </div>
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </CardContent>
      </Card>

      <!-- Pagination -->
      <div v-if="enrollments.last_page > 1" class="flex justify-center mt-6">
        <nav class="flex items-center space-x-2">
          <Button
            :disabled="!enrollments.prev_page_url"
            @click="$inertia.visit(enrollments.prev_page_url!)"
            variant="outline"
            size="sm"
          >
            Previous
          </Button>
          <Button
            :disabled="!enrollments.next_page_url"
            @click="$inertia.visit(enrollments.next_page_url!)"
            variant="outline"
            size="sm"
          >
            Next
          </Button>
        </nav>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import Icon from '@/components/Icon.vue'
import { useToast } from '@/composables/useToast'
import { buttonVariants, formatCurrency, formatDate, getStatusColor, capitalizeWords, truncateText, debounce } from '@/lib/utils'

const props = defineProps<{
  enrollments: App.Data.Paginated<App.Data.EnrollmentData>
  filters: {
    search?: string
    payment_status?: string
    enrollment_type?: string
  }
}>()

// Toast composable
const { success, error } = useToast()

// Reactive filters
const filters = ref({
  search: props.filters.search || '',
  payment_status: props.filters.payment_status || 'all',
  enrollment_type: props.filters.enrollment_type || 'all',
})

// Loading state
const isFiltering = ref(false)

// Debounced search function
const debouncedSearch = debounce(() => {
  applyFilters()
}, 300)

// Computed statistics
const activeEnrollments = computed(() => 
  props.enrollments.data.filter(enrollment => enrollment.is_active).length
)

const completedEnrollments = computed(() => 
  props.enrollments.data.filter(enrollment => enrollment.is_completed).length
)

const totalRevenue = computed(() => 
  props.enrollments.data
    .filter(enrollment => enrollment.payment_status === 'completed')
    .reduce((total, enrollment) => total + enrollment.amount_paid, 0)
)

// Filter indicators
const hasActiveFilters = computed(() => {
  return filters.value.search || 
         (filters.value.payment_status && filters.value.payment_status !== 'all') || 
         (filters.value.enrollment_type && filters.value.enrollment_type !== 'all')
})

const activeFiltersCount = computed(() => {
  let count = 0
  if (filters.value.search) count++
  if (filters.value.payment_status && filters.value.payment_status !== 'all') count++
  if (filters.value.enrollment_type && filters.value.enrollment_type !== 'all') count++
  return count
})

// Methods
const applyFilters = () => {
  isFiltering.value = true
  
  // Remove empty filters and 'all' values
  const cleanFilters = Object.fromEntries(
    Object.entries(filters.value).filter(([_, value]) => 
      value !== '' && 
      value !== null && 
      value !== undefined && 
      value !== 'all'
    )
  )
  
  router.get(route('admin.enrollments.index'), cleanFilters, {
    preserveState: true,
    preserveScroll: true,
    replace: true,
    onFinish: () => {
      isFiltering.value = false
    }
  })
}

const clearFilters = () => {
  isFiltering.value = true
  filters.value = {
    search: '',
    payment_status: 'all',
    enrollment_type: 'all',
  }
  router.get(route('admin.enrollments.index'), {}, {
    preserveState: true,
    preserveScroll: true,
    replace: true,
    onFinish: () => {
      isFiltering.value = false
    }
  })
}

const toggleStatus = (enrollment: App.Data.EnrollmentData) => {
  router.patch(route('admin.enrollments.toggle-status', enrollment.id), {}, {
    preserveScroll: true,
    onSuccess: () => {
      const status = !enrollment.is_active ? 'activated' : 'deactivated'
      success(`Enrollment ${status} successfully.`)
    },
    onError: () => {
      error('Failed to update enrollment status.')
    }
  })
}

const deleteEnrollment = (enrollment: App.Data.EnrollmentData) => {
  if (confirm(`Are you sure you want to delete the enrollment for ${enrollment.student.full_name} in ${enrollment.course.title}?`)) {
    router.delete(route('admin.enrollments.destroy', enrollment.id), {
      preserveScroll: true,
      onSuccess: () => {
        success('Enrollment deleted successfully.')
      },
      onError: () => {
        error('Failed to delete enrollment.')
      }
    })
  }
}

const getEnrollmentTypeColor = (type: string) => {
  const colors: Record<string, string> = {
    paid: 'bg-blue-100 text-blue-800',
    free: 'bg-green-100 text-green-800',
    scholarship: 'bg-purple-100 text-purple-800',
    trial: 'bg-orange-100 text-orange-800',
  }
  return colors[type] || 'bg-gray-100 text-gray-800'
}
</script> 