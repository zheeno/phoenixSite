<?php
    require_once("DB.php");

    // $db = new DB("localhost", "phoenixe_bank_DB", "phoenixe_root", "@phoenix");
    // $con = mysqli_connect('localhost', 'phoenixe_root', '@phoenix', 'phoenixe_bank_DB');    
    $db = new DB("localhost", "cap_one", "root", "");
    $con = mysqli_connect('localhost', 'root', '', 'cap_one');    

    if($_SERVER['REQUEST_METHOD'] == "GET"){
       //...       
    }else if($_SERVER['REQUEST_METHOD'] == "POST"){

        if($_REQUEST['url'] == "login"){//login
            $body = file_get_contents("php://input");
            $body = json_decode($body);
            $id = stripslashes($body->user_id);
            $id = strip_tags($id);
            $pass = stripslashes($body->password);
            $pass = strip_tags($pass);
            $pass = md5($pass);

            $query = mysqli_query($con,"SELECT * FROM accounts WHERE username = '$id' AND password = '$pass' ");
            if(mysqli_num_rows($query) == null){
                echo '{"status": 0, "message": "Invalid login credentials. Please try again."}';
            }else{
                $row = mysqli_fetch_assoc($query);
                $token = rand_string(40);
                $token = md5($token);
                //username and password match an account
                $id = $row['id'];
                $sql = "UPDATE accounts SET token = '$token' WHERE id = '$id' ";
                if(mysqli_query($con, $sql)){
                    $cookie_name = "token";
                    $cookie_value = $token;
                    setcookie($cookie_name, $cookie_value, time() + (3600), "/");
                    echo '{"status": 1, "message": "Login Successful", "token": "'.$token.'", "user_id": "'.$id.'", "acc_name": "'.$row['acc_name'].'", "acc_no": "'.$row['acc_no'].'", "acc_bal": "'.number_format(ledgerBalance($con, $id), 2).'" }';
                }else{
                    echo '{"status": 0, "message": "Error encountered while logging in. Please try again."}';
                }
            }
            http_response_code(200);
        }
        if($_REQUEST['url'] == "getTrans" || $_REQUEST['url'] == "getAllTrans"){//get transaction history
            $body = file_get_contents("php://input");
            $body = json_decode($body);
            //this snippet of code is use to correct any error in the notes field
            //of the database
            //$cor = mysqli_query($con, "SELECT * FROM transactions WHERE acc_id = '$id'");
            // while($core = mysqli_fetch_assoc($cor)){
            //     $t_id = $core['id'];
            //     $notes = str_ireplace("\n","<br>",$core['notes']);
            //     mysqli_query($con, "UPDATE transactions SET notes = '$notes' WHERE id = $t_id ");
            // }
            if($_REQUEST['url'] == "getAllTrans"){
                $id = $body->id;
                $query = mysqli_query($con,"SELECT * FROM transactions WHERE acc_id = '$id' ORDER BY id DESC ");
            }else{
                $id = $body->user_id;
                $query = mysqli_query($con,"SELECT * FROM transactions WHERE acc_id = '$id' ORDER BY id DESC LIMIT 10");
            }
            $trans = ""; $i = 1;
            while($row = mysqli_fetch_assoc($query)){
                if($i < mysqli_num_rows($query)){
                    $comma = ",";
                }else{
                    $comma = "";
                }
                $trans .= '{
                            "id": "'.$row['id'].'",
                            "credit": "'.number_format($row['credit'],2).'",
                            "debit": "'.number_format($row['debit'],2).'",
                            "date_time": "'.$row['date_time'].'",
                            "notes": "'.str_ireplace("\n","<br>",$row['notes']).'"
                        }'.$comma;
                $i++;
            }
            echo "[".$trans."]";
            http_response_code(200);
        }
        else if($_REQUEST['url'] == "showOtheBenef" || $_REQUEST['url'] == "delOthBenef" ){
            $body = file_get_contents("php://input");
            $body = json_decode($body);
            $user_id = (int)$body->user_id;
            if($_REQUEST['url'] == "delOthBenef"){
                $acc_no = $body->acc_no;
                mysqli_query($con,"DELETE FROM other_beneficiaries WHERE user_id = $user_id AND acc_no = $acc_no ");
            }
            $query = mysqli_query($con,"SELECT * FROM other_beneficiaries WHERE user_id = $user_id ORDER BY id DESC ");
            if(mysqli_num_rows($query) == null){
                echo '[]';
            }else{
                $ben = ""; $i = 1;
                while($row = mysqli_fetch_assoc($query)){
                    if($i < mysqli_num_rows($query)){
                        $comma = ",";
                    }else{
                        $comma = "";
                    }
                    $ben .= '{ "id": "'.($i-1).'", "acc_name": "'.$row['acc_name'].'", "iban": "'.$row['acc_no'].'", "swiftbic": "'.$row['swiftbic'].'", "bank": "'.$row['dest_bank'].'"  }'.$comma;
                    $i++;
                }
                echo "[".$ben."]";
            }
        }
        else if($_REQUEST['url'] == "showBenef" || $_REQUEST['url'] == "delBenef"){
            $body = file_get_contents("php://input");
            $body = json_decode($body);
            $user_id = $body->user_id;
            if($_REQUEST['url'] == "delBenef"){
                $ben_id = $body->ben_id;
                mysqli_query($con,"DELETE FROM beneficiaries WHERE user_id = $user_id AND ben_id = $ben_id ");
            }
            $query = mysqli_query($con,"SELECT * FROM beneficiaries WHERE user_id = $user_id ORDER BY id DESC ");
            if(mysqli_num_rows($query) == null){
                echo '[]';
            }else{
                $ben = ""; $i = 1;
                while($row = mysqli_fetch_assoc($query)){
                    if($i < mysqli_num_rows($query)){
                        $comma = ",";
                    }else{
                        $comma = "";
                    }
                    $query_2 = mysqli_query($con,"SELECT * FROM accounts WHERE id = '".$row['ben_id']."' ");
                    if(mysqli_num_rows($query_2) != null){
                        $row_2 = mysqli_fetch_assoc($query_2);
                        $ben .= '{ "id": "'.$row['ben_id'].'", "acc_name": "'.$row_2['acc_name'].'", "acc_no": "'.$row_2['acc_no'].'"  }'.$comma;
                    }
                    $i++;
                }
                echo "[".$ben."]";
            }
        }
        else if($_REQUEST['url'] == "getAcc"){//get account details
            $body = file_get_contents("php://input");
            $body = json_decode($body);
            $id = $body->user_id;
            echo json_encode($db->query("SELECT * FROM accounts WHERE id = '$id' "));
            http_response_code(200);
        }
        else if($_REQUEST['url'] == "getAccBal"){//get account details
            $body = file_get_contents("php://input");
            $body = json_decode($body);
            $id = $body->user_id;
            echo '{"balance": "'.number_format(ledgerBalance($con, $id), 2).'", "user": '.$id.'}';
            http_response_code(200);
        }
        else if($_REQUEST['url'] == "verifyAcc"){//check if account exists on the database
            $body = file_get_contents("php://input");
            $body = json_decode($body);
            $acc_no = $body->acc_no;
            $token = $body->token;
            $query = mysqli_query($con,"SELECT * FROM accounts WHERE acc_no = '$acc_no' AND token != '$token' ");
            if(mysqli_num_rows($query) != null){
                $row = mysqli_fetch_assoc($query);
                echo '[{ "status": 1, "id": "'.$row['id'].'", "acc_name": "'.$row['acc_name'].'", "acc_no": "'.$row['acc_no'].'" }]';
            }else{
                echo '[{ "status": 0 }]';
            }
            http_response_code(200);
        }
        else if($_REQUEST['url'] == "transferOthBnkNow"){//transfer funds within the bank
            $body = file_get_contents("php://input");
            $body = json_decode($body);
            $bank = $body->bank;
            $accName = $body->accName;
            $iban = $body->iban;
            $swiftBIC = $body->swiftBIC;
            $amount = $body->amount;
            $notes = $body->notes;
            $token = $body->token;
            $saveBen = $body->saveBen;
            
            //check if origin account has sufficient funds
            $getOrigin = $db->query("SELECT * FROM accounts WHERE token = '$token' ");
            $origin = $getOrigin[0]["id"];
            if(ledgerBalance($con, $origin) >= $body->amount){
                    if($saveBen == "true"){
                        //check if the sender has save the same receiver as a beneficiary 
                        $chk = mysqli_query($con, "SELECT * FROM other_beneficiaries WHERE user_id = $origin AND acc_no = '$iban' ");
                        if(mysqli_num_rows($chk) == null){
                            //save receiver's details to beneficiary table
                            mysqli_query($con,"INSERT INTO other_beneficiaries VALUES ('',$origin,0,'$accName','$iban','$bank','$swiftBIC') ");
                        }
                    }
                    //preceed with transfer
                     $cur_bal_2 = ledgerBalance($con, $origin); //sender's current balance
                    $new_bal_2 = $cur_bal_2 - $amount; //sender's new balance
                    $date = Date("Y/m/d h:i:s");
                    //GENERATE TRANSACTION ID
                    $chars = "0123456789";	
                    $str = "";
                    $size = strlen( $chars );
                    for( $i = 0; $i < 10; $i++ ) {
                        $str .= $chars[ rand( 0, $size - 1 ) ];
                    }
                    $tran_id = $str;
                    $notes_con = "TRANSACTION ID: ".$tran_id."<br>";
                    $notes_con .= "TRANSACTION METHOD: WIRE TRANSFER<br>";
                    $notes_con .= "TRANSACTION NOTES: ".$body->notes."<br>";
                    $notes_con .= "AMOUNT: $".number_format($body->amount,2)."<br>";
                    //update sender's account balance
                    $notes_2 = "--DEBIT ALERT--<br>";
                    $notes_2 .= $notes_con;
                    $notes_2 .= "RECEIVER: ".strtoupper($accName);
                    $notes_2 .= "<br>RECEIVING BANK:".strtoupper($bank);
                    $wire_limit = $getOrigin[0]['wire_trials_limit'] -1;
                    //check if inter bank transfer if allowed for the account
                    if($getOrigin[0]['wire_permission'] == 700 || $getOrigin[0]['wire_permission'] == 702 ){
                        if(($getOrigin[0]['wire_trials_limit'] > 0) && (mysqli_query($con, "UPDATE accounts SET acc_bal = $new_bal_2, wire_trials_limit = $wire_limit WHERE id = '$origin' ") &&
                        mysqli_query($con, "INSERT INTO transactions VALUES ('',$origin,0,0,$body->amount,'$notes_2','$date') "))){
                            //transaction successful
                            echo '{"status": "200", "balance": "'.number_format(ledgerBalance($con, $origin), 2).'", "Description": "Transaction Successful "}';
                        }else{
                            //transaction error 
                            echo '{"status": "405", "Description": "Transaction Error"}';
                        }
                    }else{
                        //transaction error 
                        echo '{"status": "405", "Description": "Transaction Error: You can not perform this transaction at the moment, kindly contact your account manager for further inquiries."}';
                    }

                }
             else{
                echo '{"status": "404", "Description": "You currently do not have sufficient funds to carry out this transaction" }';
            }
        }
        else if($_REQUEST['url'] == "transferSmBnkNow"){//transfer funds within the bank
            $body = file_get_contents("php://input");
            $body = json_decode($body);
            $dest = $body->id;
            $token = $body->token;
            $saveBen = $body->saveBen;
            //check if origin account has sufficient funds
            $getOrigin = $db->query("SELECT * FROM accounts WHERE token = '$token' ");
            $origin = $getOrigin[0]["id"];
            if(ledgerBalance($con, $origin) >= $body->amount){
                //check if destination account exists
                if (strlen(json_encode($db->query("SELECT * FROM accounts WHERE id = $dest "))) > 2){
                    //debit and credit origin and destination accounts respectively
                    $destAcc = $db->query("SELECT * FROM accounts WHERE id = $dest ");
                    $dest_acc_no = $destAcc[0]['acc_no'];
                    $dest_acc_name = $destAcc[0]['acc_name'];
                    if($saveBen == "true"){
                        //check if the sender has save the same receiver as a beneficiary 
                        $chk = mysqli_query($con, "SELECT * FROM beneficiaries WHERE user_id = $origin AND ben_id = $dest ");
                        if(mysqli_num_rows($chk) == null){
                            //save receiver's details to beneficiary table
                            mysqli_query($con,"INSERT INTO beneficiaries VALUES ('',$origin,$dest,'$dest_acc_name','$dest_acc_no') ");
                        }
                    }
                //preceed with transfer
                $cur_bal = ledgerBalance($con, $dest); //receiver's current balance
                $new_bal = $cur_bal + $body->amount; //receiver's new balance
                //////////////////////////////
                $cur_bal_2 = ledgerBalance($con, $origin); //sender's current balance
                $new_bal_2 = $cur_bal_2 - $body->amount; //sender's new balance
                $date = Date("Y/m/d h:i:s");
                //GENERATE TRANSACTION ID
                $chars = "0123456789";	
                $str = "";
                $size = strlen( $chars );
                for( $i = 0; $i < 10; $i++ ) {
                    $str .= $chars[ rand( 0, $size - 1 ) ];
                }
                $tran_id = $str;
                $notes_con = "TRANSACTION ID: ".$tran_id."<br>";
                $notes_con .= "TRANSACTION METHOD: WIRE TRANSFER<br>";
                $notes_con .= "TRANSACTION NOTES: ".$body->notes."<br>";
                $notes_con .= "AMOUNT: $".number_format($body->amount,2)."<br>";
                //update receiver's account balance
                $notes_1 = "--CREDIT ALERT--<br>";
                $notes_1 .= $notes_con;
                $notes_1 .= "SENDER: ".strtoupper($getOrigin[0]["acc_name"]);
                if($getOrigin[0]['wire_permission'] == 700 || $getOrigin[0]['wire_permission'] == 701 ){                    
                    if(($getOrigin[0]['wire_trials_limit'] > 0) && (mysqli_query($con, "UPDATE accounts SET acc_bal = $new_bal WHERE id = '$dest' ") &&
                            mysqli_query($con, "INSERT INTO transactions VALUES ('',$dest,$origin,$body->amount,0,'$notes_1','$date') "))){

                            //update sender's account balance
                            $notes_2 = "--DEBIT ALERT--<br>";
                            $notes_2 .= $notes_con;
                            $notes_2 .= "RECEIVER: ".strtoupper($destAcc[0]['acc_name']);
                            $wire_limit = $getOrigin[0]['wire_trials_limit'] -1;
                            if(mysqli_query($con, "UPDATE accounts SET acc_bal = $new_bal_2, wire_trials_limit = $wire_limit WHERE id = '$origin' ") &&
                            mysqli_query($con, "INSERT INTO transactions VALUES ('',$origin,$dest,0,$body->amount,'$notes_2','$date') ")){
                                //transaction successful
                                echo '{"status": "200", "balance": "'.number_format(ledgerBalance($con, $origin), 2).'", "Description": "Transaction Successful "}';
                            }else{
                                //transaction error 
                                echo '{"status": "405", "Description": "Transaction Error"}';
                            }
                        }else{
                            //transaction error
                            echo '{"status": "405", "Description": "Transaction Error"}';
                        }
                    }else{
                        //transaction error 
                        echo '{"status": "405", "Description": "Transaction Error: You can not perform this transaction at the moment, kindly contact your account manager for further inquiries."}';
                    }
                }
            }else{
                //insufficient funds
                echo '{"status": "404", "Description": "You currently do not have sufficient funds to carry out this transaction" }';
            }
            http_response_code(200);
        }
    }else{
        http_response_code(405);
    }
    function ledgerBalance($con, $user_id){
        if(!$con) {
            die("could not connect");
        }
        //get ledger balance
        $ledg = "SELECT * FROM transactions WHERE acc_id = '$user_id' ;";
        $ledger = mysqli_query($con, $ledg);
        $ledgerBal = 0; $credit = 0; $debit = 0;
        while($ledgers = mysqli_fetch_assoc($ledger)){
            $credit += $ledgers['credit'];
            $debit += $ledgers['debit'];
        }
        $ledgerBal = $credit - $debit;
        return $ledgerBal;
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