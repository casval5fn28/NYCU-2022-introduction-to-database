function check_shop_name(shop_name) {
    if (shop_name != "") {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            var message;
            if (this.readyState == 4 && this.status == 200) {
                switch (this.responseText) {
                    case 'YES':
                        message = 'The shop name is available.';
                        break;
                    case 'NO':
                        message = 'The shop name is not available.';
                        break;
                    case 'ERROR':
                        message = 'shop name contain illegal letter!';
                        break;
                    default:
                        message = 'Oops. There is something wrong.';
                        break
                }
                document.getElementById("check_shop_name").innerHTML = message;
            }
        }
        xhttp.open("POST", "php/check_shop_name.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("shop_name=" + shop_name);
    } else {
        document.getElementById("check_shop_name").innerHTML = "";
    }

}