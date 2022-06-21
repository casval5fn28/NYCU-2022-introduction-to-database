function CancelOrder(OID){
    if(OID[0]!='['){
        OID = JSON.stringify(OID);
    }
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if(this.responseText){
                alert(this.responseText);
            }
            location.reload();
        };
    }
    xhttp.open("POST", "php/cancel_order.php", true);
    xhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xhttp.send("OID="+OID);
}

function MO_cancel(){
    var MOtable = document.getElementById('MyOrderTable');
    var length = MOtable.rows.length;
    var arr = [];
    for(var i=1;i<length;i++){
        if(document.getElementById('MyOrderBox'+i)){
            if($('#MyOrderBox'+i).is(':checked')){
                arr.push(MOtable.rows[i].cells[1].innerHTML);
            }
        }
    }
    if(arr.length){
        CancelOrder(JSON.stringify(arr));
    }
    else{
        alert("Didn't select !");
    }
}

function SO_cancel(){
    var SOtable = document.getElementById('ShopOrderTable');
    var length = SOtable.rows.length;
    var arr = [];
    for(var i=1;i<length;i++){
        if(document.getElementById('ShopOrderBox'+i)){
            if($('#ShopOrderBox'+i).is(':checked')){
                arr.push(SOtable.rows[i].cells[1].innerHTML);
            }
        }
    }
    if(arr.length){
        CancelOrder(JSON.stringify(arr));
    }
    else{
        alert("Didn't select ! ");
    }
}