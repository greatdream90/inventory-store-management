// Mock API Service for Demo Mode
import { apiConfig } from './app.js'

// Mock Data
const mockUsers = [
  {
    id: 1,
    email: 'admin@demo.com',
    name: 'Admin Demo',
    role: 'admin',
    password: 'admin123'
  },
  {
    id: 2,
    email: 'staff@demo.com', 
    name: 'Staff Demo',
    role: 'staff',
    password: 'staff123'
  },
  {
    id: 3,
    email: 'viewer@demo.com',
    name: 'Viewer Demo', 
    role: 'viewer',
    password: 'viewer123'
  }
]

const mockCategories = [
  { id: 1, name: 'Electronics', description: 'Electronic products', is_active: 1 },
  { id: 2, name: 'Clothing', description: 'Fashion items', is_active: 1 },
  { id: 3, name: 'Books', description: 'Educational materials', is_active: 1 }
]

const mockProducts = [
  { id: 1, name: 'Laptop Dell', category_id: 1, price: 25000, stock: 10, sku: 'LAPTOP001' },
  { id: 2, name: 'T-Shirt Blue', category_id: 2, price: 299, stock: 50, sku: 'TSHIRT001' },
  { id: 3, name: 'JavaScript Guide', category_id: 3, price: 599, stock: 25, sku: 'BOOK001' }
]

// Mock API responses with realistic delays
const delay = (ms = 500) => new Promise(resolve => setTimeout(resolve, ms))

export const mockApi = {
  // Authentication
  async login(credentials) {
    await delay(800) // Simulate network delay
    
    const user = mockUsers.find(u => 
      u.email === credentials.email && u.password === credentials.password
    )
    
    if (!user) {
      throw new Error('อีเมลหรือรหัสผ่านไม่ถูกต้อง')
    }
    
    const token = btoa(JSON.stringify({
      userId: user.id,
      email: user.email,
      role: user.role,
      exp: Date.now() + 3600000 // 1 hour
    }))
    
    return {
      data: {
        success: true,
        token,
        user: {
          id: user.id,
          email: user.email,
          name: user.name,
          role: user.role
        }
      }
    }
  },
  
  async logout() {
    await delay(300)
    return { data: { success: true, message: 'ออกจากระบบเรียบร้อย' } }
  },
  
  async me() {
    await delay(200)
    // Get current user from localStorage or return first admin
    const token = localStorage.getItem('token')
    if (token) {
      try {
        const decoded = JSON.parse(atob(token))
        const user = mockUsers.find(u => u.id === decoded.userId)
        if (user) {
          return {
            data: {
              id: user.id,
              email: user.email,
              name: user.name,
              role: user.role
            }
          }
        }
      } catch (e) {
        console.error('Invalid token:', e)
      }
    }
    throw new Error('Token required')
  },
  
  // Categories
  async getCategories() {
    await delay(400)
    return {
      data: {
        success: true,
        data: mockCategories
      }
    }
  },
  
  // Products
  async getProducts() {
    await delay(600)
    return {
      data: {
        success: true,
        data: mockProducts,
        pagination: {
          total: mockProducts.length,
          per_page: 15,
          current_page: 1
        }
      }
    }
  },
  
  // Health check
  async health() {
    await delay(100)
    return {
      data: {
        status: 'OK (Demo Mode)',
        timestamp: new Date().toISOString(),
        mode: 'mock'
      }
    }
  }
}

// Mock interceptor to log requests in demo mode
export const mockInterceptor = {
  request: (config) => {
    if (apiConfig.features.debugLogging) {
      console.log('🎭 Mock API Request:', config.method?.toUpperCase(), config.url)
    }
    return config
  },
  
  response: (response) => {
    if (apiConfig.features.debugLogging) {
      console.log('🎭 Mock API Response:', response.status, response.data)
    }
    return response
  }
}