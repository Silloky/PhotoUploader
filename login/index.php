<?php

if(session_status() === PHP_SESSION_NONE){
    session_start();
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
                echo 'ur a bloody gay';
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="./login.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="./login.js" defer></script>
</head>
<body>

    <main>
        <div id="text">
            <p id="welcome">Welcome !</p>
            <p id="please">Please log in</p>
        </div>

        <form action="./index.php" method="post">
            <div id="fields">
                <div class="field">
                    <input type="text" name="user" placeholder="Username">
                </div>
                <div class="field">
                    <input type="password" name="pwd" placeholder="Password">
                </div>
            </div>
            <button type="submit" value="signin" name="action">Sign in</button>
        </form>
    </main>

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script> -->
</body>
</html>