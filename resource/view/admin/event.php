<?php
require_once('../../../classes/member.php');
session_start();
if (!isset($_SESSION['active_user'])) {
    header('Location: ../../../index.php');
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
    <title>Event</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="../../css/nav.css" rel="stylesheet">
    <style>
        body {
            background-image: url("../../img/BG.png");
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
        </div>
    </nav>
    
</div>
</body>
</html>
