#!/bin/bash

# =============================================================================
# Expert Maintenance - Backend API Quick Test Script
# =============================================================================
# This script tests all backend API endpoints from the command line
# Usage: ./test_quick.sh
# =============================================================================

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
BASE_URL="http://localhost/ExpertMaintenance/backend/api.php"
EMPLOYEE_ID=1
LOGIN="admin"
PASSWORD="admin123"

# Counters
TOTAL_TESTS=0
PASSED_TESTS=0
FAILED_TESTS=0

# =============================================================================
# Helper Functions
# =============================================================================

print_header() {
    echo -e "\n${BLUE}============================================================${NC}"
    echo -e "${BLUE}$1${NC}"
    echo -e "${BLUE}============================================================${NC}\n"
}

print_test() {
    echo -e "${YELLOW}Testing:${NC} $1"
}

print_success() {
    echo -e "${GREEN}✓ PASS:${NC} $1"
    ((PASSED_TESTS++))
    ((TOTAL_TESTS++))
}

print_failure() {
    echo -e "${RED}✗ FAIL:${NC} $1"
    ((FAILED_TESTS++))
    ((TOTAL_TESTS++))
}

print_info() {
    echo -e "${BLUE}INFO:${NC} $1"
}

# Test endpoint function
test_endpoint() {
    local name="$1"
    local url="$2"
    local method="${3:-GET}"
    local data="${4:-}"

    print_test "$name"

    if [ "$method" == "POST" ] && [ -n "$data" ]; then
        response=$(curl -s -X POST "$url" \
            -H "Content-Type: application/json" \
            -d "$data")
    else
        response=$(curl -s "$url")
    fi

    # Check if response contains "success": true
    if echo "$response" | grep -q '"success":true\|"success": true'; then
        print_success "$name"
        return 0
    else
        print_failure "$name"
        echo -e "${RED}Response: $response${NC}"
        return 1
    fi
}

# =============================================================================
# Main Tests
# =============================================================================

print_header "🧪 Expert Maintenance - Backend API Tests"
print_info "Base URL: $BASE_URL"
print_info "Employee ID: $EMPLOYEE_ID"
print_info "Login: $LOGIN"

# Check if backend is accessible
print_header "1. Connectivity Tests"

test_endpoint "Backend Accessibility" "$BASE_URL?action=full_sync&last_sync=0&employee_id=$EMPLOYEE_ID"

# Test Database Connection
print_test "Database Connection"
response=$(curl -s "$BASE_URL?action=full_sync&last_sync=0&employee_id=$EMPLOYEE_ID")
if echo "$response" | grep -q '"employees"\|"clients"\|"sites"'; then
    print_success "Database Connection"
else
    print_failure "Database Connection"
    echo -e "${RED}Response: $response${NC}"
fi

# Authentication Tests
print_header "2. Authentication Tests"

test_endpoint "Authentication (Valid Credentials)" \
    "$BASE_URL?action=authenticate" \
    "POST" \
    "{\"login\":\"$LOGIN\",\"password\":\"$PASSWORD\"}"

test_endpoint "Authentication (Invalid Credentials)" \
    "$BASE_URL?action=authenticate" \
    "POST" \
    "{\"login\":\"invalid\",\"password\":\"wrong\"}"

# Synchronization Tests
print_header "3. Synchronization Tests"

test_endpoint "Full Sync" "$BASE_URL?action=full_sync&last_sync=0&employee_id=$EMPLOYEE_ID"
test_endpoint "Sync Employees" "$BASE_URL?action=sync_employees&last_sync=0"
test_endpoint "Sync Clients" "$BASE_URL?action=sync_clients&last_sync=0"
test_endpoint "Sync Sites" "$BASE_URL?action=sync_sites&last_sync=0"
test_endpoint "Sync Interventions" "$BASE_URL?action=sync_interventions&last_sync=0&employee_id=$EMPLOYEE_ID"
test_endpoint "Sync Tasks" "$BASE_URL?action=sync_tasks&last_sync=0"
test_endpoint "Sync Priorities" "$BASE_URL?action=sync_priorities&last_sync=0"
test_endpoint "Sync Images" "$BASE_URL?action=sync_images&last_sync=0"

# Intervention Tests
print_header "4. Intervention Tests"

test_endpoint "Get Intervention Details" "$BASE_URL?action=get_intervention&id=1"
test_endpoint "Get Intervention History" "$BASE_URL?action=get_intervention_history&site_id=1&limit=10"

# Test update intervention
print_test "Update Intervention"
response=$(curl -s -X POST "$BASE_URL?action=update_intervention" \
    -H "Content-Type: application/json" \
    -d "{\"id\":1,\"commentaires\":\"Test update from CLI\",\"valsync\":2}")
if echo "$response" | grep -q '"success":true\|"success": true'; then
    print_success "Update Intervention"
else
    print_failure "Update Intervention"
    echo -e "${RED}Response: $response${NC}"
fi

# Image Tests
print_header "5. Image Tests"

test_endpoint "Get Images" "$BASE_URL?action=get_images&intervention_id=1"
test_endpoint "Get Image Binary" "$BASE_URL?action=get_image_binary&id=1"

# Test invalid action
print_header "6. Error Handling Tests"

print_test "Invalid Action"
response=$(curl -s "$BASE_URL?action=invalid_action")
if echo "$response" | grep -q '"success":false\|"success": false'; then
    print_success "Invalid Action (Returns Error)"
else
    print_failure "Invalid Action (Should Return Error)"
fi

print_test "Invalid Intervention ID"
response=$(curl -s "$BASE_URL?action=get_intervention&id=99999")
if echo "$response" | grep -q '"success":false\|"success": false\|not found'; then
    print_success "Invalid Intervention ID (Returns Error)"
else
    print_failure "Invalid Intervention ID (Should Return Error)"
fi

# =============================================================================
# Summary
# =============================================================================

print_header "📊 Test Summary"

echo -e "Total Tests:  $TOTAL_TESTS"
echo -e "${GREEN}Passed:       $PASSED_TESTS${NC}"
echo -e "${RED}Failed:       $FAILED_TESTS${NC}"

if [ $FAILED_TESTS -eq 0 ]; then
    echo -e "\n${GREEN}🎉 All tests passed! Backend is working correctly.${NC}\n"
    exit 0
else
    echo -e "\n${RED}⚠️  Some tests failed. Check the output above for details.${NC}\n"
    echo -e "${YELLOW}Troubleshooting tips:${NC}"
    echo "1. Make sure XAMPP is running (Apache and MySQL)"
    echo "2. Verify the database 'gem' exists and contains data"
    echo "3. Check that api.php is in the correct location"
    echo "4. Review the API log: api.log"
    echo ""
    exit 1
fi
