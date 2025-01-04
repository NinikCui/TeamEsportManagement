<?php
require_once('../../classes/member.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli('localhost', 'root', '', 'fullstack');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $first_name = $_POST['firstName'];
    $last_name = $_POST['lastName'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    require_once('../../classes/member.php');
    $member = new Member($conn);
    if ($member->Registrasi($username, $password, $first_name, $last_name)) {
        header("Location: signUp.php?status=success");
        exit();
    } else {
        header("Location: signUp.php?status=failed");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
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
            height: 100vh;
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
            width: 100%;
            max-width: 900px;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            flex-direction: row;
        }

        .form-container {
            padding: 40px;
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        h1 {
            color: #26137C;
            margin-bottom: 10px;
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
            right: 30px;
            top: 45%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .image-container {
            width: 50%;
            background-image: url('../img/signUp.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .image-container {
                width: 100%;
                height: 200px;
            }

            .form-container {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            body {
                height: auto;
            }

            .form-container {
                padding: 20px;
            }

            input[type="text"],
            input[type="password"] {
                font-size: 14px;
            }

            button {
                font-size: 14px;
            }

            .signin-text {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <!-- PHP Logic -->
    <?php
    
    ?>
    <!-- HTML Content -->
    <div class="container">
        <div class="image-container"></div> 
        <div class="form-container">
            <h1>Start for free</h1>
            <h1>Create new account</h1>
            <p>Find out what everyone is talking about!</p>
            <form action="" method="POST">
                <input type="text" name="firstName" placeholder="First name" required>
                <input type="text" name="lastName" placeholder="Last name" required>
                <input type="text" name="username" placeholder="Username" required>
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <span class="see-password" id="passEye"><img src="../img/passwordMata.png"></span>
                </div>
                <button type="submit">Sign Up</button>
            </form>
            <p class="signin-text">Already have an account? <a href="login.php">Sign In</a></p>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const urlPar = new URLSearchParams(window.location.search);
            const status = urlPar.get('status');

            if (status === 'success') {
                alert('Registration successful! Redirecting to login page.');
                window.location.href = 'login.php';
            } else if (status === 'failed') {
                alert("Registration Failed, Username is already taken");
            }
        });

        document.querySelector("#passEye").addEventListener("click", function () {
            const password = document.querySelector("#password");
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
        });
    </script>
</body>
</html>
