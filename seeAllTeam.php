<?php
require_once('classes/Team.php');
session_start();


$conn = new mysqli('localhost', 'root', '', 'fullstack');
$t = new Team($conn);





$maxRows = 5;
$page = (isset($_GET["page"]) && is_numeric($_GET["page"])) ? ($_GET["page"]) :1;
$pageStart = ($page - 1) * $maxRows;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link href="resource/css/nav.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <style>
     body {
            margin: 0;
            padding: 0;
            font-family: "Poppins", sans-serif;
            color: white;
            background-image: url("resource/img/BG.png");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            position: relative;
        }

        .logo {
            display: flex;
            align-items: center;
            font-size: 24px;
            font-weight: bold;
        }

        .logo img {
            width: 50px;
            height: auto;
            margin-right: 10px;
        }

        .menu-toggle {
            display: none;
            flex-direction: column;
            cursor: pointer;
            padding: 10px;
        }

        .menu-toggle span {
            width: 25px;
            height: 3px;
            background-color: white;
            margin: 3px 0;
            transition: 0.4s;
        }

        .nav-section {
            list-style: none;
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nav-section li a {
            text-decoration: none;
            color: white;
            font-size: 18px;
            padding: 5px 15px;
        }

        .nav-button button {
            margin-left: 15px;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }

        .login-button {
            background-color: #5d20a7;
            color: white;
        }

        .nav-button .sign-up-button {
            background-color: transparent;
            color: white;
            border: 2px solid white;
        }

        .nav-button a {
            color: white;
            text-decoration: none;
        }

        .container {
            margin-left: 15%;
            margin-top: 5%;
            display: flex;
            flex-wrap: wrap;
            gap: 50px;
            width: 80%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }

        .container .info {
            font-size: 32px;
        }

        .info p {
            font-size: 16px;
            margin-top: 0px;
        }
        .image-upload-container {
            display: flex;
            align-items: start;
            gap: 20px;
        }

        .image-preview {
            width: 100px;
            height: 100px;
            border: 1px dashed #ccc;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 10px;
        }

        .image-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
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

        .table img {
            width: 70px;
            height: 70px;
            object-fit: cover; 
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
        @media screen and (max-width: 768px) {
            .menu-toggle {
                display: flex;
            }

            .nav-section {
                display: none;
                width: 100%;
                flex-direction: column;
                text-align: center;
                background-color: rgba(93, 32, 167, 0.9);
                position: absolute;
                top: 100%;
                left: 0;
                padding: 20px 0;
            }

            .nav-section.active {
                display: flex;
            }

            .nav-section li a {
                margin: 10px 0;
                display: block;
            }

            .container {
                margin: 20px;
            }

            .info h1 {
                font-size: 28px;
            }

            .table {
                font-size: 14px;
            }

            .table th, .table td {
                padding: 10px;
            }

            .buttons {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .buttons button {
                width: 100%; /* Agar tombol memenuhi lebar kontainer */
                text-align: center;
                margin-right: 0;
            }
        }

        @media screen and (max-width: 480px) {
            .navbar {
                padding: 10px;
            }

            .logo {
                font-size: 18px;
            }

            .logo img {
                width: 30px;
            }

            .nav-button {
                display: flex;
                gap: 10px;
            }

            .nav-button button {
                padding: 8px 15px;
                font-size: 14px;
            }

            .info h1 {
                font-size: 24px;
            }

            .info p {
                font-size: 12px;
            }
            
            .table {
                font-size: 12px;
            }

            .table th, .table td {
                padding: 8px;
            }

            .container {
                margin: 10px;
                padding: 10px;
                gap: 20px;
            }

            .image-preview {
                width: 80px;
                height: 80px;
            }

            .info {
                font-size: 24px;
            }
        }

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
        <div class="menu-toggle">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="logo">
            <img src="resource/img/hiksrotIcon.png" alt="Hiksrot Logo">
            HIKSROT
        </div>
        <ul class="nav-section">
            <li><a href="index.php">Home</a></li>
            <li><a href="seeAllTeam.php"><u>Team Detail</u></a></li>
        </ul>
        <div class="nav-button">
            <button class="sign-up-button"><a href="resource/view/signUp.php">Sign Up</a></button>
            <button class="login-button"><a href="resource/view/login.php">Login</a></button>
        </div>
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

        //NAVBAR
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.querySelector('.menu-toggle');
            const navSection = document.querySelector('.nav-section');

            menuToggle.addEventListener('click', function() {
                navSection.classList.toggle('active');
            });
        });
    </script>

    <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th>Game Name</th>
                    <th>team Name</th>
                    <th>Logo</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $teams =  $t->ReadDataTeam($pageStart, $maxRows);
                if (!empty($teams)) {
                    foreach ($teams as $team) {
                        echo "<tr>";
                        echo "<td>" . $team["gameName"] . "</td>";
                        echo "<td>" . $team["teamName"] . "</td>";
                        $idTeamGambar = $team["idteam"];
                        echo "
                               <td>
                                    <img src=\"resource/img/teamImg/$idTeamGambar.jpg?v=" . time() . "\">
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
            <a href="<?php if($page <= 1){echo " # ";} else {echo "seeAllTeam.php?page=". $page - 1;} ?>"><button>Back</button></a>
            <a href="<?php if($page >= $totalPages){echo"#";} else{echo"seeAllTeam.php?page=".$page + 1 ;} ?>"><button>Next</button></a>
        </div>
    </div>
</body>
</html>
