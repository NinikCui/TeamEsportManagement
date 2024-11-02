<?php
require_once('../../../classes/member.php');
require_once('../../../classes/Join_Proposal.php');
session_start();
if (!isset($_SESSION['active_user'])) {
    header('Location: ../login.php');
    exit;
}

$idmember=  $_SESSION['active_user']->idmember;
$cekTeam = false;
$idTeamUser = "";
$namaTeamUser ="";
$conn = new mysqli('localhost', 'root', '', 'esport');

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
            <li><a href="teamUser.php"><u>Team</u></a></li>
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
        <?php 
            if($cekTeam){
                echo "<h1>Selamat Anda Telah Bergabung Dalam Team $namaTeamUser</h1>";
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
