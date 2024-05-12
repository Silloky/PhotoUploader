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

async function uploadPhoto(photo){
    setText(photo.uuid, 'Uploading')
    $.ajax({
        xhr: function(){
            var xhr = new window.XMLHttpRequest()
            xhr.upload.addEventListener('progress', function(evt){
                if (evt.lengthComputable) {
                    var percentComplete = (evt.loaded / evt.total * 0.88);
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
        },
        error: async function(jqxhr){
            if (jqxhr.status == 401){
                await reauth()
                $.ajax(this)
            }
        }
    })
}

function processPhoto(uuid){
    var sse = new EventSource('./api/processPhoto.php?uuid=' + encodeURIComponent(uuid))
    sse.addEventListener('message', function(evt){
        var response = JSON.parse(evt.data)
        setText(uuid, response.message)
        var value = $(`.photo[uuid=${uuid}]`).find(".progress").circleProgress('value');
        if (response.type == 'info'){
            console.log(response.complex_message)
            $(`.photo[uuid=${uuid}]`).find(".progress").circleProgress('value', value + 0.02)
        } else if (response.type == 'error'){
            console.error(response.complex_message)
            $(`.photo[uuid=${uuid}]`).find(".percentage").addClass('material-symbols-rounded').addClass('symbol')
            $(`.photo[uuid=${uuid}]`).find(".progress").circleProgress('value', value)
            $(`.photo[uuid=${uuid}]`).find(".progress").circleProgress({value: value, fill: {color: errorColor}})
            $(`.photo[uuid=${uuid}]`).find(".percentage").addClass('error')
            $(`.photo[uuid=${uuid}]`).find(".percentage").html("warning")
        }
    })
    sse.addEventListener('close', function(evt){
        sse.close()
        setTimeout(function(){
            if ($(`.photo[uuid=${uuid}]`).find(".percentage").text() != "warning"){
                setText(uuid, 'Done !')
                $(`.photo[uuid=${uuid}]`).find(".percentage").addClass('material-symbols-rounded').addClass('symbol')
                $(`.photo[uuid=${uuid}]`).find(".percentage").addClass('success')
                $(`.photo[uuid=${uuid}]`).find(".percentage").html("done_all")
            } else {
                wasUploadError = true
            }
            if (noMoreToUpload){
                if (!wasUploadError){
                    var endToast = {
                        type: 'success',
                        message: 'All photos were successfully uploaded ! Redirecting you in 10 seconds...',
                        complex_message: 'Success : all photos were correctly uploaded and processed. User will be redirected to initial "editing" page in 10 seconds...'
                    }
                    showToast(endToast)
                }
                // logoutCount = 0
                $.ajax({
                    url: './api/emptySessionPhotos.php',
                    type: 'GET',
                    success: function(){
                        setTimeout(function(){
                            window.location.reload()
                        }, 10000)
                    }
                })
            } else if (typeof photos[uploadedIndex] !== 'undefined'){
                uploadPhoto(photos[uploadedIndex])
                uploadedIndex++
            } else {
                noMoreToUpload = true
            }
            $($(`.photo[uuid=${uuid}]`).find(".progress").circleProgress('widget')).stop();
        }, 3000)
    })
}

function tempBack(){
    $.ajax({
        url: './api/emptySessionPhotos.php',
        type: 'GET'
    })
    changeStep('confirmation')
}

var wallDiv = $("#wall")
var emptyColor = getComputedStyle(document.documentElement).getPropertyValue('--border-color')
var successColor = getComputedStyle(document.documentElement).getPropertyValue('--success-toast-color')
var errorColor = getComputedStyle(document.documentElement).getPropertyValue('--error-toast-color')
var maxSimultaneousUploads = 2
var uploadedIndex = 0
var noMoreToUpload = false
var wasUploadError = false

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

setTimeout(function(){
    for (let i = 0; i < maxSimultaneousUploads; i++) {
        if (typeof photos[uploadedIndex] !== 'undefined'){
            uploadPhoto(photos[uploadedIndex])
            uploadedIndex++
        } else {
            noMoreToUpload = true
        }
    }
}, 4000)