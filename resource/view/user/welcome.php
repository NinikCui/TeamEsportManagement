<?php
require_once('../../../classes/member.php');
session_start();
if (!isset($_SESSION['active_user'])) {
    header('Location: ../login.php');
    exit;
}else{
    if($_SESSION['active_user']->profile != "member"){
        header('Location: ProjectFSP/notfound.php');
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
        
        .container .info{
            font-size: 32px;
        }
        .info p{
            font-size: 16px;
            margin-top: 0px;
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
        <div class="container-img">
            <img src="../../img/imgHome.png">
        </div>
        
    </div>
</body>
</html>
