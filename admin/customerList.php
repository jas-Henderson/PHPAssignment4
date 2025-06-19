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

$lastName = $_GET['lastName'] ?? '';

$sql = "SELECT * FROM customers WHERE lastName = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $lastName);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Search</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }
        table {
            border-collapse: collapse;
            margin-top: 10px;
            width: 100%;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background-color: #eee;
        }
        .button {
            padding: 5px 10px;
            font-size: 0.9em;
        }
        .header-actions {
            margin-bottom: 20px;
        }
        .header-actions a {
            margin-right: 10px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <h2>SportsPro Technical Support</h2>
    <p>Sports management software for the sports enthusiast</p>
    <hr>

    <div class="header-actions">
        <a href="addCustomer.php">
            <button type="button" class="button">+ Add New Customer</button>
        </a>
    </div>

    <h3>Customer Search</h3>
    <form method="get" action="customerList.php">
        <label>Last Name:</label>
        <input type="text" name="lastName" value="<?= htmlspecialchars($lastName) ?>" required>
        <button type="submit" class="button">Search</button>
    </form>

    <?php if ($lastName): ?>
        <h4>Results</h4>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Email Address</th>
                    <th>City</th>
                    <th colspan="2">Actions</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['firstName'] . ' ' . $row['lastName']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['city']) ?></td>
                        <td>
                            <form method="get" action="manageCustomer.php" style="display:inline;">
                                <input type="hidden" name="customerID" value="<?= $row['customerID'] ?>">
                                <button type="submit" class="button">Select</button>
                            </form>
                        </td>
                        <td>
                            <form method="post" action="deleteCustomer.php" onsubmit="return confirm('Are you sure you want to delete this customer?');" style="display:inline;">
                                <input type="hidden" name="customerID" value="<?= $row['customerID'] ?>">
                                <button type="submit" class="button" style="color:red;">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No customers found with last name "<?= htmlspecialchars($lastName) ?>"</p>
        <?php endif; ?>
    <?php endif; ?>

    <br><a href="projectManager.php">← Back to Admin Dashboard</a>
</body>
</html>