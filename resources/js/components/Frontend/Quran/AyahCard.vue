<template>
    <div class="group">
        <!-- Ayah Number -->
        <div class="mb-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[#5f5fcd]/10">
                    <span class="text-sm font-semibold text-[#5f5fcd]">{{ toBanglaNumber(ayah.numberInSurah) }}</span>
                </div>
                <div class="text-sm text-gray-500">আয়াত {{ toBanglaNumber(ayah.numberInSurah) }}</div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center space-x-2 opacity-0 transition-opacity group-hover:opacity-100">
                <button
                    @click="playAyah"
                    class="rounded-lg p-2 text-gray-600 transition-colors hover:bg-gray-100 hover:text-[#5f5fcd]"
                    title="এই আয়াত শুনুন"
                >
                    <PlayIcon class="h-4 w-4" />
                </button>

                <button
                    @click="shareAyah"
                    class="rounded-lg p-2 text-gray-600 transition-colors hover:bg-gray-100 hover:text-[#5f5fcd]"
                    title="শেয়ার করুন"
                >
                    <ShareIcon class="h-4 w-4" />
                </button>

                <button
                    @click="copyAyah"
                    class="rounded-lg p-2 text-gray-600 transition-colors hover:bg-gray-100 hover:text-[#5f5fcd]"
                    title="কপি করুন"
                >
                    <CopyIcon class="h-4 w-4" />
                </button>
            </div>
        </div>

        <!-- Arabic Text -->
        <div class="mb-6 rounded-xl border border-[#5f5fcd]/10 bg-gradient-to-r from-[#5f5fcd]/5 to-[#2d5a27]/5 p-6" dir="rtl">
            <div
                class="font-arabic cursor-pointer text-center leading-loose text-[#5f5fcd] transition-colors hover:text-[#4a4aa6]"
                :style="{ fontSize: fontSize + 'px' }"
                @click="playAyah"
                title="ক্লিক করে শুনুন"
            >
                {{ ayah.text }}
            </div>
        </div>

        <!-- Translation -->
        <div v-if="showTranslation" class="mb-4">
            <div v-if="translation" class="px-4 text-center leading-relaxed text-gray-700">"{{ translation }}"</div>
            <div v-else class="py-2 text-center">
                <div class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-sm text-gray-500">
                    <div class="mr-2 h-3 w-3 animate-spin rounded-full border-b-2 border-gray-400"></div>
                    অনুবাদ লোড হচ্ছে...
                </div>
            </div>
        </div>

        <!-- Reference -->
        <div class="text-center text-sm text-gray-500">
            <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1">
                <BookOpenIcon class="mr-1 h-3 w-3" />
                {{ ayah.surah?.englishName || 'সূরা' }} • আয়াত {{ toBanglaNumber(ayah.numberInSurah) }}
            </span>
        </div>

        <!-- Audio Loading Indicator -->
        <div v-if="audioLoading" class="mt-4 text-center">
            <div class="inline-flex items-center rounded-full bg-[#5f5fcd]/10 px-3 py-1 text-sm text-[#5f5fcd]">
                <div class="mr-2 h-3 w-3 animate-spin rounded-full border-b-2 border-[#5f5fcd]"></div>
                অডিও লোড হচ্ছে...
            </div>
        </div>
    </div>
</template>

<script setup>
import { BookOpenIcon, CopyIcon, PlayIcon, ShareIcon } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps({
    ayah: {
        type: Object,
        required: true,
    },
    translation: {
        type: String,
        default: '',
    },
    showTranslation: {
        type: Boolean,
        default: true,
    },
    fontSize: {
        type: Number,
        default: 20,
    },
    selectedReciter: {
        type: String,
        default: 'ar.alafasy',
    },
});

const emit = defineEmits(['play']);

const audioLoading = ref(false);

const toBanglaNumber = (num) => {
    const en = '0123456789';
    const bn = '০১২৩৪৫৬৭৮৯';
    return num
        .toString()
        .split('')
        .map((d) => (en.includes(d) ? bn[en.indexOf(d)] : d))
        .join('');
};

const playAyah = () => {
    audioLoading.value = true;
    emit('play', props.ayah);

    // Reset loading state after a delay
    setTimeout(() => {
        audioLoading.value = false;
    }, 2000);
};

const shareAyah = () => {
    const shareText = `${props.ayah.text}\n\n"${props.translation || 'অনুবাদ উপলব্ধ নেই'}"\n\n- ${props.ayah.surah?.englishName || 'সূরা'}, আয়াত ${props.ayah.numberInSurah}\n\nইকরা অনলাইন একাডেমি থেকে`;

    if (navigator.share) {
        navigator
            .share({
                title: `কুরআনের আয়াত - ${props.ayah.surah?.englishName || 'সূরা'} ${props.ayah.numberInSurah}`,
                text: shareText,
                url: window.location.href,
            })
            .catch(console.error);
    } else {
        navigator.clipboard
            .writeText(shareText)
            .then(() => {
                alert('আয়াতটি কপি করা হয়েছে!');
            })
            .catch(() => {
                alert(shareText);
            });
    }
};

const copyAyah = () => {
    const copyText = `${props.ayah.text}\n\n"${props.translation || 'অনুবাদ উপলব্ধ নেই'}"\n\n- ${props.ayah.surah?.englishName || 'সূরা'}, আয়াত ${props.ayah.numberInSurah}`;

    navigator.clipboard
        .writeText(copyText)
        .then(() => {
            alert('আয়াতটি কপি করা হয়েছে!');
        })
        .catch(() => {
            alert('কপি করতে সমস্যা হয়েছে।');
        });
};
</script>

<style scoped>
.font-arabic {
    font-family: 'Amiri', 'Scheherazade', 'Noto Naskh Arabic', serif;
    line-height: 1.8;
}

[dir='rtl'] {
    text-align: center;
}

/* Hover effects */
.group:hover .arabic-text {
    transform: scale(1.02);
    transition: transform 0.2s ease;
}
</style>
