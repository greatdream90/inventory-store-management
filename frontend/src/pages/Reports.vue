<template>
  <div class="reports-page">
    <h2 class="mb-4">รายงาน</h2>
    
    <!-- Demo Content -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body text-center py-5">
            <i class="bi bi-graph-up fs-1 text-muted mb-3"></i>
            <h4>หน้ารายงาน</h4>
            <p class="text-muted">{{ apiConfig.demoMode ? 'รายงานและสถิติการขาย (Demo Mode)' : 'กำลังโหลดข้อมูลรายงาน...' }}</p>
            <div class="row g-3 mt-4">
              <div class="col-md-6 col-lg-3">
                <div class="card border-primary">
                  <div class="card-body">
                    <h6><i class="bi bi-calendar-day me-2"></i>รายงานรายวัน</h6>
                    <p class="text-muted small">สรุปการขายรายวัน</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-3">
                <div class="card border-success">
                  <div class="card-body">
                    <h6><i class="bi bi-calendar-week me-2"></i>รายงานรายสัปดาห์</h6>
                    <p class="text-muted small">สรุปการขายรายสัปดาห์</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-3">
                <div class="card border-info">
                  <div class="card-body">
                    <h6><i class="bi bi-calendar-month me-2"></i>รายงานรายเดือน</h6>
                    <p class="text-muted small">สรุปการขายรายเดือน</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-3">
                <div class="card border-warning">
                  <div class="card-body">
                    <h6><i class="bi bi-boxes me-2"></i>รายงานสต๊อก</h6>
                    <p class="text-muted small">สถานะสินค้าคงคลัง</p>
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