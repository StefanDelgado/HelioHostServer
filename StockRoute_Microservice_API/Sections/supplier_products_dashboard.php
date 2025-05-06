<?php
include_once '../Settings/db.php';

// Fetch products from database
$sql = "SELECT * FROM microservice_products ORDER BY product_id ASC";
$result = $conn->query($sql);
$products = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>
<!-- Supplier Products Section -->
<div id="products" class="section">
        <h2>Supplier Products</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Supplier ID</th>
                <th>Order ID</th>
            </tr>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?= $product['product_id'] ?></td>
                <td><?= $product['name'] ?></td>
                <td><?= $product['price'] ?></td>
                <td><?= $product['supplier_id'] ?></td>
                <td><?= $product['order_id'] ?? 'N/A' ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>