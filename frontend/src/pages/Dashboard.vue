<template>
  <div class="dashboard-page">
    <!-- Welcome Header -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="card bg-gradient-primary text-white">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="me-3">
                <i class="bi bi-house-door fs-1"></i>
              </div>
              <div>
                <h3 class="mb-1">ยินดีต้อนรับสู่ระบบจัดการคลังสินค้า</h3>
                <p class="mb-0 opacity-75">วันนี้ {{ formatDate(new Date()) }}</p>
                <small class="opacity-75">
                  <i class="bi bi-person me-1"></i>{{ authStore.userName }} ({{ authStore.userRole }})
                  {{ apiConfig.demoMode ? '- โหมดทดสอบ' : '' }}
                </small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
      <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white bg-primary">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="me-3">
                <i class="bi bi-box fs-2"></i>
              </div>
              <div>
                <h6 class="card-title mb-0">สินค้าทั้งหมด</h6>
                <h3 class="mb-0">{{ stats.totalProducts }}</h3>
                <small class="opacity-75">+5 จากเมื่อวาน</small>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white bg-success">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="me-3">
                <i class="bi bi-currency-dollar fs-2"></i>
              </div>
              <div>
                <h6 class="card-title mb-0">ยอดขายวันนี้</h6>
                <h3 class="mb-0">฿{{ stats.todaySales.toLocaleString() }}</h3>
                <small class="opacity-75">+12% จากเมื่อวาน</small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white bg-warning">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="me-3">
                <i class="bi bi-people fs-2"></i>
              </div>
              <div>
                <h6 class="card-title mb-0">ลูกค้าทั้งหมด</h6>
                <h3 class="mb-0">{{ stats.totalCustomers }}</h3>
                <small class="opacity-75">+8 คนใหม่</small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white bg-info">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="me-3">
                <i class="bi bi-graph-up fs-2"></i>
              </div>
              <div>
                <h6 class="card-title mb-0">คำสั่งซื้อ</h6>
                <h3 class="mb-0">{{ stats.totalOrders }}</h3>
                <small class="opacity-75">15 รอดำเนินการ</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
      <div class="col-lg-8 mb-3">
        <div class="card">
          <div class="card-header">
            <h5 class="mb-0">ยอดขาย 7 วันที่ผ่านมา</h5>
          </div>
          <div class="card-body">
            <div class="chart-container" style="height: 300px;">
              <canvas id="salesChart"></canvas>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-lg-4 mb-3">
        <div class="card">
          <div class="card-header">
            <h5 class="mb-0">สินค้าขายดี</h5>
          </div>
          <div class="card-body">
            <div class="list-group list-group-flush">
              <div v-for="product in topProducts" :key="product.id" class="list-group-item d-flex justify-content-between align-items-center px-0">
                <div>
                  <h6 class="mb-0">{{ product.name }}</h6>
                  <small class="text-muted">{{ product.category }}</small>
                </div>
                <span class="badge bg-primary rounded-pill">{{ product.sales }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions & Recent Activity -->
    <div class="row">
      <div class="col-lg-6 mb-3">
        <div class="card">
          <div class="card-header">
            <h5 class="mb-0">การดำเนินการด่วน</h5>
          </div>
          <div class="card-body">
            <div class="row g-2">
              <div class="col-6">
                <router-link to="/pos" class="btn btn-outline-primary w-100">
                  <i class="bi bi-calculator me-2"></i>
                  ขายสินค้า
                </router-link>
              </div>
              <div class="col-6">
                <router-link to="/products" class="btn btn-outline-success w-100">
                  <i class="bi bi-plus-circle me-2"></i>
                  เพิ่มสินค้า
                </router-link>
              </div>
              <div class="col-6">
                <router-link to="/inventory" class="btn btn-outline-warning w-100">
                  <i class="bi bi-box-seam me-2"></i>
                  จัดการสต๊อก
                </router-link>
              </div>
              <div class="col-6">
                <router-link to="/reports" class="btn btn-outline-info w-100">
                  <i class="bi bi-graph-up me-2"></i>
                  รายงาน
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-6 mb-3">
        <div class="card">
          <div class="card-header">
            <h5 class="mb-0">กิจกรรมล่าสุด</h5>
          </div>
          <div class="card-body" style="max-height: 300px; overflow-y: auto;">
            <div class="list-group list-group-flush">
              <div v-for="activity in recentActivities" :key="activity.id" class="list-group-item px-0">
                <div class="d-flex align-items-start">
                  <div class="me-3 mt-1">
                    <i class="bi" :class="activity.icon" :style="{ color: activity.color }"></i>
                  </div>
                  <div class="flex-grow-1">
                    <p class="mb-1">{{ activity.message }}</p>
                    <small class="text-muted">{{ formatDate(activity.timestamp) }}</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Low Stock Alert -->
    <div v-if="lowStockProducts.length > 0" class="row">
      <div class="col-12">
        <div class="alert alert-warning">
          <h5><i class="bi bi-exclamation-triangle me-2"></i>สินค้าเหลือน้อย</h5>
          <div class="row">
            <div v-for="product in lowStockProducts" :key="product.id" class="col-md-4">
              <div class="d-flex justify-content-between">
                <span>{{ product.name }}</span>
                <span class="badge bg-warning">{{ product.stock }} ชิ้น</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { apiConfig } from '@/config/app.js'

const authStore = useAuthStore()

// Mock data
const stats = ref({
  totalProducts: 156,
  todaySales: 45620,
  totalCustomers: 89,
  totalOrders: 234
})

const topProducts = ref([
  { id: 1, name: 'Laptop Dell', category: 'อิเลคทรอนิกส์', sales: 45 },
  { id: 2, name: 'T-Shirt Blue', category: 'เสื้อผ้า', sales: 32 },
  { id: 3, name: 'JavaScript Guide', category: 'หนังสือ', sales: 28 },
  { id: 4, name: 'Gaming Mouse', category: 'อิเลคทรอนิกส์', sales: 24 },
  { id: 5, name: 'Coffee Mug', category: 'ของใช้', sales: 19 }
])

const recentActivities = ref([
  {
    id: 1,
    message: 'ขายสินค้า Laptop Dell จำนวน 2 ชิ้น',
    timestamp: new Date(Date.now() - 5 * 60 * 1000),
    icon: 'bi-cart-check',
    color: '#28a745'
  },
  {
    id: 2,
    message: 'เพิ่มสินค้าใหม่ Wireless Keyboard',
    timestamp: new Date(Date.now() - 15 * 60 * 1000),
    icon: 'bi-plus-circle',
    color: '#007bff'
  },
  {
    id: 3,
    message: 'ปรับปรุงสต๊อก T-Shirt Blue เพิ่ม 50 ชิ้น',
    timestamp: new Date(Date.now() - 30 * 60 * 1000),
    icon: 'bi-box-seam',
    color: '#ffc107'
  },
  {
    id: 4,
    message: 'ลูกค้าใหม่ลงทะเบียน: นาย วิชัย ใจดี',
    timestamp: new Date(Date.now() - 45 * 60 * 1000),
    icon: 'bi-person-plus',
    color: '#17a2b8'
  }
])

const lowStockProducts = ref([
  { id: 1, name: 'Laptop Dell', stock: 2 },
  { id: 2, name: 'Gaming Mouse', stock: 5 },
  { id: 3, name: 'USB Cable', stock: 3 }
])

// Methods
function formatDate(date) {
  const now = new Date()
  const targetDate = new Date(date)
  const diffInMinutes = Math.floor((now - targetDate) / (1000 * 60))
  
  if (diffInMinutes < 1) {
    return 'เมื่อสักครู่'
  } else if (diffInMinutes < 60) {
    return `${diffInMinutes} นาทีที่แล้ว`
  } else if (diffInMinutes < 1440) {
    return `${Math.floor(diffInMinutes / 60)} ชั่วโมงที่แล้ว`
  } else {
    return targetDate.toLocaleDateString('th-TH')
  }
}

onMounted(() => {
  // Initialize chart (would use Chart.js in real implementation)
  console.log('Dashboard mounted - demo mode:', apiConfig.demoMode)
})
</script>

<style scoped>
.bg-gradient-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card {
  transition: transform 0.2s;
  border: 1px solid rgba(0, 0, 0, 0.125);
}

.card:hover {
  transform: translateY(-2px);
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.chart-container {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f8f9fa;
  border-radius: 8px;
  border: 2px dashed #dee2e6;
}

.chart-container::before {
  content: "📊 Demo Chart Area";
  color: #6c757d;
  font-size: 1.2rem;
}

.list-group-item {
  border: none;
  padding: 0.75rem 0;
}

.list-group-item:not(:last-child) {
  border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.btn {
  transition: all 0.3s ease;
}

.btn:hover {
  transform: translateY(-1px);
}
</style>