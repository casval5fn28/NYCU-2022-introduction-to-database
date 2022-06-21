<?php
session_start();
ini_set('date.timezone','Asia/Taipei');
$dbservername = 'localhost';
$dbname = 'db';
$dbusername = 'admin';
$dbpassword = 'admin';

try{
    $conn = new PDO("mysql:host = $dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT product_name, product_img, product_img_type FROM orders_pictures WHERE OID = :OID");
    $stmt->execute(array('OID'=>$_REQUEST['OID']));
    $data = $stmt->fetchAll();
    echo  json_encode($data);
}catch (Exception $e){
    $msg = $e->getMessage();
    echo $msg;
}
?>
