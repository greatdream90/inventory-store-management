// Netlify Serverless Function - API Handler
const mysql = require('mysql2/promise');

// Database configuration
const dbConfig = {
  host: process.env.DB_HOST || 'localhost',
  port: process.env.DB_PORT || 3306,
  user: process.env.DB_USER || 'root',
  password: process.env.DB_PASSWORD || '',
  database: process.env.DB_DATABASE || 'inventory_store',
  charset: 'utf8mb4'
};

// CORS headers
const corsHeaders = {
  'Access-Control-Allow-Origin': process.env.FRONTEND_URL || '*',
  'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE, OPTIONS',
  'Access-Control-Allow-Headers': 'Content-Type, Authorization',
  'Access-Control-Allow-Credentials': 'true',
  'Content-Type': 'application/json'
};

// Main handler
exports.handler = async (event, context) => {
  // Handle CORS preflight
  if (event.httpMethod === 'OPTIONS') {
    return {
      statusCode: 200,
      headers: corsHeaders,
      body: ''
    };
  }

  try {
    // Parse path
    const path = event.path.replace('/.netlify/functions/api/', '');
    const method = event.httpMethod;

    // Connect to database
    const connection = await mysql.createConnection(dbConfig);

    let response;

    // Authentication endpoints
    if (path === 'auth/login' && method === 'POST') {
      response = await handleLogin(connection, JSON.parse(event.body));
    } else if (path === 'auth/logout' && method === 'POST') {
      response = await handleLogout();
    } else if (path === 'auth/me' && method === 'GET') {
      response = await handleMe(event.headers.authorization);
    } else if (path === 'categories' && method === 'GET') {
      response = await handleGetCategories(connection);
    } else if (path === 'products' && method === 'GET') {
      response = await handleGetProducts(connection);
    } else if (path === 'health' || path === '') {
      response = { status: 'OK', timestamp: new Date().toISOString() };
    } else {
      response = { error: 'Endpoint not found', path, method };
    }

    await connection.end();

    return {
      statusCode: response.error ? (response.path ? 404 : 500) : 200,
      headers: corsHeaders,
      body: JSON.stringify(response)
    };

  } catch (error) {
    console.error('API Error:', error);
    return {
      statusCode: 500,
      headers: corsHeaders,
      body: JSON.stringify({ error: 'Internal server error', message: error.message })
    };
  }
};

// Login handler
async function handleLogin(connection, body) {
  if (!body.email || !body.password) {
    throw new Error('Email and password required');
  }

  // Check user in database
  const [rows] = await connection.execute(
    'SELECT * FROM users WHERE email = ? AND is_active = 1',
    [body.email]
  );

  if (rows.length > 0) {
    const user = rows[0];
    // Note: You'll need to implement proper password verification
    if (user.password && body.password === 'admin123') {
      const token = Buffer.from(JSON.stringify({
        user_id: user.id,
        email: user.email,
        role: user.role,
        exp: Date.now() + 3600000 // 1 hour
      })).toString('base64');

      return {
        success: true,
        token,
        user: {
          id: user.id,
          email: user.email,
          name: user.name,
          role: user.role
        }
      };
    }
  }

  // Demo accounts fallback
  const demoAccounts = {
    'admin@demo.com': { password: 'admin123', role: 'admin', name: 'Admin User' },
    'admin@inventory.com': { password: 'admin123', role: 'admin', name: 'Administrator' }
  };

  if (demoAccounts[body.email] && demoAccounts[body.email].password === body.password) {
    const user = demoAccounts[body.email];
    const token = Buffer.from(JSON.stringify({
      email: body.email,
      role: user.role,
      exp: Date.now() + 3600000
    })).toString('base64');

    return {
      success: true,
      token,
      user: {
        email: body.email,
        name: user.name,
        role: user.role
      }
    };
  }

  throw new Error('อีเมลหรือรหัสผ่านไม่ถูกต้อง');
}

// Logout handler
async function handleLogout() {
  return {
    success: true,
    message: 'ออกจากระบบเรียบร้อย'
  };
}

// Get user info
async function handleMe(authHeader) {
  if (!authHeader || !authHeader.startsWith('Bearer ')) {
    throw new Error('Token required');
  }

  const token = authHeader.substring(7);
  const decoded = JSON.parse(Buffer.from(token, 'base64').toString());

  if (!decoded.exp || decoded.exp < Date.now()) {
    throw new Error('Token expired');
  }

  return {
    email: decoded.email,
    name: decoded.name || 'Demo User',
    role: decoded.role
  };
}

// Get categories
async function handleGetCategories(connection) {
  const [rows] = await connection.execute(
    'SELECT * FROM categories WHERE is_active = 1 ORDER BY created_at DESC'
  );

  return {
    success: true,
    data: rows
  };
}

// Get products
async function handleGetProducts(connection) {
  const [rows] = await connection.execute(`
    SELECT p.*, c.name as category_name, c.color as category_color
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    WHERE p.is_active = 1 
    ORDER BY p.created_at DESC
  `);

  return {
    success: true,
    data: {
      data: rows,
      total: rows.length,
      per_page: 15,
      current_page: 1
    }
  };
}