<?php 
require_once('../../../classes/member.php');
require_once('../../../classes/TeamMember.php');
session_start();
if (!isset($_SESSION['active_user'])) {
    header('Location: ../../../index.php');
    exit;
}else{
    if($_SESSION['active_user']->profile != "admin"){
        header('Location: ProjectFSP/utilities/404.php');
        exit;
    }
}

if (isset($_POST['idteam'])) {
    $idTeamMember = $_POST['idteam'];
    $namateam = $_POST['namateam'];
}
$conn = new mysqli('localhost', 'root', '', 'fullstack');
$tm = new TeamMember($conn);
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idteamDelete'])) {
    $idTeamMember = $_POST['idteamDelete'];
    $namateam = $_POST['namateamDelete'];
    if(isset($_POST['action'])){
        $action = $_POST['action'];
        if ($action == 'delete') {
            $idTeam = $_POST['idteamDelete'];
            $idMember = $_POST['idmemberDelete'];
            $tm->DeleteTeamMember($idTeam ,$idMember);
        }
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
    <title>See Member</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link href="../../css/menu/navMenu.css" rel="stylesheet">
    <link href="../../css/menu/bodyUser.css" rel="stylesheet">    
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
            font-size: 24px;
            color: #4834d4;
            font-weight: 700;
            padding: 20px !important;
            text-align: center;
        }

        .member-name {
            font-weight: 500;
            color: #333;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
        }

        .delete-btn {
            color: #FF474D;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 18px;
            padding: 5px 10px;
        }

        .delete-btn:hover {
            opacity: 0.8;
        }

        .no-data {
            padding: 20px;
            text-align: center;
            color: #666;
            font-style: italic;
        }
        .formNew-Team {
            width: 100%;
            padding: 10px;
            border: 1px solid #eee;
            border-radius: 5px;
        }

        .formNew-btnAddContainer {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
        }

        /* Form Modal Styling */
        .frmNew {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }

        .frm-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
        }

        .formNew-Group {
            margin-bottom: 15px;
        }

        .formNew-Group label {
            display: block;
            margin-bottom: 5px;
        }

        .formNew-Group input,
        .formNew-Group select,
        .formNew-Group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .formNew-btnAdd {
            background: #4834d4;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .close{
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
        <a  class="logo">
            <img src="../../img/hiksrotIcon.png" alt="HIKSROT">
            HIKSROT
        </a>
        <ul class="nav-section">
            <div class="sec-hov">
                <li><a href="proposal.php">Proposal</a></li>
            </div>

            
                <li><a href="team.php" style="color:#4834D4;"><b>Team</b></a></li>
            

            <div class="sec-hov">
                <li><a href="game.php">Game</a></li>
            </div>

            <div class="sec-hov">
                <li><a href="event.php"  >Event</a></li>
            </div>
            <div class="sec-hov">
                <li><a href="achievement.php" >Achivement</a></li>
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
    <!-- Table -->
    <div class="table-wrapper">
        <table class="team-table">
            <thead>
                <tr>
                    <th colspan="2" class="team-header">
                        <?php echo $namateam; ?> Members
                    </th>
                </tr>
                <tr>
                    <th>Member Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $members = $tm->ReadDataTeamMember($idTeamMember, $pageStart, $maxRows);
                if (!empty($members)) {
                    foreach($members as $mem) {
                        echo "<tr>";
                        echo "<td class='member-name'>" . $mem["username"] . "</td>";
                        echo "<td class='action-buttons'>
                                <form method='POST' action=''>
                                    <input type='hidden' name='idteamDelete' value='" . $mem["idteam"] . "'>
                                    <input type='hidden' name='idmemberDelete' value='" . $mem["idmember"] . "'>
                                    <input type='hidden' name='namateamDelete' value='" . $namateam . "'>
                                    <button type='submit' name='action' value='delete' class='delete-btn'>
                                        &#x1F5D1; Delete
                                    </button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2' class='no-data'>No members in this team</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div> 
</body>
</html>