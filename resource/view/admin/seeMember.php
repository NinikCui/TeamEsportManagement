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
    <title></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="../../css/nav.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-image: url("../../img/BG.png");
            background-size: cover;
            background-attachment: fixed;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .table {
            width: 100%;
            max-width: 800px; /* Membatasi lebar maksimal tabel */
            margin: 0 auto;
            border-collapse: collapse;
            font-size: 16px;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .table th, .table td {
            padding: 12px;
            background-color: rgba(255, 255, 255, 0.2);
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .table th {
            background-color: rgba(255, 255, 255, 0.3);
        }

        .buttons {
            width: 100%;
            max-width: 800px; /* Menyamakan dengan lebar maksimal tabel */
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .buttons button {
            background-color: #fff;
            color: #3c0036;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Media Queries */
        @media screen and (max-width: 768px) {
            .container {
                padding: 15px;
            }
            
            .table {
                font-size: 14px;
                width: 90%;
            }
        }

        @media screen and (max-width: 600px) {
            .container {
                padding: 10px;
            }

            .table-wrapper {
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                margin: 0 auto;
            }

            .table {
                width: 100%;
                min-width: 300px;
                font-size: 13px;
                margin: 0 auto;
            }

            .table th, .table td {
                padding: 10px;
                min-width: 100px;
            }
        }

        @media screen and (max-width: 480px) {
            .container {
                padding: 8px;
            }
            
            .table {
                width: 85%;
                font-size: 12px;
            }
            
            .table th, .table td {
                padding: 6px;
                min-width: 70px;
            }
            
            .buttons {
                flex-direction: column;
                width: 85%;
                margin: 0 auto 15px auto;
            }
            
            .buttons button {
                width: 100%;
                margin: 5px 0;
                padding: 8px 15px;
            }
        }
    </style>
</head>
<body>
<nav class="navbar">
        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="logo">
            <img src="../../img/hiksrotIcon.png" alt="Hiksrot Logo">
            HIKSROT
        </div>
        <ul class="nav-section">
            <li><a href="proposal.php">Proposal</a></li>
            <li><a href="team.php"><u>Team</u></a></li>
            <li><a href="game.php">Game</a></li>
            <li><a href="event.php">Event</a></li>
            <li><a href="achievement.php">Achivement</a></li>
        </ul>
        <div class="photo-profile">
            <img src="../../img/fotoProfile.png" alt="Foto Profil">
            <h5>Hello, <?php  echo $_SESSION['active_user']->fname;?></h5>
            <div  class="btn-logout">
                <button  class="logout" onclick="confirmLogout()">Log Out</button>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
            const hamburger = document.querySelector('.hamburger');
            const navSection = document.querySelector('.nav-section');

            hamburger.addEventListener('click', function() {
                this.classList.toggle('active');
                navSection.classList.toggle('active');
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.navbar')) {
                    hamburger.classList.remove('active');
                    navSection.classList.remove('active');
                }
            });

            // Close menu when clicking a link
            document.querySelectorAll('.nav-section li a').forEach(link => {
                link.addEventListener('click', () => {
                    hamburger.classList.remove('active');
                    navSection.classList.remove('active');
                });
            });
        });
            function confirmLogout() {
            var result = confirm("Apakah Anda yakin ingin logout?");
            if (result) {
                window.location.href = "../logout.php"; 
            } 
        }
        </script>
    </nav>
    
    <div class="container">
    <table class="table">
            <thead>
            <tr>
                <th colspan="2"><?php echo  $namateam; ?></th>
                </tr>
                <tr>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $members = $tm->ReadDataTeamMember($idTeamMember,$pageStart,$maxRows);
                if (!empty($members)) {
                    foreach($members as $mem) {
                        echo "<tr>";
                        echo "<td>" . $mem["username"] . "</td>";
                        echo "<td>
                                <form method='POST' action='' style='display:inline;'>
                                    <input type='hidden' name='idteamDelete' value='" . $mem["idteam"] . "'>
                                    <input type='hidden' name='idmemberDelete' value='" . $mem["idmember"] . "'>
                                    <input type='hidden' name='namateamDelete' value='" . $namateam . "'>
                                    <button type='submit' name='action' value='delete' style='color: #FF474D; border: none; background: none; cursor: pointer; font-size: 18px;'><span>&#x1F5D1;</span> Delete</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr>";
                    echo "<td colspan='4' style='text-align: center;'>None</td>";
                    echo "</tr>";
                }
                
                ?>
            </tbody>
        </table>
        
    </div>
</body>
</html>