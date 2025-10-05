<template>
  <div class="products-page">
    <h2 class="mb-4">จัดการสินค้า</h2>
    
    <!-- Demo Content -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body text-center py-5">
            <i class="bi bi-box fs-1 text-muted mb-3"></i>
            <h4>หน้าจัดการสินค้า</h4>
            <p class="text-muted">{{ apiConfig.demoMode ? 'ข้อมูลจำลอง - สินค้าทั้งหมด 156 รายการ' : 'กำลังโหลดข้อมูลสินค้า...' }}</p>
            <div class="row g-3 mt-4">
              <div class="col-md-3">
                <div class="card bg-primary text-white">
                  <div class="card-body">
                    <h5>สินค้าทั้งหมด</h5>
                    <h3>156</h3>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card bg-success text-white">
                  <div class="card-body">
                    <h5>มีสต๊อก</h5>
                    <h3>142</h3>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card bg-warning text-white">
                  <div class="card-body">
                    <h5>สต๊อกต่ำ</h5>
                    <h3>8</h3>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card bg-danger text-white">
                  <div class="card-body">
                    <h5>หมดสต๊อก</h5>
                    <h3>6</h3>
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