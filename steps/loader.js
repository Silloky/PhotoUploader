if (sessionStorage.getItem('step') == null){
    sessionStorage.setItem('step', 'editing')
}
$("body").load('./steps/editing/editing.php')

function changeStep(step){
    sessionStorage.setItem('step', step)
    $("body").empty()
    var loadURL = './steps/' + step + '/' + step + '.php'
    $("body").load(loadURL)
}

window.onbeforeunload = function(event){
    if (logoutCount == 0){
        return 'Are you sure you want to reload ?'
    }
}

window.onunload = function(event){
    if (logoutCount == 0){
        $.ajax({
            url: './api/emptySessionPhotos.php',
            type: 'GET'
        })
    }
}