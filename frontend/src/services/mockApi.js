// Mock API Service for Demo/Presentation Mode
// Works without backend connection

class MockApiService {
  constructor() {
    this.isEnabled = false
    this.delay = 800 // Simulate network delay
    
    // Demo users data
    this.demoUsers = [
      {
        id: 1,
        email: 'admin@demo.com',
        password: 'admin123',
        name: 'ผู้ดูแลระบบ',
        role: 'admin',
        avatar: null,
        created_at: '2025-01-01T00:00:00Z'
      },
      {
        id: 2,
        email: 'staff@demo.com', 
        password: 'staff123',
        name: 'พนักงานขาย',
        role: 'staff',
        avatar: null,
        created_at: '2025-01-01T00:00:00Z'
      },
      {
        id: 3,
        email: 'viewer@demo.com',
        password: 'viewer123', 
        name: 'ผู้ชมข้อมูล',
        role: 'viewer',
        avatar: null,
        created_at: '2025-01-01T00:00:00Z'
      }
    ]
    
    // Demo data
    this.demoData = {
      categories: [
        { id: 1, name: 'อิเล็กทรอนิกส์', description: 'สินค้าอิเล็กทรอนิกส์และอุปกรณ์', is_active: 1 },
        { id: 2, name: 'เสื้อผ้า', description: 'เสื้อผ้าแฟชั่น', is_active: 1 },
        { id: 3, name: 'อาหาร', description: 'อาหารและเครื่องดื่ม', is_active: 1 }
      ],
      products: [
        { id: 1, name: 'สมาร์ทโฟน Samsung', category_id: 1, price: 15000, stock: 50, sku: 'PHONE001' },
        { id: 2, name: 'เสื้อเชิ้ตผู้ชาย', category_id: 2, price: 599, stock: 100, sku: 'SHIRT001' },
        { id: 3, name: 'น้ำดื่ม 600ml', category_id: 3, price: 10, stock: 200, sku: 'WATER001' }
      ],
      customers: [
        { id: 1, name: 'คุณสมชาย ใจดี', email: 'somchai@email.com', phone: '081-234-5678' },
        { id: 2, name: 'คุณสมหญิง รักดี', email: 'somying@email.com', phone: '082-345-6789' }
      ]
    }
  }

  enable() {
    this.isEnabled = true
    console.log('🎭 Mock API enabled for demo mode')
  }

  disable() {
    this.isEnabled = false
    console.log('🔌 Mock API disabled - using real backend')
  }

  async simulateRequest(data, shouldFail = false) {
    return new Promise((resolve, reject) => {
      setTimeout(() => {
        if (shouldFail) {
          reject(new Error('Mock API Error'))
        } else {
          resolve({ data, status: 200 })
        }
      }, this.delay)
    })
  }

  // Authentication endpoints
  async login(credentials) {
    if (!this.isEnabled) throw new Error('Mock API not enabled')
    
    const user = this.demoUsers.find(
      u => u.email === credentials.email && u.password === credentials.password
    )
    
    if (!user) {
      throw new Error('อีเมลหรือรหัสผ่านไม่ถูกต้อง')
    }
    
    // Generate mock token
    const token = btoa(JSON.stringify({
      user_id: user.id,
      email: user.email,
      role: user.role,
      exp: Date.now() + 3600000 // 1 hour
    }))
    
    return this.simulateRequest({
      success: true,
      token: token,
      user: {
        id: user.id,
        email: user.email,
        name: user.name,
        role: user.role
      }
    })
  }

  async logout() {
    if (!this.isEnabled) throw new Error('Mock API not enabled')
    
    return this.simulateRequest({
      success: true,
      message: 'ออกจากระบบเรียบร้อย'
    })
  }

  async getMe(token) {
    if (!this.isEnabled) throw new Error('Mock API not enabled')
    
    try {
      const decoded = JSON.parse(atob(token))
      const user = this.demoUsers.find(u => u.id === decoded.user_id)
      
      if (!user || decoded.exp < Date.now()) {
        throw new Error('Token expired')
      }
      
      return this.simulateRequest({
        id: user.id,
        email: user.email,
        name: user.name,
        role: user.role
      })
    } catch (error) {
      throw new Error('Invalid token')
    }
  }

  // Data endpoints
  async getCategories() {
    if (!this.isEnabled) throw new Error('Mock API not enabled')
    return this.simulateRequest({ success: true, data: this.demoData.categories })
  }

  async getProducts() {
    if (!this.isEnabled) throw new Error('Mock API not enabled')
    return this.simulateRequest({ success: true, data: this.demoData.products })
  }

  async getCustomers() {
    if (!this.isEnabled) throw new Error('Mock API not enabled')
    return this.simulateRequest({ success: true, data: this.demoData.customers })
  }

  // Dashboard stats
  async getDashboardStats() {
    if (!this.isEnabled) throw new Error('Mock API not enabled')
    
    const stats = {
      total_products: this.demoData.products.length,
      total_categories: this.demoData.categories.length,
      total_customers: this.demoData.customers.length,
      total_sales: 150,
      monthly_revenue: 125000,
      low_stock_items: 5
    }
    
    return this.simulateRequest({ success: true, data: stats })
  }
}

// Export singleton instance
const mockApi = new MockApiService()
export default mockApi