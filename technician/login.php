<?php
session_start();
require_once __DIR__ . '/../data/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT techID, firstName, lastName, password FROM technicians WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $tech = $result->fetch_assoc();

    // Plain-text password comparison
    if ($tech && $password === $tech['password']) {
        $_SESSION['tech_logged_in'] = true;
        $_SESSION['techID'] = $tech['techID'];
        $_SESSION['techName'] = $tech['firstName'] . ' ' . $tech['lastName'];
        header("Location: listTechnician.php");
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Technician Login</title></head>
<body>
    <h2>Login</h2>
    <?php if ($error): ?><p style="color:red;"><?= htmlspecialchars($error) ?></p><?php endif; ?>

    <form method="post">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>