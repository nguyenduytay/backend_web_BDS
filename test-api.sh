#!/bin/bash

# Script test API tự động
# Sử dụng: ./test-api.sh [BASE_URL]

BASE_URL="${1:-http://localhost:8000}"
API_URL="${BASE_URL}/api"

echo "🧪 Bắt đầu test API tự động..."
echo "📍 Base URL: $BASE_URL"
echo ""

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Test counter
PASSED=0
FAILED=0

# Function to test endpoint
test_endpoint() {
    local method=$1
    local endpoint=$2
    local data=$3
    local expected_status=$4
    local description=$5
    
    if [ "$method" = "GET" ]; then
        response=$(curl -s -w "\n%{http_code}" -X GET "${API_URL}${endpoint}")
    elif [ "$method" = "POST" ]; then
        response=$(curl -s -w "\n%{http_code}" -X POST "${API_URL}${endpoint}" \
            -H "Content-Type: application/json" \
            -d "$data")
    fi
    
    http_code=$(echo "$response" | tail -n1)
    body=$(echo "$response" | sed '$d')
    
    if [ "$http_code" = "$expected_status" ]; then
        echo -e "${GREEN}✅ PASS${NC}: $description (HTTP $http_code)"
        ((PASSED++))
        return 0
    else
        echo -e "${RED}❌ FAIL${NC}: $description (Expected $expected_status, got $http_code)"
        echo "   Response: $body"
        ((FAILED++))
        return 1
    fi
}

# Test Health Check
echo "📋 Testing Health Check..."
test_endpoint "GET" "/health" "" "200" "Health check"

# Test Auth
echo ""
echo "📋 Testing Auth APIs..."
test_endpoint "POST" "/auth/login" '{"email":"admin@gmail.com","password":"password"}' "200" "Login"
# Lưu token từ response (cần parse JSON)

# Test Properties (Public)
echo ""
echo "📋 Testing Property APIs (Public)..."
test_endpoint "GET" "/properties/all" "" "200" "Get all properties"
test_endpoint "GET" "/properties/by-type/1" "" "200" "Get properties by type"
test_endpoint "GET" "/properties/by-location" "" "200" "Get properties by location"
test_endpoint "GET" "/properties/featured" "" "200" "Get featured properties"
test_endpoint "GET" "/properties/detail/1" "" "200" "Get property detail"

# Test Locations
echo ""
echo "📋 Testing Location APIs..."
test_endpoint "GET" "/locations/all" "" "200" "Get all locations"
test_endpoint "GET" "/locations/cities" "" "200" "Get cities"

# Test Property Types
echo ""
echo "📋 Testing Property Type APIs..."
test_endpoint "GET" "/property_types/all" "" "200" "Get all property types"

# Test Features
echo ""
echo "📋 Testing Feature APIs..."
test_endpoint "GET" "/features/all" "" "200" "Get all features"

# Summary
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "📊 Kết quả test:"
echo -e "${GREEN}✅ Passed: $PASSED${NC}"
echo -e "${RED}❌ Failed: $FAILED${NC}"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

if [ $FAILED -eq 0 ]; then
    echo -e "${GREEN}🎉 Tất cả tests đều PASS!${NC}"
    exit 0
else
    echo -e "${RED}⚠️  Có $FAILED test(s) FAILED${NC}"
    exit 1
fi

