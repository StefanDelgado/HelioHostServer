<?php
// filepath: /C:/xampp/htdocs/WebDesign_BSITA-2/2nd sem/Joshan_System/HelioHostServer/StockRoute_Microservice_API/api/microservice_delivery_orders/crud/create.php

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
    // Start transaction
    $conn->begin_transaction();

    try {
        $business_id = $input['business_id'];
        $supplier_id = $input['supplier_id'];
        $total_price = $input['total_price'];
        $order_status = $input['order_status'] ?? 'Pending';
        $delivery_date = $input['delivery_date'] ?? null;
        $order_items = $input['order_items']; // Array of items

        // Create order
        $sql = "INSERT INTO orders (business_id, supplier_id, total_price, order_status, delivery_date) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iidss", $business_id, $supplier_id, $total_price, $order_status, $delivery_date);
        $stmt->execute();
        
        $order_id = $conn->insert_id;

        // Insert order items
        $item_sql = "INSERT INTO order_items (order_id, product_id, quantity, unit_price, subtotal) 
                     VALUES (?, ?, ?, ?, ?)";
        
        $item_stmt = $conn->prepare($item_sql);
        
        foreach ($order_items as $item) {
            $subtotal = $item['quantity'] * $item['unit_price'];
            // Fix: Added missing parameter in bind_param
            $item_stmt->bind_param("iiidd", 
                $order_id,
                $item['product_id'],
                $item['quantity'],
                $item['unit_price'],
                $subtotal
            );
            $item_stmt->execute();
        }

        // Commit transaction
        $conn->commit();

        echo json_encode([
            'message' => 'Order created successfully',
            'order_id' => $order_id
        ]);

    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo json_encode(['message' => 'Error: ' . $e->getMessage()]);
        http_response_code(500);
    }
} else {
    echo json_encode(['message' => 'Invalid request method']);
    http_response_code(405);
}
?>