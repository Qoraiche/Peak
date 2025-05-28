<script setup>
import {computed} from 'vue'
import {usePage} from '@inertiajs/vue3'
import WarningAlert from "@/Themes/Breeze/Components/Alerts/WarningAlert.vue";

const errors = computed(() => usePage().props.errors)

const formattedErrors = computed(() => {
  const list = [];

  if (!errors.value || Object.keys(errors.value).length === 0) return list

  Object.values(errors.value).forEach(error => {
    if (Array.isArray(error)) {
      list.push(...error)
    } else if (typeof error === 'string') {
      list.push(error)
    } else if (typeof error === 'object' && error !== null) {
      list.push(...Object.values(error))
    }
  })

  return list.flat()
})
</script>

<template>
  <WarningAlert v-if="formattedErrors.length" class="mb-3">
    <ul class="list-disc list-inside space-y-1">
      <li v-for="(err, i) in formattedErrors" :key="i">{{ err }}</li>
    </ul>
  </WarningAlert>
</template>
