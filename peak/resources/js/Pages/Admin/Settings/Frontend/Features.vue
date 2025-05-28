<script setup>
import AddNewFeature from '@peak/Modals/Admin/Settings/Frontend/Features/AddNewFeature.vue';
import EditFeature from '@peak/Modals/Admin/Settings/Frontend/Features/EditFeature.vue';
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import {PencilIcon, PencilSquareIcon, TrashIcon} from "@heroicons/vue/24/outline/index.js";
import {router, usePage} from "@inertiajs/vue3";
import {inject} from "vue";
import {useToast} from "vue-toastification";
import Card from "@peak/Components/Admin/Card.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import {CircleChevronDown, CircleChevronUp} from "lucide-vue-next";
import {__} from "@peak/Composables/useTranslate.js";

const props = defineProps({
  errors: Object,
  features: Object
});

const page = usePage();
const toast = useToast();
const dayJS = inject('dayJS');
const emitter = inject('emitter');

const addNewFeatureAction = () => {
  emitter.emit('feature:add-new');
}

const editFeatureAction = (feature) => {
  emitter.emit('feature:edit', feature);
}

const deleteFeature = (feature) => {
  window.swal.fire({
    title: __('Are you sure you want to delete this feature?'),
    showCancelButton: true,
    confirmButtonText: __('Delete'),
    confirmButtonColor: 'tomato',
  }).then((result) => {
    if (result.isConfirmed) {
      router.delete(route('admin.settings.frontend.features.delete', {
        feature: feature.id,
      }), {
        onSuccess: () => {
          toast.success(__('Feature deleted'));
        },
        onError: () => {
          toast.error(__('An error occurred while deleting the feature.'));
        },
        preserveScroll: true,
      });
    }
  });
};


const moveUp = (item) => {
  router.patch(route('admin.settings.frontend.features.move-up', item.id), {}, {
    preserveState: false,
    preserveScroll: true,
    onSuccess: () => {
      toast.success(__('Changes Saved'));
    }
  });
};

const moveDown = (item) => {
  router.patch(route('admin.settings.frontend.features.move-down', item.id), {}, {
    preserveState: false,
    preserveScroll: true,
    onSuccess: () => {
      toast.success(__('Changes Saved'));
    }
  });
};
</script>

<template>
  <AdminLayout :description="__('Showcase the platform\'s key features')" :title="__('Features')">
    <AddNewFeature/>
    <EditFeature/>

    <Card :collapsible="false" :has-shadow="false" class="flex flex-col space-y-1">
      <template #header>
        {{ __('Features') }}
      </template>

      <ul v-if="features.length > 0" class="w-full divide-y divide-gray-100" role="list">
        <li v-for="(feature, index) in features"
            class="flex flex-wrap items-center justify-between py-3 gap-x-6 gap-y-4 sm:flex-nowrap">
          <div>
            <div class="flex items-center gap-x-3">

              <img v-if="feature.image_url" :alt="feature.name"
                   :src="feature.image_url" class="w-12 h-12 object-cover g-gray-50 ring-2 ring-white">

              <div class="flex flex-col gap-1">
                <p class="text-sm font-semibold leading-6 text-gray-900">
                  <button class="hover:underline" @click="editFeatureAction(feature)">
                    {{ feature.name }}
                  </button>
                </p>

                <p class="text-xs text-muted-foreground">
                  <time :datetime="feature.created_at">
                    {{ __('Created on :date', {date: feature.created_at}) }}
                  </time>
                </p>
              </div>
            </div>
          </div>

          <dl class="flex justify-between flex-none w-full gap-x-8 sm:w-auto">
            <div class="flex gap-x-1">
              <button class="text-gray-500 hover:text-blue-600" @click="editFeatureAction(feature)">
                <PencilSquareIcon class="w-5 h-5 stroke-current"/>
              </button>

              <button class="text-gray-500 hover:text-red-600" @click="deleteFeature(feature)">
                <TrashIcon class="w-5 h-5 stroke-current"/>
              </button>

              <button class="text-gray-500 hover:text-gray-600" v-if="index > 0" v-tooltip="__('Move up')" @click="moveUp(feature)">
                <CircleChevronUp class="size-4"></CircleChevronUp>
              </button>

              <button v-if="index !== features.length - 1" v-tooltip="__('Move down')"
                      @click="moveDown(feature)" class="text-gray-500 hover:text-gray-600">
                <CircleChevronDown class="size-4"></CircleChevronDown>
              </button>
            </div>
          </dl>
        </li>
      </ul>

      <div v-else class="text-sm text-muted-foreground">
        {{ __('No features found.') }}
      </div>

      <template #footer>
        <PrimaryButton class="self-end" @click="addNewFeatureAction">
          {{ __('Add New Feature') }}
        </PrimaryButton>
      </template>
    </Card>


    <!--    <div class="p-3 border border-gray-200 rounded-md">
          <div class="flex flex-col p-3">
            <div class="flex items-center justify-end pb-3 border-b border-gray-200">
              <PrimaryButton class="self-end" @click="addNewFeature">
                Add New Feature
              </PrimaryButton>
            </div>

            <ul v-if="features.length > 0" class="w-full divide-y divide-gray-100" role="list">
              <li v-for="feature in features"
                  class="flex flex-wrap items-center justify-between py-5 gap-x-6 gap-y-4 sm:flex-nowrap">
                <div>
                  <div class="flex items-center gap-x-3">

                    <img v-if="feature.image_url" :alt="feature.name"
                         :src="feature.image_url" class="w-12 h-12 g-gray-50 ring-2 ring-white">

                    <p class="text-sm font-semibold leading-6 text-gray-900">
                      <button class="hover:underline" @click="editFeature(feature)">
                        {{ feature.name }}
                      </button>
                    </p>
                  </div>

                  <div class="flex items-center mt-1 text-xs leading-5 text-gray-500 gap-x-2">
                    <p>
                      <time :datetime="feature.created_at">
                        {{ feature.created_at }}
                      </time>
                    </p>
                  </div>
                </div>

                <dl class="flex justify-between flex-none w-full gap-x-8 sm:w-auto">
                  <div class="flex w-16 gap-x-2.5">
                    <button @click="editFeature(feature)">
                      <PencilIcon class="w-6 h-6 text-gray-400 hover:text-gray-600"/>
                    </button>

                    <button @click="deleteFeature(feature)">
                      <TrashIcon class="w-6 h-6 text-gray-400 hover:text-red-600"/>
                    </button>
                  </div>
                </dl>
              </li>
            </ul>

            <div v-else class="mt-4 text-sm text-gray-600">
              No features found.
            </div>
          </div>
        </div>-->
  </AdminLayout>
</template>

<style scoped></style>
