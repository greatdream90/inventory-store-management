<template>
  <div class="users-page">
    <h2 class="mb-4">จัดการผู้ใช้งาน</h2>
    
    <!-- Demo Content -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body text-center py-5">
            <i class="bi bi-person-gear fs-1 text-muted mb-3"></i>
            <h4>หน้าจัดการผู้ใช้งาน</h4>
            <p class="text-muted">{{ apiConfig.demoMode ? 'ข้อมูลจำลอง - ผู้ใช้งานระบบ 8 คน' : 'กำลังโหลดข้อมูลผู้ใช้งาน...' }}</p>
            <div class="row g-3 mt-4">
              <div class="col-md-4">
                <div class="card bg-primary text-white">
                  <div class="card-body">
                    <h5>ผู้ใช้ทั้งหมด</h5>
                    <h3>8</h3>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="card bg-success text-white">
                  <div class="card-body">
                    <h5>ออนไลน์</h5>
                    <h3>3</h3>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="card bg-info text-white">
                  <div class="card-body">
                    <h5>แอดมิน</h5>
                    <h3>2</h3>
                  </div>
                </div>
              </div>
            </div>
            <div class="mt-4">
              <h6>บทบาทผู้ใช้งาน (Demo)</h6>
              <div class="row g-2 mt-2">
                <div class="col-6 col-md-3">
                  <span class="badge bg-danger">Admin</span>
                </div>
                <div class="col-6 col-md-3">
                  <span class="badge bg-warning">Manager</span>
                </div>
                <div class="col-6 col-md-3">
                  <span class="badge bg-info">Cashier</span>
                </div>
                <div class="col-6 col-md-3">
                  <span class="badge bg-secondary">Staff</span>
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