<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

include '../../../Settings/db.php';
include '../../authenticator.php';

$input = json_decode(file_get_contents('php://input'), true);

// Log the incoming request
file_put_contents("log.txt", json_encode($input, JSON_PRETTY_PRINT));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Start transaction
    $conn->begin_transaction();

    try {
        $order_id = $input['order_id'] ?? null;
        $order_status = $input['order_status'] ?? null;
        $order_items = $input['order_items'] ?? [];

        if (!$order_id || !$order_status) {
            throw new Exception("Missing required fields: order_id or order_status");
        }

        // Update only the order status
        $sql = "UPDATE orders SET order_status = ? WHERE order_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $order_status, $order_id);
        $stmt->execute();

        // Commit transaction
        $conn->commit();

        echo json_encode([
            'message' => 'Order status updated successfully',
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
