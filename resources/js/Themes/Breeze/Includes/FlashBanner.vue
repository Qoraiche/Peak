<script setup>
import {usePage} from '@inertiajs/vue3';
import AlertWarning from '@/Themes/Breeze/Components/Alerts/WarningAlert.vue';
import AlertError from '@/Themes/Breeze/Components/Alerts/ErrorAlert.vue';
import AlertInfo from '@/Themes/Breeze/Components/Alerts/InfoAlert.vue';
import AlertSuccess from '@/Themes/Breeze/Components/Alerts/SuccessAlert.vue';

const {flash} = usePage().props;

// Function to return the correct alert component based on the bannerStyle
const getAlertComponent = () => {
  switch (flash.bannerStyle) {
    case 'success':
      return AlertSuccess;
    case 'warning':
      return AlertWarning;
    case 'danger':
      return AlertError;
    case 'info':
      return AlertInfo;
    default:
      return null;
  }
};
</script>

<template>
  <div>
    <!-- Conditionally render the alert component based on the bannerStyle -->
    <component :is="getAlertComponent()" v-if="flash.banner" class="mb-3">
      {{ flash.banner }}
    </component>
  </div>
</template>

<style scoped>
/* Additional styles for your flash messages if needed */
</style>