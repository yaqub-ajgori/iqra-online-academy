<template>
    <FrontendLayout>
        <Head title="Certificate Verification - Iqra Online Academy" />
        
        <div class="min-h-screen bg-gray-50 py-8">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                
                <!-- Verification Form -->
                <div class="bg-white rounded-2xl shadow-lg border p-8 mb-8">
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-r from-[#2d5a27]/10 to-[#5f5fcd]/10 rounded-xl mb-4">
                            <SearchIcon class="h-6 w-6 text-[#5f5fcd]" />
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">
                            Certificate Verification
                        </h1>
                        <p class="text-gray-600">
                            Enter certificate number or verification code
                        </p>
                    </div>
                            
                    <form @submit.prevent="submitVerification" class="space-y-6">
                        <div>
                            <input
                                id="certificate_code"
                                v-model="form.certificate_code"
                                type="text"
                                placeholder="Enter certificate number (IOA-2024-0001) or verification code"
                                class="w-full px-4 py-3 text-base border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-[#5f5fcd]/20 focus:border-[#5f5fcd] transition-all duration-300"
                                :class="{ 'border-red-400 focus:border-red-400': errors.certificate_code }"
                                required
                            />
                            <p v-if="errors.certificate_code" class="mt-2 text-red-600 text-sm">
                                {{ errors.certificate_code }}
                            </p>
                        </div>

                        <button
                            type="submit"
                            :disabled="submitting || !form.certificate_code.trim()"
                            class="w-full bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] text-white font-semibold py-3 px-6 rounded-xl hover:shadow-lg transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="submitting" class="inline-flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Verifying...
                            </span>
                            <span v-else class="inline-flex items-center">
                                <ShieldCheckIcon class="mr-2 h-5 w-5" />
                                Verify Certificate
                            </span>
                        </button>
                    </form>

                    <!-- Error Message -->
                    <div v-if="errorMessage" class="mt-6 bg-red-50 border border-red-200 rounded-xl p-4">
                        <div class="flex">
                            <XCircleIcon class="h-5 w-5 text-red-500 mt-0.5" />
                            <div class="ml-3">
                                <h3 class="text-sm font-semibold text-red-800">
                                    Certificate Not Found
                                </h3>
                                <p class="mt-1 text-sm text-red-700">
                                    {{ errorMessage }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Verified Certificate Display -->
                <div v-if="certificate" class="bg-white rounded-2xl shadow-lg border border-green-200 p-6">
                    <div class="flex items-start space-x-4 mb-6">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <CheckCircleIcon class="h-6 w-6 text-green-600" />
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-green-800 mb-1">Certificate Verified</h3>
                            <p class="text-green-600 text-sm">This is a valid certificate issued by Iqra Online Academy</p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ certificate.status }}
                            </span>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-600 mb-1">Student Name</h4>
                                <p class="text-lg font-semibold text-gray-900">{{ certificate.student_name }}</p>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-600 mb-1">Course Title</h4>
                                <p class="text-base font-medium text-gray-900 leading-tight">{{ certificate.course_title }}</p>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-600 mb-1">Completion Date</h4>
                                <p class="text-base text-gray-900">{{ certificate.completion_date }}</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-600 mb-1">Certificate Number</h4>
                                <p class="text-base font-mono text-gray-900">{{ certificate.certificate_number }}</p>
                            </div>
                            
                            <div v-if="certificate.verification_code" class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-600 mb-1">Verification Code</h4>
                                <p class="text-base font-mono text-gray-900">{{ certificate.verification_code }}</p>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-600 mb-1">Instructor</h4>
                                <p class="text-base text-gray-900">{{ certificate.instructor }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="flex items-center justify-center text-sm text-gray-500">
                            <ShieldCheckIcon class="h-4 w-4 text-green-500 mr-2" />
                            Verified authentic certificate issued by Iqra Online Academy
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </FrontendLayout>
</template>

<script setup>
import { Head, router, usePage } from '@inertiajs/vue3'
import { ref, computed, onMounted } from 'vue'
import { route } from 'ziggy-js'
import FrontendLayout from '@/layouts/FrontendLayout.vue'
import {
    CheckCircleIcon,
    SearchIcon,
    ShieldCheckIcon,
    XCircleIcon,
} from 'lucide-vue-next'

// State
const form = ref({
    certificate_code: ''
})

const submitting = ref(false)

// Page data
const page = usePage()
const errors = computed(() => page.props.errors || {})
const successMessage = computed(() => page.props.success || null)
const errorMessage = computed(() => page.props.error || null)
const certificate = computed(() => page.props.certificate || null)

// Methods
const submitVerification = () => {
    if (submitting.value || !form.value.certificate_code.trim()) return
    
    submitting.value = true
    
    router.post(route('certificates.verify.submit'), {
        certificate_code: form.value.certificate_code.trim()
    }, {
        preserveState: true,
        preserveScroll: true,
        onFinish: () => {
            submitting.value = false
        }
    })
}

// Focus on input field when component mounts
onMounted(() => {
    document.getElementById('certificate_code')?.focus()
})
</script>