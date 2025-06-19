<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start(); // ✅ Only call once
require_once __DIR__ . '/../data/db.php';

// ✅ Display success message if set
if (isset($_SESSION['success_message'])) {
    echo '<p style="color:green;">' . htmlspecialchars($_SESSION['success_message']) . '</p>';
    unset($_SESSION['success_message']);
}

// ✅ Fetch technicians from DB
$sql = "SELECT * FROM technicians";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Technician List</title>
</head>
<body>
    <h2>Technicians</h2>

    <a href="addTechnician.php">+ Add Technician</a><br><br>

    <?php if ($result->num_rows > 0): ?>
        <table border="1" cellpadding="8">
            <tr>
                <th>TechID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
            <?php while ($tech = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($tech['techID']) ?></td>
                    <td><?= htmlspecialchars($tech['firstName'] . ' ' . $tech['lastName']) ?></td>
                    <td><?= htmlspecialchars($tech['email']) ?></td>
                    <td><?= htmlspecialchars($tech['phone']) ?></td>
                    <td>
                        <form method="post" action="manageTechnician.php" style="display:inline;">
                            <input type="hidden" name="techID" value="<?= $tech['techID'] ?>">
                            <button type="submit">Select</button>
                        </form>
                        <form method="post" action="deleteTechnician.php" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                            <input type="hidden" name="techID" value="<?= $tech['techID'] ?>">
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        <a href="logout.php">Log Out</a>
    <?php else: ?>
        <p>No technicians found.</p>
    <?php endif; ?>

    <br><br>
    <a href="/book_apps/sportsprotech/admin/projectManager.php">← Back to Dashboard</a>
</body>
</html>