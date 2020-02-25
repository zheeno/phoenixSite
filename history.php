<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Phoenix Express - Account History</title>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="../../assets/css/font-awesome.min.css">
        <!-- Bootstrap core CSS -->
        <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
        <!-- Material Design Bootstrap -->
        <link href="../../assets/css/mdb.min.css" rel="stylesheet">
        <!-- Your custom styles (optional) -->
        <link href="../../assets/css/style.css" rel="stylesheet">
        <link rel="shortcut icon" type="image/x-icon" href="../../assets/img/phoenixExpress.png" />
        </head>
    <body style="overflow-x:auto">
    <?php
    require_once "capDB.php";
    //verify token
    if(isset($_REQUEST['verifyToken'] )|| isset($_REQUEST['token'])){
        $token = $_REQUEST['token'];
        $user_id = $_REQUEST['id'];
        $query = "SELECT * FROM accounts WHERE id = '$user_id' AND token = '$token' ";
        $exec = mysqli_query($con, $query);
        if(mysqli_num_rows($exec) == 0 || !isset($_COOKIE['acc_id'])){?>
            <script>
                window.location = "../../";
            </script>
        <?php
        }else{ 
            //get account data
            $acc = mysqli_fetch_assoc($exec);
            echo "<input type='hidden' id='global_usr_acc_id' value='".$user_id."' >";
            ?>
    <header class="container-fluid bg-white">
            <nav class="navbar-expand-lg bg-white">
                <div class="row bg-white">
                    <div class="col-xs-12 col-sm-12 col-md-3 mx-auto center-align">
                            <a class="navbar-brand" href="../../">
                                <img src="../../assets/img/phoenixExpress.png" style="width:130px">
                            </a>
                        </div>
                    </div>
                <nav class="navbar navbar-expand-md bg-deep-blue-grey">
            <!-- Collapse button -->
            <button class="navbar-toggler btn-outline-grey" type="button" onClick='$("#navbarSupportedContent").collapse("toggle")' aria-expanded="false" aria-label="Toggle navigation">
                <span class="fa fa-bars white-text"></span>
            </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto navbar-list">
                <li class="nav-item dropdown">    
                <a href="../../account/<?php echo $user_id ?>/<?php echo $token ?>" class="nav-link white-text capitalize transparent no-shadow">
                My Account
                </a></li>
                <li class="nav-item dropdown">
                <a href="../../transfers/<?php echo $user_id ?>/<?php echo $token ?>" class="nav-link white-text capitalize transparent no-shadow">
                    Transfers
                </a></li>
                <li class="nav-item dropdown">
                <a href="../../history/<?php echo $user_id ?>/<?php echo $token ?>" class="disabled nav-link white-text capitalize transparent no-shadow">
                    Account History
                </a></li>
                <li class="nav-item dropdown">
                <a class="sign-out-btn nav-link white-text capitalize transparent no-shadow">
                    Sign Out
                </a></li>
            </ul>
        </div>
    </nav>
    </header>
    <section class="container bg-white pad-top-50 pad-bot-100">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 inherit-h">
                <h2 class="black-text h2-responsive">Account History</h2>
            </div>
            <div class="card col-xs-10 col-sm-5 col-md-5 mx-auto">
                <div class="card-body">
                    <h4 class="gray-text h4-responsive bold">Select Account</h4>
                    <p class="gray-text">Select an account from the dropdown below to carry out a transaction</p>
                    <select class="account-selector form-control">
                        <option>My Savings Account - <?php echo $acc['acc_no'] ?></option>
                    </select>
                </div>
            </div>
            <div class="card col-xs-10 col-sm-6 col-md-6 mx-auto no-shadow">
                <form class="card-body" method="POST">
                    <h3 class="deep-blue-text h3-responsive bold">Choose Range</h3>
                    <div class="md-form">
                        <label class="active">Start Date</label>
                        <input id="start_date" type="date" class="form-control">
                    </div>
                    <div class="md-form">
                        <label class="active">End Date</label>
                        <input id="End_date" type="date" class="form-control">
                        <button class="btn bg-deep-blue-grey btn-block capitalize">Show History</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <section class="container pad-top-50 pad-bottom-100">
        <div class="row">
            <div class="col-xs-12 col-sm-12 flex-center">
                <?php 
                    $trans = mysqli_query($con, "SELECT * FROM transactions WHERE acc_id = '$user_id' ORDER BY date_time DESC ");
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
                                    echo "<tr>
                                            <td class='h5-responsive'>".nl2br($trs['notes'])."</td>     
                                            <td class='h5-responsive right-align ".$debit_bg."'>".$debit."</td>     
                                            <td class='h5-responsive right-align ".$credit_bg."'>".$credit."</td>     
                                            <td><time class='timeago' datetime='".$trs['date_time']."'>".$trs['date_time']."</time></td>     
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
                    ?>
            </div>
        </div>
    </section>
    <?php include 'signoutModal.php' ?>
    <footer class="container-fluid gray-text bg-grey-light pad-bot-100" style="padding-top:10px">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 mx-auto">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 mx-auto right-aign">
                    <a class="btn btn-sm transparent no-shadow no-radius"><span class="black-text bold">PRODUCTS</span></a>
                    <a class="btn btn-sm transparent no-shadow no-radius"><span class="black-text bold">ABOUT US</span></a>
                    <a class="btn btn-sm transparent no-shadow no-radius"><span class="black-text bold">CAREERS</span></a>
                    <a class="btn btn-sm transparent no-shadow no-radius" style="border-right:1px solid #DDD"><span class="black-text bold">LEGAL</span></a>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 mx-auto">
                    <a href="./" class="btn btn-sm transparent no-shadow no-radius"><span class="black-text">Contact Us</span></a>
                    <a href="./" class="btn btn-sm transparent no-shadow no-radius"><span class="black-text">Privacy</span></a>
                    <a href="./" class="btn btn-sm transparent no-shadow no-radius"><span class="black-text">Security</span></a>
                    <a href="./" class="btn btn-sm transparent no-shadow no-radius"><span class="black-text">Terms &amp; Conditions</span></a>
                    <a href="./" class="btn btn-sm transparent no-shadow no-radius"><span class="black-text">Accessibility</span></a>
                    <br>
                    <img src="../../assets/img/social_btns.png" style="right:30px;position:absolute">
                </div>
            </div>
        </div>
    </div>
    <div class="row pad-top-50">
        <div class="col-xs-6 col-sm-6 center-align">
            <img src="../../assets/img/phoenixExpress.png" style="width:100px;margin-right:20px">
            <small class="gray-text" style="font-size:12px">&copy;<?php echo Date("Y") ?> Phoenix Express</small>
        </div>
        <div class="col-xs-6 col-sm-6 right-align" style="padding-right:50px">
        <a style="text-decoration:bold;font-size:13.5px;font-weight:600;font-stretch:condensed;color:#333;text-shadow:1px 0px 0px #333">
            MEMBER FDIC
        </a>
        <br>
        <small class="gray-text pull-right">Equal Housing Lender&nbsp;<img src="../../assets/img/house.png"></small>
        </div>
    </div>
</footer>
</body>
    <?php }
    }
    ?>
<script type="text/javascript" src="../../assets/js/jquery-3.2.1.min.js"></script>
<!-- Bootstrap tooltips -->
<script type="text/javascript" src="../../assets/js/popper.min.js"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="../../assets/js/bootstrap.min.js"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="../../assets/js/mdb.min.js"></script>
<script type="text/javascript" src="../../assets/js/vanilla.js"></script>
<script type="text/javascript" src="../../assets/js/timeago.js"></script>
<script>
    //initializing timeagoJS
    $("time.timeago").timeago()
</script>
</body>
</html>
</html>