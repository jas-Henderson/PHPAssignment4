<?php
session_start();
require_once __DIR__ . '/../data/db.php';

$techID = $_POST['techID'] ?? null;

if ($techID) {
    $stmt = $conn->prepare("DELETE FROM technicians WHERE techID = ?");
    $stmt->bind_param("i", $techID);
    $stmt->execute();
}

header("Location: listTechnicians.php");
exit;