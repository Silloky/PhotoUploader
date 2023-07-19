function addPhotoBlock(file){
    var uuid = crypto.randomUUID()
    file.uuid = uuid
    var url = URL.createObjectURL(file)
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

function refreshEditor(list) {
    if (nullEditor){
        $("#editor-null").hide()
        $("#real-editor").show()
        nullEditor = false
    }
    $("#preview").children("img").remove()
    var currentlyEditingBlocks = list
    var currentlyEditing = Array()
    currentlyEditingBlocks.each(function() {
        var current = photos.find(photo => photo.uuid === $(this).attr('uuid'))
        current.jqueryObj = $(this)
        currentlyEditing.push(current)
        // console.log(currentlyEditing)
        var html = `<img src="${URL.createObjectURL(current)}" alt="${current.name}">`
        // console.log(html)
        $("#preview").append(html)
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
        photos.push(file)
        addPhotoBlock(file)
    })
});

$("#filebrowser")[0].onchange = evt => {
    var justImportedPhotos = Array.from($("#filebrowser")[0].files)
    justImportedPhotos.forEach(file => {
        photos.push(file)
        addPhotoBlock(file)
    })
}