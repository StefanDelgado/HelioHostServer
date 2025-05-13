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
            <tr data-id="<?= $product['product_id'] ?>">
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // DELETE
    document.querySelectorAll('.delete-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            if(confirm('Are you sure you want to delete this product?')) {
                fetch('../api/microservice_supplier_products/crud/delete.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ product_id: this.dataset.id })
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    location.reload();
                });
            }
        });
    });

    // EDIT (simple prompt for demonstration)
    document.querySelectorAll('.edit-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const row = btn.closest('tr');
            const product_id = btn.dataset.id;
            const name = row.children[1].textContent;
            const supplier_name = row.children[2].textContent;
            const price = row.children[3].textContent.replace(/[^\d.]/g, '');
            const stock = row.children[4].textContent;
            const category = row.children[5].textContent;
            const description = row.children[6].textContent;

            const newName = prompt('Edit product name:', name);
            const newPrice = prompt('Edit price:', price);
            const newStock = prompt('Edit stock:', stock);
            const newCategory = prompt('Edit category:', category);
            const newDescription = prompt('Edit description:', description);

            if(newName && newPrice && newStock && newCategory && newDescription) {
                fetch('../api/microservice_supplier_products/crud/edit.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        product_id: product_id,
                        name: newName,
                        supplier_id: row.children[2].dataset.supplierId || '', // You may want to improve this
                        price: newPrice,
                        stock: newStock,
                        category: newCategory,
                        description: newDescription,
                        image_url: '' // Add if you want to support image editing
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