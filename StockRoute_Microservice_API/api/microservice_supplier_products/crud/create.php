<?php
// filepath: /C:/xampp/htdocs/WebDesign_BSITA-2/2nd sem/Joshan_System/HelioHostServer/api/microservice_supplier_products/crud/create.php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

include '../../../Settings/db.php';

// Authenticate the API key

$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $input['name'];
    $supplier_id = $input['supplier_id'];
    $price = $input['price'];
    $stock = $input['stock'];
    $category = $input['category'];
    $description = $input['description'];
    $image_url = $input['image_url'];

    // Create product
    $sql = "INSERT INTO products (name, supplier_id, price, stock, category, description, image_url) VALUES ('$name', '$supplier_id', '$price', '$stock', '$category', '$description', '$image_url')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['message' => 'Product created successfully']);
    } else {
        echo json_encode(['message' => 'Error: ' . $sql . '<br>' . $conn->error]);
    }
} else {
    echo json_encode(['message' => 'Invalid request method']);
    http_response_code(405);
}
?>