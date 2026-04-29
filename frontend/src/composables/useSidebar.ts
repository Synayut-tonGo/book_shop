import { ref, watch } from 'vue'

const STORAGE_KEY = 'sidebar_collapsed'

const isCollapsed = ref<boolean>(false)
const expandedGroups = ref<Record<string, boolean>>({})

export function useSidebar() {
  const initFromStorage = () => {
    const stored = localStorage.getItem(STORAGE_KEY)
    if (stored !== null) {
      isCollapsed.value = stored === 'true'
    }
  }

  const toggleCollapse = () => {
    isCollapsed.value = !isCollapsed.value
  }

  const toggleGroup = (groupKey: string) => {
    expandedGroups.value[groupKey] = !expandedGroups.value[groupKey]
  }

  const isGroupExpanded = (groupKey: string): boolean => {
    return expandedGroups.value[groupKey] ?? false
  }

  watch(isCollapsed, (newVal) => {
    localStorage.setItem(STORAGE_KEY, String(newVal))
  })

  initFromStorage()

  return {
    isCollapsed,
    toggleCollapse,
    toggleGroup,
    isGroupExpanded,
  }
}