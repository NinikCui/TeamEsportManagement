<?php 
require_once('../../../classes/member.php');
session_start();
if (!isset($_SESSION['active_user'])) {
    header('Location: ../../../index.php');
    exit;
}
if (isset($_POST['idteam'])) {
    $idTeamMember = $_POST['idteam'];
    $namateam = $_POST['namateam'];
}
$conn = new mysqli('localhost', 'root', '', 'esport');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idteamDelete'])) {
    $idTeamMember = $_POST['idteamDelete'];
    $namateam = $_POST['namateamDelete'];
    if(isset($_POST['action'])){

        $action = $_POST['action'];
        if ($action == 'delete') {
        
            $idTeam = $_POST['idteamDelete'];
            $idMember = $_POST['idmemberDelete'];
            $conn = new mysqli('localhost', 'root', '', 'esport');
            $stmt = $conn->prepare("delete from team_members where idteam = $idTeam and idmember =$idMember;");
            $stmt->execute();
            $stmt-> close();
    
            $stmt = $conn->prepare("delete from join_proposal where idmember = $idMember and idteam = $idTeam and status ='approved';");
            $stmt->execute();
            $stmt->close();
            
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
                $kon = new mysqli('localhost', 'root', '', 'esport');
                $stmt = $kon->prepare("select tm.* , m.username from team_members tm inner join member m on m.idmember = tm.idmember where idteam = $idTeamMember limit ". $pageStart.", ". $maxRows);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($res->num_rows > 0) {
                    while ($categori = $res->fetch_array()) {
                        echo "<tr>";
                        echo "<td>" . $categori["username"] . "</td>";
                        echo "<td>
                                <form method='POST' action='' style='display:inline;'>
                                    <input type='hidden' name='idteamDelete' value='" . $categori["idteam"] . "'>
                                    <input type='hidden' name='idmemberDelete' value='" . $categori["idmember"] . "'>
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
                $stmt->close();
                $q = " select count(*) as totalRows from event";
                $resCount = $conn->query($q);
                $rcount = $resCount->fetch_array();
                $totalRows = $rcount["totalRows"];
                $totalPages = ceil($totalRows / $maxRows);
                ?>
            </tbody>
        </table>
        <div>
            <?php 
                echo("Showing Data " . $pageStart + 1 . " to  " . $pageStart + $maxRows);
            ?>
        </div>
        <!-- Navigation Buttons -->
        <div class="buttons">
            <a href="<?php if($page <= 1){echo " # ";} else {echo "seeMember.php?page=". $page - 1;} ?>"><button>Back</button></a>
        <!--    <?php //for($i = 1; $i <= $totalPages; $i++) :?>
                <a href="?page="<?php //echo($i);?>> <?php //  echo($i) ?> </a>
            <?php   //endfor;  ?> -->
            <a href="<?php if($page >= $totalPages){echo"#";} else{echo"seeMember.php?page=".$page + 1 ;} ?>"><button>Next</button></a>

        </div>
    </div>
</body>
</html>