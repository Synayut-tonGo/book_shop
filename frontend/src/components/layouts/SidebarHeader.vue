<script setup lang="ts">
import { useSidebar } from '@/composables/useSidebar'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

defineProps<{
  title: string
  subtitle?: string
}>()

const { isCollapsed, toggleCollapse } = useSidebar()
</script>

<template>
  <header class="w-full bg-sage rounded-md text-center pt-6 pb-6 relative">
    <button
      @click="toggleCollapse"
      class="absolute top-3 right-2 p-2 text-warm-sand hover:text-white transition-colors"
      :title="isCollapsed ? 'Expand' : 'Collapse'"
    >
      <FontAwesomeIcon icon="bars" />
    </button>

    <div class="flex flex-col gap-2">
      <h1 class="text-4xl font-bold text-warm-sand">{{ title }}</h1>
      <h4 v-if="!isCollapsed && subtitle" class="text-xl text-warm-sand">{{ subtitle }}</h4>
    </div>
  </header>
</template>