<template>
  <DashboardLayout :title="__('Dashboard')"
                   description="Welcome to an example application dashboard. Find more resources below.">
    <div class="relative flex min-h-svh flex-1 flex-col">
      <div class="flex h-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
          <div
              class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
            <svg class="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20"
                 fill="none">
              <defs>
                <pattern id="pattern-zwljcys" height="8" patternUnits="userSpaceOnUse" width="8" x="0"
                         y="0">
                  <path d="M-1 5L5 -1M3 9L8.5 3.5" stroke-width="0.5"></path>
                </pattern>
              </defs>
              <rect fill="url(#pattern-zwljcys)" height="100%" stroke="none" width="100%"></rect>
            </svg>
          </div>
          <div
              class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
            <svg class="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20"
                 fill="none">
              <defs>
                <pattern id="pattern-c0nc0dt" height="8" patternUnits="userSpaceOnUse" width="8" x="0"
                         y="0">
                  <path d="M-1 5L5 -1M3 9L8.5 3.5" stroke-width="0.5"></path>
                </pattern>
              </defs>
              <rect fill="url(#pattern-c0nc0dt)" height="100%" stroke="none" width="100%"></rect>
            </svg>
          </div>
          <div
              class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
            <svg class="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20"
                 fill="none">
              <defs>
                <pattern id="pattern-ar5aomd" height="8" patternUnits="userSpaceOnUse" width="8" x="0"
                         y="0">
                  <path d="M-1 5L5 -1M3 9L8.5 3.5" stroke-width="0.5"></path>
                </pattern>
              </defs>
              <rect fill="url(#pattern-ar5aomd)" height="100%" stroke="none" width="100%"></rect>
            </svg>
          </div>
        </div>

        <div
            class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
          <svg class="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20"
               fill="none">
            <defs>
              <pattern id="pattern-k3ie3f7" height="8" patternUnits="userSpaceOnUse" width="8" x="0"
                       y="0">
                <path d="M-1 5L5 -1M3 9L8.5 3.5" stroke-width="0.5"></path>
              </pattern>
            </defs>
            <rect fill="url(#pattern-k3ie3f7)" height="100%" stroke="none" width="100%"></rect>
          </svg>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>

import DashboardLayout from "@/Themes/Breeze/Layouts/Dashboard/DashboardLayout.vue";
</script>
