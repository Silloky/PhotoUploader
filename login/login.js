function changeLanguage(newLanguage) {
    let language = newLanguage
    document.cookie = "lang=" + newLanguage + "; expires=" + new Date(new Date().setFullYear(new Date().getFullYear() + 1)) + "; path=/"
    window.location.reload()
}