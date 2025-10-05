<template>
  <div class="notifications-page">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="mb-1">การแจ้งเตือน</h2>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><router-link to="/">หน้าหลัก</router-link></li>
            <li class="breadcrumb-item active">การแจ้งเตือน</li>
          </ol>
        </nav>
      </div>
      <div>
        <button class="btn btn-outline-primary" @click="markAllAsRead" :disabled="unreadCount === 0">
          <i class="bi bi-check2-all me-2"></i>
          อ่านทั้งหมด
        </button>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
      <div class="col-md-3">
        <div class="card bg-primary text-white">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="me-3">
                <i class="bi bi-bell fs-2"></i>
              </div>
              <div>
                <h6 class="card-title mb-0">ทั้งหมด</h6>
                <h4 class="mb-0">{{ totalNotifications }}</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card bg-warning text-dark">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="me-3">
                <i class="bi bi-bell-fill fs-2"></i>
              </div>
              <div>
                <h6 class="card-title mb-0">ยังไม่อ่าน</h6>
                <h4 class="mb-0">{{ unreadCount }}</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card bg-danger text-white">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="me-3">
                <i class="bi bi-exclamation-triangle fs-2"></i>
              </div>
              <div>
                <h6 class="card-title mb-0">เร่งด่วน</h6>
                <h4 class="mb-0">{{ urgentCount }}</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card bg-info text-white">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="me-3">
                <i class="bi bi-info-circle fs-2"></i>
              </div>
              <div>
                <h6 class="card-title mb-0">ข้อมูล</h6>
                <h4 class="mb-0">{{ infoCount }}</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-3">
            <label class="form-label">ประเภท</label>
            <select class="form-select" v-model="filters.type">
              <option value="">ทั้งหมด</option>
              <option value="info">ข้อมูล</option>
              <option value="warning">คำเตือน</option>
              <option value="urgent">เร่งด่วน</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">สถานะ</label>
            <select class="form-select" v-model="filters.read">
              <option value="">ทั้งหมด</option>
              <option value="false">ยังไม่อ่าน</option>
              <option value="true">อ่านแล้ว</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">ค้นหา</label>
            <input type="text" class="form-control" v-model="filters.search" placeholder="ค้นหาข้อความ...">
          </div>
          <div class="col-md-2">
            <label class="form-label">&nbsp;</label>
            <button class="btn btn-outline-secondary w-100" @click="clearFilters">
              <i class="bi bi-arrow-clockwise me-1"></i>
              ล้าง
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Notifications List -->
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">รายการแจ้งเตือน</h5>
      </div>
      <div class="card-body p-0">
        <div class="list-group list-group-flush">
          <div 
            v-for="notification in filteredNotifications" 
            :key="notification.id"
            class="list-group-item list-group-item-action d-flex align-items-start"
            :class="{
              'list-group-item-warning': !notification.read,
              'bg-light': notification.read
            }"
            @click="markAsRead(notification)"
            style="cursor: pointer;"
          >
            <div class="me-3 mt-1">
              <i 
                class="fs-4"
                :class="{
                  'bi bi-info-circle text-info': notification.type === 'info',
                  'bi bi-exclamation-triangle text-warning': notification.type === 'warning',  
                  'bi bi-exclamation-triangle-fill text-danger': notification.type === 'urgent'
                }"
              ></i>
            </div>
            <div class="flex-grow-1">
              <div class="d-flex justify-content-between align-items-start">
                <h6 class="mb-1" :class="{ 'fw-bold': !notification.read }">
                  {{ notification.title }}
                </h6>
                <small class="text-muted">{{ formatDate(notification.created_at) }}</small>
              </div>
              <p class="mb-1">{{ notification.message }}</p>
              <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                  <i class="bi bi-tag me-1"></i>{{ notification.category }}
                </small>
                <span v-if="!notification.read" class="badge bg-primary">ใหม่</span>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Empty State -->
        <div v-if="filteredNotifications.length === 0" class="text-center p-5">
          <i class="bi bi-bell-slash fs-1 text-muted"></i>
          <h5 class="mt-3 text-muted">ไม่มีการแจ้งเตือน</h5>
          <p class="text-muted">{{ filters.search || filters.type || filters.read ? 'ไม่พบการแจ้งเตือนที่ตรงกับเงื่อนไข' : 'ยังไม่มีการแจ้งเตือนในขณะนี้' }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useToast } from 'vue-toastification'

const toast = useToast()

// Mock notifications data
const notifications = ref([
  {
    id: 1,
    title: 'สินค้าในสต๊อกเหลือน้อย',
    message: 'สินค้า "Laptop Dell" เหลือในสต๊อกเพียง 2 ชิ้น กรุณาเติมสต๊อก',
    type: 'warning',
    category: 'สต๊อกสินค้า',
    read: false,
    created_at: new Date('2025-10-05T10:30:00')
  },
  {
    id: 2,
    title: 'การขายเกินเป้าหมาย',
    message: 'ยอดขายวันนี้เกินเป้าหมาย 150% จำนวน 45,000 บาท',
    type: 'info',
    category: 'การขาย',
    read: false,
    created_at: new Date('2025-10-05T09:15:00')
  },
  {
    id: 3,
    title: 'ระบบสำรองข้อมูล',
    message: 'การสำรองข้อมูลประจำวันสำเร็จเรียบร้อย',
    type: 'info',
    category: 'ระบบ',
    read: true,
    created_at: new Date('2025-10-05T06:00:00')
  },
  {
    id: 4,
    title: 'สินค้าหมดสต๊อก',
    message: 'สินค้า "T-Shirt Blue" หมดสต๊อก ต้องการสั่งซื้อเพิ่ม',
    type: 'urgent',
    category: 'สต๊อกสินค้า',
    read: false,
    created_at: new Date('2025-10-04T16:45:00')
  },
  {
    id: 5,
    title: 'ลูกค้าใหม่',
    message: 'มีลูกค้าใหม่ลงทะเบียน 3 คน ในวันนี้',
    type: 'info',
    category: 'ลูกค้า',
    read: true,
    created_at: new Date('2025-10-04T14:20:00')
  }
])

const filters = ref({
  type: '',
  read: '',
  search: ''
})

// Computed properties
const totalNotifications = computed(() => notifications.value.length)
const unreadCount = computed(() => notifications.value.filter(n => !n.read).length)
const urgentCount = computed(() => notifications.value.filter(n => n.type === 'urgent').length)
const infoCount = computed(() => notifications.value.filter(n => n.type === 'info').length)

const filteredNotifications = computed(() => {
  let result = notifications.value

  // Filter by type
  if (filters.value.type) {
    result = result.filter(n => n.type === filters.value.type)
  }

  // Filter by read status
  if (filters.value.read !== '') {
    const isRead = filters.value.read === 'true'
    result = result.filter(n => n.read === isRead)
  }

  // Filter by search
  if (filters.value.search) {
    const search = filters.value.search.toLowerCase()
    result = result.filter(n => 
      n.title.toLowerCase().includes(search) ||
      n.message.toLowerCase().includes(search) ||
      n.category.toLowerCase().includes(search)
    )
  }

  // Sort by date (newest first)
  return result.sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
})

// Methods
function markAsRead(notification) {
  if (!notification.read) {
    notification.read = true
    toast.success('ทำเครื่องหมายว่าอ่านแล้ว')
  }
}

function markAllAsRead() {
  notifications.value.forEach(n => n.read = true)
  toast.success('ทำเครื่องหมายทั้งหมดว่าอ่านแล้ว')
}

function clearFilters() {
  filters.value = {
    type: '',
    read: '',
    search: ''
  }
}

function formatDate(date) {
  const now = new Date()
  const notificationDate = new Date(date)
  const diffInHours = Math.floor((now - notificationDate) / (1000 * 60 * 60))
  
  if (diffInHours < 1) {
    return 'เมื่อสักครู่'
  } else if (diffInHours < 24) {
    return `${diffInHours} ชั่วโมงที่แล้ว`
  } else {
    const diffInDays = Math.floor(diffInHours / 24)
    return `${diffInDays} วันที่แล้ว`
  }
}

onMounted(() => {
  // Auto-mark demo notifications as read after 10 seconds
  setTimeout(() => {
    if (unreadCount.value > 0) {
      toast.info('💡 นี่คือข้อมูลจำลอง - สามารถคลิกเพื่อทำเครื่องหมายว่าอ่านแล้ว')
    }
  }, 2000)
})
</script>

<style scoped>
.list-group-item {
  border-left: 4px solid transparent;
  transition: all 0.3s ease;
}

.list-group-item:hover {
  background-color: #f8f9fa !important;
  transform: translateX(2px);
}

.list-group-item-warning {
  border-left-color: #ffc107;
}

.card {
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
  border: 1px solid rgba(0, 0, 0, 0.125);
}

.badge {
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.05);
  }
  100% {
    transform: scale(1);
  }
}
</style>