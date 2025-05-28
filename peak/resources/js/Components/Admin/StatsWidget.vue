<script setup>

import {Deferred} from "@inertiajs/vue3";

const props = defineProps({
  bordered: {
    type: Boolean,
    default: true,
  },
  deferred: {
    type: Boolean,
    default: false
  },
  deferredData: {
    type: [String, Array],
    default: []
  }
});
</script>

<template>
  <section :class="{'border' : bordered}" class="rounded-xl bg-white dark:bg-gray-900 w-full">
    <div class="p-6">
      <div class="flex gap-x-5 items-center">
        <div v-if="$slots.icon" class="flex">
          <slot name="icon"/>
        </div>

        <div v-if="$slots.data" class="w-full truncate">
          <div class="flex flex-col">
            <div class="mt-1 text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-200">
              <Deferred v-if="deferred" :data="deferredData">
                <template #fallback>
                  <div class="space-y-2 w-full">
                    <div v-for="n in 2"
                         :key="n"
                         class="animate-pulse md:space-y-0 md:gap-x-8 rtl:gap-x-reverse md:flex md:articles-center"
                         role="status">
                      <div class="w-full">
                        <div
                            class="h-2.5 bg-zinc-200/70 rounded-full dark:bg-zinc-700 w-24"></div>
                      </div>
                      <span class="sr-only">Loading...</span>
                    </div>
                  </div>
                </template>

                <slot name="data"/>
              </Deferred>

              <slot v-else name="data"/>
            </div>
          </div>
        </div>
      </div>

      <div v-if="$slots.label" class="mt-2 text-xs font-medium text-gray-500 truncate">
        <slot name="label"/>
      </div>
    </div>
  </section>
</template>

<style scoped>

</style>
