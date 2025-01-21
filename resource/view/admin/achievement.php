<?php
require_once('../../../classes/member.php');
require_once('../../../classes/Achievement.php');
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

$conn = new mysqli('localhost', 'root', '', 'fullstack');
$a = new Achievement($conn);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $action = $_POST['action'];

    
    if ($action == 'delete') {
        $idAchi = $_POST['idachievement'];
        $a->DeleteAchievement($idAchi);
    }
    else if($action == "add"){
        $idteam = $_POST['idteam'];
        $namaAchi = $_POST['nameAchi'];
        $dateAchi = $_POST['date'];
       
        $desAchi = $_POST['descriptionAchi'];
        
        $a->AddAchievement($idteam,$namaAchi,$dateAchi,$desAchi);
    }
    else if ($action == "edit") {
        $idAchi = $_POST['idachievement'];
        $team = $_POST['idteam'];
        $nameAchi = $_POST['nameAchi'];
        $dateAchi = $_POST['date'];
        $descAchi = $_POST['descriptionAchi'];
        $a->EditAchievement($team,$nameAchi,$dateAchi,$descAchi,$idAchi);
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
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }

        .table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
            font-size: 16px;
        }

        .table th, .table td {
            padding: 10px;
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
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .buttons button {
            background-color: #fff;
            color: #3c0036;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .buttons button:hover {
            background-color: #55004d;
            color: #fff;
        }

        .desc {
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
            width: 80%;
            max-width: 500px;
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

        .formNew-Group {
            margin-bottom: 15px;
            color: black;
        }

        .formNew-Group label {
            display: block;
            font-size: 16px;
            margin-bottom: 5px;
            color: black;
        }

        .formNew-Group input, .formNew-Group textarea {
            width: 100%;
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
        }

        .formNew-btnAdd:hover {
            background-color: #55004d;
        }

        .formNew-btnAddContainer {
            display: flex;
            justify-content: flex-end;
        }

        .formNew-Team {
            padding: 5px;
        }

        .search-form {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px; /* Spasi antara input dan button */
            margin-bottom: 20px; /* Jarak dari elemen lainnya */
            flex-wrap: wrap; /* Agar responsif pada layar kecil */
        }

        .search-input {
            padding: 10px 15px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            max-width: 300px; /* Batas maksimum lebar */
            transition: all 0.3s ease-in-out;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .search-input:focus {
            outline: none;
            border-color: #3c0036;
            box-shadow: 0 0 8px rgba(60, 0, 54, 0.3);
        }

        .search-button {
            padding: 10px 20px;
            background-color: #3c0036;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease-in-out;
        }

        .search-button:hover {
            background-color: #55004d;
            box-shadow: 0 4px 8px rgba(85, 0, 77, 0.2);
        }

        /* Media query untuk layar tablet (maksimal 768px) */
        @media screen and (max-width: 768px) {
            .container {
                width: 95%;
                padding: 10px;
            }
            
            .table {
                font-size: 14px;
            }
            
            .table th, .table td {
                padding: 8px;
            }
            
            .frm-content {
                width: 95%;
            }
            
            .formNew-Group label {
                font-size: 14px;
                padding-bottom: 10px;
            }
            
            .formNew-Group input,
            .formNew-Group textarea {
                font-size: 14px;
            }
            
            .buttons button {
                padding: 8px 15px;
            }
            .search-form {
                gap: 8px;
                margin-bottom: 15px;
            }

            .search-input {
                font-size: 14px;
                max-width: 250px;
            }

            .search-button {
                font-size: 14px;
                padding: 8px 15px;
            }
        }

        /* Media query untuk layar ponsel (maksimal 480px) */
        @media screen and (max-width: 480px) {
            .container {
                width: 100%;
                padding: 5px;
            }
            
            .table {
                font-size: 12px;
            }
            
            .table th, .table td {
                padding: 6px;
            }
            
            .actions {
                flex-direction: column;
                gap: 5px;
            }
            
            .frm-content {
                margin: 2% auto;
                padding: 15px;
            }
            
            .formNew-Group label {
                padding-bottom: 8px;
            }
            
            .formNew-Group input,
            .formNew-Group textarea {
                width: 100%;
                padding: 8px;
            }
            
            .formNew-btnAdd {
                width: 100%;
                margin-top: 10px;
            }
            .frm-content {
                margin: 15% auto; /* Lebih banyak margin atas untuk layar kecil */
                padding: 10px; /* Padding lebih kecil */
                width: 90%; /* Kurangi ukuran untuk layar HP */
                font-size: 14px; /* Ukuran font lebih kecil */
            }
            .search-form {
                flex-direction: column; /* Tumpuk elemen secara vertikal */
                align-items: stretch; /* Isi seluruh lebar */
                gap: 5px;
            }

            .search-input {
                width: 100%; /* Isi seluruh lebar */
                font-size: 14px;
            }

            .search-button {
                width: 100%; /* Isi seluruh lebar */
                font-size: 14px;
                padding: 10px;
            }
        }

        @media screen and (max-width: 600px) {
            .table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
            .frm-content {
                margin: 5% auto;
            }
            .buttons {
                display: flex;
                justify-content: flex-end;
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
    <script>
        function filterTeams() {
            const input = document.getElementById("search-team").value.toLowerCase();
            const rows = document.querySelectorAll("#achievement-table tbody tr");

            rows.forEach(row => {
                const teamName = row.querySelector(".team-name").textContent.toLowerCase();
                row.style.display = teamName.includes(input) ? "" : "none";
            });
        }
    </script>

<div class="container">
    <form method="POST" action="">
        <a onclick="openFrmNew()" style="padding: 10px 20px; background-color: #fff; color: #3c0036; text-decoration: none; border-radius: 5px; border: none; margin-bottom: 10px; cursor: pointer; float: right;">+ New</a>

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
                            $conn = new mysqli('localhost', 'root', '', 'fullstack');
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
     <!-- Search Form -->
     <form method="GET" action="">
        <input type="text" name="team_name" placeholder="Search by team name..." value="<?php echo isset($_GET['team_name']) ? $_GET['team_name'] : ''; ?>">
        <button type="submit">Search</button>
    </form>
    <!-- Table -->
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
                $teamNameFilter = isset($_GET['team_name']) ? $_GET['team_name'] : '';
                $achievements = $a->ReadDataAchievement($teamNameFilter,$pageStart,$maxRows);
                if(!empty($achievements)){
                    foreach($achievements as $achiee){
                        echo "<tr>";
                        echo "<td>" . $achiee["idachievement"] . "</td>";
                        echo "<td>" . $achiee["team"] . "</td>";
                        echo "<td>" . $achiee["name"] . "</td>";
                        echo "<td>" . $achiee["date"] . "</td>";
                        echo "<td class='desc'>" . $achiee["description"] . "</td>";
                        echo "<td>
                                <button type='button' onclick=\"openFrmEdit('".$achiee["idachievement"]."', '".$achiee["name"]."', '".$achiee["team"]."', '".$achiee["date"]."', '".$achiee["description"]."')\" style='color: #A0D683; border: none; background: none; cursor: pointer; font-size: 18px;'>✔ Edit</button>
                                <form method='POST' action='' style='display:inline;'>
                                    <input type='hidden' name='idachievement' value='" . $achiee["idachievement"] . "'>
                                    <button type='submit' name='action' value='delete' style='color: #FF474D; border: none; background: none; cursor: pointer; font-size: 18px;'><span>&#x1F5D1;</span> Delete</button>
                                </form>
                              </td>";
                        echo "</tr>"; 
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align: center;'>None</td></tr>";
                }
                
                
                $totalPages = $a->ReadPages($maxRows);
            ?>
        </tbody>
    </table>

    <div>
        <?php 
            echo("Showing Data " . $pageStart + 1 . " to  " . $pageStart + $maxRows);      
        ?>
    </div>
    <div class="buttons">
        <a href="<?php echo ($page <= 1) ? "#" : "achievement.php?page=" . ($page - 1); ?>"><button>Back</button></a>
        <a href="<?php echo ($page >= $totalPages) ? "#" : "achievement.php?page=" . ($page + 1); ?>"><button>Next</button></a>
    </div>
</div>
</body>
</html>