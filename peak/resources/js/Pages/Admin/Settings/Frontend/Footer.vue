<script setup>

import AddNewMenuItem from "@peak/Modals/Admin/Settings/Frontend/Menu/AddNewMenuItem.vue";
import EditNewMenuItem from "@peak/Modals/Admin/Settings/Frontend/Menu/EditNewMenuItem.vue";
import InputError from "@peak/Components/Admin/InputError.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import {PencilSquareIcon, TrashIcon} from "@heroicons/vue/24/outline/index.js";
import {router, useForm, usePage} from "@inertiajs/vue3";
import {inject, onMounted} from "vue";
import {useToast} from "vue-toastification";
import TextInput from "@peak/Components/Admin/Input.vue";
import InputLabel from "@peak/Components/Admin/InputLabel.vue";
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import Card from "@peak/Components/Admin/Card.vue";
import {CircleChevronDown, CircleChevronUp} from "lucide-vue-next";
import {__} from "@peak/Composables/useTranslate.js";

const page = usePage();

const props = defineProps({
  errors: Object,
  footerMenu: Object,
  footer_copyright_text: String | null
});

const toast = useToast();

const emitter = inject('emitter');

const addNewMenu = () => {
  emitter.emit('menu:add-item', 'footer');
};

const editItem = item => {
  emitter.emit('menu:edit-item', item);
};

const deleteItem = (itemId) => {
  window.swal.fire({
    title: __('Are you sure you want to delete this menu item?'),
    showCancelButton: true,
    confirmButtonColor: 'tomato',
    confirmButtonText: __('Delete'),
  }).then((result) => {
    if (result.isConfirmed) {
      router.delete(route('admin.settings.frontend.footer.menu.delete', itemId), {
        onSuccess: () => {
          location.reload();
          toast.success(__('Menu item deleted successfully.'));
        },
        onError: () => {
          toast.error(__('Failed to delete the menu item.'));
        }
      });
    }
  });
};


const templateUrl = (name) => {
  switch (name) {
    case 'documentation':
      return route('docs.index');

    case 'roadmap':
      return route('roadmap.index');

    case 'blog':
      return route('blog.index');

    case 'changelog':
      return route('changelog.index',);

    case 'contact':
      return route('contact.index',);

    case 'support':
      return route('support.index');

    case 'about':
      return route('about.index');
  }
}

const itemLink = (item) => {
  if (item.page) {
    return route('page.show', item.page.slug);
  }

  if (item.template_name) {
    return templateUrl(item.template_name);
  }

  if (item.external_link) {
    return item.external_link;
  }

  if (item.internal_path) {
    // return route('built-in-page.' + item.internal_path + '.view');
  }
}

onMounted(() => {
  emitter.on('menu:reload', function () {
    location.reload();
  });
});

const updateHeaderTextForm = useForm({
  footer_copyright_text: props.footer_copyright_text
});

const save = () => {
  updateHeaderTextForm.put(route('admin.settings.frontend.footer.update'), {
    onSuccess: () => {
      toast.success(__('Changes Saved'));
    }
  });
}

const moveUp = (item) => {
  router.patch(route('admin.settings.frontend.footer.menu.move-up', item.id), {}, {
    preserveState: false,
    preserveScroll: true,
    onSuccess: () => {
      toast.success(__('Changes Saved'));
    }
  });
}

const moveDown = (item) => {
  router.patch(route('admin.settings.frontend.footer.menu.move-down', item.id), {}, {
    preserveState: false,
    preserveScroll: true,
    onSuccess: () => {
      toast.success(__('Changes Saved'));
    }
  });
}

</script>

<template>
  <AdminLayout :description="__('Edit footer links and text')" :title="__('Footer')">
    <AddNewMenuItem/>
    <EditNewMenuItem/>

    <div class="flex flex-col gap-6">
      <Card :collapsible="false" :has-shadow="false" class="flex flex-col space-y-1">
        <template #header>
          {{ __('Content') }}
        </template>

        <div class="grid grid-cols-1 gap-5 py-5">
          <div>
            <div class="flex flex-col space-y-2">
              <InputLabel :value="__('Copyright Text')" for="footer_copyright_text"/>
              <TextInput id="footer_copyright_text" v-model="updateHeaderTextForm.footer_copyright_text"
                         class="w-full" placeholder="Copyright Text"/>
              <InputError :message="errors?.footer_copyright_text" class="mt-1"/>
            </div>
          </div>
        </div>

        <template #footer>
          <PrimaryButton :class="{ 'opacity-25': !updateHeaderTextForm.isDirty }"
                         :disabled="!updateHeaderTextForm.isDirty" class="self-end"
                         @click="save">
            {{ __('Save Changes') }}
          </PrimaryButton>
        </template>
      </Card>

      <Card :collapsible="false" :has-shadow="false" class="flex flex-col space-y-1">
        <template #header>
          {{ __('Menu items') }}
        </template>

        <template #actions>
          <PrimaryButton class="self-end" @click="addNewMenu">
            {{ __('Add New Menu Item') }}
          </PrimaryButton>
        </template>

        <div id="header_menu_items">
          <div v-if="footerMenu.length < 1" class="mt-4 text-sm text-gray-600">
            {{ __('No Menu items found.') }}
          </div>

          <div v-else>
            <ul class="divide-y divide-gray-100" role="list">
              <li v-for="(item, index) in footerMenu" :key="item.id"
                  class="flex items-center justify-between px-3 py-3 gap-x-6 hover:bg-gray-50">
                <div class="flex items-start min-w-0 gap-x-4">
                  <div class="flex-auto min-w-0">
                    <p v-if="item.title" class="text-sm font-semibold leading-6 text-gray-900 cursor-pointer"
                       @click="editItem(item)">
                      {{ item.title }}
                    </p>

                    <p v-else class="cursor-pointer text-muted-foreground text-sm" @click="editItem(item)">
                      {{ __('No title') }}
                    </p>

                    <a :href="itemLink(item)" class="mt-1 text-xs leading-5 text-gray-500 truncate hover:underline"
                       target="_blank">
                      {{ itemLink(item) }}
                    </a>
                  </div>
                </div>

                <div class="flex gap-x-1">
                  <button class="text-gray-500 hover:text-blue-600" @click="editItem(item)">
                    <PencilSquareIcon class="w-5 h-5 stroke-current"/>
                  </button>

                  <button class="text-gray-500 hover:text-red-600" @click="deleteItem(item.id)">
                    <TrashIcon class="w-5 h-5 stroke-current"/>
                  </button>

                  <button class="text-gray-500 hover:text-gray-600" v-if="index > 0" v-tooltip="__('Move up')" @click="moveUp(item)">
                    <CircleChevronUp class="size-4"></CircleChevronUp>
                  </button>

                  <button v-if="index !== footerMenu.length - 1" v-tooltip="__('Move down')"
                          @click="moveDown(item)" class="text-gray-500 hover:text-gray-600">
                    <CircleChevronDown class="size-4"></CircleChevronDown>
                  </button>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </Card>
    </div>
  </AdminLayout>
</template>

<style scoped></style>
