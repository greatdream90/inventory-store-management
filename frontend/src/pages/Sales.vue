<template>
  <div class="sales-page">
    <h2 class="mb-4">รายการขาย</h2>
    
    <!-- Demo Content -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body text-center py-5">
            <i class="bi bi-cart-check fs-1 text-muted mb-3"></i>
            <h4>หน้ารายการขาย</h4>
            <p class="text-muted">{{ apiConfig.demoMode ? 'ข้อมูลจำลอง - ยอดขายวันนี้ 245,680 บาท' : 'กำลังโหลดข้อมูลขาย...' }}</p>
            <div class="row g-3 mt-4">
              <div class="col-md-3">
                <div class="card bg-primary text-white">
                  <div class="card-body">
                    <h5>ขายวันนี้</h5>
                    <h3>89 รายการ</h3>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card bg-success text-white">
                  <div class="card-body">
                    <h5>ยอดขายวันนี้</h5>
                    <h3>245,680 ฿</h3>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card bg-info text-white">
                  <div class="card-body">
                    <h5>ลูกค้าวันนี้</h5>
                    <h3>67 คน</h3>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card bg-warning text-white">
                  <div class="card-body">
                    <h5>ยอดขายเฉลี่ย</h5>
                    <h3>2,758 ฿</h3>
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