<script setup lang="ts">
import { ref } from 'vue'
import { API_IMAGE_URL } from '@/utils/constants'

const props = defineProps<{
  columns: { key: string; label: string; sortable?: boolean }[]
  data: any[]
  selectable?: boolean
}>()

const emit = defineEmits<{
  (e: 'edit', row: any): void
  (e: 'delete', row: any): void
  (e: 'selection-change', rows: any[]): void
}>()

const selectedRows = ref<Set<number>>(new Set())

const getRowId = (row: any): number => {
  if (row.id) return row.id
  if (row.user_id) return row.user_id
  if (row.book_id) return row.book_id
  if (row.author_id) return row.author_id
  if (row.category_id) return row.category_id
  return 0
}

const getImageUrl = (imagePath: string | undefined): string => {
  if (!imagePath) return ''
  if (imagePath.startsWith('http')) return imagePath
  return API_IMAGE_URL + imagePath
}

const isImageColumn = (key: string, value: any): boolean => {
  return key.toLowerCase().includes('image') && typeof value === 'string' && value.length > 0
}

const toggleSelect = (row: any) => {
  const id = getRowId(row)
  if (selectedRows.value.has(id)) {
    selectedRows.value.delete(id)
  } else {
    selectedRows.value.add(id)
  }
  emit('selection-change', props.data.filter(row => selectedRows.value.has(getRowId(row))))
}

const toggleAll = () => {
  if (selectedRows.value.size === props.data.length) {
    selectedRows.value.clear()
  } else {
    selectedRows.value = new Set(props.data.map(row => getRowId(row)))
  }
  emit('selection-change', props.data.filter(row => selectedRows.value.has(getRowId(row))))
}

const isSelected = (row: any) => selectedRows.value.has(getRowId(row))
</script>

<template>
  <div class="overflow-x-auto w-full">
    <table class="w-full border-collapse border border-gray-300">
      <thead>
        <tr class="bg-moss-green text-white">
          <th v-if="selectable" class="p-3 text-left border border-gray-300">
            <input 
              type="checkbox" 
              @change="toggleAll"
              :checked="selectedRows.size === data.length && data.length > 0"
              class="w-4 h-4"
            />
          </th>
          <th 
            v-for="col in columns" 
            :key="col.key" 
            class="p-3 text-left border border-gray-300"
          >
            {{ col.label }}
          </th>
          <th class="p-3 text-center border border-gray-300">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr 
          v-for="row in data" 
          :key="getRowId(row)"
          class="border border-gray-300 hover:bg-gray-100"
          :class="isSelected(row) ? 'bg-green-100' : ''"
        >
          <td v-if="selectable" class="p-3 border border-gray-300">
            <input 
              type="checkbox" 
              :checked="isSelected(row)"
              @change="toggleSelect(row)"
              class="w-4 h-4"
            />
          </td>
          <td 
            v-for="col in columns" 
            :key="col.key" 
            class="p-3 border border-gray-300 text-gray-800"
          >
            <template v-if="isImageColumn(col.key, row[col.key])">
              <img 
                v-if="row[col.key]"
                :src="getImageUrl(row[col.key])" 
                :alt="col.label"
                class="w-16 h-16 object-cover rounded"
              />
              <span v-else class="text-gray-400">No image</span>
            </template>
            <slot v-else :name="col.key" :row="row">
              {{ row[col.key] }}
            </slot>
          </td>
          <td class="p-3 text-center border border-gray-300">
            <button 
              @click="emit('edit', row)" 
              class="px-3 py-1 mx-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm"
            >
              Edit
            </button>
            <button 
              @click="emit('delete', row)" 
              class="px-3 py-1 mx-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm"
            >
              Delete
            </button>
          </td>
        </tr>
        <tr v-if="data.length === 0">
          <td :colspan="columns.length + (selectable ? 2 : 1)" class="p-4 text-center text-gray-500">
            No data available
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>