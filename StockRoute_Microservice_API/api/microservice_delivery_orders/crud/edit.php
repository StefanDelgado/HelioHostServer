<?php
// filepath: /C:/xampp/htdocs/WebDesign_BSITA-2/2nd sem/Joshan_System/HelioHostServer/StockRoute_Microservice_API/api/microservice_delivery_orders/crud/edit.php

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
        $order_id = $input['order_id'];
        $business_id = $input['business_id'];
        $supplier_id = $input['supplier_id'];
        $total_price = $input['total_price'];
        $order_status = $input['order_status'];
        $delivery_date = $input['delivery_date'] ?? null;
        $order_items = $input['order_items']; // Array of items

        // Update order
        $sql = "UPDATE orders 
                SET business_id = ?, 
                    supplier_id = ?, 
                    total_price = ?, 
                    order_status = ?, 
                    delivery_date = ?
                WHERE order_id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iidssi", 
            $business_id, 
            $supplier_id, 
            $total_price, 
            $order_status, 
            $delivery_date,
            $order_id
        );
        $stmt->execute();

        // Delete existing order items
        $delete_sql = "DELETE FROM order_items WHERE order_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $order_id);
        $delete_stmt->execute();

        // Insert updated order items
        $item_sql = "INSERT INTO order_items (order_id, product_id, quantity, unit_price, subtotal) 
                     VALUES (?, ?, ?, ?, ?)";
        
        $item_stmt = $conn->prepare($item_sql);
        
        foreach ($order_items as $item) {
            $subtotal = $item['quantity'] * $item['unit_price'];
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
            'message' => 'Order updated successfully',
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