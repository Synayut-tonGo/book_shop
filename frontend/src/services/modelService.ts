import api from '@/utils/api'
import type { Book, CreateBook, Author, CreateAuthor, Category, CreateCategory } from '@/types/models'

export const bookService = {
    getAll: () => api.get('/admin/books/getAll'),
    
    getById: (id: number) => api.get(`/admin/books/getById/${id}`),
    
    create: (data: CreateBook) => {
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
        return api.post('/admin/books/createBook', formData)
    },
    
    update: (id: number, data: Partial<CreateBook>) => {
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
        return api.put(`/admin/books/updateBook/${id}`, formData)
    },
    
    delete: (id: number) => api.delete(`/admin/books/deleteById/${id}`),
    
    deleteMultiple: (ids: number[]) => api.delete('/admin/books/deleteMultiById', { data: ids }),
    
    deleteAll: () => api.delete('/admin/books/deleteAll'),
}

export const authorService = {
    getAll: () => api.get('/admin/authors/getAll'),
    
    getById: (id: number) => api.get(`/admin/authors/getById/${id}`),
    
    create: (data: CreateAuthor) => {
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
        return api.post('/admin/authors/createAuthor', formData)
    },
    
    update: (id: number, data: Partial<CreateAuthor>) => {
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
        return api.put(`/admin/authors/updateAuthor/${id}`, formData)
    },
    
    delete: (id: number) => api.delete(`/admin/authors/deleteById/${id}`),
    
    deleteMultiple: (ids: number[]) => api.delete('/admin/authors/deleteMultiById', { data: ids }),
    
    deleteAll: () => api.delete('/admin/authors/deleteAll'),
}

export const categoryService = {
    getAll: () => api.get('/admin/categories/getAll'),
    
    getById: (id: number) => api.get(`/admin/categories/getById/${id}`),
    
    create: (data: CreateCategory) => api.post('/admin/categories/createCategory', data),
    
    update: (id: number, data: Partial<CreateCategory>) => 
        api.put(`/admin/categories/updateCategory/${id}`, data),
    
    delete: (id: number) => api.delete(`/admin/categories/deleteById/${id}`),
    
    deleteMultiple: (ids: number[]) => api.delete('/admin/categories/deleteMultiById', { data: ids }),
    
    deleteAll: () => api.delete('/admin/categories/deleteAll'),
}