<script setup>
import AddNewFaq from "@peak/Modals/Admin/Settings/Frontend/Faqs/AddNewFaq.vue";
import EditFaq from "@peak/Modals/Admin/Settings/Frontend/Faqs/EditFaq.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import {PencilIcon, PencilSquareIcon, TrashIcon} from "@heroicons/vue/24/outline/index.js";
import {router, usePage} from "@inertiajs/vue3";
import {inject} from "vue";
import {useToast} from "vue-toastification";
import Card from "@peak/Components/Admin/Card.vue";
import {CircleChevronDown, CircleChevronUp} from "lucide-vue-next";
import {useConfirm} from "@peak/Composables/useConfirm.js";
import {__} from "@peak/Composables/useTranslate.js";

const props = defineProps({
  errors: Object,
  faqs: Object
});

const toast = useToast();
const dayJS = inject('dayJS');
const emitter = inject('emitter');
const page = usePage();

const addNewFaqAction = () => {
  emitter.emit('faq:add-new');
};

const editFaqAction = (faq) => {
  emitter.emit('faq:edit', faq);
};

const deleteFaq = async (faq) => {

  const confirmed = await useConfirm({
    title: __("Delete FAQ?"),
    text: __("Are you sure you want to delete this faq? This cannot be undone."),
    confirmButtonText: __("Yes, delete it"),
  });

  if (confirmed) {
    router.delete(route('admin.settings.frontend.faqs.delete', {
      preserveScroll: true,
      faq: faq.id,
    }), {
      onSuccess: () => {
        toast.success(__('Item was successfully deleted.'));
      },
      onError: () => {
        toast.success(__('Something went wrong.'));
      }
    });
  }
};

const moveUp = (item) => {
  router.patch(route('admin.settings.frontend.faqs.move-up', item.id), {}, {
    preserveState: false,
    preserveScroll: true,
    onSuccess: () => {
      toast.success(__('FAQ order changed.'));
    }
  });
}

const moveDown = (item) => {
  router.patch(route('admin.settings.frontend.faqs.move-down', item.id), {}, {
    preserveState: false,
    preserveScroll: true,
    onSuccess: () => {
      toast.success(__('FAQ order changed.'));
    }
  });
}
</script>

<template>
  <AdminLayout :description="__('Manage frequently asked questions for the frontend.')" :title="__('FAQs')">
    <AddNewFaq/>
    <EditFaq/>

    <Card :collapsible="false" :has-shadow="false" class="flex flex-col space-y-1">
      <template #header>
        {{ __('FAQs') }}
      </template>

      <template #actions>
        <PrimaryButton class="self-end" @click="addNewFaqAction">
          {{ __('Add New FAQ') }}
        </PrimaryButton>
      </template>

      <ul v-if="faqs.length > 0" class="w-full divide-y divide-gray-100" role="list">
        <li v-for="(faq, index) in faqs"
            class="flex flex-wrap items-center justify-between py-2 gap-x-6 gap-y-4 sm:flex-nowrap">
          <div>
            <div class="flex items-center gap-x-3">

              <div class="flex flex-col gap-1">
                <p class="text-sm font-semibold leading-6 text-gray-900">
                  <button class="hover:underline" @click="editFaqAction(faq)">
                    {{ faq.question }}
                  </button>
                </p>

                <p class="text-xs text-muted-foreground">
                  <time :datetime="faq.created_at">
                    {{ __('Created on :date', {date: faq.created_at}) }}
                  </time>
                </p>
              </div>
            </div>
          </div>

          <div class="flex gap-x-1">
            <button class="text-gray-500 hover:text-blue-600" @click="editFaqAction(faq)">
              <PencilSquareIcon class="w-5 h-5 stroke-current"/>
            </button>

            <button class="text-gray-500 hover:text-red-600" @click="deleteFaq(faq)">
              <TrashIcon class="w-5 h-5 stroke-current"/>
            </button>

            <button class="text-gray-500 hover:text-gray-600" v-if="index > 0" v-tooltip="__('Move up')" @click="moveUp(faq)">
              <CircleChevronUp class="size-4"></CircleChevronUp>
            </button>

            <button v-if="index !== faqs.length - 1" v-tooltip="__('Move down')"
                    @click="moveDown(faq)" class="text-gray-500 hover:text-gray-600">
              <CircleChevronDown class="size-4"></CircleChevronDown>
            </button>
          </div>
        </li>
      </ul>

      <div v-else class="text-sm text-muted-foreground">
        {{ __('No FAQs found.') }}
      </div>

    </Card>
  </AdminLayout>
</template>

<style scoped></style>
