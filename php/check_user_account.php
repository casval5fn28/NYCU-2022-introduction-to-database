<?php
ini_set('date.timezone','Asia/Taipei');
$dbservername = 'localhost';
$dbname = 'db';
$dbusername = 'admin';
$dbpassword = 'admin';

try{
    if (!isset($_REQUEST['user_account']) || empty($_REQUEST['user_account']))
    {
        echo 'FAILED';
        exit();
    }
    if(!preg_match("#^[a-zA-Z0-9]+$#", $_REQUEST['user_account'])){
        echo 'ERROR';
        exit();
    }
    $user_account = $_REQUEST['user_account'];
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname",$dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT user_account FROM user WHERE user_account =:user_account");
    $stmt->execute(array('user_account'=>$user_account));
    if($stmt->rowCount() == 0){
        echo 'YES';
    }else{
        echo 'NO';
    }
}catch (Exception $e){
    echo 'FAILED';
}
