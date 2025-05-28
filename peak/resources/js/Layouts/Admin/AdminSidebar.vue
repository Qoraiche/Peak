<script setup>

import {Disclosure, DisclosureButton, DisclosurePanel} from '@headlessui/vue'
import DynamicLucideIcon from "@peak/Components/Admin/DynamicLucideIcon.vue";
import {computed, inject, onMounted, ref, watch} from "vue";
import {ChevronRightIcon} from '@heroicons/vue/24/solid';
import {Link, usePage} from "@inertiajs/vue3";

const emitter = inject('emitter');
const page = usePage();

const props = defineProps({
  mobile: {
    type: Boolean,
    default: false
  }
});

const sidebarMinimized = ref(localStorage.getItem('sidebarMinimized') === 'true');

const minimizeSidebar = () => {
  sidebarMinimized.value = !sidebarMinimized.value;
};


watch(sidebarMinimized, (newVal, oldVal) => {
  emitter.emit('sidebarMinimizedActivated', sidebarMinimized.value);
  localStorage.setItem('sidebarMinimized', newVal);
});

const navigation = computed(() => page.props.admin.menus?.sidebar);

function generateId(menuName) {
  return menuName.toLowerCase().replace(/\s+/g, '-');
}

const openSidebarLink = (children, parentName) => {
  if (children?.length > 0 && sidebarMinimized.value) {
    window.location.hash = generateId('#menu-' + parentName);
    minimizeSidebar();
  }
};

onMounted(() => {
  emitter.on('openMobileSidebar', () => {
    if (sidebarMinimized.value) {
      sidebarMinimized.value = false;
    }
  });
});
</script>

<template>
  <div
      id="sidebar"
      :class="{'hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:flex-col':!mobile, 'md:w-[75px]': sidebarMinimized}"
      class="transition-all ease-in-out duration-300 w-[263px] bg-[#202B46]">
    <div
        class="px-6 py-0.5 overflow-hidden flex justify-between items-center h-16 min-h-16 border-b border-gray-600/60 border-dashed">

      <span class="hidden"></span>

      <Link v-if="page.props.admin?.logo_path" :href="route('admin.dashboard')"
            class="flex h-16 shrink-0 items-center">
        <img v-show="!sidebarMinimized" id="admin_sidebar_logo_img"
             :alt="page.props.app.name"
             :src="page.props.admin?.logo_path" class="h-12 w-auto max-w-[95px]"/>
      </Link>

      <Link v-if="!page.props.admin?.logo_path && !sidebarMinimized" id="admin_sidebar_logo_app_name"
            :href="route('admin.dashboard')"
            class="truncate flex h-16 shrink-0 items-center text-sm hover:text-white text-[#8d98af]">
        {{ page.props.app.name }}
      </Link>

      <!-- close sidebar Button -->
      <button class="hidden md:block bg-white/10 hover:bg-white/20 p-[5px] rounded-md" @click="minimizeSidebar">
        <svg :class="[sidebarMinimized ? 'ltr:rotate-180 rtl:rotate-0' : 'rtl:rotate-180']"
             class="w-[18px] transition-all duration-700" fill="none" viewBox="0 0 24 24"
             xmlns="http://www.w3.org/2000/svg">
          <path class="stroke-gray-400"
                d="M19 19L12.7071 12.7071C12.3166 12.3166 12.3166 11.6834 12.7071 11.2929L19 5"
                stroke-linecap="round" stroke-linejoin="round" stroke-width="1"/>
          <path class="stroke-gray-200"
                d="M11 19L4.70711 12.7071C4.31658 12.3166 4.31658 11.6834 4.70711 11.2929L11 5"
                stroke="#ffffff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
        </svg>
      </button>
    </div>

    <perfect-scrollbar>
      <!-- Sidebar component, swap this element with another sidebar if you like -->
      <div
          class="max-h-[calc(100vh-80px)] md:max-h-none flex grow flex-col gap-y-5 overflow-y-auto px-6 py-3 pb-4 mt-3 overflow-x-hidden">
        <nav class="flex flex-1 flex-col">
          <ul class="flex flex-1 flex-col gap-y-7" role="list">
            <li>
              <ul :class="{'space-y-3' : sidebarMinimized}" class="-mx-2 space-y-1" role="list">
                <li v-for="item in navigation"
                    :id="'menu-' + item.slug"
                    :key="item.slug" v-tooltip.right="sidebarMinimized ? item.title : null"
                    @click="openSidebarLink(item.children, item.title)">
                  <Link v-if="!item.children"
                        :class="[item.active ? 'text-white' : 'text-[#8d98af]', 'hover:text-white group flex items-center gap-x-3 rounded-md p-2 text-sm font-medium leading-6']"
                        :href="route(item.route) + '#menu-' + item.slug"
                        preserve-state>

                    <DynamicLucideIcon v-if="item.icon" :class="{'w-[1.5rem]': sidebarMinimized}" :icon="item.icon"
                                       size="size-4"/>

                    <span v-show="!sidebarMinimized"
                          :class="!sidebarMinimized ? 'opacity-100' : 'opacity-0'"
                          class="transition-opacity duration-500">{{ __(item.title) }}</span>
                  </Link>
                  <Disclosure v-else v-slot="{ open }" :default-open="item.active" as="div">
                    <DisclosureButton
                        :class="[item.active ? 'text-white' : 'text-[#8d98af]', 'hover:text-white flex w-full items-center gap-x-3 rounded-md p-2 text-sm font-medium leading-6']">

                      <DynamicLucideIcon v-if="item.icon" :class="{'w-[1.5rem]': sidebarMinimized}" :icon="item.icon"
                                         size="size-4"/>

                      <span v-show="!sidebarMinimized">{{ __(item.title) }}</span>

                      <ChevronRightIcon v-show="!sidebarMinimized"
                                        :class="[open ? 'ltr:rotate-90 rtl:rotate-90 text-white' : 'rtl:rotate-180 text-[#8d98af]', 'transition-transform duration-500 ltr:ml-auto rtl:mr-auto h-4 w-4 shrink-0']"
                                        aria-hidden="true"/>
                    </DisclosureButton>

                    <!-- Add transition for animation -->
                    <!-- Auto height animation for smooth open/close -->
                    <DisclosurePanel v-show="!sidebarMinimized" as="ul" class="mt-1 px-2 overflow-hidden"
                                     style="margin: 0 -16px;background: #1c263e;padding: 10px 0;">
                      <li v-for="subItem in item.children" :key="subItem.slug">
                        <Link :href="route(subItem.route)  + '#menu-' +  item.slug" preserve-state>
                          <span :class="[subItem.active ? 'text-white' : 'text-[#8d98af]', 'hover:text-white block rounded-md py-1.5 w-full ltr:pl-9 rtl:pr-9 pr-2 text-sm leading-6 text-[#8d98af]']">
                            {{ __(subItem.title) }}
                          </span>
                        </Link>
                      </li>
                    </DisclosurePanel>
                  </Disclosure>
                </li>
              </ul>
            </li>
          </ul>
        </nav>
      </div>
    </perfect-scrollbar>
  </div>
</template>

<style scoped>
#sidebar {
  box-shadow: 7px 0 60px rgba(0, 0, 0, .05);
}
</style>
