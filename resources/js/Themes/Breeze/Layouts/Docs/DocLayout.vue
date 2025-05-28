<script setup>
import {Link} from '@inertiajs/vue3';
import DocsContent from "@/Themes/Breeze/Layouts/Docs/DocContent.vue";
import DocsSidebar from "@/Themes/Breeze/Layouts/Docs/DocsSidebar.vue";
import DocsMobileSidebar from "@/Themes/Breeze/Layouts/Docs/DocsMobileSidebar.vue";
</script>

<template>
  <div class="flex items-start w-full px-6 mx-auto max-w-7xl md:px-8 docs">

    <DocsSidebar/>

    <div class="flex flex-col items-start w-full">
      <DocsMobileSidebar/>

      <DocsContent>

        <div v-if="$page.props.doc.category?.name" class="text-blue-600 font-semibold">
          {{ $page.props.doc.category.name }}
        </div>

        <h1 :class="{'mt-3': $page.props.doc.category?.name}"
            class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">
          {{ $page.props.doc?.label }}
        </h1>

        <slot/>

        <div class="mt-9 text-slate-500 text-sm">
          Last edit {{ $page.props.doc.updated_at }}
        </div>

        <div class="flex mt-10 justify-between items-center w-full">

          <div>
            <div v-if="$page.props.previousDoc" class="flex leading-6 font-medium">
              <Link :href="$page.props.previousDoc.url"
                    class="flex text-right ml-auto transition-colors duration-200 hover:text-gray-900">
                <span aria-hidden="true" class="ml-2 ltr:hidden">→</span><span
                  aria-hidden="true" class="mr-2 rtl:hidden">&leftarrow;</span>&nbsp;{{ $page.props.previousDoc.title }}
              </Link>
            </div>

          </div>

          <div>
            <div v-if="$page.props.nextDoc" class="flex leading-6 font-medium">
              <Link :href="$page.props.nextDoc.url"
                    class="flex text-right ml-auto transition-colors duration-200 hover:text-gray-900">
                {{ $page.props.nextDoc.title }} <span aria-hidden="true" class="ml-2 rtl:hidden">→</span><span
                  aria-hidden="true" class="mr-2 ltr:hidden">&leftarrow;</span>
              </Link>
            </div>

          </div>
        </div>
      </DocsContent>

    </div>
  </div>
</template>

<style scoped>

</style>
