<?php
session_start();
require_once __DIR__ . '/../data/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT * FROM customers WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $customer = $result->fetch_assoc();

    if ($customer) {
        $_SESSION['customer_logged_in'] = true;
        $_SESSION['customer'] = $customer;

        header("Location: customerIndex.php");
        exit;
    } else {
        $error = "Invalid email address.";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Customer Login</title>
</head>
<body>
    <h2>SportsPro Technical Support</h2>
    <p>Sports management software for the sports enthusiast</p>
    <a href="index.php">Home</a>
    <h3>Customer Login</h3>
    <p>You must log in before you can register a product.</p>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Email:</label>
        <input type="email" name="email" required>
        <button type="submit">Login</button>
    </form>

    <footer>
        <p>&copy; 2022 SportsPro, Inc.</p>
    </footer>
</body>
</html>