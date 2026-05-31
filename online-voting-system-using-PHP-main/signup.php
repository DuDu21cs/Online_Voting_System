<?php
session_start();
include 'includes/conn.php'; // Your DB connection

if(isset($_POST['signup'])){
    $voters_id = $_POST['voters_id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $password = $_POST['password'];

    // Optional: handle photo upload
    $photo = '';
    if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0){
        $target_dir = "uploads/";
        if(!is_dir($target_dir)){
            mkdir($target_dir, 0777, true);
        }
        $photo = $target_dir . basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'], $photo);
    }

    // Check if voter ID already exists
    $check = $conn->query("SELECT * FROM voters WHERE voters_id = '$voters_id'");
    if($check->num_rows > 0){
        $_SESSION['error'] = "Voter ID already exists!";
        header('location: signup.php');
        exit();
    }

    // Insert new voter into database
    $sql = "INSERT INTO voters (voters_id, firstname, lastname, password, photo) 
            VALUES ('$voters_id', '$firstname', '$lastname', '$password', '$photo')";

    if($conn->query($sql)){
        $_SESSION['success'] = "Registration successful! You can now login.";
        header('location: index.php'); // redirect to login page
        exit();
    } else {
        $_SESSION['error'] = "Registration failed: " . $conn->error;
        header('location: signup.php');
        exit();
    }
}
?>

<?php include 'includes/header.php'; ?>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <b>Voting System</b> Registration
    </div>

    <div class="login-box-body">
        <p class="login-box-msg">Register as a new voter</p>

        <form action="signup.php" method="POST" enctype="multipart/form-data">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" name="voters_id" placeholder="Voter ID" required>
            </div>
            <div class="form-group has-feedback">
                <input type="text" class="form-control" name="firstname" placeholder="First Name" required>
            </div>
            <div class="form-group has-feedback">
                <input type="text" class="form-control" name="lastname" placeholder="Last Name" required>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <div class="form-group has-feedback">
                <input type="file" class="form-control" name="photo">
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat" name="signup">
                        Sign Up
                    </button>
                </div>
            </div>
        </form>

        <?php
        if(isset($_SESSION['error'])){
            echo "<div class='callout callout-danger text-center mt20'>".$_SESSION['error']."</div>";
            unset($_SESSION['error']);
        }

        if(isset($_SESSION['success'])){
            echo "<div class='callout callout-success text-center mt20'>".$_SESSION['success']."</div>";
            unset($_SESSION['success']);
        }
        ?>
    </div>
</div>

<?php include 'includes/scripts.php'; ?>
</body>
</html>
