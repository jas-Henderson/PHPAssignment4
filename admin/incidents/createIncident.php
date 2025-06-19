<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../../data/db.php';

$customerID = $_GET['customerID'] ?? null;
$message = '';
$error = '';

if (!$customerID) {
    header("Location: selectCustomer.php");
    exit;
}

// Get customer details
$stmt = $conn->prepare("SELECT * FROM customers WHERE customerID = ?");
$stmt->bind_param("i", $customerID);
$stmt->execute();
$customer = $stmt->get_result()->fetch_assoc();

// Get registered products
$stmt = $conn->prepare("SELECT p.productCode, p.name FROM products p 
                        JOIN registrations r ON p.productCode = r.productCode 
                        WHERE r.customerID = ?");
$stmt->bind_param("i", $customerID);
$stmt->execute();
$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productCode = $_POST['productCode'] ?? '';
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';

    if (!$productCode || !$title || !$description) {
        $error = "Please fill in all fields.";
    } else {
        // Insert incident without techID (default to NULL)
        $stmt = $conn->prepare("INSERT INTO incidents 
            (customerID, productCode, dateOpened, dateClosed, title, description)
            VALUES (?, ?, NOW(), NULL, ?, ?)");

        // Bind only 5 parameters now
        $stmt = $conn->prepare("INSERT INTO incidents 
    (customerID, productCode, dateOpened, dateClosed, title, description)
    VALUES (?, ?, NOW(), NULL, ?, ?)");

      $stmt->bind_param("isss", $customerID, $productCode, $title, $description);
        if ($stmt->execute()) {
            $message = "Incident created successfully!";
        } else {
            $error = "Database error: " . htmlspecialchars($stmt->error);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Incident</title>
</head>
<body>
    <h2>Create Incident</h2>

    <?php if ($customer): ?>
        <p><strong>Customer:</strong> <?= htmlspecialchars($customer['firstName'] . ' ' . $customer['lastName']) ?></p>
    <?php else: ?>
        <p style="color:red;">Customer not found.</p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>
    <?php if ($message): ?>
        <p style="color:green;"><?= $message ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Product:</label><br>
        <select name="productCode" required>
            <option value="">--Select--</option>
            <?php foreach ($products as $product): ?>
                <option value="<?= htmlspecialchars($product['productCode']) ?>">
                    <?= htmlspecialchars($product['name']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Title:</label><br>
        <input type="text" name="title" maxlength="50" required><br><br>

        <label>Description:</label><br>
        <textarea name="description" rows="5" cols="40" maxlength="2000" required></textarea><br><br>

        <button type="submit">Create Incident</button>
    </form>

    <a href="selectCustomer.php">← Back to Customer Lookup</a>
</body>
</html>