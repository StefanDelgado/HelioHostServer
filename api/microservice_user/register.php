<?php
// filepath: /C:/xampp/htdocs/WebDesign_BSITA-2/2nd sem/Joshan_System/HelioHostServer/api/microservice_user/register.php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

include '../../Settings/db.php';

$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $input['email'];
    $username = $input['username'];
    $password = password_hash($input['password'], PASSWORD_DEFAULT);
    $date_created = date('Y-m-d H:i:s');
    $roles = $input['roles'] ?? 'user'; // Default role is 'user'
    $date_updated = $date_created;

    // Create microservice user
    $sql = "INSERT INTO microservice_users (email, username, password, api_id, date_created, roles, date_updated) VALUES ('$email', '$username', '$password', '$api_id', '$date_created', '$roles', '$date_updated')";

    if ($conn->query($sql) === TRUE) {
        // Update the users table with the new API ID
        $updateSql = "UPDATE users SET api_id = '$api_id', date_created = '$date_created', roles = '$roles', date_updated = '$date_updated' WHERE username = '$username'";
        if ($conn->query($updateSql) === TRUE) {
            echo json_encode(['message' => 'Microservice user registered successfully']);
        } else {
            echo json_encode(['message' => 'Error updating users table: ' . $conn->error]);
        }
    } else {
        echo json_encode(['message' => 'Error: ' . $sql . '<br>' . $conn->error]);
    }
} else {
    echo json_encode(['message' => 'Invalid request method']);
    http_response_code(405);
}
?>