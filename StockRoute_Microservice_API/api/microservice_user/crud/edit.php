<?php
// filepath: StockRoute_Microservice_API/api/microservice_user/crud/edit.php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit();

include '../../../Settings/db.php';

$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $input['id'];
    $username = $input['username'];
    $email = $input['email'];
    $role_id = $input['role_id'];
    $type_id = $input['type_id'];

    $sql = "UPDATE microservice_users SET username=?, email=?, role_id=?, type_id=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiii", $username, $email, $role_id, $type_id, $id);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'User updated successfully']);
    } else {
        echo json_encode(['message' => 'Error: ' . $stmt->error]);
    }
} else {
    echo json_encode(['message' => 'Invalid request method']);
    http_response_code(405);
}
?>