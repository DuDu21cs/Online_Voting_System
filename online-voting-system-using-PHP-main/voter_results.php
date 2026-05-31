<?php
include 'includes/session.php';
include 'includes/conn.php';


// Check if results are visible
$setting = $conn->query("SELECT key_value FROM settings WHERE key_name='results_visible'")->fetch_assoc();
if ($setting['key_value'] == 0) {
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Election Results</title>
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css'>
    </head>
    <body class='bg-light d-flex justify-content-center align-items-center' style='height:100vh;'>
        <div class='text-center'>
            <h3 class='text-muted'>🗳 Results are not available yet.</h3>
            <p class='text-secondary'>Please check back later.</p>
        </div>
    </body>
    </html>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Election Results</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .results-header {
      margin: 30px 0;
      text-align: center;
    }
    .results-header h1 {
      font-weight: 700;
      color: #333;
    }
    .card {
      border-radius: 15px;
      margin-bottom: 25px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .table th {
      background: #343a40;
      color: #fff;
    }
    hr {
      margin: 40px 0;
    }
  </style>
</head>
<body>
<div class="container">
  <div class="results-header">
    <h1>🗳 Election Results</h1>
    <p class="text-muted">Official report of the election</p>
  </div>

  <?php
  $query = $conn->query("SELECT * FROM positions ORDER BY priority ASC");
  while($row = $query->fetch_assoc()){
      echo "<div class='card'>";
      echo "  <div class='card-body'>";
      echo "    <h4 class='card-title text-primary'>".$row['description']."</h4>";
      echo "    <div class='table-responsive'>";
      echo "      <table class='table table-bordered table-striped align-middle'>";
      echo "        <thead><tr><th>Candidate</th><th>Party</th><th>Votes</th></tr></thead><tbody>";

      $cquery = $conn->query("SELECT * FROM candidates WHERE position_id='".$row['id']."'");
      while($crow = $cquery->fetch_assoc()){
          $vquery = $conn->query("SELECT * FROM votes WHERE candidate_id='".$crow['id']."'");
          echo "<tr>
                  <td>".$crow['firstname']." ".$crow['lastname']."</td>
                  <td>".$crow['political_party']."</td>
                  <td><strong>".$vquery->num_rows."</strong></td>
                </tr>";
      }

      echo "        </tbody></table>";
      echo "    </div>";
      echo "  </div>";
      echo "</div>";
  }
  ?>
</div>
</body>
</html>
