import { computed } from 'vue'
import { faUsers, faBook, faUser, faPen, faTags } from '@fortawesome/free-solid-svg-icons'
import type { NavGroup } from '@/types/navigation'
import { useAuthStore } from '@/stores/auth'

export function useNavigation() {
  const authStore = useAuthStore()

  const navigationGroups = computed<NavGroup[]>(() => {
    const groups: NavGroup[] = []

    if (authStore.isSuperAdmin) {
      groups.push({
        label: 'User Management',
        icon: faUsers,
        items: [
          { label: 'User', icon: faUser, to: '/admin/users', name: 'userPage' },
        ],
      })
    }

    if (authStore.isAdminOrSuper) {
      groups.push({
        label: 'Book Management',
        icon: faBook,
        items: [
          { label: 'Book', icon: faBook, to: '/admin/books', name: 'bookPage' },
        ],
      })

      groups.push({
        label: 'Author Management',
        icon: faPen,
        items: [
          { label: 'Author', icon: faUser, to: '/admin/authors', name: 'authorPage' },
        ],
      })

      groups.push({
        label: 'Category Management',
        icon: faTags,
        items: [
          { label: 'Category', icon: faTags, to: '/admin/categories', name: 'categoryPage' },
        ],
      })
    }

    return groups
  })

  return {
    navigationGroups,
  }
}