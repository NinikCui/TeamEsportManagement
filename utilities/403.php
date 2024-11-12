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
        }
        .container{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            height: 100vh;

            
        }
        .info-1{
            font-size: 300px;
            margin: 0;
        }
        .info-2{
            font-size: 50px;
            margin: 0;

        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="info-1">403 </h1>
        <H1 class="info-2">FORBIDDEN</H1>
    </div>
    
</body>
</html>