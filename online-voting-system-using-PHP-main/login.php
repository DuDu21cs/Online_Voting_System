<?php
session_start();
include 'includes/conn.php'; // Your DB connection

if(isset($_POST['login'])){
    $voter = $_POST['voter'];
    $password = $_POST['password'];

    // Correct column name is 'voters_id'
    $sql = "SELECT * FROM voters WHERE voters_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $voter);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();

        // ✅ Use password_verify for hashed password
        if(password_verify($password, $row['password'])){
            // Store user info in session
            $_SESSION['voter']  = $row['id'];      // internal voter ID
            $_SESSION['region'] = $row['region'];  // store region from DB

            header('location: home.php');
            exit();
        } else {
            $_SESSION['error'] = 'Incorrect password';
            header('location: index.php');
            exit();
        }
    } else {
        $_SESSION['error'] = 'Voter ID not found';
        header('location: index.php');
        exit();
    }
} else {
    header('location: index.php');
    exit();
}
?>
