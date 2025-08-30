<?php
$host = "sql305.infinityfree.com";
$user = "if0_39822054";
$pass = "eBJKtA0YV3";
$db   = "if0_39822054_toko_roti";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(["status"=>false, "message"=>"Database connection failed"]));
}
?>
