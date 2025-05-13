<?php
include_once '../Settings/db.php';

// Fetch orders from database with business and supplier information
$sql = "SELECT o.*, 
               b.username as business_name,
               s.username as supplier_name
        FROM orders o
        LEFT JOIN microservice_users b ON o.business_id = b.id
        LEFT JOIN microservice_users s ON o.supplier_id = s.id
        ORDER BY o.order_date DESC";
$result = $conn->query($sql);
$orders = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}
?>
<div id="orders" class="section">
    <h2>Delivery Orders</h2>
    <?php if (!empty($orders)): ?>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Business</th>
                <th>Supplier</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Order Date</th>
                <th>Delivery Date</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= htmlspecialchars($order['order_id']) ?></td>
                <td><?= htmlspecialchars($order['business_name']) ?></td>
                <td><?= htmlspecialchars($order['supplier_name']) ?></td>
                <td>₱<?= htmlspecialchars(number_format($order['total_price'], 2)) ?></td>
                <td><?= htmlspecialchars($order['order_status']) ?></td>
                <td><?= htmlspecialchars(date('M d, Y', strtotime($order['order_date']))) ?></td>
                <td><?= $order['delivery_date'] ? htmlspecialchars(date('M d, Y', strtotime($order['delivery_date']))) : 'Not set' ?></td>
                <td>
                    <button class="edit-btn" data-id="<?= $order['order_id'] ?>">Edit</button>
                    <button class="delete-btn" data-id="<?= $order['order_id'] ?>">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No orders found in the database.</p>
    <?php endif; ?>
</div>