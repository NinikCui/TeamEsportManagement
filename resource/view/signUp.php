<?php
require_once('../../classes/member.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli('localhost', 'root', '', 'fullstack');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $first_name = $_POST['firstName'];
    $last_name = $_POST['lastName'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    require_once('../../classes/member.php');
    $member = new Member($conn);
    if ($member->Registrasi($username, $password, $first_name, $last_name)) {
        header("Location: signUp.php?status=success");
        exit();
    } else {
        header("Location: signUp.php?status=failed");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link href="../css/menu/navMenu.css" rel="stylesheet">
    <link href="../css/menu/logReg.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <style>
        .image-container {
            width: 50%;
            background-image: url("../img/signUp.jpg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
</head>
<body>
<nav class="navbar">
        <div class="menu-toggle">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <a href="index.php" class="logo">
            <img src="../img/hiksrotIcon.png" alt="HIKSROT">
            HIKSROT
        </a>
        <ul class="nav-section" style="margin-right:150px">
            
            <div class="sec-hov">
                <li><a href="../../index.php" >Home</a></li>
            </div>

            <div class="sec-hov">
                <li><a href="../../gameDetail.php">Game Detail</a></li>
            </div>
            
            <div class="sec-hov">
                <li><a href="../../teamDetail.php">Team Detail</a></li>
            </div>
        </ul>
        <div class="nav-button">
            
        </div>
    </nav>
    <div class="container">
        <div class="image-container"></div> 
        <div class="form-container">
            <h1>Start for free</h1>
            <h1>Create new account</h1>
            <p>Find out what everyone is talking about!</p>
            <form action="" method="POST">
                <input type="text" name="firstName" placeholder="First name" required>
                <input type="text" name="lastName" placeholder="Last name" required>
                <input type="text" name="username" placeholder="Username" required>
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <span class="see-password" id="passEye"><img src="../img/passwordMata.png"></span>
                </div>
                <button type="submit">Sign Up</button>
            </form>
            <p class="signin-text">Already have an account? <a href="login.php">Sign In</a></p>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const urlPar = new URLSearchParams(window.location.search);
            const status = urlPar.get('status');

            if (status === 'success') {
                alert('Registration successful! Redirecting to login page.');
                window.location.href = 'login.php';
            } else if (status === 'failed') {
                alert("Registration Failed, Username is already taken");
            }
        });


        //FOR EYE PASS
        document.querySelector("#passEye").addEventListener("click", function () {
            const password = document.querySelector("#password");
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
        });

        //NAV RESPON
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.querySelector('.menu-toggle');
            const navSection = document.querySelector('.nav-section');
            const navButton = document.querySelector('.nav-button');

            menuToggle.addEventListener('click', function() {
                this.classList.toggle('active');
                navSection.classList.toggle('active');
                navButton.classList.toggle('active');
            });

            // Menutup menu saat mengklik di luar
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.menu-toggle') && 
                    !event.target.closest('.nav-section') && 
                    !event.target.closest('.nav-button')) {
                    menuToggle.classList.remove('active');
                    navSection.classList.remove('active');
                    navButton.classList.remove('active');
                }
            });

            // Mencegah menu tertutup saat mengklik di dalam nav
            navSection.addEventListener('click', function(event) {
                event.stopPropagation();
            });
        });

    </script>
</body>
</html>
