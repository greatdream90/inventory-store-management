<template>
  <div class="customers-page">
    <h2 class="mb-4">จัดการลูกค้า</h2>
    
    <!-- Demo Content -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body text-center py-5">
            <i class="bi bi-people fs-1 text-muted mb-3"></i>
            <h4>หน้าจัดการลูกค้า</h4>
            <p class="text-muted">{{ apiConfig.demoMode ? 'ข้อมูลจำลอง - ลูกค้าทั้งหมด 1,247 คน' : 'กำลังโหลดข้อมูลลูกค้า...' }}</p>
            <div class="row g-3 mt-4">
              <div class="col-md-3">
                <div class="card bg-primary text-white">
                  <div class="card-body">
                    <h5>ลูกค้าทั้งหมด</h5>
                    <h3>1,247</h3>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card bg-success text-white">
                  <div class="card-body">
                    <h5>ซื้อเดือนนี้</h5>
                    <h3>423</h3>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card bg-info text-white">
                  <div class="card-body">
                    <h5>ลูกค้าใหม่</h5>
                    <h3>67</h3>
                    <small>เดือนนี้</small>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card bg-warning text-white">
                  <div class="card-body">
                    <h5>VIP</h5>
                    <h3>89</h3>
                    <small>ลูกค้าพิเศษ</small>
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