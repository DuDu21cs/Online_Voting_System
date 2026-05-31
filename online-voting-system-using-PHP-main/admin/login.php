<?php 
session_start();
include 'includes/conn.php';

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id, password FROM admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows < 1){
        $_SESSION['error'] = 'Cannot find account with the username';
    } else {
        $row = $result->fetch_assoc();
        $db_password = $row['password'];

        $login_ok = false;

        // ✅ Case 1: Already migrated (password_hash used)
        if(password_verify($password, $db_password)){
            $login_ok = true;
        }
        // ✅ Case 2: Old MD5 hash
        elseif(md5($password) === $db_password){
            $login_ok = true;

            // 🔄 Upgrade MD5 hash to password_hash()
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE admin SET password = ? WHERE id = ?");
            $update->bind_param('si', $newHash, $row['id']);
            $update->execute();
            $update->close();
        }

        if($login_ok){
            $_SESSION['admin'] = $row['id'];
        } else {
            $_SESSION['error'] = 'Incorrect password';
        }
    }

    $stmt->close();
} else {
    $_SESSION['error'] = 'Input admin credentials first';
}

header('location: index.php');
?>
