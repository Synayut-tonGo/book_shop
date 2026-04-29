<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import type { NavItem } from '@/types/navigation'
import { useSidebar } from '@/composables/useSidebar'

const props = defineProps<{
  item: NavItem
}>()

const route = useRoute()
const { isCollapsed } = useSidebar()

const isActive = computed(() => {
  if (!props.item.name) return false
  return route.name === props.item.name
})
</script>

<template>
  <RouterLink
    :to="item.to"
    class="flex items-center gap-3 p-3 rounded-md transition-all duration-300 text-warm-sand"
    :class="[
      isActive
        ? 'bg-deep-forest border-l-4 border-white'
        : 'hover:bg-deep-forest',
      isCollapsed ? 'justify-center' : 'justify-start'
    ]"
    :title="isCollapsed ? item.label : undefined"
  >
    <FontAwesomeIcon :icon="item.icon" />
    <span v-if="!isCollapsed" class="whitespace-nowrap">{{ item.label }}</span>
  </RouterLink>
</template>