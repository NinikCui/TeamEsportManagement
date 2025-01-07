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
$t = new Team($conn);





$maxRows = 5;
$page = (isset($_GET["page"]) && is_numeric($_GET["page"])) ? ($_GET["page"]) :1;
$pageStart = ($page - 1) * $maxRows;
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
    z-index: 1000;
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

/* Hero Section */
.hero-section {
    position: relative;
    padding: 100px 20px;
    text-align: center;
    background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7));
    margin-bottom: 40px;
}

.hero-content {
    max-width: 800px;
    margin: 0 auto;
}

.hero-content h1 {
    font-size: 3.5em;
    margin-bottom: 20px;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: 2px;
}

.hero-content p {
    font-size: 1.2em;
    color: #cecece;
    line-height: 1.6;
}

/* Main Container */
.container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

/* Section Header */
.section-header {
    text-align: center;
    margin-bottom: 40px;
    animation: fadeIn 0.5s ease-out;
}

.section-header h2 {
    font-size: 2.5em;
    color: #fff;
    margin-bottom: 15px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.section-header p {
    color: #cecece;
    font-size: 1.1em;
    max-width: 600px;
    margin: 0 auto;
}

/* Teams Overview Section */
.teams-overview {
    background: rgba(0, 0, 0, 0.7);
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    animation: slideUp 0.5s ease-out;
}

/* Table Styles */
.table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin-bottom: 30px;
}

.table th {
    background-color: rgba(93, 32, 167, 0.6);
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 14px;
    padding: 15px;
    letter-spacing: 1px;
}

.table td {
    padding: 20px 15px;
    background-color: rgba(255, 255, 255, 0.05);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.table tr:last-child td {
    border-bottom: none;
}

.table tr {
    transition: background-color 0.3s ease;
}

.table tr:hover td {
    background-color: rgba(255, 255, 255, 0.1);
}

.game-info, .team-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.game-name, .team-name {
    font-size: 1.1em;
    color: #fff;
}

.team-logo {
    display: flex;
    justify-content: center;
    align-items: center;
}

.team-logo img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(93, 32, 167, 0.6);
    transition: transform 0.3s ease;
}

.team-logo img:hover {
    transform: scale(1.1);
}

/* Pagination */
.pagination-info {
    text-align: center;
    color: #cecece;
    margin: 20px 0;
    font-size: 0.9em;
}

.buttons {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-top: 20px;
}

.buttons button {
    background-color: #5d20a7;
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.buttons button:hover:not([disabled]) {
    background-color: #4a1a85;
    transform: translateY(-2px);
}

.buttons button[disabled] {
    background-color: #cccccc;
    cursor: not-allowed;
    opacity: 0.7;
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideUp {
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
        background-color: rgba(0, 0, 0, 0.95);
        position: absolute;
        top: 100%;
        left: 0;
        padding: 20px 0;
    }

    .nav-section.active {
        display: flex;
    }

    .nav-section li a {
        margin: 10px 0;
        display: block;
    }

    .hero-content h1 {
        font-size: 2.5em;
    }

    .section-header h2 {
        font-size: 2em;
    }

    .table td {
        padding: 15px 10px;
    }

    .team-logo img {
        width: 60px;
        height: 60px;
    }

    .buttons {
        flex-direction: column;
        align-items: stretch;
    }

    .buttons button {
        width: 100%;
    }
}

@media screen and (max-width: 480px) {
    .navbar {
        padding: 10px;
    }

    .logo {
        font-size: 18px;
    }

    .logo img {
        width: 30px;
    }

    .hero-section {
        padding: 60px 15px;
    }

    .hero-content h1 {
        font-size: 2em;
    }

    .hero-content p {
        font-size: 1em;
    }

    .section-header h2 {
        font-size: 1.5em;
    }

    .team-logo img {
        width: 50px;
        height: 50px;
    }

    .game-name, .team-name {
        font-size: 0.9em;
    }

    .table {
        font-size: 14px;
    }
}

/* No Data State */
.no-data {
    text-align: center;
    padding: 30px;
    color: #cecece;
    font-style: italic;
}

/* Image Preview */
.image-preview {
    width: 100px;
    height: 100px;
    border: 1px dashed #ccc;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 10px;
    border-radius: 5px;
    overflow: hidden;
}

.image-preview img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
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
            <li><a href="index.php"><u>Home</u></a></li>
            <li><a href="gameDetail.php">Game Detail</a></li>
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
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.menu-toggle') && !event.target.closest('.nav-section')) {
                    menuToggle.classList.remove('active');
                    navSection.classList.remove('active');
                }
            });

            // Prevent menu from closing when clicking inside nav
            navSection.addEventListener('click', function(event) {
                event.stopPropagation();
            });

            // Add active class to current page tab
            const currentPage = window.location.pathname.split('/').pop() || 'index.php';
            const navLinks = document.querySelectorAll('.nav-section a');
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPage) {
                    link.classList.add('active');
                }
            });
        });
    </script>
    <div class="hero-section">
        <div class="hero-content">
            <h1>Welcome to HIKSROT</h1>
            <p>Discover our competitive gaming teams and their achievements in various esports titles</p>
        </div>
    </div>

    

    <div class="container">
    <div class="section-header">
        <h2>Our Gaming Teams</h2>
        <p>Meet our dedicated teams across different game titles</p>
    </div>

    <div class="teams-overview">
        <table class="table">
            <thead>
                <tr>
                    <th>Game Title</th>
                    <th>Team Name</th>
                    <th>Team Logo</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $teams = $t->ReadDataTeam($pageStart, $maxRows);
                if (!empty($teams)) {
                    foreach ($teams as $team) {
                        echo "<tr>";
                        echo "<td>
                                <div class='game-info'>
                                    <span class='game-name'>{$team["gameName"]}</span>
                                </div>
                              </td>";
                        echo "<td>
                                <div class='team-info'>
                                    <span class='team-name'>{$team["teamName"]}</span>
                                </div>
                              </td>";
                        $idTeamGambar = $team["idteam"];
                        echo "<td>
                                <div class='team-logo'>
                                    <img src=\"resource/img/teamImg/$idTeamGambar.jpg?v=" . time() . "\" 
                                         onerror=\"this.src='resource/img/default-team.jpg';\">
                                </div>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' class='no-data'>No teams available</td></tr>";
                }
                $totalPages = $t->ReadPages($maxRows);
                ?>
            </tbody>
        </table>

        <!-- Pagination info -->
        <div class="pagination-info">
            <?php 
                $totalData = count($teams);
                $startNumber = $pageStart + 1;
                $endNumber = $pageStart + $totalData;
                echo "Showing $startNumber to $endNumber of our teams";
            ?>
        </div>

        <!-- Navigation Buttons -->
        <div class="buttons">
            <a href="<?php if($page <= 1){echo '#';} else {echo 'index.php?page='.($page-1);} ?>">
                <button <?php if($page <= 1) echo 'disabled'; ?>>Previous</button>
            </a>
            <a href="<?php if($page >= $totalPages){echo '#';} else {echo 'index.php?page='.($page+1);} ?>">
                <button <?php if($page >= $totalPages) echo 'disabled'; ?>>Next</button>
            </a>
        </div>
    </div>
</div>

</body>
</html>
