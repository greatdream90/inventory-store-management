<template>
  <div id="app">
    <div class="d-flex">
      <!-- Sidebar -->
      <nav class="sidebar" :class="{ show: showSidebar }">
        <div class="p-3">
          <div class="d-flex align-items-center mb-4">
            <i class="bi bi-shop display-6 me-2"></i>
            <h5 class="mb-0">Inventory Store</h5>
          </div>
          
          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <router-link to="/" class="nav-link" :class="{ active: $route.name === 'Dashboard' }">
                <i class="bi bi-speedometer2"></i>
                แดชบอร์ด
              </router-link>
            </li>
            
            <li class="nav-item">
              <router-link to="/pos" class="nav-link" :class="{ active: $route.name === 'POS' }" v-if="authStore.hasPermission('sales.create')">
                <i class="bi bi-cart-plus"></i>
                ขายสินค้า (POS)
              </router-link>
            </li>
            
            <!-- Products Management -->
            <li class="nav-item" v-if="authStore.hasPermission('products.view')">
              <a class="nav-link" data-bs-toggle="collapse" href="#productsMenu" role="button">
                <i class="bi bi-box-seam"></i>
                จัดการสินค้า
                <i class="bi bi-chevron-down ms-auto"></i>
              </a>
              <div class="collapse" id="productsMenu">
                <ul class="nav nav-pills flex-column ms-3">
                  <li class="nav-item">
                    <router-link to="/products" class="nav-link" :class="{ active: $route.name === 'Products' }">
                      <i class="bi bi-list-ul"></i>
                      รายการสินค้า
                    </router-link>
                  </li>
                  <li class="nav-item">
                    <router-link to="/categories" class="nav-link" :class="{ active: $route.name === 'Categories' }">
                      <i class="bi bi-tags"></i>
                      หมวดหมู่สินค้า
                    </router-link>
                  </li>
                  <li class="nav-item" v-if="authStore.hasPermission('inventory.manage')">
                    <router-link to="/inventory" class="nav-link" :class="{ active: $route.name === 'Inventory' }">
                      <i class="bi bi-boxes"></i>
                      จัดการสต๊อก
                    </router-link>
                  </li>
                </ul>
              </div>
            </li>
            
            <li class="nav-item" v-if="authStore.hasPermission('customers.view')">
              <router-link to="/customers" class="nav-link" :class="{ active: $route.name === 'Customers' }">
                <i class="bi bi-people"></i>
                ลูกค้า
              </router-link>
            </li>
            
            <li class="nav-item" v-if="authStore.hasPermission('sales.view')">
              <router-link to="/sales" class="nav-link" :class="{ active: $route.name === 'Sales' }">
                <i class="bi bi-receipt"></i>
                ประวัติการขาย
              </router-link>
            </li>
            
            <li class="nav-item" v-if="authStore.hasPermission('reports.view')">
              <router-link to="/reports" class="nav-link" :class="{ active: $route.name === 'Reports' }">
                <i class="bi bi-graph-up"></i>
                รายงาน
              </router-link>
            </li>
            
            <hr class="my-3">
            
            <li class="nav-item">
              <router-link to="/notifications" class="nav-link" :class="{ active: $route.name === 'Notifications' }">
                <i class="bi bi-bell"></i>
                การแจ้งเตือน
                <span class="badge bg-danger ms-auto" v-if="notificationCount > 0">{{ notificationCount }}</span>
              </router-link>
            </li>
            
            <li class="nav-item" v-if="authStore.hasRole('admin')">
              <router-link to="/users" class="nav-link" :class="{ active: $route.name === 'Users' }">
                <i class="bi bi-person-gear"></i>
                ผู้ใช้งาน
              </router-link>
            </li>
            
            <li class="nav-item" v-if="authStore.hasRole('admin')">
              <router-link to="/settings" class="nav-link" :class="{ active: $route.name === 'Settings' }">
                <i class="bi bi-gear"></i>
                ตั้งค่าระบบ
              </router-link>
            </li>
          </ul>
        </div>
      </nav>
      
      <!-- Main Content -->
      <div class="flex-fill">
        <!-- Top Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
          <div class="container-fluid">
            <button class="btn btn-outline-primary d-lg-none" @click="toggleSidebar">
              <i class="bi bi-list"></i>
            </button>
            
            <div class="navbar-nav ms-auto">
              <!-- Notifications Dropdown -->
              <div class="nav-item dropdown">
                <a class="nav-link position-relative" href="#" data-bs-toggle="dropdown">
                  <i class="bi bi-bell fs-5"></i>
                  <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
                        v-if="notificationCount > 0">
                    {{ notificationCount }}
                  </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><h6 class="dropdown-header">การแจ้งเตือน</h6></li>
                  <li v-if="notificationCount === 0">
                    <span class="dropdown-item-text text-muted">ไม่มีการแจ้งเตือน</span>
                  </li>
                  <li v-else v-for="notification in recentNotifications" :key="notification.id">
                    <a class="dropdown-item" href="#">
                      <div class="d-flex">
                        <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                        <div>
                          <div class="fw-bold">{{ notification.title }}</div>
                          <small class="text-muted">{{ notification.message }}</small>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li><hr class="dropdown-divider"></li>
                  <li>
                    <router-link to="/notifications" class="dropdown-item text-center">
                      ดูการแจ้งเตือนทั้งหมด
                    </router-link>
                  </li>
                </ul>
              </div>
              
              <!-- User Dropdown -->
              <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                  <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
                       style="width: 32px; height: 32px;">
                    <i class="bi bi-person-fill text-white"></i>
                  </div>
                  {{ authStore.userName }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><h6 class="dropdown-header">{{ authStore.userRole }} Account</h6></li>
                  <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>โปรไฟล์</a></li>
                  <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>การตั้งค่า</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li>
                    <a class="dropdown-item text-danger" href="#" @click="handleLogout">
                      <i class="bi bi-box-arrow-right me-2"></i>ออกจากระบบ
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </nav>
        
        <!-- Page Content -->
        <main class="p-4">
          <div class="container-fluid">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <router-link to="/" class="text-decoration-none">หน้าหลัก</router-link>
                </li>
                <li class="breadcrumb-item active" v-if="$route.meta.title">
                  {{ $route.meta.title }}
                </li>
              </ol>
            </nav>
            
            <!-- Page Title -->
            <div class="d-flex justify-content-between align-items-center mb-4" v-if="$route.meta.title">
              <h2 class="mb-0">{{ $route.meta.title }}</h2>
              <div class="text-muted">
                {{ currentDateTime }}
              </div>
            </div>
            
            <!-- Router View -->
            <router-view />
          </div>
        </main>
      </div>
    </div>
    
    <!-- Sidebar Overlay for Mobile -->
    <div class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-lg-none" 
         v-if="showSidebar" 
         @click="closeSidebar"
         style="z-index: 999;"></div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useToast } from 'vue-toastification'

const router = useRouter()
const authStore = useAuthStore()
const toast = useToast()

const showSidebar = ref(false)
const currentDateTime = ref('')
const notificationCount = ref(0)
const recentNotifications = ref([])

// Methods
function toggleSidebar() {
  showSidebar.value = !showSidebar.value
}

function closeSidebar() {
  showSidebar.value = false
}

async function handleLogout() {
  await authStore.logout()
  router.push('/login')
}

function updateDateTime() {
  const now = new Date()
  currentDateTime.value = now.toLocaleString('th-TH', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

// Initialize
onMounted(() => {
  updateDateTime()
  setInterval(updateDateTime, 60000) // Update every minute
  
  // TODO: Fetch notifications
  // fetchNotifications()
})
</script>