<?php
session_start();
include 'Settings/db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'] ?? null;

if ($username) {
    $sql = "SELECT api_id FROM users WHERE username = '$username'";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();
    $api_id = $user['api_id'] ?? 'N/A';
} else {
    $api_id = 'N/A';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles/main.css">
</head>
<body>
    <div class="container">
        <h2>Welcome to your Dashboard</h2>
        <p>Your API ID: <?php echo htmlspecialchars($api_id); ?></p>
        <h3>Available Microservices</h3>
        <ul>
            <li>Microservice 1: User Login and Registration</li>
        </ul>
        <h3>API Usage</h3>
        <p>Below shows the API URL of login and register users. The API uses JSON files to process your information.</p>
        <h4>Register a User</h4>
        <p><strong>Endpoint:</strong> <code>POST /script/api.php</code></p>
        <p><strong>Request Body:</strong></p>
        <pre><code>{
  "action": "register",
  "email": "user@example.com",
  "username": "username",
  "password": "password123"
}</code></pre>
        <p><strong>Response:</strong></p>
        <pre><code>{
  "message": "Microservice user registered successfully"
}</code></pre>
        <h4>Login a User</h4>
        <p><strong>Endpoint:</strong> <code>POST /script/api.php</code></p>
        <p><strong>Request Body:</strong></p>
        <pre><code>{
  "action": "login",
  "username": "username",
  "password": "password123"
}</code></pre>
        <p><strong>Response:</strong></p>
        <pre><code>{
  "message": "Login successful",
  "api_id": "unique_api_id"
}</code></pre>
        <h4>Get User Details</h4>
        <p><strong>Endpoint:</strong> <code>GET /script/api.php?username=username</code></p>
        <p><strong>Response:</strong></p>
        <pre><code>{
  "id": 1,
  "email": "user@example.com",
  "username": "username",
  "api_id": "unique_api_id"
}</code></pre>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>