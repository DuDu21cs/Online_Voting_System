<?php
include 'includes/session.php';
include 'includes/slugify.php';
include 'includes/conn.php'; // DB connection

// Set headers so browser downloads as a text file
header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="results.txt"');

// Build text content
$output = "Election Results\n";
$output .= "====================\n\n";

$query = $conn->query("SELECT * FROM positions ORDER BY priority ASC");
while($row = $query->fetch_assoc()){
    $output .= "Position: ".$row['description']."\n";
    $output .= str_repeat("-", strlen("Position: ".$row['description']))."\n";

    $cquery = $conn->query("SELECT * FROM candidates WHERE position_id='".$row['id']."'");
    while($crow = $cquery->fetch_assoc()){
        $vquery = $conn->query("SELECT * FROM votes WHERE candidate_id='".$crow['id']."'");
        $output .= "Candidate: ".$crow['lastname']." | Party: ".$crow['political_party']." | Votes: ".$vquery->num_rows."\n";
    }
    $output .= "\n";
}

// Print out text file
echo $output;
exit;
?>
