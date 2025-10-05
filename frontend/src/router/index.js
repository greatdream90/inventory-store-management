import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

// Layout
import DefaultLayout from '@/layouts/DefaultLayout.vue'
import AuthLayout from '@/layouts/AuthLayout.vue'

// Pages
import Login from '@/pages/Login.vue'
import Dashboard from '@/pages/Dashboard.vue'
import Products from '@/pages/Products.vue'
import Categories from '@/pages/Categories.vue'
import Customers from '@/pages/Customers.vue'
import Sales from '@/pages/Sales.vue'
import POS from '@/pages/POS.vue'
import Reports from '@/pages/Reports.vue'
import Inventory from '@/pages/Inventory.vue'
import Users from '@/pages/Users.vue'
import Settings from '@/pages/Settings.vue'
import Notifications from '@/pages/Notifications.vue'

const routes = [
  {
    path: '/login',
    component: AuthLayout,
    children: [
      {
        path: '',
        name: 'Login',
        component: Login,
        meta: { requiresGuest: true }
      }
    ]
  },
  {
    path: '/',
    component: DefaultLayout,
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        name: 'Dashboard',
        component: Dashboard,
        meta: { title: 'แดชบอร์ด' }
      },
      {
        path: '/products',
        name: 'Products',
        component: Products,
        meta: { title: 'จัดการสินค้า', permission: 'products.view' }
      },
      {
        path: '/categories',
        name: 'Categories',
        component: Categories,
        meta: { title: 'หมวดหมู่สินค้า', permission: 'categories.view' }
      },
      {
        path: '/customers',
        name: 'Customers',
        component: Customers,
        meta: { title: 'ลูกค้า', permission: 'customers.view' }
      },
      {
        path: '/sales',
        name: 'Sales',
        component: Sales,
        meta: { title: 'ประวัติการขาย', permission: 'sales.view' }
      },
      {
        path: '/pos',
        name: 'POS',
        component: POS,
        meta: { title: 'ขายสินค้า', permission: 'sales.create' }
      },
      {
        path: '/inventory',
        name: 'Inventory',
        component: Inventory,
        meta: { title: 'จัดการสต๊อก', permission: 'inventory.manage' }
      },
      {
        path: '/reports',
        name: 'Reports',
        component: Reports,
        meta: { title: 'รายงาน', permission: 'reports.view' }
      },
      {
        path: '/notifications',
        name: 'Notifications',
        component: Notifications,
        meta: { title: 'การแจ้งเตือน' }
      },
      {
        path: '/users',
        name: 'Users',
        component: Users,
        meta: { title: 'ผู้ใช้งาน', permission: 'users.manage', role: 'admin' }
      },
      {
        path: '/settings',
        name: 'Settings',
        component: Settings,
        meta: { title: 'ตั้งค่าระบบ', permission: 'settings.manage', role: 'admin' }
      }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

// Navigation guards
router.beforeEach(async (to, from, next) => {
  try {
    const authStore = useAuthStore()
    
    console.log('Navigating to:', to.name, 'Auth status:', authStore.isAuthenticated)
    
    // Check if route requires authentication
    if (to.matched.some(record => record.meta.requiresAuth)) {
      if (!authStore.isAuthenticated) {
        console.log('Redirecting to login - not authenticated')
        next({ name: 'Login' })
        return
      }
      
      // Check role-based access
      if (to.meta.role && !authStore.hasRole(to.meta.role)) {
        console.log('Redirecting to dashboard - insufficient role')
        next({ name: 'Dashboard' })
        return
      }
      
      // Check permission-based access
      if (to.meta.permission && !authStore.hasPermission(to.meta.permission)) {
        console.log('Redirecting to dashboard - insufficient permission')
        next({ name: 'Dashboard' })
        return
      }
    }
    
    // Check if route requires guest (not authenticated)
    if (to.matched.some(record => record.meta.requiresGuest)) {
      if (authStore.isAuthenticated) {
        console.log('Redirecting to dashboard - already authenticated')
        next({ name: 'Dashboard' })
        return
      }
    }
    
    console.log('Navigation allowed to:', to.name)
    next()
  } catch (error) {
    console.error('Router navigation error:', error)
    // Fallback to login on any error
    if (to.name !== 'Login') {
      next({ name: 'Login' })
    } else {
      next()
    }
  }
})

export default router