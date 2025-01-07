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
    font-family: 'Poppins', sans-serif;
    background-color: #0a0a0a;
    color: white;
    background-image: url("resource/img/BG.png");
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    min-height: 100vh;
}

/* Navbar styles */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background: rgba(0, 0, 0, 0.8);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.logo {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 24px;
    font-weight: bold;
    color: white;
    text-decoration: none;
}

.logo img {
    width: 40px;
    height: auto;
}

.nav-section {
    display: flex;
    gap: 30px;
    list-style: none;
    margin: 0;
    padding: 0;
}

.nav-section a {
    color: white;
    text-decoration: none;
    font-size: 16px;
    font-weight: 500;
    transition: color 0.3s ease;
}

.nav-section a:hover {
    color: #8A2BE2;
}

.nav-button {
    display: flex;
    gap: 15px;
}

.nav-button button {
    padding: 10px 20px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s ease;
}

.sign-up-button {
    background: transparent;
    border: 2px solid white !important;
    color: white;
}

.login-button {
    background: #8A2BE2;
    color: white;
}

.sign-up-button:hover {
    background: white;
    color: #8A2BE2;
}

.login-button:hover {
    background: #6B1FB3;
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


/* Container styles */
.container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.game-card {
    background: rgba(0, 0, 0, 0.8);
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.game-header {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.game-info h2 {
    margin: 0 0 10px 0;
    font-size: 24px;
    color: #8A2BE2;
}

.game-description {
    color: #cecece;
    font-size: 16px;
    line-height: 1.6;
    margin-bottom: 30px;
}

/* Teams section */
.teams-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.team-item {
    background: rgba(138, 43, 226, 0.1);
    border-radius: 10px;
    padding: 15px;
    text-align: center;
    transition: transform 0.3s ease;
}

.team-item:hover {
    transform: translateY(-5px);
}

.team-item img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    margin-bottom: 10px;
    border: 2px solid #8A2BE2;
}

.team-item h3 {
    margin: 10px 0;
    font-size: 18px;
    color: white;
}

/* Events section */
.events-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 10px;
}

.events-table th {
    background: rgba(138, 43, 226, 0.2);
    padding: 15px;
    text-align: left;
    font-weight: 600;
    color: white;
}

.events-table td {
    background: rgba(255, 255, 255, 0.05);
    padding: 15px;
}

.events-table tr {
    transition: transform 0.3s ease;
}

.events-table tr:hover {
    transform: translateX(5px);
}

.section-title {
    font-size: 20px;
    color: #8A2BE2;
    margin: 30px 0 20px;
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
    background-color: rgba(138, 43, 226, 0.2);
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
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background-color: rgba(138, 43, 226, 0.1);
}

/* Pagination */
.buttons {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-top: 20px;
}

.buttons button {
    background: #8A2BE2;
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: all 0.3s ease;
}

.buttons button:hover:not([disabled]) {
    background: #6B1FB3;
    transform: translateY(-2px);
}

.buttons button[disabled] {
    background-color: #cccccc;
    cursor: not-allowed;
}

.page-info {
    text-align: center;
    margin: 20px 0;
    color: #cecece;
}

/* No Data Message */
.no-data {
    text-align: center;
    padding: 20px;
    color: #cecece;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    font-style: italic;
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
        background-color: rgba(0, 0, 0, 0.95);
        position: absolute;
        top: 100%;
        left: 0;
        padding: 20px 0;
        backdrop-filter: blur(10px);
    }
    .nav-section li a:hover {
    color: #5d20a7;
    }

    .nav-section li a.active {
        background: #5d20a7;
        color: white;
    }

    .nav-button {
        display: flex;
        gap: 10px;
    }

    .nav-button button {
        padding: 8px 15px;
        font-size: 14px;
        margin-left: 0;
    }

    .game-header {
        flex-direction: column;
        text-align: center;
    }

    .teams-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }

    .table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
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
        <a href="index.php" class="logo">
            <img src="resource/img/hiksrotIcon.png" alt="HIKSROT">
            HIKSROT
        </a>
        <ul class="nav-section">
            <li><a href="index.php">Home</a></li>
            <li><a href="gameDetail.php" ><u>Game Detail</u></a></li>
            <li><a href="teamDetail.php">Team Detail</a></li>
        </ul>
        <div class="nav-button">
            <button class="sign-up-button"><a href="resource/view/signUp.php">Sign Up</a></button>
            <button class="login-button"><a href="resource/view/login.php">Login</a></button>
        </div>
    </nav>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.querySelector('.menu-toggle');
            const navSection = document.querySelector('.nav-section');

            menuToggle.addEventListener('click', function() {
                this.classList.toggle('active');
                navSection.classList.toggle('active');
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!menuToggle.contains(e.target) && !navSection.contains(e.target)) {
                    menuToggle.classList.remove('active');
                    navSection.classList.remove('active');
                }
            });
        });
    </script>
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
