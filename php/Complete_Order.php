<?php

ini_set('date.timezone','Asia/Taipei');
session_start();
$dbservername = 'localhost';
$dbname = 'db';
$dbusername = 'admin';
$dbpassword = 'admin';
$conn = new PDO("mysql:host = $dbservername;dbname=$dbname", $dbusername, $dbpassword);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$OIDs = json_decode($_POST["OID"],true);
//echo count($OIDs);
for ($i = 0; $i < count($OIDs); $i++) {
    $OID = str_pad($OIDs[$i] , 4,"0" , STR_PAD_LEFT);
    $stmt = $conn->prepare("SELECT order_status from `orders` where OID=:OID");
    $stmt->execute(array('OID' => $OID));
    $row = $stmt->fetch();
    if ($row['order_status'] != 'undone') {
        echo "Order is cancelled!";
        exit();
    }
}
$time = date("Y-m-d H:i:s");
for ($i = 0; $i < count($OIDs); $i++) {
    $OID = str_pad($OIDs[$i] , 4,"0" , STR_PAD_LEFT);
    $stmt = $conn->prepare('UPDATE `orders` set order_status=:ORDER, order_finish_time=:END where OID=:OID');
    $stmt->execute(array('ORDER' => 'done' , 'OID' => $OID, 'END' => $time));
}

