<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import Icon from '@/components/Icon.vue'

import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { formatCurrency, formatDate, getPaymentStatusClass, capitalizeWords } from '@/lib/utils'

const props = defineProps<{
  payment: App.Data.PaymentData
}>()
</script>

<template>
  <AdminLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Payment Details</h1>
          <p class="text-gray-600 mt-1">View detailed information about this payment</p>
        </div>
        <div class="flex space-x-3">
          <Button
            variant="outline"
            @click="$inertia.visit(route('admin.payments.edit', payment.id))"
          >
            <Icon name="Pencil" class="h-4 w-4 mr-2" />
            Edit Payment
          </Button>
          <Link
            :href="route('admin.payments.index')"
            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all h-9 px-4 py-2 border bg-background shadow-xs hover:bg-accent hover:text-accent-foreground"
          >
            <Icon name="ArrowLeft" class="h-4 w-4 mr-2" />
            Back
          </Link>
        </div>
      </div>

      <!-- Content -->
      <div class="space-y-8">
        <!-- Payment Overview -->
        <Card>
          <CardHeader>
            <CardTitle class="flex items-center justify-between">
              <span class="flex items-center">
                <Icon name="CreditCard" class="w-5 h-5 mr-2 text-[#d4a574]" />
                Payment Overview
              </span>
              <span
                :class="[
                  'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium',
                  getPaymentStatusClass(payment.status)
                ]"
              >
                {{ capitalizeWords(payment.status) }}
              </span>
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div class="text-center">
                <div class="text-3xl font-bold text-gray-900">
                  {{ formatCurrency(payment.amount, payment.currency) }}
                </div>
                <div class="text-sm text-gray-500">Amount</div>
              </div>
              <div class="text-center">
                <div class="text-lg font-semibold text-gray-900">
                  {{ capitalizeWords(payment.payment_method?.replace('_', ' ') || 'Unknown') }}
                </div>
                <div class="text-sm text-gray-500">Payment Method</div>
              </div>
              <div class="text-center">
                <div class="text-lg font-semibold text-gray-900">
                  {{ formatDate(payment.created_at) }}
                </div>
                <div class="text-sm text-gray-500">Payment Date</div>
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
                    {{ payment.student.full_name }}
                  </div>
                </div>
                <div>
                  <div class="text-sm font-medium text-gray-500">Email</div>
                  <div class="text-lg text-gray-900">
                    {{ payment.student.user?.email }}
                  </div>
                </div>
                <div v-if="payment.student.phone">
                  <div class="text-sm font-medium text-gray-500">Phone</div>
                  <div class="text-lg text-gray-900">
                    {{ payment.student.phone }}
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
                    {{ payment.course.title }}
                  </div>
                </div>
                <div>
                  <div class="text-sm font-medium text-gray-500">Category</div>
                  <div class="text-lg text-gray-900">
                    {{ payment.course.category?.name || 'N/A' }}
                  </div>
                </div>
                <div>
                  <div class="text-sm font-medium text-gray-500">Price</div>
                  <div class="text-lg text-gray-900">
                    {{ formatCurrency(payment.course.price, payment.course.currency) }}
                  </div>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>

        <!-- Transaction Details -->
        <Card>
          <CardHeader>
            <CardTitle class="flex items-center">
              <Icon name="CreditCard" class="h-5 w-5 mr-2 text-[#d4a574]" />
              Transaction Details
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <div class="text-sm font-medium text-gray-500">Transaction ID</div>
                <div class="text-lg font-mono text-gray-900">
                  {{ payment.transaction_id || 'N/A' }}
                </div>
              </div>

              <!-- Mobile Banking Details -->
              <template v-if="['bkash', 'nagad', 'rocket'].includes(payment.payment_method)">
                <div v-if="payment.sender_number">
                  <div class="text-sm font-medium text-gray-500">Sender Number</div>
                  <div class="text-lg text-gray-900">
                    {{ payment.sender_number }}
                  </div>
                </div>
                <div v-if="payment.receiver_number">
                  <div class="text-sm font-medium text-gray-500">Receiver Number</div>
                  <div class="text-lg text-gray-900">
                    {{ payment.receiver_number }}
                  </div>
                </div>
              </template>

              <!-- Bank Transfer Details -->
              <template v-if="payment.payment_method === 'bank_transfer'">
                <div v-if="payment.bank_name">
                  <div class="text-sm font-medium text-gray-500">Bank Name</div>
                  <div class="text-lg text-gray-900">
                    {{ payment.bank_name }}
                  </div>
                </div>
                <div v-if="payment.account_number">
                  <div class="text-sm font-medium text-gray-500">Account Number</div>
                  <div class="text-lg font-mono text-gray-900">
                    {{ payment.account_number }}
                  </div>
                </div>
                <div v-if="payment.branch_name">
                  <div class="text-sm font-medium text-gray-500">Branch Name</div>
                  <div class="text-lg text-gray-900">
                    {{ payment.branch_name }}
                  </div>
                </div>
              </template>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  </AdminLayout>
</template> 