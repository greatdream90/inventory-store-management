<template>
  <div class="dashboard">
    <!-- Welcome Section -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="card bg-primary text-white">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col">
                <h3 class="mb-1">ยินดีต้อนรับ, {{ authStore.userName }}!</h3>
                <p class="mb-0">ภาพรวมของระบบจัดการคลังสินค้า</p>
              </div>
              <div class="col-auto">
                <i class="bi bi-shop display-4"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
      <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card bg-primary text-white h-100">
          <div class="card-body text-center">
            <i class="bi bi-box-seam display-4 mb-3"></i>
            <div class="stats-number">{{ stats.totalProducts }}</div>
            <div class="stats-label">สินค้าทั้งหมด</div>
          </div>
        </div>
      </div>
      
      <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card bg-success text-white h-100">
          <div class="card-body text-center">
            <i class="bi bi-graph-up display-4 mb-3"></i>
            <div class="stats-number">{{ formatCurrency(stats.todaySales) }}</div>
            <div class="stats-label">ยอดขายวันนี้</div>
          </div>
        </div>
      </div>
      
      <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card bg-warning text-white h-100">
          <div class="card-body text-center">
            <i class="bi bi-exclamation-triangle display-4 mb-3"></i>
            <div class="stats-number">{{ stats.lowStockItems }}</div>
            <div class="stats-label">สินค้าเหลือน้อย</div>
          </div>
        </div>
      </div>
      
      <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card bg-info text-white h-100">
          <div class="card-body text-center">
            <i class="bi bi-people display-4 mb-3"></i>
            <div class="stats-number">{{ stats.totalCustomers }}</div>
            <div class="stats-label">ลูกค้าทั้งหมด</div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="row">
      <!-- Recent Sales -->
      <div class="col-lg-8 mb-4">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
              <i class="bi bi-receipt me-2"></i>การขายล่าสุด
            </h5>
            <router-link to="/sales" class="btn btn-sm btn-outline-primary">
              ดูทั้งหมด
            </router-link>
          </div>
          <div class="card-body">
            <div v-if="recentSales.length === 0" class="text-center py-4 text-muted">
              <i class="bi bi-inbox display-4"></i>
              <p class="mt-2">ยังไม่มีการขาย</p>
            </div>
            <div class="table-responsive" v-else>
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>เลขที่</th>
                    <th>ลูกค้า</th>
                    <th>จำนวนรายการ</th>
                    <th>ยอดรวม</th>
                    <th>สถานะ</th>
                    <th>วันที่</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="sale in recentSales" :key="sale.id">
                    <td class="fw-bold">#{{ sale.sale_number }}</td>
                    <td>{{ sale.customer?.name || 'ลูกค้าทั่วไป' }}</td>
                    <td>{{ sale.total_items }} รายการ</td>
                    <td class="fw-bold text-success">{{ formatCurrency(sale.total) }}</td>
                    <td>
                      <span class="badge" :class="getSaleStatusClass(sale.status)">
                        {{ getSaleStatusText(sale.status) }}
                      </span>
                    </td>
                    <td>{{ formatDateTime(sale.created_at) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Low Stock Alert -->
      <div class="col-lg-4 mb-4">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-warning">
              <i class="bi bi-exclamation-triangle me-2"></i>สินค้าเหลือน้อย
            </h5>
            <router-link to="/inventory" class="btn btn-sm btn-outline-warning">
              จัดการ
            </router-link>
          </div>
          <div class="card-body">
            <div v-if="lowStockProducts.length === 0" class="text-center py-4 text-muted">
              <i class="bi bi-check-circle display-4 text-success"></i>
              <p class="mt-2">สินค้าปกติทั้งหมด</p>
            </div>
            <div v-else>
              <div v-for="product in lowStockProducts" :key="product.id" 
                   class="d-flex justify-content-between align-items-center mb-3 p-2 border rounded">
                <div>
                  <div class="fw-bold">{{ product.name }}</div>
                  <small class="text-muted">SKU: {{ product.sku }}</small>
                </div>
                <div class="text-end">
                  <div class="fw-bold text-danger">{{ product.quantity }} {{ product.unit }}</div>
                  <small class="text-muted">ขั้นต่ำ: {{ product.min_quantity }}</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h5 class="mb-0">
              <i class="bi bi-lightning me-2"></i>การดำเนินการด่วน
            </h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-lg-2 col-md-4 col-6 mb-3" v-if="authStore.hasPermission('sales.create')">
                <router-link to="/pos" class="btn btn-primary w-100 h-100 d-flex flex-column justify-content-center">
                  <i class="bi bi-cart-plus fs-1 mb-2"></i>
                  <span>ขายสินค้า</span>
                </router-link>
              </div>
              
              <div class="col-lg-2 col-md-4 col-6 mb-3" v-if="authStore.hasPermission('products.create')">
                <router-link to="/products" class="btn btn-success w-100 h-100 d-flex flex-column justify-content-center">
                  <i class="bi bi-plus-square fs-1 mb-2"></i>
                  <span>เพิ่มสินค้า</span>
                </router-link>
              </div>
              
              <div class="col-lg-2 col-md-4 col-6 mb-3" v-if="authStore.hasPermission('customers.create')">
                <router-link to="/customers" class="btn btn-info w-100 h-100 d-flex flex-column justify-content-center">
                  <i class="bi bi-person-plus fs-1 mb-2"></i>
                  <span>เพิ่มลูกค้า</span>
                </router-link>
              </div>
              
              <div class="col-lg-2 col-md-4 col-6 mb-3" v-if="authStore.hasPermission('reports.view')">
                <router-link to="/reports" class="btn btn-secondary w-100 h-100 d-flex flex-column justify-content-center">
                  <i class="bi bi-graph-up fs-1 mb-2"></i>
                  <span>รายงาน</span>
                </router-link>
              </div>
              
              <div class="col-lg-2 col-md-4 col-6 mb-3" v-if="authStore.hasPermission('inventory.manage')">
                <router-link to="/inventory" class="btn btn-warning w-100 h-100 d-flex flex-column justify-content-center">
                  <i class="bi bi-boxes fs-1 mb-2"></i>
                  <span>จัดการสต๊อก</span>
                </router-link>
              </div>
              
              <div class="col-lg-2 col-md-4 col-6 mb-3">
                <router-link to="/notifications" class="btn btn-outline-primary w-100 h-100 d-flex flex-column justify-content-center">
                  <i class="bi bi-bell fs-1 mb-2"></i>
                  <span>การแจ้งเตือน</span>
                </router-link>
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

const authStore = useAuthStore()

// Reactive data
const stats = ref({
  totalProducts: 0,
  todaySales: 0,
  lowStockItems: 0,
  totalCustomers: 0
})

const recentSales = ref([])
const lowStockProducts = ref([])
const isLoading = ref(false)

// Methods
function formatCurrency(amount) {
  return new Intl.NumberFormat('th-TH', {
    style: 'currency',
    currency: 'THB'
  }).format(amount)
}

function formatDateTime(dateString) {
  return new Date(dateString).toLocaleString('th-TH', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

function getSaleStatusClass(status) {
  const classes = {
    pending: 'bg-warning',
    completed: 'bg-success',
    cancelled: 'bg-danger',
    refunded: 'bg-secondary'
  }
  return classes[status] || 'bg-secondary'
}

function getSaleStatusText(status) {
  const texts = {
    pending: 'รอดำเนินการ',
    completed: 'สำเร็จ',
    cancelled: 'ยกเลิก',
    refunded: 'คืนเงิน'
  }
  return texts[status] || status
}

async function loadDashboardData() {
  isLoading.value = true
  try {
    // TODO: Replace with actual API calls
    // Simulate loading data
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    // Mock data for demonstration
    stats.value = {
      totalProducts: 150,
      todaySales: 12500.00,
      lowStockItems: 5,
      totalCustomers: 75
    }
    
    recentSales.value = [
      {
        id: 1,
        sale_number: 'SAL-20241004-0001',
        customer: { name: 'คุณสมชาย ใจดี' },
        total_items: 3,
        total: 850.00,
        status: 'completed',
        created_at: new Date().toISOString()
      },
      {
        id: 2,
        sale_number: 'SAL-20241004-0002',
        customer: null,
        total_items: 1,
        total: 250.00,
        status: 'completed',
        created_at: new Date(Date.now() - 3600000).toISOString()
      }
    ]
    
    lowStockProducts.value = [
      {
        id: 1,
        name: 'เสื้อยืดผ้าฝ้าย',
        sku: 'T001',
        quantity: 2,
        min_quantity: 5,
        unit: 'ตัว'
      },
      {
        id: 2,
        name: 'กระเป๋าสะพายหลัง',
        sku: 'B001',
        quantity: 1,
        min_quantity: 3,
        unit: 'ใบ'
      }
    ]
    
  } catch (error) {
    console.error('Error loading dashboard data:', error)
  } finally {
    isLoading.value = false
  }
}

// Initialize
onMounted(() => {
  loadDashboardData()
})
</script>

<style scoped>
.dashboard {
  animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

.stats-card {
  transition: transform 0.2s, box-shadow 0.2s;
}

.stats-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.2);
}

.stats-number {
  font-size: 2rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
}

.stats-label {
  opacity: 0.9;
  font-size: 0.9rem;
}

.btn.w-100.h-100 {
  min-height: 120px;
  text-decoration: none;
}

.btn.w-100.h-100:hover {
  transform: translateY(-2px);
}
</style>