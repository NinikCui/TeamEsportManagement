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
        .container {
            width: 80%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        .table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
            font-size: 18px;
        }

        .table th, .table td {
            padding: 15px;
            background-color: rgba(255, 255, 255, 0.2);
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
            
        }

        .table th {
            background-color: rgba(255, 255, 255, 0.3);
        }

        .actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .actions .approve {
            color: green;
            cursor: pointer;
        }

        .actions .decline {
            color: red;
            cursor: pointer;
        }
        .buttons {
            display: flex;
            justify-content: space-between;
            float: right;
            margin-left: 10px;
        }

        .buttons button {
            background-color: #fff;
            color: #3c0036;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 20px;
            
        }


        .tab-link {
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .tab-link:hover {
            background-color: rgba(255, 255, 255, 0.4);
        }

        .tab-link.active {
            background-color: rgba(255, 255, 255, 0.5);
        }

        .tab-content {
            display: none;
            margin-top: 20px;
        }

        .tab-content.active {
            display: block;
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
            <li><a href="welcome.php">Home</a></li>
            <li><a href="seeAllTeam.php">Team Detail</a></li>

            <li><a href="teamUser.php"><u>Apply Team</u></a></li>
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
            function showContent(contentType) {
                // Sembunyikan semua konten
                window.location.href = `teamUser.php?tab=${contentType}&page=1`;
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                });
                
                // Hapus class active dari semua tab
                document.querySelectorAll('.tab-link').forEach(tab => {
                    tab.classList.remove('active');
                });
                
                // Tampilkan konten yang dipilih
                document.getElementById(contentType + 'Content').classList.add('active');
                document.getElementById(contentType + 'Tab').classList.add('active');

                // Selalu set page ke 1 saat berganti tab
                const newUrl = new URL(window.location);
                newUrl.searchParams.set('tab', contentType);
                newUrl.searchParams.set('page', '1');
                window.location.href = newUrl.toString();

                <?php if($cekTeam): ?>
                setTimeout(() => refreshTeamImage('<?php echo $idTeamUser; ?>'), 100);
                <?php endif; ?>
            }

            //  untuk handle back/forward browser
            window.addEventListener('popstate', function() {
                const urlParams = new URLSearchParams(window.location.search);
                const currentTab = urlParams.get('tab') || 'member';
                showContent(currentTab);
            });

        
            document.addEventListener('DOMContentLoaded', function() {
                const urlParams = new URLSearchParams(window.location.search);
                const currentTab = urlParams.get('tab') || 'member';
            });

            function refreshTeamImage(idTeam) {
                const teamImages = document.querySelectorAll(`img[src*="${idTeam}.jpg"]`);
                const timestamp = new Date().getTime();
                
                teamImages.forEach(img => {
                    const newSrc = img.src.split('?')[0] + '?v=' + timestamp;
                    img.src = newSrc;
                });
            }
            function setupImageRefresh(idTeam) {
                // Refresh gambar setiap kali tab berubah
                const tabLinks = document.querySelectorAll('.tab-link');
                tabLinks.forEach(tab => {
                    tab.addEventListener('click', () => {
                        setTimeout(() => refreshTeamImage(idTeam), 100);
                    });
                });

                // Refresh gambar saat halaman dimuat
                window.addEventListener('load', () => {
                    refreshTeamImage(idTeam);
                });
            }
        </script>
    </nav>
    <div class="container">
        <?php 
            if($cekTeam)
            {
                $currentTab = isset($_GET['tab']) ? $_GET['tab'] : 'member';
                $page = (isset($_GET["page"]) && is_numeric($_GET["page"])) ? $_GET["page"] : 1;
                $maxRows = 5;
                $pageStart = ($page - 1) * $maxRows;
                $timestamp = time();

                echo "
               <table class=\"table\">  
                    <thead>
                        <tr>
                            <th colspan=\"2\" > 
                                <img src=\"../../img/teamImg/$idTeamUser.jpg?v=$timestamp\" width=\"400\" 
                                    onerror=\"this.onerror=null; this.src='../../img/default-team.jpg';\" 
                                    onload=\"this.style.display='block';\"> 
                            </th>
                            <th >  $namaTeamUser Team's</th>
                        </tr>
                        <tr>
                            <th id=\"memberTab\" class=\"tab-link " . ($currentTab == 'member' ? 'active' : '') . "\" onclick=\"showContent('member')\">Member Team</th>
                            <th id=\"achievementTab\" class=\"tab-link " . ($currentTab == 'achievement' ? 'active' : '') . "\" onclick=\"showContent('achievement')\">Achievement Team</th>
                            <th id=\"eventTab\" class=\"tab-link " . ($currentTab == 'event' ? 'active' : '') . "\" onclick=\"showContent('event')\">Event Team</th>
                        </tr>
                    </thead> 
                </table>";
                if($currentTab == 'member') {
                    echo "<table class='table'>
                        <tr>
                            <th>ID Member</th>
                            <th>Nama Member</th>
                        </tr>";
                        
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
                        echo "<tr><td colspan='2'>No members found</td></tr>";
                    }
                    echo "</table>";
                    
                    if ($totalMembers > 0) {
                        echo "<div class='buttons'>";
                        if($page > 1) {
                            echo "<a href='teamUser.php?tab=member&page=" . ($page - 1) . "'><button>Previous</button></a>";
                        }
                        if($page < $totalPages) {
                            echo "<a href='teamUser.php?tab=member&page=" . ($page + 1) . "'><button>Next</button></a>";
                        }
                        echo "</div>";
                    }
                }
                
                // Achievement tab
                if($currentTab == 'achievement') {
                    echo "<table class='table'>
                        <tr>
                            <th>Nama Achievement</th>
                            <th>Tanggal</th>
                            <th>Description</th>
                        </tr>";
                        
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
                        echo "<tr><td colspan='3'>No achievements found</td></tr>";
                    }
                    echo "</table>";
                    
                    if ($totalAchievements > 0) {
                        echo "<div class='buttons'>";
                        if($page > 1) {
                            echo "<a href='teamUser.php?tab=achievement&page=" . ($page - 1) . "'><button>Previous</button></a>";
                        }
                        if($page < $totalPages) {
                            echo "<a href='teamUser.php?tab=achievement&page=" . ($page + 1) . "'><button>Next</button></a>";
                        }
                        echo "</div>";
                    }
                }
                
                // Event tab
                if($currentTab == 'event') {
                    echo "<table class='table'>
                        <tr>
                            <th>Nama Event</th>
                            <th>Tanggal</th>
                        </tr>";
                        
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
                        echo "<tr><td colspan='2'>No events found</td></tr>";
                    }
                    echo "</table>";
                    
                    if ($totalEvents > 0) {
                        echo "<div class='buttons'>";
                        if($page > 1) {
                            echo "<a href='teamUser.php?tab=event&page=" . ($page - 1) . "'><button>Previous</button></a>";
                        }
                        if($page < $totalPages) {
                            echo "<a href='teamUser.php?tab=event&page=" . ($page + 1) . "'><button>Next</button></a>";
                        }
                        echo "</div>";
                    }
                }

                
            }else{
               echo " <table class=\"table\">
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
                
                $totalPages = $jp->totPagesUser($maxRows);
                
                echo " </tbody>
                </table>";

                echo "<div>";
                echo "Showing data " . $pageStart + 1 . " to  " . $pageStart + $maxRows;
                echo "</div>
                      <div class =\"buttons\">
                      <a href=\"";if($page <= 1){echo " # ";} else {echo "teamUser.php?page=". $page - 1;} echo "\"><button>Back</button></a>";
                      echo "<a href=\"";if($page >= $totalPages){echo"#";} else{echo"teamUser.php?page=".$page + 1 ;} echo "\"><button>Next</button></a>";
                      
            }

        ?>
        

       
        <script>
            function alrt() {
                alert("Data sudah masuk, Tunggu Konfirmasi.");
            
            }
        </script>
</div>
</body>
</html>
