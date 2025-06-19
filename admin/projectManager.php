<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Project Management Panel</title>
    <meta charset="UTF-8">
</head>
<body>
    <h1>Welcome, <?= htmlspecialchars($_SESSION['admin_user']) ?>!</h1>
    <p><a href="logout.php">Logout</a></p>

    <h2>Project Management Panel</h2>
    <ul>
        <li><a href="./customerList.php">Manage Customers</a></li>
        <li><a href="../technician/listTechnician.php">Manage Technicians</a></li>
        <li><a href="incidents/selectCustomer.php">Create Incident</a></li>
        <li><a href="incidents/incidentManager.php">ManageIncident</a></li>
        <li><a href="addProducts.php">Manage Products</a></li>
    </ul>
</body>
</html>