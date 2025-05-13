<?php
include_once '../Settings/db.php';

// Fetch products from database with supplier information
$sql = "SELECT p.*, m.username as supplier_name 
        FROM products p 
        LEFT JOIN microservice_users m ON p.supplier_id = m.id 
        ORDER BY p.product_id ASC";
$result = $conn->query($sql);
$products = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>
<div id="products" class="section">
    <h2>Supplier Products</h2>
    <?php if (!empty($products)): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Supplier</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Category</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?= htmlspecialchars($product['product_id']) ?></td>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td><?= htmlspecialchars($product['supplier_name']) ?></td>
                <td>₱<?= htmlspecialchars(number_format($product['price'], 2)) ?></td>
                <td><?= htmlspecialchars($product['stock']) ?></td>
                <td><?= htmlspecialchars($product['category']) ?></td>
                <td><?= htmlspecialchars($product['description']) ?></td>
                <td>
                    <button class="edit-btn" data-id="<?= $product['product_id'] ?>">Edit</button>
                    <button class="delete-btn" data-id="<?= $product['product_id'] ?>">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No products found in the database.</p>
    <?php endif; ?>
</div>