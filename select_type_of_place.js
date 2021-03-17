function change() {
    var type = document.getElementById("type_of_place").value;
    document.getElementById('place_select').setAttribute("list", type);
    var placeholder;

    if (type == "regiony") placeholder = "Wybierz region";
    else if (type == "panstwa") placeholder = "Wybierz państwo";
    else if (type == "stany") placeholder = "Wybierz stan";
    else if (type == "miasta") placeholder = "Wybierz miasto";
    else {
        document.getElementById('place_select').value = " --- ";
        document.getElementById('place_select').hidden = true;
        return;
    }

    document.getElementById('place_select').hidden = false;

    document.getElementById('place_select').setAttribute("placeholder", placeholder);
    document.getElementById('place_select').setAttribute("onblur", placeholder);
    document.getElementById('place_select').value = "";
}

window.onload = change();