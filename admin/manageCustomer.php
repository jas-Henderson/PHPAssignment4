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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $customerID = $_GET['customerID'] ?? '';
    $stmt = $conn->prepare("SELECT * FROM customers WHERE customerID = ?");
    $stmt->bind_param('i', $customerID);
    $stmt->execute();
    $result = $stmt->get_result();
    $customer = $result->fetch_assoc();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerID = $_POST['customerID'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postalCode = $_POST['postalCode'];
    $countryCode = $_POST['countryCode'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("UPDATE customers 
        SET firstName = ?, lastName = ?, address = ?, city = ?, state = ?, postalCode = ?, countryCode = ?, phone = ?, email = ?, password = ? 
        WHERE customerID = ?");
    $stmt->bind_param('ssssssssssi', $firstName, $lastName, $address, $city, $state, $postalCode, $countryCode, $phone, $email, $password, $customerID);
    $stmt->execute();

    header("Location: customerList.php?lastName=" . urlencode($lastName));
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View/Update Customer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #F5F7FA;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 600px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border: 1px solid #ccc;
        }

        h1 {
            color: #2c3e50;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 6px;
            margin-top: 4px;
            margin-bottom: 12px;
            border: 1px solid #aaa;
        }

        button {
            padding: 10px 20px;
            background-color: #2c3e50;
            color: #fff;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #1a242f;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #2c3e50;
        }

        .header, .footer {
            background-color: #eef1f5;
            padding: 10px 20px;
            text-align: left;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>SportsPro Technical Support</h2>
        <p>Sports management software for the sports enthusiast</p>
        <a href="index.php">Home</a>
    </div>

    <div class="container">
        <h1>View/Update Customer</h1>

        <?php if (isset($customer)): ?>
            <form method="post">
                <input type="hidden" name="customerID" value="<?= $customer['customerID'] ?>">

                <label>First Name:</label>
                <input type="text" name="firstName" value="<?= htmlspecialchars($customer['firstName']) ?>" required>

                <label>Last Name:</label>
                <input type="text" name="lastName" value="<?= htmlspecialchars($customer['lastName']) ?>" required>

                <label>Address:</label>
                <input type="text" name="address" value="<?= htmlspecialchars($customer['address']) ?>" required>

                <label>City:</label>
                <input type="text" name="city" value="<?= htmlspecialchars($customer['city']) ?>" required>

                <label>State:</label>
                <input type="text" name="state" value="<?= htmlspecialchars($customer['state']) ?>" required>

                <label>Postal Code:</label>
                <input type="text" name="postalCode" value="<?= htmlspecialchars($customer['postalCode']) ?>" required>

                <label>Country Code:</label>
                <input type="text" name="countryCode" value="<?= htmlspecialchars($customer['countryCode']) ?>" maxlength="2" required>

                <label>Phone:</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($customer['phone']) ?>" required>

                <label>Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($customer['email']) ?>" required>

                <label>Password:</label>
                <input type="password" name="password" value="<?= htmlspecialchars($customer['password']) ?>" required>

                <button type="submit">Update Customer</button>
            </form>
        <?php else: ?>
            <p>Customer not found.</p>
        <?php endif; ?>

        <a href="customerList.php">← Search Customers</a>
    </div>

    <div class="footer">
        &copy; 2022 SportsPro, Inc.
    </div>
</body>
</html>