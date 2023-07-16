function addPhotoBlock(file){
    var url = URL.createObjectURL(file)
    var date = file.lastModifiedDate.toLocaleDateString([], {year: '2-digit', month: '2-digit', day: '2-digit'})
    var time = file.lastModifiedDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})
    var html = `
    <div class="photoblock">
            <img src="${url}" alt="">
            <span>${file.name}</span>
            <span>${date} ${time}</span>
    </div>
    `
    photosBar.append(html)
    $("#dnd").hide()
    $("#photo-panel-big").show()
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
    alert('Hello !')
    console.log(list)
}

function removeSelectedPhotos() {
    const currentlySelected = $("#photos").multiSelector('get')
    currentlySelected.each(function() {
        $(this).remove()
        photos = photos.filter(obj => {
            return obj.name !== $(this).children().eq(1).text()
        })
    })
    if (photos.length == 0){
        $("#dnd").show()
        $("#photo-panel-big").hide()
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