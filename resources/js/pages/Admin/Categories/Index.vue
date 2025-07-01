<template>
  <AdminLayout>
    <!-- Page Header -->
    <div class="mb-8">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Course Categories</h1>
          <p class="text-sm text-gray-600">Manage all course categories</p>
        </div>
        <div class="flex items-center space-x-3">
          <div class="text-sm text-gray-500">
            {{ categories.total }} {{ categories.total === 1 ? 'category' : 'categories' }}
          </div>
          <Link
            :href="route('admin.categories.create')"
            :class="buttonVariants({ variant: 'primary' })"
          >
            <Icon name="Plus" class="h-4 w-4" />
            New Category
          </Link>
        </div>
      </div>
    </div>

    <!-- Categories Table -->
    <Card>
      <CardHeader>
        <CardTitle class="flex items-center justify-between">
          <span>Category List</span>
          <div class="text-sm text-gray-500">
            {{ categories.from }}-{{ categories.to }} of {{ categories.total }}
          </div>
        </CardTitle>
      </CardHeader>
      <CardContent class="p-0">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Category</TableHead>
              <TableHead text-align="center">Course Count</TableHead>
              <TableHead text-align="center">Status</TableHead>
              <TableHead text-align="right">Actions</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow
                  v-for="category in categories.data"
                  :key="category.id"
                  class="hover:bg-gray-50"
                >
                  <!-- Category Info -->
              <TableCell>
                    <div class="flex items-center space-x-3">
                      <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                        <Icon name="Folder" class="h-4 w-4 text-emerald-600" />
                      </div>
                      <div>
                        <div class="text-sm font-medium text-gray-900">
                          {{ category.name }}
                        </div>
                    <div v-if="category.description" class="text-sm text-gray-500 max-w-xs">
                      {{ truncateText(category.description, 60) }}
                        </div>
                        <div class="text-xs text-gray-400">
                          {{ category.slug }}
                        </div>
                      </div>
                    </div>
              </TableCell>

                  <!-- Course Count -->
              <TableCell text-align="center">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                  {{ category.courses_count }} {{ category.courses_count === 1 ? 'course' : 'courses' }}
                    </span>
              </TableCell>

                  <!-- Status -->
              <TableCell text-align="center">
                    <span
                      :class="[
                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                    getStatusColor(category.is_active ? 'active' : 'inactive')
                      ]"
                    >
                      {{ category.is_active ? 'Active' : 'Inactive' }}
                    </span>
              </TableCell>

                  <!-- Actions -->
              <TableCell text-align="right">
                    <div class="flex items-center justify-end space-x-2">
                      <Link
                        :href="route('admin.categories.edit', category.id)"
                    :class="buttonVariants({ variant: 'ghost', size: 'icon-sm' })"
                      >
                        <Icon name="Edit" class="h-4 w-4" />
                      </Link>
                  
                      <Button
                        @click="toggleStatus(category)"
                        variant="ghost"
                    size="icon-sm"
                        :class="category.is_active ? 'text-red-600 hover:text-red-700' : 'text-green-600 hover:text-green-700'"
                      >
                        <Icon :name="category.is_active ? 'EyeOff' : 'Eye'" class="h-4 w-4" />
                      </Button>
                  
                      <Button
                        @click="deleteCategory(category)"
                        variant="ghost"
                    size="icon-sm"
                        class="text-red-600 hover:text-red-700"
                    :disabled="category.courses_count > 0"
                      >
                        <Icon name="Trash2" class="h-4 w-4" />
                      </Button>
                    </div>
              </TableCell>
            </TableRow>

                <!-- Empty State -->
            <TableRow v-if="categories.data.length === 0">
              <TableCell :colspan="4" class="text-center py-12">
                    <div class="flex flex-col items-center justify-center space-y-3">
                      <Icon name="FolderOpen" class="h-12 w-12 text-gray-400" />
                      <div class="text-gray-500">No categories found</div>
                      <Link
                        :href="route('admin.categories.create')"
                    :class="buttonVariants({ variant: 'primary' })"
                      >
                        Create First Category
                      </Link>
                    </div>
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </CardContent>
    </Card>

    <!-- Pagination -->
    <div v-if="categories.last_page > 1" class="flex justify-center mt-6">
      <nav class="flex items-center space-x-2">
        <Button
          :disabled="!categories.prev_page_url"
          @click="goToPage(categories.current_page - 1)"
          variant="outline"
          size="sm"
        >
          Previous
        </Button>
        <span class="px-3 py-1 text-sm text-gray-700">
          Page {{ categories.current_page }} of {{ categories.last_page }}
        </span>
        <Button
          :disabled="!categories.next_page_url"
          @click="goToPage(categories.current_page + 1)"
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
import { router, Link } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Table, TableHeader, TableHead, TableBody, TableRow, TableCell } from '@/components/ui/table'
import Icon from '@/components/Icon.vue'
import { useToast } from '@/composables/useToast'
import { getStatusColor, truncateText, buttonVariants } from '@/lib/utils'

// Toast composable
const { success, error } = useToast()

// Props
interface Category {
      id: number
      name: string
      slug: string
      description?: string
      is_active: boolean
      courses_count: number
}

interface Props {
  categories: {
    data: Category[]
    current_page: number
    last_page: number
    per_page: number
    total: number
    from: number
    to: number
    prev_page_url?: string
    next_page_url?: string
  }
}

defineProps<Props>()

// Methods
const goToPage = (page: number) => {
  router.get(route('admin.categories.index'), { page }, {
    preserveState: true,
    preserveScroll: true,
  })
}

const toggleStatus = (category: Category) => {
  router.patch(route('admin.categories.toggle-status', category.id), {}, {
    preserveScroll: true,
    onSuccess: () => {
      const status = !category.is_active ? 'Active' : 'Inactive'
      success(`Category status changed to ${status}.`)
    },
    onError: () => {
      error('Failed to change category status.')
    }
  })
}

const deleteCategory = (category: Category) => {
  if (category.courses_count > 0) {
    error('This category has courses. Please move the courses to another category first.')
    return
  }

  if (confirm('Are you sure you want to delete this category?')) {
    router.delete(route('admin.categories.destroy', category.id), {
      preserveScroll: true,
      onSuccess: () => {
        success('Category deleted successfully.')
      },
      onError: () => {
        error('Failed to delete category.')
      }
    })
  }
}
</script> 