<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../data/db.php';

// Check if productCode is present
if (!isset($_GET['productCode'])) {
    echo "No product code provided.";
    exit;
}

$code = $_GET['productCode'];

//check if the product exists before deleting
$check_stmt = $conn->prepare('SELECT * FROM products WHERE productCode = ?');
$check_stmt->bind_param('s', $code);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows === 0) {
    echo "Product not found.";
    exit;
}
$check_stmt->close();

// Delete the product
$delete_stmt = $conn->prepare('DELETE FROM products WHERE productCode = ?');
$delete_stmt->bind_param('s', $code);

if (!$delete_stmt->execute()) {
    echo "Error deleting product: " . $delete_stmt->error;
    exit;
}

$delete_stmt->close();
header('Location: index.php');
exit;