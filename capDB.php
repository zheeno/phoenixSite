<?php
$server = 'localhost';
$user = 'root';
$password = '';

$error = 'could\'nt connect';

$dataB = 'cap_one';

$con = mysqli_connect($server, $user, $password, $dataB);

if(!$con) {
	die($error);
}
?>