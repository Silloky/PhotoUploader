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
                    <input type="text" name="pwd" placeholder="Password">
                </div>
            </div>
            <button type="submit">Sign in</button>
        </form>
    </main>

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script> -->
</body>
</html>