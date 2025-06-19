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

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName   = trim($_POST['firstName']);
    $lastName    = trim($_POST['lastName']);
    $address     = trim($_POST['address']);
    $city        = trim($_POST['city']);
    $state       = trim($_POST['state']);
    $postalCode  = trim($_POST['postalCode']);
    $countryCode = strtoupper(substr(trim($_POST['countryCode']), 0, 2));
    $phone       = trim($_POST['phone']);
    $email       = trim($_POST['email']);
    $password    = $_POST['password']; // Do not trim password to preserve spaces if any

    // Basic validation
    if (!$firstName) $errors[] = "First name is required.";
    if (!$lastName)  $errors[] = "Last name is required.";
    if (!$address)   $errors[] = "Address is required.";
    if (!$city)      $errors[] = "City is required.";
    if (!$state)     $errors[] = "State/Province is required.";
    if (!$postalCode) $errors[] = "Postal code is required.";
    if (!$countryCode) $errors[] = "Country code is required.";
    if (!$phone)     $errors[] = "Phone number is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO customers (firstName, lastName, address, city, state, postalCode, countryCode, phone, email, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "ssssssssss",
            $firstName,
            $lastName,
            $address,
            $city,
            $state,
            $postalCode,
            $countryCode,
            $phone,
            $email,
            $hashedPassword
        );

        if ($stmt->execute()) {
            $success = true;
            header("Location: customerList.php?lastName=" . urlencode($lastName));
            exit;
        } else {
            $errors[] = "Database error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Customer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }
        .form-container {
            width: 450px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label, input {
            display: block;
            width: 100%;
        }
        input {
            padding: 8px;
            box-sizing: border-box;
        }
        .error {
            color: red;
            font-size: 0.9em;
        }
        .button {
            padding: 10px 15px;
            font-size: 1em;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h2>Add New Customer</h2>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" class="form-container" autocomplete="off">
        <div class="form-group">
            <label>First Name:</label>
            <input type="text" name="firstName" value="<?= htmlspecialchars($_POST['firstName'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Last Name:</label>
            <input type="text" name="lastName" value="<?= htmlspecialchars($_POST['lastName'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Address:</label>
            <input type="text" name="address" value="<?= htmlspecialchars($_POST['address'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>City:</label>
            <input type="text" name="city" value="<?= htmlspecialchars($_POST['city'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>State / Province:</label>
            <input type="text" name="state" value="<?= htmlspecialchars($_POST['state'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Postal Code:</label>
            <input type="text" name="postalCode" value="<?= htmlspecialchars($_POST['postalCode'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Country Code:</label>
            <input type="text" name="countryCode" value="<?= htmlspecialchars($_POST['countryCode'] ?? '') ?>" maxlength="3" required>
        </div>
        <div class="form-group">
            <label>Phone:</label>
            <input type="tel" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Email Address:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit" class="button">Add Customer</button>
    </form>

    <br>
    <a href="customerList.php">← Back to Customer List</a>
</body>
</html>