<?php
include 'includes/session.php';
include 'includes/conn.php';

// Check current status
$statusQuery = $conn->query("SELECT setting_value FROM site_settings WHERE setting_key='home_enabled'");
$row = $statusQuery->fetch_assoc();
$current = $row['setting_value'];

// Toggle value
$new = ($current == "1") ? "0" : "1";
$conn->query("UPDATE site_settings SET setting_value='$new' WHERE setting_key='home_enabled'");

// Return new status
echo ($new == "1") ? "enabled" : "disabled";
