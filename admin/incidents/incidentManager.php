<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../../data/db.php';
$error = '';
$success = '';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateStatus'])) {
    $incidentID = $_POST['incidentID'];
    $newStatus = $_POST['status'];

    $stmt = $conn->prepare("UPDATE incidents SET status = ? WHERE incidentID = ?");
    $stmt->bind_param('si', $newStatus, $incidentID);
    $stmt->execute();
}

// Unassigned Incidents
$unassignedSql = "SELECT i.incidentID, i.dateOpened, i.title, i.description, i.status,
                         c.firstName, c.lastName,
                         p.name AS productName
                  FROM incidents i
                  JOIN customers c ON i.customerID = c.customerID
                  JOIN products p ON i.productCode = p.productCode
                  WHERE i.techID IS NULL
                  ORDER BY i.dateOpened DESC";
$unassignedResult = $conn->query($unassignedSql);

// Assigned Incidents
$assignedSql = "SELECT i.incidentID, i.dateOpened, i.title, i.description, i.status,
                       c.firstName, c.lastName,
                       p.name AS productName,
                       t.firstName AS techFirst, t.lastName AS techLast
                FROM incidents i
                JOIN customers c ON i.customerID = c.customerID
                JOIN products p ON i.productCode = p.productCode
                JOIN technicians t ON i.techID = t.techID
                WHERE i.techID IS NOT NULL
                ORDER BY i.dateOpened DESC";
$assignedResult = $conn->query($assignedSql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Incident Manager</title>
</head>
<body>
    <h2>Unassigned Incidents</h2>
    <?php if ($unassignedResult->num_rows > 0): ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Product</th>
                    <th>Date Opened</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($incident = $unassignedResult->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($incident['firstName'] . ' ' . $incident['lastName']) ?></td>
                        <td><?= htmlspecialchars($incident['productName']) ?></td>
                        <td><?= htmlspecialchars($incident['dateOpened']) ?></td>
                        <td><?= htmlspecialchars($incident['title']) ?></td>
                        <td><?= nl2br(htmlspecialchars($incident['description'])) ?></td>
                        <td><?= htmlspecialchars($incident['status']) ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="incidentID" value="<?= $incident['incidentID'] ?>">
                                <select name="status">
                                    <option value="Open" <?= $incident['status'] === 'Open' ? 'selected' : '' ?>>Open</option>
                                    <option value="In Progress" <?= $incident['status'] === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                    <option value="Resolved" <?= $incident['status'] === 'Resolved' ? 'selected' : '' ?>>Resolved</option>
                                    <option value="Closed" <?= $incident['status'] === 'Closed' ? 'selected' : '' ?>>Closed</option>
                                </select>
                                <button type="submit" name="updateStatus">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No unassigned incidents found.</p>
    <?php endif; ?>

    <h2>Assigned Incidents</h2>
    <?php if ($assignedResult->num_rows > 0): ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Product</th>
                    <th>Technician</th>
                    <th>Date Opened</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($incident = $assignedResult->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($incident['firstName'] . ' ' . $incident['lastName']) ?></td>
                        <td><?= htmlspecialchars($incident['productName']) ?></td>
                        <td><?= htmlspecialchars($incident['techFirst'] . ' ' . $incident['techLast']) ?></td>
                        <td><?= htmlspecialchars($incident['dateOpened']) ?></td>
                        <td><?= htmlspecialchars($incident['title']) ?></td>
                        <td><?= nl2br(htmlspecialchars($incident['description'])) ?></td>
                        <td><?= htmlspecialchars($incident['status']) ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="incidentID" value="<?= $incident['incidentID'] ?>">
                                <select name="status">
                                    <option value="Open" <?= $incident['status'] === 'Open' ? 'selected' : '' ?>>Open</option>
                                    <option value="In Progress" <?= $incident['status'] === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                    <option value="Resolved" <?= $incident['status'] === 'Resolved' ? 'selected' : '' ?>>Resolved</option>
                                    <option value="Closed" <?= $incident['status'] === 'Closed' ? 'selected' : '' ?>>Closed</option>
                                </select>
                                <button type="submit" name="updateStatus">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No assigned incidents found.</p>
    <?php endif; ?>

    <br><a href="/book_apps/sportsprotech/admin/projectManager.php">← Back to Dashboard</a>
</body>
</html>