<?php
session_start();
ini_set('date.timezone','Asia/Taipei');
$dbservername = 'localhost';
$dbname = 'db';
$dbusername = 'admin';
$dbpassword = 'admin';

$conn = new PDO("mysql:host = $dbservername;dbname=$dbname", $dbusername, $dbpassword);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("Select * from `orders` WHERE user_account=:user_account order by order_start_time desc");
$stmt->execute(array('user_account' => $_SESSION['user_account']));

if ($stmt->rowCount()){
    $information = $stmt->fetchAll();
    echo  json_encode($information );
}
?>
