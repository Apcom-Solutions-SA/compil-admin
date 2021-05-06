<template>
  <div>
    <div
      class="tiptap-menu d-flex flex-wrap align-items-center"
      v-if="editor"
    >
      <!-- basic formatting -->
      <div>
        <button
          @click="editor.chain().focus().toggleBold().run()"
          :class="{ 'is-active': editor.isActive('bold') }"
        >
          <i class="fas fa-bold"></i>
        </button>
        <button
          @click="editor.chain().focus().toggleItalic().run()"
          :class="{ 'is-active': editor.isActive('italic') }"
        >
          <i class="fas fa-italic"></i>
        </button>
        <button
          @click="editor.chain().focus().toggleStrike().run()"
          :class="{ 'is-active': editor.isActive('strike') }"
        >
          <i class="fas fa-strikethrough"></i>
        </button>

        <button
          @click="editor.chain().focus().toggleCode().run()"
          :class="{ 'is-active': editor.isActive('code') }"
        >
          <i class="fas fa-code"></i>
        </button>

        <button
          @click="editor.chain().focus().setParagraph().run()"
          :class="{ 'is-active': editor.isActive('paragraph') }"
        >
          <i class="fas fa-paragraph"></i>
        </button>
      </div>

      <!-- no icons yet -->
      <div v-if="false">
        <button @click="editor.chain().focus().unsetAllMarks().run()">
          clear marks
        </button>

        <button @click="editor.chain().focus().clearNodes().run()">
          clear nodes
        </button>

        <button @click="editor.chain().focus().setHorizontalRule().run()">
          horizontal rule
        </button>

        <button @click="editor.chain().focus().setHardBreak().run()">
          hard break
        </button>

        <button
          @click="editor.chain().focus().toggleCodeBlock().run()"
          :class="{ 'is-active': editor.isActive('codeBlock') }"
        >
          code block
        </button>
      </div>

      <!-- list -->
      <button
        @click="editor.chain().focus().toggleBulletList().run()"
        :class="{ 'is-active': editor.isActive('bulletList') }"
      >
        <i class="fas fa-list-ul"></i>
      </button>

      <button
        @click="editor.chain().focus().toggleOrderedList().run()"
        :class="{ 'is-active': editor.isActive('orderedList') }"
      >
        <i class="fas fa-list-ol"></i>
      </button>

      <button
        @click="editor.chain().focus().toggleBlockquote().run()"
        :class="{ 'is-active': editor.isActive('blockquote') }"
      >
        <i class="fas fa-quote-left"></i>
      </button>

      <!-- image -->
      <button @click="addImage">
        <i class="far fa-image"></i>
      </button>

      <!-- heading-->
      <div>
        <button
          @click="editor.chain().focus().toggleHeading({ level: 1 }).run()"
          :class="{ 'is-active': editor.isActive('heading', { level: 1 }) }"
        >
          h1
        </button>
        <button
          @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
          :class="{ 'is-active': editor.isActive('heading', { level: 2 }) }"
        >
          h2
        </button>
        <button
          @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
          :class="{ 'is-active': editor.isActive('heading', { level: 3 }) }"
        >
          h3
        </button>
        <button
          @click="editor.chain().focus().toggleHeading({ level: 4 }).run()"
          :class="{ 'is-active': editor.isActive('heading', { level: 4 }) }"
        >
          h4
        </button>
        <button
          @click="editor.chain().focus().toggleHeading({ level: 5 }).run()"
          :class="{ 'is-active': editor.isActive('heading', { level: 5 }) }"
        >
          h5
        </button>
        <button
          @click="editor.chain().focus().toggleHeading({ level: 6 }).run()"
          :class="{ 'is-active': editor.isActive('heading', { level: 6 }) }"
        >
          h6
        </button>
      </div>

      <!-- text align -->
      <div>
        <button
          @click="editor.chain().focus().setTextAlign('left').run()"
          :class="{ 'is-active': editor.isActive({ textAlign: 'left' }) }"
        >
          <i class="fas fa-align-left"></i>
        </button>
        <button
          @click="editor.chain().focus().setTextAlign('center').run()"
          :class="{ 'is-active': editor.isActive({ textAlign: 'center' }) }"
        >
          <i class="fas fa-align-center"></i>
        </button>
        <button
          @click="editor.chain().focus().setTextAlign('right').run()"
          :class="{ 'is-active': editor.isActive({ textAlign: 'right' }) }"
        >
          <i class="fas fa-align-right"></i>
        </button>
        <button
          @click="editor.chain().focus().setTextAlign('justify').run()"
          :class="{ 'is-active': editor.isActive({ textAlign: 'justify' }) }"
        >
          <i class="fas fa-align-justify"></i>
        </button>
      </div>

      <!--high light-->
      <div>
        <button
          @click="editor.chain().focus().toggleHighlight().run()"
          :class="{ 'is-active': editor.isActive('highlight') }"
        >
          <i class="fas fa-highlighter"></i>
        </button>

        <button
          @click="editor.chain().focus().toggleHighlight({
            color: ''
          }).run()"
          :class="{ 'is-active': editor.isActive('highlight', {
            color: ''
          }) }"
        >
          <i
            class="fas fa-highlighter"
            style="color:#fcf8e3"
          ></i>
        </button>

        <button
          @click="editor.chain().focus().toggleHighlight({ color: 'red' }).run()"
          :class="{ 'is-active': editor.isActive('highlight', { color: 'red' }) }"
        >
          <i
            class="fas fa-highlighter"
            style="color:red"
          ></i>
        </button>

        <button
          @click="editor.chain().focus().toggleHighlight({ color: '#ffc078' }).run()"
          :class="{ 'is-active': editor.isActive('highlight', { color: '#ffc078' }) }"
        >
          <i
            class="fas fa-highlighter"
            style="color:#ffc078"
          ></i>
        </button>
        <button
          @click="editor.chain().focus().toggleHighlight({ color: '#8ce99a' }).run()"
          :class="{ 'is-active': editor.isActive('highlight', { color: '#8ce99a' }) }"
        >
          <i
            class="fas fa-highlighter"
            style="color:#8ce99a"
          ></i>
        </button>
        <button
          @click="editor.chain().focus().toggleHighlight({ color: '#74c0fc' }).run()"
          :class="{ 'is-active': editor.isActive('highlight', { color: '#74c0fc' }) }"
        >
          <i
            class="fas fa-highlighter"
            style="color:#74c0fc"
          ></i>
        </button>

        <button
          @click="editor.chain().focus().toggleHighlight({ color: '#b197fc' }).run()"
          :class="{ 'is-active': editor.isActive('highlight', { color: '#b197fc' }) }"
        >
          <i
            class="fas fa-highlighter"
            style="color:#b197fc"
          ></i>
        </button>
      </div>

      <button @click="editor.chain().focus().undo().run()">
        <i class="fas fa-undo"></i>
      </button>
      <button @click="editor.chain().focus().redo().run()">
        <i class="fas fa-redo"></i>
      </button>
    </div>
    <editor-content :editor="editor" />
  </div>
</template>

<script>
import { Editor, EditorContent, BubbleMenu, FloatingMenu } from '@tiptap/vue-2'
import { defaultExtensions } from '@tiptap/starter-kit'
import Document from '@tiptap/extension-document'
import Paragraph from '@tiptap/extension-paragraph'
import Text from '@tiptap/extension-text'
import Highlight from '@tiptap/extension-highlight'
import Typography from '@tiptap/extension-typography'
import Image from '@tiptap/extension-image'
import Dropcursor from '@tiptap/extension-dropcursor'
import TextAlign from '@tiptap/extension-text-align'

import Icon from "./Icon";

export default {
  components: {
    EditorContent,
    BubbleMenu,
    Icon
  },

  props: {
    value: {
      type: String,
      default: '',
    },
  },

  data() {
    return {
      editor: null,
    }
  },

  watch: {
    value(value) {
      // HTML
      const isSame = this.editor.getHTML() === value

      // JSON
      // const isSame = this.editor.getJSON().toString() === value.toString()

      if (isSame) {
        return
      }

      this.editor.commands.setContent(this.value, false)
    },
  },

  mounted() {
    this.editor = new Editor({
      extensions: [
        ...defaultExtensions(),
        Document,
        Paragraph,
        Text,
        Highlight.configure({ multicolor: true }),
        Typography,
        Image,
        Dropcursor,
        TextAlign,
      ],
      content: this.value,
      onUpdate: () => {
        // HTML
        this.$emit('input', this.editor.getHTML())

        // JSON
        // this.$emit('input', this.editor.getJSON())
      },
    })
  },

  beforeDestroy() {
    this.editor.destroy()
  },

  methods: {
    addImage() {
      const url = window.prompt('URL')

      if (url) {
        this.editor.chain().focus().setImage({ src: url }).run()
      }
    },
  },
}
</script>
<style lang="scss">
/* Basic editor styles */
.ProseMirror {
  > * + * {
    margin-top: 0.75em;
  }

  ul,
  ol {
    padding: 0 1rem;
    p {
      margin-bottom: 0;
    }
  }

  blockquote {
    padding-left: 1rem;
    border-left: 2px solid rgba(#0d0d0d, 0.1);
  }

  img {
    max-width: 100%;
    height: auto;

    &.ProseMirror-selectednode {
      outline: 3px solid #68cef8;
    }
  }
}

.bubble-menu {
  display: flex;
  background-color: #0d0d0d;
  padding: 0.2rem;
  border-radius: 0.5rem;

  button {
    border: none;
    background: none;
    color: #fff;
    font-size: 0.85rem;
    font-weight: 500;
    padding: 0 0.2rem;
    opacity: 0.6;

    &:hover,
    &.is-active {
      opacity: 1;
    }
  }
}

.floating-menu {
  display: flex;
  background-color: #0d0d0d10;
  padding: 0.2rem;
  border-radius: 0.5rem;

  button {
    border: none;
    background: none;
    font-size: 0.85rem;
    font-weight: 500;
    padding: 0 0.2rem;
    opacity: 0.6;

    &:hover,
    &.is-active {
      opacity: 1;
    }
  }
}
</style>