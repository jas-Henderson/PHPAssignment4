<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../data/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['customerID'])) {
    $customerID = $_POST['customerID'];

    $stmt = $conn->prepare("DELETE FROM customers WHERE customerID = ?");
    $stmt->bind_param('i', $customerID);
    $stmt->execute();

    header("Location: customerList.php?lastName=");
    exit;
} else {
    echo "Invalid request.";
}