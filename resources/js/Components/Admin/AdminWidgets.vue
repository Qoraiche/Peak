<script setup>
import {computed, defineAsyncComponent} from "vue";
import {usePage} from "@inertiajs/vue3";
import {widgetComponents} from "@/Layouts/Admin/Widgets/loader";
import AlertWarning from "@peak/Components/Admin/Alerts/AlertWarning.vue";

const page = usePage();
const widgets = computed(() => page.props.admin.widgets);

const resolveComponent = (componentName) => {
  return widgetComponents[componentName]
      ? defineAsyncComponent(widgetComponents[componentName])
      : null;
};
</script>

<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <template v-for="widget in widgets" :key="widget.slug">
      <component
          :is="resolveComponent(widget.component)"
          v-if="resolveComponent(widget.component)"
          v-bind="{ icon: widget.icon, lazy: widget.lazy, title: widget.title, ...widget.data }"
      />
      <AlertWarning v-else>
        Missing Widget: {{ widget.component }}
      </AlertWarning>
    </template>
  </div>
</template>
