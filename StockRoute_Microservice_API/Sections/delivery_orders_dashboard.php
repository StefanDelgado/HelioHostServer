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
            <tr data-id="<?= $order['order_id'] ?>">
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
<div id="editModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:30px 20px; border-radius:8px; max-width:400px; margin:auto; position:relative;">
        <h3>Edit Delivery Order</h3>
        <form id="editForm">
            <input type="hidden" id="edit-order-id">
            <div>
                <label for="edit-order-status">Order Status:</label>
                <select id="edit-order-status" required>
                    <option value="Pending">Pending</option>
                    <option value="Processing">Processing</option>
                    <option value="Ready to Pickup">Ready to Pickup</option>
                    <option value="Delivering">Delivering</option>
                    <option value="Delivered">Delivered</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>
            <div style="margin-top:15px; text-align:right;">
                <button type="button" id="cancelEdit" style="margin-right:10px;">Cancel</button>
                <button type="submit">Confirm</button>
            </div>
        </form>
    </div>
</div>
<!-- Delete Modal -->
<div id="deleteModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); z-index:1001; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:30px 20px; border-radius:8px; max-width:350px; margin:auto; position:relative;">
        <h3>Confirm Deletion</h3>
        <p>Are you sure you want to delete this delivery order? This action cannot be undone.</p>
        <div style="margin-top:15px; text-align:right;">
            <button type="button" id="cancelDelete" style="margin-right:10px;">Cancel</button>
            <button type="button" id="confirmDelete" style="background:#dc3545; color:#fff;">Delete</button>
        </div>
    </div>
</div>
