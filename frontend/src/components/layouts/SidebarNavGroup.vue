<script setup lang="ts">
import { computed } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import type { NavGroup } from '@/types/navigation'
import { useSidebar } from '@/composables/useSidebar'
import SidebarNavItem from './SidebarNavItem.vue'

const props = defineProps<{
  group: NavGroup
  groupKey: string
}>()

const { isCollapsed, toggleGroup, isGroupExpanded } = useSidebar()

const isExpanded = computed(() => isGroupExpanded(props.groupKey))
</script>

<template>
  <div class="w-full">
    <button
      @click="toggleGroup(groupKey)"
      class="flex w-full items-center gap-3 p-4 rounded-md transition-all duration-300 text-warm-sand hover:bg-deep-forest"
      :class="isCollapsed ? 'justify-center' : 'justify-around'"
    >
      <FontAwesomeIcon :icon="group.icon" />
      <span v-if="!isCollapsed" class="flex-1 text-left">{{ group.label }}</span>
      <FontAwesomeIcon
        v-if="!isCollapsed"
        :icon="isExpanded ? 'chevron-up' : 'chevron-down'"
        class="transition-transform duration-300"
        :class="isExpanded ? 'rotate-180' : ''"
      />
    </button>

    <div
      v-if="isExpanded && !isCollapsed"
      class="ml-4 mt-1 flex flex-col gap-1 overflow-hidden transition-all duration-300"
    >
      <SidebarNavItem
        v-for="item in group.items"
        :key="item.to"
        :item="item"
      />
    </div>
  </div>
</template>