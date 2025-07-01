<template>
  <AdminLayout>
    <!-- Page Header -->
    <div class="mb-8">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Courses</h1>
          <p class="text-sm text-gray-600">Manage course content and settings</p>
        </div>
        <div class="flex items-center space-x-3">
          <div class="text-sm text-gray-500">
            {{ courses.total }} {{ courses.total === 1 ? 'course' : 'courses' }}
          </div>
          <Link 
            :href="route('admin.courses.create')"
            :class="buttonVariants({ variant: 'primary' })"
          >
            <Icon name="Plus" class="h-4 w-4 mr-2" />
            New Course
          </Link>
        </div>
      </div>
    </div>

    <!-- Courses Table -->
    <Card>
      <CardHeader>
        <CardTitle class="flex items-center justify-between">
          <span>Course List</span>
          <div class="text-sm text-gray-500">
            {{ courses.from }}-{{ courses.to }} of {{ courses.total }}
          </div>
        </CardTitle>
      </CardHeader>
      <CardContent class="p-0">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Course</TableHead>
              <TableHead>Category</TableHead>
              <TableHead>Instructor</TableHead>
              <TableHead text-align="center">Students</TableHead>
              <TableHead text-align="center">Price</TableHead>
              <TableHead text-align="center">Status</TableHead>
              <TableHead text-align="right">Actions</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow
              v-for="course in courses.data"
              :key="course.id"
              class="hover:bg-gray-50"
            >
              <!-- Course Info -->
              <TableCell>
                <div class="flex items-center space-x-3">
                  <div class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                      <Icon name="BookOpen" class="h-6 w-6 text-gray-400" />
                    </div>
                  </div>
                  <div class="min-w-0 flex-1">
                    <div class="text-sm font-medium text-gray-900">
                      {{ truncateText(course.title, 40) }}
                    </div>
                    <div class="flex items-center space-x-2 mt-1">
                      <span
                        :class="[
                          'inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium',
                          getStatusColor(course.level)
                        ]"
                      >
                        {{ capitalizeWords(course.level) }}
                      </span>
                      <span v-if="course.is_featured" class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                        Featured
                      </span>
                      <span v-if="course.is_free" class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">
                        Free
                      </span>
                    </div>
                  </div>
                </div>
              </TableCell>

              <!-- Category -->
              <TableCell>
                <span
                  v-if="course.category"
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                >
                  {{ course.category.name }}
                </span>
                <span
                  v-else
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800"
                >
                  No Category
                </span>
              </TableCell>

              <!-- Instructor -->
              <TableCell>
                <div v-if="course.instructor" class="text-sm">
                  <div class="font-medium text-gray-900">{{ course.instructor.full_name }}</div>
                </div>
                <span v-else class="text-sm text-gray-500">No Instructor</span>
              </TableCell>

              <!-- Students Count -->
              <TableCell text-align="center">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                  {{ course.enrollments_count || 0 }} {{ (course.enrollments_count || 0) === 1 ? 'student' : 'students' }}
                </span>
              </TableCell>

              <!-- Price -->
              <TableCell text-align="center">
                <div v-if="course.is_free" class="text-sm font-medium text-green-600">
                  Free
                </div>
                <div v-else class="text-sm">
                  <div class="font-medium text-gray-900">{{ formatCurrency(course.price) }}</div>
                  <div v-if="course.discount_price" class="text-xs text-gray-500 line-through">
                    {{ formatCurrency(course.discount_price) }}
                  </div>
                </div>
              </TableCell>

              <!-- Status -->
              <TableCell text-align="center">
                <span
                  :class="[
                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                    getStatusColor(course.status)
                  ]"
                >
                  {{ capitalizeWords(course.status) }}
                </span>
              </TableCell>

              <!-- Actions -->
              <TableCell text-align="right">
                <div class="flex items-center justify-end space-x-2">
                  <Link 
                    :href="route('admin.courses.builder', course.id)"
                    :class="buttonVariants({ variant: 'ghost', size: 'icon-sm' })"
                    title="Open Course Builder"
                  >
                    <Icon name="Layers" class="h-4 w-4" />
                  </Link>
                  
                  <Link 
                    :href="route('admin.courses.edit', course.id)"
                    :class="buttonVariants({ variant: 'ghost', size: 'icon-sm' })"
                  >
                    <Icon name="Edit" class="h-4 w-4" />
                  </Link>
                  
                  <Button
                    @click="toggleFeatured(course)"
                    variant="ghost"
                    size="icon-sm"
                    :class="course.is_featured ? 'text-purple-600 hover:text-purple-700' : 'text-gray-600 hover:text-gray-700'"
                  >
                    <Icon :name="course.is_featured ? 'StarOff' : 'Star'" class="h-4 w-4" />
                  </Button>
                  
                  <Button
                    @click="deleteCourse(course)"
                    variant="ghost"
                    size="icon-sm"
                    class="text-red-600 hover:text-red-700"
                    :disabled="course.enrollments_count > 0"
                  >
                    <Icon name="Trash2" class="h-4 w-4" />
                  </Button>
                </div>
              </TableCell>
            </TableRow>

            <!-- Empty State -->
            <TableRow v-if="courses.data.length === 0">
              <TableCell :colspan="7" class="text-center py-12">
                <div class="flex flex-col items-center justify-center space-y-3">
                  <Icon name="BookOpen" class="h-12 w-12 text-gray-400" />
                  <div class="text-gray-500">No courses found</div>
                  <Link 
                    :href="route('admin.courses.create')"
                    :class="buttonVariants({ variant: 'primary' })"
                  >
                    Create First Course
                  </Link>
                </div>
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </CardContent>
    </Card>

    <!-- Pagination -->
    <div v-if="courses.last_page > 1" class="flex justify-center mt-6">
      <nav class="flex items-center space-x-2">
        <Button
          :disabled="!courses.prev_page_url"
          @click="goToPage(courses.current_page - 1)"
          variant="outline"
          size="sm"
        >
          Previous
        </Button>
        
        <span class="px-3 py-1 text-sm text-gray-700">
          Page {{ courses.current_page }} of {{ courses.last_page }}
        </span>
        
        <Button
          :disabled="!courses.next_page_url"
          @click="goToPage(courses.current_page + 1)"
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
import { getStatusColor, truncateText, capitalizeWords, formatCurrency, buttonVariants } from '@/lib/utils'

// Toast composable
const { success, error } = useToast()

// Props
interface Course {
  id: number
  title: string
  slug: string
  thumbnail_image?: string
  level: string
  is_featured: boolean
  is_free: boolean
  price: number
  discount_price?: number
  status: string
  enrollments_count: number
  modules_count: number
  lessons_count: number
  category?: {
    id: number
    name: string
  }
  instructor?: {
    id: number
    full_name: string
    user?: {
      id: number
      name: string
    }
  }
}

interface Props {
  courses: {
    data: Course[]
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
  router.get(route('admin.courses.index'), { page }, {
    preserveState: true,
    preserveScroll: true,
  })
}

const toggleFeatured = (course: Course) => {
  router.patch(route('admin.courses.toggle-featured', course.id), {}, {
    preserveScroll: true,
    onSuccess: () => {
      const status = !course.is_featured ? 'ফিচার্ড' : 'সাধারণ'
      success(`কোর্স ${status} করা হয়েছে।`)
    },
    onError: () => {
      error('কোর্স ফিচার্ড স্ট্যাটাস পরিবর্তন করতে সমস্যা হয়েছে।')
    }
  })
}

const deleteCourse = (course: Course) => {
  if (course.enrollments_count > 0) {
    error('এই কোর্সে শিক্ষার্থী নথিভুক্ত রয়েছে। কোর্স মুছে ফেলা যাবে না।')
    return
  }

  if (confirm('আপনি কি নিশ্চিত যে এই কোর্স মুছে ফেলতে চান?')) {
    router.delete(route('admin.courses.destroy', course.id), {
      preserveScroll: true,
      onSuccess: () => {
        success('কোর্স সফলভাবে মুছে ফেলা হয়েছে।')
      },
      onError: () => {
        error('কোর্স মুছে ফেলতে সমস্যা হয়েছে।')
      }
    })
  }
}
</script>

<style scoped>
.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style> 