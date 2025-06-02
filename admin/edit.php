<?php
require_once __DIR__ . '/../data/db.php';
$code = $_GET['productCode'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $version = $_POST['version'];
    $releaseDate = $_POST['releaseDate'];

    $sql = 'UPDATE products SET name = ?, version = ?, releaseDate = ? WHERE productCode = ?';
    $stmt = $db->prepare($sql);
    $stmt->execute([$name, $version, $releaseDate, $code]);

    header('Location: index.php');
    exit();
}

// Fetch product data
$stmt = $db->prepare('SELECT * FROM products WHERE productCode = ?');
$stmt->execute([$code]);
$product = $stmt->fetch();

if (!$product) {
    echo "Product not found.";
    exit();
}
?>

<h1>Edit Product</h1>
<form method="post">
    Code: <?= htmlspecialchars($product['productCode']) ?><br>
    Name: <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required><br>
    Version: <input type="text" name="version" value="<?= htmlspecialchars($product['version']) ?>" required><br>
    Release Date: <input type="date" name="releaseDate" value="<?= htmlspecialchars($product['releaseDate']) ?>" required><br>
    <button type="submit">Update</button>
</form>
<a href="index.php">Back to Products</a>
