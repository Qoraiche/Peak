<script setup>

import Card from "@peak/Components/Admin/Card.vue";
import TextInput from "@peak/Components/Admin/Input.vue";
import InputError from "@peak/Components/Admin/InputError.vue";
import InputLabel from "@peak/Components/Admin/InputLabel.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import {useForm} from "@inertiajs/vue3";
import {inject} from "vue";
import {useToast} from "vue-toastification";
import {__} from "@peak/Composables/useTranslate.js";

const emitter = inject('emitter');
const toast = useToast();

const props = defineProps({
  facebook_link: {
    type: String,
    default: null
  },
  twitter_x_link: {
    type: String,
    default: null
  },
  instagram_link: {
    type: String,
    default: null
  },
  youtube_link: {
    type: String,
    default: null
  },
  twitch_link: {
    type: String,
    default: null
  },
  tiktok_link: {
    type: String,
    default: null
  },
  linkedin_link: {
    type: String,
    default: null
  },
  snapchat_link: {
    type: String,
    default: null
  },
  reddit_link: {
    type: String,
    default: null
  },
  pinterest_link: {
    type: String,
    default: null
  },
  discord_link: {
    type: String,
    default: null
  },
  whatsapp_link: {
    type: String,
    default: null
  },
  telegram_link: {
    type: String,
    default: null
  },
  github_link: {
    type: String,
    default: null
  },
  spotify_link: {
    type: String,
    default: null
  },
  soundcloud_link: {
    type: String,
    default: null
  }
});

const form = useForm({
  facebook_link: props.facebook_link,
  twitter_x_link: props.twitter_x_link,
  instagram_link: props.instagram_link,
  youtube_link: props.youtube_link,
  twitch_link: props.twitch_link,
  tiktok_link: props.tiktok_link,
  linkedin_link: props.linkedin_link,
  snapchat_link: props.snapchat_link,
  reddit_link: props.reddit_link,
  pinterest_link: props.pinterest_link,
  discord_link: props.discord_link,
  whatsapp_link: props.whatsapp_link,
  telegram_link: props.telegram_link,
  github_link: props.github_link,
  spotify_link: props.spotify_link,
  soundcloud_link: props.soundcloud_link,
});
const save = () => {
  form.put(route('admin.settings.website.social-links.update'), {
    onSuccess: () => {
      toast.success(__('Changes Saved'));
    },
    onError: () => {
      toast.error(__('Something went wrong.'));
    },
  });
};

</script>

<template>
  <AdminLayout :description="__('Add links to social profiles for display.')" :title="__('Social Links')">

    <template #actions>
      <PrimaryButton :class="{ 'opacity-25': !form.isDirty || form.processing }"
                     :disabled="!form.isDirty || form.processing" :loading="form.processing"
                     class="self-end"
                     @click="save">
        {{ __('Save Changes') }}
      </PrimaryButton>
    </template>

    <Card class="flex flex-col space-y-1">

      <template #header>
        {{ __('Edit Settings') }}
      </template>

      <div class="grid grid-cols-1 gap-5 py-5 md:grid-cols-2">
        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Facebook URL')" for="facebook_link"/>
            <TextInput v-model="form.facebook_link" class="w-full" name="facebook_link"/>

            <InputError :message="form.errors?.facebook_link" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('X (twitter) URL')" for="twitter_x_link"/>
            <TextInput v-model="form.twitter_x_link" class="w-full" name="twitter_x_link"/>

            <InputError :message="form.errors?.twitter_x_link" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Instagram URL')" for="instagram_link"/>
            <TextInput v-model="form.instagram_link" class="w-full" name="instagram_link"/>

            <InputError :message="form.errors?.instagram_link" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Youtube URL')" for="youtube_link"/>
            <TextInput v-model="form.youtube_link" class="w-full" name="youtube_link"/>

            <InputError :message="form.errors?.youtube_link" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Twitch URL')" for="twitch_link"/>
            <TextInput v-model="form.twitch_link" class="w-full" name="twitch_link"/>

            <InputError :message="form.errors?.twitch_link" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('TikTok URL')" for="tiktok_link"/>
            <TextInput v-model="form.tiktok_link" class="w-full" name="tiktok_link"/>

            <InputError :message="form.errors?.tiktok_link" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('LinkedIn URL')" for="linkedin_link"/>
            <TextInput v-model="form.linkedin_link" class="w-full" name="linkedin_link"/>

            <InputError :message="form.errors?.linkedin_link" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Snapchat URL')" for="snapchat_link"/>
            <TextInput v-model="form.snapchat_link" class="w-full" name="snapchat_link"/>

            <InputError :message="form.errors?.snapchat_link" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Reddit URL')" for="reddit_link"/>
            <TextInput v-model="form.reddit_link" class="w-full" name="reddit_link"/>

            <InputError :message="form.errors?.reddit_link" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Pinterest URL')" for="pinterest_link"/>
            <TextInput v-model="form.pinterest_link" class="w-full" name="pinterest_link"/>

            <InputError :message="form.errors?.pinterest_link" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Discord URL')" for="discord_link"/>
            <TextInput v-model="form.discord_link" class="w-full" name="discord_link"/>

            <InputError :message="form.errors?.discord_link" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('WhatsApp URL')" for="whatsapp_link"/>
            <TextInput v-model="form.whatsapp_link" class="w-full" name="whatsapp_link"/>

            <InputError :message="form.errors?.whatsapp_link" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Telegram URL')" for="telegram_link"/>
            <TextInput v-model="form.telegram_link" class="w-full" name="telegram_link"/>

            <InputError :message="form.errors?.telegram_link" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Github URL')" for="github_link"/>
            <TextInput v-model="form.github_link" class="w-full" name="github_link"/>

            <InputError :message="form.errors?.github_link" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Spotify URL')" for="spotify_link"/>
            <TextInput v-model="form.spotify_link" class="w-full" name="spotify_link"/>

            <InputError :message="form.errors?.spotify_link" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('SoundCloud URL')" for="soundcloud_link"/>
            <TextInput v-model="form.soundcloud_link" class="w-full" name="soundcloud_link"/>

            <InputError :message="form.errors?.soundcloud_link" class="mt-1"/>
          </div>
        </div>
      </div>
    </Card>
  </AdminLayout>
</template>

<style scoped></style>
