<script setup>
import AlertWarning from "@peak/Components/Admin/Alerts/AlertWarning.vue";
import {usePage} from "@inertiajs/vue3";
import {computed, defineAsyncComponent} from "vue";
import {widgetComponents} from "@/Layouts/Admin/HeaderWidgets/loader.js";

const page = usePage();

const widgets = computed(() => page.props.admin.header_widgets);

const resolvedWidgets = computed(() => {
  return widgets.value.map(widget => ({
    ...widget,
    component: widgetComponents[widget.component]
        ? defineAsyncComponent(widgetComponents[widget.component])
        : null
  }));
});
</script>

<template>
  <div class="flex gap-x-7 items-center">
    <template v-for="widget in resolvedWidgets" :key="widget.slug">
      <component
          :is="widget.component"
          v-if="widget.component"
          :icon="widget.icon"
          :title="widget.title"
          v-bind="widget.data"
      />

      <AlertWarning v-else>
        Missing Widget: {{ widget.component }}
      </AlertWarning>
    </template>
  </div>
</template>

<style scoped>
</style>
