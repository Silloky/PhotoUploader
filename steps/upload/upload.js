var wallDiv = $("#wall")
var emptyColor = getComputedStyle(document.documentElement).getPropertyValue('--border-color')
var successColor = getComputedStyle(document.documentElement).getPropertyValue('--success-toast-color')
var errorColor = getComputedStyle(document.documentElement).getPropertyValue('--error-toast-color')

photos.forEach((photo, index) => {
    var uuid = photo.uuid
    var url = photo.data
    var html = 
    `<div class="photo" uuid="${uuid}">
        <div class="photo-preview">
            <img src="${url}" alt="">
        </div>
        <div class="upload-stats">
            <div class="progress">
                <span class="percentage"></span>
            </div>
            <span class="status">Pending</span>
        </div>
    </div>`
    wallDiv.append(html)
    // if (index != photos.length - 1){
    //     var html = '<div class="photo-spacer"></div>'
    //     wallDiv.append(html)
    // }
})

$('.progress').circleProgress({
    value: 0,
    fill: {
        color: successColor
    },
    lineCap: 'round',
    emptyFill: emptyColor,
    thickness: '10'
}).on('circle-animation-progress', function(event, progress, stepValue) {
    var value = parseInt(stepValue*100)
    $(this).find('span.percentage').html(value + '<i>%</i>')
})