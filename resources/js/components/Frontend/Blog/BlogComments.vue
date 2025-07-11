<template>
  <section class="bg-white rounded-2xl p-6 shadow-islamic border border-neutral-100">
    <h3 class="text-xl font-bold text-primary mb-6 flex items-center">
      <MessageCircleIcon class="w-6 h-6 mr-2 text-[#5f5fcd]" />
      মন্তব্য ({{ comments.length }})
    </h3>

    <!-- Comment Form -->
    <div class="mb-8">
      <h4 class="text-lg font-semibold text-primary mb-4">মন্তব্য করুন</h4>
      
      <!-- Authentication Required Message -->
      <div v-if="!isAuthenticated" class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
        <div class="flex items-center justify-center mb-4">
          <MessageCircleIcon class="w-8 h-8 text-blue-600" />
        </div>
        <h5 class="text-lg font-semibold text-blue-900 mb-2">মন্তব্য করতে লগইন প্রয়োজন</h5>
        <p class="text-blue-700 mb-4">
          আমাদের ব্লগে মন্তব্য করার জন্য আপনাকে অবশ্যই লগইন করতে হবে।
        </p>
        <div class="flex items-center justify-center space-x-4">
          <Link 
            :href="route('login')" 
            class="px-6 py-2 bg-[#5f5fcd] text-white font-medium rounded-lg hover:bg-[#4a4aa6] transition-colors"
          >
            লগইন করুন
          </Link>
          <Link 
            :href="route('register')" 
            class="px-6 py-2 border border-[#5f5fcd] text-[#5f5fcd] font-medium rounded-lg hover:bg-[#5f5fcd] hover:text-white transition-colors"
          >
            রেজিস্টার করুন
          </Link>
        </div>
      </div>

      <!-- Comment Form for Authenticated Users -->
      <form v-else @submit.prevent="submitComment" class="space-y-4">
        <div>
          <label for="comment" class="block text-sm font-medium text-neutral-700 mb-2">মন্তব্য *</label>
          <textarea
            id="comment"
            v-model="commentForm.content"
            rows="4"
            required
            class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-[#5f5fcd] focus:border-[#5f5fcd] resize-none"
            placeholder="আপনার মন্তব্য লিখুন..."
          ></textarea>
        </div>
        
        <div class="flex items-center justify-between">
          <p class="text-sm text-secondary">
            মন্তব্য প্রকাশের আগে মডারেশনের জন্য অপেক্ষা করতে হবে।
          </p>
          <button
            type="submit"
            :disabled="submitting"
            class="px-6 py-3 bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] text-white font-semibold rounded-lg hover:from-[#4a4aa6] hover:to-[#1f3e1b] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#5f5fcd] transition-all duration-200 disabled:opacity-50"
          >
            {{ submitting ? 'পাঠানো হচ্ছে...' : 'মন্তব্য পাঠান' }}
          </button>
        </div>
      </form>
    </div>

    <!-- Comments List -->
    <div v-if="comments.length > 0" class="space-y-6">
      <CommentItem
        v-for="comment in topLevelComments"
        :key="comment.id"
        :comment="comment"
        :post-id="postId"
        @reply-added="handleReplyAdded"
      />
    </div>
    
    <div v-else class="text-center py-8">
      <MessageCircleIcon class="w-12 h-12 text-neutral-300 mx-auto mb-3" />
      <p class="text-muted">এই পোস্টে এখনো কোন মন্তব্য নেই। প্রথম মন্তব্য করুন!</p>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useForm, usePage, Link } from '@inertiajs/vue3'
import { MessageCircle as MessageCircleIcon } from 'lucide-vue-next'
import CommentItem from './CommentItem.vue'

interface Comment {
  id: number
  content: string
  author_name: string
  author_email?: string
  author_avatar?: string
  created_at: string
  status: string
  parent_id?: number
  replies?: Comment[]
  user_id?: number
}

interface Props {
  postId: number
  comments: Comment[]
}

const props = defineProps<Props>()
const emit = defineEmits<{
  commentAdded: [comment: Comment]
}>()

const page = usePage()
const isAuthenticated = computed(() => !!page.props.auth?.user)

const submitting = ref(false)
const commentForm = ref({
  name: '',
  email: '',
  content: ''
})

const topLevelComments = computed(() => {
  return props.comments.filter(comment => !comment.parent_id)
})

const submitComment = async () => {
  if (!isAuthenticated.value) {
    return
  }

  submitting.value = true
  
  try {
    const formData = {
      blog_post_id: props.postId,
      content: commentForm.value.content
    }

    // Submit comment via Inertia
    await useForm(formData).post(route('frontend.blog.comments.store'), {
      preserveScroll: true,
      onSuccess: () => {
        // Reset form
        commentForm.value = {
          name: '',
          email: '',
          content: ''
        }
        
        // Show success message
        console.log('Comment submitted successfully')
      },
      onError: (errors) => {
        console.error('Comment submission errors:', errors)
      }
    })
  } catch (error) {
    console.error('Error submitting comment:', error)
  } finally {
    submitting.value = false
  }
}

const handleReplyAdded = (reply: Comment) => {
  emit('commentAdded', reply)
}
</script>