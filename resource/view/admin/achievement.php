<?php
require_once('../../../classes/member.php');
session_start();
if (!isset($_SESSION['active_user'])) {
    header('Location: ../../../index.php');
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'esport');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $action = $_POST['action'];

    
    if ($action == 'delete') {
            $idAchi = $_POST['idachievement'];
            
            $stmt = $conn->prepare("delete from achievement where idachievement =". $idAchi);
            $stmt->execute();
            $stmt->close();
            $conn->close();
        
    }
    else if($action == "add"){
        $idteam = $_POST['idteam'];
        $namaAchi = $_POST['nameAchi'];
        $dateAchi = $_POST['date'];
       
        $desAchi = $_POST['descriptionAchi'];
        
        $stmt = $conn->prepare("INSERT INTO achievement (idachievement, idteam, name, date, description) VALUES ('', '$idteam', '$namaAchi', '$dateAchi', '$desAchi')");
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }
    else if ($action == "edit") {
        $idAchi = $_POST['idachievement'];
        $team = $_POST['idteam'];
        $nameAchi = $_POST['nameAchi'];
        $dateAchi = $_POST['date'];
        $descAchi = $_POST['descriptionAchi'];
        $stmt = $conn->prepare("UPDATE achievement SET idteam=?, name=?, date=?, description=? WHERE idachievement = ?");
        $stmt->bind_param("ssssi", $team, $nameAchi, $dateAchi, $descAchi, $idAchi);
        $stmt->execute();
        $stmt->close();
        $conn->close();
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
    <title>Achivement</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link href="../../css/nav.css" rel="stylesheet">
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
        .desc{
            cursor: pointer;
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
            <li><a href="event.php">Event</a></li>
            <li><a href="achievement.php"><u>Achievement</u></a></li>
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
        document.getElementById('name').value = ""; 
        document.getElementById('description').value = ""; 
        document.getElementById('actionButton').value = "add"; 
        document.getElementById('actionButtonText').innerText = "Add new"; 
        document.getElementById('idachievement').value = ""; 
        document.getElementById('cbteam').selectedIndex = 0; // Reset combo box
    }
        function openFrmEdit(idachievement, name, team, date, description) {
        document.getElementById('formNew').style.display = "block";
        document.getElementById('idachievement').value = idachievement; // ID achievement untuk edit
        document.getElementById('name').value = name;
        document.getElementById('cbteam').value = team;
        document.getElementById('date').value = date;
        document.getElementById('description').value = description;

        // Ubah tombol submit menjadi 'edit'
        document.getElementById('actionButton').value = "edit";
        document.getElementById('actionButtonText').innerText = "Update Achievement";
        
        // Set selected team in combo box
        var cbteam = document.getElementById('cbteam');
        for (var i = 0; i < cbteam.options.length; i++) {
            if (cbteam.options[i].text === teamName) {
                cbteam.selectedIndex = i;
                break;
            }
        }
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
        <a onclick="openFrmNew()" style="padding: 10px 20px; background-color: #fff; color: #3c0036; text-decoration: none; border-radius: 5px; border: none; cursor: pointer; float: right;">+ New</a>

        <div id="formNew" class="frmNew">
            <div class="frm-content">
                <span class="close" onclick="closeFrmNew()">&times;</span>
                <h2><span id="actionButtonText">Add a new Achievement</span></h2>
                <input type="hidden" id="idachievement" name="idachievement"> 
                <div class="formNew-Group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="nameAchi" placeholder="Enter achievement name" required>
                </div>

                <div class="formNew-Group">
                    <label for="team">Team</label>
                    <select id="cbteam" class="formNew-Team" name="idteam" required>
                        <option value="">--- SELECT TEAM ---</option>
                        <?php
                            $conn = new mysqli('localhost', 'root', '', 'esport');
                            $stmt = $conn->prepare("SELECT idteam, name FROM team;");
                            $stmt->execute();
                            $res = $stmt->get_result();
                            while($rteam = $res->fetch_array()){
                                echo "<option value='".$rteam["idteam"]."'>".$rteam["name"]."</option>";
                            }
                        ?>
                    </select>
                </div>

                <div class="formNew-Group">
                    <label for="date">Date</label>
                    <input type="date" id="date" name="date" required>
                </div>

                <div class="formNew-Group">
                    <label for="description">Description</label>
                    <textarea id="description" name="descriptionAchi" placeholder="Enter achievement description" rows="4" required></textarea>
                </div>
                <div class="formNew-btnAddContainer">
                    <button type="submit" id="actionButton" name='action' value='add' class="formNew-btnAdd">Add new</button>
                </div>
            </div>
        </div>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Id Achievement</th>
                <th>Team</th>
                <th>Name</th>
                <th>Date</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $stmt = $conn->prepare("SELECT a.idachievement, t.name as team, a.name, DATE_FORMAT(a.date,'%d/%m/%Y') as date, a.description  FROM achievement a
                                        INNER JOIN team t ON t.idteam = a.idteam ORDER BY a.idachievement ASC LIMIT ". $pageStart.", ". $maxRows);
                $stmt->execute();
                $res = $stmt->get_result();
                if($res->num_rows > 0 ){
                    while($categori = $res->fetch_array()){
                        echo "<tr>";
                        echo "<td>" . $categori["idachievement"] . "</td>";
                        echo "<td>" . $categori["team"] . "</td>";
                        echo "<td>" . $categori["name"] . "</td>";
                        echo "<td>" . $categori["date"] . "</td>";
                        echo "<td class='desc'>" . $categori["description"] . "</td>";
                        echo "<td>
                                <button type='button' onclick=\"openFrmEdit('".$categori["idachievement"]."', '".$categori["name"]."', '".$categori["team"]."', '".$categori["date"]."', '".$categori["description"]."')\" style='color: #A0D683; border: none; background: none; cursor: pointer; font-size: 18px;'>✔ Edit</button>
                                <form method='POST' action='' style='display:inline;'>
                                    <input type='hidden' name='idachievement' value='" . $categori["idachievement"] . "'>
                                    <button type='submit' name='action' value='delete' style='color: #FF474D; border: none; background: none; cursor: pointer; font-size: 18px;'><span>&#x1F5D1;</span> Delete</button>
                                </form>
                              </td>";
                        echo "</tr>"; 
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align: center;'>None</td></tr>";
                }
                $stmt->close();
                
                $q = "SELECT COUNT(*) as totalRows FROM achievement";
                $resCount = $conn->query($q);
                $rcount = $resCount->fetch_array();
                $totalRows = $rcount["totalRows"];
                $totalPages = ceil($totalRows / $maxRows);
            ?>
        </tbody>
    </table>

    <div>
        <?php 
            echo("Showing Data " . ($pageStart + 1) . " to " . min($pageStart + $maxRows, $totalRows));
        ?>
    </div>
    <div class="buttons">
        <a href="<?php echo ($page <= 1) ? "#" : "achievement.php?page=" . ($page - 1); ?>"><button>Back</button></a>
        <a href="<?php echo ($page >= $totalPages) ? "#" : "achievement.php?page=" . ($page + 1); ?>"><button>Next</button></a>
    </div>
</div>
</body>
</html>
