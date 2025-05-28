<?php
function create_microservice_user($conn, $email, $username, $password, $role_id, $type_id) {
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    $api_id = uniqid(); // Generate unique API ID
    $date_created = date('Y-m-d H:i:s');
    $date_updated = $date_created;

    $sql = "INSERT INTO microservice_users (email, username, password, api_id, date_created, role_id, type_id, date_updated) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return ['success' => false, 'message' => 'Prepare failed: ' . $conn->error];
    }

    $stmt->bind_param(
        "sssssiis",
        $email,
        $username,
        $password_hashed,
        $api_id,
        $date_created,
        $role_id,
        $type_id,
        $date_updated
    );

    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'Microservice user registered successfully'];
    } else {
        return ['success' => false, 'message' => 'Error: ' . $stmt->error];
    }
}