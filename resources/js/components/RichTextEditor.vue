<template>
    <div class="space-y-2">
        <label v-if="label" :for="inputId" class="block text-sm font-medium text-gray-700">
            {{ label }}
            <span v-if="required" class="ml-1 text-red-500">*</span>
        </label>

        <div class="overflow-hidden rounded-lg border border-gray-300 focus-within:border-[#2d5a27] focus-within:ring-2 focus-within:ring-[#2d5a27]">
            <QuillEditor
                ref="quillEditor"
                v-model:content="content"
                content-type="html"
                :options="editorOptions"
                :placeholder="placeholder"
                @update:content="handleContentUpdate"
                class="min-h-[200px]"
            />
        </div>

        <p v-if="placeholder && !required" class="mt-1 text-xs text-gray-500">
            {{ placeholder }}
        </p>

        <div v-if="error" class="mt-1 text-sm text-red-600">
            {{ error }}
        </div>
    </div>
</template>

<script setup lang="ts">
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { nextTick, ref, watch } from 'vue';

interface Props {
    modelValue?: string;
    label?: string;
    placeholder?: string;
    required?: boolean;
    error?: string;
    inputId?: string;
    minHeight?: string;
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: '',
    placeholder: 'Enter your content...',
    required: false,
    minHeight: '200px',
});

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();

const quillEditor = ref();
const content = ref(props.modelValue);

// Quill editor configuration
const editorOptions = {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ header: [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ color: [] }, { background: [] }],
            [{ list: 'ordered' }, { list: 'bullet' }],
            [{ indent: '-1' }, { indent: '+1' }],
            [{ align: [] }],
            ['blockquote', 'code-block'],
            ['link', 'image'],
            ['clean'],
        ],
    },
    placeholder: props.placeholder,
    readOnly: false,
};

// Handle content updates
const handleContentUpdate = (newContent: string) => {
    content.value = newContent;
    emit('update:modelValue', newContent);
};

// Watch for external changes to modelValue
watch(
    () => props.modelValue,
    (newValue) => {
        if (newValue !== content.value) {
            content.value = newValue;
        }
    },
    { immediate: true },
);

// Set custom height
nextTick(() => {
    if (quillEditor.value?.getQuill) {
        const editor = quillEditor.value.getQuill();
        const editorContainer = editor.container.querySelector('.ql-editor');
        if (editorContainer) {
            editorContainer.style.minHeight = props.minHeight;
        }
    }
});
</script>

<style>
/* Custom Quill styles to match our design */
.ql-toolbar {
    border-top: none !important;
    border-left: none !important;
    border-right: none !important;
    border-bottom: 1px solid rgb(209 213 219) !important;
    background-color: rgb(249 250 251);
}

.ql-container {
    border: none !important;
    font-family: inherit;
}

.ql-editor {
    font-family: inherit;
    font-size: 14px;
    line-height: 1.5;
    padding: 12px 16px;
}

.ql-editor.ql-blank::before {
    color: rgb(156 163 175);
    font-style: normal;
}

/* Islamic theme colors for active buttons */
.ql-toolbar .ql-formats .ql-active {
    color: #2d5a27 !important;
}

.ql-toolbar button:hover {
    color: #2d5a27 !important;
}

.ql-toolbar button.ql-active {
    color: #2d5a27 !important;
}

.ql-toolbar .ql-stroke {
    stroke: currentColor;
}

.ql-toolbar .ql-fill {
    fill: currentColor;
}

/* Custom scrollbar */
.ql-editor::-webkit-scrollbar {
    width: 6px;
}

.ql-editor::-webkit-scrollbar-track {
    background: rgb(243 244 246);
}

.ql-editor::-webkit-scrollbar-thumb {
    background: rgb(209 213 219);
    border-radius: 3px;
}

.ql-editor::-webkit-scrollbar-thumb:hover {
    background: rgb(156 163 175);
}
</style>
