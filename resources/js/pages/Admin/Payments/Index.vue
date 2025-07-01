<template>
  <AdminLayout>
    <!-- Page Header -->
    <div class="mb-8">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Payments</h1>
          <p class="text-sm text-gray-600">Manage all course payments and transactions</p>
        </div>
        <div class="flex items-center space-x-3">
          <div class="text-sm text-gray-500">
            {{ payments.total }} {{ payments.total === 1 ? 'payment' : 'payments' }}
          </div>
          <Link 
            :href="route('admin.payments.create')"
            :class="buttonVariants({ variant: 'primary' })"
          >
            <Icon name="Plus" class="h-4 w-4 mr-2" />
            New Payment
          </Link>
        </div>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
      <Card>
        <CardContent class="flex items-center p-6">
          <div class="flex items-center space-x-3">
            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
              <Icon name="CreditCard" class="h-5 w-5 text-blue-600" />
            </div>
            <div>
              <div class="text-2xl font-bold text-gray-900">{{ payments.total }}</div>
              <div class="text-sm text-gray-500">Total Payments</div>
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
              <div class="text-2xl font-bold text-gray-900">{{ completedPayments }}</div>
              <div class="text-sm text-gray-500">Completed Payments</div>
            </div>
          </div>
        </CardContent>
      </Card>

      <Card>
        <CardContent class="flex items-center p-6">
          <div class="flex items-center space-x-3">
            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center">
              <Icon name="Clock" class="h-5 w-5 text-yellow-600" />
            </div>
            <div>
              <div class="text-2xl font-bold text-gray-900">{{ pendingPayments }}</div>
              <div class="text-sm text-gray-500">Pending Payments</div>
            </div>
          </div>
        </CardContent>
      </Card>

      <Card>
        <CardContent class="flex items-center p-6">
          <div class="flex items-center space-x-3">
            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
              <Icon name="DollarSign" class="h-5 w-5 text-emerald-600" />
            </div>
            <div>
              <div class="text-2xl font-bold text-gray-900">{{ formatCurrency(totalRevenue, 'BDT') }}</div>
              <div class="text-sm text-gray-500">Total Revenue</div>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>

    <!-- Payments Table -->
    <Card>
      <CardHeader>
        <CardTitle class="flex items-center justify-between">
          <span>Payments List</span>
          <div class="text-sm text-gray-500">
            {{ payments.from }}-{{ payments.to }} of {{ payments.total }}
          </div>
        </CardTitle>
      </CardHeader>
      <CardContent class="p-0">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Student & Course</TableHead>
              <TableHead>Payment Details</TableHead>
              <TableHead text-align="center">Amount</TableHead>
              <TableHead text-align="center">Method</TableHead>
              <TableHead text-align="center">Status</TableHead>
              <TableHead text-align="center">Date</TableHead>
              <TableHead text-align="right">Actions</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow
              v-for="payment in payments.data"
              :key="payment.id"
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
                        {{ payment.student.full_name }}
                      </div>
                      <div class="text-sm text-gray-500">
                        {{ payment.student.user?.email }}
                      </div>
                    </div>
                  </div>
                  <div class="text-sm text-gray-600 ml-11">
                    <Icon name="BookOpen" class="h-4 w-4 inline mr-1" />
                    {{ truncateText(payment.course.title, 40) }}
                  </div>
                </div>
              </TableCell>

              <!-- Payment Details -->
              <TableCell>
                <div class="space-y-1">
                  <div class="text-sm font-medium text-gray-900">
                    TXN: {{ payment.transaction_id || 'N/A' }}
                  </div>
                </div>
              </TableCell>

              <!-- Amount -->
              <TableCell text-align="center">
                <div class="text-sm font-medium text-gray-900">
                  {{ formatCurrency(payment.amount, payment.currency) }}
                </div>
              </TableCell>

              <!-- Payment Method -->
              <TableCell text-align="center">
                <span
                  :class="[
                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                    getPaymentMethodColor(payment.payment_method)
                  ]"
                >
                  {{ capitalizeWords(payment.payment_method?.replace('_', ' ') || 'Unknown') }}
                </span>
              </TableCell>

              <!-- Status -->
              <TableCell text-align="center">
                <span
                  :class="[
                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                    getStatusColor(payment.status)
                  ]"
                >
                  {{ capitalizeWords(payment.status) }}
                </span>
              </TableCell>

              <!-- Date -->
              <TableCell text-align="center">
                <div class="text-sm text-gray-900">
                  {{ formatDate(payment.created_at) }}
                </div>
              </TableCell>

              <!-- Actions -->
              <TableCell text-align="right">
                <div class="flex items-center justify-end space-x-1">
                  <Button
                    variant="ghost"
                    size="icon-sm"
                    @click="$inertia.visit(route('admin.payments.show', payment.id))"
                    title="View Details"
                  >
                    <Icon name="Eye" class="h-4 w-4" />
                  </Button>
                  
                  <Button
                    variant="ghost"
                    size="icon-sm"
                    @click="$inertia.visit(route('admin.payments.edit', payment.id))"
                    title="Edit Payment"
                  >
                    <Icon name="Pencil" class="h-4 w-4" />
                  </Button>
                  
                  <Button
                    @click="deletePayment(payment)"
                    variant="ghost"
                    size="icon-sm"
                    class="text-red-600 hover:text-red-700"
                    title="Delete Payment"
                  >
                    <Icon name="Trash2" class="h-4 w-4" />
                  </Button>
                </div>
              </TableCell>
            </TableRow>

            <!-- Empty State -->
            <TableRow v-if="payments.data.length === 0">
              <TableCell :colspan="7" class="text-center py-12">
                <div class="flex flex-col items-center justify-center space-y-3">
                  <Icon name="CreditCard" class="h-12 w-12 text-gray-400" />
                  <div class="text-gray-500">No payments found</div>
                  <Link 
                    :href="route('admin.payments.create')"
                    :class="buttonVariants({ variant: 'primary' })"
                  >
                    Record Payment
                  </Link>
                </div>
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </CardContent>
    </Card>

    <!-- Pagination -->
    <div v-if="payments.last_page > 1" class="flex justify-center mt-6">
      <nav class="flex items-center space-x-2">
        <Button
          :disabled="!payments.prev_page_url"
          @click="$inertia.visit(payments.prev_page_url!)"
          variant="outline"
          size="sm"
        >
          Previous
        </Button>
        <Button
          :disabled="!payments.next_page_url"
          @click="$inertia.visit(payments.next_page_url!)"
          variant="outline"
          size="sm"
        >
          Next
        </Button>
      </nav>
    </div>
  </AdminLayout>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import Icon from '@/components/Icon.vue'
import { buttonVariants } from '@/lib/utils'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import { Button } from '@/components/ui/button'
import { formatCurrency, formatDate, getStatusColor, capitalizeWords, truncateText } from '@/lib/utils'

const props = defineProps<{
  payments: App.Data.Paginated<App.Data.PaymentData>
  stats: {
    total: number
    completed: number
    pending: number
    total_revenue: number
  }
}>()

const completedPayments = computed(() => props.stats.completed)
const pendingPayments = computed(() => props.stats.pending)
const totalRevenue = computed(() => props.stats.total_revenue)

function deletePayment(payment: App.Data.PaymentData) {
  if (confirm('Are you sure you want to delete this payment record? This action cannot be undone.')) {
    router.delete(route('admin.payments.destroy', payment.id), {
      preserveScroll: true,
    })
  }
}

function getPaymentMethodColor(method: string) {
  const colors: Record<string, string> = {
    bkash: 'bg-pink-100 text-pink-800',
    nagad: 'bg-orange-100 text-orange-800',
    rocket: 'bg-purple-100 text-purple-800',
    bank_transfer: 'bg-indigo-100 text-indigo-800',
  }
  return colors[method] || 'bg-gray-100 text-gray-800'
}
</script>