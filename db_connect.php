<?php
$conn = new mysqli("localhost", "root", "", "delivery management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
