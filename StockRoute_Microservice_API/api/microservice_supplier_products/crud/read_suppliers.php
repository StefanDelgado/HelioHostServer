<?php
// filepath: /C:/xampp/htdocs/WebDesign_BSITA-2/2nd sem/Joshan_System/HelioHostServer/api/microservice_supplier_products/crud/read_suppliers.php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

include '../../../Settings/db.php';
include '../../authenticator.php';

// Authenticate the API key
//authenticate();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT DISTINCT microservice_users.* 
            FROM microservice_users 
            INNER JOIN products ON microservice_users.id = products.supplier_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $suppliers = [];
        while ($supplier = $result->fetch_assoc()) {
            $suppliers[] = $supplier;
        }
        echo json_encode($suppliers);
    } else {
        echo json_encode(['message' => 'No suppliers found']);
        http_response_code(404);
    }
} else {
    echo json_encode(['message' => 'Invalid request method']);
    http_response_code(405);
}
?>