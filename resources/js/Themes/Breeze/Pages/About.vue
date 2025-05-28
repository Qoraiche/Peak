<script setup>

import PageLayout from "@/Themes/Breeze/Layouts/Main/PageLayout.vue";

const props = defineProps({});

</script>

<template>
  <PageLayout :title="__('About')">
    <main class="isolate">
      <!-- Hero section -->
      <div class="relative isolate -z-10">
        <svg
            aria-hidden="true"
            class="absolute inset-x-0 top-0 -z-10 h-[64rem] w-full stroke-gray-200 [mask-image:radial-gradient(32rem_32rem_at_center,white,transparent)]">
          <defs>
            <pattern id="1f932ae7-37de-4c0a-a8b0-a6e3b4d44b84" height="200" patternUnits="userSpaceOnUse"
                     width="200" x="50%"
                     y="-1">
              <path d="M.5 200V.5H200" fill="none"/>
            </pattern>
          </defs>
          <svg class="overflow-visible fill-gray-50" x="50%" y="-1">
            <path
                d="M-200 0h201v201h-201Z M600 0h201v201h-201Z M-400 600h201v201h-201Z M200 800h201v201h-201Z"
                stroke-width="0"/>
          </svg>
          <rect fill="url(#1f932ae7-37de-4c0a-a8b0-a6e3b4d44b84)" height="100%" stroke-width="0"
                width="100%"/>
        </svg>
        <div
            aria-hidden="true"
            class="absolute left-1/2 right-0 top-0 -z-10 -ml-24 transform-gpu overflow-hidden blur-3xl lg:ml-24 xl:ml-48">
          <div
              class="aspect-801/1036 w-[50.0625rem] bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-30"
              style="clip-path: polygon(63.1% 29.5%, 100% 17.1%, 76.6% 3%, 48.4% 0%, 44.6% 4.7%, 54.5% 25.3%, 59.8% 49%, 55.2% 57.8%, 44.4% 57.2%, 27.8% 47.9%, 35.1% 81.5%, 0% 97.7%, 39.2% 100%, 35.2% 81.4%, 97.2% 52.8%, 63.1% 29.5%)"></div>
        </div>
        <div class="overflow-hidden">
          <div class="mx-auto max-w-7xl px-6 pb-32 pt-36 sm:pt-60 lg:px-8 lg:pt-32">
            <div class="mx-auto max-w-2xl gap-x-14 lg:mx-0 lg:flex lg:max-w-none lg:items-center">
              <div class="relative w-full lg:max-w-xl lg:shrink-0 xl:max-w-2xl">
                <h1 class="text-pretty text-5xl font-semibold tracking-tight text-gray-900 sm:text-7xl">
                  We’re changing the way people connect</h1>
                <p class="mt-8 text-pretty text-lg font-medium text-gray-500 sm:max-w-md sm:text-xl/8 lg:max-w-none">
                  Cupidatat minim id magna ipsum sint dolor qui. Sunt sit in quis cupidatat mollit
                  aute velit. Et labore commodo nulla aliqua proident mollit ullamco exercitation
                  tempor. Sint aliqua anim nulla sunt mollit id pariatur in voluptate cillum. Eu
                  voluptate tempor esse minim amet fugiat veniam occaecat aliqua.</p>
              </div>
              <div
                  class="mt-14 flex justify-end gap-8 sm:-mt-44 sm:justify-start sm:pl-20 lg:mt-0 lg:pl-0">
                <div
                    class="ml-auto w-44 flex-none space-y-8 pt-32 sm:ml-0 sm:pt-80 lg:order-last lg:pt-36 xl:order-none xl:pt-80">
                  <div class="relative">
                    <img
                        alt=""
                        class="aspect-2/3 w-full rounded-xl bg-gray-900/5 object-cover shadow-lg"
                        src="https://images.unsplash.com/photo-1557804506-669a67965ba0?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&h=528&q=80">
                    <div
                        class="pointer-events-none absolute inset-0 rounded-xl ring-1 ring-inset ring-gray-900/10"></div>
                  </div>
                </div>
                <div class="mr-auto w-44 flex-none space-y-8 sm:mr-0 sm:pt-52 lg:pt-36">
                  <div class="relative">
                    <img
                        alt=""
                        class="aspect-2/3 w-full rounded-xl bg-gray-900/5 object-cover shadow-lg"
                        src="https://images.unsplash.com/photo-1485217988980-11786ced9454?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&h=528&q=80">
                    <div
                        class="pointer-events-none absolute inset-0 rounded-xl ring-1 ring-inset ring-gray-900/10"></div>
                  </div>
                  <div class="relative">
                    <img
                        alt=""
                        class="aspect-2/3 w-full rounded-xl bg-gray-900/5 object-cover shadow-lg"
                        src="https://images.unsplash.com/photo-1559136555-9303baea8ebd?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&crop=focalpoint&fp-x=.4&w=396&h=528&q=80">
                    <div
                        class="pointer-events-none absolute inset-0 rounded-xl ring-1 ring-inset ring-gray-900/10"></div>
                  </div>
                </div>
                <div class="w-44 flex-none space-y-8 pt-32 sm:pt-0">
                  <div class="relative">
                    <img
                        alt=""
                        class="aspect-2/3 w-full rounded-xl bg-gray-900/5 object-cover shadow-lg"
                        src="https://images.unsplash.com/photo-1670272504528-790c24957dda?ixlib=rb-4.0.3&ixid=MnwxMjA3fDF8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&crop=left&w=400&h=528&q=80">
                    <div
                        class="pointer-events-none absolute inset-0 rounded-xl ring-1 ring-inset ring-gray-900/10"></div>
                  </div>
                  <div class="relative">
                    <img
                        alt=""
                        class="aspect-2/3 w-full rounded-xl bg-gray-900/5 object-cover shadow-lg"
                        src="https://images.unsplash.com/photo-1670272505284-8faba1c31f7d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDF8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&h=528&q=80">
                    <div
                        class="pointer-events-none absolute inset-0 rounded-xl ring-1 ring-inset ring-gray-900/10"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Content section -->
      <div class="mx-auto -mt-12 max-w-7xl px-6 sm:mt-0 lg:px-8 xl:-mt-8">
        <div class="mx-auto max-w-2xl lg:mx-0 lg:max-w-none">
          <h2 class="text-pretty text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl">Our
            mission</h2>
          <div class="mt-6 flex flex-col gap-x-8 gap-y-20 lg:flex-row">
            <div class="lg:w-full lg:max-w-2xl lg:flex-auto">
              <p class="text-xl/8 text-gray-600">Aliquet nec orci mattis amet quisque ullamcorper neque,
                nibh sem. At arcu, sit dui mi, nibh dui, diam eget aliquam. Quisque id at vitae feugiat
                egestas ac. Diam nulla orci at in viverra scelerisque eget. Eleifend egestas fringilla
                sapien.</p>
              <p class="mt-10 max-w-xl text-base/7 text-gray-700">Faucibus commodo massa rhoncus,
                volutpat. Dignissim sed eget risus enim. Mattis mauris semper sed amet vitae sed turpis
                id. Id dolor praesent donec est. Odio penatibus risus viverra tellus varius sit neque
                erat velit. Faucibus commodo massa rhoncus, volutpat. Dignissim sed eget risus enim.
                Mattis mauris semper sed amet vitae sed turpis id.</p>
            </div>
            <div class="lg:flex lg:flex-auto lg:justify-center">
              <dl class="w-64 space-y-8 xl:w-80">
                <div class="flex flex-col-reverse gap-y-4">
                  <dt class="text-base/7 text-gray-600">Transactions every 24 hours</dt>
                  <dd class="text-5xl font-semibold tracking-tight text-gray-900">44 million</dd>
                </div>
                <div class="flex flex-col-reverse gap-y-4">
                  <dt class="text-base/7 text-gray-600">Assets under holding</dt>
                  <dd class="text-5xl font-semibold tracking-tight text-gray-900">$119 trillion</dd>
                </div>
                <div class="flex flex-col-reverse gap-y-4">
                  <dt class="text-base/7 text-gray-600">New users annually</dt>
                  <dd class="text-5xl font-semibold tracking-tight text-gray-900">46,000</dd>
                </div>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <!-- Image section -->
      <div class="mt-32 sm:mt-40 xl:mx-auto xl:max-w-7xl xl:px-8">
        <img
            alt=""
            class="aspect-5/2 w-full object-cover xl:rounded-3xl"
            src="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2832&q=80">
      </div>

      <!-- Values section -->
      <div class="mx-auto mt-32 max-w-7xl px-6 sm:mt-40 lg:px-8">
        <div class="mx-auto max-w-2xl lg:mx-0">
          <h2 class="text-pretty text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl">Our
            values</h2>
          <p class="mt-6 text-lg/8 text-gray-600">Lorem ipsum dolor sit amet consect adipisicing elit.
            Possimus magnam voluptatum cupiditate veritatis in accusamus quisquam.</p>
        </div>
        <dl class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 text-base/7 sm:grid-cols-2 lg:mx-0 lg:max-w-none lg:grid-cols-3">
          <div>
            <dt class="font-semibold text-gray-900">Be world-class</dt>
            <dd class="mt-1 text-gray-600">Aut illo quae. Ut et harum ea animi natus. Culpa maiores et sed
              sint et magnam exercitationem quia. Ullam voluptas nihil vitae dicta molestiae et. Aliquid
              velit porro vero.
            </dd>
          </div>
          <div>
            <dt class="font-semibold text-gray-900">Share everything you know</dt>
            <dd class="mt-1 text-gray-600">Mollitia delectus a omnis. Quae velit aliquid. Qui nulla maxime
              adipisci illo id molestiae. Cumque cum ut minus rerum architecto magnam consequatur. Quia
              quaerat minima.
            </dd>
          </div>
          <div>
            <dt class="font-semibold text-gray-900">Always learning</dt>
            <dd class="mt-1 text-gray-600">Aut repellendus et officiis dolor possimus. Deserunt velit quasi
              sunt fuga error labore quia ipsum. Commodi autem voluptatem nam. Quos voluptatem totam.
            </dd>
          </div>
          <div>
            <dt class="font-semibold text-gray-900">Be supportive</dt>
            <dd class="mt-1 text-gray-600">Magnam provident veritatis odit. Vitae eligendi repellat non. Eum
              fugit impedit veritatis ducimus. Non qui aspernatur laudantium modi. Praesentium rerum error
              deserunt harum.
            </dd>
          </div>
          <div>
            <dt class="font-semibold text-gray-900">Take responsibility</dt>
            <dd class="mt-1 text-gray-600">Sit minus expedita quam in ullam molestiae dignissimos in harum.
              Tenetur dolorem iure. Non nesciunt dolorem veniam necessitatibus laboriosam voluptas
              perspiciatis error.
            </dd>
          </div>
          <div>
            <dt class="font-semibold text-gray-900">Enjoy downtime</dt>
            <dd class="mt-1 text-gray-600">Ipsa in earum deserunt aut. Quos minus aut animi et soluta. Ipsum
              dicta ut quia eius. Possimus reprehenderit iste aspernatur ut est velit consequatur
              distinctio.
            </dd>
          </div>
        </dl>
      </div>

      <!-- Team section -->
      <div class="mx-auto mt-32 max-w-7xl px-6 sm:mt-48 lg:px-8">
        <div class="mx-auto max-w-2xl lg:mx-0">
          <h2 class="text-pretty text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl">Our
            team</h2>
          <p class="mt-6 text-lg/8 text-gray-600">We’re a dynamic group of individuals who are passionate
            about what we do and dedicated to delivering the best results for our clients.</p>
        </div>
        <ul class="mx-auto mt-20 grid max-w-2xl grid-cols-2 gap-x-8 gap-y-16 text-center sm:grid-cols-3 md:grid-cols-4 lg:mx-0 lg:max-w-none lg:grid-cols-5 xl:grid-cols-6"
            role="list">
          <li>
            <img alt=""
                 class="mx-auto size-24 rounded-full"
                 src="https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=8&w=1024&h=1024&q=80">
            <h3 class="mt-6 text-base/7 font-semibold tracking-tight text-gray-900">Michael Foster</h3>
            <p class="text-sm/6 text-gray-600">Co-Founder / CTO</p>
          </li>

          <!-- More people... -->
        </ul>
      </div>

    </main>
  </PageLayout>
</template>

<style scoped>

</style>
