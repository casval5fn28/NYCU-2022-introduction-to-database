<?php
ini_set('date.timezone','Asia/Taipei');
$dbservername = 'localhost';
$dbname = 'db';
$dbusername = 'admin';
$dbpassword = 'admin';

try{
    if (!isset($_REQUEST['shop_name']) || empty($_REQUEST['shop_name']))
    {
        echo 'FAILED';
        exit();
    }
    if(!preg_match("#[a-zA-Z0-9_ .]#", $_REQUEST['shop_name'])){
        echo 'ERROR';
        exit();
    }
    $shop_name = $_REQUEST['shop_name'];
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname",$dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT shop_name FROM shop WHERE shop_name =:shop_name");
    $stmt->execute(array('shop_name'=>$shop_name));
    if($stmt->rowCount() == 0){
        echo 'YES';
    }else{
        echo 'NO';
    }
}catch (Exception $e){
    echo 'FAILED';
}
?>
