<?php

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

function returnText($sentenceID, $lang, $data){
    foreach ($data as $textElement){
        if ($textElement['sentenceID'] == $sentenceID){
            break;
        }
    }
    return $textElement[$lang];
}

$textData = json_decode(file_get_contents('./text.json'), true);

if (isset($_COOKIE['lang'])){
    $lang = $_COOKIE['lang'];
} else {
    setcookie('lang', 'english', strtotime("+1 year"), '../');
    header("Refresh:0");
}

if (isset($_POST)){
    if (isset($_POST['action'])){
        if ($_POST['action'] == 'signin'){
            $username = $_POST['user'];
            $pwd = $_POST['pwd'];

            require('../dbconfig.php');
            $sql = "SELECT * FROM users WHERE username='$username'";
            $result = mysqli_query($conn,$sql);
            if ($result->num_rows > 0){
                $hash = (mysqli_fetch_assoc($result))['pwd'];
                if (password_verify($pwd, $hash)){
                    $_SESSION['connected'] = true;
                    $_SESSION['username'] = $username;
                    header('Location: ../');
                }
            } else {
                $toastContents = json_encode(Array(
                    'type' => 'error',
                    'message' => 'Incorrect username/password !',
                    'complex_message' => 'User entered non-corresponding username-password combination.'
                ));
            }
        }
    }
}


?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../modules/palettes/colors.css">
    <link rel="stylesheet" href="./login.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded&display=block:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="./login.js" defer></script>
    <script src="../lib/jquery.js"></script>
</head>
<body>

    <main>
        <div id="text">
            <p id="welcome"><?= returnText('welcome', $lang, $textData) ?></p>
            <p id="please"><?= returnText('please-log-in', $lang, $textData) ?></p>
        </div>

        <form action="./index.php" method="post">
            <div id="fields">
                <div class="text-field">
                    <input type="text" name="user" value="" placeholder=" ">
                    <span class="placeholder"><?= returnText('username', $lang, $textData) ?></span>
                    <p class="error-text"></p>
                </div>
                <div class="text-field">
                    <input type="text" name="pwd" value="" placeholder=" ">
                    <span class="placeholder"><?= returnText('password', $lang, $textData) ?></span>
                    <p class="error-text"></p>
                </div>
            </div>
            <button type="submit" value="signin" name="action"><?= returnText('log-in-btn', $lang, $textData) ?></button>
        </form>
    </main>

    <div id="language-selector">
        <div class="flag" onclick="changeLanguage($(this).children().attr('alt'))">
            <img src="https://upload.wikimedia.org/wikipedia/commons/c/c3/Flag_of_France.svg" alt="french">
        </div>
        <div class="flag" onclick="changeLanguage($(this).children().attr('alt'))">
            <img src="https://upload.wikimedia.org/wikipedia/commons/8/83/Flag_of_the_United_Kingdom_%283-5%29.svg" alt="english">
        </div>
    </div>

    <?php 
        include('../modules/toasts/toasts.php');
        if (isset($toastContents)) :
    ?>
        <script>showToast(<?= $toastContents ?>)</script>
    <?php endif; ?>

</body>
</html>