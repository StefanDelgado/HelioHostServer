<?php
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

        // Delete order items first (cascade will handle this automatically, but being explicit)
        $delete_items_sql = "DELETE FROM order_items WHERE order_id = ?";
        $delete_items_stmt = $conn->prepare($delete_items_sql);
        $delete_items_stmt->bind_param("i", $order_id);
        $delete_items_stmt->execute();

        // Delete the order
        $delete_order_sql = "DELETE FROM orders WHERE order_id = ?";
        $delete_order_stmt = $conn->prepare($delete_order_sql);
        $delete_order_stmt->bind_param("i", $order_id);
        $delete_order_stmt->execute();

        // Check if order was actually deleted
        if ($delete_order_stmt->affected_rows > 0) {
            // Commit transaction
            $conn->commit();
            echo json_encode(['message' => 'Order deleted successfully']);
        } else {
            throw new Exception('Order not found');
        }

    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo json_encode(['message' => 'Error: ' . $e->getMessage()]);
        http_response_code(404);
    }
} else {
    echo json_encode(['message' => 'Invalid request method']);
    http_response_code(405);
}
?>