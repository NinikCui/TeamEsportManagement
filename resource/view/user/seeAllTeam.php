<?php
require_once('../../../classes/member.php');
require_once('../../../classes/Team.php');
require_once('../../../classes/Join_Proposal.php');

session_start();
if (!isset($_SESSION['active_user'])) {
    header('Location: ../login.php');
    exit;
}
else{
    if($_SESSION['active_user']->profile != "member"){
        header('Location: ProjectFSP/utilities/404.php');
        exit;
    }
}

$idmember=  $_SESSION['active_user']->idmember;
$conn = new mysqli('localhost', 'root', '', 'fullstack');
$t = new Team($conn);
$cekTeam = false;

$jp = new Join_Proposal($conn);
$teamUser = $jp ->CekTeamUser($idmember);
if(count($teamUser) == 2 ){
    $cekTeam = true;
    $idTeamUser = $teamUser[0];
    $namaTeamUser = $teamUser[1];
}



$maxRows = 5;
$page = (isset($_GET["page"]) && is_numeric($_GET["page"])) ? ($_GET["page"]) :1;
$pageStart = ($page - 1) * $maxRows;
$totalPages = $t->ReadPages($maxRows);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link href="../../css/menu/navMenu.css" rel="stylesheet">
    <link href="../../css/menu/bodyUser.css" rel="stylesheet">
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
           
            
            <div class="sec-hov">
                <li><a href="welcome.php" >Home</a></li>
            </div>
                <li><a href="seeAllTeam.php" style="color:#4834D4;"><b>Team List</b></a></li>

            <div class="sec-hov">
            <?php
                if($cekTeam){
                    echo"<li><a href=\"teamUser.php\">Your Team</a></li>";
                }
                else{
                    echo"<li><a href=\"teamUser.php\">Apply Team</a></li>";
                }
            ?>
                
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

    <div class="container-user">
        <div class="table-wrapper">
            <table class="team-table">
                <thead>
                    <tr>
                        <th>Game Name</th>
                        <th>Team Name</th>
                        <th>Logo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $teams = $t->ReadDataTeam($pageStart, $maxRows);
                    if (!empty($teams)) {
                        foreach ($teams as $team) {
                            echo "<tr>";
                            echo "<td class='game-name'>" . $team["gameName"] . "</td>";
                            echo "<td class='team-name'>" . $team["teamName"] . "</td>";
                            echo "<td><img src='../../img/teamImg/{$team['idteam']}.jpg?v=" . time() . "' class='team-logo' alt='{$team['teamName']} Logo'></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3' class='no-teams'>No teams available at the moment</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>


        <div class="pagination" aria-label="Page navigation">
                <div class="buttons">
                    <a href="<?php echo ($page <= 1) ? '#' : "seeAllTeam.php?page=".($page-1); ?>" >
                        <button <?php echo ($page <= 1) ? 'disabled' : ''; ?>>Previous</button>
                    </a>
                    <a href="<?php echo ($page >= $totalPages) ? '#' : "seeAllTeam.php?page=".($page+1); ?>">
                        <button <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>>Next</button>
                    </a>
                    <div class="page-info">
                    <?php 
                        echo("Showing Data " . $pageStart + 1 . " to  " . $pageStart + $maxRows);
                    ?>
                    </div>
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
