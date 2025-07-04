<template>
    <FrontendLayout title="ইমেইল যাচাইকরণ - ইকরা অনলাইন একাডেমি">
        <Head title="ইমেইল যাচাইকরণ" />

        <section class="py-20 min-h-screen flex items-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <!-- Left Side - Information -->
                    <div class="space-y-8 hidden lg:block">
                        <div>
                            <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-600/10 border border-blue-600/20 mb-6">
                                <MailCheck class="w-4 h-4 text-blue-600 mr-2" />
                                <span class="text-blue-600 text-sm font-medium">ইমেইল যাচাইকরণ</span>
                            </div>
                            <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                                ইমেইল যাচাইকরণ
                            </h1>
                            <p class="text-xl text-gray-600 leading-relaxed mb-8">
                                আপনার অ্যাকাউন্ট সক্রিয় করতে ইমেইলে পাঠানো লিঙ্কটি ক্লিক করুন। ইমেইল না পেলে স্প্যাম ফোল্ডার চেক করুন।
                            </p>
                        </div>
                    </div>

                    <!-- Right Side - Email Verification Card -->
                    <div>
                        <div class="bg-white/95 backdrop-blur-sm p-8 rounded-2xl shadow-islamic-lg border border-gray-100/50 relative overflow-hidden">
                            <!-- Background pattern -->
                            <div class="absolute inset-0 opacity-5">
                                <svg class="w-full h-full" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
                                    <defs>
                                        <pattern id="verifyPattern" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                                            <path d="M20 0 L40 20 L20 40 L0 20 Z" fill="none" stroke="#5f5fcd" stroke-width="1"/>
                                            <circle cx="20" cy="20" r="8" fill="none" stroke="#2d5a27" stroke-width="1"/>
                                        </pattern>
                                    </defs>
                                    <rect width="400" height="400" fill="url(#verifyPattern)" />
                                </svg>
                            </div>

                            <div class="relative z-10">
                                <div class="text-center mb-8">
                                    <div class="w-16 h-16 bg-gradient-to-r from-[#5f5fcd]/20 via-[#2d5a27]/20 to-[#d4a574]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <MailCheck class="w-8 h-8 text-[#5f5fcd]" />
                                    </div>
                                    <h2 class="text-2xl font-bold text-gray-900 mb-2">ইমেইল যাচাই করুন</h2>
                                    <p class="text-gray-600">আপনার ইমেইল ঠিকানায় যাচাইকরণ লিঙ্ক পাঠানো হয়েছে।</p>
                                </div>

                                <div v-if="verificationLinkSent" class="mb-6 text-center text-sm font-medium text-green-600 bg-green-50 p-3 rounded-lg">
                                    আপনার ইমেইল ঠিকানায় একটি নতুন যাচাইকরণ লিঙ্ক পাঠানো হয়েছে।
                                </div>

                                <form @submit.prevent="submit">
                                    <Button
                                        type="submit"
                                        :class="{ 'opacity-25': isButtonDisabled }"
                                        :disabled="isButtonDisabled"
                                        class="group w-full bg-gradient-to-r from-[#5f5fcd] via-[#2d5a27] to-[#d4a574] text-white font-semibold py-3 px-4 rounded-xl hover:shadow-islamic-lg transition-all duration-300"
                                    >
                                        যাচাইকরণ ইমেইল পুনরায় পাঠান
                                    </Button>
                                </form>

                                <div v-if="cooldown > 0" class="mt-6 text-center text-sm font-medium text-red-600">
                                    পরে আবার চেষ্টা করুন ({{ cooldown }} সেকেন্ড অপেক্ষা করুন)
                                </div>

                                <div class="mt-6 text-center">
                                    <TextLink
                                        :href="route('logout')"
                                        method="post"
                                        as="button"
                                        class="inline-flex items-center text-gray-600 hover:text-red-500 font-medium"
                                    >
                                        <LogOut class="w-4 h-4 mr-2" />
                                        লগ আউট
                                    </TextLink>
                                </div>

                                <div class="mt-8 text-center">
                                    <span class="text-gray-600">ইতিমধ্যে অ্যাকাউন্ট আছে? </span>
                                    <TextLink
                                        :href="route('login')"
                                        class="inline-flex items-center text-[#5f5fcd] hover:text-[#4a4aa6] font-medium"
                                    >
                                        লগইন করুন
                                        <ArrowRight class="w-4 h-4 ml-2" />
                                    </TextLink>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </FrontendLayout>
</template>

<script setup>
import { computed, ref, onMounted, watch } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import FrontendLayout from '@/layouts/FrontendLayout.vue'
import { Button } from '@/components/ui/button'
import TextLink from '@/components/TextLink.vue'
import { MailCheck, LogOut, Award, ArrowRight } from 'lucide-vue-next'
import { useToast } from '@/composables/useToast'

const props = defineProps({
    status: String,
    error: String,
})

const form = useForm({})
const { error: showError, success: showSuccess } = useToast()

const cooldown = ref(0)
const COOLDOWN_KEY = 'verify_email_cooldown'

// Restore cooldown from localStorage on mount
onMounted(() => {
    const saved = localStorage.getItem(COOLDOWN_KEY)
    if (saved) {
        const remaining = parseInt(saved, 10) - Math.floor(Date.now() / 1000)
        if (remaining > 0) {
            cooldown.value = remaining
        } else {
            localStorage.removeItem(COOLDOWN_KEY)
        }
    }
    if (props.error) {
        showError(props.error)
    }
})

// Watch cooldown and update localStorage
watch(cooldown, (val) => {
    if (val > 0) {
        localStorage.setItem(COOLDOWN_KEY, (Math.floor(Date.now() / 1000) + val).toString())
    } else {
        localStorage.removeItem(COOLDOWN_KEY)
    }
})

// Decrement cooldown every second
let interval = null
watch(cooldown, (val) => {
    if (val > 0 && !interval) {
        interval = setInterval(() => {
            if (cooldown.value > 0) {
                cooldown.value--
            }
            if (cooldown.value <= 0 && interval) {
                clearInterval(interval)
                interval = null
            }
        }, 1000)
    } else if (val <= 0 && interval) {
        clearInterval(interval)
        interval = null
    }
})

watch(() => props.error, (val) => {
    if (val) {
        showError(val)
    }
})

const submit = () => {
    form.post(route('verification.send'), {
        onError: () => {
            // Check for rate limit error (HTTP 429)
            if (form.errors && form.errors.email && form.errors.email.includes('seconds')) {
                cooldown.value = 60 // 60 seconds cooldown
                showError('অনুগ্রহ করে কিছুক্ষণ অপেক্ষা করুন এবং পরে আবার চেষ্টা করুন।')
            } else {
                showError('কিছু ভুল হয়েছে। দয়া করে পরে আবার চেষ্টা করুন।')
            }
        }
    })
}

const verificationLinkSent = computed(() => props.status === 'verification-link-sent')
const isButtonDisabled = computed(() => form.processing || cooldown.value > 0)
</script>

<style scoped>
.text-gradient-islamic {
  background-size: 200% 200%;
  animation: gradientShift 4s ease-in-out infinite;
}

@keyframes gradientShift {
  0%, 100% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
}
</style> 