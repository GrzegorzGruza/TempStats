// https://stackoverflow.com/questions/2259036/change-number-of-html-input-fields-with-javascript
// https://stackoverflow.com/questions/9444745/javascript-how-to-get-tomorrows-date-in-format-dd-mm-yy


Date.prototype.ddmmyyyy = function () {
    var mm = this.getMonth() + 1; // getMonth() is zero-based
    var dd = this.getDate();

    return [(dd > 9 ? '' : '0') + dd + '.',
        (mm > 9 ? '' : '0') + mm + '.',
        this.getFullYear()
    ].join('');
};

Date.prototype.yyyymmdd = function () {
    var mm = this.getMonth() + 1; // getMonth() is zero-based
    var dd = this.getDate();

    return [this.getFullYear() + '-',
        (mm > 9 ? '' : '0') + mm + '-',
        (dd > 9 ? '' : '0') + dd
    ].join('');
};


function removerows(tablebody) {
    var rows = tablebody.getElementsByTagName("tr");
    while (rows.length)
        rows[0].parentNode.removeChild(rows[0]);
}

function addrows(tablebody, n) {
    var da = new Date(document.getElementById("dataPocz").value);
    for (var i = 0; i < n; i++) {
        var row = document.createElement("tr");
        var titlecell = document.createElement("td");
        titlecell.appendChild(document.createTextNode(da.ddmmyyyy()));
        row.appendChild(titlecell);

        var cell = document.createElement("td");
        var input = document.createElement("input");
        input.setAttribute("type", "number");
        input.setAttribute("name", "row[" + i + "]");
        cell.appendChild(input);
        row.appendChild(cell);
        tablebody.appendChild(row);
        da.setDate(da.getDate() + 1);
    }
}

function change() {
    var dp = new Date(document.getElementById("dataPocz").value);
    var dk = new Date(document.getElementById("dataKon").value);
    var n = parseInt((dk - dp) / (24 * 3600 * 1000));
    var tablebody = document.getElementById("maintablebody");
    removerows(tablebody);
    addrows(tablebody, n + 1);
}

function start_fun() {
    document.getElementById('dataPocz').valueAsDate = new Date();
    document.getElementById('dataKon').valueAsDate = new Date();
    change();

    var today = new Date().yyyymmdd();

    document.getElementById("dataPocz").setAttribute("max", today);
    document.getElementById("dataKon").setAttribute("max", today);
}

window.onload = start_fun();