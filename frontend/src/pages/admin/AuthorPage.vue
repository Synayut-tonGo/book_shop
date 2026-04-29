<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { authorService } from '@/services/modelService'
import DataTable from '@/components/common/DataTable.vue'
import Modal from '@/components/common/Modal.vue'
import { API_IMAGE_URL } from '@/utils/constants'

interface AuthorResponse {
  author_id: number
  first_name?: string
  last_name?: string
  full_name?: string
  phone?: string
  email?: string
  dob?: string
  age?: number
  code?: string
  status?: string
  created_at?: string
  updated_at?: string
}

const authors = ref<AuthorResponse[]>([])
const loading = ref(false)
const error = ref<string | null>(null)

const showModal = ref(false)
const editingAuthor = ref<AuthorResponse | null>(null)
const selectedAuthors = ref<AuthorResponse[]>([])

const formData = ref({
  first_name: '',
  last_name: '',
  phone: '',
  email: '',
  dob: '',
  status: 'active',
  image: null as File | null,
})

const imagePreview = ref<string | null>(null)

const columns = [
  { key: 'image', label: 'Photo' },
  { key: 'author_id', label: 'ID' },
  { key: 'code', label: 'Code' },
  { key: 'full_name', label: 'Name' },
  { key: 'email', label: 'Email' },
  { key: 'phone', label: 'Phone' },
  { key: 'status', label: 'Status' },
]

const fetchAuthors = async () => {
  loading.value = true
  error.value = null
  try {
    const res = await authorService.getAll()
    const data = res.data.data
    authors.value = Array.isArray(data) ? data : (data?.data || [])
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to fetch authors'
    authors.value = []
  } finally {
    loading.value = false
  }
}

const generateCode = () => {
  return 'ATH-' + Date.now()
}

const openCreate = () => {
  editingAuthor.value = null
  formData.value = {
    first_name: '',
    last_name: '',
    phone: '',
    email: '',
    dob: '',
    status: 'active',
    image: null,
  }
  imagePreview.value = null
  showModal.value = true
}

const openEdit = (author: AuthorResponse) => {
  editingAuthor.value = author
  formData.value = {
    first_name: author.first_name || '',
    last_name: author.last_name || '',
    phone: author.phone || '',
    email: author.email || '',
    dob: author.dob ? author.dob.split('T')[0] : '',
    code: author.code || '',
    status: author.status || 'active',
    image: null,
  }
  imagePreview.value = author.image ? API_IMAGE_URL + author.image : null
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
    data.append('first_name', formData.value.first_name)
    data.append('last_name', formData.value.last_name)
    data.append('phone', formData.value.phone)
    data.append('email', formData.value.email)
    data.append('dob', formData.value.dob)
    data.append('code', formData.value.code)
    data.append('status', formData.value.status)
    if (formData.value.image) {
      data.append('image', formData.value.image)
    }

    if (editingAuthor.value) {
      await authorService.update(editingAuthor.value.author_id, data as any)
    } else {
      await authorService.create(data as any)
    }
    showModal.value = false
    await fetchAuthors()
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Operation failed'
  } finally {
    loading.value = false
  }
}

const handleDelete = async (author: AuthorResponse) => {
  if (!confirm(`Delete "${author.full_name}"?`)) return
  
  loading.value = true
  try {
    await authorService.delete(author.author_id)
    await fetchAuthors()
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Delete failed'
  } finally {
    loading.value = false
  }
}

const handleBulkDelete = async () => {
  if (!confirm(`Delete ${selectedAuthors.value.length} authors?`)) return
  
  loading.value = true
  try {
    const ids = selectedAuthors.value.map(a => a.author_id)
    await authorService.deleteMultiple(ids)
    selectedAuthors.value = []
    await fetchAuthors()
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Delete failed'
  } finally {
    loading.value = false
  }
}

onMounted(fetchAuthors)
</script>

<template>
  <div class="p-4">
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-2xl font-bold text-moss-green">Author Management</h1>
      <div class="flex gap-2">
        <button 
          v-if="selectedAuthors.length > 0"
          @click="handleBulkDelete"
          class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
        >
          Delete ({{ selectedAuthors.length }})
        </button>
        <button 
          @click="openCreate"
          class="px-4 py-2 bg-moss-green text-white rounded hover:bg-green-700"
        >
          Create Author
        </button>
      </div>
    </div>

    <div v-if="loading" class="mb-4 p-4 text-center text-gray-500">
      Loading...
    </div>

    <div v-if="error" class="mb-4 p-3 bg-red-100 text-red-700 rounded">
      {{ error }}
    </div>

    <div v-if="!loading && authors.length === 0" class="mb-4 p-4 text-center text-gray-500 bg-gray-100 rounded">
      No authors found. Click "Create Author" to add one.
    </div>

    <div v-if="!loading && authors.length > 0">
      <DataTable
        :columns="columns"
        :data="authors"
        selectable
        @edit="openEdit"
        @delete="handleDelete"
        @selection-change="selectedAuthors = $event"
      >
        <template #image="{ row }">
          <img 
            v-if="row.image" 
            :src="API_IMAGE_URL + row.image" 
            alt="Author" 
            class="w-10 h-10 object-cover rounded-full"
          />
          <div v-else class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center text-gray-500 text-xs">
            No
          </div>
        </template>
      </DataTable>
    </div>

    <Modal :show="showModal" :title="editingAuthor ? 'Edit Author' : 'Create Author'" @close="showModal = false">
      <form @submit.prevent="handleSubmit" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium">First Name</label>
            <input v-model="formData.first_name" type="text" required class="w-full p-2 border rounded" />
          </div>
          <div>
            <label class="block text-sm font-medium">Last Name</label>
            <input v-model="formData.last_name" type="text" required class="w-full p-2 border rounded" />
          </div>
        </div>
        
        <div>
          <label class="block text-sm font-medium">Email</label>
          <input v-model="formData.email" type="email" required class="w-full p-2 border rounded" />
        </div>
        
        <div>
          <label class="block text-sm font-medium">Phone</label>
          <input v-model="formData.phone" type="tel" required class="w-full p-2 border rounded" />
        </div>
        
        <div>
          <label class="block text-sm font-medium">Date of Birth</label>
          <input v-model="formData.dob" type="date" class="w-full p-2 border rounded" />
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
                alt="Author" 
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