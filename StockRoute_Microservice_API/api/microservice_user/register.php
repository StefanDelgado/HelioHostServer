<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true"); 

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include '../../Settings/db.php';

$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $input['email'];
    $username = $input['username'];
    $password = password_hash($input['password'], PASSWORD_DEFAULT);
    $api_id = uniqid(); // Generate unique API ID
    $date_created = date('Y-m-d H:i:s');
    $roles = $input['roles'] ?? 'user'; 
    $type_id = $input['type_id'] ?? null; 
    $date_updated = $date_created;

    // Use prepared statements
    $sql = "INSERT INTO microservice_users (email, username, password, api_id, date_created, role_id, type_id, date_updated) 
            VALUES (?, ?, ?, ?, ?, (SELECT role_id FROM roles WHERE role_name = ?), ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssis", $email, $username, $password, $api_id, $date_created, $roles, $type_id, $date_updated);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Microservice user registered successfully']);
    } else {
        echo json_encode(['message' => 'Error: ' . $stmt->error]);
    }
} else {
    echo json_encode(['message' => 'Invalid request method']);
    http_response_code(405);
}
?>
