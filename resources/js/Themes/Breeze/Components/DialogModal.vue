<script setup>
import Modal from './Modal.vue';

const emit = defineEmits(['close']);

defineProps({
  blurBg: {
    type: Boolean,
    default: false,
  },
  show: {
    type: Boolean,
    default: false,
  },
  maxWidth: {
    type: String,
    default: '2xl',
  },
  closeable: {
    type: Boolean,
    default: true
  },
});

const close = () => {
  emit('close');
};
</script>

<template>
  <Modal
      :blur-bg="blurBg"
      :closeable="closeable"
      :max-width="maxWidth"
      :show="show"
      @close="close"
  >
    <div class="px-6 py-4">
      <div class="text-lg font-medium text-gray-900">
        <slot name="title"/>
      </div>

      <div class="mt-4 text-sm text-gray-600">
        <slot name="content"/>
      </div>
    </div>

    <div v-if="$slots.footer"
         class="flex flex-row justify-end px-6 py-4 space-y-0 bg-gray-100 text-end">
      <slot name="footer"/>
    </div>
  </Modal>
</template>
