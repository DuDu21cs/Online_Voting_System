<?php
include 'includes/session.php';
include 'includes/conn.php';

// Only admin can toggle
if (!isset($_SESSION['admin'])) {
    http_response_code(403);
    exit("Forbidden");
}

if (isset($_POST['status'])) {
    $status = intval($_POST['status']);
    // Update or insert
    if ($conn->query("UPDATE settings SET key_value=$status WHERE key_name='results_visible'") === false) {
        $conn->query("INSERT INTO settings (key_name, key_value) VALUES ('results_visible', $status)");
    }
    echo "ok";
}
