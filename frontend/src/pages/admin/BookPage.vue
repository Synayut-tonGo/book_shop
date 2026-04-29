<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { bookService } from '@/services/modelService'
import type { Book } from '@/types/models'
import DataTable from '@/components/common/DataTable.vue'
import Modal from '@/components/common/Modal.vue'
import { API_IMAGE_URL } from '@/utils/constants'

const books = ref<Book[]>([])
const loading = ref(false)
const error = ref<string | null>(null)

const showModal = ref(false)
const editingBook = ref<Book | null>(null)
const selectedBooks = ref<Book[]>([])

const formData = ref({
  name: '',
  description: '',
  quantity: 0,
  discount: 0,
  status: 'active',
  image: null as File | null,
})

const imagePreview = ref<string | null>(null)

const columns = [
  { key: 'image', label: 'Cover' },
  { key: 'book_id', label: 'ID' },
  { key: 'code', label: 'Code' },
  { key: 'name', label: 'Name' },
  { key: 'quantity', label: 'Quantity' },
  { key: 'discount', label: 'Discount' },
  { key: 'status', label: 'Status' },
]

const fetchBooks = async () => {
  loading.value = true
  error.value = null
  try {
    const res = await bookService.getAll()
    const data = res.data.data
    books.value = Array.isArray(data) ? data : (data?.data || [])
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to fetch books'
    books.value = []
  } finally {
    loading.value = false
  }
}

const openCreate = () => {
  editingBook.value = null
  formData.value = {
    name: '',
    description: '',
    quantity: 0,
    discount: 0,
    status: 'active',
    image: null,
  }
  imagePreview.value = null
  showModal.value = true
}

const openEdit = (book: Book) => {
  editingBook.value = book
  formData.value = {
    name: book.name,
    description: book.description,
    quantity: book.quantity,
    discount: parseFloat(String(book.discount)) || 0,
    status: book.status,
    image: null,
  }
  imagePreview.value = book.image ? API_IMAGE_URL + book.image : null
  showModal.value = true
}

const handleImageChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (target.files && target.files[0]) {
    formData.value.image = target.files[0]
    imagePreview.value = URL.createObjectURL(target.files[0])
  }
}

const handleSubmit = async () => {
  loading.value = true
  try {
    const data = new FormData()
    data.append('name', formData.value.name)
    data.append('description', formData.value.description)
    data.append('quantity', String(formData.value.quantity))
    data.append('discount', String(formData.value.discount))
    data.append('status', formData.value.status)
    if (formData.value.image) {
      data.append('image', formData.value.image)
    }

    if (editingBook.value) {
      await bookService.update(editingBook.value.book_id, data as any)
    } else {
      await bookService.create(data as any)
    }
    showModal.value = false
    await fetchBooks()
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Operation failed'
  } finally {
    loading.value = false
  }
}

const handleDelete = async (book: Book) => {
  if (!confirm(`Delete "${book.name}"?`)) return
  
  loading.value = true
  try {
    await bookService.delete(book.book_id)
    await fetchBooks()
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Delete failed'
  } finally {
    loading.value = false
  }
}

const handleBulkDelete = async () => {
  if (!confirm(`Delete ${selectedBooks.value.length} books?`)) return
  
  loading.value = true
  try {
    const ids = selectedBooks.value.map(b => b.book_id)
    await bookService.deleteMultiple(ids)
    selectedBooks.value = []
    await fetchBooks()
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Delete failed'
  } finally {
    loading.value = false
  }
}

onMounted(fetchBooks)
</script>

<template>
  <div class="p-4">
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-2xl font-bold text-moss-green">Book Management</h1>
      <div class="flex gap-2">
        <button 
          v-if="selectedBooks.length > 0"
          @click="handleBulkDelete"
          class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
        >
          Delete ({{ selectedBooks.length }})
        </button>
        <button 
          @click="openCreate"
          class="px-4 py-2 bg-moss-green text-white rounded hover:bg-green-700"
        >
          Create Book
        </button>
      </div>
    </div>

    <div v-if="loading" class="mb-4 p-4 text-center text-gray-500">
      Loading...
    </div>

    <div v-if="error" class="mb-4 p-3 bg-red-100 text-red-700 rounded">
      {{ error }}
    </div>

    <div v-if="!loading && books.length === 0" class="mb-4 p-4 text-center text-gray-500 bg-gray-100 rounded">
      No books found. Click "Create Book" to add one.
    </div>

    <div v-if="!loading && books.length > 0">
      <DataTable
        :columns="columns"
        :data="books"
        selectable
        @edit="openEdit"
        @delete="handleDelete"
        @selection-change="selectedBooks = $event"
      >
        <template #image="{ row }">
          <img 
            v-if="row.image" 
            :src="API_IMAGE_URL + row.image" 
            alt="Book cover" 
            class="w-10 h-10 object-cover rounded"
          />
          <div v-else class="w-10 h-10 bg-gray-300 rounded flex items-center justify-center text-gray-500 text-xs">
            No
          </div>
        </template>
      </DataTable>
    </div>

    <Modal :show="showModal" :title="editingBook ? 'Edit Book' : 'Create Book'" @close="showModal = false">
      <form @submit.prevent="handleSubmit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium">Name</label>
          <input v-model="formData.name" type="text" required class="w-full p-2 border rounded" />
        </div>
        
        <div>
          <label class="block text-sm font-medium">Description</label>
          <textarea v-model="formData.description" required class="w-full p-2 border rounded" rows="3"></textarea>
        </div>
        
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium">Quantity</label>
            <input v-model.number="formData.quantity" type="number" required class="w-full p-2 border rounded" />
          </div>
          <div>
            <label class="block text-sm font-medium">Discount</label>
            <input v-model.number="formData.discount" type="number" step="0.01" class="w-full p-2 border rounded" />
          </div>
        </div>
        
        <div>
          <label class="block text-sm font-medium">Status</label>
          <select v-model="formData.status" class="w-full p-2 border rounded">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>
        
        <div>
          <label class="block text-sm font-medium">Image</label>
          <div class="mt-2">
            <div class="w-32 h-32 flex flex-col items-center justify-center bg-gray-300 rounded relative overflow-hidden">
              <img 
                v-if="imagePreview" 
                :src="imagePreview" 
                alt="Book cover" 
                class="w-full h-full object-cover"
              />
              <div v-else class="w-full h-full flex items-center justify-center text-gray-500">
                No image
              </div>
              <input type="file" accept="image/*" @change="handleImageChange" class="w-full h-full absolute opacity-0 cursor-pointer" />
            </div>
          </div>
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