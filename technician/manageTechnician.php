<?php
session_start();
require_once __DIR__ . '/../data/db.php';

$techID = $_POST['techID'] ?? $_GET['techID'] ?? null;
$error = '';
$message = '';

if (!$techID) {
    die("Technician not found.");
}

// Fetch technician
$stmt = $conn->prepare("SELECT * FROM technicians WHERE techID = ?");
$stmt->bind_param("i", $techID);
$stmt->execute();
$tech = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $firstName = $_POST['firstName'] ?? '';
    $lastName  = $_POST['lastName'] ?? '';
    $email     = $_POST['email'] ?? '';
    $phone     = $_POST['phone'] ?? '';
    $password  = $_POST['password'] ?? '';

    $stmt = $conn->prepare("UPDATE technicians SET firstName=?, lastName=?, email=?, phone=?, password=? WHERE techID=?");
    $stmt->bind_param("sssssi", $firstName, $lastName, $email, $phone, $password, $techID);

    if ($stmt->execute()) {
        $message = "Technician updated.";
    } else {
        $error = "Error updating: " . htmlspecialchars($stmt->error);
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Manage Technician</title></head>
<body>
    <h2>Manage Technician</h2>

    <?php if ($error): ?><p style="color:red;"><?= $error ?></p><?php endif; ?>
    <?php if ($message): ?><p style="color:green;"><?= $message ?></p><?php endif; ?>

    <form method="post">
        <input type="hidden" name="techID" value="<?= htmlspecialchars($techID) ?>">
        <label>First Name:</label><br>
        <input type="text" name="firstName" value="<?= htmlspecialchars($tech['firstName']) ?>"><br><br>
        <label>Last Name:</label><br>
        <input type="text" name="lastName" value="<?= htmlspecialchars($tech['lastName']) ?>"><br><br>
        <label>Email:</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($tech['email']) ?>"><br><br>
        <label>Phone:</label><br>
        <input type="text" name="phone" value="<?= htmlspecialchars($tech['phone']) ?>"><br><br>
        <label>Password:</label><br>
        <input type="text" name="password" value="<?= htmlspecialchars($tech['password']) ?>"><br><br>

        <button type="submit" name="update">Update Technician</button>
    </form>

    <br><a href="listTechnicians.php">← Back to Technician List</a>
</body>
</html>