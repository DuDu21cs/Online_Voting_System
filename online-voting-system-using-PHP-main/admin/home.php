<?php 
include 'includes/session.php'; 
include 'includes/slugify.php'; 
include 'includes/header.php'; 
?> 

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1 class="page-header">Dashboard</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <section class="content">

      <!-- Small boxes -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <div class="small-box" style="background-color:#00bcd4; color:#fff;">
            <div class="inner">
              <?php $query = $conn->query("SELECT * FROM positions"); echo "<h3>".$query->num_rows."</h3>"; ?>
              <p>No. of Region</p>
            </div>
            <div class="icon"><i class="fa fa-tasks"></i></div>
            <a href="positions.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <div class="small-box" style="background-color:#4caf50; color:#fff;">
            <div class="inner">
              <?php $query = $conn->query("SELECT * FROM candidates"); echo "<h3>".$query->num_rows."</h3>"; ?>
              <p>No. of Candidates </p>
            </div>
            <div class="icon"><i class="fa fa-black-tie"></i></div>
            <a href="candidates.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <div class="small-box" style="background-color:#ff9800; color:#fff;">
            <div class="inner">
              <?php $query = $conn->query("SELECT * FROM voters"); echo "<h3>".$query->num_rows."</h3>"; ?>
              <p>Total Voters</p>
            </div>
            <div class="icon"><i class="fa fa-users"></i></div>
            <a href="voters.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <div class="small-box" style="background-color:#f44336; color:#fff;">
            <div class="inner">
              <?php $query = $conn->query("SELECT * FROM votes GROUP BY voters_id"); echo "<h3>".$query->num_rows."</h3>"; ?>
              <p>Voters Voted</p>
            </div>
            <div class="icon"><i class="fa fa-edit"></i></div>
            <a href="votes.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>

      <!-- Votes Tally -->
      <div class="row">
        <div class="col-xs-12">
          <h3>Votes Tally

            <!-- Print button -->
            <a href="report.php" class="btn btn-success btn-sm btn-flat pull-right" style="margin-left:10px;">
              <span class="glyphicon glyphicon-print"></span> Print
            </a>

            <!-- AJAX Election Toggle Button -->
            <?php
              $statusQuery = $conn->query("SELECT setting_value FROM site_settings WHERE setting_key='home_enabled'");
              $row = $statusQuery->fetch_assoc();
              $home_enabled = $row['setting_value'];
            ?>
            <button id="toggleElection" class="btn <?php echo ($home_enabled=="1")?'btn-success':'btn-danger'; ?> btn-sm btn-flat pull-right">
              <?php echo ($home_enabled=="1")?'End Election':'Start Election'; ?>
            </button>

          </h3>
        </div>
      </div>

      <!-- Horizontal Scrollable Chart Wrapper -->
      <div class="chart-wrapper">
      <?php
        $query = $conn->query("SELECT * FROM positions ORDER BY priority ASC");
        while($row = $query->fetch_assoc()){
          $cquery = $conn->query("SELECT * FROM candidates WHERE position_id = '".$row['id']."'");
          $max_votes = 0;
          $leader_party = 'No leader yet';
          while($crow = $cquery->fetch_assoc()){
            $vquery = $conn->query("SELECT * FROM votes WHERE candidate_id = '".$crow['id']."'"); 
            if($vquery->num_rows > $max_votes){
              $max_votes = $vquery->num_rows;
              if($max_votes > 0) $leader_party = $crow['political_party'];
            }
          }

          echo "
            <div class='chart-card'>
              <div class='chart-header'>
                <h4>".$row['description']."</h4>
                <p class='leader'>Leader: ".$leader_party."</p>
              </div>
              <canvas id='".slugify($row['description'])."'></canvas>
            </div>
          ";
        }
      ?>
      </div>
    </section>
  </div>

  <?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>

<style>
body { background:#121212; color:#eee; font-family:'Inter',sans-serif; }
.page-header { color:#00bcd4; font-size:2rem; font-weight:600; }
.chart-wrapper { display:flex; flex-wrap:nowrap; overflow-x:auto; gap:20px; padding:10px 0; }
.chart-card { flex:0 0 350px; background:#1e1e1e; border-radius:15px; box-shadow:0 6px 15px rgba(0,0,0,0.5); padding:15px; }
.chart-header { margin-bottom:10px; }
.chart-header h4 { margin:0; font-size:1.2rem; color:#00bcd4; }
.chart-header .leader { font-size:0.9rem; color:#ffc107; }
.chart-card canvas { width:100% !important; height:220px !important; }
</style>

<!-- ================= Chart.js Logic (unchanged) ================= -->
<?php
$query = $conn->query("SELECT * FROM positions ORDER BY priority ASC"); 
while($row = $query->fetch_assoc()){ 
  $cquery = $conn->query("SELECT * FROM candidates WHERE position_id = '".$row['id']."'"); 
  $labels = []; 
  $votes = []; 
  while($crow = $cquery->fetch_assoc()){ 
    $labels[] = $crow['lastname'];  
    $vquery = $conn->query("SELECT * FROM votes WHERE candidate_id = '".$crow['id']."'");  
    $votes[] = $vquery->num_rows;  
  } 
  $labels_json = json_encode($labels);  
  $votes_json = json_encode($votes); 
?> 
<script> 
$(function(){ 
  var description = '<?php echo slugify($row['description']); ?>'; 
  var barChartCanvas = $('#'+description).get(0).getContext('2d'); 
  var barChart = new Chart(barChartCanvas);  

  var barChartData = { 
    labels  : <?php echo $labels_json; ?>, 
    datasets: [ 
      { 
        label               : 'Votes', 
        fillColor           : 'rgba(60,141,188,0.9)', 
        strokeColor         : 'rgba(60,141,188,0.8)', 
        pointColor          : '#3b8bba', 
        pointStrokeColor    : 'rgba(60,141,188,1)', 
        pointHighlightFill  : '#fff', 
        pointHighlightStroke: 'rgba(60,141,188,1)', 
        data                : <?php echo $votes_json; ?> 
      } 
    ] 
  };  

  var barChartOptions = { 
    scaleBeginAtZero        : true, 
    scaleShowGridLines      : true, 
    scaleGridLineColor      : 'rgba(0,0,0,.05)', 
    scaleGridLineWidth      : 1, 
    scaleShowHorizontalLines: true, 
    scaleShowVerticalLines  : true, 
    barShowStroke           : true, 
    barStrokeWidth          : 2, 
    barValueSpacing         : 5, 
    barDatasetSpacing       : 1, 
    responsive              : true, 
    maintainAspectRatio     : false, 
    datasetFill             : false 
  };  

  var myChart = barChart.HorizontalBar(barChartData, barChartOptions); 
}); 
</script> 
<?php } ?> 

<!-- AJAX for Election Toggle -->
<script>
$(document).ready(function(){
  $("#toggleElection").click(function(){
    $.ajax({
      url: "toggle_home.php",
      type: "POST",
      success: function(response){
        if(response.trim() === "enabled"){
          $("#toggleElection").removeClass("btn-danger").addClass("btn-success").text("End Election");
        } else {
          $("#toggleElection").removeClass("btn-success").addClass("btn-danger").text("Start Election");
        }
      }
    });
  });
});
</script>

</body>
</html>
