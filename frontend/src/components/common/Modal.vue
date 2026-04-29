<script setup lang="ts">
import { ref, watch } from 'vue'

const props = defineProps<{
  show: boolean
  title: string
  size?: 'sm' | 'md' | 'lg'
}>()

const emit = defineEmits<{
  (e: 'close'): void
}>()

const sizeClasses = {
  sm: 'max-w-md',
  md: 'max-w-2xl',
  lg: 'max-w-4xl',
}

const close = () => {
  emit('close')
}
</script>

<template>
  <Teleport to="body">
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-black/50" @click="close"></div>
      <div 
        class="relative bg-white rounded-lg shadow-xl mx-4"
        :class="sizeClasses[size || 'md']"
      >
        <div class="flex items-center justify-between p-4 border-b">
          <h2 class="text-xl font-semibold">{{ title }}</h2>
          <button @click="close" class="text-gray-500 hover:text-gray-700">
            ✕
          </button>
        </div>
        <div class="p-4 max-h-[70vh] overflow-y-auto">
          <slot></slot>
        </div>
        <div class="flex justify-end gap-2 p-4 border-t">
          <slot name="footer">
            <button @click="close" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
              Cancel
            </button>
          </slot>
        </div>
      </div>
    </div>
  </Teleport>
</template>