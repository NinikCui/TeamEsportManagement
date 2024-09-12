<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'esport');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idAchi = $_POST['idachievement'];
    $action = $_POST['action'];

    if ($action == 'update') {
        $status = 'update';
    } elseif ($action == 'delete') {
        
            $conn = new mysqli('localhost', 'root', '', 'esport');
            $stmt = $conn->prepare("delete from achievement where idachievement =". $idAchi);
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
    <title>Achivement</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link href="nav.css" rel="stylesheet">
    <style>
        
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
        .desc{
            cursor: pointer;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="../resource/hiksrotIcon.png" alt="Hiksrot Logo">
            HIKSROT
        </div>
        <ul class="nav-section">
            <li><a href="proposal.php">Proposal</a></li>
            <li><a href="team.php">Team</a></li>
            <li><a href="game.php">Game</a></li>
            <li><a href="event.php">Event</a></li>
            <li><a href="achievement.php"><u>Achievement</u></a></li>
        </ul>
        <div class="photo-profile">
            <img src="../resource/fotoProfile.png" alt="Foto Profil">
            <h5>Hello, <?php  echo $_SESSION['fname']?></h5>
        </div>
    </nav>
    <div class="container">
        
        <table class="table">
            <thead>
                <tr>
                    <th>Id Proposal</th>
                    <th>Team</th>
                    <th>Nama</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $conn = new mysqli('localhost', 'root', '', 'esport');
                    $stmt = $conn->prepare("SELECT a.idachievement, t.name as team, a.name, DATE_FORMAT(a.date,'%d/%m/%Y') as date, a.description  FROM achievement a
                                            inner join team t on t.idteam = a.idteam");
                    $stmt->execute();
                    $res = $stmt->get_result();
                    if($res->num_rows > 0 ){
                        while($categori = $res->fetch_array()){
                            echo "<tr>";
                            echo "<td>" . $categori["idachievement"] . "</td>";
                            echo "<td>" . $categori["team"] . "</td>";
                            echo "<td>" . $categori["name"] . "</td>";
                            echo "<td>" . $categori["date"] . "</td>";
                            echo "<td class='desc'>" . $categori["description"] . "</td>";
                            echo "<td>
                                    <form method='POST' action=''>
                                        <input type='hidden' name='idachievement' value='" . $categori["idachievement"] . "'>
                                        <button type='submit' name='action' value='update' style='color: green; border: none; background: none; cursor: pointer; font-size: 18px;'><span>&#x21BB;</span> Update</button>
                                        <button type='submit' name='action' value='delete' style='color: red; border: none; background: none; cursor: pointer; font-size: 18px;'><span>&#x1F5D1;</span> Delete</button>
                                    </form>
                                </td>";
                            echo "</tr>"; 
                        }
                    }else{
                        echo "<tr>";
                        echo "<td colspan='6' style='text-align: center;'>None</td>";
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
    
</div>
</body>
</html>
