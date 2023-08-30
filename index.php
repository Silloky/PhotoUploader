<?php

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

if (!(array_key_exists('connected',$_SESSION))){
    header('Location: ./login');
} else {
    if (!($_SESSION['connected'])){
        header('Location: ./login');
    }
}

if (!(array_key_exists('step', $_SESSION))){
    $_SESSION['step'] = 'editing';
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Photos</title>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css"> -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded&display=block:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="./lib/jquery.js"></script>
    <script src="./lib/jquery.mousewheel.min.js"></script>
    <script src="./steps/loader.js" defer></script>
    <link rel="stylesheet" href="./modules/palettes/colors.css">
    <link rel="stylesheet" href="./steps/common.css">
</head>
<body>
    <div id="loader">
        <div class="spinner"></div>
    </div>
</body>
</html>