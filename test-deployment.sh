#!/bin/bash

# Vercel Deployment Test Script

echo "🚀 Testing Inventory Store for Vercel Deployment"
echo "================================================"

# Test 1: Check if build works
echo "📦 Testing build process..."
cd frontend
npm run build

if [ $? -eq 0 ]; then
    echo "✅ Build successful!"
else
    echo "❌ Build failed!"
    exit 1
fi

# Test 2: Check backend connectivity
echo "🔗 Testing backend connectivity..."
response=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/health)

if [ $response -eq 200 ]; then
    echo "✅ Backend accessible at localhost:8000"
else
    echo "❌ Backend not accessible at localhost:8000"
fi

# Test 3: Check network accessibility
echo "🌐 Testing network accessibility..."
local_ip=$(ipconfig | grep "IPv4" | head -1 | awk '{print $NF}')
echo "Local IP detected: $local_ip"

# Test 4: Check required files
echo "📁 Checking required files..."
files=("vercel.json" ".env.production" "package.json" "vite.config.js")

for file in "${files[@]}"; do
    if [ -f "$file" ]; then
        echo "✅ $file exists"
    else
        echo "❌ $file missing"
    fi
done

echo ""
echo "🎯 Next Steps:"
echo "1. Install Git: https://git-scm.com/download/win"
echo "2. Push code to GitHub"
echo "3. Deploy on Vercel: https://vercel.com"
echo "4. Set VITE_API_URL to: http://$local_ip:8000"
echo ""
echo "📖 Full instructions: See VERCEL-DEPLOYMENT.md"