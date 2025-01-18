<?php
require_once('../../../classes/member.php');
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
$cekTeam = false;
$idTeamUser = "";
$namaTeamUser ="";
$conn = new mysqli('localhost', 'root', '', 'fullstack');

$jp = new Join_Proposal($conn);
$teamUser = $jp ->CekTeamUser($idmember);
if(count($teamUser) == 2 ){
    $cekTeam = true;
    $idTeamUser = $teamUser[0];
    $namaTeamUser = $teamUser[1];
}




if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $action = $_POST['action'];

    
    if ($action == 'join') {
        $idTeam = $_POST['idteam'];
        $jp->JoinTeamUser($idmember,$idTeam);
    }

}

$maxRows = 5;
$page = (isset($_GET["page"]) && is_numeric($_GET["page"])) ? ($_GET["page"]) :1;
$pageStart = ($page - 1) * $maxRows;
$totalPages = $jp->totPagesUser($maxRows);
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

        .team-header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            width: 100%;      
            animation: slideUp 0.8s ease-out;

        }
        
        .team-logo {
            width: 400px; 
            height: auto;
            box-shadow: 0 4px 15px rgba(72, 52, 212, 0.1);
            border-radius: 10px;
            margin-bottom: 20px;
            max-width: 100%; 
            object-fit: contain; 
        }

        .team-title {
            color: #4834d4;
            font-size: 56px;
            font-weight: 800;
            margin-top: 20px;
        }


        .tab-navigation {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
            animation: slideUp 0.8s ease-out;
            
        }

        .tab-button {
            padding: 12px 24px;
            border: none;
            background: #f8f9fa;
            color: #666;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .tab-button:hover {
            background: #eee;
        }

        .tab-button.active {
            background: #4834d4;
            color: white;
        }
        @media screen and (max-width: 768px) {
            .team-logo {
                width: 300px; 
            }
            
            .team-title {
                font-size: 42px;
            }
        }

        @media screen and (max-width: 480px) {
            .team-logo {
                width: 250px; 
            }
            
            .team-title {
                font-size: 32px;
            }
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
            <div class="sec-hov">
                <li><a href="seeAllTeam.php">Team List</a></li>
            </div>
            <?php
                if($cekTeam){
                    echo"<li><a href=\"teamUser.php\" style=\"color:#4834D4;\"><b>Your Team</b></a></li>";
                }
                else{
                    echo"<li><a href=\"teamUser.php\" style=\"color:#4834D4;\"><b>Apply Team</b></a></li>";
                }
            ?>
            
                
            
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
        <?php 
            if($cekTeam) {
                $currentTab = isset($_GET['tab']) ? $_GET['tab'] : 'member';
                $page = (isset($_GET["page"]) && is_numeric($_GET["page"])) ? $_GET["page"] : 1;
                $maxRows = 5;
                $pageStart = ($page - 1) * $maxRows;
                $timestamp = time();
            
                echo "
                <div class='container-user'>
                    <div class='team-header'>
                        <img src='../../img/teamImg/$idTeamUser.jpg?v=$timestamp' 
                            class='team-logo'
                            \">
                        <h2 class='team-title'>$namaTeamUser Team's</h2>
                    </div>
            
                    <div class='tab-navigation'>
                        <button id='memberTab' 
                            class='tab-button " . ($currentTab == 'member' ? 'active' : '') . "' 
                            onclick=\"showContent('member')\">Member Team</button>
                        <button id='achievementTab' 
                            class='tab-button " . ($currentTab == 'achievement' ? 'active' : '') . "' 
                            onclick=\"showContent('achievement')\">Achievement Team</button>
                        <button id='eventTab' 
                            class='tab-button " . ($currentTab == 'event' ? 'active' : '') . "' 
                            onclick=\"showContent('event')\">Event Team</button>
                    </div>";
            
                // Member Tab Content
                if($currentTab == 'member') {
                    echo "
                    <div class='table-wrapper'>
                        <table class='team-table'>
                            <thead>
                                <tr>
                                    <th>ID Member</th>
                                    <th>Nama Member</th>
                                </tr>
                            </thead>
                            <tbody>";
                            
                    $members = $jp->getTeamMembers($idTeamUser, $pageStart, $maxRows);
                    $totalMembers = $jp->getTotalMembers($idTeamUser);
                    $totalPages = ceil($totalMembers / $maxRows);
                    
                    if (!empty($members)) {
                        foreach($members as $member) {
                            echo "<tr>
                                    <td>{$member['idmember']}</td>
                                    <td>{$member['username']}</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2' class='no-data'>No members found</td></tr>";
                    }
                    echo "</tbody></table></div>";
                    
                    if ($totalMembers > 0) {
                        echo "
                        <div class='pagination' aria-label='Page navigation'>
                            <div class='buttons'>
                                <a href='" . ($page <= 1 ? '#' : "teamUser.php?tab=member&page=" . ($page-1)) . "'>
                                    <button " . ($page <= 1 ? 'disabled' : '') . ">Previous</button>
                                </a>
                                <a href='" . ($page >= $totalPages ? '#' : "teamUser.php?tab=member&page=" . ($page+1)) . "'>
                                    <button " . ($page >= $totalPages ? 'disabled' : '') . ">Next</button>
                                </a>
                                <div class='page-info'>
                                    Showing Data " . ($pageStart + 1) . " to " . ($pageStart + $maxRows) . "
                                </div>
                            </div>
                        </div>";
                    }
                }
                
                // Achievement Tab Content
                if($currentTab == 'achievement') {
                    echo "
                    <div class='table-wrapper'>
                        <table class='team-table'>
                            <thead>
                                <tr>
                                    <th>Nama Achievement</th>
                                    <th>Tanggal</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>";
                            
                    $achievements = $jp->getTeamAchievements($idTeamUser, $pageStart, $maxRows);
                    $totalAchievements = $jp->getTotalAchievements($idTeamUser);
                    $totalPages = ceil($totalAchievements / $maxRows);
                    
                    if (!empty($achievements)) {
                        foreach($achievements as $achievement) {
                            echo "<tr>
                                    <td>{$achievement['name']}</td>
                                    <td>{$achievement['date']}</td>
                                    <td>{$achievement['description']}</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3' class='no-data'>No achievements found</td></tr>";
                    }
                    echo "</tbody></table></div>";
                    
                    if ($totalAchievements > 0) {
                        echo "
                        <div class='pagination' aria-label='Page navigation'>
                            <div class='buttons'>
                                <a href='" . ($page <= 1 ? '#' : "teamUser.php?tab=achievement&page=" . ($page-1)) . "'>
                                    <button " . ($page <= 1 ? 'disabled' : '') . ">Previous</button>
                                </a>
                                <a href='" . ($page >= $totalPages ? '#' : "teamUser.php?tab=achievement&page=" . ($page+1)) . "'>
                                    <button " . ($page >= $totalPages ? 'disabled' : '') . ">Next</button>
                                </a>
                                <div class='page-info'>
                                    Showing Data " . ($pageStart + 1) . " to " . ($pageStart + $maxRows) . "
                                </div>
                            </div>
                        </div>";
                    }
                }
                
                // Event Tab Content
                if($currentTab == 'event') {
                    echo "
                    <div class='table-wrapper'>
                        <table class='team-table'>
                            <thead>
                                <tr>
                                    <th>Nama Event</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>";
                            
                    $events = $jp->getTeamEvents($idTeamUser, $pageStart, $maxRows);
                    $totalEvents = $jp->getTotalEvents($idTeamUser);
                    $totalPages = ceil($totalEvents / $maxRows);
                    
                    if (!empty($events)) {
                        foreach($events as $event) {
                            echo "<tr>
                                    <td>{$event['name']}</td>
                                    <td>{$event['date']}</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2' class='no-data'>No events found</td></tr>";
                    }
                    echo "</tbody></table></div>";
                    
                    if ($totalEvents > 0) {
                        echo "
                        <div class='pagination' aria-label='Page navigation'>
                            <div class='buttons'>
                                <a href='" . ($page <= 1 ? '#' : "teamUser.php?tab=event&page=" . ($page-1)) . "'>
                                    <button " . ($page <= 1 ? 'disabled' : '') . ">Previous</button>
                                </a>
                                <a href='" . ($page >= $totalPages ? '#' : "teamUser.php?tab=event&page=" . ($page+1)) . "'>
                                    <button " . ($page >= $totalPages ? 'disabled' : '') . ">Next</button>
                                </a>
                                <div class='page-info'>
                                    Showing Data " . ($pageStart + 1) . " to " . ($pageStart + $maxRows) . "
                                </div>
                            </div>
                        </div>";
                    }
                }
                echo "</div>";
                
                echo "
                <script>
                    function showContent(tab) {
                        window.location.href = 'teamUser.php?tab=' + tab;
                    }
                </script>
                ";
            }else{
               echo " 
               <div class=\"container-user\">
                    <div class=\"table-wrapper\">
                        <table class=\"team-table\">
                            <thead>
                                <tr>
                                    <th>Id Team</th>
                                    <th>Game Name</th>
                                    <th>team Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>";
                                $teams = $jp ->GetProposalUser($pageStart,$maxRows);
                                if (!empty($teams)) {
                                    foreach ($teams as $team) {
                                        echo "<tr>
                                                <td>" .($team['idteam']) . "</td>
                                                <td>" . ($team['gameName']) . "</td>
                                                <td>" . ($team['teamName']) . "</td>
                                                <td>
                                                    <form method='POST' action='' style='display:inline;'>
                                                        <input type='hidden' name='idteam' value='" . ($team['idteam']) . "'>
                                                        <button type='submit' onclick='alrt()' name='action' value='join' 
                                                                style='color: #A0D683; border: none; background: none; cursor: pointer; font-size: 18px;'>
                                                            ✔ Join
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>";
                                    }
                                } else {
                                    echo'<tr>
                                                <td colspan="4" style="text-align: center;">None</td>
                                            </tr>';
                                }
                                
                echo  "     </tbody>
                        </table>
                    </div>
                    <div class='pagination' aria-label='Page navigation'>
                        <div class='buttons'>
                            <a href='" . ($page <= 1 ? '#' : "teamUser.php?page=" . ($page-1)) . "'>
                                <button " . ($page <= 1 ? 'disabled' : '') . ">Previous</button>
                            </a>
                            <a href='" . ($page >= $totalPages ? '#' : "teamUser.php?page=" . ($page+1)) . "'>
                                <button " . ($page >= $totalPages ? 'disabled' : '') . ">Next</button>
                            </a>
                            <div class='page-info'>
                                Showing Data " . ($pageStart + 1) . " to " . ($pageStart + $maxRows) . "
                            </div>
                        </div>
                    </div>
               </div>";
            
                
            }
                
               

        ?>
        

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
       
        <script>
            function alrt() {
                alert("Data sudah masuk, Tunggu Konfirmasi.");
            
            }
        </script>
    </div>
</body>
</html>
