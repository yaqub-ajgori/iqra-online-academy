<template>
  <div class="max-w-4xl mx-auto p-8 space-y-8">
    <div class="text-center">
      <h1 class="text-3xl font-bold text-gray-900 mb-4">Toast Notification Demo</h1>
      <p class="text-gray-600">ইকরা অনলাইন একাডেমির জন্য Toast নোটিফিকেশন সিস্টেম</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Basic Toasts -->
      <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold mb-4">Basic Toasts</h2>
        <div class="space-y-3">
          <button @click="showSuccess" class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
            Success Toast
          </button>
          <button @click="showError" class="w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
            Error Toast
          </button>
          <button @click="showWarning" class="w-full bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700">
            Warning Toast
          </button>
          <button @click="showInfo" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
            Info Toast
          </button>
        </div>
      </div>

      <!-- Advanced Toasts -->
      <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold mb-4">Advanced Features</h2>
        <div class="space-y-3">
          <button @click="showWithTitle" class="w-full bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700">
            Toast with Title
          </button>
          <button @click="showWithAction" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
            Toast with Action
          </button>
          <button @click="showPersistent" class="w-full bg-pink-600 text-white px-4 py-2 rounded-md hover:bg-pink-700">
            Persistent Toast
          </button>
          <button @click="showPromise" class="w-full bg-teal-600 text-white px-4 py-2 rounded-md hover:bg-teal-700">
            Promise Toast
          </button>
        </div>
      </div>

      <!-- Position Examples -->
      <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold mb-4">Different Positions</h2>
        <div class="space-y-3">
          <button @click="showTopRight" class="w-full bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
            Top Right (Default)
          </button>
          <button @click="showTopLeft" class="w-full bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
            Top Left
          </button>
          <button @click="showBottomRight" class="w-full bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
            Bottom Right
          </button>
          <button @click="showTopCenter" class="w-full bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
            Top Center
          </button>
        </div>
      </div>

      <!-- Utility Functions -->
      <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold mb-4">Utility Functions</h2>
        <div class="space-y-3">
          <button @click="clearAll" class="w-full bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700">
            Clear All Toasts
          </button>
          <button @click="clearErrors" class="w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
            Clear Error Toasts
          </button>
          <button @click="showMultiple" class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
            Show Multiple Toasts
          </button>
        </div>
      </div>
    </div>

    <!-- Code Examples -->
    <div class="bg-gray-100 p-6 rounded-lg">
      <h2 class="text-xl font-semibold mb-4">Usage Examples</h2>
      <pre class="bg-gray-900 text-green-400 p-4 rounded-md overflow-x-auto text-sm"><code>
// Import the composable
import { useToast } from '@/components/ui/toast'

// In your component
const toast = useToast()

// Basic usage
toast.success('কুপন সফলভাবে প্রয়োগ হয়েছে!')
toast.error('অবৈধ কুপন কোড')
toast.warning('সব তথ্য পূরণ করুন')
toast.info('নতুন কোর্স যোগ হয়েছে')

// With options
toast.success({
  title: 'পেমেন্ট সফল!',
  message: 'কোর্সে সফলভাবে নিবন্ধিত হয়েছেন',
  duration: 5000,
  position: 'top-center'
})

// Promise handling
toast.promise(paymentPromise, {
  loading: 'পেমেন্ট প্রক্রিয়াধীন...',
  success: 'পেমেন্ট সফল!',
  error: 'পেমেন্ট ব্যর্থ!'
})
      </code></pre>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useToast } from '@/components/ui/toast'

const toast = useToast()

// Basic toast methods
const showSuccess = () => {
  toast.success('এটি একটি সফল বার্তা! কোর্স সফলভাবে যোগ হয়েছে।')
}

const showError = () => {
  toast.error('এটি একটি ত্রুটি বার্তা! অনুগ্রহ করে আবার চেষ্টা করুন।')
}

const showWarning = () => {
  toast.warning('এটি একটি সতর্কতা বার্তা! আপনার অ্যাকাউন্ট শীঘ্রই মেয়াদোত্তীর্ণ হবে।')
}

const showInfo = () => {
  toast.info('এটি একটি তথ্য বার্তা! নতুন ফিচার সম্পর্কে জানুন।')
}

// Advanced features
const showWithTitle = () => {
  toast.success({
    title: 'কুপন প্রয়োগ সফল!',
    message: '৫০% ছাড় পেয়েছেন। কুপন: IQRA50',
    duration: 6000
  })
}

const showWithAction = () => {
  toast.info({
    title: 'নতুন কোর্স উপলব্ধ!',
    message: 'হাদিসের সহজ শিক্ষা কোর্স এখন উপলব্ধ।',
    action: {
      label: 'দেখুন',
      handler: () => {
        alert('কোর্সের পেজে নিয়ে যাওয়া হবে!')
      }
    },
    duration: 8000
  })
}

const showPersistent = () => {
  toast.warning({
    title: 'গুরুত্বপূর্ণ বিজ্ঞপ্তি',
    message: 'এই বার্তাটি নিজে থেকে মুছে যাবে না। ম্যানুয়ালি বন্ধ করুন।',
    persistent: true
  })
}

const showPromise = async () => {
  const mockPayment = new Promise((resolve, reject) => {
    setTimeout(() => {
      Math.random() > 0.5 ? resolve('Payment successful') : reject(new Error('Payment failed'))
    }, 3000)
  })

  try {
    await toast.promise(mockPayment, {
      loading: 'পেমেন্ট প্রক্রিয়াধীন... অনুগ্রহ করে অপেক্ষা করুন।',
      success: 'পেমেন্ট সফল! কোর্সে প্রবেশ করুন।',
      error: 'পেমেন্ট ব্যর্থ! আবার চেষ্টা করুন।'
    })
  } catch (error) {
    // Handle error if needed
  }
}

// Position examples
const showTopRight = () => {
  toast.success({ message: 'Top Right Position (Default)', position: 'top-right' })
}

const showTopLeft = () => {
  toast.info({ message: 'Top Left Position', position: 'top-left' })
}

const showBottomRight = () => {
  toast.warning({ message: 'Bottom Right Position', position: 'bottom-right' })
}

const showTopCenter = () => {
  toast.error({ message: 'Top Center Position', position: 'top-center' })
}

// Utility functions
const clearAll = () => {
  toast.clear()
  toast.info('সব Toast বার্তা মুছে ফেলা হয়েছে!')
}

const clearErrors = () => {
  toast.clearByType('error')
  toast.success('সব ত্রুটি বার্তা মুছে ফেলা হয়েছে!')
}

const showMultiple = () => {
  toast.success({ message: 'প্রথম বার্তা', position: 'top-right' })
  setTimeout(() => {
    toast.info({ message: 'দ্বিতীয় বার্তা', position: 'top-right' })
  }, 500)
  setTimeout(() => {
    toast.warning({ message: 'তৃতীয় বার্তা', position: 'top-right' })
  }, 1000)
}
</script> 