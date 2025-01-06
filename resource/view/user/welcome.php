<?php
require_once('../../../classes/member.php');
session_start();
if (!isset($_SESSION['active_user'])) {
    header('Location: ../login.php');
    exit;
}else{
    if($_SESSION['active_user']->profile != "member"){
        header('Location: ProjectFSP/utilities/404.php');
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link href="../../css/nav.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-image: url("../../img/BG.png");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            color: #fff;
        }

        .container {
            max-width: 1200px;
            margin: 5% auto;
            padding: 0 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 50px;
            align-items: center;
            justify-content: center;
        }
        
        .info {
            flex: 1;
            min-width: 300px;
        }

        .info h1 {
            font-size: clamp(24px, 5vw, 32px);
            line-height: 1.3;
            margin-bottom: 20px;
        }

        .info p {
            font-size: clamp(14px, 3vw, 16px);
            line-height: 1.6;
            margin-top: 0;
        }

        .container-img {
            flex: 1;
            min-width: 300px;
            max-width: 100%;
        }

        .container-img img {
            width: 100%;
            height: auto;
            display: block;
        }

        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            color: white;
        }

        .navbar .hamburger {
            display: none;
            font-size: 24px;
            cursor: pointer;
        }

        .navbar .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 24px;
            font-weight: bold;
        }

        .navbar .logo img {
            height: 40px;
        }

        .navbar .nav-section {
            display: flex;
            gap: 20px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .navbar .nav-section li a {
            text-decoration: none;
            color: white;
            font-size: 18px;
        }

        .navbar .nav-section li a:hover {
            text-decoration: underline;
        }

        .navbar .photo-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar .photo-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .navbar .btn-logout button {
            background-color: purple;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        /* Media Queries */
        @media screen and (max-width: 768px) {
            .navbar .hamburger {
                display: block;
            }

            .navbar .nav-section {
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

            .navbar .nav-section.active {
                display: flex;
            }

            .container {
                margin-top: 15%;
                text-align: center;
            }
        }

        @media screen and (max-width: 480px) {
            .container {
                gap: 30px;
                margin-top: 20%;
            }

            .info h1 {
                font-size: 24px;
            }

            .info p {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
<nav class="navbar">
        <!-- Hamburger Icon -->
        <div class="hamburger" onclick="toggleMenu()">
            &#9776; 
        </div>
        <div class="logo">
            <img src="../../img/hiksrotIcon.png" alt="Hiksrot Logo">
            HIKSROT
        </div>
        <ul class="nav-section">
            <li><a href="welcome.php">Home</a></li>
            <li><a href="seeAllTeam.php">Team Detail</a></li>
            <li><a href="teamUser.php">Apply Team</a></li>
        </ul>
        <div class="photo-profile">
            <img src="../../img/fotoProfile.png" alt="Foto Profil">
            <h5>Hello, <?php echo $_SESSION['active_user']->fname; ?></h5>
            <div class="btn-logout">
                <button class="logout" onclick="confirmLogout()">Log Out</button>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="info">
            <h1>DISCOVER COLLECT<br>GAMES FROM US</h1>
            <p>Welcome to HIKSROT, the ultimate hub for eSports enthusiasts!
                Dive into the latest news, game strategies, and exclusive content
                on your favorite competitive games. Join us to stay ahead in the
                game and connect with a community of passionate gamers!</p>
        </div>
        <div class="container-img">
            <img src="../../img/imgHome.png" alt="Home Image">
        </div>
    </div>

    <script>
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const hamburger = document.querySelector('.hamburger');
            const navSection = document.querySelector('.nav-section');
            const photoProfile = document.querySelector('.photo-profile');
            
            if (!event.target.closest('.navbar')) {
                hamburger.classList.remove('active');
                navSection.classList.remove('active');
                photoProfile.classList.remove('active');
            }
        });

        function confirmLogout() {
            var result = confirm("Apakah Anda yakin ingin logout?");
            if (result) {
                window.location.href = "../logout.php";
            }
        }

        function toggleMenu() {
                const navSection = document.querySelector('.nav-section');
                navSection.classList.toggle('active');
            }
    </script>
</body>
</html>
