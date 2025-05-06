<!-- Orders Section -->
<div id="orders" class="section">
        <h2>Orders</h2>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Business ID</th>
                <th>Supplier ID</th>
                <th>Total Price</th>
                <th>Order Status</th>
                <th>Order Date</th>
            </tr>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= $order['order_id'] ?></td>
                <td><?= $order['business_id'] ?></td>
                <td><?= $order['supplier_id'] ?></td>
                <td><?= $order['total_price'] ?></td>
                <td><?= $order['order_status'] ?></td>
                <td><?= $order['order_date'] ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>