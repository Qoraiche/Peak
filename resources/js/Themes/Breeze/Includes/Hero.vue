<script setup>

import {Link, router} from '@inertiajs/vue3';

import {useScrollReveal} from '@peak/Composables/useScrollReveal';
import PrimaryButton from "@/Themes/Breeze/Components/PrimaryButton.vue";

useScrollReveal();

defineProps({
  // settings: Object
});

</script>

<template>

  <div class="relative sr-fade-up overflow-x-hidden isolate pt-14 pb-8 min-h-[calc(100vh-5rem)]" data-sr-id="14"
       style="">
    <div class="mx-auto max-w-6xl px-6 lg:flex lg:items-start lg:gap-x-10 lg:px-8">
      <div class="mx-auto max-w-xl lg:mx-0 lg:flex-auto">
        <h1 class="mx-auto max-w-4xl text-slate-900 font-display text-4xl font-extrabold tracking-tight sm:text-6xl">
          We’re changing the way people connect
        </h1>
        <p class="col-start-1 row-start-3 mt-4 max-w-lg text-lg text-slate-600">
          Anim aute id magna aliqua ad ad non deserunt sunt. Qui irure qui lorem cupidatat commodo. Elit sunt amet fugiat veniam occaecat fugiat aliqua. Anim aute id magna aliqua ad ad non deserunt sunt.
        </p>

        <div class="mt-10 flex items-center gap-x-4">
          <PrimaryButton @click="router.visit(route('register'))"
                         :href="route('register')"
                         class="!rounded-full py-2.5 px-6 hover:scale-105 transition-all duration-200">
            {{ __('Register') }}
          </PrimaryButton>

          <Link
              class="rounded-full border border-slate-700 px-6 py-2.5 text-sm font-semibold text-slate-700 shadow-xs shadow-md hover:shadow-lg"
              :href="route('login')">
            {{ __('Login') }}
          </Link>
        </div>
        <div class="mt-10 flex flex-col gap-y-3">
          <div class="flex items-center gap-2 mt-6 relative z-40">
            <a class="flex" href="#testimonials">
              <img v-for="n in 5" :key="n"
                   alt="testimonial 1"
                   class="rounded-full border border-white w-10 h-10 cursor-pointer hover:-translate-y-1 transition"
                   :src="'https://i.pravatar.cc/100?img='+ n +'.jpg'">
            </a>
            <a
                class="flex gap-1 fill-amber-500 w-24 cursor-pointer" href="#testimonials">
              <svg fill="#000000" height="22px" version="1.1" viewBox="0 0 47.94 47.94" width="22px"
                   xml:space="preserve"
                   xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g
                    id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g
                    id="SVGRepo_iconCarrier"><path d="M26.285,2.486l5.407,10.956c0.376,0.762,1.103,1.29,1.944,1.412l12.091,1.757 c2.118,0.308,2.963,2.91,1.431,4.403l-8.749,8.528c-0.608,0.593-0.886,1.448-0.742,2.285l2.065,12.042 c0.362,2.109-1.852,3.717-3.746,2.722l-10.814-5.685c-0.752-0.395-1.651-0.395-2.403,0l-10.814,5.685 c-1.894,0.996-4.108-0.613-3.746-2.722l2.065-12.042c0.144-0.837-0.134-1.692-0.742-2.285l-8.749-8.528 c-1.532-1.494-0.687-4.096,1.431-4.403l12.091-1.757c0.841-0.122,1.568-0.65,1.944-1.412l5.407-10.956 C22.602,0.567,25.338,0.567,26.285,2.486z" style="fill:#ED8A19;"></path></g></svg>
              <svg fill="#000000" height="22px" version="1.1" viewBox="0 0 47.94 47.94" width="22px"
                   xml:space="preserve"
                   xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier"><path d="M26.285,2.486l5.407,10.956c0.376,0.762,1.103,1.29,1.944,1.412l12.091,1.757 c2.118,0.308,2.963,2.91,1.431,4.403l-8.749,8.528c-0.608,0.593-0.886,1.448-0.742,2.285l2.065,12.042 c0.362,2.109-1.852,3.717-3.746,2.722l-10.814-5.685c-0.752-0.395-1.651-0.395-2.403,0l-10.814,5.685 c-1.894,0.996-4.108-0.613-3.746-2.722l2.065-12.042c0.144-0.837-0.134-1.692-0.742-2.285l-8.749-8.528 c-1.532-1.494-0.687-4.096,1.431-4.403l12.091-1.757c0.841-0.122,1.568-0.65,1.944-1.412l5.407-10.956 C22.602,0.567,25.338,0.567,26.285,2.486z" style="fill:#ED8A19;"></path></g></svg>
              <svg fill="#000000" height="22px" version="1.1" viewBox="0 0 47.94 47.94" width="22px"
                   xml:space="preserve"
                   xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier"><path d="M26.285,2.486l5.407,10.956c0.376,0.762,1.103,1.29,1.944,1.412l12.091,1.757 c2.118,0.308,2.963,2.91,1.431,4.403l-8.749,8.528c-0.608,0.593-0.886,1.448-0.742,2.285l2.065,12.042 c0.362,2.109-1.852,3.717-3.746,2.722l-10.814-5.685c-0.752-0.395-1.651-0.395-2.403,0l-10.814,5.685 c-1.894,0.996-4.108-0.613-3.746-2.722l2.065-12.042c0.144-0.837-0.134-1.692-0.742-2.285l-8.749-8.528 c-1.532-1.494-0.687-4.096,1.431-4.403l12.091-1.757c0.841-0.122,1.568-0.65,1.944-1.412l5.407-10.956 C22.602,0.567,25.338,0.567,26.285,2.486z" style="fill:#ED8A19;"></path></g></svg>
              <svg fill="#000000" height="22px" version="1.1" viewBox="0 0 47.94 47.94" width="22px"
                   xml:space="preserve"
                   xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier"><path d="M26.285,2.486l5.407,10.956c0.376,0.762,1.103,1.29,1.944,1.412l12.091,1.757 c2.118,0.308,2.963,2.91,1.431,4.403l-8.749,8.528c-0.608,0.593-0.886,1.448-0.742,2.285l2.065,12.042 c0.362,2.109-1.852,3.717-3.746,2.722l-10.814-5.685c-0.752-0.395-1.651-0.395-2.403,0l-10.814,5.685 c-1.894,0.996-4.108-0.613-3.746-2.722l2.065-12.042c0.144-0.837-0.134-1.692-0.742-2.285l-8.749-8.528 c-1.532-1.494-0.687-4.096,1.431-4.403l12.091-1.757c0.841-0.122,1.568-0.65,1.944-1.412l5.407-10.956 C22.602,0.567,25.338,0.567,26.285,2.486z" style="fill:#ED8A19;"></path></g></svg>
              <svg fill="#000000" height="22px" version="1.1" viewBox="0 0 47.94 47.94" width="22px"
                   xml:space="preserve"
                   xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier"><path d="M26.285,2.486l5.407,10.956c0.376,0.762,1.103,1.29,1.944,1.412l12.091,1.757 c2.118,0.308,2.963,2.91,1.431,4.403l-8.749,8.528c-0.608,0.593-0.886,1.448-0.742,2.285l2.065,12.042 c0.362,2.109-1.852,3.717-3.746,2.722l-10.814-5.685c-0.752-0.395-1.651-0.395-2.403,0l-10.814,5.685 c-1.894,0.996-4.108-0.613-3.746-2.722l2.065-12.042c0.144-0.837-0.134-1.692-0.742-2.285l-8.749-8.528 c-1.532-1.494-0.687-4.096,1.431-4.403l12.091-1.757c0.841-0.122,1.568-0.65,1.944-1.412l5.407-10.956 C22.602,0.567,25.338,0.567,26.285,2.486z" style="fill:#ED8A19;"></path></g></svg>
            </a></div>
          <div class="mt-2 relative z-40 text-sm italic">
            {{ __('Join 1.5k+ happy customers shipping fast with ' + $page.props.app.name) }}
          </div>
        </div>
      </div>

            <div class="hidden lg:block mt-16 sm:mt-28 lg:mt-0 lg:shrink-0 lg:grow relative -top-[10px]">
              <div><img src="https://placehold.co/600x400?text=Hero" alt="hero image"
                        class="absolute max-w-[700px] w-[60vw]"></div>
            </div>
    </div>
  </div>
</template>

<style scoped>

</style>
