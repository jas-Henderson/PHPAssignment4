<?php
session_start();
session_unset();
session_destroy();

// Redirect to root index.php
header("Location: ../index.php");
exit;