<?php
session_start();
if (isset($_SESSION['username'])) {
    if($_SESSION['profile'] == "admin"){
        header('Location: admin/proposal.php');
    }else{
        header('Location: user/welcome.php');
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HIKSROT</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link href="resource/css/nav.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Poppins", sans-serif;
            color: white;
            background-image: url("resource/img/BG.png");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            position: relative;
        }

        .logo {
            display: flex;
            align-items: center;
            font-size: 24px;
            font-weight: bold;
        }

        .logo img {
            width: 50px;
            height: auto;
            margin-right: 10px;
        }
        

        .menu-toggle {
            display: none;
            flex-direction: column;
            cursor: pointer;
            padding: 10px;
        }

        .menu-toggle span {
            width: 25px;
            height: 3px;
            background-color: white;
            margin: 2px 0;
            transition: 0.4s;
        }

        .nav-section {
            list-style: none;
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nav-section li a {
            text-decoration: none;
            color: white;
            font-size: 18px;
            padding: 5px 15px;
        }

        .nav-button button {
            margin-left: 15px;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }

        .login-button {
            background-color: #5d20a7;
            color: white;
        }

        .nav-button .sign-up-button {
            background-color: transparent;
            color: white;
            border: 2px solid white;
        }

        .nav-button a {
            color: white;
            text-decoration: none;
        }

        .container {
            margin-left: 15%;
            margin-top: 5%;
            display: flex;
            flex-wrap: wrap;
            gap: 50px;
        }

        .container .info {
            font-size: 32px;
        }

        .info p {
            font-size: 16px;
            margin-top: 0px;
        }

        

        @media screen and (max-width: 868px) {
            .menu-toggle {
                display: flex;
            }

            .navbar {
                flex-wrap: wrap;
            }

            .nav-section {
                display: none;
                width: 100%;
                flex-direction: column;
                text-align: center;
                background-color: rgba(93, 32, 167, 0.9);
                position: absolute;
                top: 100%;
                left: 0;
                padding: 20px 0;
            }

            .nav-section.active {
                display: flex;
            }

            .nav-section li a {
                margin: 10px 0;
                display: block;
            }

            .container {
                margin: 20px;
                justify-content: center;
                text-align: center;
            }

            .container .info {
                font-size: 28px;
            }

            .info p {
                font-size: 14px;
            }

            .container-img img {
                max-width: 100%;
                height: auto;
            }
        }

        @media screen and (max-width: 480px) {
            .navbar {
                padding: 10px;
            }

            .logo {
                font-size: 15px;
            }

            .logo img {
                width: 30px;
            }

            .nav-button {
                display: flex;
                gap: 10px;
            }

            .nav-button button {
                padding: 8px 15px;
                font-size: 14px;
                margin-left: 5px;
            }

            .container .info {
                font-size: 24px;
            }

            .info p {
                font-size: 12px;
            }
        }
        
    </style>
</head>
<body>
    <?php
    $is_logged_in = isset($_SESSION['active_user']); 
    ?>
    <script>
        function checkLogin(isLoggedIn) {
            if (isLoggedIn == false) {
                alert("Anda harus login terlebih dahulu untuk mengakses halaman ini.");
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.querySelector('.menu-toggle');
            const navSection = document.querySelector('.nav-section');
            
            menuToggle.addEventListener('click', function() {
                navSection.classList.toggle('active');
            });
        });
    </script>
    <nav class="navbar">
        <div class="menu-toggle">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="logo">
            <img src="resource/img/hiksrotIcon.png" alt="Hiksrot Logo">
            HIKSROT
        </div>
        <ul class="nav-section">
            <li><a href="index.php"><u>Home</u></a></li>
            <li><a href="seeAllTeam.php">Team Detail</a></li>
        </ul>
        <div class="nav-button">
            <button class="sign-up-button"><a href="resource/view/signUp.php">Sign Up</a></button>
            <button class="login-button"><a href="resource/view/login.php">Login</a></button>
        </div>
    </nav>
    <div class="container">
        <div class="info">
            <h1>DISCOVER COLLECT <br>GAMES FROM US</h1>
            <p>Welcome to HIKSROT, the ultimate hub for eSports enthusiasts!<br>
             Dive into the latest news, game strategies, and exclusive content <br>
             on your favorite competitive games. Join us to stay ahead in the <br>
             game and connect with a community of passionate gamers!</p>
        </div>
        <div class="container-img">
            <img src="resource/img/imgHome.png">
        </div>
        
    </div>
</body>
</html>