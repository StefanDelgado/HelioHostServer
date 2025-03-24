<?php
// Include database connection
include 'Settings/db.php';

// Function to fetch all Microservice Users
function getMicroserviceUsers($conn) {
    $sql = "SELECT id, username, email FROM microservice_users";
    $result = $conn->query($sql);
    if (!$result) {
        die("Query Failed: " . $conn->error);
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Function to fetch all Supplier Products
function getSupplierProducts($conn) {
    $sql = "SELECT product_id, name, price, supplier_id FROM products";
    $result = $conn->query($sql);
    if (!$result) {
        die("Query Failed: " . $conn->error);
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}

$users = getMicroserviceUsers($conn);
$products = getSupplierProducts($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Dashboard</title>
    <link rel="stylesheet" href="styles/main.css">
</head>
<body>
    <h1>Welcome to the Project Dashboard</h1>
    
    <h2>Microservice Users</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= $user['username'] ?></td>
            <td><?= $user['email'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>Supplier Products</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Supplier ID</th>
        </tr>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?= $product['product_id'] ?></td>
            <td><?= $product['name'] ?></td>
            <td><?= $product['price'] ?></td>
            <td><?= $product['supplier_id'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>