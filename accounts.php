<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Phoenix Express - My Account</title>
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
    <body style="overflow-x:hidden">
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
            //get ledger balance
            $ledg = "SELECT * FROM transactions WHERE acc_id = $user_id ;";
            $ledger = mysqli_query($con, $ledg);
            $ledgerBal = 0; $credit = 0; $debit = 0;
            while($ledgers = mysqli_fetch_assoc($ledger)){
                $credit += $ledgers['credit'];
                $debit += $ledgers['debit'];
            }
            $ledgerBal = $credit - $debit;
            ?>
    <header class="container-fluid bg-white">
            <nav class="navbar-expand-lg bg-white">
                <div class="row bg-white">
                    <div class="col-xs-12 col-sm-12 col-md-3 mx-auto center-align">
                            <button style="left:10px;top:10px;position:absolute" class="no-border navbar-toggler btn-outline-grey" type="button" onClick='$("#navbarSupportedContent").collapse("toggle")' aria-expanded="false" aria-label="Toggle navigation">
                                <span class="fa fa-bars grey-text"></span>
                            </button>
                            <a class="navbar-brand" href="./">
                                <img src="../../assets/img/phoenixExpress.png" style="width:150px">
                            </a>
                            
                            <button style="right:10px;top:10px;position:absolute" class="sign-out-btn no-border navbar-toggler btn-outline-grey" type="button">
                                <span class="fa fa-sign-out grey-text"></span>
                            </button>
                        </div>
                    </div>
            </nav>
                <nav class="navbar navbar-expand-md bg-deep-blue-grey row">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto navbar-list">
                            <li class="nav-item dropdown">    
                            <a class="nav-link disabled white-text capitalize transparent no-shadow">
                            My Account
                            </a></li>
                            <li class="nav-item dropdown">
                            <a href="../../transfers/<?php echo $user_id ?>/<?php echo $token ?>" class="nav-link white-text capitalize transparent no-shadow">
                                Transfers
                            </a></li>
                            <li class="nav-item dropdown">
                            <a href="../../history/<?php echo $user_id ?>/<?php echo $token ?>" class="nav-link white-text capitalize transparent no-shadow">
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
                <h2 class="black-text h2-responsive">Account Summary</h2>
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
                <div class="card-body" style="background-image:url('../../assets/img/icon-360-money-market.png');background-repeat:no-repeat;background-position:right;background-attachment:absolute">
                    <h3 class="deep-blue-text h3-responsive bold">Let's help you meet<br>your saving goals</h3>
                    <p class="deep-blue-text">Setup an automated<br>savings plan today</p>
                    <a class="blue-text">Learn More</a>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 card pad-0" style="margin-top:50px">
                <div class="card-header bg-light-blue-grey" data-toggle="collapse" data-target="#accDetaCOl">
                    <b class="white-text">Account Details</b><span class="white-text h5-responsive" style="right:10px;position:absolute"><?php echo $acc['acc_name'] ?></span>
                </div>
                <div id="accDetaCOl" class="card-body collapse show">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Rate</th>
                                <th>Interest YID</th>
                                <th>Interest <?php echo date("Y")-1 ?></th>
                                <th>Current Balance</th>
                                <th>Available Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="h5-responsive">4.00%</td>
                                <td class="h5-responsive">$0.00</td>
                                <td class="h5-responsive">$0.00</td>
                                <td class="h5-responsive"><?php echo "$".number_format($acc['acc_bal'], 2) ?></td>
                                <td class="h5-responsive"><?php echo "$".number_format($ledgerBal, 2) ?></td>
                                </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="col-xs-12 col-sm-12 col-md-12 card pad-0" style="margin-top:50px;overflow-x:hidden">
                <div class="card-header bg-light-blue-grey" data-toggle="collapse" data-target="#bankStatementCOl">
                    <b class="white-text">Bank Statement</b>
                </div>
                <div id="bankStatementCOl" class="card-body collaspe show">
                    <h2 class="h2-responsive">Request Bank Statement</h2>
                    <p>Requesting for your bank statement is easy, just select the range of days below and click the</p>
                    <form id="statement-form" class="container">
                        <div class="row">
                            <div class="md-form col-xs-10 col-sm-6 mx-auto">
                                <label class="active">Start Date</label>
                                <input class="form-control date-selector-1" type="date" required> 
                            </div>
                            <div class="md-form col-xs-10 col-sm-6 mx-auto">
                                <label class="active">End Date</label>
                                <input class="form-control date-selector-2" type="date" required> 
                            </div>
                            <div class="md-form col-xs-12 col-sm-12 center-align">
                                <span id="statement-info"></span><br>
                                <button class="btn bg-deep-blue-grey" type="submit">Request</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div id="transferProgress"></div>
            </div>
        </div><!--/row -->
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
</body>
</html>
</html>