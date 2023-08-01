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
    <script src="./lib/jquery.js"></script>
    <script src="multiselector.min.js"></script>
    <script src="./lib/datepicker.js?refreshthing=<?= $rand ?>"></script>
    <script src="./lib/jquery.mousewheel.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <link rel="stylesheet" href="./style.css?refreshthing=<?= $rand ?>">
    <link rel="stylesheet" href="./lib/datepicker.css?refreshthing=<?= $rand ?>">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
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
                    <div id="pathselection">
                        <span>Please select target directory :</span>
                        <ul id="folder-selector">
                            
                        </ul>
                    </div>
                </div>
                <div style="height: 20px; visibility: hidden;"></div>
                <div id="metadata" class="editor-category">
                    <div class="editor-category-titles">Metadata</div>
                    <div id="date-section">
                        <input type="text" id="dateinput" style="display: none;">
                        <div class="element-information" style="display: none;">
                            <span class="material-symbols-rounded">info</span>
                            <span>Multiple values default to 01/01/1970</span>
                        </div>
                    </div>
                    <div id="location">
                        <div id="existing-place">
                            <div class="text-field">
                                <input type="text" name="placename" value="" placeholder=" ">
                                <span class="placeholder">Search for presaved place here</span>
                                <p class="error-text"></p>
                            </div>
                            <div id="search-result-box" class="scrollbarhidden">
                                <div id="no-search">
                                    <div class="search-null-box">
                                        <span class="material-symbols-rounded">north</span>
                                        <span>Search above the find pre-existing places</span>
                                    </div>
                                </div>
                                <ul id="results" style="display: none;">
                                </ul>
                                <div id="no-results" style="display: none;">
                                    <div class="search-null-box">
                                        <span class="material-symbols-rounded">not_listed_location</span>
                                        <span>Your search returned no results...</span>
                                        <span>Please search using other word or create a new location below.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span id="locationoptions-or">or create a new location</span>
                        <div id="new-place">
                            <div id="map"></div>
                            <div class="element-information">
                                <span class="material-symbols-rounded">info</span>
                                <span>Click on map to place marker</span>
                            </div>
                            <div id="place-creator" style="display: none;">
                                <div class="text-field">
                                    <input type="text" name="place-id" value="" placeholder=" ">
                                    <span class="placeholder">Place ID</span>
                                    <p class="error-text"></p>
                                </div>
                                <div class="text-field">
                                    <input type="text" name="place-name-en" value="" placeholder=" ">
                                    <span class="placeholder">Place Name (EN)</span>
                                    <p class="error-text"></p>
                                </div>
                                <div class="text-field">
                                    <input type="text" name="place-name-fr" value="" placeholder=" ">
                                    <span class="placeholder">Place Name (FR)</span>
                                    <p class="error-text"></p>
                                </div>
                                <div class="text-field">
                                    <input type="text" name="address-line-1" value="" placeholder=" ">
                                    <span class="placeholder">Address first line</span>
                                    <p class="error-text"></p>
                                </div>
                                <div class="text-field">
                                    <input type="text" name="address-line-2" value="" placeholder=" ">
                                    <span class="placeholder">Address second line</span>
                                    <p class="error-text"></p>
                                </div>
                                <div class="text-field">
                                    <input type="text" name="postal" value="" placeholder=" ">
                                    <span class="placeholder">Postal Code</span>
                                    <p class="error-text"></p>
                                </div>
                                <div class="text-field">
                                    <input type="text" name="city" value="" placeholder=" ">
                                    <span class="placeholder">City name</span>
                                    <p class="error-text"></p>
                                </div>
                                <div class="text-field">
                                    <input type="text" name="altitude" value="" placeholder=" ">
                                    <span class="placeholder">Altitude</span>
                                    <p class="error-text"></p>
                                </div>
                            </div>
                        </div>
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
    <div class="toast hidden-toast" id="error-toast">
        <span class="toast-icon material-symbols-rounded">warning</span>
        <div>
            <p class="toast-status">Error...</p>
            <p class="toast-message"></p>
            <p class="detailed-message"></p>
        </div>
        <div class="actions">
            <span class="material-symbols-rounded" onclick="hideToast()">close</span>
            <span class="material-symbols-rounded" onclick="copyToastMessage($(this))">content_copy</span>
        </div>
    </div>
    <div class="toast hidden-toast" id="success-toast">
        <span class="toast-icon material-symbols-rounded">done_all</span>
        <div>
            <p class="toast-status">Success !</p>
            <p class="toast-message"></p>
            <p class="detailed-message"></p>
        </div>
        <div class="actions">
            <span class="material-symbols-rounded" onclick="hideToast()">close</span>
            <span class="material-symbols-rounded" onclick="copyToastMessage($(this))">content_copy</span>
        </div>
    </div>
    <div class="toast hidden-toast" id="info-toast">
        <span class="toast-icon material-symbols-rounded">info</span>
        <div>
            <p class="toast-status">Information</p>
            <p class="toast-message"></p>
            <p class="detailed-message"></p>
        </div>
        <div class="actions">
            <span class="material-symbols-rounded" onclick="hideToast()">close</span>
            <span class="material-symbols-rounded" onclick="copyToastMessage($(this))">content_copy</span>
        </div>
    </div>
    <div id="toast-hider"></div>
</body>
</html>