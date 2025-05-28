<script setup>
import {computed} from 'vue';
import {usePage} from '@inertiajs/vue3';
import PrimaryButton from "@/Themes/Breeze/Components/PrimaryButton.vue";
import {__} from '@peak/Composables/useTranslate.js';

const page = usePage();

const isImpersonating = computed(() => page.props.front?.isImpersonating);
const impersonatedUserName = computed(() => page.props.front?.impersonatedUserName);

const stopImpersonation = () => {
  window.location.href = route('dashboard.impersonate.stop');
};

function translateImpersonationMessage() {
  return __(
      "You're now impersonating another user (<strong>:username</strong>)—be careful with any changes you make.",
      {username: impersonatedUserName.value}
  );
}
</script>

<template>
  <div v-if="isImpersonating"
       class="pointer-events-none md:opacity-85 md:hover:opacity-100 z-50 fixed inset-x-0 bottom-0 sm:flex sm:justify-center sm:px-6 sm:pb-5 lg:px-8">
    <div
        class="pointer-events-auto flex items-center justify-between gap-x-3 bg-[#202B46] px-6 py-2.5 sm:rounded-xl sm:py-3 sm:pl-4 sm:pr-3.5">

      <p class="text-sm/6 text-white cursor-default">
        <span
            v-html="translateImpersonationMessage()">
        </span>
      </p>

      <PrimaryButton class="text-xs -m-1.5 flex-none p-1.5" type="button"
                     @click="stopImpersonation">
        <span class="sr-only">
          Dismiss
        </span>
        Stop
      </PrimaryButton>
    </div>
  </div>
</template>
