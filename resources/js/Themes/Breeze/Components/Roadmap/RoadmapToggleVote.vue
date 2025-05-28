<script setup>
import {router, usePage} from "@inertiajs/vue3";
import {ref} from "vue";
import {useToast} from "vue-toastification";
import {__} from '@peak/Composables/useTranslate.js';

const page = usePage();

const props = defineProps({
  roadmap: Object | null
});

const totalUpVoters = ref(props.roadmap.upvoters_count);
const toast = useToast();
const loading = ref(false);
const toggleVote = () => {

  if (!page.props.auth?.user) {
    router.visit(route('login'));
    return;
  }

  loading.value = true;

  axios.put(route('admin.roadmaps.upvote.toggle', props.roadmap.id), {})
      .then(function (response) {
        totalUpVoters.value = response.data.upvoters;

        toast.success(__('Thanks for voting'));
      })
      .catch(function (error) {
        toast.error(__('Error voting'));
      }).finally(function () {
    loading.value = false;
  });

};
</script>

<template>
  <button
      class="text-gray-500 border border-neutral-200 h-16 flex flex-col text-center justify-center items-center px-6 py-2 group rounded-lg hover:border-black transition"
      @click="toggleVote">
                        <span class="text-gray-500 flex flex-col text-center justify-center items-center">
                            <span v-if="!loading" class="group-hover:scale-125 transition-all duration-200">
                                <svg class="h-4" color="default" fill="none" height="16px" viewBox="0 0 24 24"
                                     width="16px"
                                     xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g
                                    id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g
                                    id="SVGRepo_iconCarrier">
                                    <path clip-rule="evenodd"
                                          d="M12 7C12.2652 7 12.5196 7.10536 12.7071 7.29289L19.7071 14.2929C20.0976 14.6834 20.0976 15.3166 19.7071 15.7071C19.3166 16.0976 18.6834 16.0976 18.2929 15.7071L12 9.41421L5.70711 15.7071C5.31658 16.0976 4.68342 16.0976 4.29289 15.7071C3.90237 15.3166 3.90237 14.6834 4.29289 14.2929L11.2929 7.29289C11.4804 7.10536 11.7348 7 12 7Z"
                                          fill="#000000"
                                          fill-rule="evenodd"></path> </g>
                                </svg>
                            </span>

                            <span v-if="!loading" class="text-sm font-semibold">
                                {{ totalUpVoters }}
                            </span>

                            <svg v-if="loading" class="size-4 animate-spin text-zinc-400"
                                 fill="none"
                                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor"
                                        stroke-width="4"></circle><path
                                class="opacity-75"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                fill="currentColor"></path></svg>
                        </span>
  </button>
</template>

<style scoped>

</style>
