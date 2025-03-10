<?php
// filepath: /C:/xampp/htdocs/WebDesign_BSITA-2/2nd sem/Joshan_System/HelioHostServer/api/authenticator.php

include dirname(__DIR__) . '/Settings/db.php';

function authenticate() {
    global $conn;
    $headers = apache_request_headers();
    error_log("Headers: " . json_encode($headers)); // Log all headers

    $api_key = $headers['Authorization'] ?? '';
    error_log("API Key: $api_key"); // Log the API key

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