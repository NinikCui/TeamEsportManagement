<?php
require_once('../../../classes/member.php');
require_once('../../../classes/Join_Proposal.php');
session_start();
if (!isset($_SESSION['active_user'])) {
    header('Location: ../../../index.php');
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'esport');

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
    <link href="../../css/nav.css" rel="stylesheet">
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
        .filter{
            background-color: #fff;
            color: #3c0036;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 20px;
            margin-bottom: 10px;
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
            <li><a href="proposal.php"><u>Proposal</u></a></li>
            <li><a href="team.php">Team</a></li>
            <li><a href="game.php">Game</a></li>
            <li><a href="event.php">Event</a></li>
            <li><a href="achievement.php">Achivement</a></li>
        </ul>
        <div class="photo-profile">
            <img src="../../img/fotoProfile.png" alt="Foto Profil">
            <h5>Hello, 
                <?php echo $_SESSION['active_user']->fname;
                ?>
            </h5>
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
        <!-- Form Filter -->
        <form method="GET" action="proposal.php">
            <label for="status">Filter by status: </label>
            <select name="status" id="status" class="filter">
                <option value="waiting" <?php if($status == 'waiting') echo 'selected'; ?>>Waiting</option>
                <option value="approved" <?php if($status == 'approved') echo 'selected'; ?>>Approved</option>
                <option value="rejected" <?php if($status == 'rejected') echo 'selected'; ?>>Rejected</option>
            </select>
            <button type="submit" class="filter">Filter</button><br>
        </form>
        <!-- Table Data -->
        <table class="table">
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

                    // Tampilkan data
                    if (!empty($proposals)) {
                        foreach ($proposals as $proposal) {
                            echo $jp->renderProposalRow($proposal, $statusFilter === 'waiting');
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align: center;'>None</td></tr>";
                    }

                  
                    $totalPages = $jp->totPages($status,$maxRows);
                ?>
            </tbody>
        </table>

        <div>
            <?php 
                echo("Showing Data " . $pageStart + 1 . " to  " . $pageStart + $maxRows);
            ?>
        </div>
        <div class="buttons">
            <a href="<?php if($page <= 1){echo " # ";} else {echo "proposal.php?page=". $page - 1 . "&status=" .$status;} ?>"><button>Back</button></a>
            <a href="<?php if($page >= $totalPages){echo"#";} else{echo"proposal.php?page=".$page + 1 . "&status=" .$status;} ?>"><button>Next</button></a>
        </div>
    </div>
</body>
</html>
