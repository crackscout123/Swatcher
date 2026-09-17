import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const routes = [
  {
    path: '/login',
    name: 'Login',
    component: () => import('@/pages/auth/LoginPage.vue'),
    meta: { public: true }
  },
  {
    path: '/',
    redirect: '/dashboard'
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: () => import('@/pages/dashboard/DashboardPage.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/servers',
    name: 'ServerList',
    component: () => import('@/pages/servers/ServerListPage.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/servers/:id',
    name: 'ServerDetail',
    component: () => import('@/pages/servers/ServerDetailPage.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/alerts',
    name: 'AlertRules',
    component: () => import('@/pages/alerts/AlertRulesPage.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/settings',
    name: 'UserPreferences',
    component: () => import('@/pages/settings/UserPreferencesPage.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/settings',
    name: 'GlobalSettings',
    component: () => import('@/pages/settings/GlobalSettingsPage.vue'),
    meta: { requiresAuth: true, requiresRole: 'admin' }
  },
  {
    path: '/admin/users',
    name: 'UserManagement',
    component: () => import('@/pages/admin/UserManagementPage.vue'),
    meta: { requiresAuth: true, requiresRole: 'admin' }
  },
  {
    path: '/admin/audit-log',
    name: 'AuditLog',
    component: () => import('@/pages/admin/AuditLogPage.vue'),
    meta: { requiresAuth: true, requiresRole: 'admin' }
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: () => import('@/pages/NotFoundPage.vue')
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach(async (to) => {
  const auth = useAuthStore()

  if (!to.meta.public && !auth.isAuthenticated) {
    await auth.fetchUser().catch(() => {})
  }

  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    return { name: 'Login', query: { redirect: to.fullPath } }
  }

  if (to.meta.requiresRole && !auth.hasRole(to.meta.requiresRole)) {
    return { name: 'Dashboard' }
  }
})

export default router
