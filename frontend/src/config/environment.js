// Environment Configuration
// Auto-detects localhost vs production environment

const isLocalhost = () => {
  return (
    window.location.hostname === 'localhost' ||
    window.location.hostname === '127.0.0.1' ||
    window.location.hostname === '[::1]' ||
    window.location.hostname.includes('localhost')
  )
}

const isDevelopment = () => {
  return process.env.NODE_ENV === 'development' || isLocalhost()
}

// Environment-specific configurations
const environments = {
  localhost: {
    API_BASE_URL: 'http://localhost:8000',
    FRONTEND_URL: 'http://localhost:3001',
    USE_MOCK_API: true, // Enable for demo without backend
    DEBUG_MODE: true,
    CORS_ENABLED: true
  },
  
  production: {
    API_BASE_URL: 'https://candid-puffpuff-5120a2.netlify.app/.netlify/functions/api',
    FRONTEND_URL: 'https://candid-puffpuff-5120a2.netlify.app',
    USE_MOCK_API: false,
    DEBUG_MODE: false,
    CORS_ENABLED: false
  }
}

// Get current environment config
const getConfig = () => {
  const env = isLocalhost() ? 'localhost' : 'production'
  
  if (isDevelopment()) {
    console.log(`🔧 Environment: ${env}`)
    console.log('Config:', environments[env])
  }
  
  return {
    ...environments[env],
    ENVIRONMENT: env,
    IS_LOCALHOST: isLocalhost(),
    IS_DEVELOPMENT: isDevelopment()
  }
}

export default getConfig()
export { getConfig, isLocalhost, isDevelopment }