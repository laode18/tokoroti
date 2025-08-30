<?php
header("Content-Type: application/json");
include("../config/database.php");

$result = $conn->query("SELECT * FROM products");
$products = [];

while ($row = $result->fetch_assoc()) {
    $row['image_url'] = $row['image'] ? "http://localhost/toko_roti/uploads/" . $row['image'] : null;
    $products[] = $row;
}

echo json_encode([
    "status"=>true,
    "data"=>$products
]);
