<template>
  <div class="categories-page">
    <h2 class="mb-4">จัดการหมวดหมู่</h2>
    
    <!-- Demo Content -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body text-center py-5">
            <i class="bi bi-collection fs-1 text-muted mb-3"></i>
            <h4>หน้าจัดการหมวดหมู่</h4>
            <p class="text-muted">{{ apiConfig.demoMode ? 'ข้อมูลจำลอง - หมวดหมู่ทั้งหมด 12 หมวดหมู่' : 'กำลังโหลดข้อมูลหมวดหมู่...' }}</p>
            <div class="row g-3 mt-4">
              <div class="col-md-6">
                <div class="card bg-info text-white">
                  <div class="card-body">
                    <h5>หมวดหมู่ทั้งหมด</h5>
                    <h3>12</h3>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card bg-success text-white">
                  <div class="card-body">
                    <h5>หมวดหมู่ที่ใช้งาน</h5>
                    <h3>10</h3>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { apiConfig } from '@/config/app.js'
</script>