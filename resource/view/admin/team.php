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
$totalPages = $t->ReadPages($maxRows);

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
        
        .action-buttons button {
            border: none;
            background: none;
            cursor: pointer;
            font-size: 18px;
            padding: 5px 10px;
        }

        .edit-btn {
            color: #A0D683;
        }

        .delete-btn {
            color: #FF474D;
        }

        .header-actions {
            margin-bottom: 20px;
            animation: slideUp 0.5s ease-out;

        }
        .detail-btn {
            color: #FFD700;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 18px;
            padding: 5px 10px;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .action-buttons form {
            margin: 0;
        }
        .detail-btn {
    color: #FFD700;
    border: none;
    background: none;
    cursor: pointer;
    font-size: 18px;
    padding: 5px 10px;
}

.image-upload-container {
    display: flex;
    gap: 20px;
    align-items: flex-start;
}

.image-preview {
    width: 100px;
    height: 100px;
    border: 1px dashed #ccc;
    display: flex;
    justify-content: center;
    align-items: center;
}

.image-preview img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
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
        <a class="logo">
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
        function confirmLogout() {
            var result = confirm("Apakah Anda yakin ingin logout?");
            if (result) {
                window.location.href = "../logout.php";
            }
        }
    </script>

<div class="container-user">
    <!-- Header Actions -->
    <div class="header-actions" style="display: flex; justify-content: flex-end; margin-bottom: 20px;">
        <button onclick="openFrmNew()" class="add-button" style="background: #4834d4; color: white; padding: 12px 24px; border: none; border-radius: 5px; cursor: pointer;">
            + New Team
        </button>
    </div>

    <!-- Table -->
    <div class="table-wrapper">
        <table class="team-table">
            <thead>
                <tr>
                    <th>Id Team</th>
                    <th>Game Name</th>
                    <th>Team Name</th>
                    <th>Member</th>
                    <th>Logo</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $teams = $t->ReadDataTeam($pageStart, $maxRows);
                if (!empty($teams)) {
                    foreach ($teams as $team) {
                        echo "<tr>";
                        echo "<td>" . $team["idteam"] . "</td>";
                        echo "<td class='game-name'>" . $team["gameName"] . "</td>";
                        echo "<td class='team-name'>" . $team["teamName"] . "</td>";
                        echo "<td>
                                <form method='POST' action='seeMember.php' style='display:inline;'>
                                    <input type='hidden' name='idteam' value='" . $team["idteam"] . "'>
                                    <input type='hidden' name='namateam' value='" . $team["teamName"] . "'>
                                    <button type='submit' name='detail' value='detail' class='detail-btn'>
                                        📝 Detail
                                    </button>
                                </form>
                            </td>";
                        echo "<td><img src='../../img/teamImg/{$team['idteam']}.jpg?v=" . time() . "' class='team-logo' alt='{$team['teamName']} Logo'></td>";
                        echo "<td class='action-buttons'>
                                <button type='button' 
                                        onclick='openFrmEdit(\"" . $team["idteam"] . "\", \"" . $team["gameName"] . "\", \"" . $team["teamName"] . "\")' 
                                        class='edit-btn'>
                                    ✔ Update
                                </button>
                                <form method='POST' action='' style='display:inline;'>
                                    <input type='hidden' name='idteam' value='" . $team["idteam"] . "'>
                                    <button type='submit' name='action' value='delete' class='delete-btn'>
                                        &#x1F5D1; Delete
                                    </button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='no-data'>No teams available</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination" aria-label="Page navigation">
        <div class="buttons">
            <a href="<?php echo ($page <= 1) ? '#' : "team.php?page=".($page-1); ?>">
                <button <?php echo ($page <= 1) ? 'disabled' : ''; ?>>Previous</button>
            </a>
            <a href="<?php echo ($page >= $totalPages) ? '#' : "team.php?page=".($page+1); ?>">
                <button <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>>Next</button>
            </a>
            <div class="page-info">
                <?php echo "Showing Data " . ($pageStart + 1) . " to " . ($pageStart + $maxRows); ?>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div id="formNew" class="frmNew">
        <div class="frm-content">
            <span class="close" onclick="closeFrmNew()">&times;</span>
            <h2><span id="actionButtonText">Add a new Team</span></h2>
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" id="idteam" name="idteam">
                
                <div class="formNew-Group">
                    <label for="teamName">Team Name</label>
                    <textarea id="teamName" name="teamName" placeholder="Enter Team Name" rows="4" required></textarea>
                </div>
                
                <div class="formNew-Group">
                    <label for="game">Game</label>
                    <select id="game" name="game" required>
                        <option value="">--- Select Game ---</option>
                        <?php
                        $q = "select * from game ORDER BY NAME ASC";
                        $resGame = $conn->query($q);
                        if($resGame) {
                            while($rGame = $resGame->fetch_array()) {
                                echo "<option value='{$rGame['idgame']}'>{$rGame['name']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="formNew-Group">
                    <label for="teamImage">Team Logo</label>
                    <div class="image-upload-container">
                        <input type="file" id="teamImage" name="teamImage" accept=".jpg" class="formNew-input" onchange="previewImage(this);">
                        <div id="imagePreview" class="image-preview">
                            <img id="preview" src="" alt="Preview" style="display: none;">
                        </div>
                    </div>
                    <small>*Only JPG files allowed</small>
                </div>

                <div class="formNew-btnAddContainer">
                    <button type="submit" id="actionButton" name="action" value="add" class="formNew-btnAdd">
                        Add new
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
