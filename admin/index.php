<?php
require_once __DIR__ . '/../data/db.php';


// Get all products
$query = $db->query('SELECT * FROM products ORDER BY name');
$products = $query->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Management</title>
</head>
<body>
    <h1>Products</h1>
    <a href="addProducts.php">Add New Product</a>
    <table cellpadding="8">
        <tr>
            <th>Code</th>
            <th>Name</th>
            <th>Version</th>
            <th>Release Date</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?= htmlspecialchars($product['productCode']) ?></td>
            <td><?= htmlspecialchars($product['name']) ?></td>
            <td><?= htmlspecialchars($product['version']) ?></td>
            <td><?= htmlspecialchars($product['releaseDate']) ?></td>
            <td>
                <a href="edit.php?productCode=<?= urlencode($product['productCode']) ?>">Edit</a> |
                <a href="delete.php?productCode=<?= urlencode($product['productCode']) ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
