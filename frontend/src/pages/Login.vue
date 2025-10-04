<template>
  <div class="login-form">
    <h4 class="text-center mb-4">เข้าสู่ระบบ</h4>
    
    <form @submit.prevent="handleLogin">
      <!-- Email Field -->
      <div class="mb-3">
        <label for="email" class="form-label">
          <i class="bi bi-envelope me-2"></i>อีเมล
        </label>
        <input 
          type="email" 
          class="form-control form-control-lg"
          :class="{ 'is-invalid': errors.email }"
          id="email"
          v-model="form.email"
          placeholder="กรุณาใส่อีเมล"
          required
          :disabled="authStore.isLoading"
        >
        <div class="invalid-feedback" v-if="errors.email">
          {{ errors.email }}
        </div>
      </div>
      
      <!-- Password Field -->
      <div class="mb-3">
        <label for="password" class="form-label">
          <i class="bi bi-lock me-2"></i>รหัสผ่าน
        </label>
        <div class="input-group">
          <input 
            :type="showPassword ? 'text' : 'password'" 
            class="form-control form-control-lg"
            :class="{ 'is-invalid': errors.password }"
            id="password"
            v-model="form.password"
            placeholder="กรุณาใส่รหัสผ่าน"
            required
            :disabled="authStore.isLoading"
          >
          <button 
            type="button" 
            class="btn btn-outline-secondary"
            @click="togglePasswordVisibility"
            :disabled="authStore.isLoading"
          >
            <i class="bi" :class="showPassword ? 'bi-eye-slash' : 'bi-eye'"></i>
          </button>
          <div class="invalid-feedback" v-if="errors.password">
            {{ errors.password }}
          </div>
        </div>
      </div>
      
      <!-- Remember Me -->
      <div class="mb-3 form-check">
        <input 
          type="checkbox" 
          class="form-check-input" 
          id="remember"
          v-model="form.remember"
          :disabled="authStore.isLoading"
        >
        <label class="form-check-label" for="remember">
          จดจำการเข้าสู่ระบบ
        </label>
      </div>
      
      <!-- Submit Button -->
      <div class="d-grid">
        <button 
          type="submit" 
          class="btn btn-primary btn-lg"
          :disabled="authStore.isLoading"
        >
          <span v-if="authStore.isLoading">
            <i class="bi bi-arrow-clockwise loading-spinner me-2"></i>
            กำลังเข้าสู่ระบบ...
          </span>
          <span v-else>
            <i class="bi bi-box-arrow-in-right me-2"></i>
            เข้าสู่ระบบ
          </span>
        </button>
      </div>
    </form>
    
    <!-- Demo Accounts -->
    <div class="mt-4 p-3 bg-light rounded">
      <h6 class="text-center mb-3">บัญชีทดสอบ</h6>
      <div class="row g-2">
        <div class="col-12">
          <button 
            type="button" 
            class="btn btn-outline-primary btn-sm w-100"
            @click="loginAsDemo('admin')"
            :disabled="authStore.isLoading"
          >
            <i class="bi bi-person-gear me-2"></i>Admin Demo
          </button>
        </div>
        <div class="col-12">
          <button 
            type="button" 
            class="btn btn-outline-success btn-sm w-100"
            @click="loginAsDemo('staff')"
            :disabled="authStore.isLoading"
          >
            <i class="bi bi-person-badge me-2"></i>Staff Demo
          </button>
        </div>
        <div class="col-12">
          <button 
            type="button" 
            class="btn btn-outline-info btn-sm w-100"
            @click="loginAsDemo('viewer')"
            :disabled="authStore.isLoading"
          >
            <i class="bi bi-person me-2"></i>Viewer Demo
          </button>
        </div>
      </div>
    </div>
    
    <!-- Footer Links -->
    <div class="text-center mt-4">
      <a href="#" class="text-decoration-none small text-muted">ลืมรหัสผ่าน?</a>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useToast } from 'vue-toastification'

const router = useRouter()
const authStore = useAuthStore()
const toast = useToast()

// Reactive data
const showPassword = ref(false)
const form = reactive({
  email: '',
  password: '',
  remember: false
})

const errors = reactive({
  email: '',
  password: ''
})

// Methods
function togglePasswordVisibility() {
  showPassword.value = !showPassword.value
}

function clearErrors() {
  errors.email = ''
  errors.password = ''
}

function validateForm() {
  clearErrors()
  let isValid = true

  if (!form.email) {
    errors.email = 'กรุณาใส่อีเมล'
    isValid = false
  } else if (!/\S+@\S+\.\S+/.test(form.email)) {
    errors.email = 'รูปแบบอีเมลไม่ถูกต้อง'
    isValid = false
  }

  if (!form.password) {
    errors.password = 'กรุณาใส่รหัสผ่าน'
    isValid = false
  } else if (form.password.length < 6) {
    errors.password = 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร'
    isValid = false
  }

  return isValid
}

async function handleLogin() {
  if (!validateForm()) return

  const success = await authStore.login({
    email: form.email,
    password: form.password,
    remember: form.remember
  })

  if (success) {
    router.push('/')
  }
}

async function loginAsDemo(role) {
  const demoAccounts = {
    admin: { email: 'admin@demo.com', password: 'admin123' },
    staff: { email: 'staff@demo.com', password: 'staff123' },
    viewer: { email: 'viewer@demo.com', password: 'viewer123' }
  }

  const demo = demoAccounts[role]
  if (demo) {
    form.email = demo.email
    form.password = demo.password
    
    const success = await authStore.login({
      email: demo.email,
      password: demo.password,
      remember: false
    })

    if (success) {
      toast.success(`เข้าสู่ระบบในฐานะ ${role} เรียบร้อย`)
      router.push('/')
    }
  }
}

// Initialize
onMounted(() => {
  // Clear form if coming from logout
  form.email = ''
  form.password = ''
  form.remember = false
  clearErrors()
})
</script>

<style scoped>
.login-form {
  animation: fadeInUp 0.5s ease-out;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.form-control:focus {
  box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
  border-color: #86b7fe;
}

.loading-spinner {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

.btn:hover {
  transform: translateY(-1px);
  box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.15);
}

.bg-light {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
}
</style>