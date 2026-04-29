<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { userService } from '@/services/userService'
import type { User, CreateUser } from '@/types/auth'
import DataTable from '@/components/common/DataTable.vue'
import Modal from '@/components/common/Modal.vue'
import { API_IMAGE_URL } from '@/utils/constants'

const users = ref<User[]>([])
const loading = ref(false)
const error = ref<string | null>(null)

const showModal = ref(false)
const editingUser = ref<User | null>(null)
const selectedUsers = ref<User[]>([])

const imageFile = ref<File | null>(null)
const imagePreview = ref<string | null>(null)

const formData = ref<CreateUser>({
  first_name: '',
  last_name: '',
  phone: '',
  email: '',
  password: '',
  dob: new Date(),
  gender: 'male',
  image: null,
})

const onImageChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (target.files && target.files[0]) {
    imageFile.value = target.files[0]
    imagePreview.value = URL.createObjectURL(target.files[0])
  }
}

const columns = [
  { key: 'image', label: 'Photo' },
  { key: 'user_id', label: 'ID' },
  { key: 'full_name', label: 'Name' },
  { key: 'email', label: 'Email' },
  { key: 'phone', label: 'Phone' },
  { key: 'gender', label: 'Gender' },
  { key: 'status', label: 'Status' },
]

const fetchUsers = async () => {
  loading.value = true
  error.value = null
  try {
    const res = await userService.getAll()
    const data = res.data.data
    users.value = Array.isArray(data) ? data : (data?.data || [])
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to fetch users'
    users.value = []
  } finally {
    loading.value = false
  }
}

const openCreate = () => {
  editingUser.value = null
  formData.value = {
    first_name: '',
    last_name: '',
    phone: '',
    email: '',
    password: '',
    dob: new Date(),
    gender: 'male',
    image: null,
  }
  imageFile.value = null
  imagePreview.value = null
  showModal.value = true
}

const openEdit = (user: User) => {
  editingUser.value = user
  formData.value = {
    first_name: user.first_name,
    last_name: user.last_name,
    phone: user.phone,
    email: user.email,
    password: '',
    dob: new Date(user.dob),
    gender: user.gender as 'male' | 'female',
    image: null,
  }
  imageFile.value = null
  imagePreview.value = user.image ? API_IMAGE_URL + user.image : null
  showModal.value = true
}

const getUserImage = (image: string | undefined) => {
  if (!image) return ''
  if (image.startsWith('http')) return image
  return API_IMAGE_URL + image
}

const handleSubmit = async () => {
  loading.value = true
  try {
    const data = new FormData()
    data.append('first_name', formData.value.first_name)
    data.append('last_name', formData.value.last_name)
    data.append('phone', formData.value.phone)
    data.append('email', formData.value.email)
    data.append('dob', formData.value.dob.toString())
    data.append('gender', formData.value.gender)
    if (formData.value.password) {
      data.append('password', formData.value.password)
    }
    if (imageFile.value) {
      data.append('image', imageFile.value)
    }

    if (editingUser.value) {
      await userService.update(editingUser.value.user_id, data as any)
    } else {
      await userService.create(data as any)
    }
    showModal.value = false
    await fetchUsers()
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Operation failed'
  } finally {
    loading.value = false
  }
}

const handleDelete = async (user: User) => {
  if (!confirm(`Are you sure you want to delete ${user.full_name}?`)) return
  
  loading.value = true
  try {
    await userService.delete(user.user_id)
    await fetchUsers()
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Delete failed'
  } finally {
    loading.value = false
  }
}

const handleBulkDelete = async () => {
  if (!confirm(`Delete ${selectedUsers.value.length} users?`)) return
  
  loading.value = true
  try {
    const ids = selectedUsers.value.map(u => u.user_id)
    await userService.deleteMultiple(ids)
    selectedUsers.value = []
    await fetchUsers()
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Delete failed'
  } finally {
    loading.value = false
  }
}

onMounted(fetchUsers)
</script>

<template>
  <div class="p-4">
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-2xl font-bold text-moss-green">User Management</h1>
      <div class="flex gap-2">
        <button 
          v-if="selectedUsers.length > 0"
          @click="handleBulkDelete"
          class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
        >
          Delete ({{ selectedUsers.length }})
        </button>
        <button 
          @click="openCreate"
          class="px-4 py-2 bg-moss-green text-white rounded hover:bg-green-700"
        >
          Create User
        </button>
      </div>
    </div>

    <div v-if="loading" class="mb-4 p-4 text-center text-gray-500">
      Loading...
    </div>

    <div v-if="error" class="mb-4 p-3 bg-red-100 text-red-700 rounded">
      {{ error }}
    </div>

    <div v-if="!loading && users.length === 0" class="mb-4 p-4 text-center text-gray-500 bg-gray-100 rounded">
      No users found. Click "Create User" to add one.
    </div>

<div v-if="!loading && users.length > 0">
      <DataTable
        :columns="columns"
        :data="users"
        selectable
        @edit="openEdit"
        @delete="handleDelete"
        @selection-change="selectedUsers = $event"
      >
        <template #image="{ row }">
          <img 
            v-if="row.image" 
            :src="API_IMAGE_URL + row.image" 
            alt="User" 
            class="w-10 h-10 object-cover rounded-full"
          />
          <div v-else class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center text-gray-500 text-xs">
            No
          </div>
        </template>
      </DataTable>
    </div>

    <Modal :show="showModal" :title="editingUser ? 'Edit User' : 'Create User'" @close="showModal = false">
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
        
        <div v-if="!editingUser">
          <label class="block text-sm font-medium">Password</label>
          <input v-model="formData.password" type="password" class="w-full p-2 border rounded" />
        </div>
        
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium">Gender</label>
            <select v-model="formData.gender" class="w-full p-2 border rounded">
              <option value="male">Male</option>
              <option value="female">Female</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium">Date of Birth</label>
            <input v-model="formData.dob" type="date" class="w-full p-2 border rounded" />
          </div>
        </div>
        
        <div>
          <label class="block text-sm font-medium">Image</label>
          <div class="mt-2">
            <div class="w-32 h-32 flex flex-col items-center justify-center bg-gray-300 rounded-full relative overflow-hidden">
              <img 
                v-if="imagePreview" 
                :src="imagePreview" 
                alt="User" 
                class="w-full h-full object-cover"
              />
              <div v-else class="w-full h-full flex items-center justify-center text-gray-500">
                No image
              </div>
              <input type="file" accept="image/*" @change="onImageChange" class="w-full h-full absolute opacity-0 cursor-pointer" />
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