<?php
session_start();
if (isset($_SESSION['username'])) {
    if($_SESSION['profile'] == "admin"){
        header('Location: admin/proposal.php');
    }else{
        header('Location: user/welcome.php');
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HIKSROT</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
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
            padding: 20px;
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
        .nav-section{
            list-style: none;
            display: flex;
            gap: 20px;
        }
        .nav-section li a {
            text-decoration: none;
            color: white;
            font-size: 18px;
            margin-right: 250px;
            margin-left:100px
        }
        .nav-button button{
            margin-left: 15px;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }
        .login-button{
            background-color: #5d20a7;
            color: white;
        }
        .nav-button .sign-up-button {
            background-color: transparent;
            color: white;
            border: 2px solid white;
        }
        .nav-button a{
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <?php
    $is_logged_in = isset($_SESSION['active_user']); 
    ?>
    <script>
        function checkLogin(isLoggedIn) {
            if (isLoggedIn == false) {
                alert("Anda harus login terlebih dahulu untuk mengakses halaman ini.");
            }
        }
    </script>
    <nav class="navbar">
        <div class="logo">
            <img src="resource/img/hiksrotIcon.png" alt="Hiksrot Logo">
            HIKSROT
        </div>
        <ul class="nav-section">
            <li><a href="index.php"><u>Home</u></a></li>
            <li><a href="#" onclick="checkLogin(<?php echo json_encode($is_logged_in); ?>)">Game Detail</a></li>
            <li><a href="#" onclick="checkLogin(<?php echo json_encode($is_logged_in); ?>)">Team Detail</a></li>
        </ul>
        <div class="nav-button">
            <button class="sign-up-button"><a href="resource/view/signUp.php">Sign Up</a></button>
            <button class="login-button"><a href="resource/view/login.php">Login</a></button>
        </div>
    </nav>
</body>
</html>