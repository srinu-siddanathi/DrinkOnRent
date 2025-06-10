<?php
// Set headers for JSON response
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');

// Sample subscription plans data
$plans = [
    ['id' => 1, 'name' => 'Basic Plan', 'price' => 99.99, 'data_limit' => '50GB'],
    ['id' => 2, 'name' => 'Standard Plan', 'price' => 199.99, 'data_limit' => '100GB'],
    ['id' => 3, 'name' => 'Premium Plan', 'price' => 299.99, 'data_limit' => 'Unlimited']
];

// Sample subscriptions data
$subscriptions = [
    ['id' => 1, 'user_id' => 1, 'plan_id' => 2, 'status' => 'active', 'expires_at' => '2024-04-30'],
    ['id' => 2, 'user_id' => 2, 'plan_id' => 1, 'status' => 'pending', 'expires_at' => '2024-05-15']
];

// Get the endpoint from the URL parameter
$endpoint = $_GET['endpoint'] ?? '';

// Handle different endpoints
switch ($endpoint) {
    case 'plans':
        echo json_encode([
            'status' => 'success',
            'data' => $plans
        ]);
        break;
        
    case 'plan':
        $planId = $_GET['id'] ?? null;
        $plan = array_filter($plans, function($p) use ($planId) {
            return $p['id'] == $planId;
        });
        echo json_encode([
            'status' => 'success',
            'data' => array_values($plan)[0] ?? null
        ]);
        break;
        
    case 'subscriptions':
        echo json_encode([
            'status' => 'success',
            'data' => $subscriptions
        ]);
        break;
        
    case 'subscription':
        $subId = $_GET['id'] ?? null;
        $subscription = array_filter($subscriptions, function($s) use ($subId) {
            return $s['id'] == $subId;
        });
        echo json_encode([
            'status' => 'success',
            'data' => array_values($subscription)[0] ?? null
        ]);
        break;
        
    default:
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid endpoint',
            'available_endpoints' => [
                '/api.php?endpoint=plans',
                '/api.php?endpoint=plan&id=1',
                '/api.php?endpoint=subscriptions',
                '/api.php?endpoint=subscription&id=1'
            ]
        ]);
}
?> 