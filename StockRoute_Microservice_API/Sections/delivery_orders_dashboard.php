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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // DELETE
    document.querySelectorAll('.delete-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            if(confirm('Are you sure you want to delete this order?')) {
                fetch('../api/microservice_delivery_orders/crud/delete.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ order_id: this.dataset.id })
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    location.reload();
                });
            }
        });
    });

    // EDIT (simple prompt for demonstration: only status)
    document.querySelectorAll('.edit-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const row = btn.closest('tr');
            const order_id = btn.dataset.id;
            const status = row.children[4].textContent;

            const newStatus = prompt('Edit order status:', status);

            if(newStatus) {
                fetch('../api/microservice_delivery_orders/crud/edit.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        order_id: order_id,
                        order_status: newStatus
                    })
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    location.reload();
                });
            }
        });
    });
});
</script>