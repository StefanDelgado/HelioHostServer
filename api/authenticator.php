<?php
// filepath: /C:/xampp/htdocs/WebDesign_BSITA-2/2nd sem/Joshan_System/HelioHostServer/api/authenticator.php

include dirname(__DIR__) . '/Settings/db.php';

function authenticate() {
    global $conn;

    // Try to get the Authorization header from $_SERVER
    $api_key = '';
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $api_key = $_SERVER['HTTP_AUTHORIZATION'];
    } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
        $api_key = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
    } elseif (function_exists('apache_request_headers')) {
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $api_key = $headers['Authorization'];
        }
    }

    // Log headers and API key for debugging
    error_log("API Key: $api_key");

    if (empty($api_key)) {
        echo json_encode(['message' => 'API key is missing']);
        http_response_code(401);
        exit;
    }

    $sql = "SELECT * FROM users WHERE api_id = '$api_key'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return true;
    } else {
        echo json_encode(['message' => 'Invalid API key']);
        http_response_code(401);
        exit;
    }
}
?>