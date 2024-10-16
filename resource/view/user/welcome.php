<?php
require_once('../../../classes/member.php');
session_start();
if (!isset($_SESSION['active_user'])) {
    header('Location: ../login.php');
    exit;
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
        body {
            background-image: url("../../img/BG.png");
        }
        .container{
            margin-left: 15%;
            margin-top: 5%;
            display: flex;
            flex-wrap:  wrap;
            gap: 50px;
        }
        .cont-bubble{
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            max-width: 500px;
        }
        .container .info{
            font-size: 32px;
        }
        .info p{
            font-size: 16px;
            margin-top: 0px;
        }
        #pubgm{
            margin-top: 30px;
            width: 250px;
            height: 250px;
        }
        #valo{
            margin-right: 50px;
            width: 270px;
            height: 270px;
        }
        .bubble {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: linear-gradient(to bottom, rgba(100, 90, 150, 0.5),rgba(255, 255, 255, 0));
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }
        .bubble img{
            object-fit: cover;
            max-width: 90%;
            max-height: 80%;
        }
    </style>
</head>
<body>
<nav class="navbar">
        <div class="logo">
            <img src="../../img/hiksrotIcon.png" alt="Hiksrot Logo">
            HIKSROT
        </div>
        <ul class="nav-section">
            <li><a href="welcome.php"><u>Home</u></a></li>
            <li><a href="teamUser.php">Team</a></li>
        </ul>
        <div class="photo-profile">
            <img src="../../img/fotoProfile.png" alt="Foto Profil">
            <h5>Hello, <?php  echo $_SESSION['active_user']->fname;?></h5>
            <div  class="btn-logout">
                <button  class="logout" onclick="confirmLogout()">Log Out</button>
            </div>
        </div>
        <script>
            function confirmLogout() {
            var result = confirm("Apakah Anda yakin ingin logout?");
            if (result) {
                window.location.href = "../logout.php"; 
            } 
        }
        </script>
    </nav>
    <div class="container">
        <div class="info">
            <h1>DISCOVER COLLECT <br>GAMES FROM US</h1>
            <p>Welcome to HIKSROT, the ultimate hub for eSports enthusiasts!<br>
             Dive into the latest news, game strategies, and exclusive content <br>
             on your favorite competitive games. Join us to stay ahead in the <br>
             game and connect with a community of passionate gamers!</p>
        </div>
        <div class="cont-bubble">
            <div class="bubble">
                <img src="https://upload.wikimedia.org/wikipedia/commons/1/14/Mobile_Legends_Bang_Bang_logo.png" alt="Mobile Legends: Bang Bang logo">
            </div>
            <div id="pubgm" class="bubble">
                <img src="https://upload.wikimedia.org/wikipedia/commons/4/43/PUBG_Mobile_simple_logo_black.png" alt="PUBGM logo">
            </div>
            <div id="valo" class="bubble">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fc/Valorant_logo_-_pink_color_version.svg/1280px-Valorant_logo_-_pink_color_version.svg.png" alt="PUBGM logo">
            </div>
        </div>
        
        
    </div>
</body>
</html>
