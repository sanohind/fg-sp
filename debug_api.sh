#!/bin/bash

# Debug API Script
BASE_URL="http://localhost:8000/api"

echo "=== Debug API Operator ==="
echo "Base URL: $BASE_URL"
echo ""

# Test 1: Check if server is running
echo "1. Testing server connection..."
if curl -s "$BASE_URL/auth/login" > /dev/null 2>&1; then
    echo "   ✓ Server is running"
else
    echo "   ✗ Server is not running or not accessible"
    exit 1
fi

echo ""

# Test 2: Try to login with operator
echo "2. Testing login with operator..."
LOGIN_RESPONSE=$(curl -s \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"username": "operator123", "password": "password123"}' \
    "$BASE_URL/auth/login")

echo "   Response: $LOGIN_RESPONSE"

# Check if login was successful
if echo "$LOGIN_RESPONSE" | grep -q '"success":true'; then
    echo "   ✓ Login successful"
    
    # Extract token
    TOKEN=$(echo "$LOGIN_RESPONSE" | grep -o '"token":"[^"]*"' | cut -d'"' -f4)
    echo "   Token: ${TOKEN:0:20}..."
    
    echo ""
    
    # Test 3: Test dashboard access
    echo "3. Testing dashboard access..."
    DASHBOARD_RESPONSE=$(curl -s \
        -H "Authorization: Bearer $TOKEN" \
        -H "Accept: application/json" \
        "$BASE_URL/operator/dashboard")
    
    echo "   Dashboard Response: $DASHBOARD_RESPONSE"
    
    if echo "$DASHBOARD_RESPONSE" | grep -q '"success":true'; then
        echo "   ✓ Dashboard access successful"
    else
        echo "   ✗ Dashboard access failed"
    fi
    
else
    echo "   ✗ Login failed"
    
    # Check if it's a role issue
    if echo "$LOGIN_RESPONSE" | grep -q "tidak memiliki akses operator"; then
        echo "   → Role access issue detected"
        echo "   → This suggests the user exists but role checking is failing"
    fi
fi

echo ""
echo "=== Debug Information ==="
echo "To debug further, run:"
echo "  php artisan debug:user-role operator123"
echo ""
echo "Or check the database directly:"
echo "  php artisan tinker"
echo "  App\Models\User::with('role')->where('username', 'operator123')->first()"
echo "  App\Models\Role::all()"
