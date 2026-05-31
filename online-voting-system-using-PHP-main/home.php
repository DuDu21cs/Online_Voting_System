<?php 
include 'includes/session.php'; 
include 'includes/lang.php'; 
include 'includes/header.php'; 

// ====== Check Voter Home / Election Status ======
// 0 = Not started, 1 = Ongoing, 2 = Ended
$statusQuery = $conn->query("SELECT setting_value FROM site_settings WHERE setting_key='home_enabled'");
$row = $statusQuery->fetch_assoc();
$electionStatus = $row['setting_value'] ?? "0";

// If election is not ongoing, show only the card and exit
if($electionStatus != "1"){
    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Election Status</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>
    <body style="margin:0; display:flex; justify-content:center; align-items:center; height:100vh; background:#f2f2f2;">
        <div class="alert alert-warning text-center" style="font-size:22px; padding:50px; border-radius:15px; box-shadow:0 4px 20px rgba(0,0,0,0.3); max-width:600px;">
            <i class="fa fa-info-circle"></i> 
            <b>The election has not started yet or the election has ended.</b>
        </div>
    </body>
    </html>';
    exit();
}
?>

<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">

    <?php include 'includes/navbar.php'; ?>

    <div class="content-wrapper">
        <div class="container">

            <section class="content">

                <?php
                // Load election title
                $config = parse_ini_file('admin/config.ini', FALSE, INI_SCANNER_RAW);
                $title = isset($config['election_title']) ? htmlspecialchars(strtoupper($config['election_title'])) : __('election_title');

                function displayAlert($type, $messages) {
                    $icon = ($type === 'success') ? 'fa-check' : 'fa-ban';
                    echo '<div class="alert alert-' . $type . ' alert-dismissible">';
                    echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                    if ($type === 'success') echo "<h4><i class='icon fa $icon'></i> " . __('success') . "</h4>";
                    echo is_array($messages) ? '<ul><li>' . implode('</li><li>', $messages) . '</li></ul>' : $messages;
                    echo '</div>';
                }

                if (isset($_SESSION['error'])) { displayAlert('danger', $_SESSION['error']); unset($_SESSION['error']); }
                if (isset($_SESSION['success'])) { displayAlert('success', $_SESSION['success']); unset($_SESSION['success']); }
                ?>

                <h1 class="page-header text-center title"><b><?php echo $title; ?></b></h1>

                <div class="row">
                    <div class="col-sm-12">

                        <div class="alert alert-danger alert-dismissible" id="alert" style="display:none;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <span class="message"></span>
                        </div>

                        <?php
                        // Check if voter has already voted
                        $votedSql = "SELECT * FROM votes WHERE voters_id = '".$_SESSION['voter']."'";
                        $votedQuery = $conn->query($votedSql);

                        if ($votedQuery->num_rows > 0) {
                            echo '<div class="text-center">';
                            echo '<h3>'.__("already_voted").'</h3>';
                            echo '<a href="#view" data-toggle="modal" class="btn btn-flat btn-primary btn-lg">'.__("view_ballot").'</a>';
                            echo '</div>';
                        } else {

                            $voter_region = $_SESSION['region'] ?? null;

                            if(empty($voter_region)){
                                echo '<div class="text-center">';
                                echo '<h3 class="text-warning">It seems you are not assigned to any region yet.</h3>';
                                echo '<p>Please contact the administrator to update your registration details.</p>';
                                echo '</div>';
                            } else {
                                include 'includes/slugify.php';

                                // Only fetch the position that matches the user's region
                                $positions = $conn->query("SELECT * FROM positions WHERE id='$voter_region' ORDER BY priority ASC");

                                if($positions->num_rows == 0){
                                    echo '<div class="text-center">';
                                    echo '<h3 class="text-warning">No positions are available for your region.</h3>';
                                    echo '</div>';
                                } else {
                        ?>

                        <!-- Voting Ballot -->
                        <form method="POST" id="ballotForm" action="submit_ballot.php">

                        <?php while ($pos = $positions->fetch_assoc()) {

                            // Fetch candidates for this position (region)
                            $candidates = $conn->query("SELECT * FROM candidates WHERE position_id='".$pos['id']."'");

                            if(!$candidates || $candidates->num_rows == 0){
                                echo '<div class="position-section">';
                                echo '<h3 class="position-title">'.htmlspecialchars($pos['description']).'</h3>';
                                echo '<p class="text-muted">No candidates are registered for your region in this position.</p>';
                                echo '</div>';
                                continue;
                            }

                            $slug = slugify($pos['description']);
                        ?>

                        <div class="position-section">
                            <h3 class="position-title"><?php echo htmlspecialchars($pos['description']); ?></h3>
                            <p class="instruction">
                                <?php echo ($pos['max_vote'] > 1) ? __("select_many", ["max" => $pos['max_vote']]) : __("select_one"); ?>
                                <button type="button" class="btn btn-success btn-sm reset" data-desc="<?php echo $slug; ?>">
                                    <i class="fa fa-refresh"></i> <?php echo __("reset"); ?>
                                </button>
                            </p>
                            <div class="candidate-cards">
                                <?php while ($cand = $candidates->fetch_assoc()) {
                                    $checked = '';
                                    if(isset($_SESSION['post'][$slug])) {
                                        $value = $_SESSION['post'][$slug];
                                        if (is_array($value)) $checked = in_array($cand['id'], $value) ? 'checked' : '';
                                        else $checked = ($value == $cand['id']) ? 'checked' : '';
                                    }
                                    $inputType = ($pos['max_vote'] > 1) 
                                        ? "<input type='checkbox' class='$slug' name='{$slug}[]' value='{$cand['id']}' $checked>"
                                        : "<input type='radio' class='$slug' name='$slug' value='{$cand['id']}' $checked>";
                                    $image = !empty($cand['photo']) ? 'images/'.$cand['photo'] : 'images/profile.jpg';
                                ?>
                                <div class="candidate-card">
                                    <div class="candidate-photo">
                                        <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($cand['firstname'].' '.$cand['lastname']); ?>">
                                    </div>
                                    <div class="candidate-info">
                                        <span class="cname"><?php echo htmlspecialchars($cand['firstname'].' '.$cand['lastname']); ?></span>
                                        <span class="cparty text-muted"><?php echo htmlspecialchars($cand['political_party']); ?></span>
                                        <span class="cgender text-muted"><?php echo htmlspecialchars($cand['gender']); ?></span>
                                        <div class="candidate-actions">
                                            <?php echo $inputType; ?>
                                            <button type="button" class="btn btn-primary btn-sm btn-flat platform" 
                                                data-platform="<?php echo htmlspecialchars($cand['platform']); ?>" 
                                                data-fullname="<?php echo htmlspecialchars($cand['firstname'].' '.$cand['lastname']); ?>">
                                                <i class="fa fa-search"></i> <?php echo __("platform"); ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>

                        <?php } ?>

                        <div class="text-center" style="margin-top:20px;">
                            <button type="button" class="btn btn-success btn-flat" id="preview">
                                <i class="fa fa-file-text"></i> <?php echo __("preview"); ?>
                            </button>
                            <button type="submit" class="btn btn-primary btn-flat" name="vote">
                                <i class="fa fa-check-square-o"></i> <?php echo __("submit"); ?>
                            </button>
                        </div>

                        </form>
                        <?php 
                                } // end else positions exist
                            } // end else region assigned
                        } 
                        ?>
                        <!-- End Voting Ballot -->

                    </div>
                </div>
            </section>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/ballot_modal.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>

<!-- Candidate card styling -->
<style>
.position-section { margin-bottom: 30px; }
.position-title { font-size: 22px; font-weight: bold; margin-bottom: 10px; }
.instruction { font-size: 14px; margin-bottom: 15px; }
.candidate-cards { display: flex; flex-wrap: wrap; gap: 15px; }
.candidate-card { 
    background: #fff; 
    border-radius: 8px; 
    box-shadow: 0 2px 6px rgba(0,0,0,0.1); 
    padding: 10px; 
    flex: 1 1 calc(25% - 15px); 
    text-align: center; 
    transition: transform 0.2s, box-shadow 0.2s;
}
.candidate-card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
.candidate-photo img { width: 100px; height: 100px; border-radius: 50%; margin-bottom: 10px; }
.candidate-info .cname { display: block; font-weight: bold; margin-bottom: 5px; }
.candidate-info .cparty, 
.candidate-info .cgender { font-size: 14px; display: block; margin-bottom: 5px; color: #555; }
.candidate-actions { display: flex; justify-content: center; gap: 5px; flex-wrap: wrap; }
@media (max-width: 768px) { .candidate-card { flex: 1 1 calc(50% - 15px); } }
@media (max-width: 480px) { .candidate-card { flex: 1 1 100%; } }
</style>
</body>
</html>
