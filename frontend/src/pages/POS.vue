<template>
  <div class="pos-page">
    <h2 class="mb-4">จุดขาย (POS)</h2>
    
    <!-- Demo Content -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body text-center py-5">
            <i class="bi bi-calculator fs-1 text-muted mb-3"></i>
            <h4>หน้าจุดขาย</h4>
            <p class="text-muted">{{ apiConfig.demoMode ? 'ระบบขายหน้าร้าน (Demo Mode)' : 'กำลังโหลดระบบขาย...' }}</p>
            <div class="row g-3 mt-4">
              <div class="col-md-6">
                <div class="card border-primary">
                  <div class="card-body">
                    <h5><i class="bi bi-cart-plus me-2"></i>เพิ่มสินค้า</h5>
                    <p class="text-muted">สแกนหรือเลือกสินค้าเพื่อเพิ่มใส่ตะกร้า</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card border-success">
                  <div class="card-body">
                    <h5><i class="bi bi-credit-card me-2"></i>ชำระเงิน</h5>
                    <p class="text-muted">รับชำระด้วยเงินสด/โอน</p>
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