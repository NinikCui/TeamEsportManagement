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
    <title>Achivement</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="nav.css" rel="stylesheet">
    <style>
        
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
    
</div>
</body>
</html>
