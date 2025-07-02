<template>
  <FrontendLayout title="ইকরা অনলাইন একাডেমি - ইসলামিক শিক্ষায় নতুন মাত্রা">
    <Head title="ইকরা অনলাইন একাডেমি - ইসলামিক শিক্ষায় নতুন মাত্রা">
      <meta name="description" content="ইকরা অনলাইন একাডেমি - কুরআন, হাদিস ও ইসলামিক জ্ঞানচর্চায় আধুনিক প্রযুক্তির সমন্বয়ে গড়ে তুলুন আপনার আধ্যাত্মিক জীবন। অভিজ্ঞ শিক্ষকদের সাথে শিখুন ঘরে বসেই।" />
      <meta name="keywords" content="ইসলামিক শিক্ষা, কুরআন, হাদিস, ফিকহ, অনলাইন কোর্স, ইসলামিক একাডেমি, ইকরা" />
      <meta property="og:title" content="ইকরা অনলাইন একাডেমি - ইসলামিক শিক্ষায় নতুন মাত্রা" />
      <meta property="og:description" content="কুরআন, হাদিস ও ইসলামিক জ্ঞানচর্চায় আধুনিক প্রযুক্তির সমন্বয়ে গড়ে তুলুন আপনার আধ্যাত্মিক জীবন।" />
      <meta property="og:type" content="website" />
      <meta property="og:url" content="https://iqra-online-academy.test" />
    </Head>

    <!-- Enhanced Hero Section with Better Animations -->
    <section class="relative py-20 lg:py-28 overflow-hidden bg-gradient-to-br from-slate-50 via-white to-blue-50/30">
      <!-- Enhanced Gradient Background -->
      <div class="absolute inset-0 bg-gradient-to-br from-[#5f5fcd]/5 via-transparent to-[#2d5a27]/4"></div>
      
      <!-- Animated Pattern Overlay -->
      <div class="absolute inset-0 opacity-[0.03] animate-pulse">
        <div class="absolute inset-0 bg-[#5f5fcd]" style="mask-image: radial-gradient(circle at 1px 1px, black 0.5px, transparent 0); mask-size: 20px 20px;"></div>
      </div>

      <!-- Enhanced Floating Elements with Animation -->
      <div class="absolute top-24 right-8 w-64 h-64 bg-gradient-to-br from-[#5f5fcd]/8 to-[#2d5a27]/6 rounded-full blur-3xl animate-float"></div>
      <div class="absolute bottom-24 left-8 w-48 h-48 bg-gradient-to-br from-[#d4a574]/10 to-[#5f5fcd]/8 rounded-full blur-3xl animate-float-delayed"></div>

      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
          
          <!-- Enhanced Hero Content with Animations -->
          <div class="text-center lg:text-left animate-fade-in">
            <!-- Enhanced Trust Badge -->
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-[#5f5fcd]/10 to-[#2d5a27]/10 border border-[#5f5fcd]/20 mb-8 hover:scale-105 transition-transform duration-300 backdrop-blur-sm">
              <span class="text-[#5f5fcd] text-sm font-medium">🕌 বিশ্বস্ত ইসলামিক শিক্ষা</span>
            </div>

            <!-- Enhanced Hero Title with Typing Animation -->
            <h1 class="text-4xl lg:text-6xl font-bold leading-tight mb-2">
              <span class="text-gradient-islamic animate-gradient-shift">ইকরা অনলাইন একাডেমি</span>
              <br>
              <span class="text-gray-800">তে স্বাগতম</span>
            </h1>
            <h2 class="text-base text-[#5f5fcd] font-semibold mb-6">বাংলাদেশের #১ ইসলামিক অনলাইন একাডেমি</h2>

            <!-- Enhanced Value Proposition -->
            <p class="text-lg lg:text-xl text-gray-600 leading-relaxed mb-8 max-w-2xl">
              কুরআন, হাদিস ও ইসলামিক জ্ঞানচর্চায় আধুনিক প্রযুক্তির সমন্বয়ে গড়ে তুলুন আপনার আধ্যাত্মিক জীবন। 
              অভিজ্ঞ শিক্ষকদের সাথে শিখুন ঘরে বসেই।
            </p>

            <!-- Enhanced Stats with Counter Animation -->
            <div class="grid grid-cols-3 gap-8 mb-10">
              <div class="text-center group hover:scale-105 transition-transform duration-300">
                <div class="text-3xl lg:text-4xl font-bold text-[#5f5fcd] mb-2 counter-animation" :data-target="stats?.total_courses || 0">০</div>
                <div class="text-sm text-gray-600">কোর্স</div>
              </div>
              <div class="text-center group hover:scale-105 transition-transform duration-300">
                <div class="text-3xl lg:text-4xl font-bold text-[#2d5a27] mb-2 counter-animation" :data-target="stats?.total_students || 0">০</div>
                <div class="text-sm text-gray-600">শিক্ষার্থী</div>
              </div>
              <div class="text-center group hover:scale-105 transition-transform duration-300">
                <div class="text-3xl lg:text-4xl font-bold text-[#d4a574] mb-2 counter-animation" :data-target="stats?.satisfaction_rate || 0">০</div>
                <div class="text-sm text-gray-600">সন্তুষ্টি</div>
              </div>
            </div>

            <!-- Enhanced CTAs with Hover Effects -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start mb-8">
              <PrimaryButton 
                :href="route('frontend.courses.index')" 
                tag="a"
                size="lg"
                variant="primary"
                :icon="BookOpenIcon"
                class="shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105"
              >
                কোর্স দেখুন
              </PrimaryButton>
              
              <PrimaryButton 
                :href="route('frontend.about')" 
                tag="a"
                size="lg"
                variant="outline"
                :icon="InfoIcon"
                class="border-gray-200 hover:border-[#5f5fcd]/30 transition-all duration-300 hover:scale-105"
              >
                কোর্স পরিচিতি
              </PrimaryButton>
            </div>

            <!-- Enhanced Social Proof -->
            <div class="flex flex-wrap items-center gap-6 justify-center lg:justify-start text-sm text-gray-600">
              <div class="flex items-center space-x-2">
                <div class="flex -space-x-1">
                  <img class="w-6 h-6 rounded-full border border-white" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=24&h=24&fit=crop&crop=face" alt="">
                  <img class="w-6 h-6 rounded-full border border-white" src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=24&h=24&fit=crop&crop=face" alt="">
                  <img class="w-6 h-6 rounded-full border border-white" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=24&h=24&fit=crop&crop=face" alt="">
                </div>
                <span>৫০০০+ সন্তুষ্ট শিক্ষার্থী</span>
              </div>
              
              <div class="flex items-center space-x-1">
                <div class="flex space-x-1 text-[#d4a574]">
                  <StarIcon class="w-4 h-4 fill-current" />
                  <StarIcon class="w-4 h-4 fill-current" />
                  <StarIcon class="w-4 h-4 fill-current" />
                  <StarIcon class="w-4 h-4 fill-current" />
                  <StarIcon class="w-4 h-4 fill-current" />
                </div>
                <span>৪.৯ রেটিং</span>
              </div>
            </div>
          </div>

          <!-- Enhanced Hero Image with Better Animations -->
          <div class="relative animate-slide-up">
            <!-- Main Image with Enhanced Effects -->
            <div class="relative group">
              <div class="aspect-[4/3] rounded-2xl overflow-hidden shadow-2xl group-hover:shadow-3xl transition-all duration-500">
                <img 
                  src="https://images.unsplash.com/photo-1542816417-0983c9c9ad53?w=600&h=450&fit=crop" 
                  alt="ইসলামিক শিক্ষা - কুরআন ও ইসলামিক ক্যালিগ্রাফি"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                  loading="lazy"
                />
                
                <!-- Enhanced Islamic Pattern Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-[#5f5fcd]/15 via-transparent to-[#2d5a27]/8"></div>
              </div>
              
              <!-- Enhanced Floating Elements -->
              <div class="absolute -top-4 -left-4 w-20 h-20 bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] rounded-xl flex items-center justify-center shadow-lg animate-bounce">
                <BookOpenIcon class="w-8 h-8 text-white" aria-hidden="true" />
              </div>
              
              <div class="absolute -bottom-4 -right-4 w-16 h-16 bg-gradient-to-br from-[#d4a574] to-[#5f5fcd] rounded-xl flex items-center justify-center shadow-lg animate-bounce-delayed">
                <AwardIcon class="w-6 h-6 text-white" aria-hidden="true" />
              </div>
            </div>

            <!-- Enhanced Achievement Cards -->
            <div class="absolute top-8 -right-6 bg-white/95 backdrop-blur-sm rounded-lg p-3 shadow-lg border border-gray-100 hover:scale-110 transition-transform duration-300">
              <div class="text-center">
                <div class="text-lg font-bold text-[#2d5a27]">৪.৯★</div>
                <div class="text-xs text-gray-500">রেটিং</div>
              </div>
            </div>
            
            <div class="absolute bottom-8 -left-6 bg-white/95 backdrop-blur-sm rounded-lg p-3 shadow-lg border border-gray-100 hover:scale-110 transition-transform duration-300">
              <div class="text-center">
                <div class="text-sm font-bold text-[#d4a574]">২৪/৭</div>
                <div class="text-xs text-gray-500">সাপোর্ট</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Enhanced Featured Courses Section -->
    <section class="py-20 bg-gradient-to-br from-gray-50 via-white to-slate-50 relative overflow-hidden">
      <!-- Subtle Background Pattern -->
      <div class="absolute inset-0 bg-gradient-to-br from-[#5f5fcd]/3 via-transparent to-[#2d5a27]/2"></div>
      
      <!-- Floating Elements -->
      <div class="absolute top-20 right-20 w-32 h-32 bg-gradient-to-br from-[#d4a574]/8 to-transparent rounded-full blur-2xl animate-float"></div>
      <div class="absolute bottom-20 left-20 w-40 h-40 bg-gradient-to-tl from-[#5f5fcd]/6 to-transparent rounded-full blur-2xl animate-float-delayed"></div>

      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <SectionHeader 
          title="জনপ্রিয় কোর্সসমূহ"
          subtitle="আমাদের সেরা ইসলামিক কোর্সগুলো দেখুন"
          description="কুরআন, হাদিস, ফিকহ এবং ইসলামিক জীবনযাত্রার উপর বিশেষজ্ঞ শিক্ষকদের তৈরি কোর্সগুলো এখনই শুরু করুন।"
          variant="gradient"
          size="lg"
        >
          <template #cta>
            <PrimaryButton 
              :href="route('frontend.courses.index')" 
              tag="a"
              variant="accent"
              :icon="ArrowRightIcon"
              icon-position="right"
            >
              সব কোর্স দেখুন
            </PrimaryButton>
          </template>
        </SectionHeader>

        <!-- Enhanced Course Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <CourseCard 
            v-for="(course, index) in featuredCourses" 
            :key="course?.id || index"
            :course="course"
            @enroll="handleCourseEnroll"
            @favorite="handleCourseFavorite"
          />
        </div>
      </div>
    </section>

    <!-- Enhanced Testimonials Section -->
    <section class="py-20 bg-gradient-to-br from-white via-blue-50/20 to-slate-50 relative overflow-hidden">
      <!-- Subtle Background Pattern -->
      <div class="absolute inset-0 bg-gradient-to-br from-[#2d5a27]/2 via-transparent to-[#d4a574]/3"></div>
      
      <!-- Floating Elements -->
      <div class="absolute top-32 left-32 w-24 h-24 bg-gradient-to-br from-[#5f5fcd]/8 to-transparent rounded-full blur-2xl animate-float"></div>
      <div class="absolute bottom-32 right-32 w-36 h-36 bg-gradient-to-tl from-[#2d5a27]/6 to-transparent rounded-full blur-2xl animate-float-delayed"></div>

      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <SectionHeader 
          title="শিক্ষার্থীদের মতামত"
          subtitle="আমাদের সন্তুষ্ট শিক্ষার্থীদের অভিজ্ঞতা"
          description="ইকরা অনলাইন একাডেমিতে শিক্ষা গ্রহণ করে কতজন শিক্ষার্থী তাদের জীবনে ইতিবাচক পরিবর্তন দেখেছেন।"
          variant="default"
          size="lg"
        />

        <!-- Testimonials Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-12">
          <TestimonialItem
            v-for="testimonial in testimonials"
            :key="testimonial.id"
            :testimonial="testimonial"
          />
        </div>
      </div>
    </section>

    <!-- Enhanced Why Choose Us Section -->
    <section class="py-20 bg-gradient-to-br from-slate-50 via-white to-gray-50 relative overflow-hidden">
      <!-- Subtle Background Pattern -->
      <div class="absolute inset-0 bg-gradient-to-br from-[#d4a574]/3 via-transparent to-[#5f5fcd]/2"></div>
      
      <!-- Floating Elements -->
      <div class="absolute top-24 right-24 w-28 h-28 bg-gradient-to-br from-[#2d5a27]/8 to-transparent rounded-full blur-2xl animate-float"></div>
      <div class="absolute bottom-24 left-24 w-32 h-32 bg-gradient-to-tl from-[#d4a574]/6 to-transparent rounded-full blur-2xl animate-float-delayed"></div>

      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <SectionHeader 
          title="কেন আমাদের বেছে নেবেন?"
          subtitle="ইসলামিক শিক্ষায় আমাদের বিশেষত্ব"
          description="অভিজ্ঞ শিক্ষক, আধুনিক প্রযুক্তি এবং ইসলামিক মূল্যবোধের সমন্বয়ে আমরা প্রদান করি সর্বোচ্চ মানের শিক্ষা।"
          variant="default"
          size="lg"
        />

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <!-- Feature 1 -->
          <div class="group relative bg-white rounded-2xl p-8 shadow-islamic hover:shadow-islamic-lg transition-all duration-300 border border-gray-100 overflow-hidden text-center">
            <div class="relative mb-6 flex justify-center">
              <div class="w-12 h-12 bg-gradient-to-br from-[#5f5fcd]/10 to-[#2d5a27]/10 rounded-full flex items-center justify-center">
                <BookOpenIcon class="w-6 h-6 text-[#5f5fcd]" />
              </div>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-4 group-hover:text-[#5f5fcd] transition-colors duration-200">অভিজ্ঞ শিক্ষকমণ্ডলী</h3>
            <p class="text-gray-700 leading-relaxed text-base mb-2">দেশের সেরা আলেম ও ইসলামিক স্কলারদের তত্ত্বাবধানে তৈরি আমাদের সব কোর্স।</p>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-[#d4a574] to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </div>
          <!-- Feature 2 -->
          <div class="group relative bg-white rounded-2xl p-8 shadow-islamic hover:shadow-islamic-lg transition-all duration-300 border border-gray-100 overflow-hidden text-center">
            <div class="relative mb-6 flex justify-center">
              <div class="w-12 h-12 bg-gradient-to-br from-[#2d5a27]/10 to-[#d4a574]/10 rounded-full flex items-center justify-center">
                <SmartphoneIcon class="w-6 h-6 text-[#2d5a27]" />
              </div>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-4 group-hover:text-[#5f5fcd] transition-colors duration-200">ইন্টারঅ্যাক্টিভ শিক্ষা</h3>
            <p class="text-gray-700 leading-relaxed text-base mb-2">অডিও, ইন্টারঅ্যাক্টিভ অনুশীলন, কুইজ এবং লাইভ সেশনের মাধ্যমে উপভোগ্য শিক্ষার পরিবেশ।</p>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-[#d4a574] to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </div>
          <!-- Feature 3 -->
          <div class="group relative bg-white rounded-2xl p-8 shadow-islamic hover:shadow-islamic-lg transition-all duration-300 border border-gray-100 overflow-hidden text-center">
            <div class="relative mb-6 flex justify-center">
              <div class="w-12 h-12 bg-gradient-to-br from-[#d4a574]/10 to-[#5f5fcd]/10 rounded-full flex items-center justify-center">
                <AwardIcon class="w-6 h-6 text-[#d4a574]" />
              </div>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-4 group-hover:text-[#5f5fcd] transition-colors duration-200">সার্টিফিকেট প্রদান</h3>
            <p class="text-gray-700 leading-relaxed text-base mb-2">কোর্স সম্পন্ন করলে পাবেন আন্তর্জাতিক মানের সার্টিফিকেট।</p>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-[#d4a574] to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </div>
          <!-- Feature 4 -->
          <div class="group relative bg-white rounded-2xl p-8 shadow-islamic hover:shadow-islamic-lg transition-all duration-300 border border-gray-100 overflow-hidden text-center">
            <div class="relative mb-6 flex justify-center">
              <div class="w-12 h-12 bg-gradient-to-br from-[#5f5fcd]/10 to-[#2d5a27]/10 rounded-full flex items-center justify-center">
                <ClockIcon class="w-6 h-6 text-[#5f5fcd]" />
              </div>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-4 group-hover:text-[#5f5fcd] transition-colors duration-200">নিজের সময়ে শিখুন</h3>
            <p class="text-gray-700 leading-relaxed text-base mb-2">২৪/৭ অ্যাক্সেস এবং নিজের সুবিধামতো সময়ে শিক্ষা গ্রহণের সুবিধা।</p>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-[#d4a574] to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </div>
          <!-- Feature 5 -->
          <div class="group relative bg-white rounded-2xl p-8 shadow-islamic hover:shadow-islamic-lg transition-all duration-300 border border-gray-100 overflow-hidden text-center">
            <div class="relative mb-6 flex justify-center">
              <div class="w-12 h-12 bg-gradient-to-br from-[#2d5a27]/10 to-[#d4a574]/10 rounded-full flex items-center justify-center">
                <UsersIcon class="w-6 h-6 text-[#2d5a27]" />
              </div>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-4 group-hover:text-[#5f5fcd] transition-colors duration-200">কমিউনিটি সাপোর্ট</h3>
            <p class="text-gray-700 leading-relaxed text-base mb-2">অন্যান্য শিক্ষার্থীদের সাথে আলোচনা এবং শিক্ষকদের সরাসরি গাইডেন্স।</p>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-[#d4a574] to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </div>
          <!-- Feature 6 -->
          <div class="group relative bg-white rounded-2xl p-8 shadow-islamic hover:shadow-islamic-lg transition-all duration-300 border border-gray-100 overflow-hidden text-center">
            <div class="relative mb-6 flex justify-center">
              <div class="w-12 h-12 bg-gradient-to-br from-[#d4a574]/10 to-[#5f5fcd]/10 rounded-full flex items-center justify-center">
                <SmartphoneIcon class="w-6 h-6 text-[#d4a574]" />
              </div>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-4 group-hover:text-[#5f5fcd] transition-colors duration-200">মোবাইল অ্যাপ</h3>
            <p class="text-gray-700 leading-relaxed text-base mb-2">যেকোনো জায়গা থেকে মোবাইল অ্যাপের মাধ্যমে শিক্ষা গ্রহণ করুন।</p>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-[#d4a574] to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </div>
        </div>
      </div>
    </section>

    <!-- Simplified CTA Section -->
    <section class="py-20 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 relative overflow-hidden">
      <!-- Background Pattern -->
      <div class="absolute inset-0 pattern-grid opacity-10"></div>
      
      <!-- Floating Elements -->
      <div class="absolute top-0 left-0 w-32 h-32 bg-gradient-to-br from-[#5f5fcd]/20 to-transparent rounded-full transform -translate-x-16 -translate-y-16 animate-float"></div>
      <div class="absolute bottom-0 right-0 w-40 h-40 bg-gradient-to-tl from-[#d4a574]/20 to-transparent rounded-full transform translate-x-20 translate-y-20 animate-float-delayed"></div>

      <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <!-- Header Section -->
        <div class="mb-10">
          <div class="inline-flex items-center px-6 py-3 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 mb-6 hover:scale-105 transition-transform duration-300">
            <span class="text-white text-sm font-medium mr-2">🌟 আজই শুরু করুন</span>
            <div class="w-2 h-2 bg-[#d4a574] rounded-full animate-pulse"></div>
          </div>

          <h2 class="text-3xl lg:text-5xl font-bold text-white mb-6 leading-tight">
            আপনার <span class="text-gradient-islamic bg-gradient-to-r from-[#d4a574] to-[#5f5fcd] bg-clip-text text-transparent animate-gradient-shift">ইসলামিক জ্ঞানের</span> যাত্রা শুরু করুন
          </h2>

          <p class="text-lg lg:text-xl text-gray-300 mb-8 leading-relaxed max-w-2xl mx-auto">
            আজই নিবন্ধন করুন এবং পেয়ে যান প্রথম মাসের জন্য <span class="text-[#d4a574] font-semibold">৫০% ছাড়</span>। 
            সীমিত সময়ের জন্য এই বিশেষ অফার।
          </p>
        </div>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
          <PrimaryButton 
            :href="route('register')" 
            tag="a"
            size="xl"
            variant="primary"
            :icon="UserPlusIcon"
            class="hover:scale-105 transition-transform duration-300 shadow-2xl hover:shadow-3xl min-w-[200px]"
          >
            ফ্রি রেজিস্ট্রেশন
          </PrimaryButton>
          
          <PrimaryButton 
            :href="route('frontend.courses.index')" 
            tag="a"
            size="xl"
            variant="outline"
            class="border-white/20 text-white hover:scale-105 transition-transform duration-300 min-w-[200px]"
          >
            কোর্স ব্রাউজ করুন
          </PrimaryButton>
        </div>

        <!-- Simple Trust Indicator -->
        <div class="mt-8 flex items-center justify-center space-x-6 text-sm text-gray-400">
          <div class="flex items-center space-x-2">
            <div class="flex space-x-1 text-[#d4a574]">
              <StarIcon class="w-4 h-4 fill-current" />
              <StarIcon class="w-4 h-4 fill-current" />
              <StarIcon class="w-4 h-4 fill-current" />
              <StarIcon class="w-4 h-4 fill-current" />
              <StarIcon class="w-4 h-4 fill-current" />
            </div>
            <span>৪.৯/৫ রেটিং</span>
          </div>
          <div class="w-1 h-1 bg-gray-600 rounded-full"></div>
          <span>৫০০০+ সন্তুষ্ট শিক্ষার্থী</span>
        </div>
      </div>
    </section>
  </FrontendLayout>
</template>

<script setup lang="ts">
// Enhanced HomePage component - Islamic LMS frontend
import { ref, onMounted } from 'vue'
import { Head, usePage, router } from '@inertiajs/vue3'
import FrontendLayout from '@/layouts/FrontendLayout.vue'
import SectionHeader from '@/components/Frontend/SectionHeader.vue'
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue'
import CourseCard from '@/components/Frontend/CourseCard.vue'
import TestimonialItem from '@/components/Frontend/TestimonialItem.vue'
import {
  BookOpenIcon,
  InfoIcon,
  ArrowRightIcon,
  AwardIcon,
  ClockIcon,
  UsersIcon,
  SmartphoneIcon,
  UserPlusIcon,
  StarIcon
} from 'lucide-vue-next'

// Get data from backend
const page = usePage()
const featuredCourses = ref<any[]>((page.props.featured_courses as any[]) || [])
const stats = ref<any>((page.props.stats as any) || {
  total_courses: 0,
  total_students: 0,
  satisfaction_rate: 0,
  total_instructors: 0,
  total_enrollments: 0
})

// Type definitions for better TypeScript support
interface Course {
  id: number
  slug: string
  title: string
  description: string
  image?: string
  category: string
  level: string
  price: number
  duration: string
  students_count: number
  rating: number
  instructor: {
    name: string
    avatar?: string
  }
  isNew?: boolean
  enrolled: boolean
  progress: number
}

const handleCourseEnroll = (course: any) => {
  // Redirect to payment page for course enrollment
  router.visit(route('frontend.payment.checkout', { course: course.slug }))
}

const handleCourseFavorite = (course: any, isFavorite: boolean) => {
  // Handle favorite toggle logic here
}

// Counter animation function
const animateCounters = () => {
  const counters = document.querySelectorAll('.counter-animation')
  counters.forEach(counter => {
    const target = parseInt(counter.getAttribute('data-target') || '0')
    const duration = 2000 // 2 seconds
    const step = target / (duration / 16) // 60fps
    let current = 0
    
    const timer = setInterval(() => {
      current += step
      if (current >= target) {
        current = target
        clearInterval(timer)
      }
      if (counter.textContent !== null) {
        counter.textContent = Math.floor(current).toString()
      }
    }, 16)
  })
}

// Initialize on mount
onMounted(() => {
  // Start counter animation after a delay
  setTimeout(() => {
    animateCounters()
  }, 500)
})

// Testimonials Section
const testimonials = [
  {
    id: 1,
    content: 'এই একাডেমির কোর্সগুলো খুবই মানসম্মত। শিক্ষকরা আন্তরিক এবং সহায়ক। আমি অনেক কিছু শিখেছি।',
    rating: 5,
    student: {
      name: 'মাহমুদুল হাসান',
      title: 'বিশ্ববিদ্যালয় শিক্ষার্থী',
      avatar: 'https://randomuser.me/api/portraits/men/32.jpg',
      location: 'ঢাকা, বাংলাদেশ'
    },
    verified: true,
    created_at: '2024-06-01',
    course: {
      title: 'কুরআন তিলাওয়াত শিক্ষা',
      category: 'কুরআন'
    }
  },
  {
    id: 2,
    content: 'ইকরা একাডেমির মাধ্যমে আমি ইসলামিক জ্ঞান সহজে ও সুন্দরভাবে পেয়েছি। ধন্যবাদ!',
    rating: 5,
    student: {
      name: 'সাবরিনা আক্তার',
      title: 'স্কুল শিক্ষিকা',
      avatar: 'https://randomuser.me/api/portraits/women/44.jpg',
      location: 'চট্টগ্রাম, বাংলাদেশ'
    },
    verified: true,
    created_at: '2024-05-20',
    course: {
      title: 'হাদিস শরীফ',
      category: 'হাদিস'
    }
  },
  {
    id: 3,
    content: 'অনলাইন প্ল্যাটফর্ম হিসেবে ইকরা একাডেমি অসাধারণ। কোর্স কনটেন্ট ও সাপোর্ট টিম খুবই ভালো।',
    rating: 4,
    student: {
      name: 'রাশেদুল ইসলাম',
      title: 'ফ্রিল্যান্সার',
      avatar: 'https://randomuser.me/api/portraits/men/65.jpg',
      location: 'রাজশাহী, বাংলাদেশ'
    },
    verified: true,
    created_at: '2024-04-15',
    course: {
      title: 'ইসলামিক ফিকহ',
      category: 'ফিকহ'
    }
  }
]
</script>

<style scoped>
/* Enhanced float animation for hero elements */
@keyframes float {
  0%, 100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-20px);
  }
}

@keyframes float-delayed {
  0%, 100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-15px);
  }
}

.animate-float {
  animation: float 6s ease-in-out infinite;
}

.animate-float-delayed {
  animation: float-delayed 8s ease-in-out infinite;
}

/* Enhanced gradient text animation */
@keyframes gradientShift {
  0%, 100% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
}

.text-gradient-islamic {
  background-size: 200% 200%;
  animation: gradientShift 4s ease-in-out infinite;
}

/* Enhanced bounce animations */
@keyframes bounce-delayed {
  0%, 20%, 53%, 80%, 100% {
    transform: translate3d(0, 0, 0);
  }
  40%, 43% {
    transform: translate3d(0, -8px, 0);
  }
  70% {
    transform: translate3d(0, -4px, 0);
  }
  90% {
    transform: translate3d(0, -2px, 0);
  }
}

.animate-bounce-delayed {
  animation: bounce-delayed 3s ease-in-out infinite;
  animation-delay: 1s;
}

/* Enhanced fade in animation */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fadeIn 1s ease-out;
}

/* Enhanced slide up animation */
@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(40px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-slide-up {
  animation: slideUp 1.2s ease-out;
}

/* Enhanced focus states for accessibility */
.focus-islamic:focus {
  outline: 2px solid #5f5fcd;
  outline-offset: 2px;
  border-radius: 8px;
}

/* Enhanced hover effects */
.hover-lift {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-lift:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 40px rgba(95, 95, 205, 0.15);
}
</style> 
