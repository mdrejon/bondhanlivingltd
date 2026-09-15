<template>
    <div class="rich-editor-wrapper border border-gray-300 rounded overflow-hidden">
        <!-- Toolbar -->
        <div class="rich-editor-toolbar flex flex-wrap gap-1 bg-gray-50 border-b border-gray-200 p-2">
            <button type="button" @click="editor.chain().focus().toggleBold().run()"
                :class="['toolbar-btn', editor?.isActive('bold') ? 'active' : '']" title="Bold">
                <strong>B</strong>
            </button>
            <button type="button" @click="editor.chain().focus().toggleItalic().run()"
                :class="['toolbar-btn', editor?.isActive('italic') ? 'active' : '']" title="Italic">
                <em>I</em>
            </button>
            <button type="button" @click="editor.chain().focus().toggleUnderline().run()"
                :class="['toolbar-btn', editor?.isActive('underline') ? 'active' : '']" title="Underline">
                <u>U</u>
            </button>
            <div class="w-px bg-gray-300 mx-1 self-stretch"></div>
            <button type="button" @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
                :class="['toolbar-btn', editor?.isActive('heading', { level: 2 }) ? 'active' : '']" title="Heading 2">
                H2
            </button>
            <button type="button" @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
                :class="['toolbar-btn', editor?.isActive('heading', { level: 3 }) ? 'active' : '']" title="Heading 3">
                H3
            </button>
            <div class="w-px bg-gray-300 mx-1 self-stretch"></div>
            <button type="button" @click="editor.chain().focus().toggleBulletList().run()"
                :class="['toolbar-btn', editor?.isActive('bulletList') ? 'active' : '']" title="Bullet List">
                &#8226; List
            </button>
            <button type="button" @click="editor.chain().focus().toggleOrderedList().run()"
                :class="['toolbar-btn', editor?.isActive('orderedList') ? 'active' : '']" title="Ordered List">
                1. List
            </button>
            <div class="w-px bg-gray-300 mx-1 self-stretch"></div>
            <button type="button" @click="editor.chain().focus().setHardBreak().run()" title="Line Break" class="toolbar-btn">
                &#8629;BR
            </button>
            <button type="button" @click="editor.chain().focus().unsetAllMarks().clearNodes().run()" title="Clear Formatting" class="toolbar-btn">
                &#10006;
            </button>
        </div>
        <!-- Editor area -->
        <EditorContent :editor="editor" class="rich-editor-content" />
    </div>
</template>

<script setup>
import { watch, onBeforeUnmount } from 'vue';
import { useEditor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Underline from '@tiptap/extension-underline';

const props = defineProps({
    modelValue: { type: String, default: '' },
});

const emit = defineEmits(['update:modelValue']);

const editor = useEditor({
    content: props.modelValue ?? '',
    extensions: [
        StarterKit,
        Underline,
    ],
    editorProps: {
        attributes: { class: 'prose prose-sm max-w-none p-3 min-h-[120px] focus:outline-none text-gray-700' },
    },
    onUpdate({ editor }) {
        emit('update:modelValue', editor.getHTML());
    },
});

watch(() => props.modelValue, (val) => {
    if (editor.value && editor.value.getHTML() !== val) {
        editor.value.commands.setContent(val ?? '', false);
    }
});

onBeforeUnmount(() => editor.value?.destroy());
</script>

<style scoped>
.toolbar-btn {
    @apply px-2 py-1 text-xs rounded text-gray-600 hover:bg-gray-200 transition-colors;
}
.toolbar-btn.active {
    @apply bg-blue-100 text-blue-700;
}
.rich-editor-content :deep(.ProseMirror) {
    min-height: 120px;
}
.rich-editor-content :deep(.ProseMirror p) {
    margin-bottom: 0.5rem;
}
.rich-editor-content :deep(.ProseMirror ul) {
    list-style: disc;
    padding-left: 1.25rem;
}
.rich-editor-content :deep(.ProseMirror ol) {
    list-style: decimal;
    padding-left: 1.25rem;
}
.rich-editor-content :deep(.ProseMirror h2) {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}
.rich-editor-content :deep(.ProseMirror h3) {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.375rem;
}
</style>
