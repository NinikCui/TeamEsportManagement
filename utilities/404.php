<?php
// notfound.php
header("HTTP/1.0 404 Not Found");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HIKSROT</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link href="/ProjectFSP/resource/css/nav.css" rel="stylesheet">

    <style>
        body {
            background-image: url("/ProjectFSP/resource/img/BG.png");
            background-size: cover;
            background-position: center;
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            height: 100vh;
            padding: 20px;
        }
        .info-1 {
            font-size: 20vw; 
            margin: 0;
            line-height: 1;
        }
        .info-2 {
            font-size: 8vw; 
            margin: 0;
        }

        @media (max-width: 768px) {
            .info-1 {
                font-size: 40vw; 
            }
            .info-2 {
                font-size: 12vw;
            }
        }

        @media (max-width: 480px) {
            .info-1 {
                font-size: 40vw;
            }
            .info-2 {
                font-size: 10vw;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="info-1">404</h1>
        <h1 class="info-2">NOT FOUND</h1>
    </div>
</body>
</html>
