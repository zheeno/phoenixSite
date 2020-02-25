<?php
require_once "capDB.php";

if(isset($_REQUEST['login'])){
    $id = stripslashes($_REQUEST['id']);
    $id = strip_tags($id);

    $pwd = stripslashes($_REQUEST['pwd']);
    $pwd = strip_tags($pwd);
    $pwd = md5($pwd);

    $query = "SELECT * FROM accounts WHERE username = '$id' ";
    $exe = mysqli_query($con, $query);
    if(mysqli_num_rows($exe) == 0){
        echo "<span class='red-text'>Incorrect username and/or password</span>";
    }else{
        $row = mysqli_fetch_assoc($exe);
        $pwd_2 = $row['password'];
        if($pwd != $pwd_2){
            echo "<span class='red-text'>Incorrect username and/or password</span>";
        }else{
            $token = rand_string(40);
            $token = md5($token);
            //username and password match an account
            $user_id = $row['id'];
            $sql = "UPDATE accounts SET token = '$token' WHERE id = '$user_id' ";
            if(mysqli_query($con, $sql)){
                
                $cookie_name = "acc_id";
                $cookie_value = $user_id;
                setcookie($cookie_name, $cookie_value, time() + (3600), "/");
                echo "
                    <input type='hidden' id='login-res' value='1'>
                    <input type='hidden' id='login-token' value='$token'>
                    <input type='hidden' id='login-id' value='$user_id'>";
            }else{
                echo "<span class='red-text'>Error encountered, Please try again</span>";
            }
        }
    }
}
function rand_string( $length ) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";	
    $str = "";
    $size = strlen( $chars );
    for( $i = 0; $i < $length; $i++ ) {
        $str .= $chars[ rand( 0, $size - 1 ) ];
    }

    return $str;
}
?>