<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../data/db.php';

if (!isset($_GET['productCode'])) {
    echo "No product code provided.";
    exit();
}

$code = $_GET['productCode'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $version = $_POST['version'];
    $releaseDate = $_POST['releaseDate'];

    $stmt = $conn->prepare('UPDATE products SET name = ?, version = ?, releaseDate = ? WHERE productCode = ?');
    $stmt->bind_param('ssss', $name, $version, $releaseDate, $code);

    if ($stmt->execute()) {
        header('Location: index.php');
        exit();
    } else {
        echo "Error updating product: " . $stmt->error;
    }
}

// Fetch product data
$stmt = $conn->prepare('SELECT * FROM products WHERE productCode = ?');
$stmt->bind_param('s', $code);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "Product not found.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
</head>
<body>
    <h1>Edit Product</h1>
    <form method="post">
        Code: <?= htmlspecialchars($product['productCode']) ?><br><br>
        
        <label>Name:</label><br>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required><br><br>

        <label>Version:</label><br>
        <input type="text" name="version" value="<?= htmlspecialchars($product['version']) ?>" required><br><br>

        <label>Release Date:</label><br>
        <input type="date" name="releaseDate" value="<?= htmlspecialchars($product['releaseDate']) ?>" required><br><br>

        <button type="submit">Update</button>
    </form>
    <br>
    <a href="index.php">← Back to Products</a>
</body>
</html>