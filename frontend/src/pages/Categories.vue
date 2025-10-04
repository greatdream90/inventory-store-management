<template>
  <div class="categories-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3>หมวดหมู่สินค้า</h3>
      <button class="btn btn-primary" v-if="authStore.hasPermission('categories.create')">
        <i class="bi bi-plus-circle me-2"></i>เพิ่มหมวดหมู่ใหม่
      </button>
    </div>
    
    <div class="card">
      <div class="card-body">
        <p class="text-center py-5 text-muted">
          <i class="bi bi-tags display-1"></i><br>
          หน้าหมวดหมู่สินค้า - กำลังพัฒนา
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()
</script>