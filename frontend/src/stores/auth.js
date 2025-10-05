import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import axios from 'axios'
import { useToast } from 'vue-toastification'

const toast = useToast()

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const token = ref(localStorage.getItem('token'))
  const isLoading = ref(false)

  // Computed properties
  const isAuthenticated = computed(() => !!token.value && !!user.value)
  const userRole = computed(() => user.value?.role || null)
  const userName = computed(() => user.value?.name || '')

  // Configure axios defaults
  axios.defaults.baseURL = window.location.origin
  axios.defaults.headers.common['Accept'] = 'application/json'
  axios.defaults.headers.common['Content-Type'] = 'application/json'
  
  // Set axios default authorization header
  if (token.value) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`
  }

  // Methods
  async function login(credentials) {
    isLoading.value = true
    try {
      console.log('Attempting login with:', credentials)
      const response = await axios.post('/api/auth/login', credentials)
      console.log('Login response:', response.data)
      const { token: authToken, user: authUser } = response.data
      
      token.value = authToken
      user.value = authUser
      
      localStorage.setItem('token', authToken)
      localStorage.setItem('user', JSON.stringify(authUser))
      
      axios.defaults.headers.common['Authorization'] = `Bearer ${authToken}`
      
      toast.success(`ยินดีต้อนรับ, ${authUser.name}!`)
      return true
    } catch (error) {
      console.error('Login error:', error)
      const message = error.response?.data?.message || error.message || 'เข้าสู่ระบบไม่สำเร็จ'
      toast.error(message)
      return false
    } finally {
      isLoading.value = false
    }
  }

  async function logout() {
    try {
      if (token.value) {
        await axios.post('/api/auth/logout')
      }
    } catch (error) {
      console.error('Logout error:', error)
    } finally {
      token.value = null
      user.value = null
      
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      
      delete axios.defaults.headers.common['Authorization']
      
      toast.info('ออกจากระบบเรียบร้อย')
    }
  }

  async function fetchUser() {
    if (!token.value) return false
    
    try {
      const response = await axios.get('/api/auth/me')
      user.value = response.data
      localStorage.setItem('user', JSON.stringify(response.data))
      return true
    } catch (error) {
      // Token expired or invalid
      await logout()
      return false
    }
  }

  function initializeAuth() {
    const storedToken = localStorage.getItem('token')
    const storedUser = localStorage.getItem('user')
    
    if (storedToken && storedUser) {
      try {
        token.value = storedToken
        user.value = JSON.parse(storedUser)
        axios.defaults.headers.common['Authorization'] = `Bearer ${storedToken}`
        
        // Verify token validity (optional for now)
        // fetchUser()
      } catch (error) {
        console.error('Error initializing auth:', error)
        logout()
      }
    }
  }

  // Permission and role checks
  function hasRole(role) {
    if (!user.value) return false
    return user.value.role === role || user.value.role === 'admin'
  }

  function hasPermission(permission) {
    if (!user.value) return false
    
    // Admin has all permissions
    if (user.value.role === 'admin') return true
    
    // Define permissions for each role
    const rolePermissions = {
      staff: [
        'products.view', 'products.create', 'products.edit',
        'categories.view', 'categories.create', 'categories.edit',
        'customers.view', 'customers.create', 'customers.edit',
        'sales.view', 'sales.create',
        'inventory.manage',
        'reports.view'
      ],
      viewer: [
        'products.view',
        'categories.view',
        'customers.view',
        'sales.view',
        'reports.view'
      ]
    }
    
    const userPermissions = rolePermissions[user.value.role] || []
    return userPermissions.includes(permission)
  }

  function isAdmin() {
    return hasRole('admin')
  }

  function isStaff() {
    return hasRole('staff') || hasRole('admin')
  }

  // Initialize auth on store creation
  initializeAuth()

  return {
    // State
    user,
    token,
    isLoading,
    
    // Getters
    isAuthenticated,
    userRole,
    userName,
    
    // Actions
    login,
    logout,
    fetchUser,
    initializeAuth,
    hasRole,
    hasPermission,
    isAdmin,
    isStaff
  }
})