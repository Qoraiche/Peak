<script setup>
import {Link} from "@inertiajs/vue3";
import {__} from "@peak/Composables/useTranslate.js";
import {ChevronRightIcon} from '@heroicons/vue/20/solid';
import {Rss, LayoutDashboard} from "lucide-vue-next";
import {Disclosure, DisclosureButton, DisclosurePanel} from '@headlessui/vue';
import {inject, onMounted, ref} from "vue";
import {useFeature} from "@peak/Composables/useFeature.js";


const route = inject('route');

const {featureEnabled, featureDisabled} = useFeature();

const navigation = ref([
  {
    name: __('Dashboard'),
    route: route('dashboard.index'),
    icon: LayoutDashboard,
    current: route().current('dashboard.index'),
  }
]);

onMounted(() => {

});

</script>

<template>
  <nav class="mt-6 px-3 flex flex-1 flex-col gap-y-7">
    <div class="space-y-1">
      <ul class="flex flex-1 flex-col gap-y-7" role="list">
        <li>
          <ul class="space-y-1.5" role="list">
            <li v-for="item in navigation" :key="item.name">
              <Link v-if="!item.children" :aria-current="item.current ? 'page' : undefined"
                    :class="[item.current ? 'text-zinc-900 border-zinc-200 dark:border-zinc-700 shadow-xs bg-white font-medium dark:bg-zinc-700/60 dark:text-zinc-100 transition-colors border px-2.5 py-2 flex rounded-lg w-full h-auto text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700/60 justify-start items-center hover:text-zinc-900 dark:hover:text-zinc-100 gap-x-2 overflow-hidden group-hover:autoflow-auto items' : 'border-transparent transition-colors border px-2.5 py-2 flex rounded-lg w-full h-auto text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700/60 justify-start items-center hover:text-zinc-900 dark:hover:text-zinc-100 gap-x-2 overflow-hidden group-hover:autoflow-auto items']"
                    :href="item.route">
                <component :is="item.icon"
                           :class="[item.current ? 'text-gray-500' : 'text-gray-600 group-hover:text-gray-500', 'ltr:mr-3 rtl:ml-3 size-5 shrink-0']"
                           aria-hidden="true"/>
                {{ item.name }}
              </Link>

              <Disclosure v-else v-slot="{ open }" :default-open="item.current"
                          as="div">
                <DisclosureButton
                    :class="[item.current ? 'text-zinc-900 border-zinc-200 shadow-xs bg-white font-medium transition-colors border px-2.5 py-2 flex rounded-lg w-full h-auto text-sm hover:bg-zinc-100 justify-start items-center hover:text-zinc-900 dark:hover:text-zinc-100 gap-x-2 overflow-hidden group-hover:autoflow-auto items' : 'border-transparent transition-colors border px-2.5 py-2 flex rounded-lg w-full h-auto text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700/60 justify-start items-center hover:text-zinc-900 dark:hover:text-zinc-100 gap-x-2 overflow-hidden group-hover:autoflow-auto items']">
                  <component :is="item.icon"
                             aria-hidden="true"
                             class="ltr:mr-3 rtl:ml-3 size-5 shrink-0 text-gray-600"/>
                  {{ item.name }}
                  <ChevronRightIcon
                      :class="[open ? 'rotate-90 text-gray-500' : 'rtl:rotate-180 text-gray-600', 'ltr:ml-auto rtl:mr-auto size-4 shrink-0']"
                      aria-hidden="true"/>
                  <!--                                                                <ChevronLeftIcon :class="[open ? 'rotate-90 text-gray-500' : 'text-gray-600', 'ml-auto size-5 shrink-0']" aria-hidden="true" />-->
                </DisclosureButton>
                <DisclosurePanel as="ul" class="mt-1.5 bg-white">
                  <li v-for="subItem in item.children"
                      :key="subItem.name">
                    <Link
                        :class="[subItem.current ? 'font-semibold' : 'hover:text-black', 'block rounded-md py-2 pl-9 pr-2 text-sm/6 text-gray-700']"
                        :href="subItem.route">
                      {{ subItem.name }}
                    </Link>
                  </li>
                </DisclosurePanel>
              </Disclosure>
            </li>

          </ul>

        </li>

      </ul>
    </div>

    <div class="mt-auto relative space-y-1.5 text-zinc-700 dark:text-zinc-400">

<!--      <Link v-if="featureEnabled('blog')" :href="route('blog.index')"
            class="border-transparent transition-colors border px-2.5 py-2 flex rounded-lg w-full h-auto text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700/60 justify-start items-center hover:text-zinc-900 dark:hover:text-zinc-100 gap-2 overflow-hidden group-hover:autoflow-auto items">

        <Rss class="w-5 h-5 shrink-0"/>

        <span class="shrink-0 ease-out duration-50">
                {{ __('Blog') }}
        </span>
      </Link>-->

    </div>
  </nav>
</template>

<style scoped>

</style>