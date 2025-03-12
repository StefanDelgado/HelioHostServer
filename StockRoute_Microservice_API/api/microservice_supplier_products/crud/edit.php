<?php
// filepath: /C:/xampp/htdocs/WebDesign_BSITA-2/2nd sem/Joshan_System/HelioHostServer/api/microservice_supplier_products/crud/edit.php

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

$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $input['product_id'];
    $name = $input['name'];
    $supplier_id = $input['supplier_id'];
    $price = $input['price'];
    $stock = $input['stock'];
    $category = $input['category'];
    $description = $input['description'];
    $image_url = $input['image_url'];

    // Update product
    $sql = "UPDATE products SET name = '$name', supplier_id = '$supplier_id', price = '$price', stock = '$stock', category = '$category', description = '$description', image_url = '$image_url' WHERE product_id = '$product_id'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['message' => 'Product updated successfully']);
    } else {
        echo json_encode(['message' => 'Error: ' . $sql . '<br>' . $conn->error]);
    }
} else {
    echo json_encode(['message' => 'Invalid request method']);
    http_response_code(405);
}
?>