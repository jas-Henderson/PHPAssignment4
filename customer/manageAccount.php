<?php
// Show errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../data/db.php';

if (!isset($_SESSION['customer_logged_in']) || !isset($_SESSION['customer'])) {
    header("Location: customerLogin.php");
    exit;
}

$customer = $_SESSION['customer'];
$customerID = $customer['customerID'];
$message = '';
$error = '';

// --- Update Email ---
if (isset($_POST['update_email'])) {
    $new_email = $_POST['email'];
    $stmt = $conn->prepare("UPDATE customers SET email = ? WHERE customerID = ?");
    $stmt->bind_param('si', $new_email, $customerID);
    if ($stmt->execute()) {
        $_SESSION['customer']['email'] = $new_email;
        $message = "Email updated successfully.";
    } else {
        $error = "Failed to update email.";
    }
}

// --- Update Password ---
if (isset($_POST['update_password'])) {
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE customers SET password = ? WHERE customerID = ?");
    $stmt->bind_param('si', $new_password, $customerID);
    if ($stmt->execute()) {
        $message = "Password updated successfully.";
    } else {
        $error = "Failed to update password.";
    }
}

// --- Delete Product Registration ---
if (isset($_POST['delete_product'])) {
    $productCode = $_POST['productCode'];
    $stmt = $conn->prepare("DELETE FROM registrations WHERE customerID = ? AND productCode = ?");
    $stmt->bind_param('is', $customerID, $productCode);
    if ($stmt->execute()) {
        $message = "Product registration deleted.";
    } else {
        $error = "Failed to delete product registration.";
    }
}

// --- Fetch Registered Products ---
$stmt = $conn->prepare("
    SELECT r.productCode, r.registrationDate, p.name AS productName
    FROM registrations r
    INNER JOIN products p ON r.productCode = p.productCode
    WHERE r.customerID = ?
    ORDER BY r.registrationDate DESC
");
$stmt->bind_param('i', $customerID);
$stmt->execute();
$products = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }
        h2, h3 {
            color: #444;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input, button {
            padding: 8px;
            margin-top: 5px;
            font-size: 1em;
        }
        .success { color: green; }
        .error { color: red; }
        .product-entry {
            margin-bottom: 10px;
            padding: 8px;
            border-bottom: 1px solid #ccc;
        }
    </style>
</head>
<body>

    <h2>Manage Account</h2>
    <a href="customerIndex.php">← Back to Dashboard</a>

    <?php if (!empty($message)): ?>
        <p class="success"><?= htmlspecialchars($message) ?></p>
    <?php elseif (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <h3>Update Email</h3>
    <form method="post">
        <label for="email">New Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($customer['email']) ?>" required>
        <button type="submit" name="update_email">Update Email</button>
    </form>

    <h3>Update Password</h3>
    <form method="post">
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" required>
        <button type="submit" name="update_password">Update Password</button>
    </form>

    <h3>Registered Products</h3>
    <?php if ($products->num_rows > 0): ?>
        <?php while ($product = $products->fetch_assoc()): ?>
            <div class="product-entry">
                <strong><?= htmlspecialchars($product['productName']) ?></strong><br>
                Registered on: <?= htmlspecialchars(date("F j, Y", strtotime($product['registrationDate']))) ?>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="productCode" value="<?= htmlspecialchars($product['productCode']) ?>">
                    <button type="submit" name="delete_product" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No products registered yet.</p>
    <?php endif; ?>

    <footer>
        <p>&copy; <?= date('Y') ?> SportsPro, Inc.</p>
    </footer>
</body>
</html>