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