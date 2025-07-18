<template>
    <FrontendLayout title="ইকরা অনলাইন একাডেমি - ইসলামিক শিক্ষায় নতুন মাত্রা">
        <Head title="ইকরা অনলাইন একাডেমি - ইসলামিক শিক্ষায় নতুন মাত্রা">
            <meta
                name="description"
                content="ইকরা অনলাইন একাডেমি - কুরআন, হাদিস ও ইসলামিক জ্ঞানচর্চায় আধুনিক প্রযুক্তির সমন্বয়ে গড়ে তুলুন আপনার আধ্যাত্মিক জীবন। অভিজ্ঞ শিক্ষকদের সাথে শিখুন ঘরে বসেই।"
            />
            <meta name="keywords" content="ইসলামিক শিক্ষা, কুরআন, হাদিস, ফিকহ, অনলাইন কোর্স, ইসলামিক একাডেমি, ইকরা" />
            <meta property="og:title" content="ইকরা অনলাইন একাডেমি - ইসলামিক শিক্ষায় নতুন মাত্রা" />
            <meta
                property="og:description"
                content="কুরআন, হাদিস ও ইসলামিক জ্ঞানচর্চায় আধুনিক প্রযুক্তির সমন্বয়ে গড়ে তুলুন আপনার আধ্যাত্মিক জীবন।"
            />
            <meta property="og:type" content="website" />
            <meta property="og:url" content="https://iqra-online-academy.com" />
        </Head>

        <!-- Enhanced Hero Section with Better Animations -->
        <section class="relative overflow-hidden bg-gradient-to-br from-slate-50 via-white to-blue-50/30 pt-4 pb-8 sm:py-12 lg:py-28">
            <!-- Enhanced Gradient Background -->
            <div class="absolute inset-0 bg-gradient-to-br from-[#5f5fcd]/5 via-transparent to-[#2d5a27]/4"></div>

            <!-- Animated Pattern Overlay -->
            <div class="absolute inset-0 animate-pulse opacity-[0.03]">
                <div
                    class="absolute inset-0 bg-[#5f5fcd]"
                    style="mask-image: radial-gradient(circle at 1px 1px, black 0.5px, transparent 0); mask-size: 20px 20px"
                ></div>
            </div>

            <!-- Enhanced Floating Elements with Animation -->
            <div
                class="animate-float absolute top-24 right-8 h-64 w-64 rounded-full bg-gradient-to-br from-[#5f5fcd]/8 to-[#2d5a27]/6 blur-3xl"
            ></div>
            <div
                class="animate-float-delayed absolute bottom-24 left-8 h-48 w-48 rounded-full bg-gradient-to-br from-[#d4a574]/10 to-[#5f5fcd]/8 blur-3xl"
            ></div>

            <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-12 lg:grid-cols-2 lg:items-center lg:gap-20">
                    <!-- Enhanced Hero Content with Animations -->
                    <div class="animate-fade-in text-center lg:text-left">
                        <!-- Enhanced Trust Badge -->
                        <div
                            class="mb-8 inline-flex items-center rounded-full border border-[#5f5fcd]/20 bg-gradient-to-r from-[#5f5fcd]/10 to-[#2d5a27]/10 px-4 py-2 backdrop-blur-sm transition-transform duration-300 hover:scale-105"
                        >
                            <span class="text-sm font-medium text-[#5f5fcd]">🕌 বিশ্বস্ত ইসলামিক শিক্ষা</span>
                        </div>

                        <!-- Enhanced Hero Title with Typing Animation -->
                        <h1 class="mb-2 text-2xl leading-tight font-bold sm:text-4xl lg:text-6xl">
                            <span class="text-gradient-islamic animate-gradient-shift">ইকরা অনলাইন একাডেমি</span>
                            <br />
                            <span class="text-gray-800">তে স্বাগতম</span>
                        </h1>
                        <h2 class="mb-6 text-base font-semibold text-[#5f5fcd]">বাংলাদেশের #১ ইসলামিক অনলাইন একাডেমি</h2>

                        <!-- Enhanced Value Proposition -->
                        <p class="mb-8 max-w-2xl text-lg leading-relaxed text-gray-600 lg:text-xl">
                            কুরআন, হাদিস ও ইসলামিক জ্ঞানচর্চায় আধুনিক প্রযুক্তির সমন্বয়ে গড়ে তুলুন আপনার আধ্যাত্মিক জীবন। অভিজ্ঞ শিক্ষকদের সাথে
                            শিখুন ঘরে বসেই।
                        </p>

                        <!-- Enhanced Stats with Counter Animation -->
                        <div class="mb-8 grid grid-cols-2 gap-4 sm:mb-10 sm:grid-cols-4 sm:gap-8">
                            <div class="group animate-stat-in text-center" style="animation-delay: 0s">
                                <div class="mb-1 text-2xl font-bold text-[#5f5fcd] sm:mb-2 sm:text-3xl lg:text-4xl">
                                    {{ toBanglaNumber(displayCourses) }}+
                                </div>
                                <div class="text-xs text-gray-600 sm:text-sm">কোর্স</div>
                            </div>
                            <div class="group animate-stat-in text-center" style="animation-delay: 0.1s">
                                <div class="mb-1 text-2xl font-bold text-[#2d5a27] sm:mb-2 sm:text-3xl lg:text-4xl">
                                    {{ toBanglaNumber(displayStudents) }}+
                                </div>
                                <div class="text-xs text-gray-600 sm:text-sm">শিক্ষার্থী</div>
                            </div>
                            <div class="group animate-stat-in text-center" style="animation-delay: 0.2s">
                                <div class="mb-1 text-2xl font-bold text-[#d4a574] sm:mb-2 sm:text-3xl lg:text-4xl">
                                    {{ toBanglaNumber(displayHappyStudents) }}+
                                </div>
                                <div class="text-xs text-gray-600 sm:text-sm">সন্তুষ্ট শিক্ষার্থী</div>
                            </div>
                            <div class="group animate-stat-in text-center" style="animation-delay: 0.3s">
                                <div class="mb-1 text-2xl font-bold text-[#d4a574] sm:mb-2 sm:text-3xl lg:text-4xl">
                                    {{ displayRating.toFixed(1) }}
                                </div>
                                <div class="text-xs text-gray-600 sm:text-sm">রেটিং</div>
                            </div>
                        </div>

                        <!-- Enhanced CTAs with Hover Effects -->
                        <div class="mb-6 flex flex-row justify-center gap-3 sm:mb-8 sm:gap-4 lg:justify-start">
                            <PrimaryButton
                                :href="route('frontend.courses.index')"
                                tag="a"
                                size="lg"
                                variant="primary"
                                :icon="BookOpenIcon"
                                class="flex-1 shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl sm:max-w-xs"
                            >
                                কোর্স দেখুন
                            </PrimaryButton>

                            <PrimaryButton
                                :href="route('frontend.about')"
                                tag="a"
                                size="lg"
                                variant="outline"
                                :icon="InfoIcon"
                                class="flex-1 border-gray-200 transition-all duration-300 hover:scale-105 hover:border-[#5f5fcd]/30 sm:max-w-xs"
                            >
                                কোর্স পরিচিতি
                            </PrimaryButton>
                        </div>

                        <!-- Enhanced Social Proof -->
                        <div class="flex flex-wrap items-center justify-center gap-6 text-sm text-gray-600 lg:justify-start">
                            <div class="flex items-center space-x-2">
                                <div class="flex -space-x-1">
                                    <img
                                        class="h-6 w-6 rounded-full border border-white"
                                        src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=24&h=24&fit=crop&crop=face"
                                        alt=""
                                    />
                                    <img
                                        class="h-6 w-6 rounded-full border border-white"
                                        src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=24&h=24&fit=crop&crop=face"
                                        alt=""
                                    />
                                    <img
                                        class="h-6 w-6 rounded-full border border-white"
                                        src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=24&h=24&fit=crop&crop=face"
                                        alt=""
                                    />
                                </div>
                                <span>{{ toBanglaNumber(displayHappyStudents) }}+ সন্তুষ্ট শিক্ষার্থী</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <div class="flex space-x-1 text-[#d4a574]">
                                    <StarIcon class="h-4 w-4 fill-current" />
                                    <StarIcon class="h-4 w-4 fill-current" />
                                    <StarIcon class="h-4 w-4 fill-current" />
                                    <StarIcon class="h-4 w-4 fill-current" />
                                    <StarIcon class="h-4 w-4 fill-current" />
                                </div>
                                <span>{{ displayRating.toFixed(1) }} রেটিং</span>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Hero Image with Better Animations -->
                    <div class="animate-slide-up relative">
                        <!-- Main Image with Enhanced Effects -->
                        <div class="group relative">
                            <div class="group-hover:shadow-3xl aspect-[4/3] overflow-hidden rounded-2xl shadow-2xl transition-all duration-500">
                                <img
                                    src="https://images.unsplash.com/photo-1542816417-0983c9c9ad53?w=600&h=450&fit=crop"
                                    alt="ইসলামিক শিক্ষা - কুরআন ও ইসলামিক ক্যালিগ্রাফি"
                                    class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                                    loading="lazy"
                                />

                                <!-- Enhanced Islamic Pattern Overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-[#5f5fcd]/15 via-transparent to-[#2d5a27]/8"></div>
                            </div>

                            <!-- Enhanced Floating Elements -->
                            <div
                                class="absolute -top-4 -left-4 flex h-20 w-20 animate-bounce items-center justify-center rounded-xl bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] shadow-lg"
                            >
                                <BookOpenIcon class="h-8 w-8 text-white" aria-hidden="true" />
                            </div>

                            <div
                                class="animate-bounce-delayed absolute -right-4 -bottom-4 flex h-16 w-16 items-center justify-center rounded-xl bg-gradient-to-br from-[#d4a574] to-[#5f5fcd] shadow-lg"
                            >
                                <AwardIcon class="h-6 w-6 text-white" aria-hidden="true" />
                            </div>
                        </div>

                        <!-- Enhanced Achievement Cards -->

                        <div
                            class="absolute bottom-8 -left-6 rounded-lg border border-gray-100 bg-white/95 p-3 shadow-lg backdrop-blur-sm transition-transform duration-300 hover:scale-110"
                        >
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
        <section class="relative overflow-hidden bg-gradient-to-br from-gray-50 via-white to-slate-50 py-12 sm:py-20">
            <!-- Subtle Background Pattern -->
            <div class="absolute inset-0 bg-gradient-to-br from-[#5f5fcd]/3 via-transparent to-[#2d5a27]/2"></div>

            <!-- Floating Elements -->
            <div
                class="animate-float absolute top-20 right-20 h-32 w-32 rounded-full bg-gradient-to-br from-[#d4a574]/8 to-transparent blur-2xl"
            ></div>
            <div
                class="animate-float-delayed absolute bottom-20 left-20 h-40 w-40 rounded-full bg-gradient-to-tl from-[#5f5fcd]/6 to-transparent blur-2xl"
            ></div>

            <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <SectionHeader
                    title="জনপ্রিয় কোর্সসমূহ"
                    subtitle="আমাদের সেরা ইসলামিক কোর্সগুলো দেখুন"
                    description="কুরআন, হাদিস, ফিকহ এবং ইসলামিক জীবনযাত্রার উপর বিশেষজ্ঞ শিক্ষকদের তৈরি কোর্সগুলো এখনই শুরু করুন।"
                    variant="gradient"
                    size="lg"
                >
                    <template #cta>
                        <PrimaryButton :href="route('frontend.courses.index')" tag="a" variant="accent" :icon="ArrowRightIcon" icon-position="right">
                            সব কোর্স দেখুন
                        </PrimaryButton>
                    </template>
                </SectionHeader>

                <!-- Enhanced Course Grid -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 sm:gap-8 lg:grid-cols-3">
                    <CourseCard v-for="(course, index) in featuredCourses" :key="course?.id || index" :course="course" @enroll="handleCourseEnroll" />
                </div>
            </div>
        </section>

        <!-- Enhanced Testimonials Section -->
        <WhenVisible>
            <section class="relative overflow-hidden bg-gradient-to-br from-white via-blue-50/20 to-slate-50 py-12 sm:py-20">
                <!-- Subtle Background Pattern -->
                <div class="absolute inset-0 bg-gradient-to-br from-[#2d5a27]/2 via-transparent to-[#d4a574]/3"></div>

                <!-- Floating Elements -->
                <div
                    class="animate-float absolute top-32 left-32 h-24 w-24 rounded-full bg-gradient-to-br from-[#5f5fcd]/8 to-transparent blur-2xl"
                ></div>
                <div
                    class="animate-float-delayed absolute right-32 bottom-32 h-36 w-36 rounded-full bg-gradient-to-tl from-[#2d5a27]/6 to-transparent blur-2xl"
                ></div>

                <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <SectionHeader
                        title="শিক্ষার্থীদের মতামত"
                        subtitle="আমাদের সন্তুষ্ট শিক্ষার্থীদের অভিজ্ঞতা"
                        description="ইকরা অনলাইন একাডেমিতে শিক্ষা গ্রহণ করে কতজন শিক্ষার্থী তাদের জীবনে ইতিবাচক পরিবর্তন দেখেছেন।"
                        variant="default"
                        size="lg"
                    />

                    <!-- Testimonials Grid -->
                    <div class="mt-8 grid grid-cols-1 gap-6 sm:mt-12 sm:grid-cols-2 sm:gap-8 lg:grid-cols-3">
                        <TestimonialItem v-for="testimonial in testimonials" :key="testimonial.id" :testimonial="testimonial" />
                    </div>
                </div>
            </section>
        </WhenVisible>

        <!-- Enhanced Why Choose Us Section -->
        <WhenVisible>
            <section class="relative overflow-hidden bg-gradient-to-br from-slate-50 via-white to-gray-50 py-12 sm:py-20">
                <!-- Subtle Background Pattern -->
                <div class="absolute inset-0 bg-gradient-to-br from-[#d4a574]/3 via-transparent to-[#5f5fcd]/2"></div>

                <!-- Floating Elements -->
                <div
                    class="animate-float absolute top-24 right-24 h-28 w-28 rounded-full bg-gradient-to-br from-[#2d5a27]/8 to-transparent blur-2xl"
                ></div>
                <div
                    class="animate-float-delayed absolute bottom-24 left-24 h-32 w-32 rounded-full bg-gradient-to-tl from-[#d4a574]/6 to-transparent blur-2xl"
                ></div>

                <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <SectionHeader
                        title="কেন আমাদের বেছে নেবেন?"
                        subtitle="ইসলামিক শিক্ষায় আমাদের বিশেষত্ব"
                        description="অভিজ্ঞ শিক্ষক, আধুনিক প্রযুক্তি এবং ইসলামিক মূল্যবোধের সমন্বয়ে আমরা প্রদান করি সর্বোচ্চ মানের শিক্ষা।"
                        variant="default"
                        size="lg"
                    />

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 sm:gap-8 lg:grid-cols-3">
                        <!-- Feature 1 -->
                        <div
                            class="group shadow-islamic hover:shadow-islamic-lg relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-8 text-center transition-all duration-300"
                        >
                            <div class="relative mb-6 flex justify-center">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-[#5f5fcd]/10 to-[#2d5a27]/10"
                                >
                                    <BookOpenIcon class="h-6 w-6 text-[#5f5fcd]" />
                                </div>
                            </div>
                            <h3 class="mb-4 text-xl font-semibold text-gray-900 transition-colors duration-200 group-hover:text-[#5f5fcd]">
                                অভিজ্ঞ শিক্ষকমণ্ডলী
                            </h3>
                            <p class="mb-2 text-base leading-relaxed text-gray-700">
                                দেশের সেরা আলেম ও ইসলামিক স্কলারদের তত্ত্বাবধানে তৈরি আমাদের সব কোর্স।
                            </p>
                            <div
                                class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-transparent via-[#d4a574] to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                            ></div>
                        </div>
                        <!-- Feature 2 -->
                        <div
                            class="group shadow-islamic hover:shadow-islamic-lg relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-8 text-center transition-all duration-300"
                        >
                            <div class="relative mb-6 flex justify-center">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-[#2d5a27]/10 to-[#d4a574]/10"
                                >
                                    <SmartphoneIcon class="h-6 w-6 text-[#2d5a27]" />
                                </div>
                            </div>
                            <h3 class="mb-4 text-xl font-semibold text-gray-900 transition-colors duration-200 group-hover:text-[#5f5fcd]">
                                ইন্টারঅ্যাক্টিভ শিক্ষা
                            </h3>
                            <p class="mb-2 text-base leading-relaxed text-gray-700">
                                অডিও, ইন্টারঅ্যাক্টিভ অনুশীলন, কুইজ এবং লাইভ সেশনের মাধ্যমে উপভোগ্য শিক্ষার পরিবেশ।
                            </p>
                            <div
                                class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-transparent via-[#d4a574] to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                            ></div>
                        </div>
                        <!-- Feature 3 -->
                        <div
                            class="group shadow-islamic hover:shadow-islamic-lg relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-8 text-center transition-all duration-300"
                        >
                            <div class="relative mb-6 flex justify-center">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-[#d4a574]/10 to-[#5f5fcd]/10"
                                >
                                    <AwardIcon class="h-6 w-6 text-[#d4a574]" />
                                </div>
                            </div>
                            <h3 class="mb-4 text-xl font-semibold text-gray-900 transition-colors duration-200 group-hover:text-[#5f5fcd]">
                                সার্টিফিকেট প্রদান
                            </h3>
                            <p class="mb-2 text-base leading-relaxed text-gray-700">কোর্স সম্পন্ন করলে পাবেন আন্তর্জাতিক মানের সার্টিফিকেট।</p>
                            <div
                                class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-transparent via-[#d4a574] to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                            ></div>
                        </div>
                        <!-- Feature 4 -->
                        <div
                            class="group shadow-islamic hover:shadow-islamic-lg relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-8 text-center transition-all duration-300"
                        >
                            <div class="relative mb-6 flex justify-center">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-[#5f5fcd]/10 to-[#2d5a27]/10"
                                >
                                    <ClockIcon class="h-6 w-6 text-[#5f5fcd]" />
                                </div>
                            </div>
                            <h3 class="mb-4 text-xl font-semibold text-gray-900 transition-colors duration-200 group-hover:text-[#5f5fcd]">
                                নিজের সময়ে শিখুন
                            </h3>
                            <p class="mb-2 text-base leading-relaxed text-gray-700">
                                ২৪/৭ অ্যাক্সেস এবং নিজের সুবিধামতো সময়ে শিক্ষা গ্রহণের সুবিধা।
                            </p>
                            <div
                                class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-transparent via-[#d4a574] to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                            ></div>
                        </div>
                        <!-- Feature 5 -->
                        <div
                            class="group shadow-islamic hover:shadow-islamic-lg relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-8 text-center transition-all duration-300"
                        >
                            <div class="relative mb-6 flex justify-center">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-[#2d5a27]/10 to-[#d4a574]/10"
                                >
                                    <UsersIcon class="h-6 w-6 text-[#2d5a27]" />
                                </div>
                            </div>
                            <h3 class="mb-4 text-xl font-semibold text-gray-900 transition-colors duration-200 group-hover:text-[#5f5fcd]">
                                কমিউনিটি সাপোর্ট
                            </h3>
                            <p class="mb-2 text-base leading-relaxed text-gray-700">
                                অন্যান্য শিক্ষার্থীদের সাথে আলোচনা এবং শিক্ষকদের সরাসরি গাইডেন্স।
                            </p>
                            <div
                                class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-transparent via-[#d4a574] to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                            ></div>
                        </div>
                        <!-- Feature 6 -->
                        <div
                            class="group shadow-islamic hover:shadow-islamic-lg relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-8 text-center transition-all duration-300"
                        >
                            <div class="relative mb-6 flex justify-center">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-[#d4a574]/10 to-[#5f5fcd]/10"
                                >
                                    <SmartphoneIcon class="h-6 w-6 text-[#d4a574]" />
                                </div>
                            </div>
                            <h3 class="mb-4 text-xl font-semibold text-gray-900 transition-colors duration-200 group-hover:text-[#5f5fcd]">
                                মোবাইল অ্যাপ
                            </h3>
                            <p class="mb-2 text-base leading-relaxed text-gray-700">যেকোনো জায়গা থেকে মোবাইল অ্যাপের মাধ্যমে শিক্ষা গ্রহণ করুন।</p>
                            <div
                                class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-transparent via-[#d4a574] to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                            ></div>
                        </div>
                    </div>
                </div>
            </section>
        </WhenVisible>

        <!-- Simplified CTA Section -->
        <WhenVisible>
            <section
                class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-8 sm:py-12 lg:py-20"
                id="donation"
                :class="{ 'donation-highlight': donationHighlight }"
            >
                <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col items-center gap-6 sm:gap-8 lg:flex-row lg:items-start lg:gap-12">
                        <!-- Left: Heading and Description -->
                        <div class="flex w-full flex-col items-center justify-center text-center lg:w-1/2">
                            <div
                                class="mb-6 inline-flex items-center rounded-full border border-white/20 bg-white/10 px-6 py-3 backdrop-blur-sm transition-transform duration-300 hover:scale-105"
                            >
                                <HeartIcon class="mr-2 h-5 w-5 text-[#d4a574]" />
                                <span class="text-sm font-medium text-white">দান করুন, সওয়াবের অংশীদার হোন</span>
                            </div>
                            <h2 class="mb-4 text-center text-2xl leading-tight font-bold text-white sm:mb-6 sm:text-3xl lg:text-4xl">
                                ইসলামী শিক্ষার প্রসারে
                                <span
                                    class="text-gradient-islamic animate-gradient-shift bg-gradient-to-r from-[#d4a574] to-[#5f5fcd] bg-clip-text text-transparent"
                                    >আপনার দান</span
                                >
                                গুরুত্বপূর্ণ
                            </h2>
                            <p class="mx-auto max-w-xl text-center text-base leading-relaxed text-gray-300 sm:text-lg lg:text-xl">
                                ইকরা অনলাইন একাডেমির মাধ্যমে হাজারো শিক্ষার্থী ইসলামী শিক্ষা গ্রহণ করছে। আপনার দান সরাসরি শিক্ষার্থীদের বৃত্তি,
                                পাঠ্যপুস্তক ও প্রযুক্তি সহায়তায় ব্যবহৃত হবে।
                            </p>
                        </div>
                        <!-- Right: Donation Form -->
                        <div class="w-full lg:w-1/2">
                            <form
                                class="mx-auto max-w-xl space-y-4 rounded-2xl border border-white/10 bg-white/10 p-4 text-left shadow-lg sm:space-y-6 sm:p-8"
                                @submit.prevent="submitDonation"
                            >
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-6">
                                    <div>
                                        <label class="mb-2 block text-gray-200">নাম</label>
                                        <input
                                            type="text"
                                            v-model="form.name"
                                            class="w-full rounded-xl border border-gray-300 bg-white/80 px-4 py-3 text-gray-900 focus:border-[#5f5fcd] focus:ring-[#5f5fcd]"
                                            placeholder="আপনার নাম"
                                            required
                                        />
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-gray-200">ইমেইল</label>
                                        <input
                                            type="email"
                                            v-model="form.email"
                                            class="w-full rounded-xl border border-gray-300 bg-white/80 px-4 py-3 text-gray-900 focus:border-[#5f5fcd] focus:ring-[#5f5fcd]"
                                            placeholder="আপনার ইমেইল"
                                            required
                                        />
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-gray-200">ফোন নম্বর</label>
                                        <input
                                            type="tel"
                                            v-model="form.phone"
                                            class="w-full rounded-xl border border-gray-300 bg-white/80 px-4 py-3 text-gray-900 focus:border-[#5f5fcd] focus:ring-[#5f5fcd]"
                                            placeholder="ফোন নম্বর"
                                            required
                                        />
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-gray-200">দানের পরিমাণ (৳)</label>
                                        <input
                                            type="number"
                                            v-model="form.amount"
                                            min="1"
                                            class="w-full rounded-xl border border-gray-300 bg-white/80 px-4 py-3 text-gray-900 focus:border-[#5f5fcd] focus:ring-[#5f5fcd]"
                                            placeholder="যত টাকা দান করতে চান"
                                            required
                                        />
                                    </div>
                                </div>
                                <div>
                                    <label class="mb-2 block text-gray-200">দানের কারণ</label>
                                    <input
                                        type="text"
                                        v-model="form.reason"
                                        class="w-full rounded-xl border border-gray-300 bg-white/80 px-4 py-3 text-gray-900 focus:border-[#5f5fcd] focus:ring-[#5f5fcd]"
                                        placeholder="কেন দান করছেন (ঐচ্ছিক)"
                                    />
                                </div>
                                <div>
                                    <label class="mb-2 block text-gray-200">পেমেন্ট মাধ্যম</label>
                                    <div class="flex flex-col gap-3 sm:gap-4">
                                        <button
                                            type="button"
                                            @click="form.payment_method = 'bkash'"
                                            :class="[
                                                'flex flex-1 items-center justify-center gap-2 rounded-xl px-4 py-3 font-semibold',
                                                form.payment_method === 'bkash'
                                                    ? 'bg-[#e2136e]/90 text-white shadow-lg'
                                                    : 'border border-gray-300 bg-white/80 text-gray-900',
                                            ]"
                                        >
                                            <span>bKash</span>
                                        </button>
                                        <button
                                            type="button"
                                            @click="form.payment_method = 'nagad'"
                                            :class="[
                                                'flex flex-1 items-center justify-center gap-2 rounded-xl px-4 py-3 font-semibold',
                                                form.payment_method === 'nagad'
                                                    ? 'bg-[#f58220]/90 text-white shadow-lg'
                                                    : 'border border-gray-300 bg-white/80 text-gray-900',
                                            ]"
                                        >
                                            <span>Nagad</span>
                                        </button>
                                        <button
                                            type="button"
                                            @click="form.payment_method = 'rocket'"
                                            :class="[
                                                'flex flex-1 items-center justify-center gap-2 rounded-xl px-4 py-3 font-semibold',
                                                form.payment_method === 'rocket'
                                                    ? 'bg-[#8a2be2]/90 text-white shadow-lg'
                                                    : 'border border-gray-300 bg-white/80 text-gray-900',
                                            ]"
                                        >
                                            <span>Rocket</span>
                                        </button>
                                    </div>
                                </div>
                                <div v-if="form.payment_method" class="mt-2 rounded-xl bg-white/20 p-4 text-gray-100">
                                    <div v-if="form.payment_method === 'bkash'">
                                        <div class="font-bold">bKash নম্বর: <span class="font-mono">01915878662 (পার্সোনাল)</span></div>
                                    </div>
                                    <div v-else-if="form.payment_method === 'nagad'">
                                        <div class="font-bold">Nagad নম্বর: <span class="font-mono">01750-469027 (পার্সোনাল)</span></div>
                                    </div>
                                    <div v-else-if="form.payment_method === 'rocket'">
                                        <div class="font-bold">Rocket নম্বর: <span class="font-mono">019158786625 (পার্সোনাল)</span></div>
                                    </div>
                                    <div class="mt-2 text-sm">অনুগ্রহ করে উপরের নম্বরে সেন্ড মানি করুন এবং নিচে ট্রানজেকশন আইডি লিখুন।</div>
                                </div>
                                <div v-if="form.payment_method">
                                    <label class="mt-4 mb-2 block text-gray-200">ট্রানজেকশন আইডি</label>
                                    <input
                                        type="text"
                                        v-model="form.transaction_id"
                                        class="w-full rounded-xl border border-gray-300 bg-white/80 px-4 py-3 text-gray-900 focus:border-[#5f5fcd] focus:ring-[#5f5fcd]"
                                        placeholder="পেমেন্টের পর প্রাপ্ত ট্রানজেকশন আইডি লিখুন"
                                        required
                                    />
                                </div>
                                <div class="pt-4">
                                    <PrimaryButton
                                        size="xl"
                                        variant="primary"
                                        :icon="HeartIcon"
                                        class="w-full min-w-[200px]"
                                        :loading="form.processing"
                                        type="submit"
                                    >
                                        দান জমা দিন
                                    </PrimaryButton>
                                </div>
                            </form>
                            <div v-if="form.hasErrors" class="mt-4 text-center text-red-500">
                                <div v-for="(msg, field) in form.errors" :key="field">{{ msg }}</div>
                            </div>
                            <div class="mt-6 text-center text-sm text-gray-400 sm:mt-8 lg:text-left">
                                <span>আল্লাহ্‌ আপনার দান কবুল করুন এবং দ্বীনি শিক্ষার প্রসারে আপনাকে অংশীদার করুন।</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </WhenVisible>
    </FrontendLayout>
</template>

<script setup lang="ts">
// Enhanced HomePage component - Islamic LMS frontend
import CourseCard from '@/components/Frontend/CourseCard.vue';
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue';
import SectionHeader from '@/components/Frontend/SectionHeader.vue';
import TestimonialItem from '@/components/Frontend/TestimonialItem.vue';
import WhenVisible from '@/components/Frontend/WhenVisible.vue';
import { useToast } from '@/composables/useToast';
import FrontendLayout from '@/layouts/FrontendLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { ArrowRightIcon, AwardIcon, BookOpenIcon, ClockIcon, HeartIcon, InfoIcon, SmartphoneIcon, StarIcon, UsersIcon } from 'lucide-vue-next';
import { onMounted, ref, watch } from 'vue';

// Get data from backend
const page = usePage();
const featuredCourses = ref<any[]>((page.props.featured_courses as any[]) || []);

// Stats will be loaded via deferred props
const stats = ref<any>({
    total_courses: 0,
    total_students: 0,
    satisfaction_rate: 0,
    total_instructors: 0,
    total_enrollments: 0,
});

// Testimonials will be loaded via deferred props
const testimonials = ref<any[]>([]);

// Watch for deferred props updates
watch(
    () => page.props.stats,
    (newStats) => {
        if (newStats) {
            stats.value = newStats;
        }
    },
);

watch(
    () => page.props.testimonials,
    (newTestimonials) => {
        if (newTestimonials) {
            testimonials.value = newTestimonials;
        }
    },
);

// Type definitions for better TypeScript support
interface Course {
    id: number;
    slug: string;
    title: string;
    description: string;
    image?: string;
    category: string;
    level: string;
    price: number;
    duration: string;
    students_count: number;
    rating: number;
    instructor: {
        name: string;
        avatar?: string;
    };
    isNew?: boolean;
    enrolled: boolean;
    progress: number;
}

const handleCourseEnroll = (course: any) => {
    // Logic to handle course enrollment
    router.visit(route('frontend.courses.show', { slug: course.slug }));
};

// Bangla numeral conversion
function toBanglaNumber(num: number): string {
    const en = '0123456789';
    const bn = '০১২৩৪৫৬৭৮৯';
    return num
        .toString()
        .split('')
        .map((d) => (en.includes(d) ? bn[en.indexOf(d)] : d))
        .join('');
}

// Animated stat values
const displayCourses = ref(0);
const displayStudents = ref(0);
const displayHappyStudents = ref(0);
const displayRating = ref(0);

// Update stat targets
const targetCourses = 15;
const targetStudents = 5000;
const targetHappyStudents = 5000;
const targetRating = 4.9;

function animateStat(refValue: any, target: number, duration = 700) {
    const start = 0;
    const startTime = performance.now();
    function animate(now: number) {
        const elapsed = now - startTime;
        const progress = Math.min(elapsed / duration, 1);
        refValue.value = Math.floor(progress * (target - start) + start);
        if (progress < 1) {
            requestAnimationFrame(animate);
        } else {
            refValue.value = target;
        }
    }
    requestAnimationFrame(animate);
}

// State for donation highlight animation
const donationHighlight = ref(false);
let highlightTimeout: NodeJS.Timeout | null = null;

onMounted(() => {
    animateStat(displayCourses, targetCourses);
    setTimeout(() => animateStat(displayStudents, targetStudents), 200);
    setTimeout(() => animateStat(displayHappyStudents, targetHappyStudents), 400);
    setTimeout(() => animateStat(displayRating, targetRating, 700), 600);

    // Smooth scroll to donation if coming from menu
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('scroll') === 'donation') {
        setTimeout(() => {
            const el = document.getElementById('donation');
            if (el) {
                el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                // Use Vue reactivity instead of direct DOM manipulation
                donationHighlight.value = true;
                highlightTimeout = setTimeout(() => {
                    donationHighlight.value = false;
                }, 1200);
            }
        }, 300);
    }
});

// Testimonials will be loaded from deferred props - initialized above

const toast = useToast();
const form = useForm({
    name: '',
    email: '',
    phone: '',
    amount: '',
    reason: '',
    payment_method: '',
    transaction_id: '',
});

const sanitizeInput = (input: string): string => {
    if (!input) return '';
    return input
        .replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '')
        .replace(/<[^>]*>/g, '')
        .replace(/javascript:/gi, '')
        .replace(/on\w+=/gi, '')
        .trim();
};

const validateForm = (): boolean => {
    if (!form.name || form.name.trim().length < 2) {
        toast.error({ title: 'ত্রুটি', message: 'নাম কমপক্ষে ২ অক্ষরের হতে হবে।' });
        return false;
    }
    if (!form.email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
        toast.error({ title: 'ত্রুটি', message: 'সঠিক ইমেইল ঠিকানা দিন।' });
        return false;
    }
    if (!form.phone || !/^(\+880|880|0)?1[3-9]\d{8}$/.test(form.phone.replace(/\s/g, ''))) {
        toast.error({ title: 'ত্রুটি', message: 'সঠিক বাংলাদেশী ফোন নম্বর দিন।' });
        return false;
    }
    if (!form.amount || form.amount < 1) {
        toast.error({ title: 'ত্রুটি', message: 'দানের পরিমাণ ১ টাকার চেয়ে বেশি হতে হবে।' });
        return false;
    }
    if (!form.payment_method) {
        toast.error({ title: 'ত্রুটি', message: 'পেমেন্ট মাধ্যম নির্বাচন করুন।' });
        return false;
    }
    if (!form.transaction_id || form.transaction_id.trim().length < 5) {
        toast.error({ title: 'ত্রুটি', message: 'ট্রানজেকশন আইডি কমপক্ষে ৫ অক্ষরের হতে হবে।' });
        return false;
    }
    return true;
};

const submitDonation = () => {
    if (!validateForm()) return;

    // Sanitize form data before submission
    const sanitizedForm = {
        name: sanitizeInput(form.name),
        email: sanitizeInput(form.email),
        phone: sanitizeInput(form.phone),
        amount: Number(form.amount),
        reason: sanitizeInput(form.reason),
        payment_method: sanitizeInput(form.payment_method),
        transaction_id: sanitizeInput(form.transaction_id),
    };

    form.transform((data) => sanitizedForm).post(route('donations.store'), {
        preserveScroll: true,
        onSuccess: () => {
            let successMsg = 'আপনার দান সফলভাবে গ্রহণ করা হয়েছে!';
            const flash = page.props.flash as Record<string, any>;
            if (flash && typeof flash === 'object' && typeof flash.success === 'string') {
                successMsg = flash.success;
            }
            toast.success({
                title: 'সফল',
                message: successMsg,
                duration: 5000,
            });
            form.reset();
        },
        onError: (errors) => {
            if (errors.name) {
                toast.error({ title: 'নাম ত্রুটি', message: errors.name });
            } else if (errors.email) {
                toast.error({ title: 'ইমেইল ত্রুটি', message: errors.email });
            } else if (errors.phone) {
                toast.error({ title: 'ফোন ত্রুটি', message: errors.phone });
            } else if (errors.amount) {
                toast.error({ title: 'দানের পরিমাণ ত্রুটি', message: errors.amount });
            } else if (errors.payment_method) {
                toast.error({ title: 'পেমেন্ট মাধ্যম ত্রুটি', message: errors.payment_method });
            } else if (errors.transaction_id) {
                toast.error({ title: 'ট্রানজেকশন আইডি ত্রুটি', message: errors.transaction_id });
            } else if (errors.error) {
                toast.error({ title: 'ত্রুটি', message: errors.error });
            } else {
                toast.error({
                    title: 'ত্রুটি',
                    message: 'দান জমা দিতে সমস্যা হয়েছে। অনুগ্রহ করে পুনরায় চেষ্টা করুন।',
                    duration: 7000,
                });
            }
        },
        onFinish: () => {
            // Reset processing state if needed
        },
    });
};
</script>

<style scoped>
/* Enhanced float animation for hero elements */
@keyframes float {
    0%,
    100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-20px);
    }
}

@keyframes float-delayed {
    0%,
    100% {
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
    0%,
    100% {
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
    0%,
    20%,
    53%,
    80%,
    100% {
        transform: translate3d(0, 0, 0);
    }
    40%,
    43% {
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
    transition:
        transform 0.3s ease,
        box-shadow 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(95, 95, 205, 0.15);
}

@keyframes float-slow {
    0%,
    100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-24px);
    }
}
@keyframes spin-slow {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
.animate-float-slow {
    animation: float-slow 10s ease-in-out infinite;
}
.animate-spin-slow {
    animation: spin-slow 18s linear infinite;
}

@keyframes statIn {
    from {
        opacity: 0;
        transform: translateY(24px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-stat-in {
    opacity: 0;
    transform: translateY(24px);
    animation: statIn 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}

/* Donation highlight animation */
.donation-highlight {
    animation: highlightPulse 1.2s ease-in-out;
}

@keyframes highlightPulse {
    0%,
    100% {
        box-shadow: 0 0 0 rgba(95, 95, 205, 0);
    }
    50% {
        box-shadow:
            0 0 20px rgba(95, 95, 205, 0.3),
            inset 0 0 20px rgba(95, 95, 205, 0.1);
    }
}
</style>
