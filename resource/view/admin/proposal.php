<?php
require_once('../../../classes/member.php');
session_start();
if (!isset($_SESSION['active_user'])) {
    header('Location: ../../../index.php');
    exit();
}



$conn = new mysqli('localhost', 'root', '', 'esport');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idProposal = $_POST['id_proposal'];
    $action = $_POST['action'];
    if ($action == 'approve') {
        $status = 'approved';
    } elseif ($action == 'rejected') {
        $status = 'rejected';
    }
    $sql = "UPDATE join_proposal SET status = ? WHERE idjoin_proposal = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $status, $idProposal);
    $stmt->execute();
    $stmt->close();
    $conn->close();
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
        </div>
    </nav>
    <div class="container">
        
        <table class="table">
            <thead>
                <tr>
                    <th>Id Proposal</th>
                    <th>Username</th>
                    <th>Team</th>
                    <th>Game</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $conn = new mysqli('localhost', 'root', '', 'esport');
                    $stmt = $conn->prepare("SELECT jp.idjoin_proposal, m.username, t.name as team, g.name as game FROM join_proposal jp
                                            inner join member m on m.idmember = jp.idmember 
                                            inner join team t on t.idteam = jp.idteam
                                            inner join  game g on g.idgame = t.idgame 
                                            where status ='waiting'");
                    $stmt->execute();
                    $res = $stmt->get_result();
                    if($res->num_rows > 0 ){
                        while($categori = $res->fetch_array()){
                            echo "<tr>";
                            echo "<td>" . $categori["idjoin_proposal"] . "</td>";
                            echo "<td>" . $categori["username"] . "</td>";
                            echo "<td>" . $categori["team"] . "</td>";
                            echo "<td>" . $categori["game"] . "</td>";
                            echo "<td>
                                    <form method='POST' action=''>
                                        <input type='hidden' name='id_proposal' value='" . $categori["idjoin_proposal"] . "'>
                                        <button type='submit' name='action' value='approve' style='color: green; border: none; background: none; cursor: pointer; font-size: 18px;'>✔ Approve</button>
                                        <button type='submit' name='action' value='rejected' style='color: red; border: none; background: none; cursor: pointer; font-size: 18px;'>✖ Decline</button>
                                    </form>
                                </td>";
                            echo "</tr>"; 
                        }
                    }else{
                        echo "<tr>";
                        echo "<td colspan='5' style='text-align: center;'>None</td>";
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
