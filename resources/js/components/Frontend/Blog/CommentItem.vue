<template>
  <article class="bg-neutral-50 rounded-lg p-4">
    <!-- Comment Header -->
    <div class="flex items-start space-x-3 mb-3">
      <img 
        v-if="comment.author_avatar"
        :src="comment.author_avatar" 
        :alt="comment.author_name"
        class="w-10 h-10 rounded-full"
      />
      <div 
        v-else
        class="w-10 h-10 rounded-full bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] flex items-center justify-center"
      >
        <span class="text-white font-semibold text-sm">
          {{ getInitials(comment.author_name) }}
        </span>
      </div>
      
      <div class="flex-1">
        <div class="flex items-center space-x-2">
          <h4 class="font-semibold text-primary">{{ comment.author_name }}</h4>
          <span class="text-sm text-muted">{{ formatDate(comment.created_at) }}</span>
        </div>
        
        <!-- Comment Content -->
        <div class="mt-2">
          <p class="text-neutral-700 leading-relaxed">{{ comment.content }}</p>
        </div>
        
        <!-- Comment Actions -->
        <div class="flex items-center space-x-4 mt-3">
          <button
            @click="toggleReplyForm"
            class="text-sm text-[#5f5fcd] hover:text-[#4a4aa6] font-medium flex items-center"
          >
            <ReplyIcon class="w-4 h-4 mr-1" />
            উত্তর দিন
          </button>
          
          <button
            @click="toggleLike"
            class="text-sm text-muted hover:text-[#5f5fcd] flex items-center"
            :class="{ 'text-[#5f5fcd]': isLiked }"
          >
            <ThumbsUpIcon class="w-4 h-4 mr-1" :class="{ 'fill-current': isLiked }" />
            পছন্দ ({{ likeCount }})
          </button>
        </div>
      </div>
    </div>

    <!-- Reply Form -->
    <div v-if="showReplyForm" class="mt-4 ml-13 bg-white rounded-lg p-4 border border-neutral-200">
      <form @submit.prevent="submitReply" class="space-y-3">
        <div v-if="!isAuthenticated" class="grid grid-cols-1 md:grid-cols-2 gap-3">
          <input
            v-model="replyForm.name"
            type="text"
            required
            class="px-3 py-2 border border-neutral-300 rounded-lg focus:ring-[#5f5fcd] focus:border-[#5f5fcd] text-sm"
            placeholder="আপনার নাম"
          />
          <input
            v-model="replyForm.email"
            type="email"
            required
            class="px-3 py-2 border border-neutral-300 rounded-lg focus:ring-[#5f5fcd] focus:border-[#5f5fcd] text-sm"
            placeholder="আপনার ইমেইল"
          />
        </div>
        
        <textarea
          v-model="replyForm.content"
          rows="3"
          required
          class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-[#5f5fcd] focus:border-[#5f5fcd] resize-none text-sm"
          placeholder="আপনার উত্তর লিখুন..."
        ></textarea>
        
        <div class="flex items-center space-x-3">
          <button
            type="submit"
            :disabled="replySubmitting"
            class="px-4 py-2 bg-[#5f5fcd] text-white text-sm font-medium rounded-lg hover:bg-[#4a4aa6] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#5f5fcd] disabled:opacity-50"
          >
            {{ replySubmitting ? 'পাঠানো হচ্ছে...' : 'উত্তর পাঠান' }}
          </button>
          <button
            type="button"
            @click="showReplyForm = false"
            class="px-4 py-2 text-secondary text-sm font-medium hover:text-neutral-800"
          >
            বাতিল
          </button>
        </div>
      </form>
    </div>

    <!-- Replies -->
    <div v-if="comment.replies && comment.replies.length > 0" class="mt-4 ml-13 space-y-4">
      <CommentItem
        v-for="reply in comment.replies"
        :key="reply.id"
        :comment="reply"
        :post-id="postId"
        :is-reply="true"
        @reply-added="$emit('reply-added', $event)"
      />
    </div>
  </article>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { Reply as ReplyIcon, ThumbsUp as ThumbsUpIcon } from 'lucide-vue-next'

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
  likes_count?: number
  user_liked?: boolean
}

interface Props {
  comment: Comment
  postId: number
  isReply?: boolean
}

const props = defineProps<Props>()
const emit = defineEmits<{
  replyAdded: [reply: Comment]
}>()

const page = usePage()
const isAuthenticated = computed(() => !!page.props.auth?.user)

const showReplyForm = ref(false)
const replySubmitting = ref(false)
const isLiked = ref(props.comment.user_liked || false)
const likeCount = ref(props.comment.likes_count || 0)

const replyForm = ref({
  name: '',
  email: '',
  content: ''
})

const toggleReplyForm = () => {
  showReplyForm.value = !showReplyForm.value
  if (!showReplyForm.value) {
    replyForm.value = { name: '', email: '', content: '' }
  }
}

const submitReply = async () => {
  replySubmitting.value = true
  
  try {
    const formData = {
      blog_post_id: props.postId,
      parent_id: props.comment.id,
      content: replyForm.value.content,
      ...(isAuthenticated.value 
        ? {} 
        : {
            author_name: replyForm.value.name,
            author_email: replyForm.value.email
          }
      )
    }

    await useForm(formData).post(route('frontend.blog.comments.store'), {
      preserveScroll: true,
      onSuccess: () => {
        replyForm.value = { name: '', email: '', content: '' }
        showReplyForm.value = false
        console.log('Reply submitted successfully')
      },
      onError: (errors) => {
        console.error('Reply submission errors:', errors)
      }
    })
  } catch (error) {
    console.error('Error submitting reply:', error)
  } finally {
    replySubmitting.value = false
  }
}

const toggleLike = async () => {
  try {
    // Toggle like state optimistically
    isLiked.value = !isLiked.value
    likeCount.value += isLiked.value ? 1 : -1

    // Send request to backend
    await useForm({
      comment_id: props.comment.id,
      action: isLiked.value ? 'like' : 'unlike'
    }).post(route('frontend.blog.comments.like'), {
      preserveScroll: true,
      onError: () => {
        // Revert on error
        isLiked.value = !isLiked.value
        likeCount.value += isLiked.value ? 1 : -1
      }
    })
  } catch (error) {
    console.error('Error toggling like:', error)
    // Revert on error
    isLiked.value = !isLiked.value
    likeCount.value += isLiked.value ? 1 : -1
  }
}

const formatDate = (date: string) => {
  const d = new Date(date)
  const now = new Date()
  const diffTime = Math.abs(now.getTime() - d.getTime())
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
  
  if (diffDays === 1) {
    return 'গতকাল'
  } else if (diffDays < 7) {
    return `${diffDays} দিন আগে`
  } else {
    const options: Intl.DateTimeFormatOptions = { 
      month: 'short', 
      day: 'numeric' 
    }
    return new Intl.DateTimeFormat('bn-BD', options).format(d)
  }
}

const getInitials = (name: string): string => {
  return name
    .split(' ')
    .map(word => word.charAt(0))
    .join('')
    .toUpperCase()
    .slice(0, 2)
}
</script>