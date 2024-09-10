<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
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
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #2a0136;
            color: white;
            text-align: center;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background-color: #250129;
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
        .photo-profile{
            display: flex;
            align-items: center;
            font-size: 24px;
            font-weight: bold;
        }
        .photo-profile img {
            width: 40px;
            height: auto;
            margin-right: 10px;
        }
        .nav-section{
            list-style: none;
            display: flex;
            gap: 20px;
            align-items: center;
        }
        .nav-section li a {
            text-decoration: none;
            color: white;
            font-size: 18px;
            margin-right: 20px;
            margin-left:20px
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
            <li><a href="team.php"><u>Team</u></a></li>
            <li><a href="game.php">Game</a></li>
            <li><a href="event.php">Event</a></li>
            <li><a href="achievement.php">Achivement</a></li>
        </ul>
        <div class="photo-profile">
            <img src="../resource/fotoProfile.png" alt="Foto Profil">
            <h5>Hello, <?php  echo $_SESSION['fname']?></h5>
        </div>
    </nav>
    
</div>
</body>
</html>
