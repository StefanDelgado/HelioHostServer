<?php
// filepath: /C:/xampp/htdocs/WebDesign_BSITA-2/2nd sem/Joshan_System/HelioHostServer/api/microservice_supplier_products/crud/read.php

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
    if (isset($_GET['supplier_id'])) {
        $supplier_id = $_GET['supplier_id'];
        $sql = "SELECT * FROM products WHERE supplier_id = '$supplier_id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $products = [];
            while ($product = $result->fetch_assoc()) {
                $products[] = $product;
            }
            echo json_encode($products);
        } else {
            echo json_encode(['message' => 'No products found for the given supplier']);
            http_response_code(404);
        }
    } else {
        echo json_encode(['message' => 'Supplier ID not specified']);
        http_response_code(400);
    }
} else {
    echo json_encode(['message' => 'Invalid request method']);
    http_response_code(405);
}
?>