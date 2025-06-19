<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Customer</title>
</head>
<body>
    <h1>Search for Customer</h1>
    <form method="get" action="customerList.php">
        <label>Last Name:</label>
        <input type="text" name="lastName" required>
        <button type="submit">Search</button>
    </form>
    <br>
    <a href="adminindex.php">← Back to Products</a>
</body>
</html>