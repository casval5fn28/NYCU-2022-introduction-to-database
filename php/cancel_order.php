<?php
session_start();
ini_set('date.timezone','Asia/Taipei');
$dbservername = 'localhost';
$dbname = 'db';
$dbusername = 'admin';
$dbpassword = 'admin';
try {
    $conn = new PDO("mysql:host = $dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $product_name = $_POST['OID'];/////
    $OID_all = json_decode($_POST["OID"]);
    for ($i = 0; $i < count($OID_all); $i++) {
        $OID = $OID_all[$i];
        $stmt = $conn->prepare("SELECT order_status from `orders` where OID=:OID");
        $stmt->execute(array('OID' => $OID));
        $row = $stmt->fetch();
        if ($row['order_status'] != 'undone') {
            echo "Failed to cancel order , the order has been finished or canceled";
            exit();
        }
    }

    for ($i = 0; $i < count($OID_all); $i++) {
        $OID = $OID_all[$i];
        $stmt = $conn->prepare('SELECT shop_name,user_account,order_price from `orders` where OID=:OID');
        $stmt->execute(array('OID' => $OID));
        $row = $stmt->fetch();

        $stmt = $conn->prepare('SELECT user_balance from user join shop on user.user_account=shop.shop_owner where shop_name =:shop');
        $stmt->execute(array('shop' => $row['shop_name']));
    }

    $no_product = array();
    for ($i = 0; $i < count($OID_all); $i++) {

            $OID = $OID_all[$i];
            $stmt = $conn->prepare("SELECT order_status,shop_name,user_account,order_price,order_detail from `orders` where OID=:OID");
            $stmt->execute(array('OID' => $OID));
            $row = $stmt->fetch();

            $order_detail = json_decode($row['order_detail'], true);

            for ($j = 1; $j < count($order_detail); $j++) {///from 0 or 1 ??
                $stmt = $conn->prepare('SELECT product_name from product join shop on product.product_shop = shop.shop_name  where product_name =:product');
                $stmt->execute(array('product' => $order_detail[$j]["meal"]));
                $row_tmp_2 = $stmt->rowCount();
                if ($row_tmp_2 == 0) {
                    array_push($no_product, $order_detail[$j]["meal"]);
                    //throw new Exception('Product was deleted, Order cannot be cancelled');

                }
            }
    }


    for ($i = 0; $i < count($OID_all); $i++) {
        $OID = $OID_all[$i];
        $stmt = $conn->prepare("SELECT order_status,shop_name,user_account,order_price,order_detail from `orders` where OID=:OID");
        $stmt->execute(array('OID' => $OID));
        $row = $stmt->fetch();

        $order_detail = json_decode($row['order_detail'], true);

        //order data
        $stmt = $conn->prepare('SELECT shop_name,order_price,user_account from `orders` where OID=:OID_main');
        $stmt->execute(array('OID_main' => $OID));
        $row_o = $stmt->fetch();
        //user data
        $stmt = $conn->prepare('SELECT user_account,user_balance from `user` where user_account=:user_account');
        $stmt->execute(array('user_account' => $row_o['user_account']));
        $row_u = $stmt->fetch();
        //shop data
        $stmt = $conn->prepare('SELECT * from `shop` where shop_name=:shop_name');
        $stmt->execute(array('shop_name' => $row_o['shop_name']));
        $row_s = $stmt->fetch();


        for ($j = 1; $j < count($order_detail); $j++) {
            if (!in_array($order_detail[$j]["meal"], $no_product)){
                continue;
            }
            $stmt = $conn->prepare('UPDATE product set product_amount=product_amount+:amount where product_name=:p_name and product_shop=:p_shop');
            $stmt->execute(array('amount' => $order_detail[$j]["quantity"], 'p_name' => $order_detail[$j]["meal"], 'p_shop' => $row['shop_name']));
        }

        //normal user
        $stmt = $conn->prepare('UPDATE user set user_balance=user_balance+:money where user_account =:u_account');
        $stmt->execute(array('money' => (int)$row['order_price'], 'u_account' => $row['user_account']));
        //shop owner
        $stmt = $conn->prepare('UPDATE user set user_balance=user_balance-:money where user_account=
                        (SELECT user_account from user join shop on user.user_account =shop.shop_owner where shop_name =:shop)');
        $stmt->execute(array('money' => (int)$row['order_price'], 'shop' => $row["shop_name"]));

        $time = date("Y-m-d H:i:s");
        $stmt = $conn->prepare('UPDATE orders set order_status=:ORDER, order_finish_time=:finish where OID=:OID');
        $stmt->execute(array('ORDER' => 'cancel', 'OID' => $OID, 'finish' => $time));

        $tra_user = $row_u['user_account'];
        $tra_shop = $row_s['shop_name'];
        $tra_money = $row_o['order_price'];
        $stmt = $conn->prepare('SELECT * from shop where shop_name=:shop');
        $stmt->execute(array('shop' => $tra_shop));
        $row_fin = $stmt->fetch();
        $tra_shop_account = $row_fin['shop_owner'];

        //user receive
        $stmt = $conn->prepare('INSERT INTO transaction (user_account,trader,tra_price,tra_time, tra_action) values 
                                   (:account,:trader,:val,:time,:type)');
        $stmt->execute(array('account' => $tra_user, 'trader' => $tra_shop, 'val' => '+' . $tra_money, 'time' => $time, 'type' => 'receive'));
        //payment
        $stmt = $conn->prepare('INSERT INTO transaction (user_account,trader,tra_price,tra_time, tra_action) values 
                                   (:account,:trader,:val,:time,:type)');
        $stmt->execute(array('account' => $tra_shop_account, 'trader' => $tra_user, 'val' => '-' . $tra_money, 'time' => $time, 'type' => 'receive'));
    }
    $stmt = $conn->prepare("SELECT user_balance from `user` where user_account=:user_account");
    $stmt->execute(array('user_account' => $_SESSION['user_account']));
    $row = $stmt->fetch();
    $_SESSION['user_balance'] = $row['user_balance'];
}
catch(Exception $e){
    $msg = $e->getMessage();
    echo($msg);
}
?>