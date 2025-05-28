<template>
  <div ref="htmlelement" class="editorjs"></div>
</template>
<script setup>

import {onMounted, onUnmounted, ref, watch} from 'vue';

import EditorJS from "@editorjs/editorjs";
import Header from "editorjs-header-with-anchor";
import List from "@editorjs/list";
import ImageTool from "@editorjs/image";
import Quote from "@editorjs/quote";
import Table from "@editorjs/table";
import Code from "@editorjs/code";
import Delimiter from "@editorjs/delimiter";
import InlineCode from "@editorjs/inline-code";
import Marker from "@editorjs/marker";
import Alert from 'editorjs-alert';
import DragDrop from "editorjs-drag-drop";
import Undo from 'editorjs-undo';
import LinkTool from '@editorjs/link';

const htmlelement = ref(null);

const props = defineProps({
  modelValue: {
    type: [Object, String],
    default: null,
  },
  placeholder: {type: String, default: "Start writing..."},
  readOnly: Boolean,
});

const emit = defineEmits(['update:modelValue']);
let editor;
let updatingModel = false;

// model -> view
function modelToView() {
  if (!props.modelValue) {
    return;
  }
  if (typeof props.modelValue === 'string') {
    editor.blocks.renderFromHTML(props.modelValue);
    return;
  }
  editor.render(props.modelValue);
}

// view -> model
function viewToModel(api, event) {
  if (props.readOnly) {
    return;
  }
  updatingModel = true;
  editor.save().then((outputData) => {
    console.log(event, 'Saving completed: ', outputData)
    emit('update:modelValue', outputData);
  }).catch((error) => {
    console.log(event, 'Saving failed: ', error)
  }).finally(() => {
    updatingModel = false;
  })
}

onMounted(() => {
  editor = new EditorJS({
    holder: htmlelement.value,
    placeholder: props.placeholder,
    inlineToolbar: ['bold', 'italic', 'link', 'marker'],
    tools: {
      linkTool: {
        class: LinkTool,
        config: {
          endpoint: route('admin.docs.url.info'),
        }
      },
      header: {
        class: Header,
        placeholder: 'Enter a header',
        levels: [2, 3, 4],
        defaultLevel: 2,
        allowAnchor: true,
      },
      list: {class: List, inlineToolbar: true},
      image: {
        class: ImageTool,
        inlineToolbar: true,
        config: {
          endpoints: {
            byFile: route('admin.docs.image.upload')
          },

          additionalRequestData: {
            _token: document.querySelector('meta[name="csrf-token"]').content, // Add CSRF token
          },
        }
      },
      quote: {class: Quote, inlineToolbar: true},
      table: {class: Table, inlineToolbar: true},
      code: Code,
      alert: Alert,
      delimiter: Delimiter,
      inlineCode: {class: InlineCode, inlineToolbar: true},
      marker: {class: Marker, inlineToolbar: true},
    },
    readOnly: props.readOnly,
    minHeight: 'auto',
    data: props.modelValue,
    onReady: () => {
      modelToView();
      new Undo({editor});
      new DragDrop(editor); // Initialize DragDrop
    },
    onChange: viewToModel,
  })
})
watch(() => props.modelValue, () => {
  if (!updatingModel) {
    modelToView()
  }
})
onUnmounted(() => {
  editor.destroy()
})
</script>

<style>
.ce-block__content, .ce-toolbar__content {
  max-width: 100%;
}
</style>