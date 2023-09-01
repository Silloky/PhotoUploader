<?php

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

$basePath = '/media';

if (!(array_key_exists('connected',$_SESSION))){
    header('Location: ./login');
} else {
    if (!($_SESSION['connected'])){
        header('Location: ./login');
    }
}

if ($_COOKIE['lang'] == 'french'){
    $text = Array(
        'Nom du dossier à créer',
        'Valider'
    );
} else {
    $text = Array(
        'Folder Name',
        'Continue'
    );
}

if (isset($_POST['validateCreate'])){
    $path = $basePath . '/' . $_POST['foldername'];
    if (mkdir($path)){
        $toastContents = json_encode(Array(
            'type' => 'success',
            'message' => 'Folder successfully created',
            'complex_message' => 'The folder ' . $_POST['foldername'] . ' has been successfully created in ' . $basePath
        ));
    } else {
        $toastContents = json_encode(Array(
            'type' => 'error',
            'message' => 'Couldn\'t create folder',
            'complex_message' => 'The folder ' . $_POST['foldername'] . ' couldn\'t be created in ' . $basePath
        ));
    }
    unset($_POST['foldername']);
    unset($_POST['validateCreate']);

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer dossier racine</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="../modules/palettes/colors.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded&display=block:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="../lib/jquery.js"></script>
</head>
<body>
    <form action="./index.php" method="post">
        <div class="text-field">
            <input type="text" name="foldername" value="" placeholder=" " required>
            <span class="placeholder"><?= $text[0] ?></span>
            <p class="error-text"></p>
        </div>
        <div id="submit-wrapper">
            <button type="submit" value="create" name="validateCreate"><?= $text[1] ?></button>
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