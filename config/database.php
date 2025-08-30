<?php
$host = "sql12.freesqldatabase.com";
$user = "sql12796658";
$pass = "enNlblbMZd";
$db   = "sql12796658";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(["status"=>false, "message"=>"Database connection failed"]));
}
?>
