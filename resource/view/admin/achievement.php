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
$totalPages = $a->ReadPages($maxRows);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Achivement</title>
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

            <div class="sec-hov">
                <li><a href="team.php">Team</a></li>
            </div>

            <div class="sec-hov">
                <li><a href="game.php">Game</a></li>
            </div>

            <div class="sec-hov">
                <li><a href="event.php">Event</a></li>
            </div>

            <li><a href="achievement.php"  style="color:#4834D4;"><b>Achivement</b></a></li>
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
        function confirmLogout() {
            var result = confirm("Apakah Anda yakin ingin logout?");
            if (result) {
                window.location.href = "../logout.php";
            }
        }
    
        function filterTeams() {
            const input = document.getElementById("search-team").value.toLowerCase();
            const rows = document.querySelectorAll("#achievement-table tbody tr");

            rows.forEach(row => {
                const teamName = row.querySelector(".team-name").textContent.toLowerCase();
                row.style.display = teamName.includes(input) ? "" : "none";
            });
        }
    </script>

<div class="container-user">
    <!-- Header Actions -->
    <div class="header-actions" style="display: flex; justify-content: space-between; margin-bottom: 20px;">
        <!-- Button New -->
        <button onclick="openFrmNew()" class="add-button" style="background: #4834d4; color: white; padding: 12px 24px; border: none; border-radius: 5px; cursor: pointer;">
            + New Achievement
        </button>

        <!-- Search Form -->
        <form method="GET" action="" style="display: flex; gap: 10px;">
            <input type="text" 
                   name="team_name" 
                   placeholder="Search by team name..." 
                   value="<?php echo isset($_GET['team_name']) ? $_GET['team_name'] : ''; ?>"
                   style="padding: 10px; border: 1px solid #eee; border-radius: 5px;">
            <button type="submit" 
                    style="background: #4834d4; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
                Search
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="table-wrapper">
        <table class="team-table">
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
                $achievements = $a->ReadDataAchievement($teamNameFilter, $pageStart, $maxRows);
                if(!empty($achievements)) {
                    foreach($achievements as $achiee) {
                        echo "<tr>";
                        echo "<td>{$achiee['idachievement']}</td>";
                        echo "<td class='team-name'>{$achiee['team']}</td>";
                        echo "<td>{$achiee['name']}</td>";
                        echo "<td>{$achiee['date']}</td>";
                        echo "<td>{$achiee['description']}</td>";
                        echo "<td class='action-buttons'>
                                <button type='button' 
                                        onclick=\"openFrmEdit('{$achiee['idachievement']}', '{$achiee['name']}', '{$achiee['team']}', '{$achiee['date']}', '{$achiee['description']}')\"
                                        class='edit-btn'>
                                    ✔ Edit
                                </button>
                                <form method='POST' action='' style='display:inline;'>
                                    <input type='hidden' name='idachievement' value='{$achiee['idachievement']}'>
                                    <button type='submit' name='action' value='delete' class='delete-btn'>
                                        &#x1F5D1; Delete
                                    </button>
                                </form>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='no-data'>No achievements found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination" aria-label="Page navigation">
        <div class="buttons">
            <a href="<?php echo ($page <= 1) ? '#' : "achievement.php?page=".($page-1); ?>" >
                <button <?php echo ($page <= 1) ? 'disabled' : ''; ?>>Previous</button>
            </a>
            <a href="<?php echo ($page >= $totalPages) ? '#' : "achievement.php?page=".($page+1); ?>">
                <button <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>>Next</button>
            </a>
            <div class="page-info">
                <?php echo("Showing Data " . ($pageStart + 1) . " to " . ($pageStart + $maxRows)); ?>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div id="formNew" class="frmNew">
        <div class="frm-content">
            <span class="close" onclick="closeFrmNew()">&times;</span>
            <h2><span id="actionButtonText">Add a new Achievement</span></h2>
            <form method="POST" action="">
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
                            $stmt = $conn->prepare("SELECT idteam, name FROM team;");
                            $stmt->execute();
                            $res = $stmt->get_result();
                            while($rteam = $res->fetch_array()) {
                                echo "<option value='{$rteam["idteam"]}'>{$rteam["name"]}</option>";
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
            </form>
        </div>
    </div>
</div>
    <hr class="garis-abu">
    <footer>
        <div class="footer-content">
            <div class="footer-logo">
                <img src="../../img/hiksrotIcon.png" >
                <h3>HIKSROT</h3>
            </div>
            <div class="contact-info">
                <p>📍 Universitas Surabaya</p>
                <p>📞 +62 896725960</p>
            </div>
        </div>
    </footer>
</body>
</html>