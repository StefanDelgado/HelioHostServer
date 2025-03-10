<?php
// filepath: /C:/xampp/htdocs/WebDesign_BSITA-2/2nd sem/Joshan_System/HelioHostServer/api/microservice_user/login.php

include '../../Settings/db.php';
include '../authenticator.php';

// Authenticate the API key
authenticate();

$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $input['username'];
    $password = $input['password'];
    $sql = "SELECT * FROM microservice_users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            echo json_encode(['message' => 'Login successful', 'api_id' => $user['api_id']]);
        } else {
            echo json_encode(['message' => 'Invalid username or password']);
        }
    } else {
        echo json_encode(['message' => 'Invalid username or password']);
    }
} else {
    echo json_encode(['message' => 'Invalid request method']);
    http_response_code(405);
}
?>