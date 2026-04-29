<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { categoryService } from '@/services/modelService'
import type { Category, CreateCategory } from '@/types/models'
import DataTable from '@/components/common/DataTable.vue'
import Modal from '@/components/common/Modal.vue'

const categories = ref<Category[]>([])
const loading = ref(false)
const error = ref<string | null>(null)

const showModal = ref(false)
const editingCategory = ref<Category | null>(null)
const selectedCategories = ref<Category[]>([])

const formData = ref<CreateCategory>({
  name: '',
  code: '',
  status: 'active',
})

const columns = [
  { key: 'category_id', label: 'ID' },
  { key: 'code', label: 'Code' },
  { key: 'name', label: 'Name' },
  { key: 'status', label: 'Status' },
]

const fetchCategories = async () => {
  loading.value = true
  error.value = null
  try {
    const res = await categoryService.getAll()
    console.log('Categories API Response:', res.data)
    const data = res.data.data
    categories.value = Array.isArray(data) ? data : (data?.data || [])
    console.log('Categories loaded:', categories.value)
  } catch (err: any) {
    console.error('API Error:', err)
    error.value = err.response?.data?.message || 'Failed to fetch categories'
    categories.value = []
  } finally {
    loading.value = false
  }
}

const openCreate = () => {
  editingCategory.value = null
  formData.value = { name: '', code: '', status: 'active' }
  showModal.value = true
}

const openEdit = (category: Category) => {
  editingCategory.value = category
  formData.value = { name: category.name, code: category.code, status: category.status }
  showModal.value = true
}

const handleSubmit = async () => {
  loading.value = true
  try {
    if (editingCategory.value) {
      await categoryService.update(editingCategory.value.category_id, formData.value)
    } else {
      await categoryService.create(formData.value)
    }
    showModal.value = false
    await fetchCategories()
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Operation failed'
  } finally {
    loading.value = false
  }
}

const handleDelete = async (category: Category) => {
  if (!confirm(`Delete "${category.name}"?`)) return
  
  loading.value = true
  try {
    await categoryService.delete(categorycategory_id)
    await fetchCategories()
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Delete failed'
  } finally {
    loading.value = false
  }
}

const handleBulkDelete = async () => {
  if (!confirm(`Delete ${selectedCategories.value.length} categories?`)) return
  
  loading.value = true
  try {
    const ids = selectedCategories.value.map(c => ccategory_id)
    await categoryService.deleteMultiple(ids)
    selectedCategories.value = []
    await fetchCategories()
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Delete failed'
  } finally {
    loading.value = false
  }
}

onMounted(fetchCategories)
</script>

<template>
  <div class="p-4">
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-2xl font-bold text-moss-green">Category Management</h1>
      <div class="flex gap-2">
        <button 
          v-if="selectedCategories.length > 0"
          @click="handleBulkDelete"
          class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
        >
          Delete ({{ selectedCategories.length }})
        </button>
        <button 
          @click="openCreate"
          class="px-4 py-2 bg-moss-green text-white rounded hover:bg-green-700"
        >
          Create Category
        </button>
      </div>
    </div>

    <div v-if="loading" class="mb-4 p-4 text-center text-gray-500">
      Loading...
    </div>

    <div v-if="error" class="mb-4 p-3 bg-red-100 text-red-700 rounded">
      {{ error }}
    </div>

    <div v-if="!loading && categories.length === 0" class="mb-4 p-4 text-center text-gray-500 bg-gray-100 rounded">
      No categories found. Click "Create Category" to add one.
    </div>

    <div v-if="!loading && categories.length > 0">
      <DataTable
        :columns="columns"
        :data="categories"
        selectable
        @edit="openEdit"
        @delete="handleDelete"
        @selection-change="selectedCategories = $event"
      />
    </div>

    <Modal :show="showModal" :title="editingCategory ? 'Edit Category' : 'Create Category'" @close="showModal = false">
      <form @submit.prevent="handleSubmit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium">Name</label>
          <input v-model="formData.name" type="text" required class="w-full p-2 border rounded" />
        </div>
        
        <div>
          <label class="block text-sm font-medium">Code</label>
          <input v-model="formData.code" type="text" required class="w-full p-2 border rounded" />
        </div>
        
        <div>
          <label class="block text-sm font-medium">Status</label>
          <select v-model="formData.status" class="w-full p-2 border rounded">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>
        
        <div class="flex justify-end gap-2 pt-4">
          <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-300 rounded">
            Cancel
          </button>
          <button type="submit" :disabled="loading" class="px-4 py-2 bg-moss-green text-white rounded">
            {{ loading ? 'Saving...' : 'Save' }}
          </button>
        </div>
      </form>
    </Modal>
  </div>
</template>