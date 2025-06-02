<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'db.php';

// Use a real table and column name from your database
$sql = "SELECT firstName FROM customers"; // Adjust as needed
$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "Customer First Name: " . $row["firstName"] . "<br>";
        }
    } else {
        echo "No results found.";
    }
} else {
    echo "Query error: " . $conn->error;
}

$conn->close();
?>