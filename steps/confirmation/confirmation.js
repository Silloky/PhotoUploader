var listDiv = $("#list")
var toEditUUID = ''

function editUUID(uuid){
    toEditUUID = uuid
    changeStep('editing')
}

photos.forEach((photo, index) => {
    var uuid = photo.uuid
    var url = photo.data
    var name = photo.name
    var saveLocation = '/test/path/forthe/moment'
    var date = photo.lastModifiedDate.toLocaleString('fr-FR')
    var gpsLocation = photo.gpsLocation
    if (gpsLocation == undefined){
        gpsLocation = 'None'
    }
    var html = 
    `<div class="photo" uuid="${uuid}">
        <div class="photo-preview">
            <img src="${url}" alt="">
        </div>
        <div class="photo-data">
            <div>
                <span>Name</span>
                <span>${name}</span>
            </div>
            <div>
                <span>Save Location</span>
                <span>${saveLocation}</span>
            </div>
            <div>
                <span>Date & Time</span>
                <span>${date}</span>
            </div>
            <div>
                <span>GPS Location</span>
                <span>${gpsLocation}</span>
            </div>
        </div>
        <span class="material-symbols-rounded" class="edit-btn" onclick="editUUID('${uuid}')">edit</span>
    </div>`
    listDiv.append(html)
    if (index != photos.length - 1){
        var html = '<div class="photo-spacer"></div>'
        listDiv.append(html)
    }
})