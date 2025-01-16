<?php
require_once('classes/Team.php');
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
$team = new Team($conn);

$maxRows = 5;
$page = (isset($_GET["page"]) && is_numeric($_GET["page"])) ? ($_GET["page"]) :1;
$pageStart = ($page - 1) * $maxRows;
// Get teams using the Team class method
$teams = $team->ReadDataTeam($pageStart, $maxRows);
$totalPages = $team->ReadPages($maxRows);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link href="resource/css/menu/navMenu.css" rel="stylesheet">
    <link href="resource/css/menu/bodyMenu.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  
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
            <div class="sec-hov">
                <li><a href="index.php">Home</a></li>
            </div>
            

            <div class="sec-hov">
                <li><a href="gameDetail.php">Game Detail</a></li>
            </div>

            <li><a href="teamDetail.php" style="color:#4834D4;"><b>Team Detail</b></a></li>
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

    <div class="container-teamDetail">
        <?php foreach($teams as $teamData): ?>
            <div class="team-card">
                <div class="team-header">
                    <h2 class="team-title">
                        <?php echo $teamData['teamName'] . ' - ' . $teamData['gameName']; ?>
                    </h2>
                </div>
                
                <!-- Team Members Section -->
                <div class="team-section">
                    <h3 class="section-title">Team Members</h3>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $members = $team->getTeamMembers($teamData['idteam']);
                                if($members && $members->num_rows > 0):
                                    while($member = $members->fetch_assoc()): 
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($member['fname'] . ' ' . $member['lname']); ?></td>
                                    <td><?php echo htmlspecialchars($member['description']); ?></td>
                                </tr>
                                <?php 
                                    endwhile;
                                else:
                                ?>
                                <tr>
                                    <td colspan="2" class="no-data">No team members</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Achievements Section -->
                <div class="achievements-section">
                    <h3 class="section-title">Achievements</h3>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Achievement</th>
                                    <th>Date</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $achievements = $team->getTeamAchievements($teamData['idteam']);
                                if($achievements && $achievements->num_rows > 0):
                                    while($achievement = $achievements->fetch_assoc()): 
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($achievement['name']); ?></td>
                                    <td><?php echo date('d M Y', strtotime($achievement['date'])); ?></td>
                                    <td><?php echo htmlspecialchars($achievement['description']); ?></td>
                                </tr>
                                <?php 
                                    endwhile;
                                else:
                                ?>
                                <tr>
                                    <td colspan="3" class="no-data">No achievements yet</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Events Section -->
                <div class="events-section">
                    <h3 class="section-title">Upcoming Events</h3>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Event Name</th>
                                    <th>Date</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $events = $team->getTeamEvents($teamData['idteam']);
                                if($events && $events->num_rows > 0):
                                    while($event = $events->fetch_assoc()): 
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($event['name']); ?></td>
                                    <td><?php echo date('d M Y', strtotime($event['date'])); ?></td>
                                    <td><?php echo htmlspecialchars($event['description']); ?></td>
                                </tr>
                                <?php 
                                    endwhile;
                                else:
                                ?>
                                <tr>
                                    <td colspan="3" class="no-data">No upcoming events</td>
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
                    <a href="<?php echo ($page <= 1) ? '#' : "teamDetail.php?page=".($page-1); ?>" >
                        <button <?php echo ($page <= 1) ? 'disabled' : ''; ?>>Previous</button>
                    </a>
                    <a href="<?php echo ($page >= $totalPages) ? '#' : "teamDetail.php?page=".($page+1); ?>">
                        <button <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>>Next</button>
                    </a>
                    <div class="page-info">
                    <?php 
                        echo("Showing Data " . $pageStart + 1 . " to  " . $pageStart + $maxRows);
                    ?>
                    </div>
                </div>
                
            </div>
    </div>

</body>
</html>
