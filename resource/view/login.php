<?php
require_once('../../classes/member.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        session_start();

        $conn = new mysqli('localhost', 'root', '', 'fullstack');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $username = $_POST['username'];
        $password = $_POST['password'];

        $member = new Member($conn);
        if ($member->login($username, $password)) {
            $_SESSION['active_user'] = $member;
            if ($member->profile == "admin") {
                header('Location: admin/proposal.php');
                exit();
            } else {
                header('Location: user/welcome.php');
                exit();
            }
        } else {
            header('Location: login.php?status=failed');
            exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            background-color: #FAFAFA;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-image: url("../img/BG.png");
        }
        .container {
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 900px;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        @media (min-width: 768px) {
            .container {
                flex-direction: row;
                width: 60%;
            }
        }

        .form-container {
            padding: 20px;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        @media (min-width: 768px) {
            .form-container {
                padding: 40px;
                width: 50%;
            }
        }

        h1 {
            color: #26137C;
        }

        p {
            color: #26137C;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input[type="text"],
        input[type="password"] {
            padding: 15px;
            margin-bottom: 15px;
            border: 2px solid #ddd;
            border-radius: 30px;
            font-size: 16px;
            border-color: #26137C;
            width: 100%;
        }

        button {
            background: linear-gradient(180deg, #0B43FE, #3F0DF4);
            padding: 15px;
            border: none;
            border-radius: 50px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 20px;
        }

        button:hover {
            background: linear-gradient(180deg, #3F0DF4, #3F0DF4);
        }

        .signin-text {
            text-align: center;
            margin-top: 20px;
            color: #555;
        }

        .signin-text a {
            color: #0B43FE;
            text-decoration: none;
        }

        .password-container {
            position: relative;
        }

        .password-container input[type="password"],
        .password-container input[type="text"] {
            padding-right: 60px;
        }

        .see-password {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .image-container {
            width: 100%;
            height: 200px;
            background-image: url('../img/login.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        @media (min-width: 768px) {
            .image-container {
                width: 50%;
                height: auto;
            }
        }

        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .forgot-password {
            text-align: right;
            color: #26137C;
            text-decoration: none;
            font-size: 14px;
        }
    </style>
</head>
<body>
    
    <div class="container">
        <div class="image-container"></div>
        <div class="form-container">
            <h1>Hello!</h1>
            <h1>Welcome back</h1>
            <p>Access your world of endless possibilities</p>
            <form action="" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <span class="see-password" id="passEye">
                        <img src="../img/passwordMata.png" alt="Show Password">
                    </span>
                </div>
                <button type="submit">Sign In</button>
            </form>
            <p class="signin-text">Don't have an account? <a href="signUp.php">Sign Up</a></p>
        </div>
    </div>
    <script>
        const info = document.querySelector("#passEye");
        const password = document.querySelector("#password");

        info.addEventListener("click", function () {
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
        });

        document.addEventListener('DOMContentLoaded', () => {
            const urlPar = new URLSearchParams(window.location.search);
            const status = urlPar.get('status');
            if (status === 'failed') {
                alert("Login Failed, Username or Password is wrong");
            }
        });
    </script>
</body>
</html>
