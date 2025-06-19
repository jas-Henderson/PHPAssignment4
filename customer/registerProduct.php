<?php
session_start();
require_once __DIR__ . '/../data/db.php';

// Redirect if user not logged in
if (!isset($_SESSION['customer'])) {
    header("Location: login.php");
    exit();
}

$customer = $_SESSION['customer'];
$products = [];
$message = '';
$error = '';

// Get available products
$result = $conn->query("SELECT productCode, name FROM products ORDER BY name");
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productCode = $_POST['productCode'] ?? '';

    if (!empty($productCode)) {
        $stmt = $conn->prepare("INSERT INTO registrations (customerID, productCode, registrationDate) VALUES (?, ?, NOW())");
        $stmt->bind_param("is", $customer['customerID'], $productCode);

        if ($stmt->execute()) {
            $message = "Product registered successfully.";
        } else {
            $error = "Failed to register product. Please try again.";
        }
    } else {
        $error = "Please select a product.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }
        h2 {
            color: #444;
        }
        form {
            margin-top: 20px;
        }
        select, button {
            padding: 8px;
            font-size: 1em;
            margin-top: 10px;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
        footer {
            margin-top: 40px;
            font-size: 0.9em;
            color: #777;
        }
    </style>
</head>
<body>

    <h2>SportsPro Technical Support</h2>
    <p>Sports management software for the sports enthusiast</p>
    <a href="customerIndex.php">← Back to Home</a>

    <h3>Register Product</h3>

    <p>Customer: <?= htmlspecialchars($customer['firstName']) . ' ' . htmlspecialchars($customer['lastName']) ?></p>

    <?php if ($message): ?>
        <p class="success"><?= htmlspecialchars($message) ?></p>
    <?php elseif ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="product">Product:</label><br>
        <select name="productCode" id="product" required>
            <option value="">-- Select a product --</option>
            <?php foreach ($products as $product): ?>
                <option value="<?= htmlspecialchars($product['productCode']) ?>">
                    <?= htmlspecialchars($product['name']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>
        <button type="submit">Register Product</button>
    </form>

    <footer>
        <p>&copy; <?= date('Y') ?> SportsPro, Inc.</p>
    </footer>

</body>
</html>