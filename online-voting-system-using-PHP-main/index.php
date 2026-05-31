<?php
session_start();

// Redirect if already logged in
if(isset($_SESSION['admin'])){
    header('location: admin/home.php');
    exit();
}

if(isset($_SESSION['voter'])){
    header('location: home.php');
    exit();
}
?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition login-page">

<div class="login-box" style="width: 320px; animation: fadeIn 1s ease-in-out;">
    <div class="login-card rounded-4">
        
        <div class="login-logo text-center">
            <div class="flag-circle mb-3">
               <img src="image/flag.png" alt="Ethiopia">
            </div>
            <!-- Modern Title Below Logo -->
            <h2 class="login-title mb-1">Ethiopia Online Voting</h2>
            <p class="login-subtitle mb-3">Secure & Transparent Voting Portal</p>
        </div>

        <!-- Login Form -->
        <form action="login.php" method="POST">
            <div class="form-group">
                <input type="text" class="form-control modern-input" id="voter" name="voter" placeholder="Voter ID" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control modern-input" id="password" name="password" placeholder="Password" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn modern-btn" name="login">
                    <i class="fa fa-sign-in"></i> SIGN IN
                </button>
            </div>
        </form>

        <!-- Error Alert -->
        <?php
        if(isset($_SESSION['error'])){
            echo "
            <div class='alert alert-danger text-center'>
                <i class='fa fa-exclamation-circle'></i> ".$_SESSION['error']."
            </div>
            ";
            unset($_SESSION['error']);
        }
        ?>
    </div>
</div>

<style>
/* Body Modern Background */
body {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(-45deg, #0f5132, #ffc107, #d63333, #0d6efd);
    background-size: 400% 400%;
    animation: gradientAnimation 20s ease infinite;
    position: relative;
    overflow: hidden;
}

/* Optional subtle overlay pattern */
body::before {
    content: "";
    position: absolute;
    top:0; left:0; right:0; bottom:0;
    background: radial-gradient(circle, rgba(255,255,255,0.05) 1px, transparent 1px);
    background-size: 50px 50px;
    pointer-events: none;
}

/* Gradient Animation */
@keyframes gradientAnimation {
    0% {background-position: 0% 50%;}
    50% {background-position: 100% 50%;}
    100% {background-position: 0% 50%;}
}

/* Fade-in Animation */
@keyframes fadeIn {
    from {opacity: 0; transform: translateY(-20px);}
    to {opacity: 1; transform: translateY(0);}
}

/* Login Card */
.login-card {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    gap: 0.65rem;
    padding: 1.5rem 1.2rem;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 1rem;
    border: 1px solid rgba(255,255,255,0.3);
    box-shadow: 0 25px 50px rgba(0,0,0,0.35);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.login-card:hover {
    transform: translateY(-5px) scale(1.03);
    box-shadow: 0 30px 60px rgba(0,0,0,0.4);
}

/* Flag Circle */
.flag-circle {
    width: 95px;
    height: 95px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: auto;
    overflow: hidden;
    border: 4px solid transparent;
    background-image: linear-gradient(white, white), linear-gradient(270deg, green, yellow, red, green);
    background-origin: border-box;
    background-clip: content-box, border-box;
    background-size: 300% 300%;
    animation: borderFlow 5s linear infinite, pulse 2s infinite;
}

.flag-circle img {
    width: 92%;
    height: 92%;
    object-fit: cover;
    border-radius: 50%;
}

/* Title and Subtitle */
.login-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #0f5132;
    letter-spacing: 0.5px;
    margin: 0.3rem 0;
}

.login-subtitle {
    font-size: 1rem;
    font-weight: 500;
    color: #495057;
    margin: 0 0 1rem 0;
}

/* Inputs */
.form-group {
    margin-bottom: 0.75rem;
}

.modern-input {
    width: 100%;
    padding: 0.65rem 1.2rem;
    font-size: 1rem;
    border-radius: 50px;
    border: 1px solid #ced4da;
    transition: all 0.3s ease-in-out;
    box-shadow: inset 0 2px 5px rgba(0,0,0,0.05);
}

.modern-input:focus {
    border-color: #198754;
    box-shadow: 0 0 12px rgba(25,135,84,0.35);
    font-weight: 600;
}

/* Button */
.d-grid {
    margin-top: 0.5rem;
}

.modern-btn {
    width: 100%;
    background: linear-gradient(270deg, #0f5132, #198754, #0f5132);
    background-size: 200% 200%;
    color: #fff;
    border-radius: 50px;
    font-size: 1.05rem;
    letter-spacing: 1px;
    padding: 0.75rem;
    text-transform: uppercase;
    transition: all 0.3s ease;
}

.modern-btn:hover {
    transform: scale(1.08);
    box-shadow: 0 10px 22px rgba(25,135,84,0.4);
}

/* Alerts */
.alert {
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
    margin-top: 0.6rem;
    border-radius: 50px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

/* Animations */
@keyframes borderFlow {
    0% {background-position: 0% 50%;}
    100% {background-position: 300% 50%;}
}

@keyframes pulse {
    0% {transform: scale(1);}
    50% {transform: scale(1.05);}
    100% {transform: scale(1);}
}
</style>

<?php include 'includes/scripts.php'; ?>
</body>
</html>
