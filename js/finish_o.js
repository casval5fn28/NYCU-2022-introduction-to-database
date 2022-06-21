function FinishOrder(OID) {
    if (OID[0] != '[') {
        OID = JSON.stringify(OID);
    }
    //alert(OID);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText) {
                alert(this.responseText);
            }
            location.reload();
        }
        ;
    }
    xhttp.open("POST", "php/Complete_Order.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("OID=" + OID);
}

function Finish_Selected_orders() {
    var SOtable = document.getElementById('ShopOrderTable');
    var length = SOtable.rows.length;
    var arr = [];
    for (var i = 1; i < length; i++) {
        if (document.getElementById('ShopOrderBox' + i)) {
            if ($('#ShopOrderBox' + i).is(':checked')) {
                arr.push(SOtable.rows[i].cells[1].innerHTML);
            }
        }
    }
    if (arr.length) {
        FinishOrder(JSON.stringify(arr));
    } else {
        alert("No item selected!");
    }
}