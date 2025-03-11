<?php
// filepath: /C:/xampp/htdocs/WebDesign_BSITA-2/2nd sem/Joshan_System/HelioHostServer/api/microservice_user/crud/read.php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

include '../../../Settings/db.php';
include '../../authenticator.php';

// Authenticate the API key
authenticate();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['username'])) {
        $username = $_GET['username'];
        $sql = "SELECT * FROM microservice_users WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            unset($user['password']); // Remove password from the response
            echo json_encode($user);
        } else {
            echo json_encode(['message' => 'User not found']);
            http_response_code(404);
        }
    } else {
        echo json_encode(['message' => 'Username not specified']);
        http_response_code(400);
    }
} else {
    echo json_encode(['message' => 'Invalid request method']);
    http_response_code(405);
}
?>