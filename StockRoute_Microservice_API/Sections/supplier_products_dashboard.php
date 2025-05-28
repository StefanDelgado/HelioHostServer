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

// Debug output to verify suppliers
if (empty($suppliers)) {
    error_log("No suppliers found in database");
}

// Modify suppliers query to include role name for verification
$suppliers_result = $conn->query("
    SELECT m.id, m.username, m.role_id 
    FROM microservice_users m 
    WHERE m.role_id = 303 
    ORDER BY m.username ASC
");

$suppliers = [];
if ($suppliers_result && $suppliers_result->num_rows > 0) {
    while($row = $suppliers_result->fetch_assoc()) {
        $suppliers[] = $row;
    }
} else {
    error_log("Suppliers query failed or returned no results: " . $conn->error);
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
                <th>Actions
                    <button id="createProductBtn" style="margin-left:10px;">Create</button>
                </th>
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
                <label for="edit-supplier-id">Supplier ID:</label>
                <input type="number" id="edit-supplier-id" readonly>
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
<!-- Create Product Modal -->
<div id="createProductModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); z-index:1002; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:30px 20px; border-radius:8px; max-width:400px; margin:auto; position:relative;">
        <h3>Create Product</h3>
        <form id="createProductForm">
            <div>
                <label for="create-product-name">Product Name:</label>
                <input type="text" id="create-product-name" required>
            </div>
            
            <div>
                <label for="create-supplier-id">Supplier ID:</label>
                <input type="number" id="create-supplier-id" required>
            </div>
            <div>
                <label for="create-price">Price:</label>
                <input type="number" step="0.01" id="create-price" required>
            </div>
            <div>
                <label for="create-stock">Stock:</label>
                <input type="number" id="create-stock" required>
            </div>
            <div>
                <label for="create-category">Category:</label>
                <input type="text" id="create-category" required>
            </div>
            <div>
                <label for="create-description">Description:</label>
                <textarea id="create-description" required></textarea>
            </div>
            <div style="margin-top:15px; text-align:right;">
                <button type="button" id="cancelCreateProduct" style="margin-right:10px;">Cancel</button>
                <button type="submit">Create</button>
            </div>
        </form>
    </div>
</div>
<!-- Delete Modal -->
<div id="deleteModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); z-index:1001; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:30px 20px; border-radius:8px; max-width:350px; margin:auto; position:relative;">
        <h3>Confirm Deletion</h3>
        <p>Are you sure you want to delete this product? This action cannot be undone.</p>
        <div style="margin-top:15px; text-align:right;">
            <button type="button" id="cancelDelete" style="margin-right:10px;">Cancel</button>
            <button type="button" id="confirmDelete" style="background:#dc3545; color:#fff;">Delete</button>
        </div>
    </div>
</div>
