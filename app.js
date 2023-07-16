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

}

// $(window).on('load',function() {
//     $('.material-icons-rounded').css('display','inline');
// });

let photos = Array()
var photosBar = $("#photos")

$("#filebrowser")[0].onchange = evt => {
    var justImportedPhotos = Array.from($("#filebrowser")[0].files)
    justImportedPhotos.forEach(file => {
        photos.push(file)
        addPhotoBlock(file)
    })
}