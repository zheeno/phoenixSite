<?php
    require_once "capDB.php";
    $id = $_REQUEST['q'];
    $token = md5("SIGNEDOUT");
    if(mysqli_query($con, "UPDATE accounts SET token = '$token' WHERE id = $id ")){
        setcookie("acc_id", "", time() - 3600, "/");
        header("location:../");
    }
?>