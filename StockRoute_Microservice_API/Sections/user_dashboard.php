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