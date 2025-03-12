<?php
$servername = "localhost:3306";
$username = "just1ncantiler0_projectusers";
$password = "gE7@66y7u";
$dbname = "just1ncantiler0_projectusers";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>