function check_user_account(user_account) {
    if (user_account != "") {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            var message;
            if (this.readyState == 4 && this.status == 200) {
                switch (this.responseText) {
                    case 'YES':
                        message = 'The account is available.';
                        break;
                    case 'NO':
                        message = 'The account is not available.';
                        break;
                    case 'ERROR':
                        message = 'Account can only contains numbers and letters!';
                        break;
                    default:
                        message = 'Oops. There is something wrong.';
                        break
                }
                document.getElementById("check_user_account").innerHTML = message;
            }
        }
        xhttp.open("POST", "php/check_user_account.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("user_account=" + user_account);
    } else {
        document.getElementById("check_user_account").innerHTML = "";
    }

}