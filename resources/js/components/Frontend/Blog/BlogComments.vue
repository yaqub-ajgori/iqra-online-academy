<template>
    <section class="shadow-islamic rounded-2xl border border-neutral-100 bg-white p-6">
        <h3 class="mb-6 flex items-center text-xl font-bold text-primary">
            <MessageCircleIcon class="mr-2 h-6 w-6 text-[#5f5fcd]" />
            মন্তব্য ({{ comments.length }})
        </h3>

        <!-- Comment Form -->
        <div class="mb-8">
            <h4 class="mb-4 text-lg font-semibold text-primary">মন্তব্য করুন</h4>

            <!-- Authentication Required Message -->
            <div v-if="!isAuthenticated" class="rounded-lg border border-blue-200 bg-blue-50 p-6 text-center">
                <div class="mb-4 flex items-center justify-center">
                    <MessageCircleIcon class="h-8 w-8 text-blue-600" />
                </div>
                <h5 class="mb-2 text-lg font-semibold text-blue-900">মন্তব্য করতে লগইন প্রয়োজন</h5>
                <p class="mb-4 text-blue-700">আমাদের ব্লগে মন্তব্য করার জন্য আপনাকে অবশ্যই লগইন করতে হবে।</p>
                <div class="flex items-center justify-center space-x-4">
                    <Link
                        :href="route('login')"
                        class="rounded-lg bg-[#5f5fcd] px-6 py-2 font-medium text-white transition-colors hover:bg-[#4a4aa6]"
                    >
                        লগইন করুন
                    </Link>
                    <Link
                        :href="route('register')"
                        class="rounded-lg border border-[#5f5fcd] px-6 py-2 font-medium text-[#5f5fcd] transition-colors hover:bg-[#5f5fcd] hover:text-white"
                    >
                        রেজিস্টার করুন
                    </Link>
                </div>
            </div>

            <!-- Comment Form for Authenticated Users -->
            <form v-else @submit.prevent="submitComment" class="space-y-4">
                <div>
                    <label for="comment" class="mb-2 block text-sm font-medium text-neutral-700">মন্তব্য *</label>
                    <textarea
                        id="comment"
                        v-model="commentForm.content"
                        rows="4"
                        required
                        class="w-full resize-none rounded-lg border border-neutral-300 px-4 py-3 focus:border-[#5f5fcd] focus:ring-[#5f5fcd]"
                        placeholder="আপনার মন্তব্য লিখুন..."
                    ></textarea>
                </div>

                <div class="flex items-center justify-between">
                    <p class="text-sm text-secondary">মন্তব্য প্রকাশের আগে মডারেশনের জন্য অপেক্ষা করতে হবে।</p>
                    <button
                        type="submit"
                        :disabled="submitting"
                        class="rounded-lg bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] px-6 py-3 font-semibold text-white transition-all duration-200 hover:from-[#4a4aa6] hover:to-[#1f3e1b] focus:ring-2 focus:ring-[#5f5fcd] focus:ring-offset-2 focus:outline-none disabled:opacity-50"
                    >
                        {{ submitting ? 'পাঠানো হচ্ছে...' : 'মন্তব্য পাঠান' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Comments List -->
        <div v-if="comments.length > 0" class="space-y-8">
            <div class="border-t border-neutral-200 pt-6">
                <h4 class="mb-4 text-lg font-semibold text-primary">সকল মন্তব্য</h4>
            </div>
            <CommentItem v-for="comment in topLevelComments" :key="comment.id" :comment="comment" :post-id="postId" @reply-added="handleReplyAdded" />
        </div>

        <div v-else class="py-8 text-center">
            <MessageCircleIcon class="mx-auto mb-3 h-12 w-12 text-neutral-300" />
            <p class="text-muted">এই পোস্টে এখনো কোন মন্তব্য নেই। প্রথম মন্তব্য করুন!</p>
        </div>
    </section>
</template>

<script setup lang="ts">
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { MessageCircle as MessageCircleIcon } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import CommentItem from './CommentItem.vue';

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
}

interface Props {
    postId: number;
    comments: Comment[];
}

const props = defineProps<Props>();
const emit = defineEmits<{
    commentAdded: [comment: Comment];
}>();

const page = usePage();
const isAuthenticated = computed(() => !!page.props.auth?.user);

const submitting = ref(false);
const commentForm = ref({
    name: '',
    email: '',
    content: '',
});

const topLevelComments = computed(() => {
    return props.comments.filter((comment) => !comment.parent_id);
});

const submitComment = async () => {
    if (!isAuthenticated.value) {
        return;
    }

    submitting.value = true;

    try {
        const formData = {
            blog_post_id: props.postId,
            content: commentForm.value.content,
        };

        // Submit comment via Inertia
        await useForm(formData).post(route('frontend.blog.comments.store'), {
            preserveScroll: true,
            onSuccess: () => {
                // Reset form
                commentForm.value = {
                    name: '',
                    email: '',
                    content: '',
                };

                // Show success message
                // Comment submitted successfully
            },
            onError: (errors) => {
                // Comment submission errors
            },
        });
    } catch (error) {
        // Error submitting comment
    } finally {
        submitting.value = false;
    }
};

const handleReplyAdded = (reply: Comment) => {
    emit('commentAdded', reply);
};
</script>
