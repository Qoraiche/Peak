<script setup>
import {computed, defineAsyncComponent} from "vue";
import {usePage} from "@inertiajs/vue3";
import {cardComponents} from "@/Layouts/Admin/Cards/loader";
import AlertWarning from "@peak/Components/Admin/Alerts/AlertWarning.vue";

const page = usePage();
const widgets = computed(() => page.props.admin.cards);

const resolveComponent = (componentName) =>
    cardComponents[componentName]
        ? defineAsyncComponent(cardComponents[componentName])
        : null;
</script>

<template>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <template v-for="card in widgets" :key="card.slug">

      <component
          :is="resolveComponent(card.component)"
          v-if="resolveComponent(card.component)"
          :collapsible="card.collapsible"
          :description="card.description"
          :lazy="card.lazy"
          :title="card.title"
          :view-more-route-name="card.viewMoreRouteName"
          v-bind="card.data"
      />

      <AlertWarning v-else>
        Missing Card: {{ card.component }}
      </AlertWarning>
    </template>
  </div>
</template>

<style scoped>

</style>
