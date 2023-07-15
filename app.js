function addPhotoBlock(){
    
}

// $(window).on('load',function() {
//     $('.material-icons-rounded').css('display','inline');
// });

$("#filebrowser")[0].onchange = evt => {
    Array.from($("#filebrowser")[0].files).forEach(file => {
        console.log(file.name)
    })
}