function showToast(res){
    if (!$(".visible-toast").exists()){ // gets if an element with class .visible-toast exists
        // if not :
        var toast = $(`#${res.type}-toast`) // loads right type toast
        // below, also logs the complex_message to the console
        if (res.type == 'error'){
            console.error(res.complex_message)
        } else if (res.type == 'info' || res.type == 'success'){
            console.info(res.complex_message)
        }
        toast.find(".toast-message").text(res.message) // fills in the message
        toast.find(".detailed-message").text(res.complex_message) // filles in the complex_message (hidden in DOM)
        toast.removeClass('hidden-toast') // Makes the toast
        toast.addClass('visible-toast')   // visible
    } else {
        // if yes :
        replaceToast(res) // allows for animation suppressing : much cleaner when changing toasts
    }
}

function replaceToast(res){
    var initialToast = $(".visible-toast")
    $("#toast-hider").show() // absolutely-positioned element that hides the animation process
    initialToast.removeClass('visible-toast') // hides the
    initialToast.addClass('hidden-toast')     // toast
    initialToast.find(".toast-message").text('') // empties the message
    initialToast.find(".detailed-message").text('') // empties the complex_message
    setTimeout(() => {
        $("#toast-hider").hide() // hide the toast hider
        showToast(res) // run showToast() with no existing .visible-toast
    }, 500) // after 0.5s
}

function hideToast(){
    var toast = $(".visible-toast")
    try {
        toast.removeClass('visible-toast') // hides
        toast.addClass('hidden-toast')     // the toast
        setTimeout(() => {
            toast.find(".toast-message").text('')
            toast.find(".detailed-message").text('')
        }, 500); // after 0.5s, waiting for the animation to finish before erasing toast contents
    }
    catch {}
}

async function copyToastMessage(button) {
    var firstLine = button.parent().siblings("div").children(".toast-message").text() // first line of text is the standard message
    var secondLine = button.parent().siblings("div").children(".detailed-message").text() // second line is complex_message
    var message = firstLine + "\n" + secondLine
    if (!navigator.clipboard) { // tests if Clipboard API is available
        var toastData = {
            type: 'error',
            message: "Couldn't copy : function not available",
            complex_message: "Copy error : Clipboard API not available in your browser. Please use a more modern browser."
        } // JSON data for showToast()
        showToast(toastData)
        return;
    }
    navigator.permissions.query({ 
        name: 'clipboard-write' // queries the clipboard-write permission from the browser
    }).then(result => {
        if (result.state === 'granted' || result.state === 'prompt') {
            navigator.clipboard.writeText(message) // copies multi-line message to clipboard if permission is granted (eitehr automatically, either with a prompt)
        } else {
            var toastData = {
                type: 'error',
                message: "Couldn't copy : permission denied",
                complex_message: "Copy error : permission 'clipboard-write' denied. Please allow access to clipboard."
            } // JSON data for showToast()
            showToast(toastData)
        }
    });
}