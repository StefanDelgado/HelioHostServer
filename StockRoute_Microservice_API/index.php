<?php
// Include database connection
include 'Settings/db.php';

// Function to fetch all Microservice Users
function getMicroserviceUsers($conn) {
    $sql = "SELECT id, username, email, role_id, type_id FROM microservice_users";
    $result = $conn->query($sql);
    if (!$result) {
        die("Query Failed: " . $conn->error);
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Function to fetch all Supplier Products with Order ID
function getSupplierProducts($conn) {
    $sql = "SELECT p.product_id, p.name, p.price, p.supplier_id, oi.order_id FROM products p 
            LEFT JOIN order_items oi ON p.product_id = oi.product_id";
    $result = $conn->query($sql);
    if (!$result) {
        die("Query Failed: " . $conn->error);
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Function to fetch all Orders
function getOrders($conn) {
    $sql = "SELECT order_id, business_id, supplier_id, total_price, order_status, order_date FROM orders";
    $result = $conn->query($sql);
    if (!$result) {
        die("Query Failed: " . $conn->error);
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}

$users = getMicroserviceUsers($conn);
$products = getSupplierProducts($conn);
$orders = getOrders($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Dashboard</title>
    <link rel="stylesheet" href="styles/main.css">
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .tab-btn {
            padding: 10px 20px;
            margin: 5px;
            cursor: pointer;
            background-color: #007BFF;
            color: #fff;
            border: none;
            outline: none;
            border-radius: 4px;
        }
        .tab-btn.active {
            background-color: #0056b3;
        }
        .section {
            display: none;
        }
        .section.active {
            display: block;
        }
        table {
            margin: 0 auto;
            border-collapse: collapse;
            width: 80%;
        }
        th, td {
            border: 1px solid #444;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
    </style>
    <script>
        function showSection(sectionId) {
            // Hide all sections
            var sections = document.getElementsByClassName("section");
            for (var i = 0; i < sections.length; i++) {
                sections[i].classList.remove("active");
            }
            // Remove active class from all tab buttons
            var buttons = document.getElementsByClassName("tab-btn");
            for (var j = 0; j < buttons.length; j++) {
                buttons[j].classList.remove("active");
            }
            // Show the selected section and mark its button active
            document.getElementById(sectionId).classList.add("active");
            document.getElementById("btn-" + sectionId).classList.add("active");
        }
        window.onload = function() {
            showSection("users"); // default tab
        }
    </script>
</head>
<body>
    <h1>Welcome to the StockRoute Dashboard</h1>

    <div class="tabs">
        <button id="btn-users" class="tab-btn" onclick="showSection('users')">Microservice Users</button>
        <button id="btn-products" class="tab-btn" onclick="showSection('products')">Supplier Products</button>
        <button id="btn-orders" class="tab-btn" onclick="showSection('orders')">Orders</button>
    </div>

    <!-- Users Section -->
    <div id="users" class="section">
        <h2>Microservice Users</h2>
        <h3>Legend</h3>
        <h4>Role ID</h4>
        <ul>
            <li>201 - Admin</li>
            <li>202 - Owner</li>
            <li>203 - Staff</li>
        </ul>
        <h4>Type ID</h4>
        <ul>
            <li>301 - Delivery</li>
            <li>302 - Business</li>
            <li>303 - Supplier</li>
        </ul>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role ID</th>
                <th>Type ID</th>
            </tr>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= $user['username'] ?></td>
                <td><?= $user['email'] ?></td>
                <td><?= $user['role_id'] ?></td>
                <td><?= $user['type_id'] ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

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

</body>
</html>
