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

// Fetch all suppliers for the dropdown
$suppliers_result = $conn->query("SELECT id, username FROM microservice_users WHERE role_id = 303");
$suppliers = [];
if ($suppliers_result && $suppliers_result->num_rows > 0) {
    while($row = $suppliers_result->fetch_assoc()) {
        $suppliers[] = $row;
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
            <tr data-id="<?= $product['product_id'] ?>" data-supplier-id="<?= $product['supplier_id'] ?>">
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
<div id="editModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:30px 20px; border-radius:8px; max-width:400px; margin:auto; position:relative;">
        <h3>Edit Product</h3>
        <form id="editForm">
            <input type="hidden" id="edit-product-id">
            <div>
                <label for="edit-product-name">Product Name:</label>
                <input type="text" id="edit-product-name" required>
            </div>
            <div>
                <label for="edit-supplier-id">Supplier:</label>
                <select id="edit-supplier-id" required>
                    <?php foreach ($suppliers as $supplier): ?>
                        <option value="<?= $supplier['id'] ?>"><?= htmlspecialchars($supplier['username']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="edit-price">Price:</label>
                <input type="number" step="0.01" id="edit-price" required>
            </div>
            <div>
                <label for="edit-stock">Stock:</label>
                <input type="number" id="edit-stock" required>
            </div>
            <div>
                <label for="edit-category">Category:</label>
                <input type="text" id="edit-category" required>
            </div>
            <div>
                <label for="edit-description">Description:</label>
                <textarea id="edit-description" required></textarea>
            </div>
            <div style="margin-top:15px; text-align:right;">
                <button type="button" id="cancelEdit" style="margin-right:10px;">Cancel</button>
                <button type="submit">Confirm</button>
            </div>
        </form>
    </div>
</div>
