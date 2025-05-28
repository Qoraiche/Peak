<script setup>


</script>

<template>
  <div class="relative isolate -z-10 px-8 py-24">
    <div
        class="absolute inset-x-0 top-1/2 -z-10 flex -translate-y-1/2 justify-center overflow-hidden [mask-image:radial-gradient(50%_45%_at_50%_55%,white,transparent)]">
      <svg aria-hidden="true" class="h-[40rem] w-[80rem] flex-none stroke-gray-200">
        <defs>
          <pattern id="e9033f3e-f665-41a6-84ef-756f6778e6fe" height="200" patternTransform="translate(-100 0)" patternUnits="userSpaceOnUse" width="200"
                   x="50%" y="50%">
            <path d="M.5 200V.5H200" fill="none"/>
          </pattern>
        </defs>
        <svg class="overflow-visible fill-gray-50" x="50%" y="50%">
          <path d="M-300 0h201v201h-201Z M300 200h201v201h-201Z" stroke-width="0"/>
        </svg>
        <rect fill="url(#e9033f3e-f665-41a6-84ef-756f6778e6fe)" height="100%" stroke-width="0" width="100%"/>
      </svg>
    </div>
    <div class="mx-auto max-w-6xl px-6 lg:px-8">
      <h2 class="text-center text-lg/8 font-semibold text-gray-900">
        {{ __('Trusted by the world’s most innovative teams') }}</h2>
      <div
          class="mx-auto mt-10 grid max-w-lg grid-cols-4 items-center gap-x-8 gap-y-10 sm:max-w-xl sm:grid-cols-6 sm:gap-x-10 lg:mx-0 lg:max-w-none lg:grid-cols-5">
        <img alt="Transistor"
             class="col-span-2 max-h-12 w-full object-contain lg:col-span-1" height="48"
             src="https://tailwindui.com/plus-assets/img/logos/158x48/transistor-logo-gray-900.svg" width="158">
        <img alt="Reform"
             class="col-span-2 max-h-12 w-full object-contain lg:col-span-1" height="48" src="https://tailwindui.com/plus-assets/img/logos/158x48/reform-logo-gray-900.svg"
             width="158">
        <img alt="Tuple"
             class="col-span-2 max-h-12 w-full object-contain lg:col-span-1" height="48" src="https://tailwindui.com/plus-assets/img/logos/158x48/tuple-logo-gray-900.svg"
             width="158">
        <img alt="SavvyCal"
             class="col-span-2 max-h-12 w-full object-contain sm:col-start-2 lg:col-span-1" height="48"
             src="https://tailwindui.com/plus-assets/img/logos/158x48/savvycal-logo-gray-900.svg" width="158">
        <img alt="Statamic"
             class="col-span-2 col-start-2 max-h-12 w-full object-contain sm:col-start-auto lg:col-span-1" height="48"
             src="https://tailwindui.com/plus-assets/img/logos/158x48/statamic-logo-gray-900.svg" width="158">
      </div>
    </div>
  </div>
</template>

<style>

</style>
