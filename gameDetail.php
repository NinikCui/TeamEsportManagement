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
    <title>Game Detail - HIKSROT</title>
    <link href="resource/css/menu/navMenu.css" rel="stylesheet">
    <link href="resource/css/menu/bodyMenu.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    
</head>
<script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.querySelector('.menu-toggle');
            const navSection = document.querySelector('.nav-section');
            const navButton = document.querySelector('.nav-button');

            menuToggle.addEventListener('click', function() {
                this.classList.toggle('active');
                navSection.classList.toggle('active');
                navButton.classList.toggle('active');
            });

            // Menutup menu saat mengklik di luar
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.menu-toggle') && 
                    !event.target.closest('.nav-section') && 
                    !event.target.closest('.nav-button')) {
                    menuToggle.classList.remove('active');
                    navSection.classList.remove('active');
                    navButton.classList.remove('active');
                }
            });

            // Mencegah menu tertutup saat mengklik di dalam nav
            navSection.addEventListener('click', function(event) {
                event.stopPropagation();
            });
        });

    </script>
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
            
            <div class="sec-hov">
                <li><a href="index.php" >Home</a></li>
            </div>

                <li><a href="gameDetail.php" style="color:#4834D4;"><b>Game Detail</b></a></li>
            
            
            <div class="sec-hov">
                <li><a href="teamDetail.php">Team Detail</a></li>
            </div>
        </ul>
        <div class="nav-button">
            <button class="sign-up-button"><a href="resource/view/signUp.php">Sign Up</a></button>
            <button class="login-button"><a href="resource/view/login.php">Login</a></button>
        </div>
    </nav>

    <div class="container-gameDetail">
        <?php if(empty($games)): ?>
            <div class="no-data-container">
                <h2>No Games Available</h2>
                <p>There are currently no games to display.</p>
            </div>
        <?php else: ?>
            <?php foreach($games as $gameData): ?>
                <div class="game-card">
                    <div class="game-header">
                        <h2><?php echo htmlspecialchars($gameData['name']); ?></h2>
                    </div>
                    <div class="game-subheader">
                        <p class="game-description"><?php echo htmlspecialchars($gameData['description']); ?></p>
                    </div>

                    <!-- Teams Section -->
                    <div class="teams-section">
                        <h3 class="section-title">Active Teams</h3>
                        <div class="teams-grid">
                            <?php 
                            $teams = $game->getTeamsByGame($gameData['idgame']);
                            if (!empty($teams)):
                                foreach($teams as $teamData): 
                            ?>
                                <div class="team-item">
                                    <img src="resource/img/teamImg/<?php echo htmlspecialchars($teamData['idteam']); ?>.jpg" 
                                         onerror="this.src='resource/img/default-team.jpg';"
                                         alt="<?php echo htmlspecialchars($teamData['teamName']); ?>"
                                         loading="lazy">
                                    <h4><?php echo htmlspecialchars($teamData['teamName']); ?></h4>
                                </div>
                            <?php 
                                endforeach;
                            else:
                            ?>
                                <p class="no-data">No teams registered for this game yet</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="events-section">
                        <h3 class="section-title">Upcoming Events</h3>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th >Event Name</th>
                                        <th >Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $events = $game->getGameEvents($gameData['idgame']);
                                    if (!empty($events)):
                                        foreach($events as $event): 
                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($event['eventName']); ?></td>
                                            <td><?php echo date('d M Y', strtotime($event['date'])); ?></td>
                                           
                                        </tr>
                                    <?php 
                                        endforeach;
                                    else:
                                    ?>
                                        <tr>
                                            <td colspan="4" class="no-events">No upcoming events scheduled</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="pagination" aria-label="Page navigation">
                <div class="buttons">
                    <a href="<?php echo ($page <= 1) ? '#' : "gameDetail.php?page=".($page-1); ?>" >
                        <button <?php echo ($page <= 1) ? 'disabled' : ''; ?>>Previous</button>
                    </a>
                    <a href="<?php echo ($page >= $totalPages) ? '#' : "gameDetail.php?page=".($page+1); ?>">
                        <button <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>>Next</button>
                    </a>
                    <div class="page-info">
                    <?php 
                        $totalData = count($games);
                        $startNumber = $pageStart + 1;
                        $endNumber = $pageStart + $totalData;
                        echo "<p>Showing entries $startNumber to $endNumber </p>"; 
                    ?>
                    </div>
                </div>
                
            </div>
        <?php endif; ?>
    </div>


    <hr class="garis-abu">
    <footer>
        <div class="footer-content">
            <div class="footer-logo">
                <img src="resource/img/hiksrotIcon.png" >
                <h3>HIKSROT</h3>
            </div>
            <div class="contact-info">
                <p>📍 Universitas Surabaya</p>
                <p>📞 +62 896725960</p>
            </div>
        </div>
    </footer>
</body>
</html>