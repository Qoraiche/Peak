<script setup>

import Card from "@peak/Components/Admin/Card.vue";
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import ConnectBitbucketAuthProvider from "@peak/Modals/Admin/Settings/Website/ConnectBitbucketAuthProvider.vue";
import ConnectFacebookAuthProvider from "@peak/Modals/Admin/Settings/Website/ConnectFacebookAuthProvider.vue";
import ConnectGithubAuthProvider from "@peak/Modals/Admin/Settings/Website/ConnectGithubAuthProvider.vue";
import ConnectGitlabAuthProvider from "@peak/Modals/Admin/Settings/Website/ConnectGitlabAuthProvider.vue";
import ConnectGoogleAuthProvider from "@peak/Modals/Admin/Settings/Website/ConnectGoogleAuthProvider.vue";
import ConnectLinkedinAuthProvider from "@peak/Modals/Admin/Settings/Website/ConnectLinkedinAuthProvider.vue";
import ConnectSlackAuthProvider from "@peak/Modals/Admin/Settings/Website/ConnectSlackAuthProvider.vue";
import ConnectXAuthProvider from "@peak/Modals/Admin/Settings/Website/ConnectXAuthProvider.vue";
import {inject} from "vue";
import {__} from "@peak/Composables/useTranslate.js";

const emitter = inject('emitter');

const props = defineProps({
  facebook: Object,
  x: Object,
  linkedin: Object,
  google: Object,
  github: Object,
  gitlab: Object,
  bitbucket: Object,
  slack: Object
});

const connectProvider = (emitAction) => {
  emitter.emit(emitAction);
};

</script>

<template>
  <AdminLayout :description="__('Enable and configure social login providers.')" :title="__('Social Login')">
    <ConnectFacebookAuthProvider :facebook="facebook"/>
    <ConnectBitbucketAuthProvider :bitbucket="bitbucket"/>
    <ConnectGithubAuthProvider :github="github"/>
    <ConnectGitlabAuthProvider :gitlab="gitlab"/>
    <ConnectGoogleAuthProvider :google="google"/>
    <ConnectLinkedinAuthProvider :linkedin="linkedin"/>
    <ConnectSlackAuthProvider :slack="slack"/>
    <ConnectXAuthProvider :x="x"/>

    <Card :collapsible="false" :has-shadow="false" class="flex flex-col space-y-1">
      <template #header>
        {{ __('Edit Settings') }}
      </template>

      <div class="mx-auto">
        <div class="mt-6">
          <h3 class="text-lg font-bold">{{ __('Connect login providers') }}</h3>

          <p class="font-semibold text-gray-600">
            {{
              __('Allow users to log in using third-party providers such as Google, GitHub, or Facebook. Simplifies authentication and enhances user convenience by connecting with external accounts.')
            }}</p>
        </div>

        <div
            class="flex lg:w-1/2 items-center justify-between p-4 mt-8 border border-gray-200 cursor-pointer hover:bg-gray-50"
            @click="connectProvider('facebookAuthProvider')">
          <div>
            <div class="flex items-center gap-x-3">

              <svg class="size-6" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>
                Facebook</title>
                <path
                    d="M9.101 23.691v-7.98H6.627v-3.667h2.474v-1.58c0-4.085 1.848-5.978 5.858-5.978.401 0 .955.042 1.468.103a8.68 8.68 0 0 1 1.141.195v3.325a8.623 8.623 0 0 0-.653-.036 26.805 26.805 0 0 0-.733-.009c-.707 0-1.259.096-1.675.309a1.686 1.686 0 0 0-.679.622c-.258.42-.374.995-.374 1.752v1.297h3.919l-.386 2.103-.287 1.564h-3.246v8.245C19.396 23.238 24 18.179 24 12.044c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.628 3.874 10.35 9.101 11.647Z"/>
              </svg>

              <span class="font-medium text-sm text-zinc-800">{{
                  __('Facebook')
                }}</span>
            </div>

          </div>

          <span v-if="facebook.active"
                class="inline-flex items-center gap-x-1.5 rounded-md bg-green-100 px-2 py-1 text-xs font-medium text-green-700">
            <svg aria-hidden="true" class="h-1.5 w-1.5 fill-green-500" viewBox="0 0 6 6">
              <circle cx="3" cy="3" r="3"/>
            </svg>
            {{ __('Connect') }}
          </span>

          <span v-else
                class="inline-flex items-center gap-x-1.5 rounded-md bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800">
            <svg aria-hidden="true" class="h-1.5 w-1.5 fill-yellow-500" viewBox="0 0 6 6">
              <circle cx="3" cy="3" r="3"/>
            </svg>
            {{ __('Not connected') }}
          </span>
        </div>

        <div
            class="flex lg:w-1/2 items-center justify-between p-4 mt-2 border border-gray-200 cursor-pointer hover:bg-gray-50"
            @click="connectProvider('xAuthProvider')">
          <div>
            <div class="flex items-center gap-x-3">

              <svg class="size-6" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>X</title>
                <path
                    d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"/>
              </svg>

              <span class="font-medium text-sm text-zinc-800">{{
                  __('Twitter (X)')
                }}</span>
            </div>

          </div>

          <span v-if="x.active"
                class="inline-flex items-center gap-x-1.5 rounded-md bg-green-100 px-2 py-1 text-xs font-medium text-green-700">
            <svg aria-hidden="true" class="h-1.5 w-1.5 fill-green-500" viewBox="0 0 6 6">
              <circle cx="3" cy="3" r="3"/>
            </svg>
            {{ __('Connect') }}
          </span>

          <span v-else
                class="inline-flex items-center gap-x-1.5 rounded-md bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800">
            <svg aria-hidden="true" class="h-1.5 w-1.5 fill-yellow-500" viewBox="0 0 6 6">
              <circle cx="3" cy="3" r="3"/>
            </svg>
            {{ __('Not connected') }}
          </span>
        </div>

        <div
            class="flex lg:w-1/2 items-center justify-between p-4 mt-2 border border-gray-200 cursor-pointer hover:bg-gray-50"
            @click="connectProvider('linkedinAuthProvider')">
          <div>
            <div class="flex items-center gap-x-3">

              <svg class="size-6" clip-rule="evenodd" fill-rule="evenodd"
                   image-rendering="optimizeQuality" shape-rendering="geometricPrecision"
                   text-rendering="geometricPrecision"
                   viewBox="0 0 512 509.64"
                   xmlns="http://www.w3.org/2000/svg">
                <rect height="509.64" rx="115.61" ry="115.61" width="512"/>
                <path
                    d="M204.97 197.54h64.69v33.16h.94c9.01-16.16 31.04-33.16 63.89-33.16 68.31 0 80.94 42.51 80.94 97.81v116.92h-67.46l-.01-104.13c0-23.81-.49-54.45-35.08-54.45-35.12 0-40.51 25.91-40.51 52.72v105.86h-67.4V197.54zm-38.23-65.09c0 19.36-15.72 35.08-35.08 35.08-19.37 0-35.09-15.72-35.09-35.08 0-19.37 15.72-35.08 35.09-35.08 19.36 0 35.08 15.71 35.08 35.08zm-70.17 65.09h70.17v214.73H96.57V197.54z"
                    fill="#fff"/>
              </svg>

              <span class="font-medium text-sm text-zinc-800">{{
                  __('LinkedIn')
                }}</span>
            </div>

          </div>

          <span v-if="linkedin.active"
                class="inline-flex items-center gap-x-1.5 rounded-md bg-green-100 px-2 py-1 text-xs font-medium text-green-700">
            <svg aria-hidden="true" class="h-1.5 w-1.5 fill-green-500" viewBox="0 0 6 6">
              <circle cx="3" cy="3" r="3"/>
            </svg>
            {{ __('Connect') }}
          </span>

          <span v-else
                class="inline-flex items-center gap-x-1.5 rounded-md bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800">
            <svg aria-hidden="true" class="h-1.5 w-1.5 fill-yellow-500" viewBox="0 0 6 6">
              <circle cx="3" cy="3" r="3"/>
            </svg>
            {{ __('Not connected') }}
          </span>
        </div>

        <div
            class="flex lg:w-1/2 items-center justify-between p-4 mt-2 border border-gray-200 cursor-pointer hover:bg-gray-50"
            @click="connectProvider('googleAuthProvider')">

          <div>
            <div class="flex items-center gap-x-3">

              <svg class="size-6" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>
                Google</title>
                <path
                    d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z"/>
              </svg>

              <span class="font-medium text-sm text-zinc-800">{{
                  __('Google')
                }}</span>
            </div>

          </div>

          <span v-if="google.active"
                class="inline-flex items-center gap-x-1.5 rounded-md bg-green-100 px-2 py-1 text-xs font-medium text-green-700">
            <svg aria-hidden="true" class="h-1.5 w-1.5 fill-green-500" viewBox="0 0 6 6">
              <circle cx="3" cy="3" r="3"/>
            </svg>
            {{ __('Connect') }}
          </span>

          <span v-else
                class="inline-flex items-center gap-x-1.5 rounded-md bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800">
            <svg aria-hidden="true" class="h-1.5 w-1.5 fill-yellow-500" viewBox="0 0 6 6">
              <circle cx="3" cy="3" r="3"/>
            </svg>
            {{ __('Not connected') }}
          </span>
        </div>

        <div
            class="flex lg:w-1/2 items-center justify-between p-4 mt-2 border border-gray-200 cursor-pointer hover:bg-gray-50"
            @click="connectProvider('githubAuthProvider')">

          <div>
            <div class="flex items-center gap-x-3">

              <svg class="size-6" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>
                GitHub</title>
                <path
                    d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/>
              </svg>

              <span class="font-medium text-sm text-zinc-800">{{
                  __('Github')
                }}</span>
            </div>

          </div>

          <span v-if="github.active"
                class="inline-flex items-center gap-x-1.5 rounded-md bg-green-100 px-2 py-1 text-xs font-medium text-green-700">
            <svg aria-hidden="true" class="h-1.5 w-1.5 fill-green-500" viewBox="0 0 6 6">
              <circle cx="3" cy="3" r="3"/>
            </svg>
            {{ __('Connect') }}
          </span>

          <span v-else
                class="inline-flex items-center gap-x-1.5 rounded-md bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800">
            <svg aria-hidden="true" class="h-1.5 w-1.5 fill-yellow-500" viewBox="0 0 6 6">
              <circle cx="3" cy="3" r="3"/>
            </svg>
            {{ __('Not connected') }}
          </span>
        </div>

        <div
            class="flex lg:w-1/2 items-center justify-between p-4 mt-2 border border-gray-200 cursor-pointer hover:bg-gray-50"
            @click="connectProvider('gitlabAuthProvider')">
          <div>
            <div class="flex items-center gap-x-3">

              <svg class="size-6" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>X</title>
                <path
                    d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"/>
              </svg>

              <span class="font-medium text-sm text-zinc-800">{{
                  __('Gitlab')
                }}</span>
            </div>

          </div>

          <span v-if="gitlab.active"
                class="inline-flex items-center gap-x-1.5 rounded-md bg-green-100 px-2 py-1 text-xs font-medium text-green-700">
            <svg aria-hidden="true" class="h-1.5 w-1.5 fill-green-500" viewBox="0 0 6 6">
              <circle cx="3" cy="3" r="3"/>
            </svg>
            {{ __('Connect') }}
          </span>

          <span v-else
                class="inline-flex items-center gap-x-1.5 rounded-md bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800">
            <svg aria-hidden="true" class="h-1.5 w-1.5 fill-yellow-500" viewBox="0 0 6 6">
              <circle cx="3" cy="3" r="3"/>
            </svg>
            {{ __('Not connected') }}
          </span>
        </div>

        <div
            class="flex lg:w-1/2 items-center justify-between p-4 mt-2 border border-gray-200 cursor-pointer hover:bg-gray-50"
            @click="connectProvider('bitbucketAuthProvider')">

          <div>
            <div class="flex items-center gap-x-3">

              <svg class="size-6" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>
                GitLab</title>
                <path
                    d="m23.6004 9.5927-.0337-.0862L20.3.9814a.851.851 0 0 0-.3362-.405.8748.8748 0 0 0-.9997.0539.8748.8748 0 0 0-.29.4399l-2.2055 6.748H7.5375l-2.2057-6.748a.8573.8573 0 0 0-.29-.4412.8748.8748 0 0 0-.9997-.0537.8585.8585 0 0 0-.3362.4049L.4332 9.5015l-.0325.0862a6.0657 6.0657 0 0 0 2.0119 7.0105l.0113.0087.03.0213 4.976 3.7264 2.462 1.8633 1.4995 1.1321a1.0085 1.0085 0 0 0 1.2197 0l1.4995-1.1321 2.4619-1.8633 5.006-3.7489.0125-.01a6.0682 6.0682 0 0 0 2.0094-7.003z"/>
              </svg>

              <span class="font-medium text-sm text-zinc-800">{{
                  __('Bitbucket')
                }}</span>
            </div>

          </div>

          <span v-if="bitbucket.active"
                class="inline-flex items-center gap-x-1.5 rounded-md bg-green-100 px-2 py-1 text-xs font-medium text-green-700">
            <svg aria-hidden="true" class="h-1.5 w-1.5 fill-green-500" viewBox="0 0 6 6">
              <circle cx="3" cy="3" r="3"/>
            </svg>
            {{ __('Connect') }}
          </span>

          <span v-else
                class="inline-flex items-center gap-x-1.5 rounded-md bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800">
            <svg aria-hidden="true" class="h-1.5 w-1.5 fill-yellow-500" viewBox="0 0 6 6">
              <circle cx="3" cy="3" r="3"/>
            </svg>
            {{ __('Not connected') }}
          </span>
        </div>

        <div
            class="flex lg:w-1/2 items-center justify-between p-4 mt-2 border border-gray-200 cursor-pointer hover:bg-gray-50"
            @click="connectProvider('slackAuthProvider')">

          <div>
            <div class="flex items-center gap-x-3">

              <svg class="size-6" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Slack</title>
                <path
                    d="M5.042 15.165a2.528 2.528 0 0 1-2.52 2.523A2.528 2.528 0 0 1 0 15.165a2.527 2.527 0 0 1 2.522-2.52h2.52v2.52zM6.313 15.165a2.527 2.527 0 0 1 2.521-2.52 2.527 2.527 0 0 1 2.521 2.52v6.313A2.528 2.528 0 0 1 8.834 24a2.528 2.528 0 0 1-2.521-2.522v-6.313zM8.834 5.042a2.528 2.528 0 0 1-2.521-2.52A2.528 2.528 0 0 1 8.834 0a2.528 2.528 0 0 1 2.521 2.522v2.52H8.834zM8.834 6.313a2.528 2.528 0 0 1 2.521 2.521 2.528 2.528 0 0 1-2.521 2.521H2.522A2.528 2.528 0 0 1 0 8.834a2.528 2.528 0 0 1 2.522-2.521h6.312zM18.956 8.834a2.528 2.528 0 0 1 2.522-2.521A2.528 2.528 0 0 1 24 8.834a2.528 2.528 0 0 1-2.522 2.521h-2.522V8.834zM17.688 8.834a2.528 2.528 0 0 1-2.523 2.521 2.527 2.527 0 0 1-2.52-2.521V2.522A2.527 2.527 0 0 1 15.165 0a2.528 2.528 0 0 1 2.523 2.522v6.312zM15.165 18.956a2.528 2.528 0 0 1 2.523 2.522A2.528 2.528 0 0 1 15.165 24a2.527 2.527 0 0 1-2.52-2.522v-2.522h2.52zM15.165 17.688a2.527 2.527 0 0 1-2.52-2.523 2.526 2.526 0 0 1 2.52-2.52h6.313A2.527 2.527 0 0 1 24 15.165a2.528 2.528 0 0 1-2.522 2.523h-6.313z"/>
              </svg>

              <span class="font-medium text-sm text-zinc-800">{{
                  __('Slack')
                }}</span>
            </div>
          </div>

          <span v-if="slack.active"
                class="inline-flex items-center gap-x-1.5 rounded-md bg-green-100 px-2 py-1 text-xs font-medium text-green-700">
            <svg aria-hidden="true" class="h-1.5 w-1.5 fill-green-500" viewBox="0 0 6 6">
              <circle cx="3" cy="3" r="3"/>
            </svg>
            {{ __('Connect') }}
          </span>

          <span v-else
                class="inline-flex items-center gap-x-1.5 rounded-md bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800">
            <svg aria-hidden="true" class="h-1.5 w-1.5 fill-yellow-500" viewBox="0 0 6 6">
              <circle cx="3" cy="3" r="3"/>
            </svg>
            {{ __('Not connected') }}
          </span>
        </div>
      </div>

    </Card>
  </AdminLayout>
</template>

<style scoped></style>
