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