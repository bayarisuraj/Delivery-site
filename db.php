<?php
// db.php - update these values for your environment
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = 'Bayari,99.';
$DB_NAME = 'my_bank_ui';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
// Create DB if not exists
$conn->query("CREATE DATABASE IF NOT EXISTS `$DB_NAME` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->select_db($DB_NAME);
$conn->set_charset('utf8mb4');
?>