// Environment Configuration
const isDevelopment = import.meta.env.DEV || window.location.hostname === 'localhost'
const isLocalhost = window.location.hostname === 'localhost'

// API Configuration
export const apiConfig = {
  // Auto-detect environment
  isDevelopment,
  isLocalhost,
  
  // API Base URLs
  baseURL: isDevelopment 
    ? 'http://localhost:8000'  // Local development
    : 'https://candid-puffpuff-5120a2.netlify.app/.netlify/functions/api', // Production
  
  // Demo Mode - always enabled for localhost (frontend-only) 
  demoMode: isLocalhost ? true : (import.meta.env.VITE_DEMO_MODE !== 'false'), // Always demo mode for localhost
  
  // Timeout settings
  timeout: 30000,
  
  // Features toggles
  features: {
    realTimeSync: !isDevelopment,
    analytics: !isDevelopment,
    debugLogging: isDevelopment
  }
}

// App Configuration
export const appConfig = {
  name: 'Inventory Store Management',
  version: '1.0.0',
  
  // Theme
  theme: {
    primary: '#667eea',
    secondary: '#764ba2'
  },
  
  // Pagination
  pagination: {
    defaultPerPage: 15,
    maxPerPage: 100
  },
  
  // Cache settings
  cache: {
    ttl: isDevelopment ? 300 : 1800, // 5 min dev, 30 min prod
    maxSize: 100
  }
}

// Export environment info for debugging
export const envInfo = {
  mode: isDevelopment ? 'development' : 'production',
  hostname: window.location.hostname,
  origin: window.location.origin,
  demoMode: apiConfig.demoMode,
  timestamp: new Date().toISOString()
}

// Log environment info in development
if (apiConfig.features.debugLogging) {
  console.log('🚀 App Environment:', envInfo)
  console.log('⚙️ API Config:', apiConfig)
}