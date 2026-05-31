<?php
include 'includes/session.php';
include 'includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Start Election
    if (isset($_POST['start_election'])) {
        $end_time = $_POST['end_time'];

        // Sanitize input
        $end_time = $conn->real_escape_string($end_time);

        // Update election started status
        $conn->query("
            INSERT INTO election_settings (key_name, key_value)
            VALUES ('election_started', 1)
            ON DUPLICATE KEY UPDATE key_value = 1
        ");

        // Set election end time
        $conn->query("
            INSERT INTO election_settings (key_name, key_value)
            VALUES ('election_end', '$end_time')
            ON DUPLICATE KEY UPDATE key_value = '$end_time'
        ");

        $_SESSION['success'] = "Election started successfully!";
    }

    // End Election
    if (isset($_POST['end_election'])) {
        $conn->query("
            UPDATE election_settings
            SET key_value = 0
            WHERE key_name = 'election_started'
        ");
        $_SESSION['success'] = "Election ended successfully!";
    }

    // Redirect back to dashboard
    header("Location: dashboard.php");
    exit();
}
?>
