<?php
header("Content-Type: application/json");
include("../config/database.php");

$data = json_decode(file_get_contents("php://input"), true);

$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

$stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        echo json_encode([
            "status"=>true,
            "message"=>"Login success",
            "user"=>["id"=>$user['id'], "username"=>$user['username']]
        ]);
    } else {
        echo json_encode(["status"=>false, "message"=>"Invalid password"]);
    }
} else {
    echo json_encode(["status"=>false, "message"=>"User not found"]);
}
