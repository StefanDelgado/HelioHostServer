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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // DELETE
    document.querySelectorAll('.delete-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            if(confirm('Are you sure you want to delete this user?')) {
                fetch('../api/microservice_user/crud/delete.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ id: this.dataset.id })
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
            const id = btn.dataset.id;
            const username = row.children[1].textContent;
            const email = row.children[2].textContent;
            const role_id = row.children[3].textContent;
            const type_id = row.children[4].textContent;

            const newUsername = prompt('Edit username:', username);
            const newEmail = prompt('Edit email:', email);
            const newRoleId = prompt('Edit role ID:', role_id);
            const newTypeId = prompt('Edit type ID:', type_id);

            if(newUsername && newEmail && newRoleId && newTypeId) {
                fetch('../api/microservice_user/crud/edit.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        id: id,
                        username: newUsername,
                        email: newEmail,
                        role_id: newRoleId,
                        type_id: newTypeId
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