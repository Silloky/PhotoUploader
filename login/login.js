function changeLanguage(newLanguage) {
    let language = newLanguage
    document.cookie = "lang=" + newLanguage + "; expires=" + new Date(new Date().setFullYear(new Date().getFullYear() + 1)) + "; path=/"
    window.location.reload()
}

async function login(){
    let username = $('input[name="user"]').val()
    let pwd = $('input[name="pwd"]').val()

    var res = await $.ajax({
        url: window.location.origin+"/api/auth.php",
        type: 'POST',
        data: {
            action: 'login',
            user: username,
            pwd: pwd
        },
        async: true,
        success: function(res){
            return res
        }
    })

    console.log(res)
    if (res.type == 'token'){
        document.cookie = "jwt="+encodeURIComponent(res.token)+"; secure; ; path=/"
        if (!modal) {window.location = "../"}
    } else {
        showToast(res.toastData)
    }
}

$('input[name="pwd"]').on("keyup", function(evt){
    if (evt.which == 13){
        evt.preventDefault()
        login()
    }
})