<?php
    require_once "capDB.php";
    if(isset($_REQUEST['showAllAccounts'])){
        $query = mysqli_query($con, "SELECT * FROM accounts ORDER BY acc_name");
        if(mysqli_num_rows($query) == 0){
            echo "<div class='alert alert-info h5-responsive center-align'><span class='fa fa-info fa-4x'></span><br>No accounts available</div>";
        }
        else{
            echo "<h3 class='h3-responsive'>Accounts (".mysqli_num_rows($query).")</h3>
            <table class='table table-striped'>
             <thead>
                <tr>
                    <th class='h5-responsive'>Account Name</th>
                    <th class='h5-responsive'>Account No.</th>
                    <th class='h5-responsive'>Balance</th>
                    <th class='h5-responsive'>Action</th>
                </tr>
            </thead>
            <tbody>";
            while($row = mysqli_fetch_assoc($query)){
                echo "<tr id='row_".$row['id']."'>
                        <td class='h5-responsive bold'>".$row['acc_name']."</td>
                        <td class='h5-responsive'>".$row['acc_no']."</td>
                        <td class='h5-responsive'>$".number_format(ledgerBalance($row['id']),2)."</td>
                        <td class='dropup'>
                            <a class='btn btn-blue btn-sm fa fa-credit-card new-transact-btn' data-id='".$row['id']."' ></a>
                            <a class='btn btn-white btn-sm' data-toggle='dropdown'><span class='grey-text fa fa-ellipsis-v'></span></a>
                            <div class='dropdown-menu pad-0'>
                                <ul class='list-group'>
                                    <li class='list-group-item acc-stat' data-id='".$row['id']."'><a><span class='fa fa-info-circle'></span>&nbsp;Statement</a></li>
                                    <li class='list-group-item edit-acc' data-id='".$row['id']."'><a><span class='fa fa-edit'></span>&nbsp;Edit Account</a></li>
                                    <li class='list-group-item del-acc' data-id='".$row['id']."'><a><span class='fa fa-trash'></span>&nbsp;Delete Account</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>";
            }
            echo "</tbody>
            </table>";
        }
    }
    if(isset($_REQUEST['deleteAccount'])){
        $acc_id = $_REQUEST['acc_id'];
        mysqli_query($con, "DELETE FROM accounts WHERE id = $acc_id");
        mysqli_query($con, "DELETE FROM transactions WHERE acc_id = $acc_id");
        
    }
    if(isset($_REQUEST['addAccount'])){ ?>
        <div class="row">
    <div class="col-xs-12 col-sm-12">
        <h3 class="h3-responsive">Add Account</h3>
        <form id="add-account-form">
            <div class="container">
            <div class="row pad-top-25">
                    <div class="md-form col-xs-12 col-sm-5 mx-auto">
                        <label>Account Name</label>
                        <input id="new-acc-name" type="text" class="form-control" required>
                    </div>
                    <div class="md-form col-xs-12 col-sm-5 mx-auto">
                        <label>Username</label>
                        <input id="new-user-name" type="text" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="md-form col-xs-12 col-sm-5 mx-auto">
                        <label>Password</label>
                        <input id="new-acc-pass" type="text" class="form-control" required>
                    </div>
                    <div class="md-form col-xs-12 col-sm-5 mx-auto">
                        <label>Opening Balance ($)</label>
                        <input id="new-bal" type="number" class="form-control" required>
                    </div>
                    <div class="md-form col-12 mx-auto center-align">
                            <div id="loader-1"></div>
                            <button type="submit" class="btn bg-deep-blue-grey white-text">Submit</button>
                            <button type="button" class="btn btn-grey white-text" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div id="transferProgress"></div>
</div>
    <?php 
    }
    if(isset($_REQUEST['editAcc'])){ 
        $sql = mysqli_query($con, "SELECT * FROM accounts WHERE id = '".$_REQUEST['acc_id']."'");
        $row = mysqli_fetch_assoc($sql);
        ?>
        <div class="row">
    <div class="col-xs-12 col-sm-12">
        <h3 class="h3-responsive">Edit Account</h3>
        <form id="update-account-form">
            <div class="container">
            <div class="row pad-top-25">
                    <div class="md-form col-xs-12 col-sm-5 mx-auto">
                        <label class="active">Account Name</label>
                        <input type="hidden" id="new-acc-id" value="<?php echo $row['id'] ?>" required >
                        <input id="new-acc-name" type="text" class="form-control" value="<?php echo $row['acc_name'] ?>" required>
                    </div>
                    <div class="md-form col-xs-12 col-sm-5 mx-auto">
                        <label class="active">Username</label>
                        <input id="new-user-name" type="text" class="form-control" value="<?php echo $row['username'] ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="md-form col-xs-12 col-sm-5 mx-auto">
                        <label>Password</label>
                        <input id="new-acc-pass" type="text" class="form-control">
                    </div>
                    <div class="md-form col-xs-12 col-sm-5 mx-auto">
                        <label class="active">Wire Permission</label>
                        <input id="new-wire-perm" type="number" class="form-control" value="<?php echo $row['wire_permission'] ?>" required>
                    </div>
                    <div class="col-12">
                        <div id="infoBody" class="center-align"></div><br>
                        <a class="blue-text" onClick='$("#wireInfo").collapse("toggle")'>Info</a>
                        <div id="wireInfo" class="left-align collapse">
                            <b>Wire Permissions</b>
                            <ul class="list-group">
                                <li class="list-group-item no-border"><b>700-</b>&nbsp;Intra and Inter Bank transfers permitted</li>
                                <li class="list-group-item no-border"><b>701-</b>&nbsp;Intra Bank transfers permitted</li>
                                <li class="list-group-item no-border"><b>702-</b>&nbsp;Inter Bank transfers permitted</li>
                                <li class="list-group-item no-border"><b>703-</b>&nbsp;Intra and Inter Bank transfers restricted</li>
                                </div>
                    </div>
                    <div class="md-form col-12 mx-auto center-align">
                        <div id="loader-1"></div>
                            <button type="submit" class="btn bg-deep-blue-grey white-text">Update</button>
                            <button type="button" class="btn btn-grey white-text" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div id="transferProgress"></div>
</div>
    <?php 
    }
    
if(isset($_REQUEST['updateAccount'])){
    $id = strip_tags($_REQUEST['acc_id']);
    $acc_name = strip_tags($_REQUEST['acc_name']);
    $acc_user_name = strip_tags($_REQUEST['acc_user_name']);
    $acc_pass = strip_tags($_REQUEST['acc_pass']);
    $wire_perm = strip_tags($_REQUEST['wire_perm']);
    $wire_perm = (int)$wire_perm;
    if(strlen($acc_pass) > 0){
        $acc_pass = md5($acc_pass);
        $query = mysqli_query($con, "UPDATE accounts SET acc_name = '$acc_name', username = '$acc_user_name', password = '$acc_pass', wire_permission = $wire_perm WHERE id = '$id' ");
    }else{
        $query = mysqli_query($con, "UPDATE accounts SET acc_name = '$acc_name', username = '$acc_user_name', wire_permission = $wire_perm WHERE id = '$id' ");
    }
    if($query){
        echo "<span class='green-text'>Account updated</span>";
    }else{
        echo "<span class='red-text'>Error Encountered</span>";
    }
}
    if(isset($_REQUEST['newTransact'])){ 
        $acc_id = $_REQUEST['acc_id'];
        $que = mysqli_query($con, "SELECT * FROM accounts WHERE id = $acc_id ");
        $row = mysqli_fetch_assoc($que);
        ?>
    <div class="row">
    <div class="col-xs-12 col-sm-12">
        <h3 class="h3-responsive">Transactions</h3>
        <form id="new-transact-form">
            <div class="container">
            <div class="row pad-top-50">
                    <div class="md-form col-12 mx-auto">
                        <label class="active">Account</label><br>
                        <input id="sel-acc-no" class="form-control no-border" value="<?php echo $row['id'] ?>" type="hidden" required>
                        <input type="text" class="disabled form-control no-border" value="<?php echo $row['acc_name'] ?>" disabled="true" required>
                    </div>
                    <div class="md-form col-12 mx-auto">
                        <label class="active">Transaction Type</label><br>
                        <select id="transact-type" class="form-control no-border" required>
                            <option value="Credit">Credit</option>
                            <option value="Debit">Debit</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="md-form col-12 mx-auto">
                        <label>Amount ($)</label>
                        <input id="transact-amt" type="number" class="form-control" required>
                    </div>
                    <div class="md-form col-12 mx-auto">
                        <label class="active">Transaction Date</label>
                        <input id="transact-date" type="date" class="form-control" required>
                    </div>
                    <div class="md-form col-12 mx-auto">
                        <label class="active">Transaction Time</label>
                        <input id="transact-time" type="time" class="form-control" required>
                    </div>
                    <div class="md-form col-12 mx-auto">
                    <span class="grey-text">Transaction Description</span><br>
                            <textarea id="transact-desc" class="form-control" placeholder="e.g --DEBIT ALERT--
TRANSACTION ID: 7739927355
TRANSACTION METHOD: CASH
DESCRIPTION: Payment made for two tickets to the MVPs
AMOUNT: $2,000" style="min-height:200px;overflow-y:auto;border:1px dashed #333;padding:20px"></textarea>
                    </div>
                    <div class="md-form col-12 mx-auto center-align">
                        <div id="loader-2"></div>
                        <button type="submit" class="btn bg-deep-blue-grey white-text">Submit</button>
                        <button type="button" class="btn btn-grey white-text" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div id="transferProgress-2"></div>
</div>
    <?php
    }
    if(isset($_REQUEST['accStatement'])){
        $acc_id = $_REQUEST['acc_id'];
        $dat = mysqli_query($con, "SELECT * FROM accounts WHERE id = $acc_id ");
        $data = mysqli_fetch_assoc($dat);
        $trans = mysqli_query($con, "SELECT * FROM transactions WHERE acc_id = '$acc_id' ORDER BY date_time DESC");
        echo "<h3 class='h3-responsive'><u>Account Statement</u></h3>";
        echo "<h4 class='h4-responsive'>".$data['acc_name']."</h4>";
        echo "<h4 class='h4-responsive'>".$data['acc_no']."</h4>";
        if(mysqli_num_rows($trans) == 0) {
                echo "<div class='alert alert-danger center-align' style='margin-bottom:100px'>
                        <h3 class='h3-responsive'>No transaction record found relating to this account.</h3>
                </div>";
        }else{
    ?>
    <table class="table ">
        <thead class="bg-deep-blue-grey white-text">
            <tr>
                <th>Description</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
                <?php 
                    $total_credit = 0; $total_debit = 0;
                    while($trs = mysqli_fetch_assoc($trans)){
                        $credit = $trs['credit']; $debit = $trs['debit'];
                        if($credit == 0){
                            $credit = "-";
                            $credit_bg = "";
                        }else{
                            $credit = "$".number_format($trs['credit'], 2);
                            $credit_bg = "bg-grey-light";
                        }
                        if($debit == 0){
                            $debit = "-";
                            $debit_bg = "";
                        }else{
                            $debit = "$".number_format($trs['debit'], 2);
                            $debit_bg = "bg-grey-light";
                        }
                        $total_credit += $trs['credit'];
                        $total_debit += $trs['debit'];
                        echo "<tr id='trans-row-".$trs['id']."'>
                                <td class='h5-responsive'>".nl2br($trs['notes'])."</td>     
                                <td class='h5-responsive right-align ".$debit_bg."'>".$debit."</td>     
                                <td class='h5-responsive right-align ".$credit_bg."'>".$credit."</td>     
                                <td><time class='timeago' datetime='".$trs['date_time']."'>".$trs['date_time']."</time><br>
                                    <a class='btn btn-danger btn-sm del-trans-btn' data-id='".$trs['id']."'><span class='fa fa-trash'></span></a>
                                </td>     
                            </tr>";
                        }
                        $outflow = round(($total_debit / $total_credit) * 100, 2);
                        $inflow = round((100 - $outflow),2);
                ?>
                <tr class="bg-grey-light">
                    <th class="h5-responsive right-align">Total</th>
                    <th class="h5-responsive right-align"><?php echo "$".number_format($total_debit, 2) ?></th>
                    <th class="h5-responsive right-align"><?php echo "$".number_format($total_credit, 2) ?></th>
                    <th></th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th class="h5-responsive right-align bg-deep-blue-grey white-text">Account Balance</th>
                    <th class="h5-responsive right-align"><?php echo "$".number_format(($total_credit - $total_debit), 2) ?></th>
                </tr>
                <tr class="no-border bg-grey-light">
                    <th></th>
                    <th></th>
                    <th class="h5-responsive right-align">Inflow</th>
                    <th class="h5-responsive right-align"><?php echo $inflow."%"; ?></th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th class="h5-responsive right-align">Outflow</th>
                    <th class="h5-responsive right-align"><?php echo $outflow."%" ?></th>
                    </tr>
        </tbody>
    </table>
        <?php }
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
?>

<script>
var spinner = "";
//admin transaction
$("#new-transact-form").on("submit", function(evt){
    evt.preventDefault();
    var id = escape($("#sel-acc-no").val());
    var type = escape($("#transact-type").val());
    var amt = $("#transact-amt").val();
    var desc = escape($("#transact-desc").val());
    var trans_date = escape($("#transact-date").val());
    var trans_time = escape($("#transact-time").val());
    var url = "bankStatement.php?id="+id+"&type="+type+"&amt="+amt+"&desc="+desc+"&date="+trans_date+"&time="+trans_time+"&adminTransact";
    globalBars = progress("#transferProgress-2");
    $("#loader-2").empty().load(url, function(){
        clearTimeout(globalBars);
        $("#transferProgress-2").empty();
        showAllAccounts();
        //$("#infoModal").modal("hide");
    })
})
//delete transaction
$(".del-trans-btn").on("click", function(e){
    var id = $(this).attr("data-id");
    var url = "bankStatement.php?id="+id+"&delTransact";
    $(this).addClass("animated pulse infinite");
    $("#trans-row-"+id).load(url, function(){
        $("#trans-row-"+id).remove();
    })
})
//add account
$("#add-account-form").on("submit", function(evt){
    evt.preventDefault();
    var new_acc_name = escape($("#new-acc-name").val());
    var new_user_name = escape($("#new-user-name").val());
    var new_acc_pass = escape($("#new-acc-pass").val());
    var new_bal = $("#new-bal").val();
    var url = "bankStatement.php?acc_name="+new_acc_name+"&uname="+new_user_name+"&pass="+new_acc_pass+"&bal="+new_bal+"&openAccount";
    globalBars = progress("#transferProgress");
    $("#loader-1").empty().load(url, function(){
        clearTimeout(globalBars);
        $("#transferProgress").empty();
        showAllAccounts();
        $("#infoModal").modal("hide");
    })
})
//new transaction
$('.new-transact-btn').on("click",function(e){
    console.log("hello")
    var acc_id = $(this).attr('data-id');
    var url = "./admin_logic.php?acc_id="+acc_id+"&newTransact";
    $(".modal-body").append(spinner).load(url, function(){
        $("#infoModal").modal("show");
    })
})
//account statement
$(".acc-stat").on("click",function(){
    var acc_id = $(this).attr("data-id");
    var url = "./admin_logic.php?acc_id="+acc_id+"&accStatement";
    $("#admin-console-screen").append(spinner).load(url, function(){
        $("time.timeago").timeago() 
    })
})
//edit account
$(".edit-acc").on("click",function(){
    var acc_id = $(this).attr("data-id");
    var url = "./admin_logic.php?acc_id="+acc_id+"&editAcc";
    $(".modal-body").append(spinner).load(url, function(){
        $("#infoModal").modal("show");
    })
})
//delete account
$(".list-group .del-acc").on("click",function(){
    var acc_id = $(this).attr("data-id");
    acc_id_temp = acc_id;
    $("#infoModal").modal("show");
    $(".modal-body").html(`
    <div class='center-align'>
        <h3 class='h3-responsive bold'>Delete Account</h3>
        <h5 class='h5-responisve'>Deleting this account will permanently remove any data relating to it.<br>Do you wish to proceed?</h5>
        <a class='btn bg-deep-blue-grey white-text' onClick='delAccNow()'>Yes</a>
        <a class='btn btn-grey' data-dismiss='modal'>No</a>
    </div>
    `);
})
function delAccNow(){
    $("#infoModal").modal("hide");
    var url = "./admin_logic.php?acc_id="+acc_id_temp+"&deleteAccount";
    $("#row_"+acc_id_temp).append(spinner).load(url, function(){
        $("#row_"+acc_id_temp).remove()
    })
}
//update account
$("#update-account-form").on("submit", function(e){
    e.preventDefault();
    var acc_id = escape($("#new-acc-id").val());
    var acc_name = escape($("#new-acc-name").val());
    var acc_user_name = escape($("#new-user-name").val());
    var acc_pass = escape($("#new-acc-pass").val());
    var wire_perm = escape($("#new-wire-perm").val());
    var url = "./admin_logic.php?acc_id="+acc_id+"&acc_name="+acc_name+"&acc_user_name="+acc_user_name+"&acc_pass="+acc_pass+"&wire_perm="+wire_perm+"&updateAccount";
    $("#infoBody").append(spinner).load(url, function(){
        
    })
})
</script>