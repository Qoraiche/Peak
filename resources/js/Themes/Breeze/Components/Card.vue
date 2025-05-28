<script setup>

import {ref} from "vue";
import {ChevronDownIcon, ChevronUpIcon} from "@heroicons/vue/24/outline/index.js";

const props = defineProps({
  hasError: false,
  collapsible: {
    type: Boolean,
    default: true,
  },
  bordered: {
    type: Boolean,
    default: true,
  },
  footerPosition: {
    type: String,
    default: 'end',
  },
  hasShadow: {
    type: Boolean,
    default: true
  },
  hasContentPadding: {
    type: Boolean,
    default: true,
  },
  contentPadding: {
    type: String,
    default: null,
  }
})

const collapsed = ref(true);

const collapse = (val) => {
  collapsed.value = val;
}
</script>

<template>
  <section>
    <div :class="{'border border-red-600' : hasError, 'shadow-xs': hasShadow, 'border': bordered}"
         class="bg-zinc-50">
      <div v-if="$slots.header" class="px-4 py-3.5 sm:px-6">
        <div class="flex justify-between items-center">
          <div>
            <h2 id="user-infos"
                class="text-base font-medium leading-6 text-gray-900">
              <slot name="header"/>
            </h2>
            <p v-if="$slots.description" class="mt-1 max-w-2xl text-sm text-gray-500">
              <slot name="description"/>
            </p>
          </div>

          <button
              v-if="collapsible"
              class="rounded-full p-2 bg-white border border-zinc-200 flex items-center justify-center text-gray-500 hover:bg-gray-100 ring-blue-600 focus:ring-blue-600">
            <ChevronDownIcon v-show="collapsed" class="w-4" @click="collapse(false)"/>
            <ChevronUpIcon v-show="!collapsed" class="w-4" @click="collapse(true)"/>
          </button>
        </div>
      </div>
      <div
          v-show="collapsed"
          :class="[hasContentPadding ? 'px-4 py-5 sm:px-6': contentPadding, bordered && $slots.header ? 'border-t border-gray-200' : null]">
        <slot/>
      </div>

      <div v-if="$slots.footer"
           v-show="collapsed"
           :class="{'justify-end' : footerPosition === 'end', 'justify-start': footerPosition === 'start', 'justify-center': footerPosition === 'center'}"
           class="border-t border-gray-200 px-4 py-5 sm:px-6 flex">
        <slot name="footer"></slot>
      </div>
    </div>
  </section>
</template>

<style scoped>

</style>
