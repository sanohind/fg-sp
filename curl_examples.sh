#!/bin/bash

# Operator API Testing Script
# Base URL - sesuaikan dengan environment Anda
BASE_URL="http://localhost:8000/api"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

# Function to extract token from login response
extract_token() {
    local response="$1"
    echo "$response" | grep -o '"token":"[^"]*"' | cut -d'"' -f4
}

# Function to test API endpoint
test_endpoint() {
    local method="$1"
    local endpoint="$2"
    local data="$3"
    local token="$4"
    
    print_status "Testing: $method $endpoint"
    
    if [ "$method" = "GET" ]; then
        if [ -n "$token" ]; then
            response=$(curl -s -w "\nHTTP_STATUS:%{http_code}" \
                -H "Authorization: Bearer $token" \
                -H "Accept: application/json" \
                "$BASE_URL$endpoint")
        else
            response=$(curl -s -w "\nHTTP_STATUS:%{http_code}" \
                -H "Accept: application/json" \
                "$BASE_URL$endpoint")
        fi
    else
        if [ -n "$token" ]; then
            response=$(curl -s -w "\nHTTP_STATUS:%{http_code}" \
                -X "$method" \
                -H "Authorization: Bearer $token" \
                -H "Content-Type: application/json" \
                -H "Accept: application/json" \
                -d "$data" \
                "$BASE_URL$endpoint")
        else
            response=$(curl -s -w "\nHTTP_STATUS:%{token}" \
                -X "$method" \
                -H "Content-Type: application/json" \
                -H "Accept: application/json" \
                -d "$data" \
                "$BASE_URL$endpoint")
        fi
    fi
    
    # Extract HTTP status
    http_status=$(echo "$response" | tail -n1 | cut -d':' -f2)
    response_body=$(echo "$response" | head -n -1)
    
    if [ "$http_status" -ge 200 ] && [ "$http_status" -lt 300 ]; then
        print_success "HTTP $http_status - $method $endpoint"
        echo "$response_body" | jq '.' 2>/dev/null || echo "$response_body"
    else
        print_error "HTTP $http_status - $method $endpoint"
        echo "$response_body" | jq '.' 2>/dev/null || echo "$response_body"
    fi
    
    echo ""
}

# Main testing flow
main() {
    print_status "Starting Operator API Testing..."
    echo ""
    
    # Test 1: Login (Public endpoint)
    print_status "=== Test 1: Authentication ==="
    login_data='{"username": "operator123", "password": "password123"}'
    login_response=$(curl -s \
        -H "Content-Type: application/json" \
        -H "Accept: application/json" \
        -d "$login_data" \
        "$BASE_URL/auth/login")
    
    # Extract token
    auth_token=$(extract_token "$login_response")
    
    if [ -n "$auth_token" ]; then
        print_success "Login successful, token obtained"
        print_status "Token: ${auth_token:0:20}..."
    else
        print_error "Login failed, cannot continue testing"
        echo "$login_response" | jq '.' 2>/dev/null || echo "$login_response"
        exit 1
    fi
    
    echo ""
    
    # Test 2: Get User Info
    print_status "=== Test 2: Get User Info ==="
    test_endpoint "GET" "/auth/me" "" "$auth_token"
    
    # Test 3: Get Dashboard
    print_status "=== Test 3: Get Dashboard ==="
    test_endpoint "GET" "/operator/dashboard" "" "$auth_token"
    
    # Test 4: Scan Slot for Store
    print_status "=== Test 4: Scan Slot for Store ==="
    store_scan_data='{"slot_name": "A-01-01", "erp_code": "ERP001", "quantity": 50, "lot_number": "LOT20250115"}'
    test_endpoint "POST" "/operator/store/scan-slot" "$store_scan_data" "$auth_token"
    
    # Test 5: Store by ERP
    print_status "=== Test 5: Store by ERP ==="
    store_data='{"slot_name": "A-01-01", "erp_code": "ERP001", "quantity": 50, "lot_number": "LOT20250115", "notes": "Stock baru dari supplier"}'
    test_endpoint "POST" "/operator/store/by-erp" "$store_data" "$auth_token"
    
    # Test 6: Scan Slot for Pull
    print_status "=== Test 6: Scan Slot for Pull ==="
    pull_scan_data='{"slot_name": "A-01-01", "quantity": 20}'
    test_endpoint "POST" "/operator/pull/scan-slot" "$pull_scan_data" "$auth_token"
    
    # Test 7: Pull by Lot Number
    print_status "=== Test 7: Pull by Lot Number ==="
    pull_data='{"slot_name": "A-01-01", "quantity": 20, "lot_number": "LOT20250115", "notes": "Untuk production line A"}'
    test_endpoint "POST" "/operator/pull/by-lot" "$pull_data" "$auth_token"
    
    # Test 8: Get Slot Info
    print_status "=== Test 8: Get Slot Info ==="
    test_endpoint "GET" "/operator/slot/A-01-01" "" "$auth_token"
    
    # Test 9: Search Items
    print_status "=== Test 9: Search Items ==="
    test_endpoint "GET" "/operator/search/items?query=bearing&limit=10" "" "$auth_token"
    
    # Test 10: Get Activities
    print_status "=== Test 10: Get Activities ==="
    test_endpoint "GET" "/operator/activities?action=store&limit=5" "" "$auth_token"
    
    # Test 11: Refresh Token
    print_status "=== Test 11: Refresh Token ==="
    test_endpoint "POST" "/auth/refresh" "" "$auth_token"
    
    # Test 12: Logout
    print_status "=== Test 12: Logout ==="
    test_endpoint "POST" "/auth/logout" "" "$auth_token"
    
    echo ""
    print_status "=== Testing Complete ==="
    print_status "Check the responses above for any errors or issues."
}

# Check if jq is installed for JSON formatting
if ! command -v jq &> /dev/null; then
    print_warning "jq is not installed. Install jq for better JSON formatting:"
    print_warning "  Ubuntu/Debian: sudo apt-get install jq"
    print_warning "  macOS: brew install jq"
    print_warning "  Windows: choco install jq"
    echo ""
fi

# Check if curl is installed
if ! command -v curl &> /dev/null; then
    print_error "curl is not installed. Please install curl to run this script."
    exit 1
fi

# Run main function
main "$@"
