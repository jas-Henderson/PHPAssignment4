<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../data/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['productCode'];
    $name = $_POST['name'];
    $version = $_POST['version'];
    $releaseDate = $_POST['releaseDate'];

    // Step 1: Check for duplicate productCode
    $checkSql = 'SELECT COUNT(*) FROM products WHERE productCode = ?';
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param('s', $code);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    // Step 2: If productCode exists, show error
    if ($count > 0) {
        $error = "A product with code '$code' already exists. Please use a different code.";
    } else {
        // Step 3: Insert product
        $sql = 'INSERT INTO products (productCode, name, version, releaseDate) VALUES (?, ?, ?, ?)';
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die('Prepare failed: ' . $conn->error);
        }

        $stmt->bind_param('ssss', $code, $name, $version, $releaseDate);

        if (!$stmt->execute()) {
            die('Execute failed: ' . $stmt->error);
        }

        $stmt->close();
        header('Location: adminindex.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
</head>
<body>
    <h1>Add Product</h1>
    <form method="post">
        <label>Code:</label><br>
        <input type="text" name="productCode" required><br><br>

        <label>Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Version:</label><br>
        <input type="text" name="version" required><br><br>

        <label>Release Date:</label><br>
        <input type="date" name="releaseDate" required><br><br>

        <button type="submit">Add</button>
    </form>
    <br>
    <a href="adminindex.php">← Back to Products</a>
</body>
</html>