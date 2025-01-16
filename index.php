<?php
require_once('classes/Achievement.php');
session_start();
if (isset($_SESSION['username'])) {
    if ($_SESSION['profile'] == "admin") {
        header('Location: admin/proposal.php');
    } else {
        header('Location: user/welcome.php');
    }
    exit();
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
    <link href="resource/css/menu/navMenu.css" rel="stylesheet">
    <link href="resource/css/menu/bodyMenu.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
   
</head>
<body>
    <nav class="navbar">
        <div class="menu-toggle">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <a href="index.php" class="logo">
            <img src="resource/img/hiksrotIcon.png" alt="HIKSROT">
            HIKSROT
        </a>
        <ul class="nav-section">
            <li><a href="index.php" style="color:#4834D4;"><b>Home</b></a></li>
            
            <div class="sec-hov">
                <li><a href="gameDetail.php">Game Detail</a></li>
            </div>
            
            <div class="sec-hov">
                <li><a href="teamDetail.php">Team Detail</a></li>
            </div>
        </ul>
        <div class="nav-button">
            <button class="sign-up-button"><a href="resource/view/signUp.php">Sign Up</a></button>
            <button class="login-button"><a href="resource/view/login.php">Login</a></button>
        </div>
    </nav>
    <script>
        //HAMBURGER MENU
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.querySelector('.menu-toggle');
            const navSection = document.querySelector('.nav-section');

            menuToggle.addEventListener('click', function() {
                this.classList.toggle('active');
                navSection.classList.toggle('active');
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.menu-toggle') && !event.target.closest('.nav-section')) {
                    menuToggle.classList.remove('active');
                    navSection.classList.remove('active');
                }
            });

            // Prevent menu from closing when clicking inside nav
            navSection.addEventListener('click', function(event) {
                event.stopPropagation();
            });

            // Add active class to current page tab
            const currentPage = window.location.pathname.split('/').pop() || 'index.php';
            const navLinks = document.querySelectorAll('.nav-section a');
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPage) {
                    link.classList.add('active');
                }
            });
        });





    </script>


    <div class="container-index">
        <div class="main">
            <div class="main-content">
                <h1>Welcome to <span class="esport">HIKSROT</span></h1>
                <p>HIKSROT is a professional esports organization committed to excellence in competitive gaming. Our teams compete at the highest levels across multiple game titles, representing the pinnacle of esports achievement.</p>
            </div>
            <div class="main-image">
                <img src="resource/img/hiksrotIcon.png">
            </div>
        </div>

        <div class="desc">
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

        <div class="achie">
            <h2>Our <span class="esport">Achievement</span></h2>
            <div class="achie-list">
                <?php 
                $achievements = $achie->ReadNewAchie();
                if (!empty($achievements)):
                    $icons = [
                        'https://cdn-icons-png.flaticon.com/128/940/940543.png',
                        'https://cdn-icons-png.flaticon.com/128/3770/3770295.png',
                        'https://cdn-icons-png.flaticon.com/128/7340/7340754.png'
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
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-logo">
                    <img src="resource/img/hiksrotIcon.png">
                    <h3>HIKSROT</h3>
                </div>
                <div class="contact-info">
                    <p>📍 UNIVERSITAS SURABAYA</p>
                    <p>📞 +62 812345678</p>
                </div>
            </div>
            
        </div>
    </footer>
</body>
</html>
