<template>
  <component :is="listTag">
    <li v-for="(item, index) in items" :key="index">{{ item.content }}</li>
  </component>
</template>

<script>
export default {
  props: {
    style: String,
    items: Array,
  },
  computed: {
    listTag() {
      return this.style === 'ordered' ? 'ol' : 'ul';
    },
  },
};
</script>