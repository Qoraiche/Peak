<script setup>

import {ref} from "vue";
import {ChevronDown, ChevronUp} from 'lucide-vue-next';

import {Deferred} from '@inertiajs/vue3'

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
    default: false
  },
  hasContentPadding: {
    type: Boolean,
    default: true,
  },
  contentPadding: {
    type: String,
    default: null,
  },
  deferred: {
    type: Boolean,
    default: false
  },
  deferredData: {
    type: [Array, Object, String],
    default: null
  }
})

const collapsed = ref(true);

const collapse = (val) => {
  collapsed.value = val;
}
</script>

<template>
  <section>
    <div :class="{'border border-red-600' : hasError, 'shadow-sm': hasShadow, 'border': bordered}"
         class="bg-white sm:rounded-lg">
      <div v-if="$slots.header" class="px-4 py-3.5 sm:px-6">
        <div class="flex justify-between items-center">
          <div>
            <h2 id="user-infos"
                class="text-base font-medium leading-6 rtl:text-right text-gray-900">
              <slot name="header"/>
            </h2>
            <p v-if="$slots.description" class="mt-1 max-w-2xl rtl:text-right text-xs text-muted-foreground">
              <slot name="description"/>
            </p>
          </div>

          <div class="flex gap-x-2 items-center">
            <div v-if="$slots.actions" class="flex gap-x-2 items-center">
              <slot name="actions"/>
            </div>

            <button
                v-if="collapsible"
                class="rounded-full size-7 bg-zinc-50 flex items-center justify-center text-gray-500 hover:bg-gray-100 ring-blue-600 focus:ring-blue-600">
              <ChevronDown v-show="collapsed" class="size-4" @click="collapse(false)"/>
              <ChevronUp v-show="!collapsed" class="size-4" @click="collapse(true)"/>
            </button>
          </div>
        </div>
      </div>

      <div
          v-show="collapsed"
          :class="[hasContentPadding ? 'px-4 py-5 sm:px-6': contentPadding, bordered && $slots.header ? 'border-t border-gray-200' : null]">

        <Deferred v-if="deferred" :data="deferredData">
          <template #fallback>
            <div class="px-4 py-5 sm:px-6 space-y-2 w-full">
              <div v-for="(n, index) in 3"
                   :key="n" class="animate-pulse md:space-y-0 md:gap-x-8 rtl:gap-x-reverse md:flex md:articles-center"
                   role="status">
                <div class="w-full">
                  <div
                      :class="{'w-80': index === 0, 'w-48' : index === 1, 'w-28': index === 2}"
                      class="h-2.5 bg-zinc-200/70 rounded-full dark:bg-zinc-700"></div>
                </div>

                <span class="sr-only">Loading...</span>
              </div>
            </div>
          </template>

          <slot/>
        </Deferred>

        <slot v-else/>
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
