<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../data/db.php';

$error = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['firstName'] ?? '';
    $lastName  = $_POST['lastName'] ?? '';
    $email     = $_POST['email'] ?? '';
    $phone     = $_POST['phone'] ?? '';
    $password  = $_POST['password'] ?? '';

    if (!$firstName || !$lastName || !$email || !$phone || !$password) {
        $error = "Please fill out all fields.";
    } else {
        $stmt = $conn->prepare("INSERT INTO technicians (firstName, lastName, email, phone, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $firstName, $lastName, $email, $phone, $password);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Technician added successfully!";
            header("Location: listTechnician.php");
            exit;
        } else {
            $error = "Error: " . htmlspecialchars($stmt->error);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Add Technician</title></head>
<body>
    <h2>Add Technician</h2>

    <?php if ($error): ?><p style="color:red;"><?= $error ?></p><?php endif; ?>
    <?php if ($message): ?><p style="color:green;"><?= $message ?></p><?php endif; ?>

    <form method="post">
        <label>First Name:</label><br>
        <input type="text" name="firstName" required><br><br>
        <label>Last Name:</label><br>
        <input type="text" name="lastName" required><br><br>
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        <label>Phone:</label><br>
        <input type="text" name="phone" required><br><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Add Technician</button>
    </form>

    <br><a href="listTechnician.php">← Back to Technician List</a>
</body>
</html>