import AdminLayout from '@/layouts/AdminLayout.vue'
import DefaultLayout from '@/layouts/DefaultLayout.vue'
import LoginPage from '@/pages/auth/LoginPage.vue'
import RegisterPage from '@/pages/auth/RegisterPage.vue'
import UserPage from '@/pages/admin/UserPage.vue'
import BookPage from '@/pages/admin/BookPage.vue'
import AuthorPage from '@/pages/admin/AuthorPage.vue'
import CategoryPage from '@/pages/admin/CategoryPage.vue'
import { createRouter, createWebHistory } from 'vue-router'
import authGuard from './guards/authGuard'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      name: 'login',
      path: '/login',
      component: LoginPage,
      meta: { guestOnly: true },
    },
    {
      name: 'register',
      path: '/register',
      component: RegisterPage,
      meta: { guestOnly: true },
    },
    {
      name: 'defaultLayout',
      path: '/',
      component: DefaultLayout,
      meta: { requiresAuth: true }
    },
    {
      name: 'admin',
      path: '/admin',
      component: AdminLayout,
      meta: { requiresAuth: true, requiresAdmin: true },
      redirect: { name: 'userPage' },
      children: [
        {
          name: 'userPage',
          path: 'users',
          component: UserPage,
        },
        {
          name: 'bookPage',
          path: 'books',
          component: BookPage,
        },
        {
          name: 'authorPage',
          path: 'authors',
          component: AuthorPage,
        },
        {
          name: 'categoryPage',
          path: 'categories',
          component: CategoryPage,
        },
      ]
    },
  ],
})

router.beforeEach(authGuard)

export default router