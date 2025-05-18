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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // EDIT - open modal
    document.querySelectorAll('.edit-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const row = btn.closest('tr');
            document.getElementById('edit-id').value = btn.dataset.id;
            document.getElementById('edit-username').value = row.children[1].textContent;
            document.getElementById('edit-email').value = row.children[2].textContent;
            document.getElementById('edit-role').value = row.children[3].textContent;
            document.getElementById('edit-type').value = row.children[4].textContent;
            document.getElementById('editModal').style.display = 'flex';
        });
    });

    // Cancel button
    document.getElementById('cancelEdit').onclick = function() {
        document.getElementById('editModal').style.display = 'none';
    };

    // Hide modal on background click
    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) this.style.display = 'none';
    });

    // Submit edit form
    document.getElementById('editForm').onsubmit = function(e) {
        e.preventDefault();
        const id = document.getElementById('edit-id').value;
        const username = document.getElementById('edit-username').value;
        const email = document.getElementById('edit-email').value;
        const role_id = document.getElementById('edit-role').value;
        const type_id = document.getElementById('edit-type').value;

        fetch('../api/microservice_user/crud/edit.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                id: id,
                username: username,
                email: email,
                role_id: role_id,
                type_id: type_id
            })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            document.getElementById('editModal').style.display = 'none';
            location.reload();
        });
    };
});
</script>

