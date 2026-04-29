<script setup lang="ts">
import { useAuthStore } from '@/stores/auth'
import { useSidebar } from '@/composables/useSidebar'
import { useNavigation } from '@/composables/useNavigation'
import { API_IMAGE_URL } from '@/utils/constants'
import SidebarHeader from './SidebarHeader.vue'
import SidebarNavGroup from './SidebarNavGroup.vue'

const authStore = useAuthStore()
const { isCollapsed } = useSidebar()
const { navigationGroups } = useNavigation()
</script>

<template>
  <div
    class="h-screen flex flex-col relative bg-moss-green transition-all duration-300"
    :class="isCollapsed ? 'w-16' : 'w-[240px]'"
  >
    <SidebarHeader title="EBOOK" subtitle="Admin Portal" />

    <nav class="flex-1 overflow-y-auto py-2">
      <SidebarNavGroup
        v-for="(group, index) in navigationGroups"
        :key="group.label"
        :group="group"
        :groupKey="`group-${index}`"
      />
    </nav>

    <div
      v-if="!isCollapsed"
      class="p-3 border-t border-sage/30"
    >
      <div class="flex items-center gap-3 p-2">
        <img
          :src="authStore.user?.image ? API_IMAGE_URL + authStore.user.image : 'https://via.placeholder.com/40'"
          alt="User"
          class="w-10 h-10 rounded-full object-cover"
        />
        <div class="flex-1 min-w-0">
          <p class="text-sm font-medium text-warm-sand truncate">
            {{ authStore.user?.full_name }}
          </p>
          <p class="text-xs text-sage truncate">
            {{ authStore.user?.roles.map(r => r.name).join(', ') }}
          </p>
        </div>
      </div>

      <button
        @click="authStore.logOut"
        class="w-full mt-2 p-2 bg-red-700 text-white rounded-md transition-colors hover:bg-red-800"
      >
        Logout
      </button>
    </div>

    <button
      v-else
      @click="authStore.logOut"
      class="p-2 mx-auto mb-2 text-red-500 hover:text-red-400"
      title="Logout"
    >
      <FontAwesomeIcon icon="right-from-bracket" />
    </button>
  </div>
</template>

<script lang="ts">
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
export default {
  components: { FontAwesomeIcon }
}
</script>