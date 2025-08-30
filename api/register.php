<?php
header("Content-Type: application/json");
include("../config/database.php");

$data = json_decode(file_get_contents("php://input"), true);

$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

if ($username && $password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hash);

    if ($stmt->execute()) {
        echo json_encode(["status"=>true, "message"=>"User registered successfully"]);
    } else {
        echo json_encode(["status"=>false, "message"=>"Registration failed"]);
    }
} else {
    echo json_encode(["status"=>false, "message"=>"Username and Password required"]);
}
