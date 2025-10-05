<template>
  <div class="auth-layout min-vh-100 d-flex align-items-center justify-content-center">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5 col-xl-4">
          <div class="text-center mb-4">
            <div class="auth-logo mb-3">
              <i class="bi bi-shop display-1 text-black-50"></i>
            </div>
            <h2 class="fw-bold text-black-50">Inventory Store</h2>
            <p class="text-muted">ระบบจัดการคลังสินค้า</p>
          </div>
          
          <div class="card shadow-lg border-0">
            <div class="card-body p-4">
              <router-view />
            </div>
          </div>
          
          <div class="text-center mt-4">
            <small class="text-muted">
              © 2025 GReaTzA - All Rights Reserved
            </small>
            <br>
            <small class="text-muted" style="font-size: 0.75rem;">
              Proprietary Software - Unauthorized use prohibited
            </small>
            <br>
            <small class="text-muted" style="font-size: 0.7rem;">
              Built with Vue.js, Laravel, Bootstrap & other open source technologies
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.auth-layout {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.auth-logo {
  animation: float 3s ease-in-out infinite;
}

@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
}

.card {
  backdrop-filter: blur(10px);
  background: rgba(255, 255, 255, 0.95);
}
</style>