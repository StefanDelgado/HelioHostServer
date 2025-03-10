<?php
include '../Settings/db.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        handlePostRequest();
        break;
    case 'GET':
        handleGetRequest();
        break;
    default:
        echo json_encode(['message' => 'Method not allowed']);
        break;
}

function validateApiId($apiId) {
    global $conn;
    $sql = "SELECT * FROM users WHERE api_id = '$apiId'";
    $result = $conn->query($sql);
    return $result->num_rows > 0;
}

function handlePostRequest() {
    global $conn;
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($_SERVER['HTTP_API_ID']) || !validateApiId($_SERVER['HTTP_API_ID'])) {
        echo json_encode(['message' => 'Invalid API ID']);
        return;
    }

    if (isset($input['action'])) {
        switch ($input['action']) {
            case 'register':
                $email = $input['email'];
                $username = $input['username'];
                $password = password_hash($input['password'], PASSWORD_DEFAULT);
                

                // Create microservice user
                $sql = "INSERT INTO microservice_users (email, username, password, api_id) VALUES ('$email', '$username', '$password', '$api_id')";

                if ($conn->query($sql) === TRUE) {
                    // Update the users table with the new API ID
                    $updateSql = "UPDATE users SET api_id = '$api_id' WHERE username = '$username'";
                    $conn->query($updateSql);

                    echo json_encode(['message' => 'Microservice user registered successfully']);
                } else {
                    echo json_encode(['message' => 'Error: ' . $sql . '<br>' . $conn->error]);
                }
                break;

            case 'login':
                $username = $input['username'];
                $password = $input['password'];

                $sql = "SELECT * FROM microservice_users WHERE username = '$username'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    if (password_verify($password, $user['password'])) {
                        echo json_encode(['message' => 'Login successful', 'api_id' => $user['api_id']]);
                    } else {
                        echo json_encode(['message' => 'Invalid username or password']);
                    }
                } else {
                    echo json_encode(['message' => 'Invalid username or password']);
                }
                break;

            default:
                echo json_encode(['message' => 'Invalid action']);
                break;
        }
    } else {
        echo json_encode(['message' => 'Action not specified']);
    }
}

function handleGetRequest() {
    global $conn;

    if (!isset($_SERVER['HTTP_API_ID']) || !validateApiId($_SERVER['HTTP_API_ID'])) {
        echo json_encode(['message' => 'Invalid API ID']);
        return;
    }

    if (isset($_GET['username'])) {
        $username = $_GET['username'];
        $sql = "SELECT * FROM microservice_users WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            unset($user['password']); // Remove password from the response
            echo json_encode($user);
        } else {
            echo json_encode(['message' => 'User not found']);
        }
    } else {
        echo json_encode(['message' => 'Username not specified']);
    }
}
?>