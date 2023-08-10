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
            $hash = (mysqli_fetch_assoc($result))['pwd'];
            if (password_verify($pwd, $hash)){
                $_SESSION['connected'] = true;
                $_SESSION['username'] = $username;
                header('Location: ../');
            } else {
                echo 'ur a bloody mf';
            }
        }
    }
}

$rand = random_int(1,10000);


?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="./login.css?refreshthing=<?= $rand ?>">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="./login.js?refreshthing=<?= $rand ?>" defer></script>
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
                <div class="field">
                    <input type="text" name="user" placeholder="<?= returnText('username', $lang, $textData) ?>">
                </div>
                <div class="field">
                    <input type="password" name="pwd" placeholder="<?= returnText('password', $lang, $textData) ?>">
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

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script> -->
</body>
</html>