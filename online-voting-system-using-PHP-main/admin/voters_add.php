<?php
include 'includes/session.php';

if(isset($_POST['add'])){
    $voters_id = $_POST['voters_id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $region = $_POST['region'];

    // Handle photo upload
    $photo = '';
    if(!empty($_FILES['photo']['name'])){
        $photo = time().'_'.$_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$photo);
    }

    $sql = "INSERT INTO voters (voters_id, firstname, lastname, password, region, photo) VALUES ('$voters_id', '$firstname', '$lastname', '$password', '$region', '$photo')";
    if($conn->query($sql)){
        $_SESSION['success'] = 'Voter added successfully';
    } else {
        $_SESSION['error'] = $conn->error;
    }
}
header('location: voters.php');
?>
