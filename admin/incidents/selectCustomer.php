<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start the session
session_start();

// Include the database connection
require_once __DIR__ . '/../../data/db.php';

// Initialize the error variable
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM customers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $customer = $result->fetch_assoc();

    if ($customer) {
        // Redirect to createIncident.php with customer ID
        header("Location: createIncident.php?customerID=" . $customer['customerID']);
        exit();
    } else {
        $error = "No customer found with that email.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Select Customer</title>
</head>
<body>
    <h2>Select Customer by Email</h2>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Email Address:</label><br>
        <input 
            type="email" 
            name="email" 
            value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" 
            required
        ><br><br>
        <button type="submit">Create Incident</button>
    </form>

    <a href="../projectManager.php">← Back to Admin Home</a>
</body>
</html>