<?php
require_once('../../../classes/member.php');
require_once('../../../classes/Achievement.php');

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
$conn = new mysqli('localhost', 'root', '', 'fullstack');
$achie = new Achievement($conn);
$maxRows = 5;
$page = (isset($_GET["page"]) && is_numeric($_GET["page"])) ? ($_GET["page"]) :1;
$pageStart = ($page - 1) * $maxRows;


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link href="../../css/menu/navMenu.css" rel="stylesheet">
    <link href="../../css/menu/bodyMenu.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <style>
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
            background-color: white;
            color: #4834d4;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            border: 2px solid #4834d4;


        }
        .navbar .btn-logout button:hover {
            background-color: #4834d4;
            color: white;
            border: 2px solid transparent;
            border-radius: 5px;
            cursor: pointer;

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
        <a href="welcome.php" class="logo">
            <img src="../../img/hiksrotIcon.png" alt="HIKSROT">
            HIKSROT
        </a>
        <ul class="nav-section">
            <li><a href="welcome.php" style="color:#4834D4;"><b>Home</b></a></li>
            
            <div class="sec-hov">
                <li><a href="seeAllTeam.php">Team List</a></li>
            </div>
            
            <div class="sec-hov">
                <li><a href="teamUser.php">Apply Team</a></li>
            </div>
        </ul>
        <div class="photo-profile">
            <img src="../../img/fotoProfile.png" alt="Foto Profil">
            <h3>Hello, <?php echo $_SESSION['active_user']->fname; ?></h3>
            <div class="btn-logout">
                <button class="logout" onclick="confirmLogout()">Log Out</button>
            </div>
        </div>
        
    </nav>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.querySelector('.menu-toggle');
            const navSection = document.querySelector('.nav-section');
            const navButton = document.querySelector('.photo-profile');

            menuToggle.addEventListener('click', function() {
                this.classList.toggle('active');
                navSection.classList.toggle('active');
                navButton.classList.toggle('active');
            });

            // Menutup menu saat mengklik di luar
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.menu-toggle') && 
                    !event.target.closest('.nav-section') && 
                    !event.target.closest('.photo-profile')) {
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



        
        function confirmLogout() {
            var result = confirm("Apakah Anda yakin ingin logout?");
            if (result) {
                window.location.href = "../logout.php";
            }
        }
    </script>
    <div class="container-index">
        <div class="main">
            <div class="main-content">
                <h1>Welcome to <span class="esport">HIKSROT</span></h1>
                <p>HIKSROT is a professional esports organization committed to excellence in competitive gaming. Our teams compete at the highest levels across multiple game titles, representing the pinnacle of esports achievement.</p>
            </div>
            <div class="main-image">
                <img src="../../img/imgHome.png">
            </div>
        </div>

        <div class="desc">
            <h2>How We <span class="esport">Work</span> </h2>
            <div class="desc-list">
                <div class="desc-card">
                    <img src="https://cdn-icons-png.flaticon.com/128/4292/4292793.png" alt="Achievement Icon" class="desc-icon">
                    <h3>Professional Teams</h3>
                    <p>Home to world-class teams competing in various esports titles including VALORANT, Mobile Legends, and PUBG Mobile.</p>
                </div>
                <div class="desc-card">
                    <img src="https://cdn-icons-png.flaticon.com/128/1436/1436664.png" alt="Training Icon" class="desc-icon">
                    <h3>Elite Training</h3>
                    <p>State-of-the-art training facilities and professional coaching staff to develop player potential.</p>
                </div>
                <div class="desc-card">
                    <img src="https://cdn-icons-png.flaticon.com/128/10764/10764993.png" alt="Community Icon" class="desc-icon">
                    <h3>Community Events</h3>
                    <p>Regular fan engagement through tournaments, meet & greets, and exclusive content.</p>
                </div>
            </div>
            

        </div>

        <div class="achie">
            <h2>Our <span class="esport">Achievement</span></h2>
            <div class="achie-list">
                <?php 
                $achievements = $achie->ReadNewAchie();
                if (!empty($achievements)):
                    $icons2 = [
                        'https://cdn-icons-png.flaticon.com/128/940/940543.png',
                        'https://cdn-icons-png.flaticon.com/128/3770/3770295.png',
                        'https://cdn-icons-png.flaticon.com/128/7340/7340754.png'
                    ];
                    $icons = [
                        '../../img/assets/achie_1.png',
                        '../../img/assets/achie_2.png',
                        '../../img/assets/achie_3.png'
                    ];
                    foreach($achievements as $index => $achievement): 
                ?>
                    <div class="achie-detail">
                        <img src="<?php echo $icons[$index]; ?>" >
                        <h3><?php echo $achievement['name']; ?></h3>
                        <p><?php echo $achievement['description']; ?></p>
                        <span><?php echo date('d M Y', strtotime($achievement['date'])); ?></span>
                    </div>
                <?php 
                    endforeach;
                else:
                ?>
                    <div class="no-achievement">
                        <p>No achievements available</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
    </div>


    <hr class="garis-abu">
    <footer>
        <div class="footer-content">
            <div class="footer-logo">
                <img src="../../img/hiksrotIcon.png" >
                <h3>HIKSROT</h3>
            </div>
            <div class="contact-info">
                <p>📍 Universitas Surabaya</p>
                <p>📞 +62 896725960</p>
            </div>
        </div>
    </footer>

    
</body>
</html>
