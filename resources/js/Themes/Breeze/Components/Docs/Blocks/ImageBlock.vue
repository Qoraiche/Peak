<template>
  <img :alt="caption" :class="imageClasses" :src="imageUrl"/>
</template>

<script>
export default {
  props: {
    imageUrl: String,
    caption: String,
    withBorder: Boolean,
    withBackground: Boolean,
    stretched: Boolean,
  },
  computed: {
    imageClasses() {
      return [
        'rounded-lg',
        this.withBorder ? 'border' : '',
        this.withBackground ? 'bg-gray-100' : '',
        this.stretched ? 'w-full' : 'max-w-full',
      ].join(' ');
    },
  },
};
</script>
