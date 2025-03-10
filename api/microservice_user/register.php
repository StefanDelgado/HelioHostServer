<?php
// filepath: /C:/xampp/htdocs/WebDesign_BSITA-2/2nd sem/Joshan_System/HelioHostServer/api/microservice_user/register.php

include '../../Settings/db.php';

$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $input['email'];
    $username = $input['username'];
    $password = password_hash($input['password'], PASSWORD_DEFAULT);
    $api_id = bin2hex(random_bytes(16)); // Generate a unique API ID

    // Create microservice user
    $sql = "INSERT INTO microservice_users (email, username, password, api_id) VALUES ('$email', '$username', '$password', '$api_id')";

    if ($conn->query($sql) === TRUE) {
        // Update the users table with the new API ID
        $updateSql = "UPDATE users SET api_id = '$api_id' WHERE username = '$username'";
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