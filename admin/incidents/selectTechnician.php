<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../../data/db.php';

$incidentID = $_POST['incidentID'] ?? null;
$message = '';
$error = '';

// If the form was submitted to assign a tech
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['technicianID'])) {
    $technicianID = $_POST['technicianID'] ?? null;

    if ($incidentID && $technicianID) {
        $stmt = $conn->prepare("UPDATE incidents SET techID = ? WHERE incidentID = ?");
        $stmt->bind_param("ii", $technicianID, $incidentID);
        if ($stmt->execute()) {
            $message = "Technician assigned successfully.";
        } else {
            $error = "Error assigning technician.";
        }
    } else {
        $error = "Invalid technician or incident ID.";
    }
}

// Get incident details
if ($incidentID) {
    $stmt = $conn->prepare("SELECT i.*, c.firstName, c.lastName, p.name AS productName
                            FROM incidents i
                            JOIN customers c ON i.customerID = c.customerID
                            JOIN products p ON i.productCode = p.productCode
                            WHERE i.incidentID = ?");
    $stmt->bind_param("i", $incidentID);
    $stmt->execute();
    $incident = $stmt->get_result()->fetch_assoc();
} else {
    $error = "No incident ID provided.";
}

// Get list of technicians
$technicians = [];
$result = $conn->query("SELECT techID, firstName, lastName FROM technicians");
while ($row = $result->fetch_assoc()) {
    $technicians[] = $row;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Select Technician</title>
</head>
<body>
    <h2>Select Technician</h2>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php elseif ($message): ?>
        <p style="color:green;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if (!empty($incident)): ?>
        <p><strong>Customer:</strong> <?= htmlspecialchars($incident['firstName'] . ' ' . $incident['lastName']) ?></p>
        <p><strong>Product:</strong> <?= htmlspecialchars($incident['productName']) ?></p>
        <p><strong>Title:</strong> <?= htmlspecialchars($incident['title']) ?></p>
        <p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($incident['description'])) ?></p>

        <form method="post">
            <input type="hidden" name="incidentID" value="<?= $incidentID ?>">
            <label for="technicianID">Select Technician:</label><br>
            <select name="technicianID" required>
                <option value="">-- Choose Technician --</option>
                <?php foreach ($technicians as $tech): ?>
                    <option value="<?= $tech['techID'] ?>">
                        <?= htmlspecialchars($tech['firstName'] . ' ' . $tech['lastName']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>
            <button type="submit">Assign Technician</button>
        </form>
    <?php endif; ?>

    <br><a href="incidentManager.php">← Back to Incident Manager</a>
</body>
</html>