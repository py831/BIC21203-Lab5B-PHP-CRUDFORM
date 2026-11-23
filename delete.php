<?php
require_once "auth_check.php";
$host = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "lab_5b"; 

$conn = new mysqli($host, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$matric = trim($_GET["matric"] ?? "");

if ($matric !== "") {
    $stmt = $conn->prepare("DELETE FROM users WHERE matric = ?");
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header("Location: display.php");
exit;