<?php
include 'includes/session.php';
include 'includes/slugify.php';
include 'includes/header.php'; 
include 'includes/conn.php';

// Only allow admin to access this page
if (!isset($_SESSION['admin'])) {
    echo "<h3>Access Denied!</h3>";
    exit();
}

// Get current setting
$setting = $conn->query("SELECT key_value FROM settings WHERE key_name='results_visible'")->fetch_assoc();
$visible = $setting['key_value'] ?? 0;
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php 
    include 'includes/navbar.php'; 
    include 'includes/menubar.php'; 
  ?>

  <div class="content-wrapper">
    <section class="content-header" style="display:flex; align-items:center; justify-content:space-between;">
      <h1>Election Results Report</h1>

      <div>
        <!-- Print Button (kept old style and position) -->
        <button onclick="window.print()" class="btn btn-primary">
          <i class="fa fa-print"></i> Print / Save PDF
        </button>

        <!-- Modern Announce Results Button (right side) -->
        <button id="toggleResultsBtn" class="btn-toggle <?php echo $visible ? 'active' : ''; ?>" style="margin-left:15px;">
          <?php echo $visible ? "Results Visible" : "Announce Results"; ?>
        </button>
      </div>
    </section>

    <section class="content">
      <div class="box box-solid">
        <div class="box-body">
          <?php
          $query = $conn->query("SELECT * FROM positions ORDER BY priority ASC");
          while($row = $query->fetch_assoc()){
            echo "<h3>".$row['description']."</h3>";
            echo "<table class='table table-bordered table-striped'>";
            echo "<thead><tr><th>Candidate</th><th>Party</th><th>Votes</th></tr></thead><tbody>";

            $cquery = $conn->query("SELECT * FROM candidates WHERE position_id='".$row['id']."'");
            while($crow = $cquery->fetch_assoc()){
              $vquery = $conn->query("SELECT * FROM votes WHERE candidate_id='".$crow['id']."'");
              echo "<tr>
                      <td>".$crow['firstname']." ".$crow['lastname']."</td>
                      <td>".$crow['political_party']."</td>
                      <td>".$vquery->num_rows."</td>
                    </tr>";
            }
            echo "</tbody></table><hr>";
          }
          ?>
        </div>
      </div>
    </section>
  </div>

  <?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>

<!-- Modern Toggle Button CSS -->
<style>
.btn-toggle {
  padding: 10px 20px;
  border-radius: 30px;
  border: none;
  font-weight: bold;
  color: #fff;
  cursor: pointer;
  transition: all 0.3s ease;
  background: #6c757d; /* grey OFF */
}
.btn-toggle.active {
  background: #28a745; /* green ON */
  box-shadow: 0 0 15px rgba(40,167,69,0.6); /* glowing */
}
.btn-toggle:hover {
  opacity: 0.9;
}
@media print {
  .btn-toggle { display: none; }
}
</style>

<!-- JavaScript Toggle -->
<script>
document.getElementById("toggleResultsBtn").addEventListener("click", function() {
  let btn = this;
  let status = btn.classList.contains("active") ? 0 : 1;

  // Update UI instantly
  if (status === 1) {
    btn.classList.add("active");
    btn.textContent = "Results Visible";
  } else {
    btn.classList.remove("active");
    btn.textContent = "Announce Results";
  }

  // Send AJAX request
  fetch("toggle_results.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "status=" + status
  });
});
</script>
</body>
</html>
