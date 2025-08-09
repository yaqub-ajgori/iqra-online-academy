<template>
    <FrontendLayout>
        <Head title="Certificate Verification - Iqra Online Academy" />
        
        <div class="min-h-screen bg-gray-50 py-4 sm:py-8">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                
                <!-- Verification Form -->
                <div class="bg-white rounded-2xl shadow-lg border p-4 sm:p-8 mb-6 sm:mb-8">
                    <div class="text-center mb-6 sm:mb-8">
                        <div class="inline-flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-[#2d5a27]/10 to-[#5f5fcd]/10 rounded-xl mb-3 sm:mb-4">
                            <SearchIcon class="h-5 w-5 sm:h-6 sm:w-6 text-[#5f5fcd]" />
                        </div>
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">
                            Certificate Verification
                        </h1>
                        <p class="text-sm sm:text-base text-gray-600">
                            Enter certificate number or verification code
                        </p>
                    </div>
                            
                    <form @submit.prevent="submitVerificationExact" class="space-y-4 sm:space-y-6">
                        <div>
                            <input
                                id="certificate_code"
                                v-model="form.certificate_code"
                                type="text"
                                placeholder="Certificate number or verification code"
                                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-[#5f5fcd]/20 focus:border-[#5f5fcd] transition-all duration-300"
                                :class="{ 'border-red-400 focus:border-red-400': errors.certificate_code }"
                                required
                            />
                            <p v-if="errors.certificate_code" class="mt-1.5 sm:mt-2 text-red-600 text-xs sm:text-sm">
                                {{ errors.certificate_code }}
                            </p>
                        </div>

                        <div>
                            <button
                                type="submit"
                                :disabled="submitting || !form.certificate_code.trim()"
                                class="w-full bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] text-white font-medium sm:font-semibold py-2.5 sm:py-3 px-4 sm:px-6 text-sm sm:text-base rounded-lg sm:rounded-xl hover:shadow-lg transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
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
                                    Verify
                                </span>
                            </button>
                        </div>

                        <p class="text-[11px] sm:text-xs text-gray-500">
                            Accepted formats: <span class="font-mono whitespace-nowrap">IOA-YYYY-####</span> or <span class="font-mono whitespace-nowrap">CERTXXXXXXXX</span>
                        </p>
                    </form>

                    <!-- Error Message -->
                    <div v-if="errorMessage" class="mt-4 sm:mt-6 bg-red-50 border border-red-200 rounded-lg sm:rounded-xl p-3 sm:p-4">
                        <div class="flex">
                            <XCircleIcon class="h-4 w-4 sm:h-5 sm:w-5 text-red-500 mt-0.5" />
                            <div class="ml-2 sm:ml-3">
                                <h3 class="text-xs sm:text-sm font-semibold text-red-800">
                                    Certificate Not Found
                                </h3>
                                <p class="mt-0.5 sm:mt-1 text-xs sm:text-sm text-red-700">
                                    {{ errorMessage }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Verified Certificate Display -->
                <div v-if="certificate" class="bg-white rounded-lg sm:rounded-2xl shadow-lg border border-green-200 p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:space-x-4 space-y-3 sm:space-y-0 mb-4 sm:mb-6">
                        <div class="flex-shrink-0 flex justify-center">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <CheckCircleIcon class="h-5 w-5 sm:h-6 sm:w-6 text-green-600" />
                            </div>
                        </div>
                        <div class="flex-1 text-center sm:text-left">
                            <h3 class="text-lg sm:text-xl font-bold text-green-800 mb-0.5 sm:mb-1">Certificate Verified</h3>
                            <p class="text-xs sm:text-sm text-green-600">This is a valid certificate issued by Iqra Online Academy</p>
                        </div>
                        <div class="flex-shrink-0 flex justify-center">
                            <span class="inline-flex items-center px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full text-[11px] sm:text-xs font-medium bg-green-100 text-green-800">
                                {{ certificate.status }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-6">
                        <div class="space-y-3 sm:space-y-4">
                            <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
                                <h4 class="text-xs sm:text-sm font-medium text-gray-600 mb-0.5 sm:mb-1">Student Name</h4>
                                <p class="text-base sm:text-lg font-semibold text-gray-900">{{ certificate.student_name }}</p>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
                                <h4 class="text-xs sm:text-sm font-medium text-gray-600 mb-0.5 sm:mb-1">Course Title</h4>
                                <p class="text-sm sm:text-base font-medium text-gray-900 leading-tight">{{ certificate.course_title }}</p>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
                                <h4 class="text-xs sm:text-sm font-medium text-gray-600 mb-0.5 sm:mb-1">Completion Date</h4>
                                <p class="text-sm sm:text-base text-gray-900">{{ certificate.completion_date }}</p>
                            </div>
                        </div>

                        <div class="space-y-3 sm:space-y-4">
                            <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
                                <h4 class="text-xs sm:text-sm font-medium text-gray-600 mb-0.5 sm:mb-1">Certificate Number</h4>
                                <p class="text-sm sm:text-base font-mono text-gray-900 break-all">{{ certificate.certificate_number }}</p>
                            </div>
                            
                            <div v-if="certificate.verification_code" class="bg-gray-50 rounded-lg p-3 sm:p-4">
                                <h4 class="text-xs sm:text-sm font-medium text-gray-600 mb-0.5 sm:mb-1">Verification Code</h4>
                                <p class="text-sm sm:text-base font-mono text-gray-900 break-all">{{ certificate.verification_code }}</p>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
                                <h4 class="text-xs sm:text-sm font-medium text-gray-600 mb-0.5 sm:mb-1">Instructor</h4>
                                <p class="text-sm sm:text-base text-gray-900">{{ certificate.instructor }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 sm:mt-6 pt-3 sm:pt-4 border-t border-gray-200">
                        <div class="flex items-center justify-center text-xs sm:text-sm text-gray-500">
                            <ShieldCheckIcon class="h-3.5 w-3.5 sm:h-4 sm:w-4 text-green-500 mr-1.5 sm:mr-2" />
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
import { ref, computed, watch, onMounted } from 'vue'
import { route } from 'ziggy-js'
import FrontendLayout from '@/layouts/FrontendLayout.vue'
import {
    CheckCircleIcon,
    SearchIcon,
    ShieldCheckIcon,
    XCircleIcon,
} from 'lucide-vue-next'

// State
const page = usePage()
const submitting = ref(false)

const form = ref({
    certificate_code: ''
})

// Sync form state from Inertia prop after navigation
watch(
    () => page.props.form,
    (newForm) => {
        if (newForm) {
            form.value.certificate_code = newForm.certificate_code || ''
        }
    },
    { immediate: true }
)

const errors = computed(() => page.props.errors || {})
const certificate = computed(() => page.props.certificate || null)
const errorMessage = computed(() => errors.value.certificate_code || null)
const successMessage = computed(() => certificate.value && !errorMessage.value ? 'Certificate verified successfully!' : null)


const submitVerificationExact = () => {
    if (submitting.value || !form.value.certificate_code.trim()) return
    submitting.value = true
    router.post(route('certificates.verify.submit'), {
        certificate_code: form.value.certificate_code.trim(),
    }, {
        preserveState: true,
        preserveScroll: true,
        onFinish: () => {
            submitting.value = false
        }
    })
}

onMounted(() => {
    document.getElementById('certificate_code')?.focus()
})
</script>