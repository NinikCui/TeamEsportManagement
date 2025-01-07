<?php
require_once('classes/Game.php');
session_start();
if (isset($_SESSION['username'])) {
    if ($_SESSION['profile'] == "admin") {
        header('Location: admin/proposal.php');
    } else {
        header('Location: user/welcome.php');
    }
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'fullstack');
$game = new Game($conn);

$maxRows = 5;
$page = (isset($_GET["page"]) && is_numeric($_GET["page"])) ? ($_GET["page"]) : 1;
$pageStart = ($page - 1) * $maxRows;

// Get games data
$games = $game->ReadDataGame($pageStart, $maxRows);
$totalPages = $game->ReadPages($maxRows);
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

/* Navbar Styles */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    position: relative;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(5px);
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
    margin: 0;
    padding: 0;
}

.nav-section li a {
    text-decoration: none;
    color: white;
    font-size: 18px;
    padding: 5px 15px;
    transition: color 0.3s ease;
}

.nav-section li a:hover {
    color: #5d20a7;
}

.nav-button button {
    margin-left: 15px;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    font-size: 16px;
    transition: all 0.3s ease;
}

.login-button {
    background-color: #5d20a7;
    color: white;
}

.login-button:hover {
    background-color: #4a1a85;
}

.nav-button .sign-up-button {
    background-color: transparent;
    color: white;
    border: 2px solid white;
}

.nav-button .sign-up-button:hover {
    background-color: white;
    color: #5d20a7;
}

.nav-button a {
    color: inherit;
    text-decoration: none;
}

/* Container and Content Styles */
.container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

/* Game Card Styles */
.game-card {
    background: rgba(0, 0, 0, 0.7);
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    animation: fadeIn 0.5s ease forwards;
}

.game-card h2 {
    color: white;
    margin: 0 0 20px 0;
    padding-bottom: 15px;
    border-bottom: 2px solid rgba(255, 255, 255, 0.1);
    font-size: 24px;
}

.game-description {
    color: #cecece;
    margin-bottom: 20px;
    font-size: 0.9em;
    line-height: 1.6;
}

/* Section Styles */
.section {
    margin-bottom: 30px;
}

.section h3 {
    color: white;
    margin: 0 0 15px 0;
    font-size: 20px;
    font-weight: 600;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    padding-bottom: 10px;
}

/* Teams Grid Styles */
.teams-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.team-item {
    text-align: center;
    background: rgba(255, 255, 255, 0.1);
    padding: 15px;
    border-radius: 8px;
    transition: transform 0.3s ease;
}

.team-item:hover {
    transform: translateY(-5px);
    background: rgba(255, 255, 255, 0.2);
}

.team-item img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    margin-bottom: 10px;
    object-fit: cover;
}

.team-item span {
    display: block;
    color: white;
    font-size: 0.9em;
}

/* Table Styles */
.table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin-bottom: 20px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    overflow: hidden;
}

.table th, 
.table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.table th {
    background-color: rgba(93, 32, 167, 0.6);
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 14px;
    letter-spacing: 0.5px;
}

.table tr:last-child td {
    border-bottom: none;
}

.table tbody tr {
    transition: background-color 0.3s ease;
}

.table tbody tr:hover {
    background-color: rgba(255, 255, 255, 0.05);
}

.table td {
    color: rgba(255, 255, 255, 0.9);
}

/* Navigation and Buttons */
.buttons {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
}

.buttons button {
    background-color: #5d20a7;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.buttons button:hover {
    background-color: #4a1a85;
}

.page-info {
    color: white;
    text-align: center;
    margin: 20px 0;
    font-size: 0.9em;
}

.no-data {
    color: #cecece;
    text-align: center;
    grid-column: 1 / -1;
    padding: 20px;
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive Design */
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
        z-index: 1000;
    }

    .nav-section.active {
        display: flex;
    }

    .nav-section li a {
        margin: 10px 0;
        display: block;
    }

    .container {
        padding: 15px;
    }

    .teams-grid {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    }

    .team-item img {
        width: 60px;
        height: 60px;
    }

    .game-card {
        padding: 15px;
    }

    .table {
        display: block;
        overflow-x: auto;
        white-spa
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
            <li><a href="gameDetail.php"><u>Game Detail</u></a></li>
            <li><a href="teamDetail.php">Team Detail</a></li>
        </ul>
        <div class="nav-button">
            <button class="sign-up-button"><a href="resource/view/signUp.php">Sign Up</a></button>
            <button class="login-button"><a href="resource/view/login.php">Login</a></button>
        </div>
    </nav>

    <div class="container">
    <?php foreach($games as $gameData): ?>
        <div class="game-card">
            <h2><?php echo $gameData['name']; ?></h2>
            <p class="game-description"><?php echo $gameData['description']; ?></p>
            
            <!-- Teams Section -->
            <div class="section">
                <h3>Teams</h3>
                <div class="teams-grid">
                    <?php 
                    $teams = $game->getTeamsByGame($gameData['idgame']);
                    if (!empty($teams)):
                        foreach($teams as $teamData): 
                    ?>
                    <div class="team-item">
                        <img src="resource/img/teamImg/<?php echo $teamData['idteam']; ?>.jpg" 
                             onerror="this.src='resource/img/default-team.jpg';"
                             alt="<?php echo $teamData['teamName']; ?>">
                        <span><?php echo $teamData['teamName']; ?></span>
                    </div>
                    <?php 
                        endforeach;
                    else:
                    ?>
                    <p class="no-data">No teams found for this game</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Events Section -->
            <div class="section">
                <h3>Events</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Participating Teams</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $events = $game->getGameEvents($gameData['idgame']);
                        if (!empty($events)):
                            foreach($events as $event): 
                        ?>
                        <tr>
                            <td><?php echo $event['eventName']; ?></td>
                            <td><?php echo date('d M Y', strtotime($event['date'])); ?></td>
                            <td><?php echo $event['description']; ?></td>
                            <td><?php echo $event['participatingTeams']; ?></td>
                        </tr>
                        <?php 
                            endforeach;
                        else:
                        ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">No events found</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Pagination -->
    <div class="buttons">
        <a href="<?php if($page <= 1){echo "#";} else {echo "gameDetail.php?page=".($page-1);} ?>">
            <button>Back</button>
        </a>
        <a href="<?php if($page >= $totalPages){echo "#";} else{echo "gameDetail.php?page=".($page+1);} ?>">
            <button>Next</button>
        </a>
    </div>
    <div class="page-info">
        <?php 
            $totalData = count($games);
            $startNumber = $pageStart + 1;
            $endNumber = $pageStart + $totalData;
            echo "Showing Data $startNumber to $endNumber"; 
        ?>
    </div>
</div>

</body>
</html>
