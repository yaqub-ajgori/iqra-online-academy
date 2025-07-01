<template>
  <AdminLayout>
    <!-- Course Builder Content -->
    <div class="px-6 py-6">
      <!-- Back Navigation -->
      <div class="mb-6">
        <Link 
          :href="route('admin.courses.index')"
          class="inline-flex items-center text-gray-600 hover:text-[#2d5a27] transition-colors"
        >
          <Icon name="ArrowLeft" class="mr-2 h-4 w-4" />
          Back to Courses
        </Link>
      </div>

      <!-- Action Bar -->
      <div class="flex items-center justify-between mb-8">
        <div>
          <h2 class="text-xl font-semibold text-gray-900">Course Content</h2>
          <p class="text-sm text-gray-600 mt-1">
            Add modules and lessons to build your course structure
          </p>
        </div>
        <button 
          @click="showAddModuleForm = true"
          class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] text-white text-sm font-medium rounded-lg hover:opacity-90 transition-all shadow-lg"
        >
          <Icon name="Plus" class="mr-2 h-4 w-4" />
          Add Module
        </button>
      </div>

      <!-- Add Module Form -->
      <div v-if="showAddModuleForm" class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-medium text-gray-900">Add New Module</h3>
          <button 
            @click="cancelAddModule"
            class="text-gray-400 hover:text-gray-600"
          >
            <Icon name="X" class="h-5 w-5" />
          </button>
        </div>
        <form @submit.prevent="submitModule">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Module Title*</label>
              <input 
                v-model="moduleForm.title"
                type="text" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5a27] focus:border-[#2d5a27]"
                placeholder="Enter module title"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
              <select 
                v-model="moduleForm.is_active"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5a27] focus:border-[#2d5a27]"
              >
                <option :value="true">Active</option>
                <option :value="false">Inactive</option>
              </select>
            </div>
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea 
              v-model="moduleForm.description"
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5a27] focus:border-[#2d5a27]"
              placeholder="Enter module description"
            ></textarea>
          </div>
          <div class="flex justify-end space-x-3">
            <button 
              type="button"
              @click="cancelAddModule"
              class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50"
            >
              Cancel
            </button>
            <button 
              type="submit"
              class="px-4 py-2 bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] text-white rounded-lg hover:opacity-90 transition-all"
              :disabled="moduleProcessing"
            >
              {{ moduleProcessing ? 'Adding...' : 'Add Module' }}
            </button>
          </div>
        </form>
      </div>

      <!-- Modules List -->
      <div v-if="course.modules && course.modules.length > 0" class="space-y-6">
        <div
          v-for="(module, index) in course.modules"
          :key="module.id"
          class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden"
        >
          <!-- Module Header -->
          <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
              <div 
                @click="toggleModule(module)"
                class="flex items-center space-x-4 cursor-pointer hover:opacity-80 transition-opacity flex-1"
              >
                <div class="flex items-center space-x-3">
                  <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] text-white rounded-lg font-semibold">
                    {{ module.sort_order || (index + 1) }}
                  </div>
                  <div>
                    <h3 class="text-lg font-medium text-gray-900">{{ module.title }}</h3>
                    <p class="text-sm text-gray-500">{{ module.lessons?.length || 0 }} lessons</p>
                  </div>
                  <span
                    :class="{
                      'bg-green-100 text-green-800': module.is_active,
                      'bg-gray-100 text-gray-800': !module.is_active
                    }"
                    class="inline-flex px-2 py-1 text-xs font-medium rounded-full"
                  >
                    {{ module.is_active ? 'Active' : 'Inactive' }}
                  </span>
                </div>
                <div class="ml-auto">
                  <Icon 
                    :name="expandedModule === module.id ? 'ChevronUp' : 'ChevronDown'"
                    class="h-5 w-5 text-gray-400 transition-transform"
                  />
                </div>
              </div>
              <div class="flex items-center space-x-3 ml-4">
                <button 
                  @click.stop="toggleAddLesson(module)"
                  class="inline-flex items-center px-3 py-1.5 text-sm text-white bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] hover:opacity-90 rounded-lg transition-all"
                >
                  <Icon name="Plus" class="mr-1 h-4 w-4" />
                  Add Lesson
                </button>
                <div class="flex space-x-1">
                  <button 
                    @click.stop="editModule(module)"
                    class="p-2 text-gray-400 hover:text-[#2d5a27] hover:bg-green-50 rounded-lg transition-colors"
                    title="Edit Module"
                  >
                    <Icon name="Edit" class="h-4 w-4" />
                  </button>
                  <button 
                    @click.stop="deleteModule(module)"
                    class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                    title="Delete Module"
                  >
                    <Icon name="Trash2" class="h-4 w-4" />
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Collapsible Module Content -->
          <transition 
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0 max-h-0"
            enter-to-class="opacity-100 max-h-screen"
            leave-active-class="transition-all duration-300 ease-in"
            leave-from-class="opacity-100 max-h-screen"
            leave-to-class="opacity-0 max-h-0"
          >
            <div v-if="expandedModule === module.id" class="overflow-hidden">
            <!-- Module Description -->
            <div v-if="module.description" class="px-6 py-3 bg-gray-25 border-b border-gray-100">
              <p class="text-sm text-gray-600">{{ module.description }}</p>
            </div>

            <!-- Edit Module Form -->
            <div v-if="showEditModuleForm === module.id" class="px-6 py-4 bg-gradient-to-r from-green-50 to-purple-50 border-b border-gray-200">
            <h4 class="text-md font-medium text-gray-900 mb-4">Edit Module</h4>
            <form @submit.prevent="updateModule">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Module Title*</label>
                  <input 
                    v-model="editModuleForm.title"
                    type="text" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5a27] focus:border-[#2d5a27]"
                    placeholder="Enter module title"
                    required
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                  <select 
                    v-model="editModuleForm.is_active"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5a27] focus:border-[#2d5a27]"
                  >
                    <option :value="true">Active</option>
                    <option :value="false">Inactive</option>
                  </select>
                </div>
              </div>
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea 
                  v-model="editModuleForm.description"
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5a27] focus:border-[#2d5a27]"
                  placeholder="Enter module description"
                ></textarea>
              </div>
              <div class="flex justify-end space-x-3">
                <button 
                  type="button"
                  @click="cancelEditModule"
                  class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50"
                >
                  Cancel
                </button>
                <button 
                  type="submit"
                  class="px-4 py-2 bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] text-white rounded-lg hover:opacity-90 transition-all"
                  :disabled="moduleProcessing"
                >
                  {{ moduleProcessing ? 'Updating...' : 'Update Module' }}
                </button>
              </div>
            </form>
          </div>

          <!-- Add Lesson Form -->
          <div v-if="showAddLessonForm === module.id" class="px-6 py-4 bg-gradient-to-r from-green-50 to-purple-50 border-b border-gray-200">
            <h4 class="text-md font-medium text-gray-900 mb-4">Add New Lesson</h4>
            <form @submit.prevent="submitLesson(module)">
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Lesson Title*</label>
                <input 
                  v-model="lessonForm.title"
                  type="text" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5a27] focus:border-[#2d5a27]"
                  :class="{ 'border-red-500': errors?.title }"
                  placeholder="Enter lesson title"
                  required
                />
                <div v-if="errors?.title" class="mt-1 text-sm text-red-600">{{ errors.title }}</div>
              </div>
              
              <div class="mb-4">
                <p class="text-sm text-gray-600 mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                  <strong>Note:</strong> You can add video content, text content, or both to create your lesson. At least one type of content is required.
                </p>
              </div>
              
              <!-- Video Content Section -->
              <div class="mb-6">
                <h5 class="text-md font-medium text-gray-900 mb-3 flex items-center">
                  <Icon name="Play" class="mr-2 h-4 w-4 text-purple-600" />
                  Video Content (Optional)
                </h5>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Video URL</label>
                    <input 
                      v-model="lessonForm.video_url"
                      type="url" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5a27] focus:border-[#2d5a27]"
                      :class="{ 'border-red-500': errors?.video_url }"
                      placeholder="https://youtube.com/watch?v=... or https://vimeo.com/..."
                    />
                    <div v-if="errors?.video_url" class="mt-1 text-sm text-red-600">{{ errors.video_url }}</div>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes)</label>
                    <input 
                      v-model.number="lessonForm.video_duration"
                      type="number" 
                      min="0"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5a27] focus:border-[#2d5a27]"
                      placeholder="10"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Provider</label>
                    <select 
                      v-model="lessonForm.video_provider"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5a27] focus:border-[#2d5a27]"
                    >
                      <option value="">Auto-detect</option>
                      <option value="youtube">YouTube</option>
                      <option value="vimeo">Vimeo</option>
                      <option value="local">Local File</option>
                    </select>
                  </div>
                </div>
              </div>

              <!-- Text Content Section -->
              <div class="mb-6">
                <h5 class="text-md font-medium text-gray-900 mb-3 flex items-center">
                  <Icon name="FileText" class="mr-2 h-4 w-4 text-green-600" />
                  Text Content (Optional)
                </h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reading Time (minutes)</label>
                    <input 
                      v-model.number="lessonForm.reading_time_minutes"
                      type="number" 
                      min="0"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5a27] focus:border-[#2d5a27]"
                      placeholder="5"
                    />
                  </div>
                </div>
                <div>
                  <RichTextEditor
                    v-model="lessonForm.content"
                    label="Lesson Content"
                    placeholder="Write the text content for this lesson..."
                    :required="false"
                    input-id="lesson-content"
                    min-height="300px"
                    :error="errors?.content"
                  />
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">

                <div>
                  <label class="flex items-center">
                    <input 
                      v-model="lessonForm.is_mandatory"
                      type="checkbox"
                      class="mr-2 rounded border-gray-300 text-[#2d5a27] focus:border-[#2d5a27] focus:ring focus:ring-green-200 focus:ring-opacity-50"
                    />
                    <span class="text-sm text-gray-700">Mandatory</span>
                  </label>
                </div>
                <div>
                  <label class="flex items-center">
                    <input 
                      v-model="lessonForm.is_active"
                      type="checkbox"
                      class="mr-2 rounded border-gray-300 text-[#2d5a27] focus:border-[#2d5a27] focus:ring focus:ring-green-200 focus:ring-opacity-50"
                    />
                    <span class="text-sm text-gray-700">Active</span>
                  </label>
                </div>
              </div>

              <div class="flex justify-end space-x-3">
                <button 
                  type="button"
                  @click="cancelAddLesson"
                  class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50"
                >
                  Cancel
                </button>
                <button 
                  type="submit"
                  class="px-4 py-2 bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] text-white rounded-lg hover:opacity-90 transition-all"
                  :disabled="lessonProcessing"
                >
                  {{ lessonProcessing ? 'Adding...' : 'Add Lesson' }}
                </button>
              </div>
            </form>
          </div>

          <!-- Lessons List -->
          <div v-if="module.lessons && module.lessons.length > 0" class="px-6 py-4">
            <div class="space-y-3">
              <div
                v-for="(lesson, lessonIndex) in module.lessons"
                :key="lesson.id"
                class="bg-white border border-gray-100 rounded-lg"
              >
                <!-- Lesson Item -->
                <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-colors">
                  <div class="flex items-center space-x-3">
                    <span class="flex items-center justify-center w-6 h-6 bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] text-white rounded-full text-xs font-medium">
                      {{ lesson.sort_order || (lessonIndex + 1) }}
                    </span>
                    <div class="flex-shrink-0">
                      <div 
                        :class="{
                          'bg-purple-100 text-purple-600': lesson.lesson_type === 'video',
                          'bg-green-100 text-green-600': lesson.lesson_type === 'text',
                          'bg-gradient-to-r from-purple-100 to-green-100 text-gray-700': lesson.lesson_type === 'mixed'
                        }"
                        class="w-8 h-8 rounded-lg flex items-center justify-center"
                      >
                        <Icon 
                          :name="lesson.lesson_type === 'video' ? 'Play' : lesson.lesson_type === 'text' ? 'FileText' : 'Layers'"
                          class="h-4 w-4"
                        />
                      </div>
                    </div>
                    <div class="min-w-0 flex-1">
                      <h4 class="text-sm font-medium text-gray-900">{{ lesson.title }}</h4>
                      <div class="flex items-center space-x-3 text-xs text-gray-500">
                        <span class="capitalize">{{ lesson.lesson_type }}</span>
                        <span v-if="lesson.video_duration">{{ lesson.video_duration }} min</span>
                        <span v-if="lesson.reading_time_minutes">{{ lesson.reading_time_minutes }} min read</span>

                        <span v-if="lesson.is_mandatory" class="text-orange-600">Mandatory</span>
                      </div>
                    </div>
                  </div>
                  <div class="flex space-x-2">
                    <button 
                      @click="editLesson(lesson)"
                      class="p-1 text-gray-400 hover:text-[#2d5a27] hover:bg-green-50 rounded transition-colors"
                      title="Edit Lesson"
                    >
                      <Icon name="Edit" class="h-3 w-3" />
                    </button>
                    <button 
                      @click="deleteLesson(lesson)"
                      class="p-1 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors"
                      title="Delete Lesson"
                    >
                      <Icon name="Trash2" class="h-3 w-3" />
                    </button>
                  </div>
                </div>

                <!-- Edit Lesson Form -->
                <div v-if="showEditLessonForm === lesson.id" class="border-t border-gray-100 p-4 bg-gradient-to-r from-green-50 to-purple-50">
                  <h5 class="text-sm font-medium text-gray-900 mb-3">Edit Lesson</h5>
                  <form @submit.prevent="updateLesson">
                    <div class="mb-4">
                      <label class="block text-sm font-medium text-gray-700 mb-2">Lesson Title*</label>
                      <input 
                        v-model="editLessonForm.title"
                        type="text" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5a27] focus:border-[#2d5a27]"
                        placeholder="Enter lesson title"
                        required
                      />
                    </div>
                    
                    <div class="mb-4">
                      <p class="text-sm text-gray-600 mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <strong>Note:</strong> You can add video content, text content, or both. At least one type of content is required.
                      </p>
                    </div>
                    
                    <!-- Video Content Section -->
                    <div class="mb-6">
                      <h6 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                        <Icon name="Play" class="mr-2 h-4 w-4 text-purple-600" />
                        Video Content (Optional)
                      </h6>
                      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                          <label class="block text-sm font-medium text-gray-700 mb-2">Video URL</label>
                          <input 
                            v-model="editLessonForm.video_url"
                            type="url" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5a27] focus:border-[#2d5a27]"
                            placeholder="https://youtube.com/watch?v=... or https://vimeo.com/..."
                          />
                        </div>
                        <div>
                          <label class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes)</label>
                          <input 
                            v-model.number="editLessonForm.video_duration"
                            type="number" 
                            min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5a27] focus:border-[#2d5a27]"
                            placeholder="10"
                          />
                        </div>
                        <div>
                          <label class="block text-sm font-medium text-gray-700 mb-2">Provider</label>
                          <select 
                            v-model="editLessonForm.video_provider"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5a27] focus:border-[#2d5a27]"
                          >
                            <option value="">Auto-detect</option>
                            <option value="youtube">YouTube</option>
                            <option value="vimeo">Vimeo</option>
                            <option value="local">Local File</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <!-- Text Content Section -->
                    <div class="mb-6">
                      <h6 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                        <Icon name="FileText" class="mr-2 h-4 w-4 text-green-600" />
                        Text Content (Optional)
                      </h6>
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                          <label class="block text-sm font-medium text-gray-700 mb-2">Reading Time (minutes)</label>
                          <input 
                            v-model.number="editLessonForm.reading_time_minutes"
                            type="number" 
                            min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5a27] focus:border-[#2d5a27]"
                            placeholder="5"
                          />
                        </div>
                      </div>
                      <div>
                        <RichTextEditor
                          v-model="editLessonForm.content"
                          label="Lesson Content"
                          placeholder="Write the text content for this lesson..."
                          :required="false"
                          input-id="edit-lesson-content"
                          min-height="300px"
                        />
                      </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                      <div>
                        <label class="flex items-center">
                          <input 
                            v-model="editLessonForm.is_mandatory"
                            type="checkbox"
                            class="mr-2 rounded border-gray-300 text-[#2d5a27] focus:border-[#2d5a27] focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="text-sm text-gray-700">Mandatory</span>
                        </label>
                      </div>
                      <div>
                        <label class="flex items-center">
                          <input 
                            v-model="editLessonForm.is_active"
                            type="checkbox"
                            class="mr-2 rounded border-gray-300 text-[#2d5a27] focus:border-[#2d5a27] focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="text-sm text-gray-700">Active</span>
                        </label>
                      </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                      <button 
                        type="button"
                        @click="cancelEditLesson"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50"
                      >
                        Cancel
                      </button>
                      <button 
                        type="submit"
                        class="px-4 py-2 bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] text-white rounded-lg hover:opacity-90 transition-all"
                        :disabled="lessonProcessing"
                      >
                        {{ lessonProcessing ? 'Updating...' : 'Update Lesson' }}
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

            <!-- Empty Lessons State -->
            <div v-if="!module.lessons || module.lessons.length === 0" class="px-6 py-8 text-center border-t border-gray-100">
              <div class="w-12 h-12 bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] rounded-lg flex items-center justify-center mx-auto mb-3">
                <Icon name="BookOpen" class="h-6 w-6 text-white" />
              </div>
              <h4 class="text-sm font-medium text-gray-900 mb-1">No lessons yet</h4>
              <p class="text-xs text-gray-500 mb-3">Add your first lesson to this module</p>
              <button 
                @click="toggleAddLesson(module)"
                class="inline-flex items-center px-3 py-1.5 text-sm text-white bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] hover:opacity-90 rounded-lg transition-all"
              >
                <Icon name="Plus" class="mr-1 h-4 w-4" />
                Add First Lesson
              </button>
            </div>
            </div>
          </transition>
        </div>
      </div>

      <!-- Empty Modules State -->
      <div v-else class="text-center py-16">
        <div class="w-20 h-20 bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] rounded-full flex items-center justify-center mx-auto mb-6">
          <Icon name="BookOpen" class="h-10 w-10 text-white" />
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Start Building Your Course</h3>
        <p class="text-sm text-gray-500 mb-6 max-w-md mx-auto">
          Create your first module to organize your course content. Each module can contain multiple lessons.
        </p>
        <button 
          @click="showAddModuleForm = true"
          class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] text-white text-sm font-medium rounded-lg hover:opacity-90 transition-all shadow-lg"
        >
          <Icon name="Plus" class="mr-2 h-5 w-5" />
          Create Your First Module
        </button>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import Icon from '@/components/Icon.vue'
import RichTextEditor from '@/components/RichTextEditor.vue'

const props = defineProps({
  course: {
    type: Object,
    required: true
  },
  errors: {
    type: Object,
    default: () => ({})
  }
})

// Reactive data
const showAddModuleForm = ref(false)
const showEditModuleForm = ref(null)
const showAddLessonForm = ref(null)
const showEditLessonForm = ref(null)
const moduleProcessing = ref(false)
const lessonProcessing = ref(false)
const expandedModule = ref(null) // Track which module is expanded

// Auto-expand first module on load
if (props.course.modules && props.course.modules.length > 0) {
  expandedModule.value = props.course.modules[0].id
}

// Forms
const moduleForm = ref({
  title: '',
  description: '',
  is_active: true
})

const editModuleForm = ref({
  id: null,
  title: '',
  description: '',
  is_active: true
})

const lessonForm = ref({
  title: '',
  content: '',
  lesson_type: 'mixed', // Will be auto-determined by backend
  video_url: '',
  video_duration: null,
  video_provider: '',
  reading_time_minutes: null,
  is_mandatory: false,
  is_active: true
})

const editLessonForm = ref({
  id: null,
  title: '',
  content: '',
  lesson_type: 'mixed', // Will be auto-determined by backend
  video_url: '',
  video_duration: null,
  video_provider: '',
  reading_time_minutes: null,
  is_mandatory: false,
  is_active: true
})

// Computed properties (if needed in the future)

// Methods
const submitModule = () => {
  moduleProcessing.value = true
  
  router.post(route('admin.courses.builder.store-module', props.course), moduleForm.value, {
    onSuccess: () => {
      resetModuleForm()
      showAddModuleForm.value = false
    },
    onFinish: () => {
      moduleProcessing.value = false
    }
  })
}

const submitLesson = (module) => {
  lessonProcessing.value = true
  
  router.post(route('admin.courses.builder.store-lesson', [props.course, module]), lessonForm.value, {
    onSuccess: () => {
      resetLessonForm()
      showAddLessonForm.value = null
    },
    onError: (errors) => {
      console.error('Lesson creation failed:', errors)
      // The errors will be handled by Inertia and displayed in the UI
    },
    onFinish: () => {
      lessonProcessing.value = false
    }
  })
}

const updateModule = () => {
  moduleProcessing.value = true
  
  router.put(route('admin.courses.builder.update-module', [props.course, editModuleForm.value.id]), editModuleForm.value, {
    onSuccess: () => {
      resetEditModuleForm()
      showEditModuleForm.value = null
    },
    onFinish: () => {
      moduleProcessing.value = false
    }
  })
}

const updateLesson = () => {
  lessonProcessing.value = true
  
  router.put(route('admin.courses.builder.update-lesson', [props.course, editLessonForm.value.id]), editLessonForm.value, {
    onSuccess: () => {
      resetEditLessonForm()
      showEditLessonForm.value = null
    },
    onFinish: () => {
      lessonProcessing.value = false
    }
  })
}

const deleteModule = (module) => {
  if (confirm(`Are you sure you want to delete "${module.title}"? This will also delete all lessons in this module.`)) {
    router.delete(route('admin.courses.builder.delete-module', [props.course, module]), {
      preserveScroll: true
    })
  }
}

const deleteLesson = (lesson) => {
  if (confirm(`Are you sure you want to delete "${lesson.title}"?`)) {
    router.delete(route('admin.courses.builder.delete-lesson', [props.course, lesson]), {
      preserveScroll: true
    })
  }
}

const editModule = (module) => {
  editModuleForm.value = {
    id: module.id,
    title: module.title,
    description: module.description || '',
    is_active: module.is_active
  }
  showEditModuleForm.value = module.id
  expandedModule.value = module.id // Expand the module being edited
  // Close other forms
  showAddModuleForm.value = false
  showAddLessonForm.value = null
  showEditLessonForm.value = null
}

const editLesson = (lesson) => {
  editLessonForm.value = {
    id: lesson.id,
    title: lesson.title,
    content: lesson.content || '',
    lesson_type: lesson.lesson_type,
    video_url: lesson.video_url || '',
    video_duration: lesson.video_duration,
    video_provider: lesson.video_provider || '',
    reading_time_minutes: lesson.reading_time_minutes,
    is_mandatory: lesson.is_mandatory,
    is_active: lesson.is_active
  }
  showEditLessonForm.value = lesson.id
  // Expand the module containing this lesson
  const moduleId = props.course.modules.find(module => 
    module.lessons && module.lessons.some(l => l.id === lesson.id)
  )?.id
  if (moduleId) {
    expandedModule.value = moduleId
  }
  // Close other forms
  showAddModuleForm.value = false
  showAddLessonForm.value = null
  showEditModuleForm.value = null
}

const toggleModule = (module) => {
  if (expandedModule.value === module.id) {
    expandedModule.value = null
  } else {
    expandedModule.value = module.id
    // Close all forms when switching modules
    showAddModuleForm.value = false
    showEditModuleForm.value = null
    showAddLessonForm.value = null
    showEditLessonForm.value = null
  }
}

const toggleAddLesson = (module) => {
  // Ensure the module is expanded first
  if (expandedModule.value !== module.id) {
    expandedModule.value = module.id
  }
  
  if (showAddLessonForm.value === module.id) {
    showAddLessonForm.value = null
  } else {
    showAddLessonForm.value = module.id
    // Close other forms
    showAddModuleForm.value = false
    showEditModuleForm.value = null
    showEditLessonForm.value = null
  }
}

const cancelAddModule = () => {
  showAddModuleForm.value = false
  resetModuleForm()
}

const cancelEditModule = () => {
  showEditModuleForm.value = null
  resetEditModuleForm()
}

const cancelAddLesson = () => {
  showAddLessonForm.value = null
  resetLessonForm()
}

const cancelEditLesson = () => {
  showEditLessonForm.value = null
  resetEditLessonForm()
}

const resetModuleForm = () => {
  moduleForm.value = {
    title: '',
    description: '',
    is_active: true
  }
}

const resetEditModuleForm = () => {
  editModuleForm.value = {
    id: null,
    title: '',
    description: '',
    is_active: true
  }
}

const resetLessonForm = () => {
  lessonForm.value = {
    title: '',
    content: '',
    lesson_type: 'mixed',
    video_url: '',
    video_duration: null,
    video_provider: '',
    reading_time_minutes: null,
    is_mandatory: false,
    is_active: true
  }
}

const resetEditLessonForm = () => {
  editLessonForm.value = {
    id: null,
    title: '',
    content: '',
    lesson_type: 'mixed',
    video_url: '',
    video_duration: null,
    video_provider: '',
    reading_time_minutes: null,
    is_mandatory: false,
    is_active: true
  }
}
</script> 