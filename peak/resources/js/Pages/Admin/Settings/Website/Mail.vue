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
  mailer: {type: String, required: true},
  host: {type: String, default: null},
  sendmail_path: {type: String, default: null},
  port: {type: String, default: null},
  username: {type: String, default: null},
  password: {type: String, default: null},
  encryption: {type: String, default: null},
  from_address: {type: String, default: null},
  from_name: {type: String, default: null},
  mailgun_domain: {type: String, default: null},
  mailgun_secret: {type: String, default: null},
  mailgun_endpoint: {type: String, default: null},
  postmark_token: {type: String, default: null},
  resend_key: {type: String, default: null},
  ses_key: {type: String, default: null},
  ses_secret: {type: String, default: null},
  ses_region: {type: String, default: null},
  mailersend_api_key: {type: String, default: null},
});

const form = useForm({
  mailer: props.mailer,
  host: props.host,
  port: props.port,
  username: props.username,
  password: props.password,
  encryption: props.encryption,
  from_address: props.from_address,
  from_name: props.from_name,
  sendmail_path: props.sendmail_path,
  mailgun_domain: props.mailgun_domain,
  mailgun_secret: props.mailgun_secret,
  mailgun_endpoint: props.mailgun_endpoint, // api.eu.mailgun.net
  postmark_token: props.postmark_token,
  resend_key: props.resend_key,
  ses_key: props.ses_key,
  ses_secret: props.ses_secret,
  ses_region: props.ses_region, // us-east-1
  mailersend_api_key: props.mailersend_api_key,
});

const save = () => {
  form.put(route('admin.settings.website.mail.update'), {
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
  <AdminLayout :description="__('Configure outgoing mail server settings.')" :title="__('Mail')">
    <template #actions>
      <PrimaryButton :class="{ 'opacity-25': !form.isDirty || form.processing }"
                     :disabled="!form.isDirty || form.processing" :loading="form.processing"
                     class="self-end"
                     @click="save">
        {{ __('Save Changes') }}
      </PrimaryButton>
    </template>

    <Card :collapsible="false" :has-shadow="false" class="flex flex-col space-y-1">
      <template #header>
        {{ __('Edit Settings') }}
      </template>

      <div class="grid grid-cols-1 gap-5 py-5 md:grid-cols-2">
        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel for="mailer" value="Mailer"/>
            <select id="mailer" v-model="form.mailer"
                    class="flex-1 block w-full min-w-0 text-gray-900 border border-gray-300 rounded-md placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500 sm:text-sm sm:leading-6">
              <option value="smtp">
                {{ __('SMTP') }}
              </option>

              <option value="sendmail">
                {{ __('Sendmail') }}
              </option>

              <option value="mailgun">
                {{ __('Mailgun') }}
              </option>

              <option value="postmark">
                {{ __('Postmark') }}
              </option>

              <option value="resend">
                {{ __('Resend') }}
              </option>

              <option value="ses">
                {{ __('Amazon SES') }}
              </option>

              <option value="mailersend">
                {{ __('MailerSend') }}
              </option>
            </select>

            <InputError :message="form.errors?.mailer" class="mt-1"/>
          </div>
        </div>

        <div v-if="form.mailer === 'sendmail'">
          <div class="flex flex-col space-y-2">
            <div class="flex items-center gap-x-2">
              <InputLabel for="path" value="Sendmail path"/>
            </div>

            <TextInput id="path" v-model="form.sendmail_path" autocomplete="off" class="block w-full"
                       placeholder="/usr/sbin/sendmail -bs -i"/>

            <span class="text-xs italic text-gray-500">{{
                __('The Sendmail path (e.g., /usr/sbin/sendmail -bs -i).')
              }}
            </span>

            <InputError :message="form.errors?.sendmail_path" class="mt-1"/>
          </div>
        </div>

        <div v-if="form.mailer === 'smtp'">
          <div class="flex flex-col space-y-2">
            <div class="flex items-center gap-x-2">
              <InputLabel for="host" value="Host"/>
            </div>
            <TextInput id="host" v-model="form.host" autocomplete="off" class="block w-full"
                       placeholder="Host"/>

            <span class="text-xs italic text-gray-500">{{
                __('The SMTP server address (e.g., smtp.mailtrap.io).')
              }}</span>

            <InputError :message="form.errors?.host" class="mt-1"/>
          </div>
        </div>

        <div v-if="form.mailer === 'smtp'">
          <div class="flex flex-col space-y-2">
            <div class="flex items-center gap-x-2">
              <InputLabel for="port" value="Port"/>
            </div>
            <TextInput id="host" v-model="form.port" autocomplete="off" class="block w-full"
                       placeholder="2525"/>

            <span class="text-xs italic text-gray-500">{{
                __('The port used to connect to the SMTP server (e.g., 587 or 465).')
              }}</span>

            <InputError :message="form.errors?.port" class="mt-1"/>
          </div>
        </div>

        <div v-if="form.mailer === 'smtp'">
          <div class="flex flex-col space-y-2">
            <div class="flex items-center gap-x-2">
              <InputLabel for="username" value="Username"/>
            </div>
            <TextInput id="username" v-model="form.username" autocomplete="off" class="block w-full"
                       placeholder="Username"/>

            <span class="text-xs italic text-gray-500">{{
                __('Your SMTP username (usually your email or provided by your mail service).')
              }}</span>

            <InputError :message="form.errors?.username" class="mt-1"/>
          </div>
        </div>

        <div v-if="form.mailer === 'smtp'">
          <div class="flex flex-col space-y-2">
            <div class="flex items-center gap-x-2">
              <InputLabel for="password" value="Password"/>
            </div>
            <TextInput id="password" v-model="form.password" autocomplete="off" class="block w-full"
                       placeholder="Password" type="password"/>

            <span class="text-xs italic text-gray-500">{{
                __('Your SMTP password or application-specific password.')
              }}</span>

            <InputError :message="form.errors?.password" class="mt-1"/>
          </div>
        </div>

        <div v-if="form.mailer === 'smtp'">
          <div class="flex flex-col space-y-2">
            <div class="flex">
              <InputLabel for="encryption" value="Encryption"/>
            </div>
            <TextInput id="encryption" v-model="form.encryption" autocomplete="off" class="block w-full"
                       placeholder="2525"/>

            <span class="text-xs italic text-gray-500">{{ __('Encryption method used (usually tls or ssl).') }}</span>
            <InputError :message="form.errors?.encryption" class="mt-1"/>
          </div>
        </div>

        <div v-if="form.mailer === 'mailgun'">
          <div class="flex flex-col space-y-2">
            <div class="flex items-center gap-x-2">
              <InputLabel for="mailgun_domain" value="Mailgun domain"/>

            </div>
            <TextInput id="mailgun_domain" v-model="form.mailgun_domain" autocomplete="off" class="block w-full"/>
            <InputError :message="form.errors?.mailgun_domain" class="mt-1"/>
          </div>
        </div>

        <div v-if="form.mailer === 'mailgun'">
          <div class="flex flex-col space-y-2">
            <div class="flex items-center gap-x-2">
              <InputLabel for="mailgun_secret" value="Mailgun secret"/>
            </div>
            <TextInput id="mailgun_secret" v-model="form.mailgun_secret" autocomplete="off" class="block w-full"/>

            <span class="text-xs italic text-gray-500">{{
                __('API key provided by Mailgun to authorize sending.')
              }}</span>

            <InputError :message="form.errors?.mailgun_secret" class="mt-1"/>
          </div>
        </div>

        <div v-if="form.mailer === 'mailgun'">
          <div class="flex flex-col space-y-2">
            <div class="flex items-center gap-x-2">
              <InputLabel for="mailgun_endpoint" value="Mailgun endpoint"/>
            </div>

            <TextInput id="mailgun_secret" v-model="form.mailgun_endpoint" autocomplete="off"
                       class="block w-full" placeholder="api.mailgun.net"/>

            <span class="text-xs italic text-gray-500">{{
                __('The Mailgun API endpoint (e.g., api.mailgun.net or EU-specific).')
              }}</span>

            <InputError :message="form.errors?.mailgun_endpoint" class="mt-1"/>
          </div>
        </div>

        <div v-if="form.mailer === 'postmark'">
          <div class="flex flex-col space-y-2">
            <div class="flex items-center gap-x-2">
              <InputLabel :value="__('Postmark token')" for="postmark_token"/>
            </div>
            <TextInput id="postmark_token" v-model="form.postmark_token" autocomplete="off" class="block w-full"/>
            <InputError :message="form.errors?.postmark_token" class="mt-1"/>
          </div>
        </div>

        <div v-if="form.mailer === 'resend'">
          <div class="flex flex-col space-y-2">
            <div class="flex items-center gap-x-2">
              <InputLabel :value="__('Resend key')" for="resend_key"/>
            </div>
            <TextInput id="resend_key" v-model="form.resend_key" autocomplete="off" class="block w-full"/>
            <InputError :message="form.errors?.resend_key" class="mt-1"/>
          </div>
        </div>

        <div v-if="form.mailer === 'ses'">
          <div class="flex flex-col space-y-2">
            <div class="flex items-center gap-x-2">
              <InputLabel :value="__('Key')" for="ses_key"/>
            </div>
            <TextInput id="ses_key" v-model="form.ses_key" autocomplete="off" class="block w-full"/>
            <InputError :message="form.errors?.ses_key" class="mt-1"/>
          </div>
        </div>

        <div v-if="form.mailer === 'ses'">
          <div class="flex flex-col space-y-2">
            <div class="flex items-center gap-x-2">
              <InputLabel :value="__('Secret')" for="ses_secret"/>
            </div>
            <TextInput id="ses_secret" v-model="form.ses_secret" autocomplete="off" class="block w-full"/>
            <InputError :message="form.errors?.ses_secret" class="mt-1"/>
          </div>
        </div>

        <div v-if="form.mailer === 'ses'">
          <div class="flex flex-col space-y-2">
            <div class="flex items-center gap-x-2">
              <InputLabel :value="__('Region')" for="ses_region"/>
            </div>
            <TextInput id="ses_region" v-model="form.ses_region" autocomplete="off" class="block w-full"/>
            <InputError :message="form.errors?.ses_region" class="mt-1"/>
          </div>
        </div>

        <div v-if="form.mailer === 'mailersend'">
          <div class="flex flex-col space-y-2">
            <div class="flex items-center gap-x-2">
              <InputLabel :value="__('API Key')" for="mailersend_api_key"/>
            </div>
            <TextInput id="mailersend_api_key" v-model="form.mailersend_api_key" autocomplete="off"
                       class="block w-full"/>
            <InputError :message="form.errors?.mailersend_api_key" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <div class="flex items-center gap-x-2">
              <InputLabel :value="__('From address')" for="from_address"/>
            </div>
            <TextInput id="from_address" v-model="form.from_address" autocomplete="off"
                       class="block w-full" placeholder="contact@domain.net"/>
            <InputError :message="form.errors?.from_address" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <div class="flex items-center gap-x-2">
              <InputLabel :value="__('From name')" for="from_name"/>
            </div>
            <TextInput id="from_name" v-model="form.from_name" :placeholder="__('Site Name')" autocomplete="off"
                       class="block w-full"/>
            <InputError :message="form.errors?.from_name" class="mt-1"/>
          </div>
        </div>
      </div>
    </Card>

    <template #footerActions>

    </template>
  </AdminLayout>
</template>

<style scoped></style>
