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
    <link href="../../css/nav.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
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
            flex-wrap: wrap;
        }

        .buttons button, .filter {
            background-color: #fff;
            color: #3c0036;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 10px;
        }

        /* Media Queries untuk Responsivitas */
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
            
            .buttons {
                justify-content: center;
            }
            
            .buttons button, .filter {
                padding: 8px 15px;
            }
        }

        @media screen and (max-width: 480px) {
            .container {
                padding: 5px;
            }
            
            .table {
                width: 85%;
                font-size: 12px;
            }
            
            .table th, .table td {
                padding: 6px;
                min-width: 70px;
            }
            
            .actions {
                flex-direction: row;
                gap: 15px;
            }
            
            .buttons button, .filter {
                width: 100%;
                margin: 5px 0;
                padding: 8px 15px;
                font-size: 14px;
            }

            .filter-section {
                width: 85%;
            }

            .filter-section select {
                padding: 6px;
                font-size: 14px;
            }

            /* Pagination buttons */
            .pagination {
                display: flex;
                justify-content: center;
                gap: 10px;
                margin-top: 15px;
            }

            .pagination button {
                padding: 6px 12px;
                font-size: 14px;
            }
        }

        /* Untuk tampilan tabel pada perangkat mobile */
        @media screen and (max-width: 600px) {
            .table {
                width: 100%;
                margin: 0;
                padding: 0;
                overflow-x: auto;
                white-space: nowrap;
            }

            .table th, .table td {
                padding: 6px;
                text-align: left;
            }
            
            .frm-content {
                margin: 10% auto;
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
            <li><a href="proposal.php"><u>Proposal</u></a></li>
            <li><a href="team.php">Team</a></li>
            <li><a href="game.php">Game</a></li>
            <li><a href="event.php">Event</a></li>
            <li><a href="achievement.php">Achivement</a></li>
        </ul>

        <div class="photo-profile">
            <img src="../../img/fotoProfile.png" alt="Foto Profil">
            <h5>Hello, <?php echo $_SESSION['active_user']->fname; ?></h5>
            <div class="btn-logout">
                <button class="logout" onclick="confirmLogout()">Log Out</button>
            </div>
        </div>
    </nav>

    <script>
        // Hamburger Menu Toggle
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

        // Logout confirmation
        function confirmLogout() {
            var result = confirm("Apakah Anda yakin ingin logout?");
            if (result) {
                window.location.href = "../logout.php";
            }
        }
    </script>

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
