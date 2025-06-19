<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../../data/db.php';

// Get unassigned incidents
$sql = "SELECT i.incidentID, i.dateOpened, i.title, i.description,
               c.firstName, c.lastName,
               p.name AS productName
        FROM incidents i
        JOIN customers c ON i.customerID = c.customerID
        JOIN products p ON i.productCode = p.productCode
        WHERE i.techID IS NULL
        ORDER BY i.dateOpened DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Incident Manager</title>
</head>
<body>
    <h2>Unassigned Incidents</h2>

    <?php if ($result->num_rows > 0): ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Product</th>
                    <th>Date Opened</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($incident = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($incident['firstName'] . ' ' . $incident['lastName']) ?></td>
                        <td><?= htmlspecialchars($incident['productName']) ?></td>
                        <td><?= htmlspecialchars($incident['dateOpened']) ?></td>
                        <td><?= htmlspecialchars($incident['title']) ?></td>
                        <td><?= nl2br(htmlspecialchars($incident['description'])) ?></td>
                        <td>
                            <form method="post" action="selectTechnician.php">
                                <input type="hidden" name="incidentID" value="<?= $incident['incidentID'] ?>">
                                <button type="submit">Select</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No unassigned incidents found.</p>
    <?php endif; ?>

    <br><a href="/book_apps/sportsprotech/admin/projectManager.php">← Back to Dashboard</a>
</body>
</html>