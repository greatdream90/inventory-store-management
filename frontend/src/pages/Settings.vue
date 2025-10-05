<template>
  <div class="settings-page">
    <h2 class="mb-4">ตั้งค่าระบบ</h2>
    
    <!-- Demo Content -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body text-center py-5">
            <i class="bi bi-gear fs-1 text-muted mb-3"></i>
            <h4>หน้าตั้งค่าระบบ</h4>
            <p class="text-muted">{{ apiConfig.demoMode ? 'การตั้งค่าและกำหนดค่าระบบ (Demo Mode)' : 'กำลังโหลดการตั้งค่า...' }}</p>
            <div class="row g-3 mt-4">
              <div class="col-md-6 col-lg-4">
                <div class="card border-primary">
                  <div class="card-body">
                    <h6><i class="bi bi-shop me-2"></i>ข้อมูลร้านค้า</h6>
                    <p class="text-muted small">ชื่อร้าน, ที่อยู่, เบอร์โทร</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div class="card border-success">
                  <div class="card-body">
                    <h6><i class="bi bi-cash-coin me-2"></i>การชำระเงิน</h6>
                    <p class="text-muted small">วิธีการรับชำระเงิน</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div class="card border-info">
                  <div class="card-body">
                    <h6><i class="bi bi-printer me-2"></i>เครื่องพิมพ์</h6>
                    <p class="text-muted small">ตั้งค่าเครื่องพิมพ์</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div class="card border-warning">
                  <div class="card-body">
                    <h6><i class="bi bi-bell me-2"></i>การแจ้งเตือน</h6>
                    <p class="text-muted small">ตั้งค่าการแจ้งเตือน</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div class="card border-danger">
                  <div class="card-body">
                    <h6><i class="bi bi-shield-lock me-2"></i>ความปลอดภัย</h6>
                    <p class="text-muted small">รหัสผ่าน, สิทธิ์</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div class="card border-secondary">
                  <div class="card-body">
                    <h6><i class="bi bi-database me-2"></i>สำรองข้อมูล</h6>
                    <p class="text-muted small">Backup & Restore</p>
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