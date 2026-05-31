<?php
include 'includes/session.php';
include 'includes/slugify.php';

if(isset($_GET['position_id'])){
    $position_id = $_GET['position_id'];
    $labels = [];
    $votes = [];

    $cquery = $conn->query("SELECT * FROM candidates WHERE position_id='$position_id'");
    while($crow = $cquery->fetch_assoc()){
        $labels[] = $crow['lastname'];
        $vquery = $conn->query("SELECT * FROM votes WHERE candidate_id='".$crow['id']."'");
        $votes[] = $vquery->num_rows;
    }

    echo json_encode(['labels'=>json_encode($labels),'votes'=>json_encode($votes)]);
}
?>
