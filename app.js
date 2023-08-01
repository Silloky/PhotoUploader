/// <reference path="./lib/jquery.js"/>

function addPhotoBlock(file){
    var uuid = crypto.randomUUID() // creates a unique id for the imported photo (cannot rely on name as name might change during process)
    file.uuid = uuid
    var url = URL.createObjectURL(file.fileObject) // creates a temp url for the img preview
    var date = file.lastModifiedDate.toLocaleDateString([], {year: '2-digit', month: '2-digit', day: '2-digit'}) // formats date...
    var time = file.lastModifiedDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})                 // and time
    var html = `
    <div class="photoblock" uuid="${uuid}">
            <img src="${url}" alt="">
            <span class="filename">${file.name}</span>
            <span class="datetime">${date} ${time}</span>
    </div>
    ` // structure for 'Available Photos' pane
    photosBar.append(html)
    $("#dnd").hide() // removes the drag and drap area
    $(".big-text").show() // shows the 'Photos Available' text
    if (selectorRunning == false){
        $("#photos").multiSelector({
            selector: ".photoblock", 
            selectedElementClass: "selected", // 'selected' is the name of the class given the selected photoblocks
            onSelectionEnd: function(list) {
                refreshEditor(list) // callback is refreshEditor with list of currently selected photos list
            }
        }) // starts the multi-selector
        selectorRunning = true
    }
}


function refreshEditor(list) {
    if (nullEditor){
        $("#editor-null").hide() // hides the default editor pane
        $("#real-editor").show() // and shows the actual useful one
        nullEditor = false
    }
    $("#preview").children("img").remove() // empties the preview pane
    $("#folder-selector").children().remove() // empties the directory selector
    $("#date-selector").remove() // removes the fancy date selector
    $("input[name='filename']").val('')
    $("input[name='filename']").off()
    $("#dateinput").off()
    $("input[name='placename']").off()
    $("#no-search").show()
    $("ul#results").hide()
    $("ul#results").children().remove()
    var currentlyEditingBlocks = list // array of DOM elements
    if (currentlyEditing != []){
        currentlyEditing = Array() // complex object
    }
    currentlyEditingBlocks.each(function() {
        var current = photos.find(photo => photo.uuid === $(this).attr('uuid')) // gets the corresponding photo in the imported photos list
        current.availableListObj = $(this) // sets the availableListObj property of the object to be the DOM of the photoblock in the 'Available Photos' list
        currentlyEditing.push(current)
        // console.log(currentlyEditing)
        var html = `<img src="${URL.createObjectURL(current.fileObject)}" alt="${current.name}" uuid=${current.uuid}>`
        // console.log(html)
        current.editingListObj = $("#preview").append(html).children(":last-child") // sets the editingListObj property of the object to be the DOM of the preview block in the 'Currently Edidting' pane
    })
    $("input[name='placename']").on('keydown keyup', function(){
        updatePlaceSearchResults(this.value)
    })
    setOriginalPhotosData()
    initTree().then(function(){
        startSavingListeners()
    }) // inits the directory structure selector
    var dateinput = $("#dateinput") // input with date in text form
    $.dateSelect.show({ // starts the date selector
        element: dateinput, // binds it to the input
        container: '#date-section' // sets #date-section as its parent
    });
    $("#date-overlay").on('click', function(){
        $("#date-overlay").hide()
    })
    dateinput.trigger('click') // clicks the input to show the selector
    if (!mapLoaded){
        var map = L.map('map').setView([46.767709937459294, 2.43165930707079], 5.5)
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map)
        map.on('click', function(e){placeMarker(e,map)})
        mapLoaded = true
    }
    startSavingListeners()
}

function setOriginalPhotosData() {
    var names = []
    var dates = []
    var queries = []
    var gpsLocations = []
    currentlyEditing.forEach(photo => {
        names.push(photo.name)
        dates.push(photo.lastModifiedDate.toLocaleDateString("fr-FR"))
        queries.push(photo.placeSearchQuery)
        gpsLocations.push(photo.gpsLocation)
    })

    var simpleNames = []
    if (names.length == 1){
        simpleNames.push(names[0].match(/([a-zA-Z0-9_\s])*/)[0].slice(0, -1))
    } else {
        names.forEach(name => {
            var sequentialResults = name.match(/(([a-zA-Z0-9\s_-]+)\s\(\d\).([a-zA-Z]{3,4}))/)
            if (sequentialResults != null){
                simpleNames.push(sequentialResults[2])
            } else {
                simpleNames.push('no match')
            }
        })
    }
    if (simpleNames.every((val, i, arr) => val === arr[0]) && simpleNames[0] != 'no match'){
        $("input[name='filename']").val(simpleNames[0])
    } else {
        var toastData = {
            type: 'info',
            message: 'Selected photos have different, unordered names',
            complex_message: 'User-selected photos have different, unordered and with no apprent logic names. Showing \'Multiple values\' in input'
        }
        $("input[name='filename']").val('Multiple values')
        showToast(toastData)
    }

    var dateinput = $("#dateinput")
    if (dates.every((val, i, arr) => val === arr[0])){
        dateinput.val(dates[0])
        $("#date-information").hide()
    } else {
        dateinput.val('01/01/1970')
        $("#date-information").show()
    }

    if (queries.every((val, i, arr) => val === arr[0]) && gpsLocations.every((val, i, arr) => val === arr[0])){
        $("input[name='placename']").val(queries[0])
        updatePlaceSearchResults(queries[0], currentlyEditing).then(function(){
            console.log($("ul#results").children(`.place-search-result[placeid="${gpsLocations[0]}"]`))
            $("ul#results").children(`.place-search-result[placeid="${gpsLocations[0]}"]`).addClass('selected-place')
        })
    } else {
        $("input[name='placename']").val('Multiple values')
    }


}

function startSavingListeners(){
    $("input[name='filename']").on('keydown keyup', function() {
        var name = $(this).val()
        if (name != ''){
            let i = 1
            currentlyEditing.forEach(photo => {
                if (currentlyEditing.length == 1){
                    photo.name = name + mimeToExtension(photo)
                } else {
                    photo.name = name + ' (' + i + ')' + mimeToExtension(photo)
                    i++
                }
                photo.availableListObj.children(".filename").text(photo.name)
            })
        }
    })
    $("#dateinput").on('change', function(){
        var date = $.dateSelect.defaults.parseDate($(this).val())
        currentlyEditing.forEach(photo => {
            const originalDate = photo.lastModifiedDate
            date.setHours(originalDate.getHours());
            date.setMinutes(originalDate.getMinutes());
            date.setSeconds(originalDate.getSeconds());
            date.setMilliseconds(originalDate.getMilliseconds());
            photo.lastModifiedDate = date
            photo.lastModified = date.getTime()
            var dateToShow = date.toLocaleDateString([], {year: '2-digit', month: '2-digit', day: '2-digit'}) // formats date...
            var timeToShow = date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})                 // and time
            photo.availableListObj.children(".datetime").text(dateToShow + ' ' + timeToShow)
        })
    })
    $(".folder-block").on('click', function(e){
        e.stopPropagation()
        $("#folder-selector").find(".selected-folder").toggleClass("selected-folder") // removes the selected-folder class from previously selected folders
        $(this).addClass("selected-folder") // adds the selected-folder class to the current folder
    })
}

function savePhotosLocation(data, query){
    currentlyEditing.forEach(photo => {
        photo.gpsLocation = data
        photo.placeSearchQuery = query
    })
}

function mimeToExtension(file){
    if (file.type == 'image/jpeg'){
        return '.jpg'
    } else if (file.type == 'image/png'){
        return '.png'
    } else if (file.type == 'image/webp'){
        return '.webpg'
    } else if (file.type == 'image/svg+xml'){
        return '.svg'
    } else if (file.type == 'image/gif'){
        return '.gif'
    } else if (file.type == 'image/avif'){
        return '.avif'
    } else if (file.type == 'image/apng'){
        return '.apng'
    }
}

async function newFolder(button){
    var ul = button.parent().parent().siblings("ul.nested") // gets the current folder's ul
    ul.show() // shows it (expands folder)
    button.parent().siblings(".folder-name").css('list-style', '\'\\e2c8\  \'') // sets the folde ricon to be 'open'
    ul.append('<li><div class="folder-block"><input type="text" name="newfoldername" placeholder="New Folder Name"></div></li>').focus().on('keyup', function(e) { // creates input for user to enter new folder name, and binds to keyup
        if (e.which == 13) { // if the key is Enter
            var newName = $(this).find("input").val() // gets the name of the new folder
            ul.children(":last-child").remove() // removes the input
            var parentNames = Array()
            parentNames.push(button.parent().siblings("span").text()) // pushes the name of the current folder (which will be the parent)
            var previousParent = button.parent().parent() // sets the previous folderblock in hierarchy
            do {
                var currentParent = previousParent.parent().parent()
                if (currentParent[0] != $("#folder-selector")[0]){
                    parentNames.push(currentParent.siblings(".folder-block").children("span").text()) // gets the name of the parent
                    previousParent = currentParent
                } else {
                    break
                }
                //loops
            } while (currentParent.parent().parent()[0] != $("#folder-selector")[0]); // while the grandparent isn't #folder-selector
            var path = parentNames.reverse().join('/') + "/" + newName // join all the parents with '/' and append the new name
            postFolderCreation(path).then(res => { // send the path to ./api/createDirectory.php and wait for response
                showToast(res) // shows toast, logs response
                initTree() // reinit the folder selector
            })
        }
    })
}

function postFolderCreation(path){
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: "./api/createDirectory.php",
            type: 'POST',
            data: {
                path: path // sends a post request to ./api/createDirectory.php with the path as data ; this creates the folder and then responds in toast data form
            },
            async: true,
            success: function(res) {
                resolve(res)
            }
        })
    })
}

function removeFolder(button){
    var parentNames = Array()
    parentNames.push(button.parent().siblings("span").text())
    var previousParent = button.parent().parent()
    do {
        var currentParent = previousParent.parent().parent()
        if (currentParent[0] != $("#folder-selector")[0]){
            parentNames.push(currentParent.siblings(".folder-block").children("span").text())
            previousParent = currentParent
        } else {
            break
        }
        // same looping as createFolder()
    } while (currentParent.parent().parent()[0] != $("#folder-selector")[0]);
    var path = parentNames.reverse().join('/')
    postFolderDeletion(path).then(res => {
        showToast(res)
        initTree()
    })
    // same logic than createFolder()
}

function postFolderDeletion(path){
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: "./api/removeDirectory.php",
            type: 'POST',
            data: {
                path: path // sends a post request to ./api/removeDirectory.php with the path as data ; this removes the folder and then responds in toast data form
            },
            async: true,
            success: function(res) {
                resolve(res)
            }
        })
    })
}

function removeSelectedPhotos() {
    const currentlySelected = $("#photos").multiSelector('get') // gets the currently selected photos from the multiselector lib
    currentlySelected.each(function() { // for each poto block selected
        $(this).remove() // removes the photoblock from 'Available Photos' list
        photos = photos.filter(obj => {
            return obj.uuid !== $(this).attr('uuid') // removes the photo from the photos array
        })
    })
    if (photos.length == 0){ // in the case where we removed the last photo left
        $("#dnd").show() // shows the drag n drop area
        $(".big-text").hide() // hides 'Available Photos'
        if (selectorRunning == false){
            $("#photos").multiSelector('unbind') // stops the multiselector
        }
    }
}

function convertFileToObject(file) {
    return {
        'lastModified'  :   file.lastModified,
        'lastModifiedDate'  :   file.lastModifiedDate,
        'name'  :   file.name,
        'size'  :   file.size,
        'type'  :   file.type,
        'fileObject'    :   file
    }
    // File-type object properties are read-only.
    // so, to be able to modify them, this function copies the File object data to a plain object (read-write)
    // it also keeps the original File in the fileObject property
}

async function initTree() {
    await $.ajax({ 
        url: "./api/getStructure.php",
        type: 'GET', // sends a GET request to ./api/getStructure.php, which returns a JSON str
        dataType: 'json',
        async: true,
        success: function(structure) {
            $("#folder-selector").children().remove() // empties the directory selector
            structure.children.forEach(folder => {
                initTreeFolderChildren(folder,$("#folder-selector")) // runs the function on each root directory
            })
            $("#folder-selector li.icon").on('dblclick', function() {
                $(this).children("ul.nested").show() // expands folder on double-click
                $(this).css('list-style', '\'\\e2c8\  \'') // and sets 'open-folder' icon as list-style
            })
            $("#folder-selector li").on('mouseover', function(e){
                e.stopPropagation() // necessary
                $("#folder-selector").find("span:contains(\"cancel\")").hide() // when hoverring ele A, hides the folder-collapse button on hover on !A
            })
            $("#folder-selector li.icon").on('mouseover', function(e){
                e.stopPropagation()
                $("#folder-selector").find("span:contains(\"cancel\")").hide() // when hoverring ele A, hides the folder-collapse button on hover on !A
                if ($(this).children("ul.nested").is(":visible")){ // if it's expanded
                    $(this).children(".folder-block").children(".folder-options").children("span:contains(\"cancel\")").show() // shows the collapse button
                }
            })
            $("#folder-selector li.icon").on('mouseleave', function(e){
                $(this).eq(0).children(".folder-block").children(".folder-options").children("span:contains(\"cancel\")").hide() // hides the collapse button if mouse leaves
            })
            $(".folder-options span:contains(\"cancel\")").on('click', function(e){ // on click on collapse button
                e.stopPropagation()
                $(this).parent().parent().siblings("ul.nested").hide() // collapses
                $(this).parent().parent().parent().css('list-style', '\'\\e2c7\  \'') // changes list-style icon to indicate closed folder
            })
        }
    })
}

function initTreeFolderChildren(folder,currentParentUl){
    if (folder.children.length != 0){ // if the current folder has children
        var currentThing = currentParentUl.append(`<li class="icon"><div class="folder-block"><span class="folder-name">${folder.name}</span><div class="folder-options"><span class="material-symbols-rounded" style="display: none;">cancel</span><span class="material-symbols-rounded create_folder" onclick="newFolder($(this))">create_new_folder</span></div></div></li>`).children(":last-child") // creates the folder block and returns it
        currentThing.css('list-style', '\'\\e2c7\  \'') // sets icon
        var nextParent = currentThing.append(`<ul class="nested"></ul>`).children(":last-child") // adds ul where children will go
        folder.children.forEach(folder => {
            initTreeFolderChildren(folder,nextParent) // recursively runs it for each child
        })
    } else {
        var currentThing = currentParentUl.append(`<li><div class="folder-block"><span class="folder-name">${folder.name}</span><div class="folder-options"><span class="material-symbols-rounded create_folder" onclick="removeFolder($(this))">folder_delete</span><span class="material-symbols-rounded create_folder" onclick="newFolder($(this))">create_new_folder</span></div></div></li>`).children(":last-child")
        // creates a standalone folder block
    }
}

function getSearchResults(query){
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: "./api/searchPlaces.php",
            type: 'GET',
            data: {
                q: query
            },
            async: true,
            success: function(res) {
                resolve(res)
            }
        })
    })
}

async function updatePlaceSearchResults(query){
    if (query == ''){
        $("#no-search").show()
        $("#results").children().remove()
        $("#results").hide()
        previousResults = []
    } else {
        var results = await getSearchResults(query)
        if (results.length == 0){
            $("#no-results").show()
            $("#results").children().remove()
            $("#results").hide()
        } else {
            if (previousResults != results){
                $("#results").children().remove()
                results.forEach(place => {
                    var placeName = place.name_en
                    $("#results").append(
                        `<li class="place-search-result" placeid=${place.id}>
                            <span>${placeName}</span>
                        </li>`
                    )
                })
                $(".place-search-result").on('click', function(){
                    $(".place-search-result").removeClass('selected-place')
                    $(this).addClass('selected-place')
                    savePhotosLocation(currentlyEditing, $(this).attr('placeid'), query)
                })
                $("#search-result-box").children().hide()
                $("#results").show()
            }
        }
        previousResults = results
    }
}

function placeMarker(e, map){
    if (mapMarker != undefined){
        map.removeLayer(mapMarker)
    }
    mapMarker = new L.marker(e.latlng).addTo(map)
    $("#place-creator").show()
    addressInNewPlaceForm(e.latlng)
}

function addressInNewPlaceForm(latlng){
    var apiURL = `https://api.geoapify.com/v1/geocode/reverse?lat=${latlng.lat}&lon=${latlng.lng}&format=json&apiKey=${geoapifyKey}`
    $.ajax({
        url: apiURL,
        type: 'GET',
        success: function(res) {
            var result = res.results[0]
            if (result.result_type == 'street'){
                var addressLine1 = result.name
            } else if (result.result_type == 'building'){
                var addressLine1 = result.housenumber + ' ' + result.street
            }
            if (result.hamlet != undefined){
                var addressLine2 = result.hamlet
            }
            if (result.suburb != undefined){
                var addressLine2 = result.suburb
            }
            $("input[name=\"address-line-1\"]").val(addressLine1)
            $("input[name=\"address-line-2\"]").val(addressLine2)
            $("input[name=\"postal\"]").val(result.postcode)
            $("input[name=\"city\"]").val(result.city)
            $("input[name=\"country\"]").val(result.country)
        }
    })
}

$.fn.exists = function () {
    return this.length !== 0; // returns {if the specified element exists}
}

function showToast(res){
    if (!$(".visible-toast").exists()){ // gets if an element with class .visible-toast exists
        // if not :
        var toast = $(`#${res.type}-toast`) // loads right type toast
        // below, also logs the complex_message to the console
        if (res.type == 'error'){
            console.error(res.complex_message)
        } else if (res.type == 'info' || res.type == 'success'){
            console.info(res.complex_message)
        }
        toast.find(".toast-message").text(res.message) // fills in the message
        toast.find(".detailed-message").text(res.complex_message) // filles in the complex_message (hidden in DOM)
        toast.removeClass('hidden-toast') // Makes the toast
        toast.addClass('visible-toast')   // visible
    } else {
        // if yes :
        replaceToast(res) // allows for animation suppressing : much cleaner when changing toasts
    }
}

function replaceToast(res){
    var initialToast = $(".visible-toast")
    $("#toast-hider").show() // absolutely-positioned element that hides the animation process
    initialToast.removeClass('visible-toast') // hides the
    initialToast.addClass('hidden-toast')     // toast
    initialToast.find(".toast-message").text('') // empties the message
    initialToast.find(".detailed-message").text('') // empties the complex_message
    setTimeout(() => {
        $("#toast-hider").hide() // hide the toast hider
        showToast(res) // run showToast() with no existing .visible-toast
    }, 500) // after 0.5s
}

function hideToast(){
    var toast = $(".visible-toast")
    try {
        toast.removeClass('visible-toast') // hides
        toast.addClass('hidden-toast')     // the toast
        setTimeout(() => {
            toast.find(".toast-message").text('')
            toast.find(".detailed-message").text('')
        }, 500); // after 0.5s, waiting for the animation to finish before erasing toast contents
    }
    catch {}
}

async function copyToastMessage(button) {
    var firstLine = button.parent().siblings("div").children(".toast-message").text() // first line of text is the standard message
    var secondLine = button.parent().siblings("div").children(".detailed-message").text() // second line is complex_message
    var message = firstLine + "\n" + secondLine
    if (!navigator.clipboard) { // tests if Clipboard API is available
        var toastData = {
            type: 'error',
            message: "Couldn't copy : function not available",
            complex_message: "Copy error : Clipboard API not available in your browser. Please use a more modern browser."
        } // JSON data for showToast()
        showToast(toastData)
        return;
    }
    navigator.permissions.query({ 
        name: 'clipboard-write' // queries the clipboard-write permission from the browser
    }).then(result => {
        if (result.state === 'granted' || result.state === 'prompt') {
            navigator.clipboard.writeText(message) // copies multi-line message to clipboard if permission is granted (eitehr automatically, either with a prompt)
        } else {
            var toastData = {
                type: 'error',
                message: "Couldn't copy : permission denied",
                complex_message: "Copy error : permission 'clipboard-write' denied. Please allow access to clipboard."
            } // JSON data for showToast()
            showToast(toastData)
        }
    });
}

function filterNewFiles(files){
    var videos = []
    var audios = []
    var otherFiles = []
    files.forEach(file => {
        // for each file
        if (file.type.match(/image.*/)){
            var jsonFile = convertFileToObject(file) // converts read-only File Object to standard rw object
            photos.push(jsonFile) // pushes the rw photo data to the photos array
            addPhotoBlock(jsonFile) // adds the photoblock to the 'Available Photos' list
        } else if (file.type.match(/video.*/)){
            videos.push(file.name)
        } else if (file.type.match(/audio.*/)){
            audios.push(file.name)
        } else {
            otherFiles.push(file.name)
        }
    })
    if (videos.length > 0){
        var toastData = {
            type: 'info',
            message: 'You cannot import videos using this tool. Videos have been removed from list.',
            complex_message: 'User tried importing video files : ' + videos.toString() + '. Importing videos is not allowed.'
        }
        showToast(toastData)
    } else if (audios.length > 0){
        var toastData = {
            type: 'info',
            message: 'You cannot import audio files using this tool. Music has been removed from list.',
            complex_message: 'User tried importing video files : ' + audios.toString() + '. Importing audio is not allowed.'
        }
        showToast(toastData)
    } else if (otherFiles.length > 0){
        var toastData = {
            type: 'info',
            message: 'You cannot import non-media using this tool. These files have been removed from list.',
            complex_message: 'User tried importing non-media files : [' + otherFiles.toString() + ']. Importing non-media files is not allowed.'
        }
        showToast(toastData)
    }
}

let photos = Array()
var photosBar = $("#photos")
var selectorRunning = false
var currentlyEditing = Array()
var nullEditor = true
let previousResults = []
let mapLoaded = false
var mapMarker
const geoapifyKey = 'fe163ddccd36422baea56b816c3af0b6'
// inits variables

$("#photo-panel-big").hide(); // hides 'Available Photos'

setTimeout(() => {
    $("#toast-hider").hide()
}, 2000); // show the toast hider for 2 seconds (prevents flashing due to toast animation)

$("#dnd").on('drag dragstart dragend dragover dragenter dragleave drop', function (e) {
    e.preventDefault(); // prevents the default behaviour with drag n drop, which is to open the dropped object in a new tab
    e.stopPropagation();
});

$("#dnd").on('drop', function (e) {
    var justImportedPhotos = Array.from(e.originalEvent.dataTransfer.files) // gets the array of File Objects imported
    filterNewFiles(justImportedPhotos)
});

$("#filebrowser")[0].onchange = evt => {
    var justImportedPhotos = Array.from($("#filebrowser")[0].files) // handles files uploaded via the browser Open menu
    filterNewFiles(justImportedPhotos)
}