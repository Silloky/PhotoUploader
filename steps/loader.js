if (sessionStorage.getItem('step') == null){
    sessionStorage.setItem('step', 'editing')
}

async function cookieWatcher() {
    return await new Promise(resolve => {
        var lastCookie = document.cookie
        const interval = setInterval(() => {
            var currentCookie = document.cookie
            if (currentCookie != lastCookie){
                resolve()
                clearInterval(interval)
            }
        }, 500)
    })
}

async function reauth(){
    console.log("hello")
    $('body *').not('.toast, .toast *, #reauth, #reauth *').addClass('blur')
    $('#reauth').show()
    await cookieWatcher()
    $('#reauth').hide()
    $('#reauth input[type="text"]').val('')
    $('#reauth input[type="password"]').val('')
    $('body *').not('.toast, .toast *, #reauth, #reauth *').removeClass('blur')
}

const spinner = localStorage.getItem('spinner')

if (spinner == 1){
    $.get('./steps/editing/editing.php', function(data){
        $("body").append($(data).hide())
        setTimeout(function(){
            $("body").children().not("link, script, #toast-hider, #reauth").show()
            $("#loader").fadeTo("slow", 0, function(){
                $("#loader").hide()
            })
        },5000)
    }).fail(function(jqxhr){
        if (jqxhr.status == 401){
            window.location = "/login"
        }
    })
    
    function changeStep(step){
        sessionStorage.setItem('step', step)
        $("body").find('*').not("#loader, .spinner").remove()
        $("#loader").fadeTo('slow', 1, function(){
            var loadURL = './steps/' + step + '/' + step + '.php'
            $.get(loadURL, function(data){
                $("body").append($(data).hide())
                setTimeout(function(){
                    $("body").children().not("link, script, #toast-hider, #reauth").show()
                    $("#loader").fadeTo("slow", 0, function(){
                        $("#loader").hide()
                    })
                }, 3000)
            }).fail(function(jqxhr){
                if (jqxhr.status == 401){
                    window.location = "/login"
                }
            })
        })
    }

    window.onbeforeunload = function(event){
        if (logoutCount == 0){
            return 'Are you sure you want to reload ?'
        }
    }

} else {
    $.get('./steps/editing/editing.php', function(data){
        $("body").append($(data).hide())
        $("body").children().not("link, script, #toast-hider, #reauth").show()
        $("#loader").hide()
    }).fail(function(jqxhr){
        if (jqxhr.status == 401){
            window.location = "/login"
        }
    })
    
    function changeStep(step){
        sessionStorage.setItem('step', step)
        var loadURL = './steps/' + step + '/' + step + '.php'
        $.get(loadURL, function(data){
            $("body").append($(data).hide())
            $("body").children().not("link, script, #toast-hider, #reauth").show()
            $("#loader").hide()
        }).fail(function(jqxhr){
            if (jqxhr.status == 401){
                window.location = "/login"
            }
        })
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