<?php
// filepath: /C:/xampp/htdocs/WebDesign_BSITA-2/2nd sem/Joshan_System/HelioHostServer/StockRoute_Microservice_API/api/microservice_delivery_orders/crud/read.php

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
    // Check if specific order_id is provided
    if (isset($_GET['order_id'])) {
        // Fetch specific order with its items
        $order_id = $_GET['order_id'];
        
        // Get order details
        $order_sql = "SELECT o.*, 
                            b.username as business_name,
                            s.username as supplier_name
                     FROM orders o
                     JOIN microservice_users b ON o.business_id = b.id
                     JOIN microservice_users s ON o.supplier_id = s.id
                     WHERE o.order_id = ?";
        
        $stmt = $conn->prepare($order_sql);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $order_result = $stmt->get_result();

        if ($order_result->num_rows > 0) {
            $order = $order_result->fetch_assoc();
            
            // Get order items
            $items_sql = "SELECT oi.*, p.name as product_name, p.description
                         FROM order_items oi
                         JOIN products p ON oi.product_id = p.product_id
                         WHERE oi.order_id = ?";
            
            $items_stmt = $conn->prepare($items_sql);
            $items_stmt->bind_param("i", $order_id);
            $items_stmt->execute();
            $items_result = $items_stmt->get_result();
            
            $items = [];
            while ($item = $items_result->fetch_assoc()) {
                $items[] = $item;
            }
            
            $order['items'] = $items;
            echo json_encode($order);
        } else {
            echo json_encode(['message' => 'Order not found']);
            http_response_code(404);
        }
    } 
    // Check if business_id is provided to get their orders
    else if (isset($_GET['business_id'])) {
        $business_id = $_GET['business_id'];
        $sql = "SELECT o.*, 
                       s.username as supplier_name
                FROM orders o
                JOIN microservice_users s ON o.supplier_id = s.id
                WHERE o.business_id = ?
                ORDER BY o.order_date DESC";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $business_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $orders = [];
            while ($order = $result->fetch_assoc()) {
                $orders[] = $order;
            }
            echo json_encode($orders);
        } else {
            echo json_encode(['message' => 'No orders found for this business']);
            http_response_code(404);
        }
    }
    // Check if supplier_id is provided to get their orders
    else if (isset($_GET['supplier_id'])) {
        $supplier_id = $_GET['supplier_id'];
        $sql = "SELECT o.*, 
                       b.username as business_name
                FROM orders o
                JOIN microservice_users b ON o.business_id = b.id
                WHERE o.supplier_id = ?
                ORDER BY o.order_date DESC";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $supplier_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $orders = [];
            while ($order = $result->fetch_assoc()) {
                $orders[] = $order;
            }
            echo json_encode($orders);
        } else {
            echo json_encode(['message' => 'No orders found for this supplier']);
            http_response_code(404);
        }
    }
    else {
        echo json_encode(['message' => 'Please provide order_id, business_id, or supplier_id']);
        http_response_code(400);
    }
} else {
    echo json_encode(['message' => 'Invalid request method']);
    http_response_code(405);
}
?>