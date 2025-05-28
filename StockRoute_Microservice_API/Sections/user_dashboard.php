<?php
// filepath: c:\xampp\htdocs\WebDesign_BSITA-2\2nd sem\Joshan_System\HelioHostServer\StockRoute_Microservice_API\Sections\user_dashboard.php

include_once '../Settings/db.php';
include '../includes/user_functions.php';

// Fetch users from database
$sql = "SELECT * FROM microservice_users ORDER BY id ASC";
$result = $conn->query($sql);
$users = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role_id = $_POST['role_id'];
    $type_id = $_POST['type_id'];

    $result = create_microservice_user($conn, $email, $username, $password, $role_id, $type_id);

    if ($result['success']) {
        // Success: redirect or show message
        echo "<script>alert('{$result['message']}'); window.location.href='your_dashboard.php';</script>";
    } else {
        // Error: show message
        echo "<script>alert('{$result['message']}'); window.history.back();</script>";
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
            <th>Actions
                <button id="createUserBtn" style="margin-left:10px;">Create</button>
            </th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr data-id="<?= $user['id'] ?>">
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

<!-- Edit Modal -->
<div id="editModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:30px 20px; border-radius:8px; max-width:400px; margin:auto; position:relative;">
        <h3>Edit User</h3>
        <form id="editForm">
            <input type="hidden" id="edit-id">
            <div>
                <label for="edit-username">Username:</label>
                <input type="text" id="edit-username" required>
            </div>
            <div>
                <label for="edit-email">Email:</label>
                <input type="email" id="edit-email" required>
            </div>
            <div>
                <label for="edit-role">Role ID:</label>
                <input type="number" id="edit-role" required>
            </div>
            <div>
                <label for="edit-type">Type ID:</label>
                <input type="number" id="edit-type" required>
            </div>
            <div style="margin-top:15px; text-align:right;">
                <button type="button" id="cancelEdit" style="margin-right:10px;">Cancel</button>
                <button type="submit">Confirm</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); z-index:1001; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:30px 20px; border-radius:8px; max-width:350px; margin:auto; position:relative;">
        <h3>Confirm Deletion</h3>
        <p>Are you sure you want to delete this user? This action cannot be undone.</p>
        <div style="margin-top:15px; text-align:right;">
            <button type="button" id="cancelDelete" style="margin-right:10px;">Cancel</button>
            <button type="button" id="confirmDelete" style="background:#dc3545; color:#fff;">Delete</button>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div id="createModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); z-index:1002; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:30px 20px; border-radius:8px; max-width:400px; margin:auto; position:relative;">
        <h3>Create User</h3>
        <form method="POST" action="create_microservice_user">
            <div>
                <label for="create-username">Username:</label>
                <input type="text" id="create-username" name="username" required>
            </div>
            <div>
                <label for="create-email">Email:</label>
                <input type="email" id="create-email" name="email" required>
            </div>
            <div>
                <label for="create-password">Password:</label>
                <input type="password" id="create-password" name="password" required>
            </div>
            <div>
                <label for="create-role">Role ID:</label>
                <input type="number" id="create-role" name="role_id" required>
            </div>
            <div>
                <label for="create-type">Type ID:</label>
                <input type="number" id="create-type" name="type_id" required>
            </div>
            <div style="margin-top:15px; text-align:right;">
                <button type="button" id="cancelCreate" style="margin-right:10px;">Cancel</button>
                <button type="submit">Create</button>
            </div>
        </form>
    </div>
</div>

