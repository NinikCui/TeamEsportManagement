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

        /* Navbar Styles */
        .navbar {
            padding: 15px 20px;
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            font-weight: bold;
        }

        .logo img {
            height: 40px;
            width: auto;
        }

        .nav-section {
            display: flex;
            gap: 20px;
            list-style: none;
        }

        .nav-section a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
        }

        .photo-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff;
        }

        .photo-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .btn-logout button {
            background: #800080;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-logout button:hover {
            background: #5618b8;
        }

        /* Hamburger Menu */
        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
            padding: 10px;
            z-index: 100;
        }

        .hamburger span {
            width: 25px;
            height: 3px;
            background-color: #fff;
            margin: 2px 0;
            transition: 0.4s;
        }

        /* Hamburger Animation */
        .hamburger.active span:nth-child(1) {
            transform: rotate(-45deg) translate(-5px, 6px);
        }

        .hamburger.active span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active span:nth-child(3) {
            transform: rotate(45deg) translate(-5px, -6px);
        }

        /* Media Queries */
        @media screen and (max-width: 768px) {
            .hamburger {
                display: flex;
                position: absolute;
                top: 15px;
                right: 20px;
            }

            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .nav-section {
                display: none;
                width: 100%;
                flex-direction: column;
                padding: 20px 0;
                position: absolute;
                top: 70px;
                left: 0;
                z-index: 99;
            }

            .nav-section.active {
                display: flex;
            }

            .nav-section li {
                text-align: center;
                padding: 10px 0;
                width: 100%;
            }

            .photo-profile {
                display: none;
                width: 100%;
                justify-content: center;
                padding: 20px 0;
                position: absolute;
                top: 100%;
                left: 0;
                flex-direction: column;
                align-items: center;
                gap: 15px;
            }

            .photo-profile.active {
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
        <div class="logo">
            <img src="../../img/hiksrotIcon.png" alt="Hiksrot Logo">
            HIKSROT
        </div>
        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <ul class="nav-section">
            <li><a href="welcome.php"><u>Home</u></a></li>
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
        // Hamburger Menu Toggle
        document.querySelector('.hamburger').addEventListener('click', function() {
            this.classList.toggle('active');
            document.querySelector('.nav-section').classList.toggle('active');
            document.querySelector('.photo-profile').classList.toggle('active');
        });

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
    </script>
</body>
</html>
