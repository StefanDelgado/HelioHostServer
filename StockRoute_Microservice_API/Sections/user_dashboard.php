<?php
// filepath: c:\xampp\htdocs\WebDesign_BSITA-2\2nd sem\Joshan_System\HelioHostServer\StockRoute_Microservice_API\Sections\user_dashboard.php

include_once '../Settings/db.php';

// Fetch users from database
$sql = "SELECT * FROM microservice_users ORDER BY id ASC";
$result = $conn->query($sql);
$users = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
?>
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
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['id']) ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= htmlspecialchars($user['role_id']) ?></td>
            <td><?= htmlspecialchars($user['type_id']) ?></td>
            <td>
                <button class="edit-btn" data-id="<?= $user['id'] ?>">Edit</button>
                <button class="delete-btn" data-id="<?= $user['id'] ?>">Delete</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>