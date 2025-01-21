<?php
require_once('../../../classes/member.php');
require_once('../../../classes/Event.php');
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
$e = new Event($conn);
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $action = $_POST['action'];

    if ($action == 'delete') {
        $idEvent = $_POST['idevent'];
        $e->DeleteEvent($idEvent);
        
    }
    else if($action == "add"){
        $nameEvent = $_POST['name'];
        $dateEvent = $_POST['date'];
        $e->AddEvent($nameEvent,$dateEvent);
    }
    else if ($action == "edit") {
        $idEvent = $_POST['idevent'];
        $nameEvent = $_POST['name'];
        $dateEvent = $_POST['date'];
        $e->EditEvent($nameEvent, $dateEvent,$idEvent);
    }
}
$maxRows = 5;
$page = (isset($_GET["page"]) && is_numeric($_GET["page"])) ? ($_GET["page"]) :1;
$pageStart = ($page - 1) * $maxRows;
$totalPages = $e->ReadPages($maxRows);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event</title>
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

            <div class="sec-hov">
                <li><a href="team.php">Team</a></li>
            </div>

            <div class="sec-hov">
                <li><a href="game.php">Game</a></li>
            </div>

            
                <li><a href="event.php"  style="color:#4834D4;"><b>Event</b></a></li>
            
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


        function openFrmNew() {
            document.getElementById('formNew').style.display = "block";
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
        <button onclick="openFrmNew()" 
                style="background: #4834d4; color: white; padding: 12px 24px; border: none; border-radius: 5px; cursor: pointer;">
            + New Event
        </button>
    </div>

    <!-- Table -->
    <div class="table-wrapper">
        <table class="team-table">
            <thead>
                <tr>
                    <th>Id Event</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $events = $e->ReadDataEvent($pageStart,$maxRows);
                if (!empty($events)) {
                    foreach($events as $eve) {
                        echo "<tr>";
                        echo "<td>" . $eve["idevent"] . "</td>";
                        echo "<td>" . $eve["name"] . "</td>";
                        echo "<td>" . $eve["date"] . "</td>";
                        echo "<td class='action-buttons'>
                                <button type='button' class='edit-btn' onclick='openFrmEdit(" . $eve["idevent"] . ", \"" . $eve["name"] . "\", \"" . $eve["date"] . "\")'>
                                    ✔ Update
                                </button>
                                <form method='POST' action='seeDetail.php' style='display:inline;'>
                                    <input type='hidden' name='namaCate' value='Event'>
                                    <input type='hidden' name='idevent' value='" . $eve["idevent"] . "'>
                                    <input type='hidden' name='namaEvent' value='" . $eve["name"] . "'>
                                    <button type='submit' name='detail' value='detail' class='detail-btn'>
                                        📝 Detail
                                    </button>
                                </form>
                                <form method='POST' action='' style='display:inline;'>
                                    <input type='hidden' name='idevent' value='" . $eve["idevent"] . "'>
                                    <button type='submit' name='action' value='delete' class='delete-btn'>
                                        &#x1F5D1; Delete
                                    </button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='no-data'>No events available</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination" aria-label="Page navigation">
        <div class="buttons">
            <a href="<?php echo ($page <= 1) ? '#' : "event.php?page=" . ($page-1); ?>">
                <button <?php echo ($page <= 1) ? 'disabled' : ''; ?>>Previous</button>
            </a>
            <a href="<?php echo ($page >= $totalPages) ? '#' : "event.php?page=" . ($page+1); ?>">
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
            <h2><span id="actionButtonText">Add a new Event</span></h2>
            <form method="POST" action="">
                <input type="hidden" id="idevent" name="idevent">
                <div class="formNew-Group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter Event Name" required>
                </div>
                <div class="formNew-Group">
                    <label for="date">Date</label>
                    <input type="date" id="date" name="date" required>
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
