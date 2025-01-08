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

// Constants for pagination
$maxRows = 10;  // Number of rows per page
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
$pageStart = ($currentPage - 1) * $maxRows;

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
    transition: all 0.3s ease;
    border-radius: 5px;
}

.nav-section li a:hover {
    color: #5d20a7;
}

.nav-section li a.active {
    background: #5d20a7;
    color: white;
}

.nav-button button {
    margin-left: 15px;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    font-size: 16px;
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
    transform: translateY(-2px);
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

.team-card {
    background: rgba(0, 0, 0, 0.7);
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    animation: fadeIn 0.5s ease forwards;
}

.team-card h2 {
    color: white;
    margin: 0 0 20px 0;
    padding-bottom: 15px;
    border-bottom: 2px solid rgba(255, 255, 255, 0.1);
    font-size: 24px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.section {
    margin-bottom: 30px;
    animation: slideUp 0.5s ease forwards;
}

.section h3 {
    color: #5d20a7;
    margin: 0 0 15px 0;
    font-size: 20px;
    font-weight: 600;
    padding-left: 10px;
    border-left: 3px solid #5d20a7;
}

/* Table Styles */
.table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin-bottom: 20px;
    background: rgba(255, 255, 255, 0.05);
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
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background-color: rgba(93, 32, 167, 0.2);
    transform: translateX(5px);
}

.table td {
    color: rgba(255, 255, 255, 0.9);
}

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

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(10px);
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
        background-color:  rgba(0, 0, 0, 0.95);
        position: absolute;
        top: 100%;
        left: 0;
        padding: 20px 0;
        backdrop-filter: blur(10px);
    }

    .nav-section.active {
        display: flex;
    }

    .nav-section li a {
        margin: 10px 0;
        display: block;
        padding: 10px 20px;
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

    .team-card {
        padding: 15px;
    }

    .team-card h2 {
        font-size: 20px;
        flex-direction: column;
        text-align: center;
        gap: 10px;
    }

    .section h3 {
        font-size: 18px;
    }

    .table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }

    .table th,
    .table td {
        padding: 10px;
        font-size: 14px;
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

    .container {
        padding: 10px;
    }

    .team-card {
        padding: 12px;
        margin-bottom: 20px;
    }

    .team-card h2 {
        font-size: 18px;
        margin-bottom: 15px;
    }

    .section h3 {
        font-size: 16px;
    }

    .table th,
    .table td {
        padding: 8px;
        font-size: 13px;
    }
}

/* Additional Utilities */
.text-center {
    text-align: center;
}

.no-data {
    text-align: center;
    padding: 20px;
    color: #cecece;
    font-style: italic;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    margin: 10px 0;
}

/* Status Badges if needed */
.status-badge {
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
    display: inline-block;
}

.status-upcoming {
    background-color: #5d20a7;
    color: white;
}

.status-completed {
    background-color: #28a745;
    color: white;
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
            <li><a href="gameDetail.php">Game Detail</a></li>
            <li><a href="teamDetail.php"><u>Team Detail</u></a></li>
        </ul>
        <div class="nav-button">
            <button class="sign-up-button"><a href="resource/view/signUp.php">Sign Up</a></button>
            <button class="login-button"><a href="resource/view/login.php">Login</a></button>
        </div>
    </nav>

    <script>
       
        //NAVBAR
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.querySelector('.menu-toggle');
            const navSection = document.querySelector('.nav-section');

            menuToggle.addEventListener('click', function() {
                navSection.classList.toggle('active');
            });
        });
    </script>

<!-- Dalam section container, ganti pemanggilan fungsi -->
<div class="container">
    <?php foreach($teams as $teamData): ?>
        <div class="team-card">
            <h2><?php echo $teamData['teamName'] . ' - ' . $teamData['gameName']; ?></h2>
            
            <!-- Team Members Section -->
            <div class="section">
                <h3>Team Members</h3>
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
                            <td><?php echo $member['fname'] . ' ' . $member['lname']; ?></td>
                            <td><?php echo $member['description']; ?></td>
                        </tr>
                        <?php 
                            endwhile;
                        else:
                        ?>
                        <tr>
                            <td colspan="2" style="text-align: center;">No team members</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Achievements Section -->
            <div class="section">
                <h3>Achievements</h3>
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
                            <td><?php echo $achievement['name']; ?></td>
                            <td><?php echo date('d M Y', strtotime($achievement['date'])); ?></td>
                            <td><?php echo $achievement['description']; ?></td>
                        </tr>
                        <?php 
                            endwhile;
                        else:
                        ?>
                        <tr>
                            <td colspan="3" style="text-align: center;">No achievements yet</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Upcoming Events Section -->
            <div class="section">
                <h3>Upcoming Events</h3>
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
                            <td><?php echo $event['name']; ?></td>
                            <td><?php echo date('d M Y', strtotime($event['date'])); ?></td>
                            <td><?php echo $event['description']; ?></td>
                        </tr>
                        <?php 
                            endwhile;
                        else:
                        ?>
                        <tr>
                            <td colspan="3" style="text-align: center;">No upcoming events</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
