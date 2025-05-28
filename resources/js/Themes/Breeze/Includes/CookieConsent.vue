<script setup>

import {usePage} from "@inertiajs/vue3";
import {onMounted, ref} from "vue";
import {useCookies} from "vue3-cookies";

const page = usePage();
const {cookies} = useCookies();

const front = page.props.front;

const cta_button_text_color = front?.cta_button_text_color;
const cta_button_background_color = front?.cta_button_background_color;

const consentMessageText = front.cookie_consent?.message;
const consentAgreeText = front.cookie_consent?.agree;
const consentEnabled = front.cookie_consent?.enabled;

const cookieConsentConfig = front.cookie_consent?.cookieConsentConfig;
const sessionDomain = front.cookie_consent?.sessionDomain;
/*const sessionSecure = page.props.cookie_consent?.sessionSecure;
const sessionSameSite = page.props.cookie_consent?.sessionSameSite;*/

const COOKIE_VALUE = 1;
const COOKIE_DOMAIN = sessionDomain;

const cookieDialogHidden = ref(false);

const consentWithCookies = () => {
  setCookie(cookieConsentConfig['cookie_name'], COOKIE_VALUE, cookieConsentConfig['cookie_lifetime']);
  cookieDialogHidden.value = true;
};

const setCookie = (name, value, expirationInDays) => {
  const date = new Date();
  date.setTime(date.getTime() + (expirationInDays * 24 * 60 * 60 * 1000));
  cookies.set(name, value, date.toUTCString(), null, COOKIE_DOMAIN, false);
}

const cookieExists = (name) => {
  return (document.cookie.split('; ').indexOf(name + '=' + COOKIE_VALUE) !== -1);
}

onMounted(() => {
  if (cookieExists(cookieConsentConfig['cookie_name'])) {
    cookieDialogHidden.value = true;
  }
});

</script>

<template>
  <div v-show="consentEnabled && !cookieDialogHidden"
       class="js-cookie-consent cookie-consent fixed bottom-0 inset-x-0 pb-2 z-20">
    <div class="max-w-7xl mx-auto px-6">
      <div class="p-2 rounded-lg bg-yellow-100">
        <div class="flex items-center justify-between flex-wrap">
          <div class="w-0 flex-1 items-center hidden md:inline">
            <p class="ml-3 text-black cookie-consent__message text-sm">
              {{ consentMessageText }}
            </p>
          </div>
          <div class="mt-2 shrink-0 w-full sm:mt-0 sm:w-auto">
            <button
                :style="{color: front.cta_button_text_color, backgroundColor: front.cta_button_background_color}"
                class="js-cookie-consent-agree cookie-consent__agree cursor-pointer flex items-center justify-center px-4 py-2 rounded-md text-sm font-medium hover:opacity-80"
                @click="consentWithCookies">
              {{ consentAgreeText }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>

<style scoped>

</style>
