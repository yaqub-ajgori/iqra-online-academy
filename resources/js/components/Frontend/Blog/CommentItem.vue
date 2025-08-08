<template>
    <article class="rounded-lg border p-4 transition-all duration-200" :class="commentClasses">
        <!-- Comment Header -->
        <div class="mb-3 flex items-start space-x-3">
            <img v-if="comment.author_avatar" :src="comment.author_avatar" :alt="comment.author_name" class="h-10 w-10 rounded-full" />
            <div v-else class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27]">
                <span class="text-sm font-semibold text-white">
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
                    <p class="leading-relaxed text-neutral-700">{{ comment.content }}</p>
                </div>

                <!-- Comment Actions -->
                <div class="mt-3 flex items-center space-x-4">
                    <button @click="toggleReplyForm" class="flex items-center text-sm font-medium text-[#5f5fcd] hover:text-[#4a4aa6]">
                        <ReplyIcon class="mr-1 h-4 w-4" />
                        উত্তর দিন
                    </button>

                    <button
                        @click="toggleLike"
                        class="flex items-center text-sm text-muted hover:text-[#5f5fcd]"
                        :class="{ 'text-[#5f5fcd]': isLiked }"
                    >
                        <ThumbsUpIcon class="mr-1 h-4 w-4" :class="{ 'fill-current': isLiked }" />
                        পছন্দ ({{ likeCount }})
                    </button>
                </div>
            </div>
        </div>

        <!-- Reply Form -->
        <div v-if="showReplyForm" class="mt-4 ml-13 rounded-lg border border-[#5f5fcd]/20 bg-gradient-to-r from-blue-50 to-indigo-50 p-4">
            <form @submit.prevent="submitReply" class="space-y-3">
                <div v-if="!isAuthenticated" class="grid grid-cols-1 gap-3 md:grid-cols-2">
                    <input
                        v-model="replyForm.name"
                        type="text"
                        required
                        class="rounded-lg border border-neutral-300 px-3 py-2 text-sm focus:border-[#5f5fcd] focus:ring-[#5f5fcd]"
                        placeholder="আপনার নাম"
                    />
                    <input
                        v-model="replyForm.email"
                        type="email"
                        required
                        class="rounded-lg border border-neutral-300 px-3 py-2 text-sm focus:border-[#5f5fcd] focus:ring-[#5f5fcd]"
                        placeholder="আপনার ইমেইল"
                    />
                </div>

                <textarea
                    v-model="replyForm.content"
                    rows="3"
                    required
                    class="w-full resize-none rounded-lg border border-neutral-300 px-3 py-2 text-sm focus:border-[#5f5fcd] focus:ring-[#5f5fcd]"
                    placeholder="আপনার উত্তর লিখুন..."
                ></textarea>

                <div class="flex items-center space-x-3">
                    <button
                        type="submit"
                        :disabled="replySubmitting"
                        class="rounded-lg bg-[#5f5fcd] px-4 py-2 text-sm font-medium text-white hover:bg-[#4a4aa6] focus:ring-2 focus:ring-[#5f5fcd] focus:ring-offset-2 focus:outline-none disabled:opacity-50"
                    >
                        {{ replySubmitting ? 'পাঠানো হচ্ছে...' : 'উত্তর পাঠান' }}
                    </button>
                    <button type="button" @click="showReplyForm = false" class="px-4 py-2 text-sm font-medium text-secondary hover:text-neutral-800">
                        বাতিল
                    </button>
                </div>
            </form>
        </div>

        <!-- Replies -->
        <div v-if="comment.replies && comment.replies.length > 0" class="mt-6 ml-8 space-y-4 border-l-2 border-[#5f5fcd]/20 pl-4">
            <div class="mb-3 flex items-center">
                <div class="h-px flex-1 bg-gradient-to-r from-[#5f5fcd]/20 to-transparent"></div>
                <span class="mx-3 text-xs font-medium text-[#5f5fcd]">{{ comment.replies.length }} টি উত্তর</span>
                <div class="h-px flex-1 bg-gradient-to-l from-[#5f5fcd]/20 to-transparent"></div>
            </div>
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
import { useForm, usePage } from '@inertiajs/vue3';
import { Reply as ReplyIcon, ThumbsUp as ThumbsUpIcon } from 'lucide-vue-next';
import { computed, ref } from 'vue';

interface Comment {
    id: number;
    content: string;
    author_name: string;
    author_email?: string;
    author_avatar?: string;
    created_at: string;
    status: string;
    parent_id?: number;
    replies?: Comment[];
    user_id?: number;
    likes_count?: number;
    user_liked?: boolean;
}

interface Props {
    comment: Comment;
    postId: number;
    isReply?: boolean;
}

const props = defineProps<Props>();
const emit = defineEmits<{
    replyAdded: [reply: Comment];
}>();

const page = usePage();
const isAuthenticated = computed(() => !!page.props.auth?.user);

const showReplyForm = ref(false);
const replySubmitting = ref(false);
const isLiked = ref(props.comment.user_liked || false);
const likeCount = ref(props.comment.likes_count || 0);

const replyForm = ref({
    name: '',
    email: '',
    content: '',
});

const commentClasses = computed(() => {
    if (props.isReply) {
        return [
            'bg-gradient-to-r from-purple-50 to-blue-50',
            'border-[#5f5fcd]/30',
            'ml-2',
            'hover:from-purple-100 hover:to-blue-100',
            'hover:border-[#5f5fcd]/50'
        ];
    } else {
        return [
            'bg-gradient-to-r from-gray-50 to-neutral-50',
            'border-neutral-200',
            'hover:from-gray-100 hover:to-neutral-100',
            'hover:border-neutral-300',
            'hover:shadow-sm'
        ];
    }
});

const toggleReplyForm = () => {
    showReplyForm.value = !showReplyForm.value;
    if (!showReplyForm.value) {
        replyForm.value = { name: '', email: '', content: '' };
    }
};

const submitReply = async () => {
    replySubmitting.value = true;

    try {
        const formData = {
            blog_post_id: props.postId,
            parent_id: props.comment.id,
            content: replyForm.value.content,
            ...(isAuthenticated.value
                ? {}
                : {
                      author_name: replyForm.value.name,
                      author_email: replyForm.value.email,
                  }),
        };

        await useForm(formData).post(route('frontend.blog.comments.store'), {
            preserveScroll: true,
            onSuccess: () => {
                replyForm.value = { name: '', email: '', content: '' };
                showReplyForm.value = false;
                // Reply submitted successfully
            },
            onError: (errors) => {
                // Reply submission errors
            },
        });
    } catch (error) {
        // Error submitting reply
    } finally {
        replySubmitting.value = false;
    }
};

const toggleLike = async () => {
    try {
        // Toggle like state optimistically
        isLiked.value = !isLiked.value;
        likeCount.value += isLiked.value ? 1 : -1;

        // Send request to backend
        await useForm({
            comment_id: props.comment.id,
            action: isLiked.value ? 'like' : 'unlike',
        }).post(route('frontend.blog.comments.like'), {
            preserveScroll: true,
            onError: () => {
                // Revert on error
                isLiked.value = !isLiked.value;
                likeCount.value += isLiked.value ? 1 : -1;
            },
        });
    } catch (error) {
        // Error toggling like
        // Revert on error
        isLiked.value = !isLiked.value;
        likeCount.value += isLiked.value ? 1 : -1;
    }
};

const formatDate = (date: string) => {
    const d = new Date(date);
    const now = new Date();
    const diffTime = Math.abs(now.getTime() - d.getTime());
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    if (diffDays === 1) {
        return 'গতকাল';
    } else if (diffDays < 7) {
        return `${diffDays} দিন আগে`;
    } else {
        const options: Intl.DateTimeFormatOptions = {
            month: 'short',
            day: 'numeric',
        };
        return new Intl.DateTimeFormat('bn-BD', options).format(d);
    }
};

const getInitials = (name: string): string => {
    return name
        .split(' ')
        .map((word) => word.charAt(0))
        .join('')
        .toUpperCase()
        .slice(0, 2);
};
</script>
