<?php
require_once 'capDB.php';
if(isset($_REQUEST['verifySameBank'])){
    $my_id = $_REQUEST['my_id'];
    $acc_no = $_REQUEST['acc_no'];
    $query = "SELECT * FROM accounts WHERE acc_no = '$acc_no' AND id != '$my_id' ";
    $exec = mysqli_query($con, $query);
    if(mysqli_num_rows($exec) == 0){
        echo "<input type='hidden' id='accStatus' value='0'>";
    }else{
        $acc = mysqli_fetch_assoc($exec);
        echo "<input type='hidden' id='accStatus' value='1'>";
        echo "<input type='hidden' id='accName' value='".$acc['acc_name']."'>";
        echo "<input type='hidden' id='accId' value='".$acc['id']."'>";
    }
}

if(isset($_REQUEST['checkWire'])){
    $from = $_REQUEST['from'];
    $to = $_REQUEST['to'];
    $amt = $_REQUEST['amt'];
    $notes = $_REQUEST['notes'];
    $my_bal = ledgerBalance($from);
    $query = "SELECT * FROM accounts WHERE id = '$to' ";
    $exe = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($exe);
    $acc_no = $row['acc_no']; $acc_name = $row['acc_name'];
    echo "<div class='container pad-top-50 pad-bot-100 right-align'>
            <div class='row'>
                <div class='md-form col-xs-12 col-sm-15 mx-auto'>
                <label class='active'>Your Account Balance ($)</label>
                    <h1 class='h1-responsive'>$".number_format($my_bal,2)."</h1>
                </div>
            </div>
            <div class='row'>
                <div class='md-form col-xs-12 col-sm-5 mx-auto'>
                    <label class='active'>Account No.</label>
                    <h3 class='h3-responsive'>".$acc_no."</h3>
                </div>
                <div class='md-form col-xs-12 col-sm-5 mx-auto'>
                    <label class='active'>Account Name</label>
                    <h3 class='h3-responsive'>".$acc_name."</h3>
                </div>
            </div>
            <div class='row'>
                <div class='md-form col-xs-12 col-sm-5 mx-auto'>
                    <label class='active'>Amount($)</label>
                    <h3 class='h3-responsive'>$".number_format($amt, 2)."</h3>
                </div>
                <div class='md-form col-xs-12 col-sm-5 mx-auto'>
                    <label class='active'>Transaction Notes</label>
                    <p id='transact_notes' class='lead'>".$notes."</p>
                </div>
            </div>
            <div class='row'>
                <div class='md-form col-xs-12 col-sm-12 mx-auto center-align'>
                <button type='button' onClick='cancelSmBankTransact();' class='wire-btn cancel-btn btn btn-outline-black deep-blue-border deep-blue-text btn-lg capitalize'>Cancel Transaction</button>
                <button type='button' onClick='confirmWire()' class='wire-btn btn bg-light-blue-grey btn-lg capitalize'>Confirm Transfer</button>
</div>
            </div>
        </div>";
}

if(isset($_REQUEST['checkWireToOtherBanks'])){
    $from = $_REQUEST['from'];
    $to = $_REQUEST['to'];
    $amt = $_REQUEST['amt'];
    $acc_name = $_REQUEST['acc_name'];
    $bank_name = $_REQUEST['bank_name'];
    $acc_no = $_REQUEST['acc_no'];
    $notes = $_REQUEST['notes'];
    $swiftbic = $_REQUEST['swiftbic'];
    $my_bal = ledgerBalance($from);
    echo "<div class='container pad-top-50 pad-bot-100 left-align'>
            <div class='row'>
                <div class='md-form col-xs-12 col-sm-15 mx-auto center-align'>
                    <small class='gray-text'>Your Account Balance</small>
                    <h1 class='deep-blue-text h1-responsive'>$".number_format($my_bal,2)."</h1>
                </div>
            </div>
            <div class='row'>
                <div class='md-form col-xs-12 col-sm-5 mx-auto'>
                    <label class='active'>Bank</label>
                    <h3 class='deep-blue-text h3-responsive'>".$bank_name."</h3>
                </div>
                <div class='md-form col-xs-12 col-sm-5 mx-auto'>
                    <label class='active'>Account No./IBAN</label>
                    <h3 class='deep-blue-text h3-responsive'>".$acc_no."</h3>
                </div>
            </div>
            <div class='row'>
                <div class='md-form col-xs-12 col-sm-5 mx-auto'>
                    <label class='active'>Account Holdeer's Name</label>
                    <h3 class='deep-blue-text h3-responsive'>".$acc_name."</h3>
                </div>
                <div class='md-form col-xs-12 col-sm-5 mx-auto'>
                    <label class='active'>SWIFTBIC</label>
                    <h3 class='deep-blue-text h3-responsive'>".$swiftbic."</h3>
                </div>
            </div>
            <div class='row'>
            <div class='md-form col-xs-12 col-sm-6 mx-auto center-align'>
                <small class='gray-text'>Amount($)</small>
                <h3 class='deep-blue-text h3-responsive'>$".number_format($amt, 2)."</h3>
            </div>
            </div>
            <div class='row'>
                <div class='md-form col-xs-12 col-sm-12 mx-auto center-align'>
                <button type='button' onClick='cancelSmBankTransact_2();' class='wire-btn cancel-btn btn btn-outline-black deep-blue-border deep-blue-text btn-lg capitalize'>Cancel Transaction</button>
                <button type='button' onClick='confirmWire(1)' class='wire-btn btn bg-light-blue-grey btn-lg capitalize'>Confirm Transfer</button>
</div>
            </div>
        </div>";
}
if(isset($_REQUEST['wireNow'])){
    $from = $_REQUEST['from'];
    $to = $_REQUEST['to'];
    $amt = $_REQUEST['amt'];
    $notes = strtoupper($_REQUEST['notes']);

    $query = "SELECT * FROM accounts WHERE id = '$from' ";
    $exe = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($exe);
    if(mysqli_num_rows($exe) == 0  || $row['wire_trials_limit'] == 0){
        //sender account does not exist
        echo "<div class='alert alert-danger transparent center-align pad-top-50 pad-bot-100 no-border no-radius'>
                    <span class='fa fa-warning fa-4x'></span>
                    <h2 class='h2-responsive'>ATTENTION!!!</h2>
                    <h3 class='h3-responsive'> 
                    <b>CAUTION:</b>Your account has been blocked!<br>Kindly contact your account officer or call our customer care center
                    </h3>
                    <a class='btn btn-red capitalize' onClick='cancelSmBankTransact()'>Try Again</a>
            </div> ";
    }else{
        //check if sender has sufficient balance
        if(ledgerBalance($from) >= $amt){
            //check if receiver's account exists
            $query_2 = "SELECT * FROM accounts WHERE id = '$to' ";
            $exe_2 = mysqli_query($con, $query_2);
            if(mysqli_num_rows($exe_2) == 0){
                //invalid destination
                echo "<div class='alert alert-danger transparent center-align pad-top-50 pad-bot-100 no-border no-radius'>
                            <span class='fa fa-warning fa-4x'></span><br>
                            <h3 class='h3-responsive'>Transaction error! The receiving account specified does not exist</h3>
                            <a class='btn btn-red capitalize' onClick='cancelSmBankTransact()'>Try Again</a>
                    </div> ";
            }else{
                $row_2 = mysqli_fetch_assoc($exe_2);
                //preceed with transfer
                $cur_bal = ledgerBalance($to); //receiver's current balance
                $new_bal = $cur_bal + $amt; //receiver's new balance
                //////////////////////////////
                $cur_bal_2 = ledgerBalance($from); //sender's current balance
                $new_bal_2 = $cur_bal_2 - $amt; //sender's new balance
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
                $notes_con .= "TRANSACTION NOTES: ".$notes."<br>";
                $notes_con .= "AMOUNT: $".number_format($amt,2)."<br>";
                //update receiver's account balance
                $notes_1 = "--CREDIT ALERT--<br>";
                $notes_1 .= $notes_con;
                $notes_1 .= "SENDER: ".strtoupper($row['acc_name']);
                mysqli_query($con, "UPDATE accounts SET acc_bal = $new_bal WHERE id = '$to' ");
                mysqli_query($con, "INSERT INTO transactions VALUES ('',$to,$from,$amt,0,'$notes_1','$date') ");
                //update sender's account balance
                $notes_2 = "--DEBIT ALERT--<br>";
                $notes_2 .= $notes_con;
                $notes_2 .= "RECEIVER: ".strtoupper($row_2['acc_name']);
                $wire_limit = $row['wire_trials_limit'] -1;
                mysqli_query($con, "UPDATE accounts SET acc_bal = $new_bal_2, wire_trials_limit = $wire_limit WHERE id = '$from' ");
                mysqli_query($con, "INSERT INTO transactions VALUES ('',$from,$to,0,$amt,'$notes_2','$date') ");
                echo "<div class='alert alert-success transparent center-align pad-top-50 pad-bot-100 no-border no-radius'>
                            <span class='fa fa-check-circle fa-4x green-text'></span><br>
                            <label class='grey-text active capitalize'>Amount Sent</label>
                            <h1 class='h1-responsive'>$".number_format($amt, 2)."</h1>
                            <label class='grey-text active capitalize'>Your New Balance is</label>
                            <h4 class='h4-responsive'>$".number_format(ledgerBalance($from), 2)."</h4>
                            <h3 class='h3-responsive'>Transaction Successful!</h3>
                            <a class='btn btn-green capitalize' onClick='cancelSmBankTransact()'>Continue</a>
                    </div> ";
            } 
        }else{
            //insufficient funds
            echo "<div class='alert alert-danger transparent center-align pad-top-50 pad-bot-100 no-border no-radius'>
                    <span class='fa fa-warning fa-4x'></span><br>
                    <label class='grey-text active'>Account Balance</label>
                    <h1 class='h1-responsive'>$".number_format(ledgerBalance($from), 2)."</h1>
                    <h3 class='h3-responsive'>Insufficient Funds</h3>
                    <a class='btn btn-red capitalize' onClick='cancelSmBankTransact()'>Try Again</a>
            </div> ";
        }
    }
}

if(isset($_REQUEST['wireNowOtherBanks'])){
    $from = $_REQUEST['from'];
    $to = $_REQUEST['to'];
    $amt = $_REQUEST['amt'];
    $swiftbic = $_REQUEST['swiftbic'];
    $notes = strtoupper($_REQUEST['notes']);
    $bank_name = strtoupper($_REQUEST['bank_name']);
    $acc_no = strtoupper($_REQUEST['acc_no']);
    $acc_name = strtoupper($_REQUEST['acc_name']);
    $query = "SELECT * FROM accounts WHERE id = '$from' ";
    $exe = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($exe);
    if(mysqli_num_rows($exe) == 0  || $row['wire_trials_limit'] == 0){
        //sender account does not exist
        echo "<div class='alert alert-danger transparent center-align pad-top-50 pad-bot-100 no-border no-radius'>
                    <span class='fa fa-warning fa-4x'></span>
                    <h3 class='h3-responsive'>Unexpected transaction error</h3>
                    <a class='btn btn-red capitalize' onClick='cancelSmBankTransact_2()'>Try Again</a>
            </div> ";
    }else{
        //check if sender has sufficient balance
        if(ledgerBalance($from) >= $amt){
            //preceed with transfer
            //////////////////////////////
            //add receiver to beneficiary table
            $chk = mysqli_query($con, "SELECT * FROM other_beneficiaries WHERE user_id = $from AND acc_no ='$acc_no' ");
            if(mysqli_num_rows($chk) == null){
                mysqli_query($con, "INSERT INTO other_beneficiaries VALUES ('', $from, $to, '$acc_name', '$acc_no', '$bank_name', '$swiftbic') ");
            }
            $cur_bal_2 = ledgerBalance($from); //sender's current balance
            $new_bal_2 = $cur_bal_2 - $amt; //sender's new balance
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
            if(strlen($notes) > 0){
                $notes_con .= "TRANSACTION NOTES: ".$notes."<br>";
            }
            $notes_con .= "AMOUNT: $".number_format($amt,2)."<br>";
            //update sender's account balance
            $notes_2 = "--DEBIT ALERT--<br>";
            $notes_2 .= $notes_con;
            $notes_2 .= "RECEIVER: ".$acc_name."<br>";
            $notes_2 .= "RECEIVING BANK: ".$bank_name;
            $wire_limit = $row['wire_trials_limit'] -1;
            mysqli_query($con, "UPDATE accounts SET acc_bal = $new_bal_2, wire_trials_limit = $wire_limit WHERE id = '$from' ");
            mysqli_query($con, "INSERT INTO transactions VALUES ('',$from,$to,0,$amt,'$notes_2','$date') ");
            echo "<div class='alert alert-success transparent center-align pad-top-50 pad-bot-100 no-border no-radius'>
                        <span class='fa fa-check-circle fa-4x'></span><br>
                        <label class='grey-text active capitalize'>Amount Sent</label>
                        <h1 class='h1-responsive'>$".number_format($amt, 2)."</h1>
                        <label class='grey-text active capitalize'>Your New Balance is</label>
                        <h4 class='h4-responsive'>$".number_format(ledgerBalance($from), 2)."</h4>
                        <h3 class='h3-responsive'>Transaction Successful!</h3>
                        <a class='btn btn-green capitalize' onClick='cancelSmBankTransact_2()'>Continue</a>
                </div> ";
        }else{
            //insufficient funds
            echo "<div class='alert alert-danger transparent center-align pad-top-50 pad-bot-100 no-border no-radius'>
                    <span class='fa fa-warning fa-4x'></span><br>
                    <label class='grey-text active'>Account Balance</label>
                    <h1 class='h1-responsive'>$".number_format(ledgerBalance($from), 2)."</h1>
                    <h3 class='h3-responsive'>Insufficient Funds</h3>
                    <a class='btn btn-red capitalize' onClick='cancelSmBankTransact_2()'>Try Again</a>
            </div> ";
        }
    }
}

if(isset($_REQUEST['openAccount'])){
    $acc_name = strip_tags($_REQUEST['acc_name']);
    $username = strip_tags($_REQUEST['uname']);
    $pass = strip_tags($_REQUEST['pass']);
    $pass = md5($pass);
    $bal = strip_tags($_REQUEST['bal']);
    $acc_no = "00".rand(5000000,9000000);;
    $query = mysqli_query($con, "INSERT INTO accounts VALUES ('','$acc_name','$acc_no','','$username','$pass','',$bal,50,700) ");
   if($query){
       $getAcc = mysqli_query($con, "SELECT * FROM accounts WHERE acc_no = '$acc_no' ");
       $Acc = mysqli_fetch_assoc($getAcc);
       $acc_id = $Acc['id']; 
       $date = Date("Y-m-d h:i:s");
       mysqli_query($con, "INSERT INTO transactions VALUES ('',$acc_id,0,$bal,0,'Opening Balance','$date') ");
        echo "<span class='green-text'>Account Added</span> ";
   }else{
       echo "<span class='red-text'>Error encountered when adding account</span> ";
   }
}
if(isset($_REQUEST['adminTransact'])){
    $id = $_REQUEST['id'];
    $type = $_REQUEST['type'];
    $amt = $_REQUEST['amt'];
    $date = $_REQUEST['date']." ".$_REQUEST['time'];;
    $desc = strip_tags($_REQUEST['desc']);
    if($type == 'Credit'){
        $new_bal = ledgerBalance($id) + $amt;
        $query = mysqli_query($con, "INSERT INTO transactions VALUES ('',$id,0,$amt,0,'$desc','$date') ") or die(mysqli_error($con));
    }else if($type == 'Debit'){
        $new_bal = ledgerBalance($id) - $amt;
        $query = mysqli_query($con, "INSERT INTO transactions VALUES ('',$id,0,0,$amt,'$desc','$date') ");
    }
    if($query){
        mysqli_query($con, "UPDATE accounts SET acc_bal = $new_bal WHERE id = $id ");
        echo "<span class='green-text'>Transaction successful</span>";
    }else{
        echo "<span class='red-text'>Error encountered</span> ";
    }
}
if(isset($_REQUEST['delTransact'])){
    $id = $_REQUEST['id'];
    mysqli_query($con, "DELETE FROM transactions WHERE id = $id ");
}
function ledgerBalance($user_id){
    include 'capDB.php';
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