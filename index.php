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

$rand = random_int(1,10000);

include_once('./api/getStructure.php');


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Photos</title>
    <script src="./app.js?refreshthing=<?= $rand ?>" defer></script>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css"> -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded&display=block:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="multiselector.min.js"></script>
    <script>var structure = <?= $structure ?>;</script>
    <link rel="stylesheet" href="./style.css?refreshthing=<?= $rand ?>">
</head>
<body>
    <div id="sidebar">
        <div id="photos" class="scrollbarhidden">
            <span class="big-text" style="display: none;">Available Photos</span>
            <div id="dnd">
                <span class="material-symbols-rounded bigicon">place_item</span>
                <span>Drag and Drop files here</span>
                <span id="dndor">or</span>
                <span>Click on 'Add photo(s)' below</span>
            </span>
            </div>
        </div>
        <ul id="controls">
            <input type="file" multiple id="filebrowser" style="display: none;">
            <li class="control" onclick="$('#filebrowser').click();">
                <span class="material-symbols-rounded">add_photo_alternate</span>
                <span>Add photo(s)</span>
            </li>
            <li class="control" onclick="removeSelectedPhotos()">
                <span class="material-symbols-rounded">delete_forever</span>
                <span>Remove selected photo(s)</span>
            </li>
            <li class="control">
                <span class="material-symbols-rounded">help</span>
                <span>Show help</span>
            </li>
        </ul>
    </div>
    <div id="editor-panel">
        <div id="editor-null">
            <span class="material-symbols-rounded bigicon">west</span>
            <span>Select photo(s) from opposite list</span>
            <span>to get started</span>
        </div>
        <!-- <img src="" alt="preview"> -->
        <div id="real-editor" style="display: none;">
            <div id="fields" class="scrollbarhidden">
            <span class="big-text">Editor</span>
                <div id="basic-information" class="editor-category">
                    <div class="editor-category-titles">Basic Information</div>
                    <div class="text-field">
                        <input type="text" name="filename" value="" placeholder=" ">
                        <span class="placeholder">File name</span>
                        <p class="error-text"></p>
                    </div>
                    <ul id="folder-selector">
                        
                    </ul>
                </div>
                <div style="height: 20px; visibility: hidden;"></div>
                <div id="metadata" class="editor-category">
                    <div class="editor-category-titles">Metadata</div>
                    <div id="map">
                        
                    </div>
                </div>
            </div>
            <div id="preview" class="scrollbarhidden">
                <span class="big-text">Currently editing</span>

            </div>
        </div>
    </div>
    <div id="bottom-bar">

    </div>
</body>
</html>