<script setup>
import AddNewTestimonial from '@peak/Modals/Admin/Settings/Frontend/Testimonials/AddNewTestimonial.vue';
import EditTestimonial from '@peak/Modals/Admin/Settings/Frontend/Testimonials/EditTestimonial.vue';
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import {PencilIcon, PencilSquareIcon, TrashIcon} from "@heroicons/vue/24/outline/index.js";
import {router, usePage} from "@inertiajs/vue3";
import {inject} from "vue";
import {useToast} from "vue-toastification";
import Card from "@peak/Components/Admin/Card.vue";
import {CircleChevronDown, CircleChevronUp, Star} from "lucide-vue-next";
import {__} from "@peak/Composables/useTranslate.js";

const props = defineProps({
  errors: Object,
  testimonials: Object
});

const toast = useToast();
const dayJS = inject('dayJS');
const emitter = inject('emitter');
const page = usePage();

const addNewTestimonialAction = () => {
  emitter.emit('testimonial:add-new');
}

const editTestimonialAction = (testimonial) => {
  emitter.emit('testimonial:edit', testimonial);
}

const deleteTestimonial = (testimonial) => {
  window.swal.fire({
    title: __('Are you sure you want to delete this testimonial?'),
    showCancelButton: true,
    confirmButtonText: __('Delete'),
    confirmButtonColor: 'tomato',
  }).then((result) => {
    if (result.isConfirmed) {
      router.delete(route('admin.settings.frontend.testimonials.delete', {
        testimonial: testimonial.id,
      }), {
        preserveScroll: true,
        onSuccess: () => {
          toast.success(__('Testimonial deleted successfully.'));
        },
        onError: () => {
          toast.error(__('Failed to delete the testimonial.'));
        }
      });
    }
  });

}

const moveUp = (item) => {
  router.patch(route('admin.settings.frontend.testimonials.move-up', item.id), {}, {
    preserveState: false,
    preserveScroll: true,
    onSuccess: () => {
      toast.success(__('Changes Saved'));
    }
  });
};

const moveDown = (item) => {
  router.patch(route('admin.settings.frontend.testimonials.move-down', item.id), {}, {
    preserveState: false,
    preserveScroll: true,
    onSuccess: () => {
      toast.success(__('Changes Saved'));
    }
  });
};

</script>

<template>
  <AdminLayout :description="__('Manage user testimonials displayed publicly')" :title="__('Testimonials')">
    <AddNewTestimonial/>
    <EditTestimonial/>

    <Card :collapsible="false" :has-shadow="false" class="flex flex-col space-y-1">
      <template #header>
        {{ __('Testimonials') }}
      </template>

      <template #actions>
        <PrimaryButton class="self-end" @click="addNewTestimonialAction">
          {{ __('Add New Testimonial') }}
        </PrimaryButton>
      </template>

      <ul v-if="testimonials.length > 0" class="w-full divide-y divide-gray-100" role="list">
        <li v-for="(testimonial, index) in testimonials"
            class="flex flex-wrap items-center justify-between py-3 gap-x-6 gap-y-4 sm:flex-nowrap">
          <div>
            <div class="flex items-center gap-x-3">
              <img :alt="testimonial.name"
                   :src="testimonial.profile_photo_url"
                   class="w-8 h-8 object-cover rounded-full bg-gray-50 ring-2 ring-white">

              <div class="flex flex-col gap-1">
                <p class="text-sm font-semibold leading-6 text-gray-900">
                  <button class="hover:underline" @click="editTestimonialAction(testimonial)">
                    {{ testimonial.name }}
                  </button>

                  <span class="flex gap-x-1">
                    <Star v-for="n in testimonial.rating" :key="n" class="w-3 fill-current text-yellow-400"/>
                  </span>
                </p>

                <p class="text-xs text-muted-foreground">
                  <time :datetime="testimonial.created_at">
                    {{ __('Created on :date', {date: testimonial.created_at}) }}
                  </time>
                </p>
              </div>

            </div>
          </div>

          <div class="flex gap-x-1">
            <button class="text-gray-500 hover:text-blue-600" @click="editTestimonialAction(testimonial)">
              <PencilSquareIcon class="w-5 h-5 stroke-current"/>
            </button>

            <button class="text-gray-500 hover:text-red-600" @click="deleteTestimonial(testimonial)">
              <TrashIcon class="w-5 h-5 stroke-current"/>
            </button>

            <button class="text-gray-500 hover:text-gray-600" v-if="index > 0" v-tooltip="__('Move up')" @click="moveUp(testimonial)">
              <CircleChevronUp class="size-4"></CircleChevronUp>
            </button>

            <button v-if="index !== testimonials.length - 1" v-tooltip="__('Move down')"
                    @click="moveDown(testimonial)" class="text-gray-500 hover:text-gray-600">
              <CircleChevronDown class="size-4"></CircleChevronDown>
            </button>
          </div>

        </li>
      </ul>

      <div v-else class="text-sm text-muted-foreground">
        {{ __('No testimonials found.') }}
      </div>
    </Card>
  </AdminLayout>
</template>

<style scoped>

</style>
