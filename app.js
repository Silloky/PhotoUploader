/// <reference path="./lib/jquery.js" />

function addPhotoBlock(file){
    var uuid = crypto.randomUUID()
    file.uuid = uuid
    var url = URL.createObjectURL(file.fileObject)
    var date = file.lastModifiedDate.toLocaleDateString([], {year: '2-digit', month: '2-digit', day: '2-digit'})
    var time = file.lastModifiedDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})
    var html = `
    <div class="photoblock" uuid="${uuid}">
            <img src="${url}" alt="">
            <span>${file.name}</span>
            <span>${date} ${time}</span>
    </div>
    `
    photosBar.append(html)
    $("#dnd").hide()
    $(".big-text").show()
    if (selectorRunning == false){
        $("#photos").multiSelector({
            selector: ".photoblock", 
            selectedElementClass: "selected",
            onSelectionEnd: function(list) {
                refreshEditor(list)
            }
        })
        selectorRunning = true
    }
}


async function refreshEditor(list) {
    if (nullEditor){
        $("#editor-null").hide()
        $("#real-editor").show()
        nullEditor = false
    }
    $("#preview").children("img").remove()
    $("#folder-selector").children().remove()
    var currentlyEditingBlocks = list
    var currentlyEditing = Array()
    currentlyEditingBlocks.each(function() {
        var current = photos.find(photo => photo.uuid === $(this).attr('uuid'))
        current.availableListObj = $(this)
        currentlyEditing.push(current)
        // console.log(currentlyEditing)
        var html = `<img src="${URL.createObjectURL(current.fileObject)}" alt="${current.name}" uuid=${current.uuid}>`
        // console.log(html)
        current.editingListObj = $("#preview").append(html).children(":last-child")
    })
    await initTree()
    $("#folder-selector li.icon").on('dblclick', function() {
        $(this).children("ul.nested").show()
        $(this).css('list-style', '\'\\e2c8\  \'')
    })
}

function removeSelectedPhotos() {
    const currentlySelected = $("#photos").multiSelector('get')
    currentlySelected.each(function() {
        $(this).remove()
        photos = photos.filter(obj => {
            return obj.uuid !== $(this).attr('uuid')
        })
    })
    if (photos.length == 0){
        $("#dnd").show()
        $(".big-text").hide()
        if (selectorRunning == false){
            $("#photos").multiSelector('unbind')
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
}

async function initTree() {
    await $.ajax({
        url: "./api/getStructure.php",
        type: 'GET',
        dataType: 'json',
        async: true,
        success: function(structure) {
            structure.children.forEach(folder => {
                initTreeFolderChildren(folder,$("#folder-selector"))
            })
        }
    })
}

function initTreeFolderChildren(folder,currentParentUl){
    var currentThing = currentParentUl.append(`<li><span>${folder.name}</span></li>`).children(":last-child")
    if (folder.children.length != 0){
        currentThing.addClass('icon')
        currentThing.css('list-style', '\'\\e2c7\  \'')
        // currentThing.addClass('material-symbols-rounded')
        var nextParent = currentThing.append(`<ul class="nested"></ul>`).children(":last-child")
        folder.children.forEach(folder => {
            initTreeFolderChildren(folder,nextParent)
        })
    }
}

// $(window).on('load',function() {
//     $('.material-icons-rounded').css('display','inline');
// });

let photos = Array()
var photosBar = $("#photos")
var selectorRunning = false
var nullEditor = true

$("#photo-panel-big").hide();

$("#dnd").on('drag dragstart dragend dragover dragenter dragleave drop', function (e) {
    e.preventDefault();
    e.stopPropagation();
});

$("#dnd").on('drop', function (e) {
    var justImportedPhotos = Array.from(e.originalEvent.dataTransfer.files)
    justImportedPhotos.forEach(file => {
        var jsonFile = convertFileToObject(file)
        photos.push(jsonFile)
        addPhotoBlock(jsonFile)
    })
});

$("#filebrowser")[0].onchange = evt => {
    var justImportedPhotos = Array.from($("#filebrowser")[0].files)
    justImportedPhotos.forEach(file => {
        var jsonFile = convertFileToObject(file)
        photos.push(jsonFile)
        addPhotoBlock(jsonFile)
    })
}