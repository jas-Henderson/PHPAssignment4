<?php
session_start();
if (!isset($_SESSION['customer_logged_in']) || !isset($_SESSION['customer'])) {
    header("Location: login.php");
    exit;
}

$customer = $_SESSION['customer']; // Assumes array with keys like 'firstName', 'lastName'
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Home</title>
</head>
<body>
    <h1>Welcome, <?= htmlspecialchars($customer['firstName']) ?>!</h1>

    <p>What would you like to do today?</p>

    <form action="registerProduct.php" method="get">
        <button type="submit">Register a Product</button>
    </form>
    <br>

    <form action="manageAccount.php" method="get">
        <button type="submit">Manage Account</button>
    </form>
    <br>

    <a href="logout.php">Logout</a>
</body>
</html>