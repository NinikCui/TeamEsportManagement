<?php 
require_once('../../../classes/member.php');
require_once('../../../classes/EventTeams.php');
session_start();
if (!isset($_SESSION['active_user'])) {
    header('Location: ../../../index.php');
    exit;
}else{
    if($_SESSION['active_user']->profile != "admin"){
        header('Location: ProjectFSP/notfound.php');
        exit;
    }
}

if (isset($_SESSION['idEventDetail'])) {
    $idEventDetail = $_SESSION['idEventDetail'];
    $namaEventDetail = $_SESSION['namaEventDetail'];
}
if (isset($_POST['namaCate'])) {

    $cate = "Detail ". $_POST['namaCate'];
    $idEventDetail = $_POST['idevent'];
    $namaEventDetail = $_POST['namaEvent'];
    $_SESSION['idEventDetail'] = $idEventDetail;
    $_SESSION['namaEventDetail'] = $namaEventDetail;
}


$conn = new mysqli('localhost', 'root', '', 'esport');
$et = new EventTeams($conn);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['action'])){

        $action = $_POST['action'];
        if ($action == 'delete') {
            $idEvent = $_POST['idevent'];
            $idTeam = $_POST['idteam'];
            $et->DeleteEventTeam($idEvent,$idTeam);
        }
        else if($action == "add"){
            $idteam = $_POST['idteam'];
            $idevent = $_SESSION['idEventDetail']; // Mengambil idevent dari session
            
            $notif = $et->AddEventTeam($idevent, $idteam);
            
            if ($notif != "" ) {
                echo "<script>
                        alert('$notif');
                      </script>";
            } 
            else{
                echo "<script>
                        alert('Terjadi kesalahan saat menambahkan team.');
                      </script>";
            }
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
    <title>Detail Event</title>
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
        .frmNew {
            display: none; 
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .frm-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border-radius: 10px;
            width: 30%;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .formNew-Group{
            margin-bottom: 15px;
            color: black;
        }

        .formNew-Group label {
            display: block;
            font-size: 16px;
            margin-bottom: 5px;
            color: black;
            padding-bottom: 20px;
        }

        .formNew-Group input, .formNew-Group textarea {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        textarea {
            resize: vertical;
        }
        .formNew-btnAdd {
            padding: 10px 20px;
            background-color: #3c0036;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            float: right;
        }

        .formNew-btnAdd:hover {
            background-color: #55004d;
        }
        .formNew-btnAddContainer {
            display: flex;
        }
        .formNew-Team{
            padding: 5px;
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
            <li><a href="team.php">Team</a></li>
            <li><a href="game.php">Game</a></li>
            <li><a href="event.php"><u>Event</u></a></li>
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
    <script>
        function openFrmNew() {
            document.getElementById('formNew').style.display = "block";
            document.getElementById('cbteam').value = ""; 
            document.getElementById('eventName').value = ""; 
            document.getElementById('date').value = ""; 
            document.getElementById('actionButton').value = "add"; 
            document.getElementById('actionButtonText').innerText = "Add new"; 
            document.getElementById('idevent').value = ""; 
        }
        function openFrmEdit(idevent, name, date) {
        document.getElementById('formNew').style.display = "block"; 
        document.getElementById('idevent').value = idevent; 
        document.getElementById('name').value = name; 
        document.getElementById('date').value = date; 
        document.getElementById('actionButton').value = "edit"; 
        document.getElementById('actionButtonText').innerText = "Update Event"; 
        }
        function closeFrmNew() {
            document.getElementById('formNew').style.display = "none";
        }

        window.onclick = function(event) {
            var frmNew = document.getElementById('formNew');
            if (event.target == frmNew) {
                frmNew.style.display = "none";
            }
        }
    </script>
    <div class="container">
        <form method="POST" action="">
            <a onclick="openFrmNew()" style="margin-bottom: 15px; padding: 10px 20px; background-color: #fff; color: #3c0036; text-decoration: none; border-radius: 5px; border: none; cursor: pointer; float: right;">+ New</a>

            <div id="formNew" class="frmNew">
                <div class="frm-content">
                    <span class="close" onclick="closeFrmNew()">&times;</span>
                    <form method="POST" action="">
                        <h2><span id="actionButtonText">Add a new Event</span></h2>
                        <input type="hidden" id="idevent" name="idevent"> <!-- Hidden input untuk idevent saat update -->
                        <div class="formNew-Group">
                        <label for="team">Team</label>
                        <select id="cbteam" class="formNew-Team" name="idteam">
                            <option value="">--- SELECT TEAM ---</option>
                            <?php
                                $conn = new mysqli('localhost', 'root', '', 'esport');
                                $stmt = $conn->prepare("SELECT idteam, name FROM team;");
                                $stmt->execute();
                                $res = $stmt->get_result();
                                while($rteam = $res->fetch_array()){
                                    echo "<option  value='".$rteam["idteam"]."'>".$rteam["name"]."</option>";
                                }
                            ?>
                        </select>
                    </div>
                        <div class="formNew-btnAddContainer">
                            <button type="submit" id="actionButton" name="action" value="add" class="formNew-btnAdd">Add new</button>
                        </div>
                    </form>
                </div>
            </div>
        </form>
        <table class="table">
            
            <thead>
                <tr>
                <th colspan="2"><?php echo  $namaEventDetail; ?></th>
                </tr>
                <tr>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $teams = $et->ReadDataEventTeam($idEventDetail,$pageStart,$maxRows);
                if (!empty($teams)) {
                    foreach($teams as $team) {
                        echo "<tr>";
                        echo "<td>" . $team["name"] . "</td>";
                        echo "<td>
                                <form method='POST' action='' style='display:inline;'>
                                    <input type='hidden' name='idevent' value='" . $team["idevent"] . "'>
                                    <input type='hidden' name='idteam' value='" . $team["idteam"] . "'>
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