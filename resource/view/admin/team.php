<?php
require_once('../../../classes/member.php');
require_once('../../../classes/Team.php');
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
$t = new Team($conn);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $action = $_POST['action'];
    $uploadSuccess = true;
    $imgPath = '';
    
    if (isset($_FILES['teamImage']) && $_FILES['teamImage']['error'] == 0) {
        $allowed = ['jpg'];
        $filename = $_FILES['teamImage']['name'];
        $fileExt = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (!in_array($fileExt, $allowed)) {
            echo "<script>alert('Only JPG files are allowed!');</script>";
            $uploadSuccess = false;
        }
        
        if ($uploadSuccess) {
            if (!file_exists('../../img/teamImg')) {
                mkdir('../../img/teamImg', 0777, true);
            }
        }
    }

    if ($action == 'delete') {
        $idTeam = $_POST['idteam'];
        // Delete associated image if it exists
        $oldImagePath = "../../img/teamImg/{$idTeam}.jpg";
        if (file_exists($oldImagePath)) {
            unlink($oldImagePath);
        }
        $t->DeleteTeam($idTeam);
    }
    else if($action == "add") {
        $team = $_POST['teamName'];
        $game = $_POST['game'];
         $t->AddTeam($game, $team, $uploadSuccess);
    }
    else if ($action == "edit") {
        $idTeam = $_POST['idteam'];
        $game = $_POST['game'];
        $name = $_POST['teamName'];
        
        // Handle image update
        if (isset($_FILES['teamImage']) && $_FILES['teamImage']['error'] == 0) {
            $imagePath = "../../img/teamImg/{$idTeam}.jpg";
            
            if ($uploadSuccess) {
                // Backup gambar lama jika ada
                if (file_exists($imagePath)) {
                    $backupPath = $imagePath . '.bak';
                    copy($imagePath, $backupPath);
                }
                
                // Coba upload gambar baru
                if (move_uploaded_file($_FILES['teamImage']['tmp_name'], $imagePath)) {
                    // Jika berhasil, hapus backup dan clear cache dengan touch
                    if (file_exists($backupPath)) {
                        unlink($backupPath);
                    }
                    touch($imagePath); // Update timestamp file
                } else {
                    // Jika gagal, kembalikan gambar lama
                    if (file_exists($backupPath)) {
                        rename($backupPath, $imagePath);
                    }
                    echo "<script>alert('Failed to update image!');</script>";
                }
            }
        }
        
        $t->EditTeam($game, $name, $idTeam);
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
    <title>Team
    </title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link href="../../css/nav.css" rel="stylesheet">
    <style>
        body {
            background-image: url("../../img/BG.png");
            background-size: cover;
            background-attachment: fixed;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 15px;
        }

        .table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
            font-size: 16px;
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

        .table img {
            max-width: 100px;
            max-height: 100px;
            object-fit: contain;
        }

        .actions {
            display: flex;
            gap: 8px;
            justify-content: center;
            flex-wrap: wrap;
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
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 15px;
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

        /* Form Modal Styles */
        .frmNew {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow-y: auto;
        }

        .frm-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
        }

        .formNew-Group {
            margin-bottom: 15px;
        }

        .formNew-Group label {
            display: block;
            margin-bottom: 5px;
            color: black;
        }

        .formNew-Group input[type="file"] {
            width: 100%;
            max-width: calc(100% - 20px);
        }

        .formNew-Group small {
            color: #666;
            font-size: 12px;
            display: block;
            margin-top: 5px;
        }

        .formNew-input,
        .formNew-Group input,
        .formNew-Group textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .formNew-btnAdd {
            padding: 10px 20px;
            background-color: #3c0036;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: auto;
            min-width: 120px;
        }

        .formNew-btnAdd:hover {
            background-color: #55004d;
        }

        .formNew-btnAddContainer {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .formNew-Team {
            padding: 5px;
        }

        .image-upload-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .image-preview {
            width: 100px;
            height: 100px;
            border: 1px dashed #ccc;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 10px 0;
        }

        .image-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        /* Media Queries */
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
                margin: 10% auto;
            }
            
            .formNew-Group input,
            .formNew-Group textarea {
                font-size: 14px;
            }
        }

        @media screen and (max-width: 480px) {
            .container {
                width: 100%;
                padding: 5px;
            }
            
            .table {
                font-size: 12px;
            }
            
            .table img {
                max-width: 60px;
                max-height: 60px;
            }
            
            .actions {
                flex-direction: column;
                gap: 5px;
            }
            
            .image-upload-container {
                gap: 5px;
            }
            .frm-content {
                margin: 15% auto; /* Lebih banyak margin atas untuk layar kecil */
                padding: 10px; /* Padding lebih kecil */
                width: 90%; /* Kurangi ukuran untuk layar HP */
                font-size: 14px; /* Ukuran font lebih kecil */
            }
        }

        /* Untuk tampilan tabel pada perangkat mobile */
        @media screen and (max-width: 600px) {
            .table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
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
        <div class="logo">
            <img src="../../img/hiksrotIcon.png" alt="Hiksrot Logo">
            HIKSROT
        </div>
        <ul class="nav-section">
            <li><a href="proposal.php">Proposal</a></li>
            <li><a href="team.php"><u>Team</u></a></li>
            <li><a href="game.php">Game</a></li>
            <li><a href="event.php">Event</a></li>
            <li><a href="achievement.php">Achievement</a></li>
        </ul>
        <div class="photo-profile">
            <img src="../../img/fotoProfile.png" alt="Foto Profil">
            <h5>Hello, <?php echo $_SESSION['active_user']->fname; ?></h5>
            <div class="btn-logout">
                <button class="logout" onclick="confirmLogout()">Log Out</button>
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
        function previewImage(input) {
            const preview = document.getElementById('preview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                
                reader.readAsDataURL(input.files[0]);
            } else {
                // Jika tidak ada file baru dipilih dan ini adalah mode edit
                const idteam = document.getElementById('idteam').value;
                if (idteam && document.getElementById('actionButton').value === 'edit') {
                    preview.src = `../../img/teamImg/${idteam}.jpg?v=${new Date().getTime()}`;
                    preview.style.display = 'block';
                } else {
                    preview.src = '';
                    preview.style.display = 'none';
                }
            }
        }

        function openFrmEdit(idteam, gameName, teamName) {
            document.getElementById('formNew').style.display = "block";
            document.getElementById('teamName').value = teamName; 
            document.getElementById('game').value = gameName;
            document.getElementById('actionButton').value = "edit"; 
            document.getElementById('actionButtonText').innerText = "Update Team";
            document.getElementById('idteam').value = idteam;
            document.getElementById('teamImage').removeAttribute('required');
            
            // Reset preview dengan timestamp
            const preview = document.getElementById('preview');
            const timestamp = new Date().getTime();
            preview.src = `../../img/teamImg/${idteam}.jpg?v=${timestamp}`;
            preview.style.display = 'block';
            
            preview.onerror = function() {
                this.style.display = 'none';
            };
            preview.onload = function() {
                this.style.display = 'block';
            };
        }
        function refreshImage(idteam) {
            const images = document.querySelectorAll(`img[src*="${idteam}.jpg"]`);
            const timestamp = new Date().getTime();
            images.forEach(img => {
                img.src = `../../img/teamImg/${idteam}.jpg?v=${timestamp}`;
            });
        }

        function openFrmNew() {
            document.getElementById('formNew').style.display = "block";
            document.getElementById('teamName').value = ""; 
            document.getElementById('game').value = "";
            document.getElementById('actionButton').value = "add"; 
            document.getElementById('actionButtonText').innerText = "Add new"; 
            document.getElementById('idteam').value = "";
            document.getElementById('teamImage').setAttribute('required', '');
            
            // Reset preview when opening new form
            const preview = document.getElementById('preview');
            preview.src = '';
            preview.style.display = 'none';
        }

        function closeFrmNew() {
            document.getElementById('formNew').style.display = "none";
        }

        // Menutup modal jika user klik di luar modal
        window.onclick = function(event) {
            var modal = document.getElementById('formNew');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

    <div class="container">
        <a onclick="openFrmNew()" style="margin-bottom: 15px; padding: 10px 20px; background-color: #fff; color: #3c0036; text-decoration: none; border-radius: 5px; border: none; cursor: pointer; float: right;">+ New</a>

    <!-- Form modal untuk add/edit -->
        <div id="formNew" class="frmNew">
            <div class="frm-content">
                <span class="close" onclick="closeFrmNew()">&times;</span>
                <form method="POST" action="" enctype="multipart/form-data">
                    <h2><span id="actionButtonText">Add a new Team</span></h2>
                    <input type="hidden" id="idteam" name="idteam">
                    
                    <div class="formNew-Group">
                        <label for="teamName">Team Name</label>
                        <textarea id="teamName" name="teamName" placeholder="Enter Team Name" rows="4" required></textarea>
                    </div>
                    
                    <div class="formNew-Group">
                        <label for="game">Game</label>
                        <select id="game" name="game" required>
                            <option value="">--- Pilih Game ---</option>
                            <?php
                                $q = "select * from game";
                                $resGame = $conn->query($q);
                                if($resGame){
                                    while($rGame = $resGame->fetch_array()){
                                        echo("<option value='".$rGame['idgame']."'>".$rGame["name"]."</option>");
                                    }
                                }
                            ?>
                        </select>
                    </div>

                    <div class="formNew-Group">
                        <label for="teamImage">Team Image</label>
                        <div class="image-upload-container">
                            <input type="file" id="teamImage" name="teamImage" accept=".jpg" class="formNew-input" onchange="previewImage(this);">
                            <div id="imagePreview" class="image-preview">
                                <img id="preview" src="" alt="Preview" style="display: none;">
                            </div>
                        </div>
                        <small>*Only JPG files allowed</small>
                    </div>
                    <div class="formNew-btnAddContainer">
                        <button type="submit" id="actionButton" name="action" value="add" class="formNew-btnAdd">Add new</button>
                    </div>
                </form>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Id Team</th>
                    <th>Game Name</th>
                    <th>team Name</th>
                    <th>Member</th>
                    <th>Logo</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $teams =  $t->ReadDataTeam($pageStart, $maxRows);
                if (!empty($teams)) {
                    foreach ($teams as $team) {
                        echo "<tr>";
                        echo "<td>" . $team["idteam"] . "</td>";
                        echo "<td>" . $team["gameName"] . "</td>";
                        echo "<td>" . $team["teamName"] . "</td>";
                        $idTeamGambar = $team["idteam"];
                        echo "<td> <form method='POST' action='seeMember.php' style='display:inline;'>
                                    <input type='hidden' name='idteam' value='" . $team["idteam"] . "'>
                                    <input type='hidden' name='namateam' value='" . $team["teamName"] . "'>
                                    <button type='submit' name='detail' value='detail' style='color: yellow; border: none; background: none; cursor: pointer; font-size: 18px;'>📝 Detail</button>
                                </form></td>
                               <td>
                                    <img src=\"../../img/teamImg/$idTeamGambar.jpg?v=" . time() . "\">
                                </td>";
                        echo "<td>
                                
                                <button type='button' onclick='openFrmEdit(\"" . $team["idteam"] . "\", \"" . $team["gameName"] . "\", \"" . $team["teamName"] . "\")' style='color: #A0D683; border: none; background: none; cursor: pointer; font-size: 18px;'>✔ Update</button>
                                <form method='POST' action='' style='display:inline;'>
                                    <input type='hidden' name='idteam' value='" . $team["idteam"] . "'>
                                    <button type='submit' name='action' value='delete' style='color: #FF474D; border: none; background: none; cursor: pointer; font-size: 18px;'><span>&#x1F5D1;</span> Delete</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tar>";
                    echo "<td colspan='6' style='text-align: center;'>None</td>";
                    echo "</tar>";
                }
                
                $totalPages = $t->ReadPages($maxRows);
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
            <a href="<?php if($page <= 1){echo " # ";} else {echo "team.php?page=". $page - 1;} ?>"><button>Back</button></a>
        <!--    <?php //for($i = 1; $i <= $totalPages; $i++) :?>
                <a href="?page="<?php //echo($i);?>> <?php //  echo($i) ?> </a>
            <?php   //endfor;  ?> -->
            <a href="<?php if($page >= $totalPages){echo"#";} else{echo"team.php?page=".$page + 1 ;} ?>"><button>Next</button></a>

        </div>
    </div>
</body>
</html>
