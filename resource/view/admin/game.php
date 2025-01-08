<?php
require_once('../../../classes/member.php');
require_once('../../../classes/Game.php');
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
$g = new Game($conn);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $action = $_POST['action'];

    
    if ($action == 'delete') {
        $idGame = $_POST['idgame'];
        $g ->DeleteGame($idGame);
        
    }
    else if($action == "add"){
        $game = $_POST['gameName'];
        $description = $_POST['desc'];
        $g->AddGame($game,$description);
    }
    else if ($action == "edit") {
        $idGame = $_POST['idgame'];
        $game = $_POST['gameName'];
        $description = $_POST['desc'];
        $g->EditGame($idGame,$game,$description);
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
    <title>Game</title>
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
            color: black;
        }

        .formNew-Group label {
            display: block;
            font-size: 16px;
            margin-bottom: 5px;
            color: black;
            padding-bottom: 15px;
        }

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
            }
            
            .formNew-Group label {
                font-size: 14px;
                padding-bottom: 10px;
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
            
            .table th, .table td {
                padding: 6px;
            }
            
            .actions {
                flex-direction: column;
                gap: 5px;
            }
            
            .frm-content {
                margin: 2% auto;
            }
            
            .formNew-Group input,
            .formNew-Group textarea {
                width: 100%;
            }
            
            .formNew-btnAdd {
                width: 100%;
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
            <li><a href="game.php"><u>Game</u></a></li>
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
            document.getElementById('gameName').value = ""; 
            document.getElementById('description').value = ""; 
            document.getElementById('actionButton').value = "add"; 
            document.getElementById('actionButtonText').innerText = "Add new"; 
            document.getElementById('idgame').value = ""; 
        }

        function openFrmEdit(idgame, name, description) {
            document.getElementById('formNew').style.display = "block";
            document.getElementById('gameName').value = name; 
            document.getElementById('description').value = description; 
            document.getElementById('actionButton').value = "edit"; 
            document.getElementById('actionButtonText').innerText = "Update Game"; 
            document.getElementById('idgame').value = idgame; 
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
                        <h2><span id="actionButtonText">Add a new Game</span></h2>
                        <input type="hidden" id="idgame" name="idgame"> 
                        <div class="formNew-Group">
                            <label for="name">Name</label>
                            <input type="text" id="gameName" name="gameName" placeholder="Enter Game Name" required>
                        </div>
                        <div class="formNew-Group">
                            <label for="description">Description</label>
                            <textarea id="description" name="desc" placeholder="Enter Game Description" rows="4" required></textarea>
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
                    <th>Id Game</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $dataGame = $g->ReadDataGame($pageStart,$maxRows);
                
                if (!empty($dataGame)) {
                    foreach($dataGame as $dgame) {
                        echo "<tr>";
                        echo "<td>" . $dgame["idgame"] . "</td>";
                        echo "<td>" . $dgame["name"] . "</td>";
                        echo "<td>" . $dgame["description"] . "</td>";
                        echo "<td>
                                <button type='button' onclick='openFrmEdit(\"" . $dgame["idgame"] . "\", \"" . $dgame["name"] . "\", \"" . $dgame["description"] . "\")' style='color: #A0D683; border: none; background: none; cursor: pointer; font-size: 18px;'>✔ Update</button>
                                <form method='POST' action='' style='display:inline;'>
                                    <input type='hidden' name='idgame' value='" . $dgame["idgame"] . "'>
                                    <button type='submit' name='action' value='delete' style='color: #FF474D; border: none; background: none; cursor: pointer; font-size: 18px;'><span>&#x1F5D1;</span> Delete</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tar>";
                    echo "<td colspan='4' style='text-align: center;'>None</td>";
                    echo "</tar>";
                }
                
                
                $totalPages = $g->ReadPages($maxRows);
                ?>
            </tbody>
        </table>
        
        <div>
            <?php 
                echo("Showing Data " . $pageStart + 1 . " to  " . $pageStart + $maxRows);
            ?>
        </div>
        <div class="buttons">
            <a href="<?php if($page <= 1){echo " # ";} else {echo "game.php?page=". $page - 1;} ?>"><button>Back</button></a>
        <!--    <?php //for($i = 1; $i <= $totalPages; $i++) :?>
                <a href="?page="<?php //echo($i);?>> <?php //  echo($i) ?> </a>
            <?php   //endfor;  ?> -->
            <a href="<?php if($page >= $totalPages){echo"#";} else{echo"game.php?page=".$page + 1 ;} ?>"><button>Next</button></a>

        </div>
    </div>
</body>
</html>
