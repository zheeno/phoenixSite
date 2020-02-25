<?php
$server = 'localhost';
$user = 'root';
$password = '';

$error = 'could\'nt connect';

$dataB = 'phoenix_courier';

$con = mysqli_connect($server, $user, $password, $dataB);

if(!$con) {
	die($error);
}
?>