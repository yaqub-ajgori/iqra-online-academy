<template>
  <FrontendLayout title="আমাদের সম্পর্কে - ইকরা অনলাইন একাডেমি">
    <Head title="আমাদের সম্পর্কে - ইকরা অনলাইন একাডেমি" />

    <!-- Main Content -->
    <div>
      <!-- Page Header -->
      <section class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-20 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 pattern-dots opacity-10"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
          <div class="text-center">
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 mb-6">
              <HeartIcon class="w-4 h-4 text-white mr-2" />
              <span class="text-white text-sm font-medium">আমাদের পরিচয়</span>
            </div>
            
            <h1 class="text-4xl lg:text-6xl font-bold text-white mb-6">
              ইকরা অনলাইন <span class="text-gradient-islamic bg-gradient-to-r from-[#d4a574] to-[#5f5fcd] bg-clip-text text-transparent">একাডেমি</span>
            </h1>
            
            <p class="text-xl text-gray-300 max-w-3xl mx-auto leading-relaxed">
              {{ aboutData?.tagline || 'ইসলামিক শিক্ষায় আধুনিক প্রযুক্তির সমন্বয়ে গড়ে উঠেছে ইকরা অনলাইন একাডেমি। আমরা বিশ্বাস করি জ্ঞানার্জনই প্রকৃত ইবাদত।' }}
            </p>
          </div>
        </div>
      </section>

      <!-- Mission Section -->
      <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <!-- Content -->
            <div>
              <div class="inline-flex items-center px-4 py-2 rounded-full bg-[#5f5fcd]/10 border border-[#5f5fcd]/20 mb-6">
                <BookOpenIcon class="w-4 h-4 text-[#5f5fcd] mr-2" />
                <span class="text-[#5f5fcd] text-sm font-medium">আমাদের লক্ষ্য</span>
              </div>
              
              <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6">
                {{ aboutData?.mission_title || 'ইসলামিক জ্ঞানে আলোকিত হোক প্রতিটি মুসলমান' }}
              </h2>
              
              <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                {{ aboutData?.mission_description || 'আমাদের মূল লক্ষ্য হলো সহজ ও আধুনিক পদ্ধতিতে ইসলামিক শিক্ষা প্রদান করা। কুরআন-হাদিসের আলোকে একটি আদর্শ ইসলামিক সমাজ গঠনে অবদান রাখা।' }}
              </p>
              
              <div class="space-y-4">
                <div v-for="feature in aboutData?.features" :key="feature.id" class="flex items-start space-x-3">
                  <div class="w-6 h-6 bg-[#2d5a27] rounded-full flex items-center justify-center mt-1">
                    <CheckIcon class="w-3 h-3 text-white" />
                  </div>
                  <div>
                    <h4 class="font-semibold text-gray-900">{{ feature.title }}</h4>
                    <p class="text-gray-600">{{ feature.description }}</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Image -->
            <div class="relative">
              <div v-if="aboutData?.mission_image">
                <img 
                  :src="aboutData.mission_image" 
                  :alt="aboutData?.mission_image_alt || 'ইসলামিক শিক্ষা'"
                  class="w-full h-auto rounded-2xl shadow-islamic-lg"
                  @error="handleImageError"
                />
              </div>
              <div v-else class="w-full h-[400px] bg-gradient-to-br from-[#5f5fcd]/10 to-[#2d5a27]/10 rounded-2xl shadow-islamic-lg flex items-center justify-center">
                <div class="text-center">
                  <div class="w-32 h-32 bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <span class="text-white font-bold text-5xl">ই</span>
                  </div>
                  <p class="text-gray-500 text-lg">ইকরা অনলাইন একাডেমি</p>
                </div>
              </div>
              
              <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-gradient-to-br from-[#5f5fcd]/20 to-[#2d5a27]/20 rounded-2xl"></div>
              <div class="absolute -top-6 -right-6 w-24 h-24 bg-gradient-to-br from-[#d4a574]/30 to-transparent rounded-full"></div>
            </div>
          </div>
        </div>
      </section>

      <!-- Values Section -->
      <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="text-center mb-16">
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-white border border-gray-200 mb-6">
              <StarIcon class="w-4 h-4 text-[#5f5fcd] mr-2" />
              <span class="text-[#5f5fcd] text-sm font-medium">আমাদের মূল্যবোধ</span>
            </div>
            
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6">
              {{ aboutData?.values_title || 'যে নীতিমালার উপর ভিত্তি করে আমরা কাজ করি' }}
            </h2>
            
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
              {{ aboutData?.values_description || 'ইসলামিক মূল্যবোধ ও আধুনিক শিক্ষা পদ্ধতির সমন্বয়ে আমরা গড়ে তুলেছি একটি অনন্য শিক্ষা প্রতিষ্ঠান।' }}
            </p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div v-for="value in aboutData?.values" :key="value.id" class="bg-white p-8 rounded-2xl shadow-islamic hover:shadow-islamic-lg transition-all duration-300 group">
              <div class="w-16 h-16 bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                <component :is="getValueIcon(value.icon)" class="w-8 h-8 text-white" />
              </div>
              <h3 class="text-xl font-semibold text-gray-900 mb-4">{{ value.title }}</h3>
              <p class="text-gray-600 leading-relaxed">{{ value.description }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- Team Section -->
      <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="text-center mb-16">
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-[#5f5fcd]/10 border border-[#5f5fcd]/20 mb-6">
              <UsersIcon class="w-4 h-4 text-[#5f5fcd] mr-2" />
              <span class="text-[#5f5fcd] text-sm font-medium">আমাদের টিম</span>
            </div>
            
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6">
              {{ aboutData?.team_title || 'অভিজ্ঞ শিক্ষকমণ্ডলী ও বিশেষজ্ঞগণ' }}
            </h2>
            
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
              {{ aboutData?.team_description || 'আমাদের টিমে রয়েছেন দেশের সেরা আলেম, ইসলামিক স্কলার এবং প্রযুক্তিবিদরা।' }}
            </p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div v-for="member in aboutData?.team_members" :key="member.id" class="bg-white p-6 rounded-2xl shadow-islamic hover:shadow-islamic-lg transition-all duration-300 text-center group">
              <div class="relative">
                <img 
                  v-if="member.avatar && !member.avatarError"
                  :src="member.avatar" 
                  :alt="member.name"
                  class="w-24 h-24 rounded-full mx-auto mb-4 border-4 border-[#d4a574] group-hover:scale-105 transition-transform duration-300 object-cover"
                  @error="handleAvatarError(member)"
                />
                <div v-else class="w-24 h-24 bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] rounded-full mx-auto mb-4 border-4 border-[#d4a574] group-hover:scale-105 transition-transform duration-300 flex items-center justify-center">
                  <span class="text-white font-bold text-3xl">{{ getInitials(member.name) }}</span>
                </div>
              </div>
              <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ member.name }}</h3>
              <p class="text-[#5f5fcd] font-medium mb-2">{{ member.position }}</p>
              <p class="text-gray-600 text-sm">{{ member.experience }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- Contact CTA -->
      <section class="py-20 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 pattern-grid opacity-10"></div>
        
        <div class="relative max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
          <h2 class="text-3xl lg:text-5xl font-bold text-white mb-6">
            {{ aboutData?.cta_title || 'আমাদের সাথে যুক্ত হন' }}
          </h2>

          <p class="text-xl text-gray-300 mb-8 leading-relaxed max-w-2xl mx-auto">
            {{ aboutData?.cta_description || 'ইসলামিক শিক্ষার এই মহান যাত্রায় আমাদের সাথে যুক্ত হন। আপনার জ্ঞানের পিপাসা মিটান আমাদের সাথে।' }}
          </p>

          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <PrimaryButton 
              :href="route('frontend.courses.index')" 
              tag="a"
              size="xl"
              variant="primary"
              :icon="BookOpenIcon"
            >
              কোর্স দেখুন
            </PrimaryButton>
            <PrimaryButton 
              :href="route('frontend.contact')" 
              tag="a"
              size="xl"
              variant="outline"
              class="border-white/20 text-white hover:bg-white/10 hover:border-white/40"
              :icon="MailIcon"
            >
              যোগাযোগ করুন
            </PrimaryButton>
          </div>
        </div>
      </section>
    </div>
  </FrontendLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import FrontendLayout from '@/layouts/FrontendLayout.vue'
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue'
import {
  BookOpenIcon,
  HeartIcon,
  CheckIcon,
  StarIcon,
  UsersIcon,
  AwardIcon,
  GlobeIcon,
  ShieldIcon,
  MailIcon,
  AlertTriangleIcon
} from 'lucide-vue-next'

// Interfaces
interface AboutData {
  tagline: string
  mission_title: string
  mission_description: string
  mission_image: string
  mission_image_alt: string
  values_title: string
  values_description: string
  team_title: string
  team_description: string
  cta_title: string
  cta_description: string
  features: Array<{
    id: number
    title: string
    description: string
  }>
  values: Array<{
    id: number
    title: string
    description: string
    icon: string
  }>
  team_members: Array<{
    id: number
    name: string
    position: string
    experience: string
    avatar: string
    avatarError?: boolean
  }>
}

interface Stat {
  id: number
  label: string
  value: string
}

// State
const loading = ref(true)
const error = ref('')
const aboutData = ref<AboutData | null>(null)
const stats = ref<Stat[]>([])

// Methods
const loadAboutData = async () => {
  loading.value = true
  error.value = ''
  
  try {
    await router.get(route('frontend.about'), {}, {
      preserveState: true,
      preserveScroll: true,
      only: ['aboutData', 'stats']
    })
    
    // Get updated data from page props
    const page = usePage()
    aboutData.value = page.props.aboutData as AboutData
    stats.value = (page.props.stats as Stat[]) || []
  } catch (err) {
    error.value = 'তথ্য লোড করতে সমস্যা হয়েছে। দয়া করে আবার চেষ্টা করুন।'
    console.error('Error loading about data:', err)
  } finally {
    loading.value = false
  }
}

const getValueIcon = (iconName: string) => {
  const icons: Record<string, any> = {
    'book-open': BookOpenIcon,
    'users': UsersIcon,
    'award': AwardIcon,
    'heart': HeartIcon,
    'globe': GlobeIcon,
    'shield': ShieldIcon,
    'star': StarIcon,
    'check': CheckIcon
  }
  return icons[iconName] || BookOpenIcon
}

const getInitials = (name: string) => {
  // Get first letter of first and last name
  const parts = name.split(' ')
  if (parts.length >= 2) {
    return parts[0][0] + parts[parts.length - 1][0]
  }
  return parts[0][0] || 'ই'
}

const handleImageError = (event: Event) => {
  const target = event.target as HTMLImageElement
  // Hide the image and let the CSS fallback show
  target.style.display = 'none'
}

const handleAvatarError = (member: any) => {
  // Mark this member's avatar as having an error so the fallback shows
  member.avatarError = true
}

// Initialize on mount
onMounted(() => {
  loadAboutData()
})
</script>

<style scoped>
/* Custom styles for the about page */
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

/* Hover animations for cards */
.group:hover .group-hover\:scale-105 {
  transform: scale(1.05);
}

.group:hover .group-hover\:scale-110 {
  transform: scale(1.1);
}
</style> 
