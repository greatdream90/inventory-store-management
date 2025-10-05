<template>
  <div class="inventory-page">
    <h2 class="mb-4">จัดการสต๊อก</h2>
    
    <!-- Demo Content -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body text-center py-5">
            <i class="bi bi-boxes fs-1 text-muted mb-3"></i>
            <h4>หน้าจัดการสต๊อก</h4>
            <p class="text-muted">{{ apiConfig.demoMode ? 'ข้อมูลจำลอง - การเคลื่อนไหวสต๊อกล่าสุด' : 'กำลังโหลดข้อมูลสต๊อก...' }}</p>
            <div class="row g-3 mt-4">
              <div class="col-md-3">
                <div class="card bg-primary text-white">
                  <div class="card-body">
                    <h5>รับสินค้า</h5>
                    <h3>24</h3>
                    <small>รายการวันนี้</small>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card bg-danger text-white">
                  <div class="card-body">
                    <h5>เบิกสินค้า</h5>
                    <h3>156</h3>
                    <small>รายการวันนี้</small>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card bg-warning text-white">
                  <div class="card-body">
                    <h5>ปรับสต๊อก</h5>
                    <h3>8</h3>
                    <small>รายการวันนี้</small>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card bg-info text-white">
                  <div class="card-body">
                    <h5>ตรวจนับ</h5>
                    <h3>12</h3>
                    <small>รายการวันนี้</small>
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