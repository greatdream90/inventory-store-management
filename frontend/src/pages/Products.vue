<template>
  <div class="products-page">
    <!-- Header Section -->
    <div class="row mb-4">
      <div class="col-md-6">
        <h3>
          <i class="bi bi-box-seam me-2"></i>จัดการสินค้า
        </h3>
      </div>
      <div class="col-md-6 text-end">
        <button 
          class="btn btn-primary"
          @click="showAddModal = true"
          v-if="authStore.hasPermission('products.create')"
        >
          <i class="bi bi-plus-circle me-2"></i>เพิ่มสินค้าใหม่
        </button>
      </div>
    </div>
    
    <!-- Search & Filter Section -->
    <div class="card mb-4">
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="search-box">
              <i class="bi bi-search"></i>
              <input 
                type="text" 
                class="form-control" 
                placeholder="ค้นหาสินค้า (ชื่อ, SKU, บาร์โค้ด)"
                v-model="searchQuery"
                @input="searchProducts"
              >
            </div>
          </div>
          <div class="col-md-3">
            <select class="form-select" v-model="selectedCategory" @change="filterProducts">
              <option value="">หมวดหมู่ทั้งหมด</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
              </option>
            </select>
          </div>
          <div class="col-md-3">
            <select class="form-select" v-model="stockFilter" @change="filterProducts">
              <option value="">สถานะสต๊อกทั้งหมด</option>
              <option value="in_stock">มีสต๊อก</option>
              <option value="low_stock">สต๊อกน้อย</option>
              <option value="out_of_stock">หมดสต๊อก</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Products Table -->
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">รายการสินค้าทั้งหมด ({{ filteredProducts.length }} รายการ)</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th>รูปภาพ</th>
                <th>ชื่อสินค้า</th>
                <th>SKU</th>
                <th>หมวดหมู่</th>
                <th>ราคา</th>
                <th>สต๊อก</th>
                <th>สถานะ</th>
                <th>จัดการ</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="isLoading">
                <td colspan="8" class="text-center py-4">
                  <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                  </div>
                </td>
              </tr>
              <tr v-else-if="filteredProducts.length === 0">
                <td colspan="8" class="text-center py-4 text-muted">
                  <i class="bi bi-inbox display-4"></i><br>
                  ไม่พบสินค้า
                </td>
              </tr>
              <tr v-else v-for="product in paginatedProducts" :key="product.id">
                <td>
                  <div class="product-image">
                    <img v-if="product.image" :src="product.image" :alt="product.name" class="img-fluid">
                    <i v-else class="bi bi-image"></i>
                  </div>
                </td>
                <td>
                  <div class="fw-bold">{{ product.name }}</div>
                  <small class="text-muted" v-if="product.description">{{ product.description }}</small>
                </td>
                <td class="font-monospace">{{ product.sku }}</td>
                <td>
                  <span class="badge bg-secondary">{{ product.category?.name || 'ไม่มีหมวดหมู่' }}</span>
                </td>
                <td class="fw-bold text-success">{{ formatCurrency(product.price) }}</td>
                <td>
                  <span :class="getStockClass(product)">
                    {{ product.quantity }} {{ product.unit }}
                  </span>
                </td>
                <td>
                  <span class="badge" :class="getStatusClass(product.is_active)">
                    {{ product.is_active ? 'ใช้งาน' : 'ปิดใช้งาน' }}
                  </span>
                </td>
                <td>
                  <div class="btn-group" role="group">
                    <button 
                      class="btn btn-sm btn-outline-primary"
                      @click="editProduct(product)"
                      v-if="authStore.hasPermission('products.edit')"
                    >
                      <i class="bi bi-pencil"></i>
                    </button>
                    <button 
                      class="btn btn-sm btn-outline-info"
                      @click="viewProductHistory(product)"
                    >
                      <i class="bi bi-clock-history"></i>
                    </button>
                    <button 
                      class="btn btn-sm btn-outline-danger"
                      @click="deleteProduct(product)"
                      v-if="authStore.hasPermission('products.delete')"
                    >
                      <i class="bi bi-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      
      <!-- Pagination -->
      <div class="card-footer" v-if="filteredProducts.length > itemsPerPage">
        <nav>
          <ul class="pagination justify-content-center mb-0">
            <li class="page-item" :class="{ disabled: currentPage === 1 }">
              <a class="page-link" href="#" @click.prevent="currentPage = 1">หน้าแรก</a>
            </li>
            <li class="page-item" :class="{ disabled: currentPage === 1 }">
              <a class="page-link" href="#" @click.prevent="currentPage--">ก่อนหน้า</a>
            </li>
            <li v-for="page in visiblePages" :key="page" 
                class="page-item" :class="{ active: currentPage === page }">
              <a class="page-link" href="#" @click.prevent="currentPage = page">{{ page }}</a>
            </li>
            <li class="page-item" :class="{ disabled: currentPage === totalPages }">
              <a class="page-link" href="#" @click.prevent="currentPage++">ถัดไป</a>
            </li>
            <li class="page-item" :class="{ disabled: currentPage === totalPages }">
              <a class="page-link" href="#" @click.prevent="currentPage = totalPages">หน้าสุดท้าย</a>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useToast } from 'vue-toastification'
import Swal from 'sweetalert2'

const authStore = useAuthStore()
const toast = useToast()

// Reactive data
const products = ref([])
const categories = ref([])
const isLoading = ref(false)
const searchQuery = ref('')
const selectedCategory = ref('')
const stockFilter = ref('')
const currentPage = ref(1)
const itemsPerPage = ref(10)
const showAddModal = ref(false)

// Mock data for demonstration
onMounted(() => {
  loadProducts()
  loadCategories()
})

function loadProducts() {
  isLoading.value = true
  
  // Mock data - replace with actual API call
  setTimeout(() => {
    products.value = [
      {
        id: 1,
        name: 'เสื้อยืดผ้าฝ้าย',
        sku: 'T001',
        description: 'เสื้อยืดผ้าฝ้าย 100% นุ่มสบาย',
        price: 299.00,
        cost_price: 150.00,
        quantity: 15,
        min_quantity: 5,
        unit: 'ตัว',
        barcode: '1234567890123',
        image: null,
        category: { id: 1, name: 'เสื้อผ้า' },
        is_active: true,
        created_at: '2024-10-01T10:00:00Z'
      },
      {
        id: 2,
        name: 'กระเป๋าสะพายหลัง',
        sku: 'B001',
        description: 'กระเป๋าสะพายหลังกันน้ำ',
        price: 1299.00,
        cost_price: 800.00,
        quantity: 2,
        min_quantity: 3,
        unit: 'ใบ',
        barcode: '1234567890124',
        image: null,
        category: { id: 2, name: 'กระเป๋า' },
        is_active: true,
        created_at: '2024-10-01T11:00:00Z'
      },
      {
        id: 3,
        name: 'หูฟังบลูทูธ',
        sku: 'H001',
        description: 'หูฟังบลูทูธคุณภาพสูง',
        price: 2499.00,
        cost_price: 1500.00,
        quantity: 0,
        min_quantity: 2,
        unit: 'ชิ้น',
        barcode: '1234567890125',
        image: null,
        category: { id: 3, name: 'อิเล็กทรอนิกส์' },
        is_active: true,
        created_at: '2024-10-01T12:00:00Z'
      }
    ]
    isLoading.value = false
  }, 1000)
}

function loadCategories() {
  categories.value = [
    { id: 1, name: 'เสื้อผ้า' },
    { id: 2, name: 'กระเป๋า' },
    { id: 3, name: 'อิเล็กทรอนิกส์' }
  ]
}

// Computed properties
const filteredProducts = computed(() => {
  let filtered = products.value
  
  // Search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(product => 
      product.name.toLowerCase().includes(query) ||
      product.sku.toLowerCase().includes(query) ||
      (product.barcode && product.barcode.includes(query))
    )
  }
  
  // Category filter
  if (selectedCategory.value) {
    filtered = filtered.filter(product => product.category?.id == selectedCategory.value)
  }
  
  // Stock filter
  if (stockFilter.value) {
    filtered = filtered.filter(product => {
      if (stockFilter.value === 'in_stock') return product.quantity > product.min_quantity
      if (stockFilter.value === 'low_stock') return product.quantity <= product.min_quantity && product.quantity > 0
      if (stockFilter.value === 'out_of_stock') return product.quantity <= 0
      return true
    })
  }
  
  return filtered
})

const totalPages = computed(() => Math.ceil(filteredProducts.value.length / itemsPerPage.value))

const paginatedProducts = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return filteredProducts.value.slice(start, end)
})

const visiblePages = computed(() => {
  const total = totalPages.value
  const current = currentPage.value
  const visible = []
  
  for (let i = Math.max(1, current - 2); i <= Math.min(total, current + 2); i++) {
    visible.push(i)
  }
  
  return visible
})

// Methods
function formatCurrency(amount) {
  return new Intl.NumberFormat('th-TH', {
    style: 'currency',
    currency: 'THB'
  }).format(amount)
}

function getStockClass(product) {
  if (product.quantity <= 0) return 'text-danger fw-bold'
  if (product.quantity <= product.min_quantity) return 'text-warning fw-bold'
  return 'text-success'
}

function getStatusClass(isActive) {
  return isActive ? 'bg-success' : 'bg-secondary'
}

function searchProducts() {
  currentPage.value = 1
}

function filterProducts() {
  currentPage.value = 1
}

function editProduct(product) {
  toast.info(`แก้ไขสินค้า: ${product.name}`)
  // TODO: Open edit modal
}

function viewProductHistory(product) {
  toast.info(`ประวัติสินค้า: ${product.name}`)
  // TODO: Show product history modal
}

async function deleteProduct(product) {
  const result = await Swal.fire({
    title: 'ต้องการลบสินค้าใช่หรือไม่?',
    text: `สินค้า: ${product.name}`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'ลบ',
    cancelButtonText: 'ยกเลิก'
  })
  
  if (result.isConfirmed) {
    // TODO: Call API to delete product
    products.value = products.value.filter(p => p.id !== product.id)
    toast.success('ลบสินค้าสำเร็จ')
  }
}
</script>