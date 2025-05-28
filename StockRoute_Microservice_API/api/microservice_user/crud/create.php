<?php
// filepath: StockRoute_Microservice_API/api/microservice_user/crud/create.php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit();

include '../../../Settings/db.php';
include '../../../includes/user_functions.php';

$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $input['email'];
    $username = $input['username'];
    $password = $input['password'];
    $role_id = $input['role_id'];
    $type_id = $input['type_id'];

    $result = create_microservice_user($conn, $email, $username, $password, $role_id, $type_id);

    if ($result['success']) {
        echo json_encode(['message' => $result['message']]);
    } else {
        echo json_encode(['message' => $result['message']]);
    }
} else {
    echo json_encode(['message' => 'Invalid request method']);
    http_response_code(405);
}
?>