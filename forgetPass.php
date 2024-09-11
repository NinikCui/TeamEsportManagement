<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: 'Poppins', sans-serif;
    }

    body {
        background-color: #FAFAFA;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .container {
        display: flex;
        width: 60%;
        background-color: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
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
    .password-container input[type="text"]  {
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
        background-image: url('resource/login.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    .image-container img {
        width: 100%;
        height: 100%; 
        object-fit: cover; 
        display: block; 
    }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Hello!</h1>
            <h1>Reset Password</h1>
            <p>Dont Forget Your Password</p>
            <form action="" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder=" New Password" required>
                    <span class="see-password" id="passEye"><img src="resource/passwordMata.png"></span>
                </div>
                <div class="password-container">
                    <input type="password" id="confPassword" name="confPassword" placeholder="Confirm Password" required>
                    <span class="see-password" id="passEyeConfirm"><img src="resource/passwordMata.png"></span>
                </div>
                <button type="submit">Reset</button>
            </form>
        </div>
        <div class="image-container">
           <img src="resource/frgtPass.jpg">
        </div>
    </div>
    <script>
        const info = document.querySelector("#passEye");
        const password = document.querySelector("#password");

        info.addEventListener("click", function () {
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
        });

        const infoConf = document.querySelector("#passEyeConfirm");
        const passwordConf = document.querySelector("#confPassword");

        infoConf.addEventListener("click", function () {
            const typeConf = passwordConf.getAttribute("type") === "password" ? "text" : "password";
            passwordConf.setAttribute("type", typeConf);
        });

        resetForm.addEventListener("submit", function(event) {
        if (password.value !== passwordConf.value) {
            event.preventDefault(); 
            alert("Password tidak memiliki kesamaan");  
        } 
    });


    </script>
</body>
</html>
