<?php
include 'Settings/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $api_id = bin2hex(random_bytes(16)); // Generate a unique API ID

    $sql = "INSERT INTO users (name, email, username, password, api_id) VALUES ('$name', '$email', '$username', '$password', '$api_id')";

    if ($conn->query($sql) === TRUE) {
        header("Location: login.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles/main.css">
    <script>
        function validateForm() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            if (password !== confirmPassword) {
                alert('Passwords do not match.');
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form action="register.php" method="post" onsubmit="return validateForm()">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>