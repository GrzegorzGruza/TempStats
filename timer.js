var time_start;
var start_mili;

function start_timer() {
    tiime_start = new Date();
    start_mili = time_start.getTime();
    odliczanie();
}


function odliczanie() {
    var teraz = new Date();
    teraz_mili = teraz.getTime();

    document.getElementById("zegar").innerHTML = teraz_mili - start_mili;

    setTimeout("odliczanie()", 1000);
}