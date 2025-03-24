<?php
// Include database connection
include 'Settings/db.php';

// Function to fetch Microservice Users
def getMicroserviceUsers($conn) {
    $sql = "SELECT * FROM microservice_users";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Function to fetch Supplier Products
def getSupplierProducts($conn) {
    $sql = "SELECT * FROM supplier_products";
    $result = $conn->query($sql);
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
            <th>Name</th>
            <th>Email</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= $user['name'] ?></td>
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
        </tr>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?= $product['id'] ?></td>
            <td><?= $product['product_name'] ?></td>
            <td><?= $product['price'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
