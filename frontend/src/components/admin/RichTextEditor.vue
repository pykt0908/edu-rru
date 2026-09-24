<script setup lang="ts">
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import Underline from '@tiptap/extension-underline'
import Link from '@tiptap/extension-link'
import { watch, onBeforeUnmount, ref } from 'vue'

const props = withDefaults(
  defineProps<{
    modelValue: string
    placeholder?: string
    minHeight?: string
  }>(),
  {
    modelValue: '',
    placeholder: 'พิมพ์หรือวางเนื้อหารายละเอียดข่าวที่นี่ สามารถจัดหัวข้อ ตัวหนา ตัวเอียง ลิงก์ และรายการได้...',
    minHeight: 'min-h-[280px]',
  }
)

const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void
}>()

const linkUrl = ref('')

const editor = useEditor({
  content: props.modelValue,
  extensions: [
    StarterKit.configure({
      heading: {
        levels: [2, 3],
      },
    }),
    Underline,
    Link.configure({
      openOnClick: false,
      HTMLAttributes: {
        class: 'text-emerald-700 underline font-semibold hover:text-emerald-800',
      },
    }),
  ],
  editorProps: {
    attributes: {
      class: `focus:outline-none ${props.minHeight || 'min-h-[280px]'} p-4 text-slate-800 text-sm leading-relaxed`,
    },
  },
  onUpdate: () => {
    emit('update:modelValue', editor.value?.getHTML() || '')
  },
})

// Sync incoming modelValue changes
watch(
  () => props.modelValue,
  (newVal) => {
    if (!editor.value) return
    const isSame = editor.value.getHTML() === newVal
    if (!isSame) {
      editor.value.commands.setContent(newVal || '', { emitUpdate: false })
    }
  }
)

onBeforeUnmount(() => {
  editor.value?.destroy()
})

// Link action
const setLink = () => {
  if (!editor.value) return
  const previousUrl = editor.value.getAttributes('link').href || ''
  linkUrl.value = previousUrl
  const url = window.prompt('ระบุ URL ลิงก์ (เช่น https://example.com)', previousUrl)
  if (url === null) return
  if (url === '') {
    editor.value.chain().focus().extendMarkRange('link').unsetLink().run()
    return
  }
  editor.value.chain().focus().extendMarkRange('link').setLink({ href: url }).run()
}
</script>

<template>
  <div class="richtext-wrapper bg-white border border-slate-300 rounded-xl overflow-hidden focus-within:border-emerald-600 focus-within:ring-2 focus-within:ring-emerald-500/20 transition-all shadow-2xs">
    <!-- ── Toolbar ──────────────────────────────────────────────────────── -->
    <div
      v-if="editor"
      class="bg-slate-50/90 border-b border-slate-200 px-3 py-2 flex flex-wrap items-center gap-1 text-slate-700 select-none sticky top-0 z-10"
    >
      <!-- Headings -->
      <button
        type="button"
        class="px-2.5 py-1 rounded-lg text-xs font-bold transition-colors cursor-pointer"
        :class="editor.isActive('paragraph') ? 'bg-white text-emerald-800 shadow-2xs border border-slate-200' : 'hover:bg-slate-200/70 text-slate-600'"
        title="ข้อความปกติ (Paragraph)"
        @click="editor.chain().focus().setParagraph().run()"
      >
        ปกติ
      </button>

      <button
        type="button"
        class="px-2.5 py-1 rounded-lg text-xs font-bold transition-colors cursor-pointer"
        :class="editor.isActive('heading', { level: 2 }) ? 'bg-white text-emerald-800 shadow-2xs border border-slate-200' : 'hover:bg-slate-200/70 text-slate-600'"
        title="หัวข้อหลัก (Heading 2)"
        @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
      >
        H2
      </button>

      <button
        type="button"
        class="px-2.5 py-1 rounded-lg text-xs font-bold transition-colors cursor-pointer"
        :class="editor.isActive('heading', { level: 3 }) ? 'bg-white text-emerald-800 shadow-2xs border border-slate-200' : 'hover:bg-slate-200/70 text-slate-600'"
        title="หัวข้อย่อย (Heading 3)"
        @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
      >
        H3
      </button>

      <div class="w-px h-5 bg-slate-300 mx-1" />

      <!-- Text Styling -->
      <button
        type="button"
        class="w-7 h-7 rounded-lg flex items-center justify-center transition-colors cursor-pointer"
        :class="editor.isActive('bold') ? 'bg-emerald-600 text-white shadow-2xs' : 'hover:bg-slate-200/70 text-slate-700'"
        title="ตัวหนา (Ctrl+B)"
        @click="editor.chain().focus().toggleBold().run()"
      >
        <v-icon icon="mdi-format-bold" size="17" />
      </button>

      <button
        type="button"
        class="w-7 h-7 rounded-lg flex items-center justify-center transition-colors cursor-pointer"
        :class="editor.isActive('italic') ? 'bg-emerald-600 text-white shadow-2xs' : 'hover:bg-slate-200/70 text-slate-700'"
        title="ตัวเอียง (Ctrl+I)"
        @click="editor.chain().focus().toggleItalic().run()"
      >
        <v-icon icon="mdi-format-italic" size="17" />
      </button>

      <button
        type="button"
        class="w-7 h-7 rounded-lg flex items-center justify-center transition-colors cursor-pointer"
        :class="editor.isActive('underline') ? 'bg-emerald-600 text-white shadow-2xs' : 'hover:bg-slate-200/70 text-slate-700'"
        title="ขีดเส้นใต้ (Ctrl+U)"
        @click="editor.chain().focus().toggleUnderline().run()"
      >
        <v-icon icon="mdi-format-underline" size="17" />
      </button>

      <button
        type="button"
        class="w-7 h-7 rounded-lg flex items-center justify-center transition-colors cursor-pointer"
        :class="editor.isActive('strike') ? 'bg-emerald-600 text-white shadow-2xs' : 'hover:bg-slate-200/70 text-slate-700'"
        title="ขีดฆ่า"
        @click="editor.chain().focus().toggleStrike().run()"
      >
        <v-icon icon="mdi-format-strikethrough" size="17" />
      </button>

      <div class="w-px h-5 bg-slate-300 mx-1" />

      <!-- Lists -->
      <button
        type="button"
        class="w-7 h-7 rounded-lg flex items-center justify-center transition-colors cursor-pointer"
        :class="editor.isActive('bulletList') ? 'bg-emerald-600 text-white shadow-2xs' : 'hover:bg-slate-200/70 text-slate-700'"
        title="รายการแบบจุด (Bullet List)"
        @click="editor.chain().focus().toggleBulletList().run()"
      >
        <v-icon icon="mdi-format-list-bulleted" size="17" />
      </button>

      <button
        type="button"
        class="w-7 h-7 rounded-lg flex items-center justify-center transition-colors cursor-pointer"
        :class="editor.isActive('orderedList') ? 'bg-emerald-600 text-white shadow-2xs' : 'hover:bg-slate-200/70 text-slate-700'"
        title="รายการแบบตัวเลข (Numbered List)"
        @click="editor.chain().focus().toggleOrderedList().run()"
      >
        <v-icon icon="mdi-format-list-numbered" size="17" />
      </button>

      <button
        type="button"
        class="w-7 h-7 rounded-lg flex items-center justify-center transition-colors cursor-pointer"
        :class="editor.isActive('blockquote') ? 'bg-emerald-600 text-white shadow-2xs' : 'hover:bg-slate-200/70 text-slate-700'"
        title="กล่องข้อความอ้างอิง (Blockquote)"
        @click="editor.chain().focus().toggleBlockquote().run()"
      >
        <v-icon icon="mdi-format-quote-close" size="17" />
      </button>

      <button
        type="button"
        class="w-7 h-7 rounded-lg flex items-center justify-center transition-colors cursor-pointer hover:bg-slate-200/70 text-slate-700"
        title="แทรกเส้นคั่นแนวนอน"
        @click="editor.chain().focus().setHorizontalRule().run()"
      >
        <v-icon icon="mdi-minus" size="17" />
      </button>

      <div class="w-px h-5 bg-slate-300 mx-1" />

      <!-- Link -->
      <button
        type="button"
        class="w-7 h-7 rounded-lg flex items-center justify-center transition-colors cursor-pointer"
        :class="editor.isActive('link') ? 'bg-emerald-600 text-white shadow-2xs' : 'hover:bg-slate-200/70 text-slate-700'"
        title="แทรกลิงก์"
        @click="setLink"
      >
        <v-icon icon="mdi-link-variant" size="17" />
      </button>

      <button
        v-if="editor.isActive('link')"
        type="button"
        class="w-7 h-7 rounded-lg flex items-center justify-center transition-colors cursor-pointer hover:bg-slate-200/70 text-rose-600"
        title="ยกเลิกลิงก์"
        @click="editor.chain().focus().unsetLink().run()"
      >
        <v-icon icon="mdi-link-variant-off" size="17" />
      </button>

      <div class="w-px h-5 bg-slate-300 mx-1" />

      <!-- Undo / Redo -->
      <button
        type="button"
        class="w-7 h-7 rounded-lg flex items-center justify-center transition-colors cursor-pointer hover:bg-slate-200/70 text-slate-700 disabled:opacity-30 disabled:pointer-events-none"
        :disabled="!editor.can().undo()"
        title="เลิกทำ (Ctrl+Z)"
        @click="editor.chain().focus().undo().run()"
      >
        <v-icon icon="mdi-undo" size="17" />
      </button>

      <button
        type="button"
        class="w-7 h-7 rounded-lg flex items-center justify-center transition-colors cursor-pointer hover:bg-slate-200/70 text-slate-700 disabled:opacity-30 disabled:pointer-events-none"
        :disabled="!editor.can().redo()"
        title="ทำซ้ำ (Ctrl+Y)"
        @click="editor.chain().focus().redo().run()"
      >
        <v-icon icon="mdi-redo" size="17" />
      </button>

      <button
        type="button"
        class="w-7 h-7 rounded-lg flex items-center justify-center transition-colors cursor-pointer hover:bg-slate-200/70 text-slate-700"
        title="ล้างการจัดรูปแบบ"
        @click="editor.chain().focus().unsetAllMarks().clearNodes().run()"
      >
        <v-icon icon="mdi-format-clear" size="16" />
      </button>
    </div>

    <!-- ── Editor Canvas ────────────────────────────────────────────────── -->
    <EditorContent :editor="editor" class="tiptap-content" />
  </div>
</template>

<style>
/* Rich Text Formatting Styles */
.tiptap-content .tiptap {
  font-family: 'LINE Seed Sans TH', system-ui, sans-serif;
}

.tiptap-content .tiptap p {
  margin-bottom: 0.85rem;
  line-height: 1.75;
}

.tiptap-content .tiptap h2 {
  font-size: 1.25rem;
  font-weight: 700;
  color: #0f172a;
  margin-top: 1.25rem;
  margin-bottom: 0.6rem;
  line-height: 1.4;
}

.tiptap-content .tiptap h3 {
  font-size: 1.1rem;
  font-weight: 700;
  color: #1e293b;
  margin-top: 1rem;
  margin-bottom: 0.5rem;
  line-height: 1.4;
}

.tiptap-content .tiptap ul {
  list-style-type: disc;
  padding-left: 1.5rem;
  margin-bottom: 0.85rem;
}

.tiptap-content .tiptap ol {
  list-style-type: decimal;
  padding-left: 1.5rem;
  margin-bottom: 0.85rem;
}

.tiptap-content .tiptap li {
  margin-bottom: 0.35rem;
  line-height: 1.6;
}

.tiptap-content .tiptap blockquote {
  border-left: 4px solid #059669;
  background-color: #f0fdf4;
  padding: 0.75rem 1rem;
  border-radius: 0 0.75rem 0.75rem 0;
  margin: 1rem 0;
  color: #064e3b;
  font-style: italic;
}

.tiptap-content .tiptap hr {
  border: none;
  border-top: 1px solid #e2e8f0;
  margin: 1.5rem 0;
}

.tiptap-content .tiptap a {
  color: #047857;
  text-decoration: underline;
  font-weight: 600;
}

.tiptap-content .tiptap a:hover {
  color: #065f46;
}

.tiptap-content .tiptap:empty::before {
  content: attr(placeholder);
  color: #94a3b8;
  pointer-events: none;
  display: block;
}
</style>
