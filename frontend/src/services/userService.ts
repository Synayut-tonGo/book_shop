import api from '@/utils/api'
import type { User, CreateUser } from '@/types/auth'

export const userService = {
    getAll: () => api.get('/admin/users/getAll'),
    
    getById: (id: number) => api.get(`/admin/users/getById/${id}`),
    
    create: (data: CreateUser) => {
        const formData = new FormData()
        Object.entries(data).forEach(([key, value]) => {
            if (value !== null && value !== undefined) {
                if (key === 'image' && value instanceof File) {
                    formData.append(key, value)
                } else if (key !== 'image') {
                    formData.append(key, String(value))
                }
            }
        })
        return api.post('/admin/users/createUser', formData)
    },
    
    update: (id: number, data: Partial<CreateUser>) => {
        const formData = new FormData()
        Object.entries(data).forEach(([key, value]) => {
            if (value !== null && value !== undefined) {
                if (key === 'image' && value instanceof File) {
                    formData.append(key, value)
                } else if (key !== 'image') {
                    formData.append(key, String(value))
                }
            }
        })
        return api.put(`/admin/users/updateUser/${id}`, formData)
    },
    
    delete: (id: number) => api.delete(`/admin/users/deleteById/${id}`),
    
    deleteMultiple: (ids: number[]) => api.delete('/admin/users/deleteMultiById', { data: ids }),
    
    deleteAll: () => api.delete('/admin/users/deleteAll'),
    
    assignRole: (userId: number, roleId: number) => 
        api.post(`/admin/users/${userId}/assign-role`, { role_id: roleId }),
}