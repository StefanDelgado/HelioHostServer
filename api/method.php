<?php
// filepath: /C:/xampp/htdocs/WebDesign_BSITA-2/2nd sem/Joshan_System/HelioHostServer/api/method.php

include 'authenticator.php';

// Authenticate the API key
authenticate();

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];

switch (true) {
    case preg_match('/\/api\/microservice_user\/register/', $path):
        include 'microservice_user/register.php';
        break;
    case preg_match('/\/api\/microservice_user\/login/', $path):
        include 'microservice_user/login.php';
        break;
    case preg_match('/\/api\/microservice_user\/crud\/create/', $path):
        include 'microservice_user/crud/create.php';
        break;
    case preg_match('/\/api\/microservice_user\/crud\/read/', $path):
        include 'microservice_user/crud/read.php';
        break;
    case preg_match('/\/api\/microservice_user\/crud\/update/', $path):
        include 'microservice_user/crud/update.php';
        break;
    case preg_match('/\/api\/microservice_user\/crud\/delete/', $path):
        include 'microservice_user/crud/delete.php';
        break;
    default:
        echo json_encode(['message' => 'Invalid endpoint']);
        http_response_code(404);
        break;
}
?>