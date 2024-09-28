<?php
require_once('../../../classes/member.php');
session_start();
if (!isset($_SESSION['active_user'])) {
    header('Location: ../../../index.php');
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'esport');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $action = $_POST['action'];

    if ($action == 'delete') {
        $idEvent = $_POST['idevent'];
        $conn = new mysqli('localhost', 'root', '', 'esport');
        $stmt = $conn->prepare("delete from event where idevent =". $idEvent);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        
    }
    else if($action == "add"){
        $nameEvent = $_POST['name'];
        $dateEvent = $_POST['date'];
        $conn = new mysqli('localhost', 'root', '', 'esport');
        $stmt = $conn->prepare("INSERT INTO event (idevent, name, date) VALUES ('', '$nameEvent', '$dateEvent')");
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }
    else if ($action == "edit") {
        $idEvent = $_POST['idevent'];
        $nameEvent = $_POST['name'];
        $dateEvent = $_POST['date'];
        $stmt = $conn->prepare("UPDATE event SET name=?, date=? WHERE idevent = " . $idEvent );
        $stmt->bind_param("ss", $nameEvent, $dateEvent);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="../../css/nav.css" rel="stylesheet">
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
            width: 30%;
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
        .formNew-Group{
            margin-bottom: 15px;
            color: black;
        }

        .formNew-Group label {
            display: block;
            font-size: 16px;
            margin-bottom: 5px;
            color: black;
            padding-bottom: 20px;
        }

        .formNew-Group input, .formNew-Group textarea {
            width: 80%;
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
            float: right;
        }

        .formNew-btnAdd:hover {
            background-color: #55004d;
        }
        .formNew-btnAddContainer {
            display: flex;
        }
        .formNew-Team{
            padding: 5px;
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
            <li><a href="team.php">Team</a></li>
            <li><a href="game.php">Game</a></li>
            <li><a href="event.php"><u>Event</u></a></li>
            <li><a href="achievement.php">Achivement</a></li>
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
    </script>

    <div class="container">
        <form method="POST" action="">
            <a onclick="openFrmNew()" style="margin-bottom: 15px; padding: 10px 20px; background-color: #fff; color: #3c0036; text-decoration: none; border-radius: 5px; border: none; cursor: pointer; float: right;">+ New</a>

            <div id="formNew" class="frmNew">
                <div class="frm-content">
                    <span class="close" onclick="closeFrmNew()">&times;</span>
                    <form method="POST" action="">
                        <h2><span id="actionButtonText">Add a new Event</span></h2>
                        <input type="hidden" id="idevent" name="idevent"> <!-- Hidden input untuk idevent saat update -->
                        <div class="formNew-Group">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" placeholder="Enter Event Name" required>
                        </div>
                        <div class="formNew-Group">
                            <label for="date">Date</label>
                            <input type="date" id="date" name="date" required>
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
                    <th>Id Event</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Action</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $conn = new mysqli('localhost', 'root', '', 'esport');
                $stmt = $conn->prepare("SELECT * FROM event");
                $stmt->execute();
                $res = $stmt->get_result();
                if ($res->num_rows > 0) {
                    while ($categori = $res->fetch_array()) {
                        echo "<tr>";
                        echo "<td>" . $categori["idevent"] . "</td>";
                        echo "<td>" . $categori["name"] . "</td>";
                        echo "<td>" . $categori["date"] . "</td>";
                        echo "<td>
                                <button type='button' style='color: green; border: none; background: none; cursor: pointer; font-size: 18px;' onclick='openFrmEdit(" . $categori["idevent"] . ", \"" . $categori["name"] . "\", \"" . $categori["date"] . "\")'>✔ Update</button>
                                <form method='POST' action='seeDetail.php' style='display:inline;'>
                                    <input type='hidden' name='namaCate' value='Event'>
                                    <input type='hidden' name='idevent' value='" . $categori["idevent"] . "'>
                                    <button type='submit' name='detail' value='detail' style='color: red; border: none; background: none; cursor: pointer; font-size: 18px;'><span>&#x1F5D1;</span> Detail</button>
                                </form>
                                <form method='POST' action='' style='display:inline;'>
                                    <input type='hidden' name='idevent' value='" . $categori["idevent"] . "'>
                                    <button type='submit' name='action' value='delete' style='color: red; border: none; background: none; cursor: pointer; font-size: 18px;'><span>&#x1F5D1;</span> Delete</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr>";
                    echo "<td colspan='4' style='text-align: center;'>None</td>";
                    echo "</tr>";
                }
                $stmt->close();
                $conn->close();
                ?>
            </tbody>
        </table>

        <!-- Navigation Buttons -->
        <div class="buttons">
            <button>Back</button>
            <button>Next</button>
        </div>
    </div>
</body>
</html>
