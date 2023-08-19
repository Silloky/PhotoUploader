<?php $relPath = substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT'])); ?>

<link rel="stylesheet" href="<?= $relPath . "/editing.css" ?>">
<script src="<?= $relPath . "/editing.js" ?>" defer></script>
<script src="<?= $relPath . "/../../lib/multiselector.min.js" ?>"></script>
<script src="<?= $relPath . "/../../lib/datepicker.js" ?>"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" href="<?= $relPath . "/../../lib/datepicker.css" ?>">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
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
        <li class="control" onclick="logout()" id="logout-control">
            <span class="material-symbols-rounded">logout</span>
            <span>Log out</span>
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
                                <input type="text" name="country" value="" placeholder=" ">
                                <span class="placeholder">Country</span>
                                <p class="error-text"></p>
                            </div>
                            <button type="submit" id="submit-new-place" onclick="submitNewPlace($(this).attr('lat'), $(this).attr('lng'))">Create place and select it</button>
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
    <button type="button" onclick="changeStep('confirmation')" class="disabled-btn" id="continue-btn">Continue</button>
    <button type="button" class="disabled-btn">Go back</button>
</div>
<?php
    include('../../modules/toasts/toasts.php');
?>