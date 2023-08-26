var wallDiv = $("#wall")
var emptyColor = getComputedStyle(document.documentElement).getPropertyValue('--border-color')
var successColor = getComputedStyle(document.documentElement).getPropertyValue('--success-toast-color')
var errorColor = getComputedStyle(document.documentElement).getPropertyValue('--error-toast-color')

function initBars(){
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
}

function setText(uuid, text){
    $(`.photo[uuid=${uuid}]`).find(".status").text(text)
}

function uploadPhoto(photo){
    setText(photo.uuid, 'Uploading')
    $.ajax({
        xhr: function(){
            var xhr = new window.XMLHttpRequest()
            xhr.upload.addEventListener('progress', function(evt){
                if (evt.lengthComputable) {
                    var percentComplete = (evt.loaded / evt.total);
                    $(`.photo[uuid=${photo.uuid}]`).find(".progress").circleProgress('value', percentComplete)
                }
            }, false)
            return xhr
        },
        type: 'POST',
        url: './api/receivePhoto.php',
        data: {
            photo: JSON.stringify(photo)
        },
        dataType: 'json',
        success: function(res){
            setText(photo.uuid, res.message)
            if (res.type == 'error'){
                var value = $(`.photo[uuid=${photo.uuid}]`).find(".progress").circleProgress('value');
                $(`.photo[uuid=${photo.uuid}]`).find(".percentage").addClass('material-symbols-rounded').addClass('symbol')
                $(`.photo[uuid=${photo.uuid}]`).find(".progress").circleProgress('value', value)
                $(`.photo[uuid=${photo.uuid}]`).find(".progress").circleProgress({value: 1, fill: {color: errorColor}})
                $(`.photo[uuid=${photo.uuid}]`).find(".percentage").addClass('error')
                $(`.photo[uuid=${photo.uuid}]`).find(".percentage").text("warning")
            } else if (res.type = 'success'){
                processPhoto(photo.uuid)
            }
        }
    })
}

function processPhoto(uuid){
    var sse = new EventSource('./api/processPhoto.php?uuid=' + encodeURIComponent(uuid))
    sse.addEventListener('message', function(evt){
        var response = JSON.parse(evt.data)
        if (response.type = 'info'){
            console.log(response.complex_message)
            setText(uuid, response.message)
        }
    })
    sse.addEventListener('close', function(evt){
        sse.close()
    })
}

function tempBack(){
    $.ajax({
        url: './api/emptySessionPhotos.php',
        type: 'GET'
    })
    changeStep('confirmation')
}

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

initBars()