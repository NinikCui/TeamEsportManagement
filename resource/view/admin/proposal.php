<?php
require_once('../../../classes/member.php');
require_once('../../../classes/Join_Proposal.php');
session_start();
if (!isset($_SESSION['active_user'])) {
    header('Location: ../../../index.php');
    exit();
}else{
    if($_SESSION['active_user']->profile != "admin"){
        header('Location: ProjectFSP/utilities/404.php');
        exit;
    }
}

$conn = new mysqli('localhost', 'root', '', 'fullstack');

$status = isset($_GET['status']) ? $_GET['status'] : 'waiting'; 

$maxRows = 5;
$page = (isset($_GET["page"]) && is_numeric($_GET["page"])) ? ($_GET["page"]) : 1;
$pageStart = ($page - 1) * $maxRows;
$jp = new Join_Proposal($conn);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idProposal = $_POST['id_proposal'];
    $action = $_POST['action']; 
    $id_user = $_POST['id_user'];
    $id_team = $_POST['id_team'];
    
    
    $jp ->UpdateProposal($action,$id_user,$id_team,$idProposal);

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proposal</title>
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
        .action-buttons {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.approve-btn {
    color: #A0D683;
    border: none;
    background: none;
    cursor: pointer;
    font-size: 18px;
}

.reject-btn {
    color: #FF474D;
    border: none;
    background: none;
    cursor: pointer;
    font-size: 18px;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 14px;
}

.status-waiting {
    background: #FFD700;
    color: #000;
}

.status-approved {
    background: #A0D683;
    color: #fff;
}

.status-rejected {
    background: #FF474D;
    color: #fff;
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
                <li><a href="proposal.php" style="color:#4834D4;"><b>Proposal</b></a></li>
            

            <div class="sec-hov">
                <li><a href="team.php">Team</a></li>
            </div>

            <div class="sec-hov">
                <li><a href="game.php">Game</a></li>
            </div>

            <div class="sec-hov">
                <li><a href="event.php">Event</a></li>
            </div>
            <div class="sec-hov">

                <li><a href="achievement.php"  >Achivement</a></li>
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

        // Logout confirmation
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
        <form method="GET" action="proposal.php" style="display: flex; gap: 10px; align-items: center;">
            <label for="status" style="color: #333;">Filter by status:</label>
            <select name="status" id="status" style="padding: 10px; border: 1px solid #eee; border-radius: 5px;">
                <option value="waiting" <?php if($status == 'waiting') echo 'selected'; ?>>Waiting</option>
                <option value="approved" <?php if($status == 'approved') echo 'selected'; ?>>Approved</option>
                <option value="rejected" <?php if($status == 'rejected') echo 'selected'; ?>>Rejected</option>
            </select>
            <button type="submit" style="background: #4834d4; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
                Filter
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="table-wrapper">
        <table class="team-table">
            <thead>
                <tr>
                    <th>Id Proposal</th>
                    <th>Username</th>
                    <th>Team</th>
                    <th>Game</th>
                    <?php if ($status == 'waiting'): ?>
                        <th>Action</th> 
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $statusFilter = isset($_GET['status']) ? $_GET['status'] : 'waiting';
                $proposals = $jp->getProposalsByStatus($statusFilter, $pageStart, $maxRows);

                if (!empty($proposals)) {
                    foreach ($proposals as $proposal) {
                        echo $jp->renderProposalRow($proposal, $statusFilter === 'waiting');
                    }
                } else {
                    echo "<tr><td colspan='5' class='no-data'>No proposals available</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
                <?php $totalPages = $jp->totPages($status,$maxRows);?>
    <!-- Pagination -->
    <div class="pagination" aria-label="Page navigation">
        <div class="buttons">
            <a href="<?php echo ($page <= 1) ? '#' : "proposal.php?page=".($page-1)."&status=".$status; ?>">
                <button <?php echo ($page <= 1) ? 'disabled' : ''; ?>>Previous</button>
            </a>
            <a href="<?php echo ($page >= $totalPages) ? '#' : "proposal.php?page=".($page+1)."&status=".$status; ?>">
                <button <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>>Next</button>
            </a>
            <div class="page-info">
                <?php echo "Showing Data " . ($pageStart + 1) . " to " . ($pageStart + $maxRows); ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
