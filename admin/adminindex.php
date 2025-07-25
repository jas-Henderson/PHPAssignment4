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

$query = $conn->query('SELECT * FROM products ORDER BY name');

if (!$query) {
    die("Database query failed: " . $conn->error);
}


$products = $query->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Management</title>
</head>
<body>
    <h1>Welcome, <?= htmlspecialchars($_SESSION['admin_user']) ?>!</h1>
    <p><a href="logout.php">Logout</a></p>
    <a href="projectManager.php">🏠 Home</a> |

    <h2>Products</h2>
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