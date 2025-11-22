<?php

/**
 * Script test API tự động bằng PHP
 * Sử dụng: php test-api.php [BASE_URL]
 */

$baseUrl = $argv[1] ?? 'http://localhost:8000';
$apiUrl = "$baseUrl/api";

echo "🧪 Bắt đầu test API tự động...\n";
echo "📍 Base URL: $baseUrl\n\n";

$passed = 0;
$failed = 0;
$token = null;

// Function để test endpoint
function testEndpoint($method, $endpoint, $data = null, $expectedStatus = 200, $description = '', $headers = []) {
    global $apiUrl, $passed, $failed;
    
    $url = "$apiUrl$endpoint";
    $ch = curl_init($url);
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $requestHeaders = ['Content-Type: application/json', 'Accept: application/json'];
    if (!empty($headers)) {
        $requestHeaders = array_merge($requestHeaders, $headers);
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "❌ ERROR: $description - cURL Error: $error\n";
        $failed++;
        return null;
    }
    
    if ($httpCode == $expectedStatus) {
        echo "✅ PASS: $description (HTTP $httpCode)\n";
        $passed++;
        return json_decode($response, true);
    } else {
        echo "❌ FAIL: $description (Expected $expectedStatus, got $httpCode)\n";
        echo "   Response: " . substr($response, 0, 200) . "\n";
        $failed++;
        return null;
    }
}

// Test Health Check (không có prefix /api)
echo "📋 Testing Health Check...\n";
$healthUrl = "$baseUrl/health";
$ch = curl_init($healthUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "✅ PASS: Health check (HTTP $httpCode)\n";
    $passed++;
} else {
    echo "❌ FAIL: Health check (Expected 200, got $httpCode)\n";
    $failed++;
}

// Test Auth - Login
echo "\n📋 Testing Auth APIs...\n";
$loginResponse = testEndpoint('POST', '/auth/login', [
    'email' => 'admin@gmail.com',
    'password' => 'password'
], 200, 'Login');

if ($loginResponse && isset($loginResponse['data']['token'])) {
    $token = $loginResponse['data']['token'];
    echo "   ✅ Token received\n";
}

// Test Properties (Public)
echo "\n📋 Testing Property APIs (Public)...\n";
testEndpoint('GET', '/properties/all', null, 200, 'Get all properties');
testEndpoint('GET', '/properties/by-type/1', null, 200, 'Get properties by type');
testEndpoint('GET', '/properties/by-location', null, 200, 'Get properties by location');
testEndpoint('GET', '/properties/featured', null, 200, 'Get featured properties');
testEndpoint('GET', '/properties/detail/1', null, 200, 'Get property detail');

// Test Locations
echo "\n📋 Testing Location APIs...\n";
testEndpoint('GET', '/locations/all', null, 200, 'Get all locations');
testEndpoint('GET', '/locations/cities', null, 200, 'Get cities');

// Test Property Types
echo "\n📋 Testing Property Type APIs...\n";
testEndpoint('GET', '/property_types/all', null, 200, 'Get all property types');

// Test Features
echo "\n📋 Testing Feature APIs...\n";
testEndpoint('GET', '/features/all', null, 200, 'Get all features');

// Test với Authentication (nếu có token)
if ($token) {
    echo "\n📋 Testing Authenticated APIs...\n";
    $authHeaders = ["Authorization: Bearer $token"];
    testEndpoint('GET', '/auth/me', null, 200, 'Get current user', $authHeaders);
}

// Summary
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 Kết quả test:\n";
echo "✅ Passed: $passed\n";
echo "❌ Failed: $failed\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

if ($failed == 0) {
    echo "🎉 Tất cả tests đều PASS!\n";
    exit(0);
} else {
    echo "⚠️  Có $failed test(s) FAILED\n";
    exit(1);
}

