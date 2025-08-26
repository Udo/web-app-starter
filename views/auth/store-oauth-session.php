<?php

// Simple endpoint to store OAuth session data
// This allows the frontend to communicate the OAuth service to the backend

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if ($input && isset($input['oauth_service']) && isset($input['oauth_state'])) {
        $_SESSION['oauth_service'] = $input['oauth_service'];
        $_SESSION['oauth_state'] = $input['oauth_state'];
        
        echo json_encode(['status' => 'success']);
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
